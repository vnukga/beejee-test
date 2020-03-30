<?php


namespace App\controllers;


use App\src\ControllerAbstract;

class PageNotFoundController extends ControllerAbstract
{
    public function run()
    {
        $this->response->setStatusCode($this->response::STATUS_NOT_FOUND);
        $this->render('page-not-found');
    }
}