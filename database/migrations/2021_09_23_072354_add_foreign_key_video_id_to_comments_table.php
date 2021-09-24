<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeyVideoIdToCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('comments', function (Blueprint $table) {
            $localTableName = 'comments';
            $localColumnName = 'video_id';
            $foreignTableName = 'videos';
            $foreignColumnName = 'id';


            $foreignKeyName = $this->getForeignKeyName(
                $localTableName,
                $localColumnName,
                $foreignTableName,
                $foreignColumnName
            );

            $table->foreign($localColumnName, $foreignKeyName)->references($foreignColumnName)->on($foreignTableName)->onUpdate('RESTRICT')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('comments', function (Blueprint $table) {
            $table->dropForeign('comments_video_id_videos_id');
        });
    }

    public function getForeignKeyName($localTableName, $localColumnName, $foreignTableName, $foreignColumnName)
    {
        $foreignKeyNameArray = [
                $localTableName,
                $localColumnName,
                $foreignTableName,
                $foreignColumnName
            ];

        $foreignKeyName = implode('_', $foreignKeyNameArray);

        return $foreignKeyName;
    }
}
