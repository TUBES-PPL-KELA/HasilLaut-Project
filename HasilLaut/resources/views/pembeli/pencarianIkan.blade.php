@extends('layouts.pembeli')

@section('title', 'Cari Produk | HasilLaut')

@section('content')
    <section class="rounded-2xl bg-midnight-teal px-6 py-8 text-white sm:px-8" aria-labelledby="search-title">
        <p class="text-xs font-semibold uppercase tracking-wider text-pale-ice">Katalog Hasil Laut</p>
        <h1 id="search-title" class="mt-2 text-2xl font-bold sm:text-3xl">Cari Produk Segar</h1>
        <p class="mt-2 max-w-2xl text-sm leading-6 text-white/75">Temukan ikan favorit berdasarkan nama produk atau Hub Pesisir.</p>

        <form id="product-search-form" action="{{ route('pembeli.pencarian-ikan') }}" method="GET" class="mt-6 flex flex-col gap-2 sm:flex-row">
            <label class="sr-only" for="search-query">Nama ikan atau Hub Pesisir</label>
            <input
                id="search-query"
                data-testid="product-search-input"
                type="search"
                name="q"
                value="{{ $search }}"
                placeholder="Contoh: Kakap Merah atau Pangandaran"
                autocomplete="off"
                class="min-h-12 flex-1 rounded-lg border border-white/30 bg-white px-4 text-sm text-slate-900 placeholder:text-slate-400 focus:border-pale-ice focus:outline-none focus:ring-2 focus:ring-pale-ice"
            >
            <button type="submit" class="min-h-12 rounded-lg bg-soft-seafoam px-6 text-sm font-bold text-white transition hover:bg-ocean-teal">
                <i class="bi bi-search mr-2" aria-hidden="true"></i>Cari Produk
            </button>
        </form>
    </section>

    <section id="search-results" data-testid="search-results" class="mt-8" aria-labelledby="results-title">
        <div class="mb-4 flex flex-wrap items-end justify-between gap-3">
            <div>
                <h2 id="results-title" class="text-xl font-bold text-midnight-teal">
                    {{ $search === '' ? 'Semua Produk' : 'Hasil Pencarian' }}
                </h2>
                <p class="mt-1 text-sm text-slate-500">
                    {{ $search === '' ? 'Jelajahi produk hasil laut yang tersedia.' : 'Kata kunci: ' . $search }}
                </p>
            </div>
            @if($search !== '')
                <a href="{{ route('pembeli.pencarian-ikan') }}" class="text-sm font-semibold text-ocean-teal hover:text-midnight-teal">
                    Hapus pencarian
                </a>
            @endif
        </div>

        @if($products->isEmpty())
            <div data-testid="search-empty-state" class="rounded-xl border border-slate-200 bg-white px-6 py-12 text-center">
                <i class="bi bi-search text-3xl text-slate-300" aria-hidden="true"></i>
                <p class="mt-3 font-semibold text-slate-700">Produk tidak ditemukan</p>
                <p class="mt-1 text-sm text-slate-500">Coba kata kunci nama ikan atau Hub Pesisir lainnya.</p>
            </div>
        @else
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-3">
                @foreach($products as $product)
                    <article data-testid="search-result-card" class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
                        <div class="h-44 overflow-hidden bg-slate-100">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" loading="lazy" class="h-full w-full object-cover">
                            @else
                                <div class="grid h-full place-items-center text-sm text-slate-400">Foto produk belum tersedia</div>
                            @endif
                        </div>
                        <div class="p-4">
                            <h3 data-testid="search-result-name" class="font-bold text-midnight-teal">{{ $product->name }}</h3>
                            <p data-testid="search-result-hub" class="mt-2 text-sm text-slate-500">
                                <i class="bi bi-geo-alt-fill mr-1 text-ocean-teal" aria-hidden="true"></i>{{ $product->hub_name ?: 'Hub belum ditentukan' }}
                            </p>
                            <div class="mt-4 flex items-center justify-between gap-3">
                                <span class="font-extrabold text-ocean-teal">Rp {{ number_format($product->price, 0, ',', '.') }} / kg</span>
                                <span class="text-xs text-slate-500">Stok {{ number_format($product->available_stock ?? 0, 0, ',', '.') }} kg</span>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </section>
@endsection
