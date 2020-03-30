<?php

namespace App\controllers;

use App\src\Application;
use App\src\ControllerAbstract;

/**
 * Logging out an user
 *
 * Class LogoutController
 * @package App\controllers
 */
class LogoutController extends ControllerAbstract
{
    /**
     * Running the controller
     */
    public function run() : void
    {
        $user = Application::app()->getUser();
        $user->logout();
        $refererUrl = $this->request->getPreviousUrl();
        $this->response->redirect($refererUrl);
    }
}