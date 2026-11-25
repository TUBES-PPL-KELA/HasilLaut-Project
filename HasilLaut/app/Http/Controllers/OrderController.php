<?php

namespace App\Http\Controllers;

use App\Models\KurirDetail;
use App\Models\Order;
use App\Models\Shipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    /**
     * Menampilkan halaman Manajemen Pesanan Masuk & Operasional Hub.
     */
    public function index(Request $request)
    {
        $activeTab = $request->query('tab', 'all');
        $allowedTabs = ['all', 'preparing', 'shipping', 'completed'];
        if (!in_array($activeTab, $allowedTabs, true)) {
            $activeTab = 'all';
        }

        $ordersQuery = Order::with([
            'user',
            'pembeliDetail',
            'payment',
            'shipment',
            'orderItems.product',
        ])->latest('id_orders');

        $allOrdersForCount = (clone $ordersQuery)->get();

        $counts = [
            'all' => $allOrdersForCount->count(),
            'preparing' => $allOrdersForCount->filter(function ($order) {
                $status = optional($order->shipment)->status;
                return is_null($status) || ($status === 'assigned' && is_null(optional($order->shipment)->assigned_at));
            })->count(),
            'shipping' => $allOrdersForCount->filter(function ($order) {
                $status = optional($order->shipment)->status;
                return $status === 'picked_up' || ($status === 'assigned' && !is_null(optional($order->shipment)->assigned_at));
            })->count(),
            'completed' => $allOrdersForCount->filter(function ($order) {
                return optional($order->shipment)->status === 'delivered';
            })->count(),
        ];

        $orders = match ($activeTab) {
            'preparing' => $allOrdersForCount->filter(function ($order) {
                $status = optional($order->shipment)->status;
                return is_null($status) || ($status === 'assigned' && is_null(optional($order->shipment)->assigned_at));
            })->values(),
            'shipping' => $allOrdersForCount->filter(function ($order) {
                $status = optional($order->shipment)->status;
                return $status === 'picked_up' || ($status === 'assigned' && !is_null(optional($order->shipment)->assigned_at));
            })->values(),
            'completed' => $allOrdersForCount->filter(function ($order) {
                return optional($order->shipment)->status === 'delivered';
            })->values(),
            default => $allOrdersForCount->values(),
        };

        $selectedOrderId = $request->query('selected');
        $selectedOrder = null;

        if ($selectedOrderId) {
            $selectedOrder = $allOrdersForCount->firstWhere('id_orders', (int) $selectedOrderId);
            if (!$selectedOrder) {
                session()->flash(
                    'error_popup',
                    "Pesanan dengan ID #{$selectedOrderId} tidak ditemukan. Menampilkan pesanan aktif default."
                );
            }
        }

        if (!$selectedOrder) {
            $selectedOrder = $orders->first() ?? $allOrdersForCount->first();
        }

        $couriers = KurirDetail::with('user')
            ->where(function ($query) {
                $query->whereNull('status')
                    ->orWhere('status', '!=', 'offline');
            })
            ->orderBy('id_kurir_detail')
            ->get();

        return view('admin.order.index', compact(
            'orders',
            'counts',
            'activeTab',
            'selectedOrder',
            'couriers'
        ));
    }

    /**
     * Memperbarui status operasional / shipment dan menetapkan kurir internal.
     * Menyiapkan skenario alternatif jika input tidak valid atau data bermasalah.
     */
    public function updateOperational(Request $request, $id)
    {
        if (!is_numeric($id) || (int) $id <= 0) {
            return redirect()
                ->route('admin.orders.index')
                ->with('error_popup', 'ID pesanan tidak valid. Silakan pilih pesanan dari daftar terlebih dahulu.');
        }

        $order = Order::with(['shipment', 'payment'])->find($id);
        if (!$order) {
            return redirect()
                ->route('admin.orders.index')
                ->with('error_popup', "Data pesanan dengan ID #{$id} tidak ditemukan di sistem.");
        }

        $validator = Validator::make(
            $request->all(),
            [
                'operational_stage' => [
                    'required',
                    Rule::in(['ready_for_pickup', 'assigned', 'picked_up', 'delivered']),
                ],
                'kurir_detail_id' => [
                    'nullable',
                    'integer',
                    'exists:kurir_detail,id_kurir_detail',
                ],
            ],
            [
                'operational_stage.required' => 'Status operasional internal hub wajib dipilih sebelum menyimpan.',
                'operational_stage.in' => 'Pilihan status operasional tidak valid. Silakan pilih status yang tersedia.',
                'kurir_detail_id.integer' => 'Format data kurir tidak valid.',
                'kurir_detail_id.exists' => 'Kurir internal yang dipilih tidak ditemukan dalam data sistem.',
            ]
        );

        // Skenario alternatif: jika status diserahkan/dikirim/selesai, kurir wajib dipilih
        $validator->after(function ($validator) use ($request) {
            $stage = $request->input('operational_stage');
            $courierId = $request->input('kurir_detail_id');

            if (in_array($stage, ['assigned', 'picked_up', 'delivered'], true) && empty($courierId)) {
                $validator->errors()->add(
                    'kurir_detail_id',
                    'Silakan pilih kurir internal terlebih dahulu sebelum menyerahkan atau memperbarui status pengiriman pesanan.'
                );
            }
        });

        if ($validator->fails()) {
            $firstMessage = $validator->errors()->first()
                ?: 'Input yang Anda masukkan belum valid. Periksa kembali form sebelum menyimpan.';

            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('error_popup', $firstMessage);
        }

        $validated = $validator->validated();
        $stage = $validated['operational_stage'];
        $courierId = $validated['kurir_detail_id'] ?? null;

        // Skenario alternatif: cek status kurir (misal sedang offline)
        $courier = null;
        if ($courierId) {
            $courier = KurirDetail::with('user')->find($courierId);
            if (!$courier) {
                return back()
                    ->withInput()
                    ->with('error_popup', 'Data kurir tidak ditemukan. Silakan muat ulang halaman dan pilih kurir lain.');
            }

            if ($courier->status === 'offline') {
                $courierName = optional($courier->user)->name ?? "Kurir #{$courier->id_kurir_detail}";
                return back()
                    ->withInput()
                    ->with('error_popup', "{$courierName} sedang berstatus Offline dan tidak dapat ditugaskan saat ini.");
            }
        }

        try {
            DB::beginTransaction();

            $now = now();
            $shipment = $order->shipment ?? new Shipment([
                'orders_id_orders' => $order->id_orders,
            ]);

            $shipment->orders_id_orders = $order->id_orders;

            if ($stage === 'ready_for_pickup') {
                $shipment->status = 'assigned';
                $shipment->assigned_at = null;
            } elseif ($stage === 'assigned') {
                $shipment->status = 'assigned';
                $shipment->assigned_at = $shipment->assigned_at ?? $now;
            } elseif ($stage === 'picked_up') {
                $shipment->status = 'picked_up';
                $shipment->assigned_at = $shipment->assigned_at ?? $now;
                $shipment->picked_up_at = $shipment->picked_up_at ?? $now;
            } elseif ($stage === 'delivered') {
                $shipment->status = 'delivered';
                $shipment->assigned_at = $shipment->assigned_at ?? $now;
                $shipment->picked_up_at = $shipment->picked_up_at ?? $now;
                $shipment->delivered_at = $shipment->delivered_at ?? $now;
            }

            $shipment->save();

            // Update status kurir sesuai tahap pengiriman jika kurir dipilih
            if ($courier) {
                if ($stage === 'delivered') {
                    $courier->status = 'available';
                } elseif (in_array($stage, ['assigned', 'picked_up'], true)) {
                    $courier->status = 'busy';
                }
                $courier->save();
            }

            DB::commit();

            $orderLabel = $order->order_number ?: "#{$order->id_orders}";
            $courierLabel = $courier
                ? (optional($courier->user)->name ?? "Kurir #{$courier->id_kurir_detail}")
                : 'belum ditetapkan';

            return redirect()
                ->route('admin.orders.index', [
                    'tab' => $request->input('tab', 'all'),
                    'selected' => $order->id_orders,
                ])
                ->with('success_popup', "Operasional pesanan {$orderLabel} berhasil diperbarui (Kurir: {$courierLabel}).");
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->with('error_popup', 'Terjadi kendala saat menyimpan perubahan operasional pesanan. Silakan coba beberapa saat lagi.');
        }
    }
}