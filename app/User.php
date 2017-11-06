<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'firstname', 'lastname', 'email', 'password',
        'is_admin', 'new_comments', 'avatar_path'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    
    public function albums()
    {
        return $this->hasMany(Album::class);
    }
    
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    
    public function commentsToImages()
    {
        return $this->hasMany(Comment::class, 'image_owner_id');
    }
    
    public function hasNewComments()
    {
        return $this->new_comments > 0;
    }
    
    public function getNewCommentCount()
    {
        return $this->new_comments;
    }
    
    public function newComments()
    {
        return $this->commentsToImages()
            ->where('is_new', true)
            ->orderBy('id', 'DESC')
            ->groupBy('image_id')
            ->get()
            ->all();
    }
    
    public function incrementNewComments()
    {
        $this->new_comments++;
        $this->save();
    }
    
    public function decreaseNewComments($commentsCount = 0)
    {
        $commentsCount = $this->new_comments - $commentsCount;
        $this->new_comments = $commentsCount > 0 ? $commentsCount : 0;
        $this->save();
        
        return $this;
    }
    
    /**
     * Update the model in the database.
     *
     * @param  array  $attributes
     * @param  array  $options
     * @return bool
     */
    public function update(array $attributes = [], array $options = [])
    {
        if (! $this->exists) {
            return false;
        }
        
        return $this->fill(array_filter($attributes))->save($options);
    }
    
    public function getFullname()
    {
        return $this->firstname . ' ' . $this->lastname;
    }
    
    public function isAdmin()
    {
        return (bool) $this->is_admin;
    }
    
    public function delete()
    {
        $this->hasMany(Comment::class, 'user_id')->delete();
        
        foreach ($this->albums()->get()->all() as $album) {
            $album->delete();
        }
    
        parent::delete();
        
        return $this;
    }
}
