<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DictsController extends Controller
{
    public function masters() {
        $masters = DB::table('users as u')
            ->leftJoin('user_position as up', 'u.id', '=', 'up.user_id')
            ->where('up.is_active', '=', true)
            ->select('u.id', 'u.name')
            ->get();

        return response()->json(['masters' => $masters]);
    }

    public function new_masters() {
        $masters = DB::table('users as u')
            ->leftJoin('user_position as up', 'u.id', '=', 'up.user_id')
            ->where('up.is_active', '=', false)
            ->select('u.id', 'u.name')
            ->get();

        return response()->json(['new_masters' => $masters]);
    }

    public function task_types() {
        $task_types = DB::table('task_types')
            ->select('id', 'name', 'system_name', 'color')
            ->get();

        return response()->json(['task_types' => $task_types]);
    }

    public function get_multiple() {
        $multiple = DB::table('system')
            ->latest('id')
            ->select('multiple')
            ->first();

        return response()->json(['multiple' => $multiple->multiple]);
    }
}
