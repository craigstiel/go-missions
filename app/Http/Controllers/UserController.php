<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{
    public function edit_user (Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        $user = User::where('id', '=', JWTAuth::user()->id)->first();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->telegram = $request->telegram;
        $user->save();

        return response()->json(compact('user'),201);
    }

    public function edit_password (Request $request) {
        $validator = Validator::make($request->all(), [
            'password' => 'required',
        ]);

        $user = User::where('id', '=', JWTAuth::user()->id)->first();
        $user->password = bcrypt($request->password);
        $user->save();
        return response()->json(compact('user'),201);
    }

    public function is_admin () {
        $user = \DB::table('users as u')
            ->leftJoin('user_position as up', 'u.id', 'up.user_id')
            ->where('u.id', '=', Auth::user()->id)
            ->first();

        if(isset($user)) {
            $user_status = $user->position;
        } else {
            $user_status = 'company';
        }
        return response()->json(compact('user_status'),201);
    }
}
