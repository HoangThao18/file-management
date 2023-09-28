<?php

namespace App\Http\Controllers\API\User\Auth;


use App\Http\Controllers\Controller;
use App\Http\Libraries\HttpResponse;
use App\Http\Requests\StoreUserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Repositories\UserRepository;
use Carbon\Carbon;

class RegisterController extends Controller
{

    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function register(StoreUserRequest $request)
    {

        $user =  $this->userRepository->create($request->validated());
        return HttpResponse::resJsonSuccess($user, "created successfully");
    }
}
