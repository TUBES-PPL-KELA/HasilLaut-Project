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

        $isCartEmpty = $cartItems->isEmpty();

        $totalHarga = $cartItems->sum(function ($item) {
            return $item->quantity * ($item->effective_price ?? $item->product->price ?? 0);
        });

        $totalBerat = $cartItems->sum(function ($item) {
            $beratPerItem = $item->product->weight ?? $item->product->berat ?? 1;
            return $item->quantity * $beratPerItem;
        });

        $estimasiOngkir = $this->hitungEstimasiOngkir($cartItems, $userId);
        $totalBelanja = $totalHarga + $estimasiOngkir;

        return view('pembeli.cart', compact(
            'cartItems',
            'isCartEmpty',
            'totalHarga',
            'totalBerat',
            'estimasiOngkir',
            'totalBelanja',
            'cart'
        ));
    }

    public function checkout(Request $request)
    {
        $userId = auth()->id() ?? 1;
        $cart = Cart::where('users_id_users', $userId)->first();

        $hasCartItems = $cart
            ? CartItem::where('carts_id_carts', $cart->id_carts)->exists()
            : false;

        $hasDirectCheckout = $request->session()->has('direct_checkout');

        if (!$hasCartItems && !$hasDirectCheckout) {
            return redirect('/pembeli/keranjang')
                ->with('error', 'Keranjang belanja Anda masih kosong. Silakan pilih produk terlebih dahulu sebelum melakukan checkout.');
        }

        if (class_exists(\App\Http\Controllers\CheckoutController::class)) {
            return app(\App\Http\Controllers\CheckoutController::class)->index($request);
        }

        return view('pembeli.checkout');
    }

    private function hitungEstimasiOngkir($cartItems, $userId): int
    {
        if ($cartItems->isEmpty()) {
            return 0;
        }

        $pembeliDetail = \DB::table('pembeli_detail')
            ->where('users_id_users', $userId)
            ->first();

        // Sesuaikan nama kunci (lowercase) dan koordinat Hub sesuai data di tabel product
        $hubCoords = [
            'pangandaran' => ['lat' => -7.6889, 'lng' => 108.6506],
            'cirebon'     => ['lat' => -6.7320, 'lng' => 108.5523],
        ];

        $totalOngkir = 0;

        $groupedByHub = $cartItems->groupBy(function ($item) {
            return $item->product->hub_id
                ?? $item->product->id_hub
                ?? $item->product->hub_name
                ?? 'default-hub';
        });

        foreach ($groupedByHub as $hubKey => $itemsInHub) {
            $beratHub = $itemsInHub->sum(function ($item) {
                $berat = $item->product->weight ?? $item->product->berat ?? 1;
                return $item->quantity * $berat;
            });

            $firstProduct = $itemsInHub->first()->product;

            $jarakKm = $firstProduct->distance_km
                ?? $firstProduct->jarak_km
                ?? null;

            if (
                $jarakKm === null
                && $pembeliDetail
                && !is_null($pembeliDetail->latitude)
                && !is_null($pembeliDetail->longitude)
            ) {
                $hubNameLower = strtolower(trim((string) ($firstProduct->hub_name ?? '')));

                foreach ($hubCoords as $namaHub => $coord) {
                    if ($hubNameLower !== '' && str_contains($hubNameLower, $namaHub)) {
                        $jarakKm = $this->hitungJarakHaversine(
                            (float) $pembeliDetail->latitude,
                            (float) $pembeliDetail->longitude,
                            (float) $coord['lat'],
                            (float) $coord['lng']
                        );
                        break;
                    }
                }
            }

            $zona = strtolower((string) (
                $firstProduct->shipping_zone
                ?? $firstProduct->zona
                ?? ''
            ));

            if ($jarakKm !== null) {
                $jarakKm = (float) $jarakKm;

                if ($jarakKm <= 15) {
                    $tarifHub = 10000;
                } elseif ($jarakKm <= 50) {
                    $tarifHub = 15000 + (ceil($jarakKm - 15) * 1000);
                } else {
                    $tarifHub = 25000 + (ceil($jarakKm - 50) * 1500) + (max(0, ceil($beratHub - 2)) * 2000);
                }
            } elseif ($zona !== '') {
                $tarifZona = match ($zona) {
                    'zona_1', 'zona 1', 'lokal', 'dekat' => 10000,
                    'zona_2', 'zona 2', 'regional', 'menengah' => 15000,
                    'zona_3', 'zona 3', 'luar_kota', 'jauh' => 25000,
                    default => 15000,
                };

                $biayaBeratTambahan = $beratHub > 2 ? (ceil($beratHub - 2) * 2000) : 0;
                $tarifHub = $tarifZona + $biayaBeratTambahan;
            } else {
                $tarifHub = 15000;
            }

            $totalOngkir += (int) $tarifHub;
        }

        return (int) $totalOngkir;
    }

    private function hitungJarakHaversine(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $earthRadius = 6371;
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2)
            + cos(deg2rad($lat1)) * cos(deg2rad($lat2))
            * sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return round($earthRadius * $c, 2);
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:product,id_product',
            'flash_sale_item_id' => 'nullable|exists:flash_sale_item,id_flash_sale_item',
            'quantity' => 'nullable|integer|min:1',
        ]);

        $userId = auth()->id() ?? 1;
        $qty = (int) $request->input('quantity', 1);
        $flashSaleId = $request->input('flash_sale_item_id');

        $product = Product::with('inventoryBatches')->findOrFail($request->product_id);
        $totalStock = $product->inventoryBatches->sum('stock');

        if ($flashSaleId) {
            $flashSale = FlashSaleItem::findOrFail($flashSaleId);
            if ($flashSale->stock < $qty) {
                return back()->with('error', 'Maaf, stok promo produk ini sudah tidak mencukupi.');
            }
        } else {
            if ($totalStock < $qty) {
                return back()->with('error', 'Maaf, stok produk ini sedang kosong.');
            }
        }

        $cart = Cart::firstOrCreate(['users_id_users' => $userId]);

        $existingItem = CartItem::where('carts_id_carts', $cart->id_carts)
            ->where('product_id_product', $request->product_id)
            ->where('flash_sale_item_id', $flashSaleId)
            ->first();

        if ($existingItem) {
            $newQty = $existingItem->quantity + $qty;

            if ($flashSaleId && isset($flashSale) && $newQty > $flashSale->stock) {
                return back()->with('error', 'Jumlah di keranjang melebihi sisa stok Flash Sale (' . $flashSale->stock . ' kg).');
            }

            if (!$flashSaleId && $newQty > $totalStock) {
                return back()->with('error', 'Jumlah di keranjang melebihi sisa stok gudang! Sisa stok: ' . $totalStock . ' kg.');
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

        // Langsung arahkan ke checkout jika tombol "Beli Sekarang" ditekan
        if ($request->input('redirect_to') === 'checkout') {
            return redirect()->route('pembeli.checkout.index');
        }

        return back()->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    public function buyNow(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:product,id_product',
            'flash_sale_item_id' => 'nullable|exists:flash_sale_item,id_flash_sale_item',
            'quantity' => 'nullable|numeric|min:1',
        ]);

        $qty = $request->input('quantity', 1);
        $flashSaleId = $request->input('flash_sale_item_id');

        if ($flashSaleId) {
            $flashSale = FlashSaleItem::findOrFail($flashSaleId);
            if ($flashSale->stock < $qty) {
                return back()->with('error', 'Maaf, stok promo produk ini sudah tidak mencukupi.');
            }
            $price = $flashSale->price ?? $flashSale->harga ?? 0;
        } else {
            $product = Product::with('inventoryBatches')->findOrFail($request->product_id);
            $totalStock = $product->inventoryBatches->sum('stock');
            if ($totalStock < $qty) {
                return back()->with('error', 'Maaf, stok produk ini sedang kosong.');
            }
            $price = $product->price ?? 0;
        }

        session()->put('direct_checkout', [
            'product_id' => $request->product_id,
            'flash_sale_item_id' => $flashSaleId,
            'quantity' => $qty,
            'price' => $price,
        ]);

        return redirect('/pembeli/checkout');
    }

    public function update(Request $request, $id)
    {
        $cartItem = CartItem::with(['flashSaleItem', 'product.inventoryBatches'])->findOrFail($id);

        if ($request->quantity <= 0) {
            $cartItem->delete();
            return redirect()->back()->with('success', 'Item dihapus dari keranjang.');
        }

        if ($cartItem->flashSaleItem && $request->quantity > $cartItem->flashSaleItem->stock) {
            return redirect()->back()->with('error', 'Maksimal pembelian promo ini adalah ' . $cartItem->flashSaleItem->stock . ' kg.');
        }

        if (!$cartItem->flashSaleItem && $cartItem->product) {
            $totalStock = $cartItem->product->inventoryBatches->sum('stock');
            if ($request->quantity > $totalStock) {
                return redirect()->back()->with('error', 'Kuantitas melebihi sisa stok gudang! Sisa stok: ' . $totalStock . ' kg.');
            }
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