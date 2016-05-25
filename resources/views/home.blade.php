@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    <?php
                    if (isset($message)&&$message!=null)
                    echo $message;
                    ?>
                    欢迎, {{Auth::user()->name}}!
                    <br>
                    <?php
                    if (isset($event_now))
                    {
                      echo '你现在正在进行 <label>'.$event_now->event_name."</label> ";
                      $num = count($participants);
                      if ($num!=0)
                      {
                        echo "和 ";
                        foreach ($participants as $participant)
                        {
                          echo $participant;
                          echo " ";
                        }
                        echo "一起哟<br>";
                      }
                      echo "<a href=\"/endEvent?event_id=".$event_now->event_id."\">结束当前事件</a> ";
                      echo "<a href=\"/selectEventType\">创建已完成事件</a>";
                    }
                    else
                    {
                      echo '你现在正在干什么呢？<br>';
                      echo "<a href=\"/selectEventType\">创建新事件</a>";
                    }
                    if (isset($invitation)&&$invitation!=null)
                    {
                      $num = count($invitation);
                      if ($num!=0)
                      {
                        echo "<ul>";
                        foreach ($invitation as $invite)
                        {
                          echo "<li> ".$invite->user_name." 邀请你一起".$invite->event_name." <a href=\"/addEvent/".$invite->event_id."\">参加</a></li>";
                        }
                        echo "</ul>";
                      }
                      else echo "你还没有任何朋友！";
                      echo "<br>";
                    }
                    ?>


                </div>
            </div>
        </div>
    </div>
</div>
@endsection
