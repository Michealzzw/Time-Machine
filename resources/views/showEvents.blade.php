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
                    $num = count($userEventList);
                    if ($num!=0)
                    {
                      echo "<table border=1>";
                      for ($i = 0;$i<$num;$i++)
                      {
                        echo "<tr>";
                        echo "<td><a href=\"/showEvent/".$userEventList[$i]->event_id."\">".$userEventList[$i]->event_name."</a></td>";
                        echo "<td>".$userEventList[$i]->start_time."</td>";
                        echo "<td>".$userEventList[$i]->end_time."</td>";
                        // echo "<td>".$userEventList[$i]->note."</td>";
                        echo "</tr>";
                      }
                      echo "</table>";
                    }
                    else echo "你还没有任何事件！";
                    echo "<br>";
                    ?>
                    </table>
                    <br>
                    <a href="/selectEventType">添加新事件</a>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
