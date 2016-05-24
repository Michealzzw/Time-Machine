<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    public function getEventTypeList()
  	{
  		return $this->hasMany('App\User_event_type','user_id','id')->get();
  	}
    public function getEventNow()
  	{
  		return $this->hasOne('App\User_now_event','user_id','id')->first();
  	}
}
