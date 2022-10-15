<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemRoleMainMenu extends Model
{
    protected $table        = 'system.role_main_menu';
    protected $primaryKey   = 'id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'auth_item_id','main_menu_id','created_at','updated_at','created_by','updated_by'
     ];
 
     /**
      * The attributes that should be hidden for arrays.
      *
      * @var array
      */
     protected $hidden = [];

    /**
     * Connection with SystemAuthItem
     */
    public function authItem(){
        return $this->belongsTo(SystemAuthItem::class,'auth_item_id','id');
    }
    
    /**
     * Connection with SystemMainMenu
     */
    public function mainMenu(){
        return $this->belongsTo(SystemMainMenu::class,'main_menu_id','id');
    }

    /**
     * Get roles by list of main menu id
     * 
     * @param array $mainMenuIds
     */
    public static function getRolesByMainMenus($mainMenus){
        return self::whereIn('main_menu_id', $mainMenus)->get();
    }
}
