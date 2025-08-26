<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LoginResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'status' => true,
            'message' => 'User logged in successfully',
            'access_token' => $this->access_token ?? null,
            'expires_in' => $this->expires_in ?? null,
        ];
    }
}
