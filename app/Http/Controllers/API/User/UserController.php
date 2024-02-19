<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\Controller;
use App\Http\Libraries\HttpResponse;
use App\Http\Requests\changePasswordRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Modules\User\UserModuleInterface;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{
    protected $userRepository;
    protected $userModule;

    public function __construct(UserRepository $userRepository, UserModuleInterface $userModule)
    {
        $this->userRepository = $userRepository;
        $this->userModule = $userModule;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = $this->userRepository->paginate(10);
        return HttpResponse::resJsonSuccess(UserResource::collection($users));
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return HttpResponse::resJsonSuccess(new UserResource($user));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request)
    {
        $user = auth()->user();
        $user = $this->userRepository->update($request->validated(), $user->id);
        return HttpResponse::resJsonSuccess(new UserResource($user));
    }


    public function getUser()
    {
        return HttpResponse::resJsonSuccess(new UserResource(Auth::user()));
    }

    public function getProfile()
    {
        $this->userModule->setUser(Auth()->user());
        return $this->userModule->getProfile();
    }

    public function changePassword(changePasswordRequest $request)
    {
        $this->userModule->setUser(Auth()->user());
        return $this->userModule->changePassword($request->new_password);
    }
}
