<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\File;

/**
 * @property int $id
 * @property int $category_id
 * @property int $country_id
 * @property int $brand_id
 * @property float $price
 * @property float $discount_price
 * @property float $price_from
 * @property bool $active
 * @property string $name
 * @property string $description
 * @property string $img_path
 */
class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id',
        'country_id',
        'brand_id',
        'name',
        'description',
        'active',
        'img_path',
        'price',
        'discount_price',
        'price_from'
    ];

    public function category(): belongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function brand(): belongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function country(): belongsTo
    {
        return $this->belongsTo(Country::class);
    }
}
