<?php


namespace App\Traits;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

trait Sortable
{
    public function scopeSortable(
        Builder $query,
        Request $request
    ): Builder {
        $sortBy=$request->get('sort_by', 'created_at');
        $sortDirection = $request->get('sort_direction', 'asc');

        return $query->orderBy($sortBy, $sortDirection);
    }
}
