<?php

namespace App\Http\Controllers\API\User\Folder;

use App\Http\Controllers\Controller;
use App\Http\Libraries\HttpResponse;
use App\Http\Requests\ActionFileRequest;
use App\Http\Requests\DeleteFilePermanentlyRequest;
use App\Http\Requests\StoreTrashRequest;
use App\Http\Resources\FileResource;
use App\Http\Resources\FolderResource;
use App\Models\File;
use App\Models\Folder;
use App\Models\User;
use App\Modules\User\UserNormal;
use App\Repositories\TrashRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use PHPUnit\TextUI\Configuration\FileCollection;

class TrashController extends Controller
{


    public function __construct()
    {
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $files = File::onlyTrashed()
            ->where('user_id', Auth::id())
            ->orderBy('deleted_at', 'desc')->get();

        $folders = Folder::onlyTrashed()
            ->where('user_id', Auth::id())
            ->orderBy('deleted_at', 'desc')->get();

        $files = FileResource::collection($files);
        $folders = FolderResource::collection($folders);

        return HttpResponse::resJsonSuccess(compact('files', 'folders'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DeleteFilePermanentlyRequest $request)
    {

        $fileIds = $request->fileIds ?? [];
        $folderIds = $request->folderIds ?? [];

        $files = File::onlyTrashed()->whereIn('id', $fileIds)->get();
        $folders = Folder::onlyTrashed()->with('files')->whereIn('id', $folderIds)->get();

        foreach ($files as $file) {
            Storage::delete($file->path);
            $file->forceDelete();
        }

        foreach ($folders as $folder) {
            $this->deleteFolderFromStorage($folder);
            $folder->forceDelete();
        }

        return HttpResponse::resJsonSuccess("delete successfully");
    }

    public function deleteFolderFromStorage($folder)
    {
        foreach ($folder->files as $file) {
            Storage::delete($file->path);
        }
        foreach ($folder->subfolders as $subFolder) {

            $this->deleteFolderFromStorage($subFolder);
            $subFolder->forceDelete();
        }
    }
}
