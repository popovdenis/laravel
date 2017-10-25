<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\AlbumImage;

class Album extends Model
{
    public $fillable = ['title', 'user_id'];
    
    public function getAlbumImagesRelationship()
    {
        return $this->hasMany(AlbumImage::class);
    }
    
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id')->get()->first();
    }
    
    public function images(Album $album, array $imagesIds = [])
    {
        $list = $album->getAlbumImagesRelationship()->get();
        $list = empty($imagesIds) ? $list->all() : $list->whereIn('image_id', $imagesIds);
        $images = [];
        foreach ($list as $item) {
            /** @var $item AlbumImage */
            $images[] = $item->image()->get()->first();
        }
        
        return $images;
    }
}
