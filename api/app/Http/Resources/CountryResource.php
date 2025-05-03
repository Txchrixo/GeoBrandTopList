<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CountryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->country_id,
            'name'       => $this->country_name,
            'code'       => $this->country_code_cca2,
            'flag_url'   => $this->country_flag_url,
        ];
    }
}
