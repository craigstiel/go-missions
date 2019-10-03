<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Tymon\JWTAuth\Facades\JWTAuth;

class DictsController extends Controller
{
    public function masters()
    {
        $masters = DB::table('users as u')
            ->leftJoin('user_position as up', 'u.id', '=', 'up.user_id')
            ->where('up.is_active', '=', true)
            ->select('u.id', 'u.name')
            ->get();

        return response()->json(['masters' => $masters, 'master' => auth()->user()]);
    }

    public function new_masters()
    {
        $masters = DB::table('users as u')
            ->leftJoin('user_position as up', 'u.id', '=', 'up.user_id')
            ->where('up.is_active', '=', false)
            ->select('u.id', 'u.name')
            ->get();

        return response()->json(['new_masters' => $masters]);
    }

    public function task_types()
    {
        $task_types = DB::table('task_types')
            ->select('id', 'name', 'system_name', 'color')
            ->get();

        return response()->json(['task_types' => $task_types]);
    }

    public function get_multiple()
    {
        $multiple = DB::table('system')
            ->latest('id')
            ->select('multiple')
            ->first();

        return response()->json(['multiple' => $multiple->multiple]);
    }

    public function new_tasks()
    {
       return response()->json(['tasks' => $this->correct_task(0)]);
    }

    public function active_tasks()
    {
        return response()->json(['tasks' => $this->correct_task(1)]);
    }

    public function completed_tasks()
    {
        return response()->json(['tasks' => $this->correct_task(2)]);
    }

    public function correct_task($status_id)
    {
        $tasks = DB::table('tasks as t')
            ->where('t.status', '=', $status_id)
            ->select('t.title','t.id as id', 't.description', 'tt.name as type', 'tt.color as type_color', 'c.name as client', 'c.phone as client_phone',
                'c.email as client_email', 'm.name as master', 't.created_at')
            ->leftJoin('task_types as tt', 'tt.id', 't.type')
            ->leftJoin('users as c', 'c.id', 't.created_by')
            ->leftJoin('users as m', 'm.id', 't.master')
            ->get()
            ->transform(function ($item, $key) {
                $item->short_description = mb_substr($item->description, 0, 100);
                if (strlen($item->short_description) < strlen($item->description)) {
                    $item->short_description = $item->short_description . '...';
                }
                return $item;
            });

        foreach ($tasks as $task) {
            $user = DB::table('user_position')->where('user_id', '=', JWTAuth::user()->id)->first();
            $task->user_position = $user->position;

            $images = DB::table('tasks_images')->where('task_id', '=', $task->id)->get();
            $task->images = [];
            foreach ($images as $image) {
                $image->code = Storage::get('tasks/' . $image->name);
                array_push($task->images, $image);
            }
        }
        return $tasks;
    }

    public function profile() {
        $user = DB::table('users as u')
            ->where('u.id', '=', JWTAuth::user()->id)
            ->leftJoin('user_position as up', 'up.user_id', 'u.id')
            ->select('u.id', 'u.name', 'u.email', 'u.phone', 'u.telegram', 'up.position', 'up.is_active', 'u.password')
            ->first();

        $user->is_active = $user->is_active === 1 ? 'Yes' : 'No';

        return response()->json(['profile' => $user]);
    }
}
