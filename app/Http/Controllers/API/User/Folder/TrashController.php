<?php

namespace App\Http\Controllers\API\User\Folder;

use App\Http\Controllers\Controller;
use App\Http\Libraries\HttpResponse;
use App\Http\Requests\StoreTrashRequest;
use App\Http\Resources\TrashResource;
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
        $trashes = $this->trashRepository->paginate(10);
        return HttpResponse::resJsonSuccess(TrashResource::collection($trashes));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $trash = $this->trashRepository->create($request->all());
        return HttpResponse::resJsonCreated(new TrashResource($trash));
    }

    /**
     * Display the specified resource.
     */
    public function show(Trash $trash)
    {
        return HttpResponse::resJsonSuccess(new TrashResource($trash));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Trash $trash)
    {
        $trash = $this->trashRepository->update($request->all(), $trash->id);
        return HttpResponse::resJsonSuccess(new TrashResource($trash));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Trash $trash)
    {
        $trash->delete();
        return HttpResponse::resJsonSuccess(null, "removed successfully");
    }
}
