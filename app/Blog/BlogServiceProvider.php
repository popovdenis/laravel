<?php

namespace App\Blog;

use Illuminate\Support\ServiceProvider;
use App\Blog\FulltextSearch\Commands\Index;
use App\Blog\FulltextSearch\Commands\IndexOne;
use App\Blog\FulltextSearch\Commands\UnindexOne;
use App\Blog\FulltextSearch\ModelObserver;
use App\Blog\FulltextSearch\Search;
use App\Blog\FulltextSearch\SearchInterface;
use App\Blog\Models\PostTranslation;

class BlogServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(SearchInterface::class, Search::class);

        $this->commands([
            Index::class,
            IndexOne::class,
            UnindexOne::class,
        ]);

        $this->loadViewsFrom(base_path('resources/views/blog'), 'blog');
//        $this->loadViewsFrom(base_path('resources/views/blog_admin'), 'blog_admin');

        $this->mergeConfigFrom(__DIR__ . '/Config/blog.php', 'blog');
    }

    public function boot(): void
    {
        if (!config('blog.search.search_enabled')) {
            ModelObserver::disableSyncingFor(PostTranslation::class);
        }

        if (config('blog.include_default_routes', true)) {
            $this->loadRoutesFrom(__DIR__ . '/routes.php');
        }

        $this->publishes([
            __DIR__ . '/../migrations' => database_path('migrations'),
        ], 'blog-migrations');

        $this->publishes([
            __DIR__ . '/css/blog_admin_css.css' => public_path('blog_admin_css.css'),
            __DIR__ . '/css/blog.css' => public_path('blog.css'),
            __DIR__ . '/css/admin-setup.css' => public_path('admin-setup.css'),
            __DIR__ . '/js/blog.js' => public_path('blog.js'),
            __DIR__ . '/Config/blog.php' => config_path('blog.php'),
            __DIR__ . '/views/blog' => resource_path('views/blog'),
            __DIR__ . '/views/blog_admin' => resource_path('views/blog_admin'),
        ], 'blog-assets');
    }
}
