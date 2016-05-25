<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;

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
        'password', 'remember_token','created_at','updated_at'
    ];
    public function getEventTypeList()
  	{
  		return $this->hasMany('App\User_event_type','user_id','id')->get();
  	}
    public function getEventList()
  	{
  		//return $this->hasMany('App\User_event','user_id','id')->get();
      return DB::table('user_'.$this->id."_view")->select('*')->get();
  	}
    public function getFriendList()
  	{
  		return $this->hasMany('App\User_friend','user_id','id')->get();
      //return DB::table('user_'.$this->id."_view")->select('*')->get();
  	}
    public function getFriendRequestList()
  	{
  		return $this->hasMany('App\User_friend_request','user_id','id')->get();
      //return DB::table('user_'.$this->id."_view")->select('*')->get();
  	}
    public function getEventNow()
  	{
  		return $this->hasOne('App\User_now_event','user_id','id')->first();
  	}
    public function getEventMessage($event_id)
    {
      return DB::table('user_events')->select('*')->whereUser_id($this->id)->whereEvent_id($event_id)->first();
    }
}
