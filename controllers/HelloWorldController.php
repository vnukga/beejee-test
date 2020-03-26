<?php


namespace App\controllers;

use App\src\ControllerAbstract;

class HelloWorldController extends ControllerAbstract
{
    public function run(){
        $this->response->setContent("Hello world!");
        $this->response->send();
    }
}