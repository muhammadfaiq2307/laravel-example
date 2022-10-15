<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SystemAuthItem extends Model
{
    use LogsActivity, HasFactory;

    protected $table        = 'system.auth_item';
    protected $primaryKey   = 'id'; 
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'name','type','description','rule_name','data','created_at','updated_at','created_by','updated_by'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logFillable();
    }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * Define connection with SystemRoleMainMenu
     */
    public function mainMenu(){
        return $this->hasMany(SystemRoleMainMenu::class,'auth_item_id','id');
    }

    /**
     * Define connection with SystemAuthAssignment
     */
    public function userRole(){
        return $this->hasMany(SystemAuthAssignment::class,'auth_item_id','id');
    }
}
