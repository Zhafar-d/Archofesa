<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRoomRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->role === 'admin';
    }

    public function rules(): array
    {
        $roomId = $this->route('room')->id;

        return [
            'room_code' => ['required', 'string', 'max:255', 'unique:rooms,room_code,' . $roomId],
            'size' => ['required', 'string', 'max:255'],
            'price_monthly' => ['required', 'numeric', 'min:0'],
            'status' => ['required', 'string'],
            'description' => ['nullable', 'string'],
            'image_url' => ['nullable', 'url'],
        ];
    }
}
