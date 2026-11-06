<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\PembeliDetail;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment; // <-- Tambahan untuk tabel pembayaran
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Midtrans\Config;    // <-- Tambahan untuk konfigurasi Midtrans
use Midtrans\Snap;      // <-- Tambahan untuk mengambil Snap Token

class CheckoutController extends Controller
{
    /**
     * Menampilkan halaman checkout dengan data rincian sesuai Figma.
     */
    public function index()
    {
        $user = auth()->user();
        $userId = $user ? $user->id_users : 1; 

        $cart = Cart::with('cartItems.product')->where('users_id_users', $userId)->first();
        $pembeliDetail = PembeliDetail::where('users_id_users', $userId)->first();

        // Kalkulasi subtotal produk
        $subtotal = 0;
        if ($cart && $cart->cartItems) {
            foreach ($cart->cartItems as $item) {
                if ($item->product) {
                    $subtotal += $item->quantity * $item->product->price;
                }
            }
        }

        // Biaya pengiriman dan biaya layanan sesuai desain Figma
        $shippingCost = 15000; 
        $serviceFee = 2500;
        $totalAmount = $subtotal + $shippingCost + $serviceFee;

        return view('pembeli.checkout.index', compact('cart', 'pembeliDetail', 'subtotal', 'shippingCost', 'serviceFee', 'totalAmount'));
    }

