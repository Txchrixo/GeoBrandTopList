<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BrandResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->brand_id,
            'name'          => $this->brand_name,
            'url'           => $this->brand_url,
            'image_url'     => $this->brand_img_url,
            'rating'        => $this->brand_rating,
            'is_active'     => $this->when(auth('sanctum')->check(), function () {
                return (bool) $this->is_active;
            }),
            'country_id'    => $this->brand_country_id,
        ];
    }
}
