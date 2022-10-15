<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\System\AuthAssignmentService;
use App\Http\Requests\StoreAuthAssignmentRequest;

class AuthAssignmentController extends Controller
{
    protected $service;
    function __construct(AuthAssignmentService $authAssignmentService){
        $this->service = $authAssignmentService;
    }
    
    function getAll(){
        $result = [
            'data'  => $this->service->getAll()
        ];
        return response()->json($result,200);   
    }

    function getOne($id){
        $result = [
            'data'  => $this->service->getOne($id)
        ];
        return response()->json($result,200);
    }

    function store(StoreAuthAssignmentRequest $request){
        $result = [
            'data'  => $this->service->store($request->validated())
        ];
        return response()->json($result,200);
    }

    function update(StoreAuthAssignmentRequest $request, $id){
        $result = [
            'data'  => $this->service->update($request, $id)
        ];
        return response()->json($result,200);
    }

    function destroy($id){
        $result = [
            'data'  => $this->service->destroy($id)
        ];
        return response()->json($result,200);
    }
}
