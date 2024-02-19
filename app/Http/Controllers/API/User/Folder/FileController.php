<?php

namespace App\Http\Controllers\API\User\Folder;

use App\Http\Controllers\Controller;
use App\Http\Libraries\HttpResponse;
use App\Http\Requests\DeleteFileRequest;
use App\Http\Requests\RestoreFileRequest;
use App\Http\Requests\StoreFileRequest;
use App\Http\Requests\UploadFileRequest;
use App\Http\Resources\FileResource;
use App\Models\File;
use App\Models\User;
use App\Modules\User\File\FileModuleInterface;
use App\Modules\User\UserNormal;
use App\Repositories\FileRepository;
use Illuminate\Http\Request;

class FileController extends Controller
{
    protected $fileRepository;
    protected $fileModule;

    public function __construct(FileRepository $fileRepository, FileModuleInterface $fileModule)
    {
        $this->fileRepository = $fileRepository;
        $this->fileModule = $fileModule;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $files = $this->fileRepository->get();
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

        return $this->fileModule->uploadFile($request->validated()['files'], $request->parent_folder ?? null);
    }

    public function deleteFile(DeleteFileRequest $request)
    {
        return $this->fileModule->deleteFile($request->validated()['fileIds'] ?? null);
    }

    public function restore(RestoreFileRequest $request)
    {
        return $this->fileModule->restore($request->fileIds);
    }

    public function getFileStarred()
    {
        return $this->fileModule->getFileStarred();
    }
}
