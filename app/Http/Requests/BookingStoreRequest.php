<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookingStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->role === 'user';
    }

    public function rules(): array
    {
        return [
            'room_id' => ['required', 'exists:rooms,id'],
            'move_in_date' => ['required', 'date'],
            'move_out_date' => ['required', 'date', 'after_or_equal:move_in_date'],
        ];
    }
}
