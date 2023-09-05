<?php

namespace App\Http\Controllers\API\User\Auth;

use App\Http\Controllers\Controller;
use App\Http\Libraries\HttpResponse;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Repositories\UserRepository;
use Carbon\Carbon;
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
            return HttpResponse::resJsonFail($validator->errors()->toArray(), 400);
        }

        if (Auth::attempt(['email' => $request->input('email'), 'password' => $request->input('password')])) {
            $user = Auth::user();
            $tokenResult = $user->createToken("token");

            return response()->json([
                'user' => new UserResource($user),
                'status' => 'success',
                'access_token' => $tokenResult->accessToken,
                'token_type' => 'Bearer',
                'expires_at' => Carbon::parse(
                    $tokenResult->token->expires_at
                )->toDateTimeString(),
            ], 200);
        }

        return HttpResponse::resJsonFail(null, 401, "Unauthorised");
    }

    // handle login social
    // public function redirectToGoogle()
    // {
    //     return Socialite::driver('google')->redirect();
    // }

    // public function handleGoogleCallback()
    // {
    //     $user = Socialite::driver('google')->user();
    //     $existingUser = $this->userRepository->findByEmail($user->email);

    //     if (!$existingUser) {
    //         $newUser = $this->userRepository->create([
    //             'email' => $user->email,
    //             "name" => $user->name,
    //             'social_id' => $user->token,
    //             'max_storage' => '10240',
    //         ]);
    //         $tokenResult = $newUser->createToken('social');
    //     } else {
    //         $tokenResult = $existingUser->createToken('social');
    //     }

    //     return response()->json([
    //         'status' => 'success',
    //         'expires_at' => $tokenResult->token->expires_at,
    //         'access_token' => $tokenResult->accessToken
    //     ], 200);
    // }
}
