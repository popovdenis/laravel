<?php

namespace App\Blog\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Blog\Events\CategoryAdded;
use App\Blog\Events\CategoryEdited;
use App\Blog\Events\CategoryWillBeDeleted;
use App\Blog\Helpers;
use App\Blog\Middleware\UserCanManageBlogPosts;
use App\Blog\Models\Category;
use App\Blog\Models\CategoryTranslation;
use App\Blog\Models\Language;
use App\Blog\Requests\DeleteBlogCategoryRequest;
use App\Blog\Requests\UpdateBlogCategoryRequest;

/**
 * Class CategoryAdminController
 *
 * @package App\Blog\Controllers
 */
class CategoryAdminController extends Controller
{
    /**
     * CategoryAdminController constructor.
     */
    public function __construct()
    {
        $this->middleware(UserCanManageBlogPosts::class);
    }

    /**
     * Show list of categories
     *
     * @return mixed
     */
    public function index(Request $request)
    {
        $languageId = $request->get('language_id');
        $categories = CategoryTranslation::orderBy("category_id")->paginate(25);

        return view("blog_admin::categories.index", [
            'categories' => $categories,
            'language_id' => $languageId
        ]);
    }

    /**
     * Show the form for creating new category
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create_category(Request $request)
    {
        $languageId = 1;
        $languageList = Language::where('active', true)->get();

        $cat_list = Category::whereHas('categoryTranslations', function ($query) use ($languageId)
        {
            return $query->where('lang_id', '=', $languageId);
        })->get();

        $rootList = Category::roots()->get();
        Category::loadSiblingsWithList($rootList);


        return view("blog_admin::categories.add_category", [
            'category' => new \App\Blog\Models\Category(),
            'category_translation' => new \App\Blog\Models\CategoryTranslation(),
            'category_tree' => $cat_list,
            'cat_roots' => $rootList,
            'language_id' => $languageId,
            'language_list' => $languageList
        ]);
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store_category(Request $request)
    {
        $languageId = $request->get('language_id');
        $languageList = $request['data'];

        if ($request['parent_id'] == 0) {
            $request['parent_id'] = null;
        }
        $new_category = Category::create([
            'parent_id' => $request['parent_id']
        ]);

        foreach ($languageList as $key => $value) {
            if ($value['lang_id'] != -1 && $value['category_name'] !== null) {
                //check for slug availability
                $obj = CategoryTranslation::where('slug', $value['slug'])->first();
                if ($obj) {
                    Category::destroy($new_category->id);
                    return response()->json([
                        'code' => 403,
                        'message' => "slug is already taken",
                        'data' => 1
                    ]);
                }
                $new_category_translation = $new_category->categoryTranslations()->create([
                    'category_name' => $value['category_name'],
                    'slug' => $value['slug'],
                    'category_description' => $value['category_description'],
                    'lang_id' => $value['lang_id'],
                    'category_id' => $new_category->id
                ]);
            }
        }

        event(new CategoryAdded($new_category, $new_category_translation));
        Helpers::flash_message("Saved new category");

        return response()->json([
            'code' => 200,
            'message' => "category successfully aaded"
        ]);
    }

    /**
     * Show the edit form for category
     *
     * @param $categoryId
     *
     * @return mixed
     */
    public function edit_category($categoryId, Request $request)
    {
        $languageId = 1;
        $languageList = Language::where('active', true)->get();

        $category = Category::findOrFail($categoryId);
        $cat_trans = CategoryTranslation::where(
            [
                ['lang_id', '=', $languageId],
                ['category_id', '=', $categoryId]
            ]
        )->first();

        return view("blog_admin::categories.edit_category", [
            'category' => $category,
            'category_translation' => $cat_trans,
            'categories_list' => CategoryTranslation::orderBy("category_id")->where('lang_id', $languageId)->get(),
            'language_id' => $languageId,
            'language_list' => $languageList
        ]);
    }

    /**
     * Save submitted changes
     *
     * @param UpdateBlogCategoryRequest $request
     * @param                           $categoryId
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update_category(UpdateBlogCategoryRequest $request, $categoryId)
    {
        /** @var Category $category */
        $category = Category::findOrFail($categoryId);
        $languageId = 1;
        $translation = CategoryTranslation::where(
            [
                ['lang_id', '=', $languageId],
                ['category_id', '=', $categoryId]
            ]
        )->first();
        $category->fill($request->all());
        $translation->fill($request->all());

        // if the parent_id is passed in as 0 it will create an error
        if ($category->parent_id <= 0) {
            $category->parent_id = null;
        }

        $category->save();
        $translation->save();

        Helpers::flash_message("Saved category changes");
        event(new CategoryEdited($category));

        return redirect($translation->editUrl());
    }

    /**
     * Delete the category
     *
     * @param DeleteBlogCategoryRequest $request
     * @param                           $categoryId
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function destroy_category(DeleteBlogCategoryRequest $request, $categoryId)
    {
        $category = Category::findOrFail($categoryId);
        $children = $category->children()->get();
        if (sizeof($children) > 0) {
            Helpers::flash_message("This category could not be deleted it has some sub-categories. First try to change parent category of subs.");
            return redirect(route('blog.admin.categories.index'));
        }

        event(new CategoryWillBeDeleted($category));
        $category->delete();

        Helpers::flash_message("Category successfully deleted!");
        return redirect(route('blog.admin.categories.index'));
    }
}
