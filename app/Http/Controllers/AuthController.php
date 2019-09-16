<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;
use App\User;

class AuthController extends Controller
{
    public function register(Request $request) {
        $user = new User();
        $user->email = $request->email;
        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->telegram = $request->telegram;
        $user->is_master = $request->is_master;
        $user->password = bcrypt($request->password);
        $user->save();

        if($request->is_master === true) {
            DB::table('user_position')
                ->insert([
                    'user_id' => $user->id,
                    'position' => 'master',
                    'is_active' => false,
                    'created_at' => DB::raw('current_timestamp')
                ]);
        }
        $user->sendEmailVerificationNotification();
        return response([
            'status' => 'success',
            'data' => $user
        ], 200);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if (!$token = JWTAuth::attempt($credentials)) {
            return response([
                'status' => 'error',
                'error' => 'invalid.credentials',
                'msg' => 'Invalid Credentials.'
            ], 400);
        }
        return response([
            'status' => 'success'
        ])
            ->header('Authorization', $token);
    }

    public function user(Request $request)
    {
        $user = User::find(Auth::id());
        return response([
            'status' => 'success',
            'data' => $user
        ]);
    }

    public function refresh()
    {
        return response([
            'status' => 'success'
        ]);
    }

    public function logout()
    {
        JWTAuth::invalidate();
        return response([
            'status' => 'success',
            'msg' => 'Logged out Successfully.'
        ], 200);
    }
}
