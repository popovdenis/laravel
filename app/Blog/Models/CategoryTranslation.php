<?php
namespace App\Blog\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryTranslation extends Model
{
    public $fillable = [
        'category_id',
        'category_name',
        'slug',
        'category_description',
        'lang_id'
    ];

    /**
     * Get the category that owns the phone.
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * The associated Language
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function language()
    {
        return $this->hasOne(Language::class,"lang_id");
    }

    /**
     * Returns the public facing URL of showing blog posts in this category
     * @return string
     */
    public function url()
    {
        $theChainString = [];
        $cat = $this->category()->get();
        $chain = $cat[0]->getAncestorsAndSelf();

        foreach ($chain as $category){
            $theChainString[] = $category->categoryTranslations()->where('lang_id' , 1)->first()->slug;
        }
        $theChainString = implode('/', $theChainString);

        return route("blog.view_category", ['subcategories' => $theChainString]);
    }

    /**
     * Returns the URL for an admin user to edit this category
     * @return string
     */
    public function edit_url()
    {
        return route("blog.admin.categories.edit_category", $this->category_id);
    }
}
