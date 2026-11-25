<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PencarianIkanController extends Controller
{
    public function index(Request $request, $query)
    {
        $validated = $request->validate([
            'q'      => ['nullable', 'string'],
            'search' => ['nullable', 'string'],
        ]);
        $keyword = Str::squish(trim($validated['search'] ?? $validated['q'] ?? ''));

        $query->when($keyword !== '', function ($query) use ($keyword) {
            $like = '%' . Str::lower($keyword) . '%';

            $query->where(function ($query) use ($like) {
                $query->whereRaw('LOWER(name) LIKE ?', [$like])
                    ->orWhereRaw('LOWER(hub_name) LIKE ?', [$like])
                    ->orWhereRaw('LOWER(description) LIKE ?', [$like])
                    ->orWhereHas('category', function ($categoryQuery) use ($like) {
                        $categoryQuery->whereRaw('LOWER(name) LIKE ?', [$like]);
                    });
            });
        });

        return $keyword;
    }
}