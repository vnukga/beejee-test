<?php


namespace App\src;


use App\src\http\Response;

abstract class ControllerAbstract
{
    protected $id;

    protected Response $response;

    public function __construct(string $id)
    {
        $this->id = $id;
        $this->response = new Response();
    }

    public abstract function run();

    public function getId()
    {
        return $this->id;
    }
}