<?php

namespace App\Http\Controllers;

use App\UserPosition;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;
use App\User;

class AuthController extends Controller
{
    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        $verification_code = str_random(30);

        $user = new User();
        $user->email = $request->email;
        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->telegram = $request->telegram;
        $user->is_master = $request->is_master;
        $user->password = bcrypt($request->password);
        $user->api_token = $verification_code;
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

        $subject = Lang::get('mail.verify');
        $email = $user->email;
        $name = $user->name;

        Mail::send('email.verify', ['id' => $user->id],
            function($mail) use ($email, $name, $subject){
                $mail->from('craigstiel@gmail.com', 'missioner');
                $mail->to($email, $name);
                $mail->subject($subject);
            });

        return response()->json(compact('user'),201);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        $user = DB::table('users')->where('email', $credentials['email'])->first();
        if($user->email_verified_at !== null) {
            if ( ! $token = JWTAuth::attempt($credentials)) {
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
        else {
            return response([
                'status'=> 'error',
                'message'=> 'Account wasn\'t verified.'
            ], 400);
        }
    }

    public function user(Request $request)
    {
        $user = User::find(Auth::user()->id);
        return response([
            'status' => 'success',
            'data' => $user
        ]);
    }

    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

    public function verify($id) {
        $user = DB::table('users')
            ->where('id', $id)
            ->first();

        if($user->email_verified_at !== null){
            return response()->json([
                'success'=> true,
                'message'=> 'Account already verified.'
            ]);
        }

        DB::table('users')
            ->where('id', $id)
            ->update([
                'api_token' => null,
                'email_verified_at' =>DB::raw('current_timestamp')
            ]);

        return redirect()->guest('/#/login');
    }
}
