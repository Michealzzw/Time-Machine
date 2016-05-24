@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    请选择事件类型：
                    <br>
                    <?php
                    $num = count($eventTypeList);
                    if ($num!=0)
                    {
                      echo "<ul>";
                      for ($i = 0;$i<$num;$i++)
                        echo "<li><a href=\"/addEvent?event_type_id=".$eventTypeList[$i]->event_type_id.
                        "&event_name=".$eventTypeList[$i]->event_type_name . "\">".$eventTypeList[$i]->event_type_name."</a></li>";
                      echo "</ul>";
                    }
                    else echo "你还没有任何事件类型";
                    echo "<br>";
                    echo "<a href=\"/showEventType\">添加新事件类型</a>";
                    ?>
                    <br>
                    <a href="javascript:history.back(-1)">返回上一页</a>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
