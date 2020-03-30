<?php

use App\src\Application;

$domain = Application::app()->getRequest()->getHomeUrl();
?>

    <br>
<h1>Главная страница</h1>
    <br>
<div class="container">
    <?php include $this->flashPath; ?>
    <?php if (Application::app()->getUser()->isGuest()): ?>
        <p>
            <a class="btn btn-primary" href="<?= $domain ?>/task-create">
                Создать задачу
            </a>
        </p>
    <?php endif; ?>
    <?php
        include __DIR__ . '/task.php';
    ?>
</div>
