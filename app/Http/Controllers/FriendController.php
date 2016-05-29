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

class FriendController extends Controller
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




       public function time_formal_check($str)
       {
         if (!preg_match("/^[0-9]{4}-([0]?[1-9])|([1][0-2])-([0-2]?[0-9])|(3[0-1]) ([0-1]?[0-9])|(2[0-4]):([0-5]?[0-9])$/",$str))
         return false;
         else return true;
       }


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
