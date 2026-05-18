<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

trait Filters
{
    public function scopeFilter(
        Builder $query,
        Request $request
    ): Builder
    {
        $query->when($request->has('name'), function ($q) use ($request) {
            $q->where('name', 'like', '%' . $request->name . '%');
        })
            ->when($request->has('active'), function ($q) use ($request) {
                $q->where('active', $request->get('active'));
            })->when($request->has('start_date'), function ($q) use ($request) {
                $q->whereDate('created_at', '>=', $request->get('start_date'));
            })->when($request->has('end_date'), function ($q) use ($request) {
                $q->whereDate('created_at', '<=', $request->get('end_date'));
            })->when($request->has('has_image'), function ($q) use ($request) {
                if ($request->get('has_image') == 'yes') {
                    $q->has('image');
                } elseif ($request->get('has_image') == 'no') {
                    $q->doesntHave('image');
                }
            });

        return $query;
    }
}
