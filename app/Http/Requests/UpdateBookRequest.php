<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateBookRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'category_id' => 'required|integer|exists:categories,id',
            'title' => 'required|string|max:255',
            'writer' => 'required|string|max:255',
            'release_date' => 'required|date',
        ];
    }

    public function messages()
    {
        return [
            'category_id.required' => 'Kategori Wajib Ada',
            'category_id.exists' => 'Kategori Tidak Ditemukan',
            'title.required' => 'Judul Wajib Diisi',
            'title.max' => 'Maksimal Panjang Judul :max karakter',
            'writer.required' => 'Penulis Wajib Diisi',
            'writer.max' => 'Maksimal Panjang Penulis :max karakter',
            'release_date.required' => 'Tanggal Terbit Wajib Diisi',
            'release_date.date' => 'Format Tanggal Terbit Tidak Valid',
        ];
    }
}
