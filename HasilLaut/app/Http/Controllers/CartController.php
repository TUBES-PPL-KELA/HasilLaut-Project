<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\FlashSaleItem;
use App\Models\Product;

class CartController extends Controller
{
    public function index()
    {
        $userId = auth()->id() ?? 1;
        $cart = Cart::where('users_id_users', $userId)->first();

        $cartItems = $cart
            ? CartItem::with(['product', 'flashSaleItem.discount'])->where('carts_id_carts', $cart->id_carts)->get()
            : collect();

        $totalHarga = $cartItems->sum(function ($item) {
            return $item->quantity * $item->effective_price;
        });

        return view('pembeli.cart', compact('cartItems', 'totalHarga', 'cart'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:product,id_product',
            'flash_sale_item_id' => 'nullable|exists:flash_sale_item,id_flash_sale_item',
            'quantity' => 'nullable|numeric|min:1',
        ]);

        $userId = auth()->id() ?? 1;
        $qty = $request->input('quantity', 1);
        $flashSaleId = $request->input('flash_sale_item_id');

        // Validasi stok sebelum masuk keranjang
        if ($flashSaleId) {
            $flashSale = FlashSaleItem::findOrFail($flashSaleId);
            if ($flashSale->stock < $qty) {
                return back()->with('error', 'Maaf, stok promo produk ini sudah tidak mencukupi.');
            }
        } else {
            $product = Product::with('inventoryBatches')->findOrFail($request->product_id);
            $totalStock = $product->inventoryBatches->sum('stock');
            if ($totalStock < $qty) {
                return back()->with('error', 'Maaf, stok produk ini sedang kosong.');
            }
        }

        $cart = Cart::firstOrCreate(['users_id_users' => $userId]);

        // Pisahkan item promo dan item reguler di keranjang agar harga tidak tercampur
        $existingItem = CartItem::where('carts_id_carts', $cart->id_carts)
            ->where('product_id_product', $request->product_id)
            ->where('flash_sale_item_id', $flashSaleId)
            ->first();

        if ($existingItem) {
            $newQty = $existingItem->quantity + $qty;
            if ($flashSaleId && isset($flashSale) && $newQty > $flashSale->stock) {
                return back()->with('error', 'Jumlah di keranjang melebihi sisa stok Flash Sale (' . $flashSale->stock . ' kg).');
            }
            $existingItem->update(['quantity' => $newQty]);
        } else {
            CartItem::create([
                'carts_id_carts' => $cart->id_carts,
                'product_id_product' => $request->product_id,
                'flash_sale_item_id' => $flashSaleId,
                'quantity' => $qty,
            ]);
        }

        return back()->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    public function update(Request $request, $id)
    {
        $cartItem = CartItem::with('flashSaleItem')->findOrFail($id);

        if ($request->quantity <= 0) {
            $cartItem->delete();
            return redirect()->back()->with('success', 'Item dihapus dari keranjang.');
        }

        if ($cartItem->flashSaleItem && $request->quantity > $cartItem->flashSaleItem->stock) {
            return redirect()->back()->with('error', 'Maksimal pembelian promo ini adalah ' . $cartItem->flashSaleItem->stock . ' kg.');
        }

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