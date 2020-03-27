<?php

use App\src\Application; ?>

<h1>Главная страница</h1>

<?= $username ?>

<?= Application::app()->getUser()->isGuest(); ?>
