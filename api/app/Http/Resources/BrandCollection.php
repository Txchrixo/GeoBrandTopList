<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Resources\BrandResource;
use App\Models\Country;
use Illuminate\Support\Facades\Auth;

class BrandCollection extends ResourceCollection
{
    public $collects = BrandResource::class;

    public function toArray($request)
    {
        return [
            'brands' => $this->collection,
            'metadata' => $this->when(
                isset($this->additional['metadata']), 
                function () {
                    return $this->additional['metadata'];
                }
            ),
            'country' => $this->when(
                !auth('sanctum')->check(),
                function () {
                    return [
                        'name' => $this->resource->first()->country->country_name,
                        'code' => $this->resource->first()->country->country_code_cca2,
                        'flag_url' => $this->resource->first()->country->country_flag_url,
                    ];
                }
            )
        ];
    }
}
