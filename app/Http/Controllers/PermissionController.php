<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Permission;
use App\Models\Role_permission;
use App\Models\User;

class PermissionController extends Controller
{
    public static function check_permission($user, $permission) {
        $permission = User::select('users.login')
            ->join('roles', 'roles.id', '=', 'users.role_id')
            ->join('role_permissions', 'role_permissions.role_id', '=', 'roles.id')
            ->join('permissions', 'permissions.id', '=', 'role_permissions.permission_id')
            ->where('users.id', $user)
            ->where('permissions.name', $permission)
            ->get();

        return !!$permission;
    }
}
