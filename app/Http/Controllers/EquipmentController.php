<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

require_once(dirname(__FILE__) . '/LogController.php');

class EquipmentController extends Controller
{
    public function add_equipment(Request $request) {
        $validated = $request->validate([
            'name' => 'required|max:100',
            'cost' => 'required|max:100',
            'serial_num' => 'required|max:100',
            'inventory_num' => 'required|max:100',
        ]);

        $json = json_encode([
            'name' => $request->name,
            'cost' => $request->cost,
            'serial_num' => $request->serial_num,
            'inventory_num' => $request->inventory_num
        ]);

        LogController::log('add_equipment', $json, $_COOKIE['user_id']);
    }
}
