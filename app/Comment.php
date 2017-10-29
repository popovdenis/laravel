<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    public $fillable = ['name', 'email', 'text', 'image_id', 'user_id', 'parent_id'];
    
    public function photo()
    {
        return $this->belongsTo(Photo::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function parent()
    {
        return $this->belongsTo('App\Comment', 'parent_id');
    }
    
    public function children()
    {
        return $this->hasMany('App\Comment', 'parent_id');
    }
}
