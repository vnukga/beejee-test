<?php


use App\src\Application;

$domain = Application::app()->getRequest()->getHomeUrl();
?>

<div class="container">
    <h1>Изменение задачи</h1>
    <div class="card card-body">
        <div class="well">
            <form action="<?= $domain;?>/task-update" id="task-update-form" method="post">
                <div class="row">
                    <input type="hidden" name="id" value="<?=$task->id?>">
                    <div class="form-group col-sm">
                        <label for="name">Имя пользователя</label>
                        <input type="text" class="form-control" id="name" name="name" value="<?=$task->name?>" disabled>
                    </div>
                    <div class="form-group  col-sm">
                        <label for="email">E-mail </label>
                        <input type="email" class="form-control" id="email" name="email" value="<?=$task->email?>" disabled>
                    </div>
                </div>
                <div class="form-group">
                    <label for="text">Текст </label>
                    <textarea class="form-control" id="text" name="text"><?=$task->text?></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Обновить</button>
            </form>
        </div>
    </div>
</div>