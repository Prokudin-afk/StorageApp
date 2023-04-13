<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Http\Controllers\TokenController;

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


        TokenController::remove_user_tokens($user['id']);       //удалим старые токены
        $result['token'] = TokenController::create_user_token($user['id']);     //сделаем новый
        $result['code'] = 120;
        return $result;
    }

    public function get_user_by_token($token) {
        return $this->select_user_by_token($token);
    }

    private function select_user_by_token($token) {
        User::select('users.id', 'roles.name')
            ->join('roles', 'roles.id', '=', 'users.role_id')
            ->join('user_tokens', 'user_tokens.user_id', '=', 'users.id')
            ->where('user_tokens.token', $token)
            ->first();
    }
}
