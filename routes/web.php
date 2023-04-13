<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Http\Controllers\UserController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\PermissionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('index');
});

Route::post('/logIn', [UserController::class, 'log_in']);
//Route::post('/addEquipment', [EquipmentController::class, 'add_equipment']);
Route::post('/addEquipment', function(Request $request) {
    return $request->token;
    return PermissionController::check_permission($request->token, 'addEquipment');
});