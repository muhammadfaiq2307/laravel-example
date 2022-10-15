<?php

namespace App\Repositories\System;

use App\Models\SystemRoleMainMenu;
use App\Repositories\System\BaseSystemRepository;

class RoleMainMenuRepository extends BaseSystemRepository{
    public function __construct(SystemRoleMainMenu $systemRoleMainMenu){
        parent::__construct($systemRoleMainMenu);
    }

    public function getAll(){
        return $this->model->select(
            'system.role_main_menu.*',
            'role.name as rolename',
            'main_menu.link as menulink'

        )
            ->join('system.auth_item as role','role.id','system.role_main_menu.auth_item_id')
            ->join('system.main_menu as main_menu','main_menu.id','system.role_main_menu.main_menu_id')
            ->get();
    }

    public function getOne($id){
        return $this->model->select(
            'system.role_main_menu.*',
            'role.name as rolename',
            'main_menu.link as menulink'

        )
            ->join('system.auth_item as role','role.id','system.role_main_menu.auth_item_id')
            ->join('system.main_menu as main_menu','main_menu.id','system.role_main_menu.main_menu_id')
            ->where('system.role_main_menu.id',$id)
            ->get();
    }
}