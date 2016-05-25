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
    public function selectEventType()
    {
      $eventTypeList = Auth::user()->getEventTypeList();
      return view('selectEventType',['eventTypeList'=>$eventTypeList]);
    }
    public function showEventType()
    {
      $tmp = Auth::user()->getEventTypeList();
      $userEventTypeList = array();
      for ($i = 0;$i<count($tmp);$i++)
        $userEventTypeList[$tmp[$i]->event_type_id] = $tmp[$i];
      $eventTypeList = Event_type::select("*")->whereParent_event_type_id(null)->get();
      //return $eventTypeList;
      return view('showEventType',['eventTypeList'=>$eventTypeList,'userEventTypeList'=>$userEventTypeList]);
    }
    public function showFromParentEventType($parent_event_type_id)
    {
      $tmp = Auth::user()->getEventTypeList();
      $userEventTypeList = array();
      for ($i = 0;$i<count($tmp);$i++)
        $userEventTypeList[$tmp[$i]->event_type_id] = $tmp[$i];
      $eventTypeList = Event_type::find($parent_event_type_id)->getSonEventTypeList();
      return view('showEventType',
          [
            'eventTypeList'=>$eventTypeList,
            'userEventTypeList'=>$userEventTypeList,
            'show_parent_event_id' => $parent_event_type_id
          ]);
    }
    public function addEventType($event_type_id)
    {
        $user_event_type = User_event_type::create([
          "user_id" => Auth::id(),
          "event_type_id" => $event_type_id,
          "event_type_name" => Input::get('event_type_name')
        ]);
        $show_parent_event_id = Input::get('show_parent_event_id');
        if ($show_parent_event_id==-1)
        return $this->showEventType();
        else return $this->showFromParentEventType($show_parent_event_id);
    }
    public function completeEvent()
    {
      $userFriendList = array();
      $tmp = Auth::user()->getFriendList();
      for ($i = 0;$i<count($tmp);$i++)
        $userFriendList[$tmp[$i]->friend_user_id] = $tmp[$i];
      $tmp = DB::select("select user_friends.friend_user_id,user_friends.friend_user_name,user_now_events.event_name,user_friends.confirmed from user_friends,user_now_events where user_friends.confirmed = true and user_friends.friend_user_id=user_now_events.user_id and user_friends.user_id=?",[Auth::id()]);
      for ($i = 0;$i<count($tmp);$i++)
        $userFriendList[$tmp[$i]->friend_user_id] = $tmp[$i];
      $event_now = Auth::user()->getEventNow();
      if ($event_now!=null)
      {
        return view('completeEvent',['event_now'=>$event_now,'event_type_id'=>Input::get('event_type_id'),'event_type_name'=>Input::get('event_type_name')]);
      }
      else
      return view('completeEvent',['userFriendList'=>$userFriendList,'event_type_id'=>Input::get('event_type_id'),'event_type_name'=>Input::get('event_type_name')]);
    }


    public function addInvitedEvent($event_id)
    {
        if (count(User_now_event::whereUser_id(Auth::id())->get())!=0)
          return $this->index("请先结束当前事件");
        $event = Event::find($event_id);
        $tmp = Event_invitation::whereEvent_id($event_id)->whereUser_id(Auth::id())->get();
        if ($tmp==null) return view('home');
        $user_eventPara=[
          'event_name' => $event->name,
          'start_time' => time(),
          'user_id' => Auth::id(),
          'event_id' =>$event_id
        ];
        $user_now_eventPara=[
          'user_id' => Auth::id(),
          'event_id' =>$event_id,
          'event_name' => $event->name
        ];
        DB::transaction(function() use($user_eventPara,$user_now_eventPara)
        {
            if (!User_event::create($user_eventPara)) DB::rollback();
            if (!User_now_event::create($user_now_eventPara)) DB::rollback();
            Event_invitation::whereUser_id($user_eventPara['user_id'])->whereEvent_id($user_eventPara['event_id'])->delete();
        });
      return $this->index("成功～");
      //return $input;
    }


    public function addEvent()
    {
      $input = Input::all();
      //return $input;
      if (!$this->time_formal_check($input['start_time']))
      {
        $input['message'] = "Start time error Time Formal.";
        return view('addEvent',$input);
      }
      if ($input['end_time']!=null&&!$this->time_formal_check($input['end_time']))
      {
        $input['message'] = "End time error Time Formal.";
        return view('completeEvent',$input);
      }
      $date1= date("Y-m-d H:i:s",strtotime($input['start_time']));
      if ($input['end_time']!=null) $date2= date("Y-m-d H:i:s",strtotime($input['end_time']));
      if (($input['end_time']!=null&&time()<$date2)||time()<$date1)
      {
        $input['message'] = "Start or End in the future.";
        return view('completeEvent',$input);
      }
      if ($input['end_time']!=null&&$date1>$date2)
      {
        $input['message'] = "Start after End time.";
        return view('completeEvent',$input);
      }

      if ($input['note']!=null)
        $eventPara=[
          'note' => $input['note'],
          'name' => $input['event_type_name'],
          'event_type_id' => $input['event_type_id'],
          'host_user_id' => Auth::id()
        ];
      else
      $eventPara=[
        'name' => $input['event_type_name'],
        'event_type_id' => $input['event_type_id'],
        'host_user_id' => Auth::id()
      ];
      if ($input['end_time']==null)
      {
        $user_eventPara=[
          'event_name' => $input['event_type_name'],
          'start_time' => $input['start_time'],
          'user_id' => Auth::id()
        ];
        $user_now_eventPara=[
          'user_id' => Auth::id(),
          'event_name' => $input['event_type_name']
        ];
        DB::transaction(function() use($eventPara,$user_eventPara,$user_now_eventPara)
        {
            if (!$event=Event::create($eventPara)) DB::rollback();
            $user_eventPara['event_id'] = $event->id;
            $user_now_eventPara['event_id'] = $event->id;
            if (!User_event::create($user_eventPara)) DB::rollback();
            if (!User_now_event::create($user_now_eventPara)) DB::rollback();
        });
        $event = User_now_event::find(Auth::id());
        $userFriendList = array();
        $tmp = Auth::user()->getFriendList();
        //return $tmp;
        for ($i = 0;$i<count($tmp);$i++)
          if (array_key_exists($tmp[$i]->friend_user_id,$input)&&$tmp[$i]->confirmed)
          {
            Event_invitation::create(
            [
              'user_id' => $tmp[$i]->friend_user_id,
              'event_id' => $event->event_id
            ]
          );
          }
      }
      else
      {
        $user_eventPara=[
          'event_name' => $input['event_type_name'],
          'start_time' => $input['start_time'],
          'end_time' => $input['end_time'],
          'user_id' => Auth::id()
        ];
        DB::transaction(function() use($eventPara,$user_eventPara)
        {
            if (!$event=Event::create($eventPara)) DB::rollback();
            $user_eventPara['event_id'] = $event->id;
            if (!User_event::create($user_eventPara)) DB::rollback();
        });
      }
      return view('addEventSuccess');
      //return $input;
    }
    public function time_formal_check($str)
    {
      if (!preg_match("/^[0-9]{4}-([0]?[1-9])|([1][0-2])-([0-2]?[0-9])|(3[0-1]) ([0-1]?[0-9])|(2[0-4]):([0-5]?[0-9])$/",$str))
      return false;
      else return true;
    }
    public function endEvent()
    {
      $input = Input::all();
      $event_now = Auth::user()->getEventNow();
      if ($event_now!=null)
      {
        $event_now_message = Auth::user()->getEventMessage($event_now->event_id);
        if (!array_key_exists('end_time',$input)) return view('endEvent',['message'=>"Input end time! ( start time :".$event_now_message->start_time.")"]);
        if ($input['end_time']!=null&&!$this->time_formal_check($input['end_time']))
            return view('endEvent',['message'=>"Error Time Formal.!"]);
        $date= date("Y-m-d H:i:s",strtotime($input['end_time']));
        if ($date<$event_now_message->start_time)
        return view('endEvent',['message'=>"End time before start time (".$event_now_message->start_time.")"]);
        if ($date>time())
        return view('endEvent',['message'=>"End in the future ( now time ".time().")"]);
        $user_id = Auth::id();
        DB::transaction(function() use($user_id,$event_now_message,$date)
        {
            DB::update('update user_events set end_time = ? where user_id = ? and event_id = ?', array($date,$user_id,$event_now_message->event_id));
            DB::delete('delete from user_now_events where user_id = ?', array($user_id));
            DB::delete('delete from event_invitations where event_id = ? and ? in (select host_user_id from events where events.id=?)', array($event_now_message->event_id,$user_id,$event_now_message->event_id));
        });
        return $this->index();
      }
      else
      return index();

    }
    public function showEvents()
    {
      $userEventList = Auth::user()->getEventList();
      return view('showEvents',['userEventList'=>$userEventList]);
    }
    public function showFriends()
    {
      $userFriendList = array();
      $tmp = Auth::user()->getFriendList();
      for ($i = 0;$i<count($tmp);$i++)
        $userFriendList[$tmp[$i]->friend_user_id] = $tmp[$i];
      $tmp = DB::select("select user_friends.friend_user_id,user_friends.friend_user_name,user_now_events.event_name,user_friends.confirmed from user_friends,user_now_events where user_friends.confirmed = true and user_friends.friend_user_id=user_now_events.user_id and user_friends.user_id=?",[Auth::id()]);
      for ($i = 0;$i<count($tmp);$i++)
        $userFriendList[$tmp[$i]->friend_user_id] = $tmp[$i];
      $userFriendRequestList = array();
      $tmp = Auth::user()->getFriendRequestList();
      for ($i = 0;$i<count($tmp);$i++)
        $userFriendRequestList[$tmp[$i]->request_user_id] = $tmp[$i];
      return view('showFriends',['userFriendList'=>$userFriendList,'userFriendRequestList'=>$userFriendRequestList]);
    }
    public function findFriend()
    {
      $str = Input::get('friend_name');
      //防注入
      $findFriendResult = User::select('*')->whereName($str)->where('id','!=',Auth::id())->get();
      $userFriendList = array();
      $tmp = Auth::user()->getFriendList();
      for ($i = 0;$i<count($tmp);$i++)
        $userFriendList[$tmp[$i]->friend_user_id] = $tmp[$i];
      $userFriendRequestList = array();
      $tmp = Auth::user()->getFriendRequestList();
      for ($i = 0;$i<count($tmp);$i++)
        $userFriendRequestList[$tmp[$i]->request_user_id] = $tmp[$i];
      return view('showFriends',['findFriendResult'=>$findFriendResult,'userFriendList'=>$userFriendList,'userFriendRequestList'=>$userFriendRequestList]);
    }
    public function addRequest($id)
    {
      User_friend::create([
        "user_id" => Auth::id(),
        "friend_user_id"=>$id,
        "confirmed" => false,
        "friend_user_name" => User::find($id)->name
      ]);
      $userFriendList = array();
      $tmp = Auth::user()->getFriendList();
      for ($i = 0;$i<count($tmp);$i++)
        $userFriendList[$tmp[$i]->friend_user_id] = $tmp[$i];
      $userFriendRequestList = array();
      $tmp = Auth::user()->getFriendRequestList();
      for ($i = 0;$i<count($tmp);$i++)
        $userFriendRequestList[$tmp[$i]->request_user_id] = $tmp[$i];
      return view('showFriends',['message'=>'请求成功','userFriendList'=>$userFriendList,'userFriendRequestList'=>$userFriendRequestList]);
    }
    public function deleteRequest($id)
    {
      User_friend_request::whereUser_id(Auth::id())->whereRequest_user_id($id)->delete();
      $userFriendList = array();
      $tmp = Auth::user()->getFriendList();
      for ($i = 0;$i<count($tmp);$i++)
        $userFriendList[$tmp[$i]->friend_user_id] = $tmp[$i];
      $userFriendRequestList = array();
      $tmp = Auth::user()->getFriendRequestList();
      for ($i = 0;$i<count($tmp);$i++)
        $userFriendRequestList[$tmp[$i]->request_user_id] = $tmp[$i];
      return view('showFriends',['message'=>'删除成功','userFriendList'=>$userFriendList,'userFriendRequestList'=>$userFriendRequestList]);
    }
    public function addFriend($id)
    {
      $user_id = Auth::id();
      $friend_name = User::find($id)->name;
      DB::transaction(function() use($user_id,$id,$friend_name)
      {
        DB::statement("update user_friend_requests set confirmed = true where user_id=".$user_id." and request_user_id = ".$id);
        DB::statement("update user_friends set confirmed = true where user_id=".$id." and friend_user_id = ".$user_id);
        User_friend::create([
          'user_id'=>$user_id,
          'friend_user_id'=>$id,
          'friend_user_name'=>$friend_name,
          'confirmed'=>true,
        ]);
      });
      User_friend_request::whereUser_id($user_id)->whereRequest_user_id($id)->delete();
      //防注入
      $userFriendList = array();
      $tmp = Auth::user()->getFriendList();
      for ($i = 0;$i<count($tmp);$i++)
        $userFriendList[$tmp[$i]->friend_user_id] = $tmp[$i];
      $userFriendRequestList = array();
      $tmp = Auth::user()->getFriendRequestList();
      for ($i = 0;$i<count($tmp);$i++)
        $userFriendRequestList[$tmp[$i]->request_user_id] = $tmp[$i];
      return view('showFriends',['message'=>'你们成为好友啦！','userFriendList'=>$userFriendList,'userFriendRequestList'=>$userFriendRequestList]);
    }
    public function deleteFriend($id)
    {
      $user_id = Auth::id();
      DB::transaction(function() use($user_id,$id)
      {
        User_friend::whereUser_id($id)->whereFriend_user_id($user_id)->delete();
        User_friend::whereUser_id($user_id)->whereFriend_user_id($id)->delete();
      });
      $userFriendList = array();
      $tmp = Auth::user()->getFriendList();
      for ($i = 0;$i<count($tmp);$i++)
        $userFriendList[$tmp[$i]->friend_user_id] = $tmp[$i];
      $userFriendRequestList = array();
      $tmp = Auth::user()->getFriendRequestList();
      for ($i = 0;$i<count($tmp);$i++)
        $userFriendRequestList[$tmp[$i]->request_user_id] = $tmp[$i];
      return view('showFriends',['message'=>'删除成功','userFriendList'=>$userFriendList,'userFriendRequestList'=>$userFriendRequestList]);
    }
}
