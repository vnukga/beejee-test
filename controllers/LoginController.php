<?php


namespace App\controllers;


use App\src\Application;
use App\src\ControllerAbstract;

class LoginController extends ControllerAbstract
{
    public function run()
    {
        if(!$this->request->isPost()){
            $this->render('login');
        }
        $post = $this->request->post();
        $login = $post['login'];
        $password = $post['password'];
        $user = Application::app()->getUser();
        $user->login($login, $password);
        $this->response->redirect('/index');
    }
}