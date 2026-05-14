<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class HonorTransferRequest extends FormRequest
{
    public function rules()
    {
        return [
            'bukti_transfer' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'catatan'        => 'nullable|string|max:255',
        ];
    }
}
