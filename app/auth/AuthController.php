<?php

namespace App\Auth;

use Tahir\Base\Controller;
use App\Auth\Forms\LoginForm;

class AuthController extends Controller
{
    protected $models = [
        'users' => 'users/Users'
    ];

    public function index()
    {
        $loginForm = $this->form->create(LoginForm::class)->form();
        return $this->view->render('auth/views/login',['loginForm'=>$loginForm],__r('login'));
    }

    public function login($request)
    {
        $request->validate([
            'email'     => 'required|email',
            'password'  => 'required'
        ]);

        $req = $request->hash('password')->only(['email','password']);
 
        $auth = $this->users->select()->andWhere($req)->single();

        //$request->post('remeber');

        if(!$auth)
        {
            return $this->url->addMessage('login','invalid data')->redirectTo('/login');
        }

        if($request->post('remeber'))
        {
            $this->session->set('user',$auth);
        }

        return $this->url->redirectTo('/users');

    }

}