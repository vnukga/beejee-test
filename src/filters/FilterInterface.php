<?php

namespace App\src\filters;

/**
 * Interface for application's filters
 *
 * @package App\src\filters
 */
interface FilterInterface
{
    public function __construct(array $config);

    /**
     * Runs filter
     *
     * @param string $route
     * @return bool
     */
    public function run(string $route) : bool;
}