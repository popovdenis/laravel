<?php


namespace App\Blog\Controllers;

use App\Blog\Helpers;
use App\Blog\Middleware\LoadLanguage;
use App\Blog\Middleware\UserCanManageBlogPosts;
use App\Blog\Models\Configuration;
use App\Blog\Models\Language;
use Illuminate\Http\Request;
use Modules\Base\Http\Controllers\Controller;

class LanguageAdminController extends Controller
{
    /**
     * LanguageAdminController constructor.
     */
    public function __construct()
    {
        $this->middleware(UserCanManageBlogPosts::class);
        $this->middleware(LoadLanguage::class);

    }

    public function index(){
        $languageList = Language::all();
        return view("blog_admin::languages.index",[
            'language_list' => $languageList
        ]);
    }

    public function create_language(){
        return view("blog_admin::languages.add_language");
    }

    public function store_language(Request $request){
        if ($request['locale'] == null){
            Helpers::flash_message("Select a language!");
            return view("blog_admin::languages.add_language");
        }
        $language = new Language();
        $language->active = $request['active'];
        $language->iso_code = $request['iso_code'];
        $language->locale = $request['locale'];
        $language->name = $request['name'];
        $language->date_format = $request['date_format'];
        $language->rtl = $request['rtl'];

        $language->save();

        Helpers::flash_message("Language: " . $language->name . " has been added.");
        return redirect( route('blog.admin.languages.index') );
    }

    public function destroy_language(Request $request, $languageId){
        $lang = Language::where('locale', Configuration::get('DEFAULT_LANGUAGE_LOCALE'))->first();
        if ($languageId == $lang->id){
            Helpers::flash_message("The default language can not be deleted!");
            return redirect( route('blog.admin.languages.index') );
        }

        try {
            $language = Language::findOrFail($languageId);
            //todo
//        event(new CategoryWillBeDeleted($category));
            $language->delete();
            Helpers::flash_message("The language is successfully deleted!");
            return redirect( route('blog.admin.languages.index') );
        } catch (\Illuminate\Database\QueryException $e) {
            Helpers::flash_message("You can not delete this language, because it's used in posts or categoies.");
            return redirect( route('blog.admin.languages.index') );
        }
    }

    public function toggle_language(Request $request, $languageId){
        $language = Language::findOrFail($languageId);
        if ($language->active == 1){
            $language->active = 0;
        }else if ($language->active == 0){
            $language->active = 1;
        }

        $language->save();
        //todo
        //event

        Helpers::flash_message("Language: " . $language->name . " has been disabled.");
        return redirect( route('blog.admin.languages.index') );
    }
}
