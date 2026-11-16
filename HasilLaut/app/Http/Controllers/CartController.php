<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\CartItem;

class CartController extends Controller
{
    public function index()
    {
        $cart = Cart::first();

        $cartItems = $cart ? CartItem::with('product')->where('carts_id_carts', $cart->id_carts)->get() : [];

        $totalHarga = collect($cartItems)->sum(function($item) {
            return $item->quantity * $item->product->price;
        });

        return view('pembeli.cart', compact('cartItems', 'totalHarga', 'cart'));
    }

    public function update(Request $request, $id)
    {
        $cartItem = CartItem::findOrFail($id);
        $cartItem->update([
            'quantity' => $request->quantity
        ]);

        return redirect()->back()->with('success', 'Kuantitas keranjang berhasil diperbarui!');
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:product,id_product',
            'quantity'   => 'required|numeric|min:0.5',
        ]);

        $userId = auth()->id() ?? 1; // sementara, samakan dengan CheckoutController

        $cart = Cart::firstOrCreate(['users_id_users' => $userId]);

        $item = CartItem::where('carts_id_carts', $cart->id_carts)
            ->where('product_id_product', $request->product_id)
            ->first();

        if ($item) {
            $item->increment('quantity', $request->quantity);
        } else {
            CartItem::create([
                'carts_id_carts'     => $cart->id_carts,
                'product_id_product' => $request->product_id,
                'quantity'           => $request->quantity,
            ]);
        }

        return redirect()->route('pembeli.cart')->with('success', 'Produk ditambahkan ke keranjang.');
    }

    public function destroy($id)
    {
        $cartItem = CartItem::findOrFail($id);
        $cartItem->delete();

        return redirect()->back()->with('success', 'Item berhasil dihapus dari keranjang!');
    }
}