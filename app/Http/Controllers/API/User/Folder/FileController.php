<?php

namespace App\Http\Controllers\API\User\Folder;

use App\Http\Controllers\Controller;
use App\Http\Libraries\HttpResponse;
use App\Http\Requests\StoreFileRequest;
use App\Http\Resources\FileResource;
use App\Models\File;
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
     * Store a newly created resource in storage.
     */
    public function store(StoreFileRequest $request)
    {
        $file = $this->fileRepository->create($request->validated());
        return HttpResponse::resJsonCreated(new FileResource($file));
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
        $file = $this->fileRepository->update($request->all(), $file->id);
        return HttpResponse::resJsonSuccess(new FileResource($file));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(File $file)
    {
        $file = $this->fileRepository->update(['status' => true], $file->id);
        return HttpResponse::resJsonSuccess($file, "removed successfully");
    }
}
