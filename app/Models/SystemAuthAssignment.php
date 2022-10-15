<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemAuthAssignment extends Model
{
    protected $table        = 'system.auth_assignment';
    protected $primaryKey   = 'id'; 
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'user_id','auth_item_id','created_at','updated_at','created_by','updated_by'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * Connection with User
     */
    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }
    /**
     * Connection with SystemAuthItem
     */
    public function role(){
        return $this->belongsTo(SystemAuthItem::class,'auth_item_id','id');
    }
}
