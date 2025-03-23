<?php
namespace App\Blog\Models;

use App\Blog\Baum\Node;

class Category extends Node
{
    protected $parentColumn = 'parent_id';
    public $siblings = array();

    public $fillable = [
        'parent_id'
    ];

    public static function boot() {
        parent::boot();

        static::deleting(function($category) { // before delete() method call this
            $category->categoryTranslations()->delete();
        });
    }

    /**
     * The associated category translations
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function categoryTranslations()
    {
        return $this->hasMany(CategoryTranslation::class,"category_id");
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function posts()
    {
        return $this->belongsToMany(Post::class, 'binshops_post_categories','category_id', 'post_id');
    }

    public function loadSiblings(){
        $this->siblings = $this->children()->get();
    }

    public static function loadSiblingsWithList($node_list){
        for($i = 0 ; sizeof($node_list) > $i ; $i++){
            $node_list[$i]->loadSiblings();
            if (sizeof($node_list[$i]->siblings) > 0){
                self::loadSiblingsWithList($node_list[$i]->siblings);
            }
        }
    }

//    public function parent()
//    {
//        return $this->belongsTo('Blog\Models\Category', 'parent_id');
//    }
//
//    public function children()
//    {
//        return $this->hasMany('Blog\Models\Category', 'parent_id');
//    }
//
//    // recursive, loads all descendants
//    private function childrenRecursive()
//    {
//        return $this->children()->with('children')->get();
//    }
//
//    public function loadChildren(){
//        $this->childrenCat = $this->childrenRecursive();
//    }

//    public function scopeApproved($query)
//    {
//        dd("A");
//        return $query->where("approved", true);
//    }
}
