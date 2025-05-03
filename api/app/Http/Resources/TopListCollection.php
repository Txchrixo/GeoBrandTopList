<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Resources\BrandResource;
class TopListCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        $countryCode = $this->additional['country']
        ?? config('toplist.default_country');

        return [
            'country' => $countryCode,
            'brands'  => BrandResource::collection($this->collection),
        ];
    }
}
