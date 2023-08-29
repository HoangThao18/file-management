<?php

namespace App\Http\Controllers\api;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Repositories\UserRepository;

class RegisterController extends Controller
{
    //
    protected $userRepository;
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    public function register(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => "required|email|unique:User",
            'name' => "required",
            'password' => "required|min:8",

        ]);
    }
}
