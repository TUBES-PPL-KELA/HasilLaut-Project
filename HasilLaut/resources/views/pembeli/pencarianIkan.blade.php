@extends('layouts.pembeli')

@section('title', 'Pencarian Ikan | HasilLaut')

@section('content')
    <section aria-labelledby="search-title" class="space-y-5">
        <div>
            <h1 id="search-title" class="text-2xl font-bold text-[#1b4d3e]">Pencarian Ikan</h1>
            @if ($keyword !== '')
                <p class="mt-1 text-sm text-gray-600">Hasil pencarian untuk "{{ $keyword }}"</p>
            @endif
        </div>

        @forelse ($products as $product)
            @if ($loop->first)
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @endif
            <article data-testid="search-result-card" class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                <a href="#" aria-label="Lihat detail {{ $product->name }}" class="block h-48 bg-gray-100">
                    @if ($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="h-full w-full object-cover" loading="lazy">
                    @else
                        <div class="grid h-full place-items-center text-sm text-gray-500">Gambar tidak tersedia</div>
                    @endif
                </a>
                <div class="space-y-2 p-4">
                    <h2 class="truncate text-lg font-semibold text-[#1b4d3e]">
                        <a href="#" class="hover:underline">{{ $product->name }}</a>
                    </h2>
                    <p class="text-sm text-gray-600">{{ $product->hub_name ?: 'Hub belum tersedia' }}</p>
                    @if ($product->category)
                        <p class="text-sm text-gray-600">{{ $product->category->name }}</p>
                    @endif
                    <p class="line-clamp-2 text-sm text-gray-600">{{ $product->description }}</p>
                    <div class="flex items-center justify-between gap-3">
                        <p class="font-bold text-[#1b4d3e]">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                        <p class="text-sm text-gray-600">{{ number_format($product->available_stock ?? 0, 0, ',', '.') }} kg</p>
                    </div>
                    <a href="#" class="inline-flex rounded-lg bg-[#1b4d3e] px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-900">Lihat Detail</a>
                </div>
            </article>
            @if ($loop->last)
                </div>
            @endif
        @empty
            <div data-testid="search-empty-state" class="rounded-xl border border-gray-200 bg-white px-6 py-10 text-center">
                <p class="text-lg font-semibold text-gray-800">Maaf, ikan yang Anda cari belum tersedia</p>
                <a href="{{ route('pembeli.pencarian-ikan') }}" class="mt-4 inline-flex rounded-lg bg-[#1b4d3e] px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-900">Tampilkan Semua Ikan</a>
            </div>
        @endforelse

        @if ($products->hasPages())
            <div>{{ $products->links() }}</div>
        @endif
    </section>
@endsection
