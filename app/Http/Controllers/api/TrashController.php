<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTrashRequest;
use App\Models\Trash;
use App\Repositories\TrashRepository;
use Illuminate\Http\Request;

class TrashController extends Controller
{
    protected $trashRepository;
    public function __construct(TrashRepository $trashRepository)
    {
        $this->trashRepository = $trashRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->trashRepository->all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->trashRepository->create($request->all());
        return response()->json(['message' => "Trash created successfully "], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Trash $trash)
    {
        //
        return response()->json($trash);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Trash $trash)
    {
        $this->trashRepository->update($request->all(), $trash->id);
        return response()->json(['message' => "Trash updated successfully "], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Trash $trash)
    {
        $trash->delete();
        return response()->json(['message' => "Trash remove successfully "], 200);
    }
}
