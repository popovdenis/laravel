<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Album;
use App\Comment;

class Photo extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'images';
    
    public $fillable = ['title', 'path', 'path_thumb'];
    
    public function album()
    {
        return $this->belongsTo(Album::class);
    }
    
    public function comments()
    {
        return $this->belongsTo(\App\Comment::class, 'image_id');
    }
}
