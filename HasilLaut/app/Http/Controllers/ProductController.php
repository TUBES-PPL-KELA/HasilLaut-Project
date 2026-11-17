<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\InventoryBatch;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['category', 'inventoryBatches'])->latest('id_product')->get();
        $categories = Category::withCount('products')->get();

        return view('admin.product.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.product.create', compact('categories'));
    }

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
                'is_top_tren'              => true,
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
        }
    }
}