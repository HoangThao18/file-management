<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFileRequest;
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
        return response()->json($this->fileRepository->all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFileRequest $request)
    {
        $this->fileRepository->create($request->validated());
        return response()->json(['message' => 'File created successfully'], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(File $file)
    {
        return response()->json($file);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, File $file)
    {
        $this->fileRepository->update($request->all(), $file->id);
        return response()->json(['message' => "File updated successfully "], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(File $file)
    {
        $this->fileRepository->update(['status' => true], $file->id);
        return response()->json(['message' => "Folder remove successfully "], 200);
    }
}
