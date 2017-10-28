<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Album;
use App\AlbumImage;
use Folklore\Image\Facades\Image;
use Chumper\Zipper\Zipper;
use Illuminate\Support\Facades\Auth;

/**
 * Class CommentController
 *
 * @package App\Http\Controllers
 */
class CommentController extends BaseController
{
    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        /*
         * Cоставляем массив данных кроме указанных полей формы
         * (т.к. в БД данные поля называются по-другому)
         */
        $data = $request->except('_token', 'comment_photo_ID', 'comment_parent');
        
        //добавляем поля с названиями как в таблице (модели)
        $data['image_id'] = $request->input('comment_image_ID');
        $data['parent_id'] = $request->input('comment_parent');
        
        //устанавливаем статус в зависимости от настройки
        $data['status'] = config('comments.show_immediately');
        
        /*
         * Если активен аутентифицированный пользователь
         * то эти данные берем из таблицы users
         */
        $user = Auth::getUser(); //аутентиф.пользователь
        
        if ($user) {
            $data['user_id'] = $user->id;
            $data['name'] = (!empty($data['name'])) ? $data['name'] : $user->name;
            $data['email'] = (!empty($data['email'])) ? $data['email'] : $user->email;
        }
        
        //Проверка
        $validator = Validator::make(
            $data, [
            'image_id' => 'integer|required',
            'text' => 'required',
            'name' => 'required',
            'email' => 'required|email',
        ]
        );
        
        
        /*
         * Создаем объект для сохранения, передаем ему массив данных
         */
        $comment = new Comment($data);
        
        
        //Ошибки
        if ($validator->fails()) {
            /*
             * Возвращаем ответ в формате json.
             * Метод all() переводит в массив т.к. данный формат работает или
             * с объектами или с массивами
             */
            return \Response::json(['error' => $validator->errors()->all()]);
        }
        
        
        //получаем модель записи к которой принадлежит комментарий
        $post = Photo::find($data['image_id']);
        /*
         * Сохраняем данные в БД
         * Используем связывающий метод comments()
         * для того, чтобы автоматически заполнилось поле post_id
         */
        $post->comments()->save($comment);
        
        
        /*
         * Формируем массив данных для вывода нового комментария с помощью AJAX
         * сразу после его добавления (без перезагрузки)
         */
        $data['id'] = $comment->id;
        $data['hash'] = md5($data['email']);
        $data['status'] = config('comments.show_immediately');
        
        //Вывод шаблона сохраняем в переменную
        $view_comment = view(env('THEME') . '.comments.new_comment')->with('data', $data)->render();
        
        //Возвращаем AJAX вывод шаблона  с данными
        return \Response::json(['success' => true, 'comment' => $view_comment, 'data' => $data]);
    }
}
