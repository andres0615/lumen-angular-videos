<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeysUserIdAndVideoIdToLikesVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('likes_videos', function (Blueprint $table) {
            $foreignKeyName = $this->getForeignKeyName(
                'likes_videos',
                'user_id',
                'users',
                'id'
            );

            $table->foreign('user_id', $foreignKeyName)->references('id')->on('users')->onUpdate('RESTRICT')->onDelete('RESTRICT');

            $foreignKeyName = $this->getForeignKeyName(
                'likes_videos',
                'video_id',
                'videos',
                'id'
            );

            $table->foreign('video_id', $foreignKeyName)->references('id')->on('videos')->onUpdate('RESTRICT')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('likes_videos', function (Blueprint $table) {
            $table->dropForeign('likes_videos_user_id_users_id');
            $table->dropForeign('likes_videos_video_id_videos_id');
        });
    }

    public function getForeignKeyName($localTableName, $localColumnName, $foreignTableName, $foreignColumnName)
    {
        $foreignKeyNameArray = [
                $localTableName,$localColumnName,$foreignTableName,$foreignColumnName
            ];

        $foreignKeyName = implode('_', $foreignKeyNameArray);

        return $foreignKeyName;
    }
}
