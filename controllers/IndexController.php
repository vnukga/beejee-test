<?php


namespace App\controllers;


use App\src\ControllerAbstract;

class IndexController extends ControllerAbstract
{
    public function run()
    {
        $params = [
            'username' => "John Doe"
        ];
        $this->render('index', $params);
    }
}