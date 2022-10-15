<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemMainMenu extends Model
{
    protected $table        = 'system.main_menu';
    protected $primaryKey   = 'id'; 
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'link','color','icon','description','created_at','updated_at','created_by','updated_by'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    /**
    * Define connection with SystemRoleMainMenu
    */
    public function roleMainMenu(){
        return $this->hasMany(SystemRoleMainMenu::class,'main_menu_id','id');
    }
}
