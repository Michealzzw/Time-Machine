<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\User;
use App\Event;
use App\Event_type;
use App\User_event_type;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
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
    public function index()
    {
        $event_now = Auth::user()->getEventNow();
        if ($event_now!=null)
        return view('home',['now_event_id'=>$event_now->id,'now_event_name'=>$event_now->event_name]);
        else
        return view('home');
    }
    public function selectEventType()
    {
      $eventTypeList = Auth::user()->getEventTypeList();
      return view('selectEventType',['eventTypeList'=>$eventTypeList]);
    }
    public function showEventType()
    {
      $tmp = Auth::user()->getEventTypeList();
      for ($i = 0;$i<count($tmp);$i++)
        $userEventTypeList[$tmp[$i]->event_type_id] = $tmp[$i];
      $eventTypeList = Event_type::select("*")->whereParent_event_type_id(null)->get();
      //return $eventTypeList;
      return view('showEventType',['eventTypeList'=>$eventTypeList,'userEventTypeList'=>$userEventTypeList]);
    }
    public function showFromParentEventType($parent_event_type_id)
    {
      $tmp = Auth::user()->getEventTypeList();
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
}
