<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLikesCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('likes_comments', function (Blueprint $table) {
            $table->primary(['user_id','comment_id'], 'pkey_likes_comments');
            $table->integer('user_id')->unsigned();
            $table->integer('comment_id')->unsigned();
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
        Schema::dropIfExists('likes_comments');
    }
}
