<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Equipment;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\UserController;

require_once(dirname(__FILE__) . '/LogController.php');

class EquipmentController extends Controller
{
    public function add_equipment(Request $request) {
        $validated = $request->validate([
            'name' => 'required|max:100',
            'cost' => 'required|max:100',
            'serial_num' => 'required|max:100',
            'inventory_num' => 'required|max:100',
            'token' => 'required|max:100',
        ]);

        $user = UserController::get_user_by_token($request->token);
        if(!$user) {
            $itog['code'] = 101;        //не авторизован
            print_r(json_encode($itog));
            return;
        }

        $json = json_encode([
            'name' => $request->name,
            'cost' => $request->cost,
            'serial_num' => $request->serial_num,
            'inventory_num' => $request->inventory_num
        ]);

        if(!count(PermissionController::check_permission($request->token, 'addEquipment'))) {
            $itog['code'] = 102;        //нет прав
            print_r(json_encode($itog));
            return;
        }

        LogController::log('add_equipment', $json, $user['id']);

        Equipment::create([
            'user_id' => $user['id'],
            'name' => $request->name,
            'cost' => $request->cost,
            'serial_num' => $request->serial_num,
            'inventory_num' => $request->inventory_num
        ]);

        $itog['code'] = 120;        //успех
        print_r(json_encode($itog));
        return;
    }
}
