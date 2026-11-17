<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\PembeliDetail;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\FlashSaleItem;
use App\Models\InventoryBatch;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Midtrans\Config;
use Midtrans\Snap;
use App\Services\StockService;

class CheckoutController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $userId = $user ? $user->id_users : 1; 

        $cart = Cart::with(['cartItems.product', 'cartItems.flashSaleItem'])->where('users_id_users', $userId)->first();
        $pembeliDetail = PembeliDetail::where('users_id_users', $userId)->first();

        $subtotal = 0;
        if ($cart && $cart->cartItems) {
            foreach ($cart->cartItems as $item) {
                if ($item->product) {
                    $subtotal += $item->quantity * $item->effective_price;
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
        $user = auth()->user();
        $userId = $user ? $user->id_users : 1;

        $pembeliDetail = PembeliDetail::where('users_id_users', $userId)->first();
        if (!$pembeliDetail) {
            return response()->json(['error' => 'Silakan lengkapi alamat pengiriman terlebih dahulu.'], 422);
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

                // 3. Baru buat header Order
                $order = Order::create([
                    'order_number'        => 'ORD-' . date('Ymd') . '-' . strtoupper(Str::random(4)),
                    'is_pangandaran_area' => 1,
                    'subtotal_amount'     => $subtotal,
                    'shipping_cost'       => $shippingCost,
                    'total_amount'        => $totalAmount,
                    'users_id_users'      => $userId,
                    'status_pesanan'      => 'Menunggu Pembayaran',
                ]);

            foreach ($cart->cartItems as $item) {
                OrderItem::create([
                    'quantity' => $item->quantity,
                    'price' => $item->effective_price,
                    'orders_id_orders' => $order->id_orders,
                    'product_id_product' => $item->product->id_product,
                    'flash_sale_item_id' => $item->flash_sale_item_id,
                ]);
            }

            Payment::create([
                'id_pesanan' => $order->id_orders,
                'metode_pembayaran' => 'Midtrans Snap',
                'jumlah_bayar' => $totalAmount,
                'status_pembayaran' => 'Pending',
            ]);

            Config::$serverKey = config('midtrans.server_key');
            Config::$isProduction = config('midtrans.is_production');
            Config::$isSanitized = config('midtrans.is_sanitized');
            Config::$is3ds = config('midtrans.is_3ds');

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

            $snapToken = Snap::getSnapToken($params);

            $cart->cartItems()->delete();
            $cart->delete();

            DB::commit();

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

    public function webhook(Request $request)
    {
        $payload = $request->getContent();
        $notification = json_decode($payload);

        if (!$notification) {
            return response()->json(['message' => 'Invalid payload'], 400);
        }

        $orderId = $notification->order_id;
        $statusCode = $notification->status_code;
        $grossAmount = $notification->gross_amount;
        $reqSignature = $notification->signature_key;

        $serverKey = config('midtrans.server_key');
        $mySignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        if ($mySignature !== $reqSignature) {
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $transactionStatus = $notification->transaction_status;
        $fraudStatus = $notification->fraud_status ?? '';

        DB::beginTransaction();
        try {
            $order = Order::where('order_number', $orderId)->first();
            if (!$order) {
                return response()->json(['message' => 'Order not found'], 404);
            }

            $payment = Payment::where('id_pesanan', $order->id_orders)->first();

            if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
                if ($transactionStatus == 'capture' && $fraudStatus == 'challenge') {
                    $payment->status_pembayaran = 'Challenge';
                    $order->status_pesanan = 'Menunggu Verifikasi';
                } else {
                    $payment->status_pembayaran = 'Lunas';
                    $payment->payment_gateway_id = $notification->transaction_id;
                    $payment->tgl_bayar = now();

                    if ($order->status_pesanan !== 'Dibayar' && $order->status_pesanan !== 'Produk Disiapkan') {
                        $order->status_pesanan = 'Dibayar';

                        $orderItems = OrderItem::where('orders_id_orders', $order->id_orders)->get();

                        foreach ($orderItems as $item) {
                            // Jika item berasal dari Flash Sale, potong stok FlashSaleItem
                            // (otomatis memotong stok InventoryBatch via event booted())
                            if ($item->flash_sale_item_id) {
                                $flashSaleItem = FlashSaleItem::find($item->flash_sale_item_id);
                                if ($flashSaleItem) {
                                    $deduct = min($flashSaleItem->stock, $item->quantity);
                                    $flashSaleItem->decrement('stock', $deduct);
                                }
                                continue;
                            }

                            // 2. Jika produk reguler, gunakan StockService FIFO (HAS-2)
                            StockService::deductFifo($item->product_id_product, $item->quantity);
                        }
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