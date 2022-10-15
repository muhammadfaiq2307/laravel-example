<?php

namespace App\Services\System;
use App\Repositories\System\UserRepository;
use Illuminate\Support\Facades\Hash;

class UserService {
    private $repository;
    function __construct(UserRepository $userRepository){
        $this->repository = $userRepository;
    }
    function hello(){
        return 'this is the service for user';
    }

    function getAll(){
        return $this->repository->getAll();
    }

    function getOne($id){
        return $this->repository->getOne($id);
    }

    function store($data){
        $data['password'] = Hash::make($data['password']);
        return $this->repository->store($data);
    }

    function update($request, $id){
        $data   = $request->all();
        return $this->repository->update($data, $id);
    }

    function destroy($id){
        return $this->repository->destroy($id);
    }
}