<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repository\UserRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Socialite\Facades\Socialite;

use function Laravel\Prompts\password;

class LoginController extends Controller
{
    //
    protected $userRepository;
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

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


        if (Auth::attempt(['email' => $request->input('email'), 'password' => $request->input('password')])) {
            $user = Auth::user();
            $tokenResult = $user->createToken("token");

            if ($request->input('remember')) {
                $tokenResult->token->expires_at = now()->addMonth(1);;
            }

            return response()->json([
                'status' => 'success',
                'expries_at' => $tokenResult->token->expires_at->toDateTimeString(),
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

    // handle login social
    public function redirectToGoogle()
    {

        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        $user = Socialite::driver('google')->user();
        $existingUser = $this->userRepository->findByEmail($user->email);

        if (!$existingUser) {
            $newUser = $this->userRepository->create([
                'email' => $user->email,
                "name" => $user->name,
                'social_id' => $user->token,
                'max_storage' => '10240',
            ]);
            $tokenResult = $newUser->createToken('social');
        } else {
            $tokenResult = $existingUser->createToken('social');
        }

        return response()->json([
            'status' => 'success',
            'expries_at' => $tokenResult->token->expires_at,
            'access_token' => $tokenResult->accessToken
        ], 200);
    }
}
