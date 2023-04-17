<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Equipment;
use App\Models\Storage;
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

        if(!count(PermissionController::check_permission($request->token, 'addEquipment'))) {
            $itog['code'] = 102;        //нет прав
            print_r(json_encode($itog));
            return;
        }

        $json = json_encode([
            'name' => $request->name,
            'cost' => $request->cost,
            'serial_num' => $request->serial_num,
            'inventory_num' => $request->inventory_num
        ]);
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

    public function show_user_equipment(Request $request) {
        $validated = $request->validate([
            'token' => 'required|max:100',
            'field' => 'required|max:100',
            'value' => 'required|max:100',
        ]);

        $user = UserController::get_user_by_token($request->token);
        if(!$user) {
            $itog['code'] = 101;        //не авторизован
            print_r(json_encode($itog));
            return;
        }

        if(!count(PermissionController::check_permission($request->token, 'showUserEquipment'))) {
            $itog['code'] = 102;        //нет прав
            print_r(json_encode($itog));
            return;
        }

        $json = json_encode([
            'user_id' => $user['id'],
            'field' => $request->field,
            'value' => $request->value
        ]);
        LogController::log('show_user_equipment', $json, $user['id']);
        
        switch($request->field) {
            case 'all':
                $result['equipment'] = Equipment::select('equipment.id', 'equipment.name', 'equipment.cost', 'equipment.serial_num', 'equipment.inventory_num', 'equipment.created_at', 'equipment.updated_at')
                    ->where('equipment.user_id', $user['id'])
                    ->get();
                break;
        }
        $result['code'] = 120;
        print_r(json_encode($result));
        return;
    }

    public function move_equipment(Request $request) {
        $validated = $request->validate([
            'token' => 'required|max:100',
            'equipment' => 'required|max:100',
            'warehouse' => 'required|max:100',
        ]);

        $user = UserController::get_user_by_token($request->token);
        if(!$user) {
            $itog['code'] = 101;        //не авторизован
            print_r(json_encode($itog));
            return;
        }

        if(!count(PermissionController::check_permission($request->token, 'moveEquipment'))) {
            $itog['code'] = 102;        //нет прав
            print_r(json_encode($itog));
            return;
        }

        $json = json_encode([
            'user_id' => $user['id'],
            'equipment' => $request->equipment,
            'warehouse' => $request->warehouse
        ]);
        LogController::log('show_user_equipment', $json, $user['id']);

        Storage::create([
            'stock_id' => $request->warehouse,
            'equipment_id' => $request->equipment,
            'user_id' => $user['id']
        ]);

        $result['code'] = 120;
        print_r(json_encode($result));
        return;
    }

    public function show_moved_equipment(Request $request) {
        $validated = $request->validate([
            'token' => 'required|max:100',
            'field' => 'required|max:100',
            'value' => 'required|max:100',
        ]);

        $user = UserController::get_user_by_token($request->token);
        if(!$user) {
            $itog['code'] = 101;        //не авторизован
            print_r(json_encode($itog));
            return;
        }

        if(!count(PermissionController::check_permission($request->token, 'moveEquipment'))) {
            $itog['code'] = 102;        //нет прав
            print_r(json_encode($itog));
            return;
        }

        switch($request->field) {
            case 'all':
                $result['equipment'] = Equipment::select('equipment.id', 'equipment.name', 'equipment.cost', 'equipment.serial_num', 'equipment.inventory_num', 'equipment.created_at', 'equipment.updated_at')
                    ->join('storages', 'storages.equipment_id', '=', 'equipment.id')
                    ->where('storages.user_id', $user['id'])
                    ->get();
                break;
        }

        $result['code'] = 120;
        print_r(json_encode($result));
        return;
    }
}
