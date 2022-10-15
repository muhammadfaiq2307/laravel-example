<?php

namespace App\Repositories\System;

use App\Models\SystemAuthItem;
use App\Repositories\System\BaseSystemRepository;
class AuthItemRepository extends BaseSystemRepository{
    public function __construct(SystemAuthItem $systemAuthItem){
        parent::__construct($systemAuthItem);
    }
}