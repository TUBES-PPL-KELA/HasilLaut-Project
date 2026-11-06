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

    public function destroy($id)
    {
        $cartItem = CartItem::findOrFail($id);
        $cartItem->delete();

        return redirect()->back()->with('success', 'Item berhasil dihapus dari keranjang!');
    }
}