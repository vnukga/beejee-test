<?php


namespace App\src\filters;


interface FilterInterface
{
    public function run(string $route) : bool;
}