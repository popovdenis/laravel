<?php

namespace App\Blog\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Blog\Helpers;
use App\Blog\Middleware\UserCanManageBlogPosts;
use App\Blog\Models\Configuration;
use App\Blog\Models\Language;

/**
 * Class AdminSetupController
 * Handles initial setup for  Blog
 */
class AdminSetupController extends Controller
{
    /**
     * AdminSetupController constructor.
     */
    public function __construct()
    {
        $this->middleware(UserCanManageBlogPosts::class);

        if (!is_array(config("blog"))) {
            throw new \RuntimeException('The config/blog.php does not exist. Publish the vendor files for the Blog package by running the php artisan publish:vendor command');
        }
    }

    /**
     * View all posts
     *
     * @return mixed
     */
    public function setup(Request $request)
    {
        return view("blog_admin::setup.setup");
    }

    public function setup_submit(Request $request){
        if ($request['locale'] == null){
            return redirect( route('blog.admin.setup_submit') );
        }
        $language = new Language();
        $language->active = $request['active'];
        $language->iso_code = $request['iso_code'];
        $language->locale = $request['locale'];
        $language->name = $request['name'];
        $language->date_format = $request['date_format'];

        $language->save();
        if (!Configuration::get('INITIAL_SETUP')){
            Configuration::set('INITIAL_SETUP', true);
            Configuration::set('DEFAULT_LANGUAGE_LOCALE', $request['locale']);
        }

        return redirect( route('blog.admin.index') );
    }
}
