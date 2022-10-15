<?php

namespace App\Repositories\System;

use App\Models\SystemAuthAssignment;
use App\Repositories\System\BaseSystemRepository;
class AuthAssignmentRepository extends BaseSystemRepository{
    public function __construct(SystemAuthAssignment $systemAuthAssignment){
        parent::__construct($systemAuthAssignment);
    }

    public function getAll(){
        return $this->model->select(
            'system.auth_assignment.*',
            'user.name as username',
            'role.name as rolename'
        )
            ->join('public.users as user','user.id','system.auth_assignment.user_id')
            ->join('system.auth_item as role','role.id','system.auth_assignment.auth_item_id')
            ->get();
    }

    public function getOne($id){
        return $this->model->select(
            'system.auth_assignment.*',
            'user.name as username',
            'role.name as rolename'
        )
            ->join('public.users as user','user.id','system.auth_assignment.user_id')
            ->join('system.auth_item as role','role.id','system.auth_assignment.auth_item_id')
            ->where('system.auth_assignment.id', $id)
            ->get();
    }
}