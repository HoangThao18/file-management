<?php

namespace App\Http\Controllers\API\User\Folder;

use App\Http\Controllers\Controller;
use App\Http\Libraries\HttpResponse;
use App\Http\Requests\DeleteFileRequest;
use App\Http\Requests\StoreFileRequest;
use App\Http\Requests\UploadFileRequest;
use App\Http\Resources\FileResource;
use App\Models\File;
use App\Models\User;
use App\Modules\User\UserNormal;
use App\Repositories\FileRepository;
use Illuminate\Http\Request;

class FileController extends Controller
{
    protected $fileRepository;

    public function __construct(FileRepository $fileRepository)
    {
        $this->fileRepository = $fileRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $files = $this->fileRepository->paginate(10);
        return HttpResponse::resJsonSuccess(FileResource::collection($files));
    }

    /**
     * Display the specified resource.
     */
    public function show(File $file)
    {
        return HttpResponse::resJsonSuccess(new FileResource($file));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, File $file)
    {
        if (!$request->user()->can('update', $file)) {
            return HttpResponse::resJsonFail("unauthorized", 403);
        }
        $this->fileRepository->update($request->all(), $file->id);
        return HttpResponse::resJsonSuccess("updated successfully");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(File $file)
    {
        $this->fileRepository->delete($file->id);
        return HttpResponse::resJsonSuccess(null, "deleted successfully");
    }

    public function upload(UploadFileRequest $request)
    {
        $user = auth()->user();
        $user = User::find($user->id);
        $userNormal = new UserNormal();
        $result =  $userNormal->setUser($user)->uploadFile($request);
        return $result;
    }

    public function deleteFile(DeleteFileRequest $request)
    {
        $user = auth()->user();
        $user = User::find($user->id);
        $userNormal = new UserNormal();
        $result =  $userNormal->setUser($user)->deleteFile($request);
        return $result;
    }
}
