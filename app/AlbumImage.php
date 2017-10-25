<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Album;
use App\Photo;

class AlbumImage extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'album_image';
    
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
    
    public $fillable = ['album_id', 'image_id'];
    
    public function album()
    {
        return $this->belongsTo(Album::class, 'album_id');
    }
    
    public function image()
    {
        return $this->belongsTo(Photo::class, 'image_id');
    }
}
