<?php


namespace App\controllers;


use App\src\ControllerAbstract;

class ForbiddenController extends ControllerAbstract
{
    public function run()
    {
        $this->response->setStatusCode($this->response::STATUS_FORBIDDEN);
        $this->render('forbidden');
    }
}