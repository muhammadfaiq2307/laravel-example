<?php

namespace App\Services\System;
use App\Repositories\System\AuthAssignmentRepository;
use Illuminate\Support\Facades\Auth;

class AuthAssignmentService {
    private $repository;
    function __construct(AuthAssignmentRepository $authAssignmentRepository){
        $this->repository = $authAssignmentRepository;
    }

    function getAll(){
        return $this->repository->getAll();
    }

    function getOne($id){
        return $this->repository->getOne($id);
    }

    function store($data){
        $data['created_by'] = Auth::user()->name;
        return $this->repository->store($data);
    }

    function update($request, $id){
        $data   = $request->all();
        $data['updated_by'] = Auth::user()->name;
        return $this->repository->update($data, $id);
    }

    function destroy($id){
        return $this->repository->destroy($id);
    }
}