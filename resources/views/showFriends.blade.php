@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    <br>


                    <?php
                    if (isset($message))
                    echo $message."<br>";
                    if (isset($findFriendResult))
                    {
                      $num = count($findFriendResult);
                      if ($num!=0)
                      {
                        echo "<ul>";
                        for ($i = 0;$i<$num;$i++)
                        {
                          if (array_key_exists($findFriendResult[$i]->id,$userFriendRequestList))
                          echo "<li> ".$findFriendResult[$i]->name." ".$findFriendResult[$i]->email." 已经发送过请求 </li>";
                          else
                          if (array_key_exists($findFriendResult[$i]->id,$userFriendList))
                          echo "<li> ".$findFriendResult[$i]->name." ".$findFriendResult[$i]->email." 已经是在列表中啦 </li>";
                          else
                          echo "<li> ".$findFriendResult[$i]->name." ".$findFriendResult[$i]->email." <a href=\"/addRequest/".$findFriendResult[$i]->id."\">请求加好友</a> </li>";
                        }
                        echo "</ul>";
                      }
                      else echo "找不到叫这个名字的胖友";
                      echo "<br>";
                    }

                    $num = count($userFriendRequestList);
                    if ($num!=0)
                    {
                      echo "<ul>";
                      foreach ($userFriendRequestList as $request)
                      {
                        echo "<li> ".$request->request_user_name." <a href=\"/addFriend/".$request->request_user_id."\">同意</a> <a href=\"/deleteRequest/".$request->request_user_id."\">拒绝</a> </li>";
                      }
                      echo "</ul>";
                    }
                    else echo "你没有任何朋友请求！";

                    $num = count($userFriendList);
                    if ($num!=0)
                    {
                      echo "<ul>";
                      foreach ($userFriendList as $friend)
                      {
                        if ($friend->confirmed)
                        {
                          if (isset($friend->event_name))
                          echo "<li> ".$friend->friend_user_name." 他在".$friend->event_name." <a href=\"/deleteFriend/".$friend->friend_user_id."\">删除</a></li>";
                          else
                          echo "<li> ".$friend->friend_user_name." <a href=\"/deleteFriend/".$friend->friend_user_id."\">删除</a></li>";
                        }
                        else {
                          echo "<li> ".$friend->friend_user_name." 请求中</li>";
                        }
                      }
                      echo "</ul>";
                    }
                    else echo "你还没有任何朋友！";
                    echo "<br>";
                    ?>
                    <br>
                    <form action="/findFriend" method="get" id="form">
                        <label>找呀找呀找朋友: </label><input type="text" name="friend_name" value="输入好友姓名"><br>
                        <input type="submit" value="查找">
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
