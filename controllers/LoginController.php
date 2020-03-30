<?php


namespace App\controllers;


use App\src\Application;
use App\src\ControllerAbstract;

/**
 * Class LoginController
 * Logging user in
 * @package App\controllers
 */
class LoginController extends ControllerAbstract
{
    /**
     * Login errors
     * @var array
     */
    private array $errors;

    /**
     * Running the controller
     */
    public function run() : void
    {
        if(!$this->request->isPost()){
            $this->render('login');
        }
        $post = $this->request->post();
        if(!$login = $post['login']) {
            $this->errors['login'] = 'Необходимо заполнить поле!';
        }
        if(!$password = $post['password']) {
            $this->errors['password'] = 'Необходимо заполнить поле!';
        }
        if(isset($this->errors)) {
            $result = ['errors' => true, 'data' => $this->errors];
            $this->sendAjax($result);
            return;
        }
        $user = Application::app()->getUser();
        $user->login($login, $password);
        if($this->request->isAjax()){
            if($errors = $user->getErrors()) {
                $result = ['errors' => true, 'data' => $errors];
            } else {
                $result = ['success' => true];
            }
            $this->sendAjax($result);
        }
    }
}