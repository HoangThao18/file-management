<?php

namespace App\Http\Controllers\API\User\Auth;

use App\Http\Controllers\Controller;
use App\Http\Libraries\HttpResponse;
use Illuminate\Http\Request;

class LogoutController extends Controller
{
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return HttpResponse::resJsonSuccess(null, 'Logout successfully');
    }
}
