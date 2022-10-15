<?php

namespace App\Repositories\System;

use App\Models\SystemMainMenu;
use App\Repositories\System\BaseSystemRepository;

class MainMenuRepository extends BaseSystemRepository{
    public function __construct(SystemMainMenu $systemMainMenu){
        parent::__construct($systemMainMenu);
    }
}