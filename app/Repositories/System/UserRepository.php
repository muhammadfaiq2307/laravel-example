<?php

namespace App\Repositories\System;

use App\Models\User;
use App\Repositories\System\BaseSystemRepository;
class UserRepository extends BaseSystemRepository{
    public function __construct(User $user){
        parent::__construct($user);
    }
}