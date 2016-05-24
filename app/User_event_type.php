<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User_event_type extends Model
{
   protected $fillable = array("user_id","event_type_id","event_type_name");
    //
}
