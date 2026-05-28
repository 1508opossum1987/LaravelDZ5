<?php

namespace App\Http\Requests\Basket;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class AddToBasketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'items.required' => 'Необходимо передать список товаров!',
            'items.array' => 'Список товаров должен быть массивом!',
            'items.*.product_id.required' => 'ID товара обязателен!',
            'items.*.product_id.exists' => 'Выбранный товар не существует!',
            'items.*.quantity.required' => 'Количество товара обязательно!',
            'items.*.quantity.integer' => 'Количество должно быть целым числом!',
            'items.*.quantity.min' => 'Количество не может быть меньше 1!',
        ];
    }
}
