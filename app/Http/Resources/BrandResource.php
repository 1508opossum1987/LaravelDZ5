<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BrandResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'active' => $this->active ? 'ACTIVE' : 'INACTIVE',
            'created_at' => $this->created_at,
            'url' => $this->whenLoaded('image', fn() => $this->image?->url)
        ];
    }
}
