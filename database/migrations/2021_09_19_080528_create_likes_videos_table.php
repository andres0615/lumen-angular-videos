<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLikesVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('likes_videos', function (Blueprint $table) {
            $table->primary(['user_id','video_id'], 'pkey_likes_videos');
            $table->integer('user_id')->unsigned();
            $table->integer('video_id')->unsigned();
            $table->boolean('type');
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
        Schema::dropIfExists('likes_videos');
    }
}
