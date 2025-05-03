<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Enums\ApiStatus;
use App\Enums\Messages;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Resources\UserResource;

class LoginResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array        
    {
        return [
            'token_type'    => 'Bearer',
            'access_token'  => $this->resource->token,
            'user'          => new UserResource($this->resource->user),
        ];
    }
}
