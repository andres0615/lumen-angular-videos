<?php 

namespace App;

use Illuminate\Database\Eloquent\Model;

class Video extends Model {

    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'videos';

    protected $primaryKey='id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'title', 'description', 'path', 'thumbnail', 'user_id', 'created_at', 'updated_at',
    ];

}
