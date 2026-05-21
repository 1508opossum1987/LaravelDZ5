<?php

namespace App\Models;

use App\Traits\Filters;
use App\Traits\Sortable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $name
 * @property bool $active
 */
class Brand extends Model
{
    use SoftDeletes, Filters, Sortable;

    protected $fillable = ['name', 'active'];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function image(): MorphOne
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    public function getCreatedAtAttribute(): string
    {
        return $this->attributes['created_at'];
    }
}
