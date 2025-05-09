<?php

namespace App\Blog\Controllers;

use App\Blog\FulltextSearch\Search;
use App\Blog\Models\Category;
use App\Blog\Models\CategoryTranslation;
use App\Blog\Models\PostTranslation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Base\Http\Controllers\Controller;

class ReaderController extends Controller
{
    /**
     * Show blog posts
     * If category_slug is set, then only show from that category
     *
     * @param Request $request
     * @param null    $categorySlug
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request, $categorySlug = null)
    {
        $title = 'Blog Page'; // default title...

        $categoryChain = null;

        if ($categorySlug) {
            $categoryTranslation = CategoryTranslation::where('slug', $categorySlug)
                ->with('category')
                ->firstOrFail();

            $category = $categoryTranslation->category;

            $categoryChain = $category->getAncestorsAndSelf();
            $posts = $category->posts()->where('post_categories.category_id', $category->id)
                ->with([
                    'postTranslations' => function ($query) use ($request)
                    {
                        $query->where('lang_id', 1);
                    }
                ])->get();

            $posts = PostTranslation::join('posts', 'post_translations.post_id', '=', 'posts.id')
                ->where('lang_id', 1)
                ->where('is_published', true)
                ->where('posted_at', '<', Carbon::now()->format('Y-m-d H:i:s'))
                ->orderBy('posted_at', 'desc')
                ->whereIn('posts.id', $posts->pluck('id'))
                ->paginate(config('blog.per_page', 10));

            // Set category for view
            \View::share('blog_category', $category);
            $title = 'Posts in ' . $categoryTranslation->category_name . ' category';
        } else {
            $posts = PostTranslation::join('posts', 'post_translations.post_id', '=', 'posts.id')
                ->where('lang_id', 1)
                ->where('is_published', true)
                ->where('posted_at', '<', Carbon::now()->format('Y-m-d H:i:s'))
                ->orderBy('posted_at', 'desc')
                ->paginate(config('blog.per_page', 10));
        }

        // Load category hierarchy
        $rootList = Category::roots()->get();
        Category::loadSiblingsWithList($rootList);

        return view('blog.index', [
            'locale' => $request->get('locale'),
            'lang_id' => 1,
            'category_chain' => $categoryChain,
            'categories' => $rootList,
            'posts' => $posts,
            'title' => $title,
            'routeWithoutLocale' => $request->get('routeWithoutLocale'),
        ]);
    }

    /**
     * Show the search results for $_GET['s']
     *
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Exception
     */
    public function search(Request $request)
    {
        if (!config('blog.search.search_enabled')) {
            throw new \Exception('Search is disabled');
        }

        $query = $request->get('s');
        $search = new Search();
        $searchResults = $search->run($query);

        \View::share('title', 'Search results for ' . e($query));

        $rootList = Category::roots()->get();
        Category::loadSiblingsWithList($rootList);

        return view('blog::search', [
            'lang_id' => 1,
            'locale' => $request->get('locale'),
            'categories' => $rootList,
            'query' => $query,
            'search_results' => $searchResults,
            'routeWithoutLocale' => $request->get('routeWithoutLocale'),
        ]);
    }

    /**
     * View all posts in $categorySlug category
     *
     * @param Request $request
     * @param         $categorySlug
     *
     * @return mixed
     */
    public function view_category(Request $request, $categorySlug)
    {
        return $this->index($request, $categorySlug);
    }

    /**
     * View a single post and (if enabled) its comments
     *
     * @param Request $request
     * @param         $blogPostSlug
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function viewSinglePost(Request $request, $blogPostSlug)
    {
        $posts = PostTranslation::where('slug', $blogPostSlug)->firstOrFail();

        $categories = $posts->post->categories()->with([
            'categoryTranslations' => function ($query) use ($request)
            {
            }
        ])->get();

        return view('blog.single_post', [
            'post' => $posts,
            'comments' => $posts->post->comments()->with('user')->get(),
            'locale' => $request->get('locale'),
            'categories' => $categories,
            'routeWithoutLocale' => $request->get('routeWithoutLocale'),
            'description' => $posts->post->currentTranslation->post_body,
            'contentMode' => $posts->post->content_mode ?? 'rich_text',
            'blocks' => $posts->post->content_blocks ?? [],
        ]);
    }
}
