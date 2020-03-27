<?php


namespace App\widgets;

use App\src\WidgetInterface;

class Footer implements WidgetInterface
{

    public static function run(): string
    {
        return <<<HTML
            <div class="footer">
                <div class="navbar-fixed-bottom row-fluid">
                    <div class="navbar-inner">
                        <div class="container">
                            Футер
                        </div>
                    </div>
                </div>
            </div>
HTML;
    }
}