<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EventSystem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('event_types', function (Blueprint $table) {
          $table->increments('id');
          $table->string('name', 100);
          $table->integer('parent_event_type_id')->unsigned()->nullable();
          $table->foreign('parent_event_type_id')
                ->references('id')->on('event_types')
                ->onDelete("restrict");
          $table->index('parent_event_type_id');
          $table->timestamps();
      });
      //事件类型，事件父类

      Schema::create('events', function (Blueprint $table) {
          $table->increments('id');
          $table->string('note', 200);
          $table->string('name', 100);
          $table->integer('event_type_id')->unsigned();
          $table->integer('host_user_id')->unsigned();
          $table->foreign('host_user_id')
                ->references('id')
                ->on('users')
                ->onDelete("cascade");
          $table->foreign('event_type_id')
                ->references('id')
                ->on('event_types')
                ->onDelete("restrict");;
          $table->timestamps();
      });
      //一个事件，对应事件类型，主持人，备注


      Schema::create('user_events', function (Blueprint $table) {
          $table->integer('user_id')->unsigned();
          $table->integer('event_id')->unsigned();
          $table->string('event_type_name', 100);
          $table->dateTime("start_time");
          $table->dateTime("end_time");
          $table->timestamps();
          $table->primary(['user_id', 'event_id']);
          $table->index('start_time');
          $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete("cascade");
          $table->foreign('event_id')
                ->references('id')
                ->on('events')->onDelete("restrict");
      });
      //一个用户的所有事件
      Schema::create('user_now_events', function (Blueprint $table) {
          $table->integer('user_id')->unsigned()->unique();
          $table->integer('event_id')->unsigned();
          $table->string('event_type_name', 100);
          $table->timestamps();
          $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete("cascade");;
          $table->foreign('event_id')
                ->references('id')
                ->on('events')->onDelete("restrict");;
      });

      Schema::create('user_event_types', function (Blueprint $table) {
          $table->integer('user_id')->unsigned();
          $table->integer('event_type_id')->unsigned();
          $table->string('event_type_name', 100);
          $table->timestamps();
          $table->primary(['user_id', 'event_type_id']);
          $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete("cascade");;
          $table->foreign('event_type_id')
                ->references('id')
                ->on('event_types')
                ->onDelete("restrict");;
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::drop('user_event_types');
      Schema::drop('user_now_events');
      Schema::drop('user_events');
      Schema::drop('events');
      Schema::drop('event_types');
    }
}
