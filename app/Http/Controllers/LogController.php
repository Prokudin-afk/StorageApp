<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Log;

class LogController extends Controller
{
    public static function log($action, $data, $user) {
        Log::create([
            'action' => $action,
            'data' => $data,
            'user_id' => $user
        ]);
        return true;
    }
}
