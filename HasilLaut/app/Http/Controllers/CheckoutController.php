<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
<<<<<<< HEAD
use App\Http\Requests\StoreProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\InventoryBatch;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
=======
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
>>>>>>> dbc6d6d9c279113ac30be2b74fba276b1ef07b22

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['category', 'inventoryBatches'])->latest('id_product')->get();
        $categories = Category::withCount('products')->get();

<<<<<<< HEAD
        return view('admin.product.index', compact('products', 'categories'));
    }

    public function create()
=======
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
>>>>>>> dbc6d6d9c279113ac30be2b74fba276b1ef07b22
    {
        $categories = Category::all();
        return view('admin.product.create', compact('categories'));
    }

<<<<<<< HEAD
    public function store(StoreProductRequest $request)
    {
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        DB::beginTransaction();

        try {
            $product = Product::create([
                'name'                     => $request->name,
                'categories_id_categories' => $request->categories_id_categories,
                'max_storage_days'         => $request->max_storage_days,
                'description'              => $request->description,
                'price'                    => $request->price,
                'image'                    => $imagePath,
            ]);

            $batchData = [
                'product_id_product' => $product->id_product,
                'users_id_users'     => auth()->id() ?? 1,
                'stock'              => $request->stock,
                'purchase_price'     => $request->purchase_price,
                'received_date'      => $request->received_date,
            ];

            $batchCols = Schema::getColumnListing('inventory_batches');
            foreach (['expired_date', 'expiry_date', 'exp_date'] as $expCol) {
                if (in_array($expCol, $batchCols)) {
                    $batchData[$expCol] = Carbon::parse($request->received_date)
                        ->addDays((int) ($request->max_storage_days ?? 7))
                        ->format('Y-m-d');
                    break;
                }
            }

            InventoryBatch::forceCreate($batchData);

            DB::commit();

            return redirect()->route('admin.product.index')
                             ->with('success', 'Produk dan Stok Awal berhasil ditambahkan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                         ->withErrors(['error' => 'Gagal menyimpan: ' . $e->getMessage()]);
        }
    }

    public function storeBatch(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'stock'          => 'required|numeric|min:1',
            'purchase_price' => 'required|numeric|min:0',
            'received_date'  => 'required|date',
            'expired_date'   => 'nullable|date|after_or_equal:received_date',
        ]);

        DB::beginTransaction();

        try {
            $batchData = [
                'product_id_product' => $product->id_product,
                'users_id_users'     => auth()->id() ?? 1,
                'stock'              => $request->stock,
                'purchase_price'     => $request->purchase_price,
                'received_date'      => $request->received_date,
            ];

            $batchCols = Schema::getColumnListing('inventory_batches');
            $calculatedExp = $request->filled('expired_date')
                ? $request->expired_date
                : Carbon::parse($request->received_date)
                    ->addDays((int) ($product->max_storage_days ?? 7))
                    ->format('Y-m-d');

            foreach (['expired_date', 'expiry_date', 'exp_date'] as $expCol) {
                if (in_array($expCol, $batchCols)) {
                    $batchData[$expCol] = $calculatedExp;
                    break;
                }
            }

            InventoryBatch::forceCreate($batchData);

            DB::commit();

            return redirect()->route('admin.product.index')
                             ->with('success', 'Batch stok baru (+' . $request->stock . ' kg) berhasil ditambahkan untuk ' . $product->name . '!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.product.index')
                             ->with('error', 'Gagal menambahkan batch stok: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();

        return view('admin.product.edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'name'                     => 'required|string|max:255',
            'categories_id_categories' => 'required|exists:categories,id_categories',
            'price'                    => 'required|numeric|min:0',
            'max_storage_days'         => 'required|integer|min:1',
            'description'              => 'required|string',
            'image'                    => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($validated);

        return redirect()->route('admin.product.index')
                         ->with('success', 'Data produk berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        DB::beginTransaction();

        try {
            $imagePath = $product->image;

            $product->inventoryBatches()->delete();
            $product->delete();

            DB::commit();

            if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }

            return redirect()->route('admin.product.index')
                             ->with('success', 'Produk berhasil dihapus!');
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return redirect()->route('admin.product.index')
                             ->with('error', 'Produk tidak dapat dihapus karena masih terkait dengan data transaksi atau flash sale.');
=======
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
            $subtotal = 0;
            foreach ($cart->cartItems as $item) {
                $subtotal += ($item->quantity * $item->effective_price);
            }
            $shippingCost = 15000;
            $serviceFee = 2500;
            $totalAmount = $subtotal + $shippingCost + $serviceFee;

            $order = Order::create([
                'order_number' => 'ORD-' . date('Ymd') . '-' . strtoupper(Str::random(4)),
                'is_pangandaran_area' => 1,
                'subtotal_amount' => $subtotal,
                'shipping_cost' => $shippingCost,
                'total_amount' => $totalAmount,
                'users_id_users' => $userId,
                'status_pesanan' => 'Menunggu Pembayaran',
            ]);

            foreach ($cart->cartItems as $item) {
                OrderItem::create([
                    'quantity' => $item->quantity,
                    'price' => $item->effective_price,
                    'orders_id_orders' => $order->id_orders,
                    'product_id_product' => $item->product->id_product,
                    'flash_sale_item_id' => $item->flash_sale_item_id ?? null,
                ]);
            }

            Payment::create([
                'orders_id_orders' => $order->id_orders,
                'payment_method' => match ($method) {
                    'cod'  => 'COD',
                    'qris' => 'QRIS',
                    'va'   => 'Virtual Account',
                    default => 'Midtrans Snap',
                },
                'amount' => $totalAmount,
                'status' => 'Pending',
                'payment_gateway_inv_id' => '-', 
            ]);

            if ($method === 'cod') {
                $order->status_pesanan = 'Menunggu Konfirmasi COD';
                $order->save();

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
                    'order_id' => $order->order_number . '-' . time(),
                    'gross_amount' => (int) round($totalAmount),
                ],
                'item_details' => [
                    [
                        'id' => 'PROD-TOTAL',
                        'price' => (int) round($totalAmount),
                        'quantity' => 1,
                        'name' => 'Total Belanja HasilLaut (' . $order->order_number . ')',
                    ]
                ],
                'customer_details' => [
                    'first_name' => $pembeliDetail->name ?? ($user->name ?? 'Pelanggan HasilLaut'),
                    'email' => $user->email ?? 'pembeli@hasillaut.test',
                    'phone' => $pembeliDetail->phone_number ?? '08123456789',
                ]
            ];

            $params['enabled_payments'] = match ($method) {
                'qris' => ['other_qris', 'gopay'],
                'va'   => ['bca_va', 'echannel', 'bri_va'],
                default => ['other_qris', 'bca_va'], 
            };

            if (!config('midtrans.is_production')) {
            Config::$curlOptions = [
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => 0,
                CURLOPT_HTTPHEADER     => [],
            ];
}
           \Log::info('SEBELUM snap');
            $snapToken = Snap::getSnapToken($params);
            \Log::info('SESUDAH snap', ['token' => $snapToken]);

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

            $payment = Payment::where('orders_id_orders', $order->id_orders)->first();

            if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
                if ($transactionStatus == 'capture' && $fraudStatus == 'challenge') {
                    $payment->status = 'Challenge'; 
                    $order->status_pesanan = 'Menunggu Verifikasi';
                } else {
                    $payment->status = 'Lunas'; 
                    $payment->payment_gateway_inv_id = $notification->transaction_id; 

                    if ($order->status_pesanan !== 'Dibayar' && $order->status_pesanan !== 'Produk Disiapkan') {
                        $order->status_pesanan = 'Dibayar';

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
            } else if ($transactionStatus == 'cancel' || $transactionStatus == 'deny' || $transactionStatus == 'expire') {
                $payment->status = 'Gagal'; 
                $order->status_pesanan = 'Dibatalkan';
            } else if ($transactionStatus == 'pending') {
                $payment->status = 'Pending'; 
                $order->status_pesanan = 'Menunggu Pembayaran';
            }

            $payment->save();
            $order->save();

            DB::commit();
            return response()->json(['message' => 'Webhook successfully processed']);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error processing webhook: ' . $e->getMessage()], 500);
>>>>>>> dbc6d6d9c279113ac30be2b74fba276b1ef07b22
        }
    }
}