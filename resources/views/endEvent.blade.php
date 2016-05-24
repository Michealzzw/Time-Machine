@extends('layouts.app')
<script>
  function getNowTime()
  {
    var myDate = new Date();
    var time = myDate.getFullYear()+"-"+ (myDate.getMonth()+1) +"-"+ myDate.getDate() +" "+ myDate.getHours() +":"+myDate.getMinutes();    //获取完整的年份(4位,1970-????)
    document.getElementById("end_time").value=time;
  }
  window.onload=getNowTime;

</script>
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    <?php
                      echo $message;

                    ?>
                    <br>
                    <form action="/endEvent" method="get" id="form">
                        <label id="startForm">结束时间: </label><input type="datetime" name="end_time" id="end_time" value=""><br>
                        <input type="submit" value="结束">
                    </form>
                    <a href="javascript:history.back(-1)">返回上一页</a>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
