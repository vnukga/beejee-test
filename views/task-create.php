<?php


use App\src\Application;

$domain = Application::app()->getRequest()->getHomeUrl();
?>

<div class="container">
    <h1>Создание задачи</h1>
        <div class="card card-body">
            <div class="well">
                <form id="task-create-form">
                        <div class="row">
                            <div class="form-group col-sm">
                                <label for="name">Имя пользователя</label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Введите имя пользователя">
                            </div>
                            <div class="form-group  col-sm">
                                <label for="email">E-mail </label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="Введите e-mail">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="text">Текст </label>
                            <textarea class="form-control" id="text" name="text" placeholder="Введите текст задачи"></textarea>
                        </div>
                    <button type="submit" class="btn btn-primary">Создать</button>
                </form>
            </div>
        </div>
</div>