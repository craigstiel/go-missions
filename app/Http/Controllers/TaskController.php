<?php

namespace App\Http\Controllers;

use App\Tasks;
use App\TaskTypes;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

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

        $task = new Tasks();
        $task->title = $request->title;
        $task->description = $request->description;
        $task->type = $request->type ? $request->type : $type;
        $task->priority = $request->priority ? $request->priority : 'medium';
        $task->master = $request->master ? $request->master : $master->id;
        $task->created_at = Carbon::now();
        $task->save();

        foreach ($request->images as $image) {
            $fileName = Carbon::now()->timestamp . '_' . uniqid() . '.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
            Storage::put('members/'.$fileName, $image);

            DB::table('tasks_images')->insert([
                'name' => $fileName,
                'task_id' => $task->id,
                'created_at' => DB::raw('current_timestamp')
            ]);
        }

    }
}
