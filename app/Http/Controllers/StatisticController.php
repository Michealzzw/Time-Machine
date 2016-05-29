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

class StatisticController extends Controller
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





      public function time_formal_check($str)
      {
        if (!preg_match("/^[0-9]{4}-([0]?[1-9])|([1][0-2])-([0-2]?[0-9])|(3[0-1]) ([0-1]?[0-9])|(2[0-4]):([0-5]?[0-9])$/",$str))
        return false;
        else return true;
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

public function showStatistic()
{
  date_default_timezone_set('PRC');
  $tmp = Auth::user()->getEventTypeList();
  $userEventTypeList = array();
  $str = "user_".Auth::id()."_view";
  //return ;
  $recentcount = DB::select("select user_event_types.event_type_id, user_event_types.event_type_name, sum(timestampdiff(minute,uv.start_time,uv.end_time)) as totaltime
  from user_event_types,".$str." as uv,events
  where user_event_types.user_id = ".Auth::id()." and uv.end_time>0 and uv.start_time between \"".date("Y-m-d H:i:s",strtotime("-7 day"))."\" and \"".date("Y-m-d H:i:s",time())."\" and uv.event_id=events.id and FIND_IN_SET(events.event_type_id,findAllChildEventType(user_event_types.event_type_id))
  group by user_event_types.event_type_id");
  $groupcountbyday = DB::select("select DATE_FORMAT( uv.start_time, \"%Y-%m-%d\" ) as day ,user_event_types.event_type_id, user_event_types.event_type_name, sum(timestampdiff(minute,uv.start_time,uv.end_time)) as totaltime
  from user_event_types,".$str." as uv,events
  where user_event_types.user_id = ".Auth::id()." and uv.end_time>0 and uv.event_id=events.id and FIND_IN_SET(events.event_type_id,findAllChildEventType(user_event_types.event_type_id))
  group by user_event_types.event_type_id , day with rollup");
  $allcount = $groupcountbyday;
  //return array($allcount,$recentcount,$groupcountbyday);


  //return $eventTypeList;
  return view('showStatistic',['all'=>$allcount,'recent'=>$recentcount,'day'=>$groupcountbyday]);
}
}
