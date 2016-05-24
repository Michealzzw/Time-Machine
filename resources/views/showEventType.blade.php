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
                      {
                        echo "<li>".$eventTypeList[$i]->name." ";
                        if (!array_key_exists($eventTypeList[$i]->id,$userEventTypeList))
                        {
                          if (isset($show_parent_event_id))
                            echo "<a href=\"/addEventType/".$eventTypeList[$i]->id.
                                "?show_parent_event_id=".$show_parent_event_id . "&event_type_name=".$eventTypeList[$i]->name."\">添加</a> ";
                          else {
                            echo "<a href=\"/addEventType/".$eventTypeList[$i]->id.
                              "?show_parent_event_id=-1&event_type_name=".$eventTypeList[$i]->name."\">添加</a> ";
                          }
                        }
                        else echo "已添加 ";
                        echo "<a href=\"/showEventType/".$eventTypeList[$i]->id."\">查看子类型</a></li>";
                      }
                      echo "</ul>";
                    }
                    else echo "没有子事件类型啦！";
                    echo "<br>";
                    ?>
                    <br>
                    <a href="javascript:history.back(-1)">返回上一页</a>
                    <a href="/selectEventType">返回添加事件</a>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
