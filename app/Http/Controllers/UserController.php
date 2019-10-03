<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
    }

    public function edit_password () {

    }
}
