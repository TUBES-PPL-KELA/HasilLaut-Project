<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Pesanan Masuk & Operasional Hub - HasilLaut</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#F8FAFC] text-slate-800 antialiased">
    <div class="min-h-screen flex">
        {{-- Sidebar Admin (gunakan partial jika tersedia) --}}
        @if(View::exists('partials.sidebar-admin'))
            @include('partials.sidebar-admin')
        @else
            <aside class="w-64 bg-white border-r border-slate-200 flex flex-col justify-between shrink-0">
                <div>
                    <div class="p-6 border-b border-slate-100 flex items-center gap-2">
                        <span class="text-xl font-extrabold tracking-tight text-[#013137]">HasilLaut</span>
                    </div>

                    <nav class="p-4 space-y-6 text-xs font-semibold text-slate-500">
                        <div>
                            <p class="px-3 mb-2 text-[10px] font-bold uppercase tracking-wider text-slate-400">Beranda</p>
                            <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-slate-50 text-slate-600">
                                <span>📊</span>
                                <span>Analitik & Grafik</span>
                            </a>
                        </div>

                        <div>
                            <p class="px-3 mb-2 text-[10px] font-bold uppercase tracking-wider text-slate-400">Gudang & Katalog</p>
                            <div class="space-y-1">
                                <a href="{{ route('admin.product.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-slate-50 text-slate-600">
                                    <span>📦</span>
                                    <span>Stok Gudang</span>
                                </a>
                                <a href="{{ route('admin.flash_sale.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-slate-50 text-slate-600">
                                    <span>⚡</span>
                                    <span>Flash Sale & Diskon</span>
                                </a>
                                <a href="{{ route('admin.stok.penyesuaian') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-slate-50 text-slate-600">
                                    <span>⚠️</span>
                                    <span>Penyesuaian Defect / Rusak</span>
                                </a>
                            </div>
                        </div>

                        <div>
                            <p class="px-3 mb-2 text-[10px] font-bold uppercase tracking-wider text-slate-400">Transaksi & Armada</p>
                            <a href="{{ route('admin.orders.index') }}"
                               class="flex items-center gap-3 px-3 py-2.5 rounded-xl bg-slate-100 text-[#013137] font-bold">
                                <span>📥</span>
                                <span>Pesanan Masuk</span>
                            </a>
                        </div>
                    </nav>
                </div>

                <div class="p-4 border-t border-slate-100">
                    <div class="flex items-center gap-3 p-2.5 rounded-xl bg-slate-50">
                        <div class="w-9 h-9 rounded-full bg-[#013137] text-white flex items-center justify-center text-xs font-bold shrink-0">
                            AM
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs font-bold text-slate-800 truncate">Andi M. (Admin)</p>
                            <p class="text-[11px] text-slate-400 truncate">Hub Pangandaran</p>
                        </div>
                    </div>
                </div>
            </aside>
        @endif

        {{-- Konten Utama --}}
        <main class="flex-1 p-6 lg:p-8 max-w-7xl mx-auto w-full">
            {{-- Header Halaman --}}
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Manajemen Pesanan Masuk & Operasional Hub</h1>
                <p class="text-sm text-slate-500 mt-1">Konfirmasi pesanan baru, proses pengemasan (packing), dan tetapkan kurir internal.</p>
            </div>

            {{-- Notifikasi Inline Ringkas --}}
            @if(session('success_popup'))
                <div class="mb-5 px-4 py-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-medium flex items-center justify-between">
                    <span>{{ session('success_popup') }}</span>
                    <button type="button" onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-900 font-bold ml-4">✕</button>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-5 px-4 py-3 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm">
                    <p class="font-semibold mb-1">Periksa kembali input Anda:</p>
                    <ul class="list-disc list-inside space-y-0.5 text-xs">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Tab Filter Status --}}
            @php
                $tabs = [
                    'all' => ['label' => 'Semua Pesanan', 'count' => $counts['all'] ?? 0, 'badge' => 'bg-slate-600 text-white', 'active' => 'bg-[#1E3A44] text-white border-[#1E3A44]'],
                    'preparing' => ['label' => '1. Disiapkan & Packing (Set Kurir)', 'count' => $counts['preparing'] ?? 0, 'badge' => 'bg-sky-500 text-white', 'active' => 'bg-white text-[#013137] border-sky-400 ring-1 ring-sky-400'],
                    'shipping' => ['label' => '2. Dalam Pengiriman', 'count' => $counts['shipping'] ?? 0, 'badge' => 'bg-purple-600 text-white', 'active' => 'bg-white text-[#013137] border-purple-400 ring-1 ring-purple-400'],
                    'completed' => ['label' => '3. Selesai', 'count' => $counts['completed'] ?? 0, 'badge' => 'bg-emerald-500 text-white', 'active' => 'bg-white text-[#013137] border-emerald-400 ring-1 ring-emerald-400'],
                ];
            @endphp

            <div class="flex flex-wrap items-center gap-3 mb-6">
                @foreach($tabs as $key => $tab)
                    @php
                        $isActive = ($activeTab ?? 'all') === $key;
                    @endphp
                    <a href="{{ route('admin.orders.index', ['tab' => $key]) }}"
                       class="inline-flex items-center gap-2.5 px-4 py-2 rounded-full text-xs font-semibold border transition {{ $isActive ? $tab['active'] : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50' }}">
                        <span>{{ $tab['label'] }}</span>
                        <span class="px-2 py-0.5 rounded-full text-[11px] font-bold {{ $isActive && $key === 'all' ? 'bg-slate-500 text-white' : $tab['badge'] }}">
                            {{ $tab['count'] }}
                        </span>
                    </a>
                @endforeach
            </div>

            {{-- Tabel Daftar Seluruh Pesanan Masuk Hub --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden mb-6">
                <div class="px-6 py-4 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                    <div class="flex items-center gap-2">
                        <span class="text-base">📋</span>
                        <h2 class="text-sm font-bold text-slate-800">Daftar Seluruh Pesanan Masuk Hub</h2>
                    </div>
                    <span class="text-xs text-slate-400">Menampilkan pesanan masuk hub</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/80 border-b border-slate-100 text-[11px] font-bold uppercase tracking-wider text-slate-400">
                                <th class="py-3.5 px-5">ID Order & Waktu</th>
                                <th class="py-3.5 px-5">Pembeli & Alamat</th>
                                <th class="py-3.5 px-5">Produk & Total Berat</th>
                                <th class="py-3.5 px-5">Total Bayar / Metode</th>
                                <th class="py-3.5 px-5">Status Operasional</th>
                                <th class="py-3.5 px-5 text-right">Aksi Admin</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-xs">
                            @forelse($orders as $order)
                                @php
                                    $isSelected = isset($selectedOrder) && $selectedOrder && (int) $selectedOrder->id_orders === (int) $order->id_orders;
                                    $shipmentStatus = optional($order->shipment)->status;
                                    $assignedAt = optional($order->shipment)->assigned_at;

                                    if ($shipmentStatus === 'delivered') {
                                        $opLabel = 'Selesai Diterima';
                                        $opClass = 'bg-emerald-50 text-emerald-700 border-emerald-200';
                                        $opIcon = '✓';
                                    } elseif ($shipmentStatus === 'picked_up' || ($shipmentStatus === 'assigned' && !is_null($assignedAt))) {
                                        $opLabel = 'Dalam Pengiriman';
                                        $opClass = 'bg-purple-50 text-purple-700 border-purple-200';
                                        $opIcon = '🚚';
                                    } else {
                                        $opLabel = 'Tahap Disiapkan';
                                        $opClass = 'bg-sky-50 text-sky-700 border-sky-200';
                                        $opIcon = '!';
                                    }

                                    $buyerName = optional($order->pembeliDetail)->name
                                        ?? optional($order->pembeliDetail)->full_name
                                        ?? optional($order->pembeliDetail)->nama_lengkap
                                        ?? optional($order->user)->name
                                        ?? 'Pembeli #' . $order->users_id_users;

                                    $buyerAddress = optional($order->pembeliDetail)->address
                                        ?? optional($order->pembeliDetail)->alamat
                                        ?? ($order->is_pangandaran_area ? 'Area Pangandaran' : 'Luar Pangandaran');

                                    $productSummary = $order->orderItems->map(function ($item) {
                                        $prodName = optional($item->product)->name ?? 'Produk';
                                        $qtyFormatted = rtrim(rtrim(number_format((float) $item->quantity, 2, '.', ''), '0'), '.');
                                        return "{$prodName} ({$qtyFormatted}kg)";
                                    })->implode(', ');

                                    $totalWeightFormatted = rtrim(rtrim(number_format((float) $order->total_weight, 2, '.', ''), '0'), '.');

                                    // [DIEDIT] Prioritaskan total_amount pada order, lalu amount pada payment, lalu kalkulasi subtotal + ongkir / items + ongkir
                                    $itemsSum = (float) $order->orderItems->sum(function ($item) {
                                        return (float) ($item->quantity ?? 0) * (float) ($item->price ?? 0);
                                    });
                                    $shippingCost = (float) ($order->shipping_cost ?? 0);
                                    $calculatedTotal = ($order->subtotal_amount !== null)
                                        ? ((float) $order->subtotal_amount + $shippingCost)
                                        : ($itemsSum + $shippingCost);

                                    if (!is_null($order->total_amount) && (float) $order->total_amount > 0) {
                                        $totalBayar = (float) $order->total_amount;
                                    } elseif (!is_null(optional($order->payment)->amount) && (float) optional($order->payment)->amount > 0) {
                                        $totalBayar = (float) optional($order->payment)->amount;
                                    } else {
                                        $totalBayar = $calculatedTotal;
                                    }

                                    $paymentMethod = strtoupper(optional($order->payment)->payment_method ?? 'COD');
                                    $paymentStatusRaw = strtolower((string) optional($order->payment)->status);
                                    $paymentStatusLabel = in_array($paymentStatusRaw, ['paid', 'settlement', 'capture', 'lunas'])
                                        ? 'Lunas'
                                        : 'Belum Bayar';
                                @endphp

                                <tr class="transition {{ $isSelected ? 'bg-[#F0FDF4]' : 'hover:bg-slate-50/70' }}">
                                    <td class="py-4 px-5 align-top">
                                        <p class="font-bold text-slate-800">{{ $order->order_number ?: '#ORD-' . $order->id_orders }}</p>
                                        <p class="text-[11px] text-slate-400 mt-0.5">
                                            {{ optional($order->created_at)->translatedFormat('d M, H:i') ?? '-' }} WIB
                                        </p>
                                    </td>

                                    <td class="py-4 px-5 align-top">
                                        <p class="font-bold text-slate-800">{{ $buyerName }}</p>
                                        <p class="text-[11px] text-slate-400 mt-0.5 max-w-[200px] truncate" title="{{ $buyerAddress }}">
                                            {{ $buyerAddress }}
                                        </p>
                                    </td>

                                    <td class="py-4 px-5 align-top">
                                        <p class="text-slate-700 font-medium max-w-[240px]">
                                            {{ $productSummary ?: 'Item pesanan' }}
                                            @if((float) $order->total_weight > 0)
                                                <span class="text-slate-400 font-normal">({{ $totalWeightFormatted }} kg)</span>
                                            @endif
                                        </p>
                                    </td>

                                    <td class="py-4 px-5 align-top">
                                        <p class="font-bold text-slate-800">
                                            Rp {{ number_format($totalBayar, 0, ',', '.') }}
                                        </p>
                                        <p class="text-[11px] text-slate-400 mt-0.5">
                                            {{ $paymentMethod }} ({{ $paymentStatusLabel }})
                                        </p>
                                    </td>

                                    <td class="py-4 px-5 align-top">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold border {{ $opClass }}">
                                            <span>{{ $opIcon }}</span>
                                            <span>{{ $opLabel }}</span>
                                        </span>
                                    </td>

                                    <td class="py-4 px-5 align-top text-right">
                                        @if($shipmentStatus === 'delivered')
                                            <span class="text-xs font-semibold text-emerald-600">Transaksi Sukses</span>
                                        @elseif($shipmentStatus === 'picked_up' || ($shipmentStatus === 'assigned' && !is_null($assignedAt)))
                                            <a href="{{ route('admin.orders.index', ['tab' => $activeTab, 'selected' => $order->id_orders]) }}"
                                               class="text-xs font-medium text-slate-500 hover:text-slate-800 underline">
                                                Atur / Detail Kurir
                                            </a>
                                        @else
                                            <a href="{{ route('admin.orders.index', ['tab' => $activeTab, 'selected' => $order->id_orders]) }}"
                                               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-slate-300 bg-white text-slate-700 font-semibold text-xs hover:bg-slate-50 transition">
                                                <span>✎</span>
                                                <span>Update Packing & Kurir</span>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-10 px-6 text-center text-slate-400 text-sm">
                                        Belum ada data pesanan masuk pada kategori ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Panel Aksi Admin di Bagian Bawah --}}
            @if(isset($selectedOrder) && $selectedOrder)
                @php
                    $selectedShipmentStatus = optional($selectedOrder->shipment)->status;
                    $selectedAssignedAt = optional($selectedOrder->shipment)->assigned_at;
                    $defaultStage = old('operational_stage');

                    if (!$defaultStage) {
                        if ($selectedShipmentStatus === 'delivered') {
                            $defaultStage = 'delivered';
                        } elseif ($selectedShipmentStatus === 'picked_up') {
                            $defaultStage = 'picked_up';
                        } elseif ($selectedShipmentStatus === 'assigned' && !is_null($selectedAssignedAt)) {
                            $defaultStage = 'assigned';
                        } else {
                            $defaultStage = 'ready_for_pickup';
                        }
                    }

                    $selectedOrderCode = $selectedOrder->order_number ?: '#ORD-' . $selectedOrder->id_orders;
                @endphp

                <div class="rounded-2xl border-2 border-dashed border-emerald-300 bg-[#F4FBF7] p-6">
                    <div class="flex items-center gap-2 mb-4">
                        <span class="text-base">📦</span>
                        <h3 class="text-sm font-bold text-slate-800">
                            Panel Aksi Admin: Proses Pengemasan & Penetapan Kurir untuk Pesanan {{ $selectedOrderCode }}
                        </h3>
                    </div>

                    <form action="{{ route('admin.orders.update-operational', $selectedOrder->id_orders) }}"
                          method="POST"
                          class="grid grid-cols-1 lg:grid-cols-12 gap-4 items-end">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="tab" value="{{ $activeTab }}">

                        {{-- Status Operasional Internal Hub --}}
                        <div class="lg:col-span-4">
                            <label for="operational_stage" class="block text-xs font-bold text-slate-700 mb-1.5">
                                Status Operasional Internal Hub
                            </label>
                            <select id="operational_stage"
                                    name="operational_stage"
                                    class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-xs font-medium text-slate-800 focus:outline-none focus:border-emerald-500">
                                <option value="">-- Pilih Status Operasional --</option>
                                <option value="ready_for_pickup" {{ $defaultStage === 'ready_for_pickup' ? 'selected' : '' }}>
                                    Selesai Dikemas & Siap Dijemput Kurir
                                </option>
                                <option value="assigned" {{ $defaultStage === 'assigned' ? 'selected' : '' }}>
                                    Ditugaskan ke Kurir
                                </option>
                                <option value="picked_up" {{ $defaultStage === 'picked_up' ? 'selected' : '' }}>
                                    Dalam Pengiriman (Diambil Kurir)
                                </option>
                                <option value="delivered" {{ $defaultStage === 'delivered' ? 'selected' : '' }}>
                                    Selesai Diterima
                                </option>
                            </select>
                        </div>

                        {{-- Tetapkan Kurir Internal --}}
                        <div class="lg:col-span-5">
                            <label for="kurir_detail_id" class="block text-xs font-bold text-slate-700 mb-1.5">
                                Tetapkan Kurir Internal (Available)
                            </label>
                            <select id="kurir_detail_id"
                                    name="kurir_detail_id"
                                    class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-xs font-medium text-slate-800 focus:outline-none focus:border-emerald-500">
                                <option value="">-- Pilih Kurir Internal --</option>
                                @foreach($couriers as $courier)
                                    @php
                                        $courierName = optional($courier->user)->name ?? 'Kurir #' . $courier->id_kurir_detail;
                                        $courierStatusLabel = ucfirst($courier->status ?? 'available');
                                    @endphp
                                    <option value="{{ $courier->id_kurir_detail }}"
                                            {{ (string) old('kurir_detail_id') === (string) $courier->id_kurir_detail ? 'selected' : '' }}>
                                        {{ $courierName }} ({{ $courier->vehicle_number }} - {{ $courierStatusLabel }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Tombol Simpan --}}
                        <div class="lg:col-span-3">
                            <button type="submit"
                                    class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-[#34A853] hover:bg-[#2C9348] text-white font-bold text-xs px-4 py-3 shadow-sm transition cursor-pointer">
                                <span>💾</span>
                                <span>Simpan & Serahkan ke Kurir</span>
                            </button>
                        </div>
                    </form>
                </div>
            @endif
        </main>
    </div>

    {{-- Pop-up Modal Error / Validasi Tidak Valid --}}
    @if(session('error_popup'))
        <div id="error-popup-modal"
             class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 p-4">
            <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-xl border border-red-100">
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 rounded-full bg-red-100 text-red-600 flex items-center justify-center font-bold text-lg shrink-0">
                        !
                    </div>
                    <div class="flex-1">
                        <h4 class="text-base font-bold text-slate-900">Input Tidak Valid / Perhatian</h4>
                        <p class="text-xs text-slate-600 mt-1.5 leading-relaxed">
                            {{ session('error_popup') }}
                        </p>
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <button type="button"
                            onclick="document.getElementById('error-popup-modal').remove()"
                            class="px-4 py-2 rounded-xl bg-red-600 hover:bg-red-700 text-white text-xs font-semibold transition cursor-pointer">
                        Mengerti, Tutup
                    </button>
                </div>
            </div>
        </div>
    @endif
</body>
</html>