    /**
     * Memproses penyimpanan data checkout ke database dan menghasilkan Midtrans Snap Token.
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        $userId = $user ? $user->id_users : 1;

        $pembeliDetail = PembeliDetail::where('users_id_users', $userId)->first();
        if (!$pembeliDetail) {
            return response()->json(['error' => 'Silakan lengkapi alamat pengiriman terlebih dahulu.'], 422);
        }

        $cart = Cart::with('cartItems.product')->where('users_id_users', $userId)->first();
        if (!$cart || $cart->cartItems->isEmpty()) {
            return response()->json(['error' => 'Keranjang belanjaan Anda kosong.'], 422);
        }

        DB::beginTransaction();
        try {
            $subtotal = 0;
            foreach ($cart->cartItems as $item) {
                $subtotal += $item->quantity * $item->product->price;
            }
            $shippingCost = 15000;
            $serviceFee = 2500;
            $totalAmount = $subtotal + $shippingCost + $serviceFee;

            // 1. Simpan data pesanan ke tabel orders
            $order = Order::create([
                'order_number' => 'ORD-' . date('Ymd') . '-' . strtoupper(Str::random(4)),
                'is_pangandaran_area' => 1,
                'subtotal_amount' => $subtotal,
                'shipping_cost' => $shippingCost,
                'total_amount' => $totalAmount,
                'users_id_users' => $userId,
                'status_pesanan' => 'Menunggu Pembayaran',
            ]);

            // 2. Simpan detail produk ke tabel order_items
            foreach ($cart->cartItems as $item) {
                OrderItem::create([
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                    'orders_id_orders' => $order->id_orders,
                    'product_id_product' => $item->product->id_product,
                ]);
            }

            // 3. Catat entri awal ke tabel payments dengan status Pending
            Payment::create([
                'id_pesanan' => $order->id_orders,
                'metode_pembayaran' => 'Midtrans Snap',
                'jumlah_bayar' => $totalAmount,
                'status_pembayaran' => 'Pending',
            ]);

            // 4. Konfigurasi Midtrans
            Config::$serverKey = config('midtrans.server_key');
            Config::$isProduction = config('midtrans.is_production');
            Config::$isSanitized = config('midtrans.is_sanitized');
            Config::$is3ds = config('midtrans.is_3ds');

            // 5. Siapkan parameter payload untuk Midtrans Snap
            $params = [
                'transaction_details' => [
                    'order_id' => $order->order_number,
                    'gross_amount' => $totalAmount,
                ],
                'customer_details' => [
                    'first_name' => $user->nama ?? 'Pelanggan HasilLaut',
                    'email' => $user->email ?? 'pembeli@hasillaut.test',
                    'phone' => $pembeliDetail->phone_number ?? '08123456789',
                ]
            ];

            // 6. Minta Snap Token ke server Midtrans
            $snapToken = Snap::getSnapToken($params);

            // 7. Bersihkan keranjang belanja setelah order berhasil dibuat
            $cart->cartItems()->delete();
            $cart->delete();

            DB::commit();

            // 8. Kembalikan response JSON berisi snap_token agar frontend bisa memicu pop-up
            return response()->json([
                'status' => 'success',
                'snap_token' => $snapToken,
                'order_number' => $order->order_number
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Terjadi kesalahan sistem: ' . $e->getMessage()], 500);
        }
    }
    /**
     * Memproses Webhook/Notifikasi dari Midtrans.
     */
    public function webhook(Request $request)
    {
        // 1. Ambil payload dari Midtrans
        $payload = $request->getContent();
        $notification = json_decode($payload);

        if (!$notification) {
            return response()->json(['message' => 'Invalid payload'], 400);
        }

        // 2. Verifikasi Signature Key (Sangat Penting untuk Keamanan)
        $orderId = $notification->order_id;
        $statusCode = $notification->status_code;
        $grossAmount = $notification->gross_amount;
        $reqSignature = $notification->signature_key;

        $serverKey = config('midtrans.server_key');
        $mySignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        if ($mySignature !== $reqSignature) {
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        // 3. Proses Status Transaksi
        $transactionStatus = $notification->transaction_status;
        $fraudStatus = $notification->fraud_status ?? '';

        DB::beginTransaction();
        try {
            // Cari data Order dan Payment berdasarkan nomor pesanan
            $order = Order::where('order_number', $orderId)->first();
            if (!$order) {
                return response()->json(['message' => 'Order not found'], 404);
            }

            $payment = Payment::where('id_pesanan', $order->id_orders)->first();

            // 4. Update Status Pembayaran & Eksekusi FIFO
            if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
                if ($transactionStatus == 'capture' && $fraudStatus == 'challenge') {
                    $payment->status_pembayaran = 'Challenge';
                    $order->status_pesanan = 'Menunggu Verifikasi';
                } else {
                    $payment->status_pembayaran = 'Lunas';
                    $payment->payment_gateway_id = $notification->transaction_id; // Simpan ID Transaksi Midtrans
                    $payment->tgl_bayar = now();
                    
                    // Eksekusi jika sebelumnya status belum Dibayar
                    if ($order->status_pesanan !== 'Dibayar' && $order->status_pesanan !== 'Produk Disiapkan') {
                        $order->status_pesanan = 'Dibayar'; // Mengubah status agar masuk antrean Dashboard Admin
                        
                        // ==========================================
                        // LOGIKA PBI-02: PEMOTONGAN STOK FIFO
                        // ==========================================
                        $orderItems = OrderItem::where('orders_id_orders', $order->id_orders)->get();
                        
                        foreach ($orderItems as $item) {
                            $qtyToDeduct = $item->quantity;
                            
                            // Ambil batch stok dari yang paling lama (ASC)
                            $batches = \App\Models\InventoryBatch::where('product_id_product', $item->product_id_product)
                                ->where('stock_qty', '>', 0)
                                ->orderBy('received_date', 'asc')
                                ->get();
                                
                            foreach ($batches as $batch) {
                                if ($qtyToDeduct <= 0) break; // Berhenti jika kebutuhan stok sudah terpenuhi
                                
                                if ($batch->stock_qty >= $qtyToDeduct) {
                                    $batch->stock_qty -= $qtyToDeduct;
                                    $batch->save();
                                    $qtyToDeduct = 0;
                                } else {
                                    $qtyToDeduct -= $batch->stock_qty;
                                    $batch->stock_qty = 0;
                                    $batch->save();
                                }
                            }
                        }
                        // ==========================================
                    }
                }
            } else if ($transactionStatus == 'cancel' || $transactionStatus == 'deny' || $transactionStatus == 'expire') {
                $payment->status_pembayaran = 'Gagal';
                $order->status_pesanan = 'Dibatalkan';
            } else if ($transactionStatus == 'pending') {
                $payment->status_pembayaran = 'Pending';
                $order->status_pesanan = 'Menunggu Pembayaran';
            }

            $payment->save();
            $order->save();

            DB::commit();
            return response()->json(['message' => 'Webhook successfully processed']);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error processing webhook: ' . $e->getMessage()], 500);
        }
    }
}