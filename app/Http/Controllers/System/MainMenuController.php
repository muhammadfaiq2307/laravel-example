<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\System\MainMenuService;
use App\Http\Requests\StoreMainMenuRequest;
use App\Exceptions\CustomInvalidException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class MainMenuController extends Controller
{
    protected $service;
    function __construct(MainMenuService $mainMenuService){
        $this->service = $mainMenuService;
    }
    
    function index(){
        $result = [
            'data'  => 'You are on Main Menu page'
        ];
        return response()->json($result,200);    
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

    function store(StoreMainMenuRequest $request){
        $result = [
            'data'  => $this->service->store($request->validated())
        ];
        return response()->json($result,200);
    }

    function update(Request $request, $id){
        try {
            $result = [
                'data'  => $this->service->update($request, $id)
            ];
            return response()->json($result,200);   
        } catch (ModelNotFoundException $exception) {
            throw new CustomInvalidException('ID Not Found');
        }
    }

    function destroy($id){
        $result = [
            'data'  => $this->service->destroy($id)
        ];
        return response()->json($result,200);
    }
}
