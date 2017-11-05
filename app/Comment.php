<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    public $fillable = ['name', 'email', 'text', 'is_new', 'image_id', 'user_id', 'image_owner_id', 'parent_id'];
    
    public function photo()
    {
        return $this->belongsTo(Photo::class, 'image_id')->get()->first();
    }
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    public function author()
    {
        return $this->user()->firstOrFail();
    }
    
    public function imageOwner()
    {
        return $this->belongsTo(User::class, 'image_owner_id');
    }
    
    public function parent()
    {
        return $this->belongsTo('App\Comment', 'parent_id');
    }
    
    public function children()
    {
        return $this->hasMany('App\Comment', 'parent_id');
    }
    
    public function isParent()
    {
        return (int) $this->parent_id === 0;
    }
}
