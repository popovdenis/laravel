<?php
/**
 * Created by PhpStorm.
 * User: denispopov
 * Date: 20.04.2018
 * Time: 21:46
 */

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogSuccessfulLoginListener
{
    /**
     * Create the event listener.
     *
     * @param  Request  $request
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Handle the event.
     *
     * @param  Login  $event
     * @return void
     */
    public function handle(Login $event)
    {
        $user = $event->user;

        $comments = $user->newComments();

        $user->new_comments = count($comments);

        $user->save();
    }
}