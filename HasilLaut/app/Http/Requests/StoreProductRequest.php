<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Ubah ke true agar form diizinkan untuk diproses
    }

    public function rules(): array
    {
        return [
            'name'                     => 'required|string|max:45',
            'categories_id_categories' => 'required|exists:categories,id_categories',
            'max_storage_days'         => 'required|integer|min:1',
            'description'              => 'required|string',
            'purchase_price'           => 'required|numeric|min:0',
            'price'                    => 'required|numeric|min:0',
            'stock'                    => 'required|integer|min:1', // Validasi cegah minus sesuai proposal
            'received_date'            => 'required|date',
            'image'                    => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ];
    }
}