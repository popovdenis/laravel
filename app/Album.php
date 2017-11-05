<?php

namespace App;

use function foo\func;
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
        return $this->belongsTo(User::class, 'user_id')->firstOrFail();
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
    
    public function comments($newOnly = false)
    {
        $commentsPerImage = [];
        $images = $this->images($this);
        if (!empty($images)) {
            foreach ($images as $image) {
                $commentsPerImage[$image->id] = $image->comments($newOnly)->get()->all();
            }
        }
        
        return $commentsPerImage;
    }
    
    public function getCountComments(array $commentsPerPhotos)
    {
        $commentsCount = 0;
        foreach ($commentsPerPhotos as $photoId => $comments) {
            $commentsCount+= count($comments);
        }
    
        return $commentsCount;
    }
    
    public function markCommentsAsRead(array $commentsPerPhotos)
    {
        foreach ($commentsPerPhotos as $photoId => $comments) {
            foreach ($comments as $comment) {
                $comment->is_new = false;
                $comment->save();
            }
        }
    }
}
