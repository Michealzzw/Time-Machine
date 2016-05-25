@extends('layouts.app')
<script>
  function getNowTime()
  {
    var myDate = new Date();
    var time = myDate.getFullYear()+"-"+ (myDate.getMonth()+1) +"-"+ myDate.getDate() +" "+ myDate.getHours() +":"+myDate.getMinutes();    //获取完整的年份(4位,1970-????)
    return time;
  }
  window.onload=EventHappened;
  function EventHappen()
  {
    var tmp = document.getElementById("startForm");
    tmp.innerHTML = "开始时间: ";
    tmp = document.getElementById("endForm");
    tmp.innerHTML = "";
    document.getElementById("invitation").style.display="inline";

    document.getElementById("start_time").style.display="inline";
    document.getElementById("start_time").value=getNowTime();

    document.getElementById("end_time").style.display="none";
    document.getElementById("end_time").value=null;
  }
  function EventHappening()
  {
    var tmp = document.getElementById("startForm");
    tmp.innerHTML = "开始时间: ";
    tmp = document.getElementById("endForm");
    tmp.innerHTML = "";

    document.getElementById("start_time").style.display="inline";
document.getElementById("start_time").value=getNowTime();

    document.getElementById("end_time").style.display="none";
    document.getElementById("end_time").value=null;
  }
  function EventHappened()
  {
    var tmp = document.getElementById("startForm");
    tmp.innerHTML = "开始时间: ";
    tmp = document.getElementById("endForm");
    tmp.innerHTML = "结束时间: ";
    document.getElementById("invitation").style.display="none";
    document.getElementById("start_time").style.display="inline";
    document.getElementById("start_time").value=getNowTime();

    document.getElementById("end_time").style.display="inline";
    document.getElementById("end_time").value=getNowTime();

  }
</script>
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    <?php
                    if (isset($message))
                      echo $message;
                    ?>
                    <?php
                      echo "<h4>创建事件:".$event_type_name."</h4>";

                    ?>
                    <br>
                    <label <?php if (isset($event_now)) echo "style=\"display:none\""?>><input name="EventType" type="radio" value="" checked="<?php if (isset($event_now)) echo "false"; else echo "true";?>" onclick="EventHappen()" />现在开始新事件 </label>
                    <label><input name="EventType" type="radio" value="" checked="<?php if (isset($event_now)) echo "true"; else echo "false";?>" onclick="EventHappened()" />已完成事件 </label>
                    <label <?php if (isset($event_now)) echo "style=\"display:none\""?>><input name="EventType" type="radio" value="" onclick="EventHappening()"/>已经开始的事件 </label>
                    <br>
                    <form action="/addEvent" method="post" id="form">
                        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                        <label>事件名: </label><input type="text" name="event_type_name" value="<?php echo $event_type_name;?>"><br>
                        <label id="startForm">开始时间: </label><input type="datetime" name="start_time" id="start_time" value=""><br>
                        <label id="endForm" ></label><input type="datetime" name="end_time" id="end_time" value="" style="display:none"><br>
                        <label id="endForm" >备注:</label><input type="text" name="note" id="note"><br>
                        <input type="hidden" name="event_type_id" value="<?php echo $event_type_id;?>">
                        <input type="hidden" name="event_type_name" value="<?php echo $event_type_name;?>">
                        <div id = "invitation">
                        <?php
                        $num = count($userFriendList);
                        if ($num!=0)
                        {
                          echo "你可以邀请你的朋友一起：";
                          echo "<ul>";
                          foreach ($userFriendList as $friend)
                          {
                            if ($friend->confirmed)
                            {
                              echo "<li>";
                              if (isset($friend->event_name))
                              echo "<label><input type=checkbox name=\"".$friend->friend_user_id."\" id=\"invitationList\" value=\"".$friend->friend_user_id."\"> ".$friend->friend_user_name." 他在".$friend->event_name." </label></li>";
                              else
                              echo "<label><input type=checkbox name=\"".$friend->friend_user_id."\" id=\"invitationList\" value=\"".$friend->friend_user_id."\"> ".$friend->friend_user_name."</label></li>";
                            }
                          }
                          echo "</ul>";
                        }
                        else echo "你还没有任何朋友！";
                        echo "<br>";
                        ?>
                      </div>
                        <input type="submit" value="建立事件">
                    </form>
                    <a href="javascript:history.back(-1)">返回上一页</a>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
