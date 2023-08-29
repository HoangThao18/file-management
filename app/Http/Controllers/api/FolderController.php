<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFolderRequest;
use App\Http\Requests\UpdateFolderRequest;
use App\Models\Folder;
use App\Repositories\FolderRepository;
use Illuminate\Http\Request;

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
        return $this->folderRepository->all();
    }

    public function show(Folder $folder)
    {
        return response()->json($folder);
    }

    public function store(StoreFolderRequest $request)
    {
        $this->folderRepository->create($request->validated());
        return response()->json(['message' => "Folder created successfully "], 200);
    }

    public function update(Request $request, Folder $folder)
    {
        $this->folderRepository->update($request->all(), $folder->id);
        return response()->json(['message' => "Folder updated successfully "], 200);
    }

    public function destroy(Folder $folder)
    {
        $this->folderRepository->update(['status' => true], $folder->id);
        return response()->json(['message' => "Folder remove successfully "], 200);
    }
}
