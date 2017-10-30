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
        return $this->hasMany(AlbumImage::class, 'image_id')->first();
    }
    
    public function comments()
    {
        return $this->hasMany(\App\Comment::class, 'image_id')->where('status', 1)/*->groupBy('parent_id')*/;
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
