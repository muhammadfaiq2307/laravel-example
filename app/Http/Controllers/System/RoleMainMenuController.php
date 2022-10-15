<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRoleMainMenuRequest;
use Illuminate\Http\Request;
use App\Services\System\RoleMainMenuService;

class RoleMainMenuController extends Controller
{
    protected $service;
    function __construct(RoleMainMenuService $roleMainMenuService){
        $this->service = $roleMainMenuService;
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

    function store(StoreRoleMainMenuRequest $request){
        $result = [
            'data'  => $this->service->store($request->validated())
        ];
        return response()->json($result,200);
    }

    function update(StoreRoleMainMenuRequest $request, $id){
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
