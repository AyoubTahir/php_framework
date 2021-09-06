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
        $req = $request->hash('password')->only(['email','password']);
 
        $auth = $this->users->select()->andWhere($req)->single();

        if(!$auth)
        {
            return $this->url->addMessage('login','invalid data')->redirectTo('/login');
        }
        

        if($request->post('remeber'))
        {
            $this->cookie->set('auth',$this->hash->encode(['auth_user' => $auth]));            
        }

        $this->session->set('auth',$this->hash->encode(['auth_user' => $auth]));
        
        session_regenerate_id();

        return $this->url->redirectTo('/users');

    }

    public function logout()
    {
        if($this->session->has('auth'))
        {
           $this->session->remove('auth'); 
        }
        
        return $this->url->redirectTo('/users');
    }

}