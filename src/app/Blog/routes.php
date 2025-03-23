<?php

Route::group(['middleware' => ['web'], 'namespace' => '\App\Blog\Controllers'], function ()
{

    /** The main public facing blog routes - show all posts, view a category, view a single post, also the add comment route */
    Route::group(['prefix' => "/{locale}/" . config('blog.blog_prefix', 'blog')], function ()
    {

        Route::get('/', 'ReaderController@index')
            ->name('blog.index');

        Route::get('/search', 'ReaderController@search')
            ->name('blog.search');

        Route::get('/category{subcategories}', 'ReaderController@view_category')
            ->where('subcategories', '^[a-zA-Z0-9-_\/]+$')->name('blog.view_category');

        Route::get('/{blogPostSlug}',
            'ReaderController@viewSinglePost'
        )
            ->name('blog.single');

        // throttle to a max of 10 attempts in 3 minutes:
        Route::group(['middleware' => 'throttle:10,3'], function ()
        {
            Route::post('save_comment/{blogPostSlug}',
                'CommentWriterController@addNewComment'
            )->name('blog.comments.add_new_comment');
        });
    });

    Route::group(['prefix' => config('blog.blog_prefix', 'blog')], function (){
        Route::get('/', 'ReaderController@index')
            ->name('blognolocale.index');

        Route::get('/search', 'ReaderController@search')
            ->name('blognolocale.search');

        Route::get('/category{subcategories}', 'ReaderController@view_category')
            ->where('subcategories', '^[a-zA-Z0-9-_\/]+$')->name('blognolocale.view_category');

        Route::get('/{blogPostSlug}',
            'ReaderController@viewSinglePost'
        )
            ->name('blognolocale.single');

        // throttle to a max of 10 attempts in 3 minutes:
        Route::group(['middleware' => 'throttle:10,3'], function ()
        {
            Route::post('save_comment/{blogPostSlug}',
                'CommentWriterController@addNewComment'
            )
                ->name('blognolocale.comments.add_new_comment');
        });
    });

    /* Admin backend routes - CRUD for posts, categories, and approving/deleting submitted comments */
    Route::group(['prefix' => config('blog.admin_prefix', 'blog_admin')], function ()
    {

        Route::get('/search',
            'AdminController@searchBlog'
        )
            ->name('blog.admin.searchblog');

        Route::get('/setup', 'AdminSetupController@setup')
            ->name('blog.admin.setup');

        Route::post('/setup-submit', 'AdminSetupController@setup_submit')
            ->name('blog.admin.setup_submit');

        Route::get('/', 'AdminController@index')
            ->name('blog.admin.index');

        Route::get('/add_post',
            'AdminController@create_post'
        )
            ->name('blog.admin.create_post');


        Route::post('/add_post',
            'AdminController@store_post'
        )
            ->name('blog.admin.store_post');

        Route::post('/add_post_toggle',
            'AdminController@store_post_toggle'
        )
            ->name('blog.admin.store_post_toggle');

        Route::get('/edit_post/{blogPostId}',
            'AdminController@edit_post'
        )
            ->name('blog.admin.edit_post');

        Route::post('/edit_post_toggle/{blogPostId}',
            'AdminController@edit_post_toggle'
        )
            ->name('blog.admin.edit_post_toggle');

        Route::post('/edit_post/{blogPostId}',
            'AdminController@update_post'
        )
            ->name('blog.admin.update_post');

        //Removes post's photo
        Route::get('/remove_photo/{slug}/{lang_id}',
            'AdminController@remove_photo'
        )
            ->name('blog.admin.remove_photo');

        Route::group(['prefix' => "image_uploads",], function ()
        {

            Route::get("/", "ImageUploadController@index")->name("blog.admin.images.all");

            Route::get("/upload", "ImageUploadController@create")->name("blog.admin.images.upload");
            Route::post("/upload", "ImageUploadController@store")->name("blog.admin.images.store");
        });

        Route::delete('/delete_post/{blogPostId}',
            'AdminController@destroy_post'
        )
            ->name('blog.admin.destroy_post');

        Route::group(['prefix' => 'comments',], function ()
        {

            Route::get('/',
                'CommentsAdminController@index'
            )
                ->name('blog.admin.comments.index');

            Route::patch('/{commentId}',
                'CommentsAdminController@approve'
            )
                ->name('blog.admin.comments.approve');
            Route::delete('/{commentId}',
                'CommentsAdminController@destroy'
            )
                ->name('blog.admin.comments.delete');
        });

        Route::group(['prefix' => 'categories'], function ()
        {

            Route::get('/',
                'CategoryAdminController@index'
            )
                ->name('blog.admin.categories.index');

            Route::get('/add_category',
                'CategoryAdminController@create_category'
            )
                ->name('blog.admin.categories.create_category');
            Route::post('/store_category',
                'CategoryAdminController@store_category'
            )
                ->name('blog.admin.categories.store_category');

            Route::get('/edit_category/{categoryId}',
                'CategoryAdminController@edit_category'
            )
                ->name('blog.admin.categories.edit_category');

            Route::patch('/edit_category/{categoryId}',
                'CategoryAdminController@update_category'
            )
                ->name('blog.admin.categories.update_category');

            Route::delete('/delete_category/{categoryId}',
                'CategoryAdminController@destroy_category'
            )
                ->name('blog.admin.categories.destroy_category');
        });

        Route::group(['prefix' => 'languages'], function ()
        {

            Route::get('/',
                'LanguageAdminController@index'
            )
                ->name('blog.admin.languages.index');

            Route::get('/add_language',
                'LanguageAdminController@create_language'
            )
                ->name('blog.admin.languages.create_language');
            Route::post('/add_language',
                'LanguageAdminController@store_language'
            )
                ->name('blog.admin.languages.store_language');

            Route::delete('/delete_language/{languageId}',
                'LanguageAdminController@destroy_language'
            )
                ->name('blog.admin.languages.destroy_language');

            Route::post('/toggle_language/{languageId}',
                'LanguageAdminController@toggle_language'
            )
                ->name('blog.admin.languages.toggle_language');
        });
    });
});

