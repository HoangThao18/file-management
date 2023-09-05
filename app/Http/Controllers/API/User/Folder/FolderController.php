<?php

namespace App\Http\Controllers\API\User\Folder;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFolderRequest;
use App\Models\Folder;
use App\Repositories\FolderRepository;
use Illuminate\Http\Request;
use App\Http\Libraries\HttpResponse;
use App\Http\Resources\FolderResource;

class FolderController extends Controller
{
    //
    protected $folderRepository;

    public function __construct(FolderRepository $folderRepository)
    {
        $this->folderRepository = $folderRepository;
    }

    public function index()
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
        $folder = $this->folderRepository->update($request->all(), $folder->id);
        return HttpResponse::resJsonSuccess(new FolderResource($folder));
    }

    public function destroy(Folder $folder)
    {
        $this->folderRepository->update(['status' => true], $folder->id);
        return HttpResponse::resJsonSuccess(null, "removed successfully");
    }
}
