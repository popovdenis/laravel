<?php
namespace App\Blog\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Blog\FulltextSearch\Search;
use App\Blog\Models\Category;
use App\Blog\Models\CategoryTranslation;
use App\Blog\Models\Language;
use App\Blog\Models\Post;
use App\Blog\Models\PostTranslation;
use Illuminate\Http\Request;

class ReaderController extends Controller
{
    /**
     * Show blog posts
     * If category_slug is set, then only show from that category
     *
     * @param Request $request
     * @param null $category_slug
     * @return \Illuminate\View\View
     */
    public function index(Request $request, $category_slug = null)
    {
        $title = 'Blog Page'; // default title...

        $categoryChain = null;
        $posts = collect();

        if ($category_slug) {
            $category = CategoryTranslation::where('slug', $category_slug)
                ->with('category')
                ->firstOrFail()->category;

            $categoryChain = $category->getAncestorsAndSelf();
            $posts = $category->posts()->where('post_categories.category_id', $category->id)
                ->with(['postTranslations' => function($query) use ($request) {
                    $query->where('lang_id', $request->get('lang_id'));
                }])->get();

            $posts = PostTranslation::join('posts', 'post_translations.post_id', '=', 'posts.id')
                ->where('lang_id', $request->get('lang_id'))
                ->where('is_published', true)
                ->where('posted_at', '<', Carbon::now()->format('Y-m-d H:i:s'))
                ->orderBy('posted_at', 'desc')
                ->whereIn('posts.id', $posts->pluck('id'))
                ->paginate(config('blog.per_page', 10));

            // Set category for view
            \View::share('blog_category', $category);
            $title = 'Posts in ' . $category->category_name . " category";
        } else {
            $posts = PostTranslation::join('posts', 'post_translations.post_id', '=', 'posts.id')
//                ->where('lang_id', $request->get('lang_id'))
                ->where('is_published', true)
                ->where('posted_at', '<', Carbon::now()->format('Y-m-d H:i:s'))
                ->orderBy('posted_at', 'desc')
                ->paginate(config('blog.per_page', 10));
        }

        // Load category hierarchy
        $rootList = Category::roots()->get();
        Category::loadSiblingsWithList($rootList);

        return view('blog.index', [
            'lang_list' => Language::all(['locale', 'name']),
            'locale' => $request->get('locale'),
            'lang_id' => $request->get('lang_id'),
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
        $search_results = $search->run($query);

        \View::share('title', 'Search results for ' . e($query));

        $rootList = Category::roots()->get();
        Category::loadSiblingsWithList($rootList);

        return view('blog::search', [
            'lang_id' => $request->get('lang_id'),
            'locale' => $request->get('locale'),
            'categories' => $rootList,
            'query' => $query,
            'search_results' => $search_results,
            'routeWithoutLocale' => $request->get('routeWithoutLocale'),
        ]);
    }

    /**
     * View all posts in $category_slug category
     *
     * @param Request $request
     * @param $category_slug
     * @return mixed
     */
    public function view_category(Request $request, $category_slug)
    {
        return $this->index($request, $category_slug);
    }

    /**
     * View a single post and (if enabled) its comments
     *
     * @param Request $request
     * @param $blogPostSlug
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function viewSinglePost(Request $request, $blogPostSlug)
    {
        $blog_post = PostTranslation::where('slug', $blogPostSlug)
//            ->where('lang_id', $request->get('lang_id'))
            ->firstOrFail();

        $categories = $blog_post->post->categories()->with(['categoryTranslations' => function ($query) use ($request) {
            $query->where('lang_id', '=', $request->get('lang_id'));
        }])->get();

        return view('blog.single_post', [
            'post' => $blog_post,
            'comments' => $blog_post->post->comments()->with('user')->get(),
            'locale' => $request->get('locale'),
            'categories' => $categories,
            'routeWithoutLocale' => $request->get('routeWithoutLocale'),
        ]);
    }
}
