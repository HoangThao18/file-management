<?php

namespace App\Http\Controllers\API\User\Auth;

use App\Http\Controllers\Controller;
use App\Http\Libraries\HttpResponse;
use App\Http\Requests\ForgotPasswordRequest;
use App\Models\PasswordReset;
use App\Notifications\ResetPasswordRequest;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ResetPasswordController extends Controller
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function sendMail(ForgotPasswordRequest $request)
    {
        $user = $this->userRepository->where('email', $request->email)->firstOrFail();
        $token =  Str::random(10);
        $passwordReset = PasswordReset::updateOrCreate([
            'email' => $user->email,
        ], [
            'token' => $token,
        ]);
        if ($passwordReset) {
            $user->notify(new ResetPasswordRequest($passwordReset->token));
        }

        return HttpResponse::resJsonSuccess(null, "We have sent e-mailed your password reset link!");
    }

    public function resetPassword(Request $request, $token)
    {
        try {
            $passwordReset = PasswordReset::where('token', $token)->first();
            $user = $this->userRepository->findByEmail($passwordReset->email);
            $user->update($request->only('password'));
            $passwordReset->delete();
            return HttpResponse::resJsonSuccess(null, "Reset password successfully");
        } catch (\Exception $e) {
            return HttpResponse::resJsonFail($e->getMessage());
        }
    }
}
