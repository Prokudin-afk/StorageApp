<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;

class UserController extends Controller
{
    public function log_in(Request $request) {
        $validated = $request->validate([
            'login' => 'required|max:100',
            'pass' => 'required|max:100',
        ]);
        
        $login = $request->login;
        $pass = $request->pass;
        
        $user = User::select('users.id', 'users.login', 'users.password', 'roles.name as role')
            ->join('roles', 'roles.id', '=', 'users.role_id')
            ->where('users.login', $login)
            ->first();

        if(!$user) {        //пользователь не найден
            $result['code'] = 101;
            return $result;
        }

        if($user['password'] != $pass) {        //неверный пароль
            $result['code'] = 102;
            return $result;
        }

        setcookie("user_id", $user['id'], time() + 3600);
        setcookie("user_role", $user['role'], time() + 3600);
        $result['code'] = 120;
        return $result;
    }

    public function log_out(Request $request) {
        setcookie ("user_id", "", time() - 3600);
        setcookie ("user_role", "", time() - 3600);
        return true;
    }
}
