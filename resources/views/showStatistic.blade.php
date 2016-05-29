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
                    $num = count($all);
                    if ($num!=0)
                    {
                      echo "总计\n";
                      echo "<table border=1>\n";
                      echo "<tr><td>类型</td><td>耗时</td></tr>\n";
                      for ($i = 0;$i<$num;$i++)
                      if ($all[$i]->day==null&&$all[$i]->event_type_id!=null)
                      {
                        echo "<tr>";

                        echo "<td>".$all[$i]->event_type_name."</td>";

                        $time = $all[$i]->totaltime;
                        echo "<td>".floor(($time)/60/24)." days ".floor($time/60%24)." hrs ".floor($time%60)." mins</td>";
                        // echo "<td>".$userEventList[$i]->note."</td>";
                        echo "</tr>\n";
                      }
                      echo "</table>\n";
                    }
                    else echo "你还没有任何事件！";
                    echo "<br>";
                    $num = count($recent);
                    if ($num!=0)
                    {
                      echo "最近一周";
                      echo "<table border=1>\n";
                      echo "<tr><td>类型</td><td>耗时</td></tr>\n";
                      for ($i = 0;$i<$num;$i++)
                      {
                        echo "<tr>";
                        echo "<td>".$recent[$i]->event_type_name."</td>";
                        $time = $recent[$i]->totaltime;
                        echo "<td>".floor($time/60/24)." days ".floor($time/60%24)." hrs ".floor($time%60)." mins</td>";
                        // echo "<td>".$userEventList[$i]->note."</td>";
                        echo "</tr>\n";
                      }
                      echo "</table>\n";
                    }
                    else echo "你还没有任何事件！";
                    echo "<br>";
                    $num = count($day);
                    echo "按天统计";
                    if ($num!=0)
                    {
                      echo "<table border=1>\n";
                      echo "<tr><td>日期</td><td>类型</td><td>耗时</td></tr>\n";
                      for ($i = 0;$i<$num;$i++)
                      if ($day[$i]->day!=null&&$day[$i]->event_type_id!=null)
                      {
                        echo "<tr>";
                        echo "<td>".$day[$i]->day."</td>";
                        echo "<td>".$day[$i]->event_type_name."</td>";
                        $time = $day[$i]->totaltime;
                        echo "<td>".floor($time/60/24)." days ".floor($time/60%24)." hrs ".floor($time%60)." mins</td>";
                        // echo "<td>".$userEventList[$i]->note."</td>";
                        echo "</tr>\n";
                      }
                      echo "</table>\n";
                    }
                    else echo "你还没有任何事件！";
                    echo "<br>";
                    ?>
                    </table>
                    <br>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
