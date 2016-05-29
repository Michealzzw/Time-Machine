<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\User;
use App\Event;
use App\Event_type;
use App\User_event_type;
use App\User_now_event;
use App\User_event;
use App\User_friend;
use App\User_friend_request;
use App\Event_invitation;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($message=null)
    {

      date_default_timezone_set('PRC');
      //return date("Y-m-d H:i:s",time());
        $event_now = Auth::user()->getEventNow();
        $event_invitations = DB::select('select events.id as event_id, users.name as user_name, events.name as event_name from users,events,event_invitations as invite
        where users.id=events.host_user_id and events.id = invite.event_id and invite.user_id='.Auth::id());
        if ($event_now!=null)
        {
          $tmp = User_now_event::select('*')->whereEvent_id($event_now->event_id)->get();
          $participants = array();
          for ($i = 0;$i<count($tmp);$i++)
            if ($tmp[$i]->user_id!=Auth::id()) $participants[] = User::find($tmp[$i]->user_id)->name;
          return view('home',['participants'=>$participants,'event_now'=>$event_now,'invitation'=>$event_invitations,'message'=>$message]);
        }
        else
        return view('home',['invitation'=>$event_invitations,'message'=>$message]);
    }




        public function time_formal_check($str)
        {
          if (!preg_match("/^[0-9]{4}-([0]?[1-9])|([1][0-2])-([0-2]?[0-9])|(3[0-1]) ([0-1]?[0-9])|(2[0-4]):([0-5]?[0-9])$/",$str))
          return false;
          else return true;
        }
      
}
