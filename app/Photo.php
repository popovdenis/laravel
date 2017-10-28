<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Album;

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
}
