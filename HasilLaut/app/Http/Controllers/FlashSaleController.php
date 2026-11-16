<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Discount;
use App\Models\FlashSaleItem;
use App\Models\InventoryBatch;
use Carbon\Carbon;

class FlashSaleController extends Controller
{
    // Menampilkan Halaman Dasbor Flash Sale
    public function index()
    {
        $discount = Discount::first();

        // Pastikan stok di flash_sale_item DAN inventory_batches sama-sama > 0
        $activeFlashSales = FlashSaleItem::with(['inventoryBatch.product', 'discount'])
            ->where('stock', '>', 0)
            ->whereHas('inventoryBatch', function ($query) {
                $query->where('stock', '>', 0);
            })
            ->paginate(5);

        // Ambil batch yang H-1 Kadaluarsa (abaikan yang ditandai salah input)
        $today = Carbon::today();
        $warningBatches = InventoryBatch::with('product')
            ->doesntHave('flashSaleItem')
            ->where('stock', '>', 0)
            ->where('is_flash_sale_excluded', false)
            ->get()
            ->filter(function ($batch) use ($today) {
                if (!$batch->product || !$batch->product->max_storage_days) return false;
                $receivedDate = Carbon::parse($batch->received_date)->startOfDay();
                $expiryDate = $receivedDate->copy()->addDays($batch->product->max_storage_days);
                
                return $today->diffInDays($expiryDate, false) == 1;
            });

        return view('admin.flash_sale.index', compact('discount', 'activeFlashSales', 'warningBatches'));
    }

    // Menyimpan atau Mengupdate Aturan Diskon Global & Sinkronisasi Harga Aktif
    public function updateGlobalDiscount(Request $request)
    {
        $request->validate([
            'percent' => 'required|numeric|min:1|max:99'
        ]);

        $discount = Discount::first();
        if ($discount) {
            $discount->update(['percent' => $request->percent]);
        } else {
            $discount = Discount::create(['percent' => $request->percent]);
        }

        // Hitung ulang harga flash sale untuk semua item yang sedang aktif
        $activeItems = FlashSaleItem::with('inventoryBatch.product')->get();
        foreach ($activeItems as $item) {
            if ($item->inventoryBatch && $item->inventoryBatch->product) {
                $normalPrice = $item->inventoryBatch->product->price;
                $newFlashSalePrice = $normalPrice - ($normalPrice * ($request->percent / 100));
                
                $item->update([
                    'flash_sale_price' => $newFlashSalePrice,
                    'discount_id_discount' => $discount->id_discount,
                ]);
            }
        }

        return back()->with('success', 'Aturan diskon global dan harga produk aktif berhasil diperbarui!');
    }

    // Menghentikan Flash Sale berdasarkan alasan (Stok Busuk vs Salah Input)
    public function destroy(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|in:spoiled,mistake'
        ]);

        $flashSale = FlashSaleItem::with('inventoryBatch')->findOrFail($id);
        $batch = $flashSale->inventoryBatch;

        if ($request->reason === 'spoiled') {
            // Stok busuk: nolkan stok gudang agar tidak terjual & tidak diproses ulang
            if ($batch) {
                $batch->update(['stock' => 0]);
            }
            $flashSale->delete();

            return back()->with('success', 'Flash sale dihentikan dan sisa stok busuk telah dinolkan dari gudang.');
        }

        // Salah input: kembalikan ke stok normal & tahan dari jadwal otomatis
        if ($batch) {
            $batch->update(['is_flash_sale_excluded' => true]);
        }
        $flashSale->delete();

        return back()->with('success', 'Flash sale dibatalkan. Silakan perbaiki tanggal masuk atau batas simpan produk.');
    }
}