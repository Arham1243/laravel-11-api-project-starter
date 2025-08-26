<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoleRequest;
use Orion\Concerns\DisableAuthorization;
use Orion\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    use DisableAuthorization;

    protected $model = Role::class;

    protected $request = RoleRequest::class;
}
