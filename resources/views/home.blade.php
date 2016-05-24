@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    欢迎, {{Auth::user()->name}}!
                    <br>
                    <?php
                    if (isset($now_event_id))
                    {
                      echo '你现在正在进行'.$now_event_id;
                      echo "<a href=\"/endEvent?event_id=".$now_event_id."\">结束当前事件</a>";
                    }
                    else
                    {
                      echo '你现在正在干什么呢？<br>';
                      echo "<a href=\"/selectEventType\">创建新事件</a>";
                    }
                    ?>


                </div>
            </div>
        </div>
    </div>
</div>
@endsection
