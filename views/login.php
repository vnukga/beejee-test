<?php

use App\src\Application;

$domain = Application::app()->getRequest()->getHomeUrl();
?>

<div class="container">
    <form action="<?=$domain; ?>/login" method="post">
        <div class="row">
            <div class="form-group">
                <label for="inputLogin">Логин </label>
                <input type="text" class="form-control" id="inputLogin" name="login" placeholder="Введите логин">
            </div>
            <div class="form-group">
                <label for="inputPassword">Пароль </label>
                <input type="password" class="form-control" id="exampleInputPassword3" name="password" placeholder="Введите пароль">
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Войти</button>
    </form>
</div>