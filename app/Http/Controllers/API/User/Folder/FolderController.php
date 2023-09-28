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
use App\Http\Requests\UploadFolderRequest;
use App\Http\Resources\FileResource;
use App\Http\Resources\FolderResource;
use App\Models\File;
use App\Models\User;
use App\Modules\User\Folder\UserFolderModule;
use App\Modules\User\UserNormal;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class FolderController extends Controller
{
    //
    protected $folderRepository;

    public function __construct(FolderRepository $folderRepository)
    {
        $this->folderRepository = $folderRepository;
    }

    public function index(Request $request)
    {
        $folders = $this->folderRepository->paginate(10);
        return HttpResponse::resJsonSuccess(FolderResource::collection($folders));
    }

    public function show(Folder $folder)
    {
        return HttpResponse::resJsonSuccess(new FolderResource($folder));
    }

    public function store(StoreFolderRequest $request)
    {
        $folder = $this->folderRepository->create($request->validated());
        return HttpResponse::resJsonCreated(new FolderResource($folder));
    }

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
        $user = Auth()->user();
        $user = User::find($user->id);
        $userNormal = new UserNormal();
        $result =  $userNormal->setUser($user)->createFolder($request);
        return $result;
    }

    public function getFilesOfFolder(Folder $folder)
    {
        $user = Auth()->user();
        $user = User::find($user->id);
        $userFolderModule = new UserFolderModule();
        $userFolderModule->setUser($user);
        $result = $userFolderModule->setFolder($folder)->getFilesOfFolder($folder);
        return $result;
    }

    public function deleteFolder(DeleteFolderRequest $request)
    {
        $user = auth()->user();
        $user = User::find($user->id);
        $userNormal = new UserNormal();
        $result =  $userNormal->setUser($user)->deleteFolder($request);
        return $result;
    }

    public function upLoadFolder(UploadFolderRequest $request)
    {
        $user = auth()->user();
        $user = User::find($user->id);
        $userFolderModule = new userFolderModule();
        $userFolderModule->setUser($user);
        $result = $userFolderModule->uploadFolder($request);
        return $result;
    }

    public function downLoad(DownloadRequest $request)
    {
        $user = auth()->user();
        $user = User::find($user->id);
        $userNormal = new UserNormal();
        $result =  $userNormal->setUser($user)->download($request);
        return $result;
    }

    public function share(ActionFileRequest $request)
    {
        $data = $request->validated();
        $tokens = [];
        foreach ($data['fileIds'] as $fileId) {
            $token = Str::random(8);
            $file = File::find($fileId);

            if (is_null($file->token_share)) {
                $file->token_share = $token;
                $file->save();
                $tokens[] = $token;
            } else {
                $tokens[] = $file->token_share;
            }
        }

        foreach ($data['folderIds'] as $folderId) {
            $token = Str::random(8);
            $folder = Folder::find($folderId);
            if (is_null($folder->token_share)) {
                $folder->token_share = $token;
                $folder->save();
                $tokens[] = $token;
            } else {
                $tokens[] = $folder->token_share;
            }
        }
        $link_share = "http://hoangthao.com/api/user/folder/share/";
        $link_share .= implode(",", $tokens);

        return HttpResponse::resJsonSuccess(['link' => $link_share]);
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
}
