<?php

namespace App\controllers;

use App\src\ControllerAbstract;

/**
 * Class PageNotFoundController
 * Returns a 404-page
 * @package App\controllers
 */
class PageNotFoundController extends ControllerAbstract
{
    /**
     * Running the controller
     */
    public function run() : void
    {
        $this->response->setStatusCode($this->response::STATUS_NOT_FOUND);
        $this->render('page-not-found');
    }
}