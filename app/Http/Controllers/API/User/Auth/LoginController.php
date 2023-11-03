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
use Illuminate\Support\Facades\Validator;
use Laravel\Socialite\Facades\Socialite;
use function Laravel\Prompts\password;
use Laravel\Passport\Client;

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
        $client = Client::where('password_client', 1)->first();

        if (Auth::attempt(['email' => $request->input('email'), 'password' => $request->input('password')])) {
            $user = Auth::user();
            $user->last_login_date = now();
            $user->save();
            $tokenResult = $user->createToken('Personal Access Token');

            return HttpResponse::resJsonSuccess([
                'token_type' => "Bearer",
                'access_token' => $tokenResult->accessToken
            ], "User Logged In Successfully");
        }

        return HttpResponse::resJsonFail("Email or password is incorrect.", 401, "Unauthorised");
    }

    //refresh token
    // public function refreshToken(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'refresh_token' => 'required',
    //     ]);
    //     if ($validator->fails()) {
    //         return HttpResponse::resJsonFail($validator->errors()->toArray(), 400);
    //     }
    //     $client = Client::where('password_client', 1)->first();

    //     $response = Http::asForm()->post('http://192.168.131.128/oauth/token', [
    //         'grant_type' => 'refresh_token',
    //         'refresh_token' => $request->refresh_token,
    //         'client_id' => $client->id,
    //         'client_secret' => $client->secret,
    //         'scope' => '',
    //     ]);
    //     return HttpResponse::resJsonSuccess($response->json());
    // }

    // handle login social
    public function redirectToProvider($provider)
    {
        $validated = $this->validateProvider($provider);

        if (!is_null($validated)) {
            return $validated;
        }

        return HttpResponse::resJsonSuccess(['url' =>  Socialite::driver('google')->stateless()->redirect()->getTargetUrl()]);
    }

    public function handleGoogleCallback()
    {
        $user =  Socialite::driver('google')->stateless()->user();

        if (!$user) {
            HttpResponse::resJsonNotFond(null);
        }

        $userCreated = User::firstOrCreate(
            ['email' => $user->email],
            [
                'email_verified_at' => now(),
                'social_id' => $user->getId(),
                "name" => $user->name,
                'max_storage' => '10240',
            ]
        );

        $token = $userCreated->createToken("access token");
        return HttpResponse::resJsonSuccess([
            'token_type' => "Bearer",
            'access_token' => $token->accessToken
        ], "User Logged In Successfully");
    }


    public function ahandleGoogleCallback()
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
            'expires_at' => $tokenResult->token->expires_at,
            'access_token' => $tokenResult->accessToken
        ], 200);
    }
}
