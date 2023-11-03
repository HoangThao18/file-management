<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\Controller;
use App\Http\Libraries\HttpResponse;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Modules\User\Folder\UserFolderModule;
use App\Modules\User\UserAdmin;
use App\Modules\User\UserNormal;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class UserController extends Controller
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($user)
    {
        $user = auth()->user();
        $user = $this->userRepository->find($user->id);
        $userAdmin = new UserAdmin();
        $result = $userAdmin->setUser($user)->deleteUser($user);
        return $result;
    }

    public function getUser()
    {
        return HttpResponse::resJsonSuccess(new UserResource(Auth::user()));
    }

    public function getProfile()
    {
        $user = auth()->user();
        $user = $this->userRepository->find($user->id);
        $userNormal = new UserNormal();
        $userProfile =  $userNormal->setUser($user)->getProfile();
        return HttpResponse::resJsonSuccess($userProfile);
    }

    public function changePassword(Request $request)
    {
        $user = auth()->user();
        $userNormal = $this->userRepository->find($user->id);
        $userNormal = new UserNormal();
        $result = $userNormal->setUser($user)->changePassword($request);
        return $result;
    }

    public function getRootFoldersAndFiles(User $user)
    {
        $user = auth()->user();
        $user = $this->userRepository->find($user->id);
        $UserFolderModule = new UserFolderModule();
        $result = $UserFolderModule->setUser($user)->getRootFoldersAndFiles();
        return $result;
    }

    public function search(Request $request)
    {
        $user = auth()->user();
        $user = $this->userRepository->find($user->id);
        $userNormal = new UserNormal();
        $result =  $userNormal->setUser($user)->search($request);
        return $result;
    }
}
