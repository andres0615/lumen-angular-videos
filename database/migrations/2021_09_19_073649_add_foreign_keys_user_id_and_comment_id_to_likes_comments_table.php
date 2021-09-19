<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeysUserIdAndCommentIdToLikesCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('likes_comments', function(Blueprint $table)
        {

            $foreignKeyName = $this->getForeignKeyName(
                'likes_comments', 'user_id', 'users', 'id'
            );

            $table->foreign('user_id', $foreignKeyName)->references('id')->on('users')->onUpdate('RESTRICT')->onDelete('RESTRICT');

            $foreignKeyName = $this->getForeignKeyName(
                'likes_comments', 'comment_id', 'comments', 'id'
            );

            $table->foreign('comment_id', $foreignKeyName)->references('id')->on('comments')->onUpdate('RESTRICT')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('likes_comments', function(Blueprint $table)
        {
            $table->dropForeign('likes_comments_user_id_users_id');
            $table->dropForeign('likes_comments_comment_id_comments_id');
        });
    }

    public function getForeignKeyName($localTableName, $localColumnName, $foreignTableName, $foreignColumnName) {

        $foreignKeyNameArray = [
                $localTableName,$localColumnName,$foreignTableName,$foreignColumnName
            ];

        $foreignKeyName = implode('_',$foreignKeyNameArray);

        return $foreignKeyName;
    }
}
