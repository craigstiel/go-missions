<?php

namespace App\Http\Controllers;

use App\System;
use App\TaskTypes;
use App\User;
use App\UserPosition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SettingsController extends Controller
{
    public function delete_master($id) {
        $user = User::where('id', $id)->first();
        $user->is_master = false;
        $user->save();

        UserPosition::where('user_id', $id)->delete();

        return response([
            'status' => 'success',
            'data' => $user . 'was deleted as a master.'
        ], 200);
    }

    public function approve_master($id) {
        $position = UserPosition::where('user_id', $id)->first();
        $position->is_active = true;
        $position->save();

        return response([
            'status' => 'success',
            'data' => 'Master was approved.'
        ], 200);
    }

    public function disapprove_master($id) {
        $position = UserPosition::where('user_id', $id)->first();
        $position->is_active = false;
        $position->save();

        return response([
            'status' => 'success',
            'data' => 'Master was disapproved.'
        ], 200);
    }

    public function approve_multiple(Request $request) {
        $system = new System();
        $system->multiple = $request->multiple;
        $system->save();

        if($request->multiple === false) {
            DB::table('user_position')->update(['is_active' => false]);
        }

        return response([
            'status' => 'success',
            'data' => 'System settings were changed.'
        ], 200);
    }

    public function add_type(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'system_name' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        $type = new TaskTypes();
        $type->name = $request->name;
        $type->system_name = $request->system_name;
        $type->color = $request->color;
        $type->save();

        return response([
            'status' => 'success',
            'data' => 'Task type was added.'
        ], 200);
    }

    public function delete_type($id) {
        TaskTypes::where('id', $id)->delete();

        return response([
            'status' => 'success',
            'data' => 'Task type was deleted.'
        ], 200);
    }
}
