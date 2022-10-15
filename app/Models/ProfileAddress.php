<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfileAddress extends Model
{
    protected $table        = 'profile.address';
    protected $primaryKey   = 'id'; 
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'user_id','urban','district','city','province','postal_code','address_detail','note','created_at','updated_at','created_by','updated_by'
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
}
