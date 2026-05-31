<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $user_id
 * @property bool $status
 */
class Basket extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'status'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User:: class, 'user_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(BasketItem::class, 'basket_id');
    }

    public function totalSum(): float
    {
        $total = 0;

        foreach ($this->items as $item) {
            $price = $item->product->discount_price ?? $item->product->price;
            $total += $price * $item->quantity;
        }

        return round($total, 2);
    }

    public function totalItems(): int
    {
        $total = 0;

        foreach ($this->items as $item) {
            $total += $item->quantity;
        }

        return $total;
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isProcessing(): bool
    {
        return $this->status === 'processing';
    }



}
