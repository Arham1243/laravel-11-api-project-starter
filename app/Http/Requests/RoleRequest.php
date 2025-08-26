<?php

namespace App\Http\Requests;

use Orion\Http\Requests\Request;

class RoleRequest extends Request
{
    public function commonRules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:roles,name'],
            'status' => ['sometimes', 'string', 'in:active,inactive'],
        ];
    }
}
