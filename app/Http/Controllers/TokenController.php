<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User_token;

class TokenController extends Controller
{
    public static function create_user_token($uid) {
        return self::insert_token($uid, self::generate_token());
    }

    public static function remove_user_tokens($uid) {
        return self::delete_user_tokens($uid);
    }

    private static function generate_token() {
        $userToken = '';
        $chars = ['h', 'e', 'L', 'l', 'O', 'w', 'o', 'r', 'l', 'D', '!'];

        foreach($chars as $key => $value) {
            $userToken = $userToken . $chars[rand(0, (count($chars) - 1))];
        }

        return $userToken;
    }

    private static function insert_token($uid, $token) {
        User_token::create([
            'user_id' => $uid,
            'token' => $token
        ]);
        return $token;
    }

    private static function delete_user_tokens($uid) {
        User_token::where('user_id', $uid)->delete();
        return true;
    }
}
