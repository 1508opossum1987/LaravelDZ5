<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BasketItem extends Model
{
    use HasFactory;

    protected $fillable=[
        'basket_id',
        'product_id',
        'quantity'
    ];

    public function basket(): BelongsTo
    {
        return $this->BelongsTo(Basket::class, 'basket_id');
    }

    public function product(): BelongsTo
    {
        return $this->BelongsTo(Product::class, 'product_id');
    }

    public function subtotal(): float
    {
        $price = $this->product->discount_price ?? $this->product->price;

        return round($price * $this->quantity, 2);
    }
}


