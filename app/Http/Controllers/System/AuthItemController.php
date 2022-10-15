<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\System\AuthItemService;
use App\Http\Requests\StoreAuthItemRequest;

class AuthItemController extends Controller
{
    protected $service;
    function __construct(AuthItemService $authItemService){
        $this->service = $authItemService;
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

    function store(StoreAuthItemRequest $request){
        $result = [
            'data'  => $this->service->store($request->validated())
        ];
        return response()->json($result,200);
    }

    function update(Request $request, $id){
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
