<?php


namespace App\controllers;

use App\src\ControllerAbstract;

/**
 * Class ForbiddenController
 * returns 403-page
 * @package App\controllers
 */
class ForbiddenController extends ControllerAbstract
{
    /**
     * Running the controller
     */
    public function run() : void
    {
        $this->response->setStatusCode($this->response::STATUS_FORBIDDEN);
        $this->render('forbidden');
    }
}