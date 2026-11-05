<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\PembeliDetail;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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
     * Memproses penyimpanan data checkout ke database.
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        $userId = $user ? $user->id_users : 1;

        $pembeliDetail = PembeliDetail::where('users_id_users', $userId)->first();
        if (!$pembeliDetail) {
            return redirect()->back()->with('error', 'Silakan lengkapi alamat pengiriman terlebih dahulu.');
        }

        $cart = Cart::with('cartItems.product')->where('users_id_users', $userId)->first();
        if (!$cart || $cart->cartItems->isEmpty()) {
            return redirect()->back()->with('error', 'Keranjang belanjaan Anda kosong.');
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

            // Simpan pesanan (pastikan tabel orders memiliki kolom payment_method jika ingin disimpan)
            $order = Order::create([
                'order_number' => 'ORD-' . date('Ymd') . '-' . strtoupper(Str::random(4)),
                'is_pangandaran_area' => 1,
                'subtotal_amount' => $subtotal,
                'shipping_cost' => $shippingCost,
                'total_amount' => $totalAmount,
                'users_id_users' => $userId,
                // 'payment_method' => $request->input('payment_method', 'cod'), // Uncomment jika kolom di tabel orders sudah ada
            ]);

            foreach ($cart->cartItems as $item) {
                OrderItem::create([
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                    'orders_id_orders' => $order->id_orders,
                    'product_id_product' => $item->product->id_product,
                ]);
            }

            // Bersihkan keranjang
            $cart->cartItems()->delete();
            $cart->delete();

            DB::commit();

            return redirect()->route('pembeli.checkout.index')->with('success', 'Pesanan dan kalkulasi biaya berhasil diproses!');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }
}