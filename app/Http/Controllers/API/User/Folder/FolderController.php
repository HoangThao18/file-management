<?php

namespace App\Http\Controllers\API\User\Folder;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFolderRequest;
use App\Models\Folder;
use App\Repositories\FolderRepository;
use Illuminate\Http\Request;
use App\Http\Libraries\HttpResponse;
use App\Http\Requests\ActionFileRequest;
use App\Http\Requests\DeleteFolderRequest;
use App\Http\Requests\DownloadRequest;
use App\Http\Requests\RestoreFolderRequest;
use App\Http\Requests\UploadFolderRequest;
use App\Http\Resources\FileResource;
use App\Http\Resources\FolderResource;
use App\Models\File;
use App\Models\User;
use App\Modules\User\Folder\FolderModuleInterface;
use App\Modules\User\Folder\FolerModuleAbstract;
use App\Modules\User\Folder\UserFolderModule;
use App\Modules\User\UserNormal;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FolderController extends Controller
{
    //
    protected $folderRepository;
    protected $folderModule;

    public function __construct(FolderRepository $folderRepository, FolderModuleInterface $folerModule)
    {
        $this->folderRepository = $folderRepository;
        $this->folderModule = $folerModule;
    }

    public function index(Request $request)
    {
        $folders = $this->folderRepository->paginate(10);
        return HttpResponse::resJsonSuccess(FolderResource::collection($folders));
    }

    // public function show($folder)
    // {
    //     $folder = Folder::find($folder);
    //     return HttpResponse::resJsonSuccess(new FolderResource($folder));
    // }

    // public function store(StoreFolderRequest $request)
    // {
    //     $folder = $this->folderRepository->create($request->validated());
    //     return HttpResponse::resJsonCreated(new FolderResource($folder));
    // }

    public function update(Request $request, Folder $folder)
    {
        if (!$request->user()->can('update', $folder)) {
            return HttpResponse::resJsonFail("unauthorized", 403);
        }
        $folder = $this->folderRepository->update($request->all(), $folder->id);
        return HttpResponse::resJsonSuccess("updated successfully");
    }

    public function destroy(Folder $folder)
    {
        $this->folderRepository->delete($folder->id);
        return HttpResponse::resJsonSuccess(null, "removed successfully");
    }

    public function createFolder(StoreFolderRequest $request)
    {
        return $this->folderModule->createFolder($request->name, $request->parent_id ?? null);
    }

    public function getFilesOfFolder($folder)
    {
        return $this->folderModule->getFilesOfFolder($folder);
    }

    public function deleteFolder(DeleteFolderRequest $request)
    {
        return $this->folderModule->deleteFolder($request->FolderIds);
    }

    public function upLoadFolder(UploadFolderRequest $request)
    {
        return $this->folderModule->uploadFolder($request->files_tree, $request->validated()['parent_id'] ?? null);
    }

    public function downLoad(DownloadRequest $request)
    {

        return $this->folderModule->download($request->fileIds, $request->folderIds);
    }

    public function getRootFoldersAndFiles()
    {
        return $this->folderModule->getRootFoldersAndFiles();
    }

    public function share(Request $request)
    {
        return $this->folderModule->share($request->token);
    }

    public function shareByMe(string $token)
    {
        $tokens = explode(",", $token);
        $files = File::where('created_by', Auth()->user()->name)->whereIn('token_share', $tokens)->get();
        $folders = Folder::where('created_by', Auth()->user()->name)->whereIn('token_share', $tokens)->get();

        if (empty($files) && empty($folders)) {
            return HttpResponse::resJsonFail("not found", 404);
        }

        return HttpResponse::resJsonSuccess([
            'files' => FileResource::collection($files),
            'folders' => FolderResource::collection($folders)
        ]);
    }

    public function restore(RestoreFolderRequest $request)
    {
        return $this->folderModule->restore($request->folderIds);
    }

    public function getFolderStarred()
    {
        return $this->folderModule->getFolderStarred();
    }
}
