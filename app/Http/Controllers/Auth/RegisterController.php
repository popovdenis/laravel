<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    use RegistersUsers;

    /**
     * Here, I replace the method used in the RegistersUsers trait
     * to modify the behaviour and implements my own logic
     *
     * @return string
     */
    protected function redirectTo()
    {
        return route('login');
    }
    
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();
        event(new Registered($user = $this->create($request->all())));
        return $this->registered($request, $user)
            ?: redirect($this->redirectPath());
    }
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }
    
    protected function registered(Request $request, $user)
    {
        \Session::flash('success', trans('auth.register_successful'));
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $validator = Validator::make($data, [
            'firstname' => 'required|max:255',
            'lastname' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);
        $validator->setCustomMessages([
            'firstname.between' => 'Firstname 6 et 30 caractères !',
            'lastname.between' => 'Lastname 6 et 30 caractères !',
            'password.confirmed' => 'Password !',
            'email.unique' => 'Email unique
            <a href="' . route('password.request') . '">faire une demande de réinitialisation</a> de votre mot de passe.',
        ]);
        return $validator;
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $user = User::create([
            'firstname' => $data['firstname'],
            'lastname' => $data['lastname'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
        
        $userData = $user->toArray();
        $userData['fullname'] = $user->getFullname();
    
        \Mail::send('emails.welcome', $userData, function($message) use ($userData)
        {
            $message->from('no-reply@site.com', "Site name");
            $message->subject("Добро пожаловать на сайт!");
            $message->to($userData['email']);
        });
    
        return $user;
    }
}
