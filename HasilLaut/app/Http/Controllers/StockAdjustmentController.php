<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\InventoryBatch;
use App\Models\StockAdjustment;
use App\Services\StockService;
use Illuminate\Http\Request;
use Exception;

class StockAdjustmentController extends Controller
{
    public function index()
    {
        $products = Product::all();
        $adjustments = StockAdjustment::with(['inventoryBatch.product', 'user'])
            ->latest()
            ->get();

        return view('admin.stok.penyesuaian', compact('products', 'adjustments'));
    }

    public function getBatchesByProduct($productId)
    {
        $batches = InventoryBatch::where('product_id_product', $productId)
            ->where('stock', '>', 0)
            ->orderBy('received_date', 'asc')
            ->get();

        return response()->json($batches);
    }

    public function store(Request $request)
    {
        $request->validate([
            'inventory_batches_id_inventory_batches' => 'required|exists:inventory_batches,id_inventory_batches',
            'quantity' => 'required|numeric|min:0.01',
            'reason' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        try {
            $userId = auth()->id() ?? 1; // ID User Admin login (fallback ke 1 jika belum set auth)

            StockService::deductManualBatch(
                $request->inventory_batches_id_inventory_batches,
                $request->quantity,
                $request->reason,
                $request->notes,
                $userId
            );

            return redirect()->back()->with('success', 'Penyesuaian stok defect berhasil disimpan.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }
}