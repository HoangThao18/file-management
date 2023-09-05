<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\Controller;
use App\Http\Libraries\HttpResponse;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
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
    public function show()
    {
        return HttpResponse::resJsonSuccess(new UserResource(auth()->user()));
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
    public function destroy(User $user)
    {
        $user->delete();
        return HttpResponse::resJsonSuccess(null, "removed successfully");
    }
}
