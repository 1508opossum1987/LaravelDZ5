<?php

namespace App\Models;

use App\Traits\Filters;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $name
 * @property bool $active
 */
class Country extends Model
{

    use SoftDeletes, Filters;

    protected $fillable = ['name', 'active'];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function image(): MorphOne
    {
        return $this->morphOne(Image::class, 'imageable');
    }
}
