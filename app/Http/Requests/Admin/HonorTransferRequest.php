<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class HonorTransferRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'bukti_transfer' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'catatan'        => 'nullable|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'bukti_transfer.required' => 'Bukti transfer wajib diupload.',
            'bukti_transfer.max'      => 'Ukuran file maksimal 5MB.',
        ];
    }
}
