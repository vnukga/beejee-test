<?php


namespace App\controllers;

use App\src\Application;
use App\src\ControllerAbstract;

class LogoutController extends ControllerAbstract
{
    public function run()
    {
        $user = Application::app()->getUser();
        $user->logout();
        $refererUrl = $this->request->getPreviousUrl();
        $this->response->redirect($refererUrl);
    }
}