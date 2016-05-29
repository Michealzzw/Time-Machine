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
                    echo $event->name."<br>";
                    echo "事件类型：".$event->event_type_id."<br>";
                    echo "备注：".$event->note."<br>";
                    echo "主持人：".$event->host_user_id."<br>";
                    echo "参与者：<br>";
                    $num = count($users);
                    if ($num!=0)
                    {
                      for ($i = 0;$i<$num;$i++)
                      {
                        echo $users[$i]." ";
                      }
                    }
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
