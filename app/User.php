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
        'firstname', 'lastname', 'email', 'password', 'is_admin', 'new_comments'
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
    
    public function hasNewComments()
    {
        return $this->new_comments > 0;
    }
    
    public function getNewComments()
    {
        return $this->comments()->where('is_new', true)->get()->all();
    }
    
    public function incrementNewComments()
    {
        $this->new_comments++;
        $this->save();
    }
    
    public function decreaseNewComments($commentsCount = 0)
    {
        $this->new_comments -= $commentsCount;
        $this->save();
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
}
