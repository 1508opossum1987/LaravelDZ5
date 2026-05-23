<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'active' => $this->active ? 'ACTIVE' : 'INACTIVE',
            'category_id' => $this->category_id,
            'country_id' => $this->country_id,
            'brand_id' => $this->brand_id,
            'created_at'=>$this->created_at
        ];
    }
}
