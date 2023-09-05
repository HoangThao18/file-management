<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\Controller;
use App\Http\Libraries\HttpResponse;
use App\Http\Requests\StoreSupportRequest;
use App\Http\Resources\SupportResource;
use App\Models\Support;
use App\Repositories\SupportRepository;
use Illuminate\Http\Request;

class SupportController extends Controller
{
    protected $supportRepository;

    public function __construct(SupportRepository $supportRepository)
    {
        $this->supportRepository = $supportRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $supports = $this->supportRepository->paginate(10);
        return HttpResponse::resJsonSuccess(SupportResource::collection($supports));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSupportRequest $request)
    {
        $support = $this->supportRepository->create($request->validated());
        return HttpResponse::resJsonCreated(new SupportResource($support));
    }

    /**
     * Display the specified resource.
     */
    public function show(Support $support)
    {
        return HttpResponse::resJsonSuccess(new SupportResource($support));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Support $support)
    {
        $support = $this->supportRepository->update($request->all(), $support->id);
        return HttpResponse::resJsonSuccess(new SupportResource($support));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Support $support)
    {
        $support->delete();
        return HttpResponse::resJsonSuccess(null, 'removed successfully');
    }
}
