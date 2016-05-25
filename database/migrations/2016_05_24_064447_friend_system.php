<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FriendSystem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('user_friends', function (Blueprint $table) {
          $table->integer('user_id')->unsigned();
          $table->integer('friend_user_id')->unsigned();
          $table->string('friend_user_name');
          $table->boolean('confirmed');
          $table->timestamps();
          $table->primary(['user_id', 'friend_user_id']);
          $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete("cascade");;
          $table->foreign('friend_user_id')
                ->references('id')
                ->on('users')
                ->onDelete("cascade");;
      });
      Schema::create('user_friend_requests', function (Blueprint $table) {
          $table->integer('user_id')->unsigned();
          $table->integer('request_user_id')->unsigned();
          $table->string('request_user_name');
          $table->boolean('confirmed');
          $table->timestamps();
          $table->primary(['user_id', 'request_user_id']);
          $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete("cascade");;
          $table->foreign('request_user_id')
                ->references('id')
                ->on('users')
                ->onDelete("cascade");;
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::drop('user_friend_requests');
      Schema::drop('user_friends');
    }
}
