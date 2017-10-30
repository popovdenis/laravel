<?php

namespace App\Http\Controllers;

use App\Photo;
use Illuminate\Http\Request;
use Validator;
use Auth;
use App\Comment;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function store(Request $request)
    {
        /*
         * Cоставляем массив данных кроме указанных полей формы
         * (т.к. в БД данные поля называются по-другому)
         */
        $data = $request->except('_token', 'comment_image_ID', 'comment_parent');
        
        //добавляем поля с названиями как в таблице (модели)
        $data['image_id'] = $request->input('comment_image_ID');
        $data['parent_id'] = $request->input('comment_parent');
        
        //устанавливаем статус в зависимости от настройки
        $data['status'] = config('comments.show_immediately');
        
        /*
         * Если активен аутентифицированный пользователь
         * то эти данные берем из таблицы users
         */
        $user = Auth::user(); //аутентиф.пользователь
        
        if ($user) {
            $data['user_id'] = $user->id;
            $data['name'] = $user->getFullname();
            $data['email'] = $user->email;
        }
        
        //Проверка
        $validator = Validator::make(
            $data, [
                'image_id' => 'integer|required',
                'text' => 'required',
            ]
        );
        
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
        $image = Photo::find($data['image_id']);
        
        /*
         * Сохраняем данные в БД
         * Используем связывающий метод comments()
         * для того, чтобы автоматически заполнилось поле post_id
         */
        $commentsObj = $image->comments();
    
        /*
         * Создаем объект для сохранения, передаем ему массив данных
         */
        $comment = new Comment($data);
        $commentsObj->save($comment);
        
        // Update count of new comments for photo's owner
        $image->album()->owner()->incrementNewComments();
        
        /*
         * Формируем массив данных для вывода нового комментария с помощью AJAX
         * сразу после его добавления (без перезагрузки)
         */
        $data['id'] = $comment->id;
        $data['hash'] = md5($data['email']);
        $data['status'] = config('comments.show_immediately');
    
        return redirect()->route('album.show', $image->album()->id)
            ->with('success', 'Comment has been created successfully');
    }
    
    public function getNewComments()
    {
        $newComments = $this->getCurrentUser()->getNewComments();
        $comments = view('comments.comments_preview')->with('comments', $newComments)->render();
        
        return response()->json(['new_comments'=> $comments], 200);
    }
}
