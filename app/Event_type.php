<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event_type extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [];

  /**
   * The attributes that should be hidden for arrays.
   *
   * @var array
   */
  protected $hidden = ['create_at', 'update_at'];
  public function getSonEventTypeList()
  {
    return $this->hasMany('App\Event_type','parent_event_type_id','id')->get();
  }
}
