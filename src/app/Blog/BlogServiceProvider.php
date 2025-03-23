<?php
namespace App\Blog;

use App\Blog\Models\PostTranslation;
use Illuminate\Support\ServiceProvider;
use App\Blog\FulltextSearch\Commands\Index;
use App\Blog\FulltextSearch\Commands\IndexOne;
use App\Blog\FulltextSearch\Commands\UnindexOne;
use App\Blog\FulltextSearch\ModelObserver;
use App\Blog\FulltextSearch\Search;
use App\Blog\FulltextSearch\SearchInterface;

class BlogServiceProvider extends ServiceProvider
{
    protected $commands = [
        Index::class,
        IndexOne::class,
        UnindexOne::class,
    ];

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        if (config("blog.search.search_enabled") == false) {
            // if search is disabled, don't allow it to sync.
            ModelObserver::disableSyncingFor(PostTranslation::class);
        }

        if (config("blog.include_default_routes", true)) {
            include(__DIR__ . "/routes.php");
        }

        foreach ([
            '2025_03_20_203019_create_categories_table.php',
            '2025_03_20_203020_create_category_translations_table.php',
            '2025_03_20_203021_create_posts_table.php',
            '2025_03_20_203022_create_post_translations_table.php',
            '2025_03_20_203023_create_comments_table.php',
            '2025_03_20_203024_create_uploaded_photos_table.php',
            '2025_03_20_203018_create_languages_table.php',
            '2025_03_20_203025_create_configurations_table.php',
            '2025_03_20_203017_create_laravel_fulltext_table.php'
        ] as $file) {
            $this->publishes([
                __DIR__ . '/../migrations/' . $file => database_path('migrations/' . $file)
            ]);
        }

        $this->publishes([
            __DIR__ . '/Views/blog' => base_path('resources/views/vendor/blog'),
            __DIR__ . '/Config/blog.php' => config_path('blog.php'),
            __DIR__ . '/css/blog_admin_css.css' => public_path('blog_admin_css.css'),
            __DIR__ . '/css/binshops-blog.css' => public_path('binshops-blog.css'),
            __DIR__ . '/css/admin-setup.css' => public_path('admin-setup.css'),
            __DIR__ . '/js/binshops-blog.js' => public_path('binshops-blog.js'),
        ]);
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            SearchInterface::class,
            Search::class
        );
        $this->loadViewsFrom(__DIR__ . "/Views/blog_admin", 'blog_admin');
        $this->loadViewsFrom(__DIR__ . "/Views/blog", 'blog');
    }
}
