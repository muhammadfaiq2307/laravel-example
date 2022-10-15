<?php

namespace App\Repositories\System;

use Illuminate\Database\Eloquent\Model;

class BaseSystemRepository{
    protected $model;
    
    function __construct(Model $model){
        $this->model = $model;
    }

    function getAll(){
        return $this->model::all();
    }

    function getOne($id){
        return $this->model::find($id);
    }
    
    function store($data){
        $created = new $this->model;
        $created = $created->create($data);
        
        return $created->fresh();
    }

    public function update($data,$id){
        return $this->model->findOrFail($id)->update($data);
    }

    public function destroy($id){
        return $this->model->findOrFail($id)->delete();
    }

}