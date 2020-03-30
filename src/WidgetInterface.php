<?php


namespace App\src;

/**
 * Interface for html-widgets
 *
 * @package App\src
 */
interface WidgetInterface
{
    /**
     * Runs widget
     *
     * @return string
     */
    public static function run() : string;
}