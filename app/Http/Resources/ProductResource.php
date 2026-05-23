<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;



class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $product=$this;

        return [
            'id' => $product->id,
            'name' => $product->name,
            'active' => $product->active ? 'ACTIVE' : 'INACTIVE',
            'category_id' => $product->category_id,
            'country_id' => $product->country_id,
            'brand_id' => $product->brand_id,
            'created_at'=>$product->created_at
        ];
    }
}
