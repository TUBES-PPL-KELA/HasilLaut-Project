<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\InventoryBatch;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Midtrans\Config as MidtransConfig;
use Midtrans\Snap;

class PaymentController extends Controller
{
    public function __construct()
    {
        // Konfigurasi Midtrans KHUSUS Mode Sandbox (PBI-15)
        MidtransConfig::$serverKey    = config('midtrans.server_key');
        MidtransConfig::$isProduction = false;
        MidtransConfig::$isSanitized  = config('midtrans.is_sanitized', true);
        MidtransConfig::$is3ds        = config('midtrans.is_3ds', true);
    }

    /**
     * AC 1: Menghasilkan Snap Token Midtrans saat tombol "Buat Pesanan" ditekan
     * Route: POST /orders/{orderNumber}/pay
     */
    public function createSnapToken(Request $request, $orderNumber)
    {
        $order = DB::table('orders')->where('order_number', $orderNumber)->first();

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Pesanan tidak ditemukan.',
            ], 404);
        }

        if ($order->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Pesanan ini sudah diproses atau bukan berstatus pending.',
            ], 400);
        }

        $items = DB::table('order_items')
            ->join('product', 'order_items.product_id_product', '=', 'product.id_product')
            ->where('order_items.orders_id_orders', $order->id_orders)
            ->select('order_items.*', 'product.name as product_name')
            ->get();

        $user = DB::table('users')->where('id_users', $order->users_id_users)->first();

        $itemDetails = [];
        foreach ($items as $item) {
            $itemDetails[] = [
                'id'       => (string) $item->product_id_product,
                'price'    => (int) round($item->price),
                'quantity' => (int) round($item->quantity),
                'name'     => substr($item->product_name ?? 'Produk HasilLaut', 0, 50),
            ];
        }

        if (!empty($order->shipping_cost) && $order->shipping_cost > 0) {
            $itemDetails[] = [
                'id'       => 'SHIPPING',
                'price'    => (int) round($order->shipping_cost),
                'quantity' => 1,
                'name'     => 'Ongkos Kirim Kurir Internal',
            ];
        }

        $params = [
            'transaction_details' => [
                'order_id'     => $order->order_number,
                'gross_amount' => (int) round($order->total_amount),
            ],
            'item_details' => $itemDetails,
            'customer_details' => [
                'first_name' => $user->name ?? 'Pembeli HasilLaut',
                'email'      => $user->email ?? 'pembeli@hasillaut.id',
            ],
        ];

        try {
            $snapToken = Snap::getSnapToken($params);

            // Catat atau perbarui status di tabel payments menjadi 'pending'
            DB::table('payments')->updateOrInsert(
                ['orders_id_orders' => $order->id_orders],
                [
                    'payment_gateway_trx_id' => $snapToken,
                    'payment_method'         => $request->input('payment_channel', 'midtrans'),
                    'amount'                 => $order->total_amount,
                    'status'                 => 'pending',
                ]
            );

            return response()->json([
                'success'      => true,
                'snap_token'   => $snapToken,
                'order_number' => $order->order_number,
            ]);
        } catch (\Exception $e) {
            Log::error('Midtrans Snap Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat Snap Token: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * AC 2, AC 3, AC 4: Menerima Webhook Midtrans, Validasi Signature Key,
     * Mengubah Status Pesanan, dan Memotong Stok Otomatis (FIFO)
     * Route: POST /api/midtrans/webhook
     */
    public function handleWebhook(Request $request)
    {
        $orderId           = $request->input('order_id', '');
        $statusCode        = $request->input('status_code', '');
        $grossAmount       = $request->input('gross_amount', '');
        $signatureKey      = $request->input('signature_key', '');
        $transactionStatus = $request->input('transaction_status', '');
        $fraudStatus       = $request->input('fraud_status', 'accept');
        $paymentType       = $request->input('payment_type', 'midtrans');
        $transactionId     = $request->input('transaction_id');

        // 1. Validasi Signature Key SHA-512 dari Midtrans
        $serverKey = config('midtrans.server_key');
        $expectedSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        if (!hash_equals($expectedSignature, $signatureKey)) {
            return response()->json(['message' => 'Invalid Signature Key'], 403);
        }

        DB::beginTransaction();

        try {
            $order = DB::table('orders')
                ->where('order_number', $orderId)
                ->lockForUpdate()
                ->first();

            if (!$order) {
                DB::rollBack();
                return response()->json(['message' => 'Order not found'], 404);
            }

            // Jika sudah lunas sebelumnya, hentikan agar stok tidak terpotong dua kali
            if ($order->status === 'lunas') {
                DB::commit();
                return response()->json(['message' => 'Order already processed'], 200);
            }

            $isPaid = ($transactionStatus === 'settlement') ||
                      ($transactionStatus === 'capture' && $fraudStatus === 'accept');

            // AC 2 & AC 3: Status 'settlement' -> Ubah jadi 'lunas' & Potong Stok FIFO di inventory_batches
            if ($isPaid && $order->status === 'pending') {
                DB::table('orders')
                    ->where('id_orders', $order->id_orders)
                    ->update(['status' => 'lunas']);

                DB::table('payments')->updateOrInsert(
                    ['orders_id_orders' => $order->id_orders],
                    [
                        'payment_gateway_trx_id' => $transactionId ?? $orderId,
                        'payment_method'         => $paymentType,
                        'amount'                 => $grossAmount ?: $order->total_amount,
                        'status'                 => 'lunas',
                    ]
                );

                // Potong stok di inventory_batches dengan metode FIFO
                $this->deductStockFIFO($order);

                DB::commit();
                return response()->json([
                    'message' => 'Status pesanan diubah menjadi lunas dan stok FIFO berhasil dipotong.',
                ], 200);
            }

            // AC 4: Status 'expire', 'cancel', atau 'deny' -> Batalkan pesanan
            if (in_array($transactionStatus, ['expire', 'cancel', 'deny'])) {
                DB::table('orders')
                    ->where('id_orders', $order->id_orders)
                    ->update(['status' => 'dibatalkan']);

                DB::table('payments')->updateOrInsert(
                    ['orders_id_orders' => $order->id_orders],
                    [
                        'payment_gateway_trx_id' => $transactionId ?? $orderId,
                        'payment_method'         => $paymentType,
                        'amount'                 => $grossAmount ?: $order->total_amount,
                        'status'                 => $transactionStatus === 'expire' ? 'expire' : 'batal',
                    ]
                );

                DB::commit();
                return response()->json(['message' => 'Pesanan dibatalkan.'], 200);
            }

            DB::commit();
            return response()->json(['message' => 'Webhook received'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Midtrans Webhook Error: ' . $e->getMessage());

            return response()->json(['message' => 'Internal Server Error'], 500);
        }
    }

    /**
     * AC 3: Pemotongan Stok Otomatis (FIFO) pada tabel inventory_batches
     * Mengambil batch dengan received_date paling lama terlebih dahulu.
     */
    protected function deductStockFIFO(object $order): void
    {
        $orderItems = DB::table('order_items')
            ->where('orders_id_orders', $order->id_orders)
            ->get();

        foreach ($orderItems as $item) {
            $remainingToDeduct = (float) $item->quantity;

            // Ambil batch stok milik produk ini yang masih > 0, urutkan dari tanggal masuk terlama (FIFO)
            $batches = InventoryBatch::where('product_id_product', $item->product_id_product)
                ->where('stock', '>', 0)
                ->orderBy('received_date', 'asc')
                ->orderBy('id_inventory_batches', 'asc')
                ->lockForUpdate()
                ->get();

            foreach ($batches as $batch) {
                if ($remainingToDeduct <= 0) {
                    break;
                }

                $available = (float) $batch->stock;
                $deducted  = min($available, $remainingToDeduct);

                // Kurangi stok di batch ini
                $batch->stock = $available - $deducted;
                $batch->save();

                $remainingToDeduct -= $deducted;

                // Jika tabel inventory_logs ada di database, catat riwayat pemotongan FIFO-nya
                if (Schema::hasTable('inventory_logs')) {
                    $logCols = Schema::getColumnListing('inventory_logs');
                    $logData = [];

                    if (in_array('product_id_product', $logCols))   $logData['product_id_product']   = $item->product_id_product;
                    if (in_array('id_inventory_batches', $logCols)) $logData['id_inventory_batches'] = $batch->id_inventory_batches;
                    if (in_array('users_id_users', $logCols))       $logData['users_id_users']       = $order->users_id_users;
                    if (in_array('quantity_changed', $logCols))     $logData['quantity_changed']     = -$deducted;
                    if (in_array('stock_out', $logCols))            $logData['stock_out']            = $deducted;
                    if (in_array('reason', $logCols))               $logData['reason']               = "Pemotongan otomatis (FIFO) Pesanan #{$order->order_number}";
                    if (in_array('created_at', $logCols))           $logData['created_at']           = now();
                    if (in_array('updated_at', $logCols))           $logData['updated_at']           = now();

                    if (!empty($logData)) {
                        DB::table('inventory_logs')->insert($logData);
                    }
                }
            }
        }
    }
}