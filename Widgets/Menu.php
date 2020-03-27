<?php

namespace App\widgets;

use App\src\Application;
use App\src\WidgetInterface;

class Menu implements WidgetInterface
{
    public static function run(string $title = null): string
    {
        $homeUrl = Application::app()->getRequest()->getHomeUrl();
        $username = Application::app()->getUser()->getUsername();
        $items = [];
        if(Application::app()->getUser()->isGuest()){
            $items[] = [
                'url' => '/login',
                'title' => 'Войти'
            ];
        } else {
            $items[] = [
                'url' => '/logout',
                'title' => 'Выйти(' . $username . ')'
            ];
        }

        $menu = '';

        foreach ($items as $item) {
            $menu .= '<li class="nav-item"><a class="nav-link" href="' . $homeUrl . $item['url'] . '">' . $item['title'] . '</a></li>';
        }

        return <<<HTML
        <div class="navbar navbar-expand-lg navbar-light bg-light">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item active">
                        <h1>$title</h1>
                    </li>
                </ul>
                <div class="text-right">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item active">
                            <a class="nav-link" href="$homeUrl">Домой<span class="sr-only">(current)</span></a>
                        </li>
                        $menu
                    </ul>                    
                </div>
<!--                    <li class="nav-item dropdown">-->
<!--                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">-->
<!--                      Dropdown-->
<!--                    </a>-->
<!--                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">-->
<!--                      <a class="dropdown-item" href="#">Action</a>-->
<!--                      <a class="dropdown-item" href="#">Another action</a>-->
<!--                      <div class="dropdown-divider"></div>-->
<!--                      <a class="dropdown-item" href="#">Something else here</a>-->
<!--                    </div>-->
<!--                    </li>-->
<!--                    <li class="nav-item">-->
<!--                        <a class="nav-link disabled" href="#">Disabled</a>-->
<!--                    </li>-->
        </div>
HTML;
    }
}