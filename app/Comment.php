<?php 

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model {

    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'comments';

    protected $primaryKey='id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'comment', 'user_id', 'created_at', 'updated_at',
    ];

}
