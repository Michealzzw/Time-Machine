<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InvitationSystem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('event_invitations', function (Blueprint $table) {
        $table->integer('user_id')->unsigned();
          $table->integer('event_id')->unsigned();
          $table->foreign('event_id')
                ->references('id')
                ->on('events')
                ->onDelete("cascade");;
          $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete("cascade");;
          $table->boolean('confirmed');
          $table->index('user_id');
          $table->timestamps();
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('event_invitations');
    }
}
