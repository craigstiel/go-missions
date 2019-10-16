<?php

namespace App\Http\Controllers;

use App\TaskImages;
use App\Tasks;
use App\TaskTypes;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class TaskController extends Controller
{
    public function index()
    {
        return view('spa');
    }

    public function add_task(Request $request) {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        $master = DB::table('user_position')->where('position', '=', 'admin')->first();
        $type = DB::table('task_types')->first();

        if(isset($type))
            $type = $type->id;
        else {
            $new_type = new TaskTypes();
            $new_type->system_name = 'unknown';
            $new_type->name = 'unknown_type';
            $new_type->color = '#00BCFF';
            $new_type->created_at = Carbon::now();
            $new_type->save();
            $type = $new_type->id;
        }

        if (JWTAuth::check()) {
            $task = new Tasks();
            $task->title = $request->title;
            $task->description = $request->description;
            $task->type = $request->type ? $request->type : $type;
            $task->status = 0;
            $task->priority = $request->priority ? $request->priority : 'medium';
            $task->created_by = JWTAuth::user()->id;
            $task->master = $request->master ? $request->master : $master->user_id;
            $task->created_at = Carbon::now();
            $task->save();
        }

        foreach ($request->images as $image) {
            $fileName = Carbon::now()->timestamp . '_' . uniqid() . '.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
            Storage::put('tasks/'.$fileName, $image);

            DB::table('tasks_images')->insert([
                'name' => $fileName,
                'task_id' => $task->id,
                'created_at' => DB::raw('current_timestamp')
            ]);
        }

        return response([
            'status' => 200,
            'data' => $task
        ]);
    }

    public function to_work($id) {
        $task = Tasks::where('id', $id)->first();
        $task->status = 1;
        $task->save();

        return response([
            'status' => 'success',
            'data' => $task
        ]);
    }

    public function done($id) {
        $task = Tasks::where('id', $id)->first();
        $task->status = 2;
        $task->save();

        return response([
            'status' => 'success',
            'data' => $task
        ]);
    }

    public function delete_task($id) {
        TaskImages::where('task_id', $id)->delete();
        Tasks::where('id', $id)->delete();

        return response([
            'status' => 'success'
        ]);
    }

    public function count() {
        $tasks_count = Tasks::where('master', Auth::user()->id)->where('status', '0')->count();
        $tasks_count = DB::table('tasks as t')
            ->where('t.master', '=', Auth::user()->id)
            ->where('t.status', '0')
            ->leftJoin('user_position as up', 'up.user_id', 't.master')
            ->where('up.is_active', true)
            ->count();

        return response()->json(['tasks_count' => $tasks_count]);
    }

    public function change_master (Request $request, $id) {
        $task = Tasks::find($id);
        $task->master = $request->master;
        $task->save();

        return response([
            'status' => 'success'
        ]);
    }
}
