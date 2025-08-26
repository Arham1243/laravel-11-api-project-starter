<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SignupResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'status' => true,
            'message' => 'User registered successfully',
        ];
    }
}
