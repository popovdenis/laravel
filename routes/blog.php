<?php
use Illuminate\Support\Facades\Route;
use App\Blog\Controllers\ReaderController;
use App\Blog\Controllers\CommentWriterController;
use App\Blog\Controllers\AdminController;
use App\Blog\Controllers\AdminSetupController;
use App\Blog\Controllers\ImageUploadController;
use App\Blog\Controllers\CommentsAdminController;
use App\Blog\Controllers\CategoryAdminController;
use App\Blog\Controllers\LanguageAdminController;

Route::middleware(['web'])->group(function () {
    Route::prefix('blog')->group(function () {
        Route::get('/', [ReaderController::class, 'index'])->name('blog');
        Route::get('/search', [ReaderController::class, 'search'])->name('blog.search');

        Route::get('/category/{subcategories}', [ReaderController::class, 'view_category'])
            ->where('subcategories', '^[a-zA-Z0-9-_\/]+$')
            ->name('blog.view_category');

        Route::get('/{blogPostSlug}', [ReaderController::class, 'viewSinglePost'])->name('blog.single');

        Route::middleware('throttle:10,3')->group(function () {
            Route::post('save_comment/{blogPostSlug}', [CommentWriterController::class, 'addNewComment'])
                ->name('blog.comments.add_new_comment');
        });
    });

    Route::prefix(config('blog.admin_prefix', 'blog_admin'))->group(function () {
        Route::get('/search', [AdminController::class, 'searchBlog'])->name('blog.admin.searchblog');
        Route::get('/setup', [AdminSetupController::class, 'setup'])->name('blog.admin.setup');
        Route::post('/setup-submit', [AdminSetupController::class, 'setup_submit'])->name('blog.admin.setup_submit');
        Route::get('/', [AdminController::class, 'index'])->name('blog.admin.index');
        Route::get('/add_post', [AdminController::class, 'create_post'])->name('blog.admin.create_post');
        Route::post('/add_post', [AdminController::class, 'store_post'])->name('blog.admin.store_post');
        Route::post('/add_post_toggle', [AdminController::class, 'store_post_toggle'])->name('blog.admin.store_post_toggle');
        Route::get('/edit_post/{blogPostId}', [AdminController::class, 'edit_post'])->name('blog.admin.edit_post');
        Route::post('/edit_post_toggle/{blogPostId}', [AdminController::class, 'edit_post_toggle'])->name('blog.admin.edit_post_toggle');
        Route::post('/edit_post/{blogPostId}', [AdminController::class, 'update_post'])->name('blog.admin.update_post');
        Route::get('/remove_photo/{slug}', [AdminController::class, 'remove_photo'])->name('blog.admin.remove_photo');

        Route::prefix("image_uploads")->group(function () {
            Route::get("/", [ImageUploadController::class, 'index'])->name("blog.admin.images.all");
            Route::get("/upload", [ImageUploadController::class, 'create'])->name("blog.admin.images.upload");
            Route::post("/upload", [ImageUploadController::class, 'store'])->name("blog.admin.images.store");
        });

        Route::delete('/delete_post/{blogPostId}', [AdminController::class, 'destroy_post'])->name('blog.admin.destroy_post');

        Route::prefix('comments')->group(function () {
            Route::get('/', [CommentsAdminController::class, 'index'])->name('blog.admin.comments.index');
            Route::patch('/{commentId}', [CommentsAdminController::class, 'approve'])->name('blog.admin.comments.approve');
            Route::delete('/{commentId}', [CommentsAdminController::class, 'destroy'])->name('blog.admin.comments.delete');
        });

        Route::prefix('categories')->group(function () {
            Route::get('/', [CategoryAdminController::class, 'index'])->name('blog.admin.categories.index');
            Route::get('/add_category', [CategoryAdminController::class, 'create_category'])->name('blog.admin.categories.create_category');
            Route::post('/store_category', [CategoryAdminController::class, 'store_category'])->name('blog.admin.categories.store_category');
            Route::get('/edit_category/{categoryId}', [CategoryAdminController::class, 'edit_category'])->name('blog.admin.categories.edit_category');
            Route::patch('/edit_category/{categoryId}', [CategoryAdminController::class, 'update_category'])->name('blog.admin.categories.update_category');
            Route::delete('/delete_category/{categoryId}', [CategoryAdminController::class, 'destroy_category'])->name('blog.admin.categories.destroy_category');
        });

        Route::prefix('languages')->group(function () {
            Route::get('/', [LanguageAdminController::class, 'index'])->name('blog.admin.languages.index');
            Route::get('/add_language', [LanguageAdminController::class, 'create_language'])->name('blog.admin.languages.create_language');
            Route::post('/add_language', [LanguageAdminController::class, 'store_language'])->name('blog.admin.languages.store_language');
            Route::delete('/delete_language/{languageId}', [LanguageAdminController::class, 'destroy_language'])->name('blog.admin.languages.destroy_language');
            Route::post('/toggle_language/{languageId}', [LanguageAdminController::class, 'toggle_language'])->name('blog.admin.languages.toggle_language');
        });
    });
});
