<?php


namespace App\src\filters;


interface FilterInterface
{
    public function __construct(array $config);

    public function run(string $route) : bool;
}