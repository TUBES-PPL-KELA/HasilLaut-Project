<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\PembeliDetail;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\FlashSaleItem;
use App\Models\InventoryBatch;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema; // === [PERBAIKAN #1 - BARIS 14]: Tambah import Schema ===
use Illuminate\Support\Str;
use Midtrans\Config;
use Midtrans\Snap;
use App\Services\StockService;

class CheckoutController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $cart = Cart::with(['cartItems.product', 'cartItems.flashSaleItem'])->where('users_id_users', $userId)->first();
        $pembeliDetail = PembeliDetail::where('users_id_users', $userId)->first();

        $subtotal = 0;
        if ($cart && $cart->cartItems) {
            foreach ($cart->cartItems as $item) {
                if ($item->product) {
                    $subtotal += ($item->quantity * $item->effective_price);
                }
            }
        }

        $shippingCost = 15000;
        $serviceFee = 2500;
        $totalAmount = $subtotal + $shippingCost + $serviceFee;

        return view('pembeli.checkout.index', compact('cart', 'pembeliDetail', 'subtotal', 'shippingCost', 'serviceFee', 'totalAmount'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $userId = $user->id_users;

        $method = $request->input('payment_method', 'qris');

        $pembeliDetail = PembeliDetail::where('users_id_users', $userId)->first();
        if (!$pembeliDetail) {
            $pembeliDetail = (object) [
                'name' => $user ? $user->name : 'Pelanggan HasilLaut',
                'phone_number' => '081234567890',
                'address' => 'Jl. Pantai Pangandaran No. 12, Kec. Pangandaran',
                'city' => 'Pangandaran'
            ];
        }

        $cart = Cart::with(['cartItems.product', 'cartItems.flashSaleItem'])->where('users_id_users', $userId)->first();
        if (!$cart || $cart->cartItems->isEmpty()) {
            return response()->json(['error' => 'Keranjang belanjaan Anda kosong.'], 422);
        }

        DB::beginTransaction();
        try {
            // 1. Validasi stok & kuota Flash Sale terlebih dahulu sebelum membuat Order
            foreach ($cart->cartItems as $item) {
                if (!is_null($item->flash_sale_item_id)) {
                    $fsItem = FlashSaleItem::find($item->flash_sale_item_id);
                    if (!$fsItem || $fsItem->stock < $item->quantity) {
                        DB::rollBack();
                        return response()->json(['error' => 'Kuota Flash Sale untuk produk ini sudah habis atau tidak mencukupi.'], 422);
                    }
                } else {
                    $availableStock = $item->product->total_stock ?? $item->product->stock ?? 0;
                    if ($availableStock < $item->quantity) {
                        DB::rollBack();
                        return response()->json(['error' => 'Maaf, stok produk segar sudah habis atau tidak mencukupi.'], 422);
                    }
                }
            }

            // 2. Hitung subtotal & total jika semua stok aman
            $subtotal = 0;
            foreach ($cart->cartItems as $item) {
                $subtotal += $item->quantity * $item->effective_price;
            }
            $shippingCost = 15000;
            $serviceFee = 2500;
            $totalAmount = $subtotal + $shippingCost + $serviceFee;

            // 3. Buat header Order (sesuai kolom asli tabel orders)
            $order = Order::create([
                'order_number'        => 'ORD-' . date('Ymd') . '-' . strtoupper(Str::random(4)),
                'is_pangandaran_area' => 1,
                'subtotal_amount'     => $subtotal,
                'shipping_cost'       => $shippingCost,
                'total_amount'        => $totalAmount,
                'users_id_users'      => $userId,
            ]);

            foreach ($cart->cartItems as $item) {
                OrderItem::create([
                    'quantity'           => $item->quantity,
                    'price'              => $item->effective_price,
                    'orders_id_orders'   => $order->id_orders,
                    'product_id_product' => $item->product->id_product,
                    'flash_sale_item_id' => $item->flash_sale_item_id ?? null,
                ]);
            }

            Payment::create([
                'orders_id_orders' => $order->id_orders,
                'payment_method'   => match ($method) {
                    'cod'   => 'COD',
                    'qris'  => 'QRIS',
                    'va'    => 'Virtual Account',
                    default => 'Midtrans Snap',
                },
                'amount'                 => $totalAmount,
                'status'                 => 'pending',
                'payment_gateway_inv_id' => '-',
            ]);

            if ($method === 'cod') {
                $cart->cartItems()->delete();
                $cart->delete();
                DB::commit();

                return response()->json([
                    'status'       => 'success',
                    'cod'          => true,
                    'order_number' => $order->order_number,
                    'redirect'     => route('pembeli.katalog'),
                ]);
            }

            Config::$serverKey = config('midtrans.server_key');
            Config::$isProduction = config('midtrans.is_production', false);
            Config::$isSanitized = config('midtrans.is_sanitized', true);
            Config::$is3ds = config('midtrans.is_3ds', true);

            $params = [
                'transaction_details' => [
                    // === [PERBAIKAN #2 - BARIS 127]: Hapus . '-' . time() agar order_id cocok saat webhook masuk ===
                    'order_id'     => $order->order_number,
                    'gross_amount' => (int) round($totalAmount),
                ],
                'item_details' => [
                    [
                        'id'       => 'PROD-TOTAL',
                        'price'    => (int) round($totalAmount),
                        'quantity' => 1,
                        'name'     => 'Total Belanja HasilLaut (' . $order->order_number . ')',
                    ]
                ],
                'customer_details' => [
                    'first_name' => $pembeliDetail->name ?? ($user->name ?? 'Pelanggan HasilLaut'),
                    'email'      => $user->email ?? 'pembeli@hasillaut.test',
                    'phone'      => $pembeliDetail->phone_number ?? '08123456789',
                ]
            ];

            $params['enabled_payments'] = match ($method) {
                'qris'  => ['other_qris', 'gopay'],
                'va'    => ['bca_va', 'echannel', 'bri_va'],
                default => ['other_qris', 'bca_va'],
            };

            if (!config('midtrans.is_production')) {
                Config::$curlOptions = [
                    CURLOPT_SSL_VERIFYPEER => false,
                    CURLOPT_SSL_VERIFYHOST => 0,
                    CURLOPT_HTTPHEADER     => ['Accept: application/json'],
                ];
            }

            try {
                \Log::info('SEBELUM snap');
                $snapToken = Snap::getSnapToken($params);
                \Log::info('SESUDAH snap', ['token' => $snapToken]);
            } catch (\Exception $snapEx) {
                // === [PERBAIKAN #3 - BARIS 164]: Hanya pakai dummy token di 'testing', jangan di 'local' ===
                if (app()->environment('testing')) {
                    $snapToken = 'DUSK-TEST-SNAP-' . Str::random(16);
                } else {
                    throw $snapEx;
                }
            }

            $cart->cartItems()->delete();
            $cart->delete();

            DB::commit();

            return response()->json([
                'status'       => 'success',
                'snap_token'   => $snapToken,
                'order_number' => $order->order_number
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Terjadi kesalahan sistem: ' . $e->getMessage()], 500);
        }
    }

    public function webhook(Request $request)
    {
        $payload = $request->getContent();
        $notification = json_decode($payload);

        if (
            !$notification ||
            empty($notification->order_id) ||
            empty($notification->status_code) ||
            !isset($notification->gross_amount) ||
            empty($notification->signature_key)
        ) {
            \Log::warning('Midtrans webhook invalid payload', ['payload' => $payload]);
            return response()->json(['message' => 'Invalid payload'], 400);
        }

        $orderId = (string) $notification->order_id;
        $statusCode = (string) $notification->status_code;
        $grossAmount = (string) $notification->gross_amount;
        $reqSignature = (string) $notification->signature_key;

        $candidateKeys = array_values(array_unique(array_filter([
            config('midtrans.signature_secret_key'),
            config('midtrans.server_key'),
        ])));

        $signatureValid = false;
        foreach ($candidateKeys as $key) {
            $expectedSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $key);
            if (hash_equals($expectedSignature, $reqSignature)) {
                $signatureValid = true;
                break;
            }
        }

        if (!$signatureValid) {
            \Log::warning('Midtrans webhook invalid signature', [
                'order_id'               => $orderId,
                'status_code'            => $statusCode,
                'gross_amount'           => $grossAmount,
                'using_separate_sig_key' => config('midtrans.signature_secret_key') !== config('midtrans.server_key'),
            ]);
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $transactionStatus = $notification->transaction_status ?? '';
        $fraudStatus = $notification->fraud_status ?? '';

        \Log::info('Midtrans webhook received', [
            'order_id'           => $orderId,
            'transaction_status' => $transactionStatus,
            'fraud_status'       => $fraudStatus,
        ]);

        DB::beginTransaction();
        try {
            $cleanOrderId = preg_replace('/-\d+$/', '', $orderId);
            $order = Order::where('order_number', $orderId)
                ->orWhere('order_number', $cleanOrderId)
                ->first();

            if (!$order) {
                DB::rollBack();
                \Log::warning('Midtrans webhook order not found', ['order_id' => $orderId]);
                return response()->json(['message' => 'Order not found'], 404);
            }

            $payment = Payment::where('orders_id_orders', $order->id_orders)->first();
            if (!$payment) {
                DB::rollBack();
                \Log::warning('Midtrans webhook payment not found', ['order_id' => $orderId]);
                return response()->json(['message' => 'Payment not found'], 404);
            }

            if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
                if ($transactionStatus == 'capture' && $fraudStatus == 'challenge') {
                    $payment->status = 'pending';
                } else {
                    if ($payment->status !== 'paid') {
                        $payment->status = 'paid';
                        $payment->payment_gateway_inv_id = $notification->transaction_id ?? $orderId;

                        $orderUpdates = [];
                        if (Schema::hasColumn('orders', 'status_pesanan')) {
                            $orderUpdates['status_pesanan'] = 'Lunas';
                        }
                        if (Schema::hasColumn('orders', 'status')) {
                            $orderUpdates['status'] = 'lunas';
                        }
                        if (!empty($orderUpdates)) {
                            DB::table('orders')
                                ->where('id_orders', $order->id_orders)
                                ->update($orderUpdates);
                        }

                        $orderItems = OrderItem::where('orders_id_orders', $order->id_orders)->get();

                        foreach ($orderItems as $item) {
                            if ($item->flash_sale_item_id) {
                                $flashSaleItem = FlashSaleItem::find($item->flash_sale_item_id);
                                if ($flashSaleItem) {
                                    $deduct = min($flashSaleItem->stock, $item->quantity);
                                    $flashSaleItem->decrement('stock', $deduct);
                                }
                                continue;
                            }

                            StockService::deductFifo($item->product_id_product, $item->quantity);
                        }
                    }
                }
            } else if ($transactionStatus == 'expire') {
                $payment->status = 'expired';

                $orderUpdates = [];
                if (Schema::hasColumn('orders', 'status_pesanan')) {
                    $orderUpdates['status_pesanan'] = 'Dibatalkan';
                }
                if (Schema::hasColumn('orders', 'status')) {
                    $orderUpdates['status'] = 'dibatalkan';
                }
                if (!empty($orderUpdates)) {
                    DB::table('orders')
                        ->where('id_orders', $order->id_orders)
                        ->update($orderUpdates);
                }
            } else if ($transactionStatus == 'cancel' || $transactionStatus == 'deny') {
                $payment->status = 'failed';

                $orderUpdates = [];
                if (Schema::hasColumn('orders', 'status_pesanan')) {
                    $orderUpdates['status_pesanan'] = 'Dibatalkan';
                }
                if (Schema::hasColumn('orders', 'status')) {
                    $orderUpdates['status'] = 'dibatalkan';
                }
                if (!empty($orderUpdates)) {
                    DB::table('orders')
                        ->where('id_orders', $order->id_orders)
                        ->update($orderUpdates);
                }
            } else if ($transactionStatus == 'pending') {
                $payment->status = 'pending';
            }

            $payment->save();

            DB::commit();
            \Log::info('Midtrans webhook processed successfully', [
                'order_id'       => $orderId,
                'payment_status' => $payment->status,
            ]);
            return response()->json(['message' => 'Webhook successfully processed']);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Midtrans webhook processing error', [
                'order_id' => $orderId,
                'error'    => $e->getMessage(),
            ]);
            return response()->json(['message' => 'Error processing webhook: ' . $e->getMessage()], 500);
        }
    }

    // === [PERBAIKAN #6 - BARIS 253]: Tambah method status() untuk menangani redirect ke /pesanan/status ===
    public function status()
    {
        $userId = Auth::id();

        $order = Order::with(['payment'])
            ->where('users_id_users', $userId)
            ->latest('id_orders')
            ->first();

        if (view()->exists('pembeli.pesanan.status')) {
            return view('pembeli.pesanan.status', compact('order'));
        }

        return redirect()->route('pembeli.katalog')
            ->with('success', 'Pesanan ' . ($order->order_number ?? '') . ' berhasil dibuat!');
    }
}