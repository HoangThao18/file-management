<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSupportRequest;
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
        return $this->supportRepository->all();
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSupportRequest $request)
    {
        $this->supportRepository->create($request->validated());
        return response()->json(['message' => 'Support created successfully'], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Support $support)
    {
        return $support;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Support $support)
    {
        $this->supportRepository->update($request->all(), $support->id);
        return response()->json(['message' => "Support updated successfully "], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Support $support)
    {
        $support->delete();
        return response()->json(['message' => "support remove successfully "], 200);
    }
}
