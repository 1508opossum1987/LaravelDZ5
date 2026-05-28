<?php

namespace App\Http\Requests\Basket;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateBasketItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            'quantity' => 'required|integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'quantity.required' => 'Количество обязательно для заполнения!',
            'quantity.integer' => 'Количество должно быть целым числом!',
            'quantity.min' => 'Количество не может быть меньше 1!',
        ];
    }
}
