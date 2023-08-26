<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use function Laravel\Prompts\password;

class UserController extends Controller
{
    //
    public function login(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',

        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'fails',
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors()->toArray(),
            ], 500);
        }
        $remember = $request->input('remember');

        if (Auth::attempt(['email' => $request->input('email'), 'password' => $request->input('password')], $remember)) {
            $user = Auth::user();
            $tokenResult = $user->createToken("token");
            $token = $tokenResult->token;
            // $token->save();
            return response()->json([
                'status' => 'success',
                'access_token' => $tokenResult->accessToken
            ], 200);
        }

        return response()->json(
            [
                'status' => 'fails',
                'message' => 'Unauthorised'
            ],
            401
        );
    }
}
