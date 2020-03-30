<?php


use App\src\Application;
use App\widgets\Pagination;

$domain = Application::app()->getRequest()->getHomeUrl();
?>

<div class="container">
    <h3>Всего записей: <?= $count;?></h3>
    <table class="table">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col"><a href="<?=$domain;?>/index?limit=<?=$limit;?>&offset=<?=$offset;?>&sort=<?=$sort === 'name ASC' ? 'name+DESC' : 'name+ASC';?>">Имя пользователя</a></th>
            <th scope="col"><a href="<?=$domain;?>/index?limit=<?=$limit;?>&offset=<?=$offset;?>&sort=<?=$sort === 'email ASC' ? 'email+DESC' : 'email+ASC';?>">E-mail</a></th>
            <th scope="col">Текст задачи</th>
            <th scope="col"><a href="<?=$domain;?>/index?limit=<?=$limit;?>&offset=<?=$offset;?>&sort=<?=$sort === 'is_closed ASC' ? 'is_closed+DESC' : 'is_closed+ASC';?>">Статус</a></th>
            <th scope="col"></th>
        </tr>
        </thead>
        <tbody>
        <?php $i = 1; foreach($tasks as $task): ?>
        <tr>
            <th scope="row"><?=$offset + $i++ ?></th>
            <td><?=$task->name; ?></td>
            <td><?=$task->email; ?></td>
            <td><?=$task->text; ?></td>
            <?php if(Application::app()->getUser()->isGuest()):?>
                <td><?=$task->is_closed === true ? '<span style="color: green">Выполнено</span>' : '<span style="color: blue">На проверке</span>'; ?></td>
                <td><?=$task->is_edited === true ? 'Изменена администратором' : ''; ?></td>
            <?php endif; ?>
            <?php if(!Application::app()->getUser()->isGuest()):?>
                <td><?=$task->is_closed === true ? '<span style="color: green">Выполнено</span>' : '<a class="btn btn-info" href="' . $domain .'/task-close?id=' . $task->id . '">Выполнить</a>'; ?></td>
                <td><?= '<a class="btn btn-info" href="' . $domain .'/task-update?id=' . $task->id . '">Изменить</a>';?></td>
            <?php endif;?>
        </tr>
        <?php endforeach;?>
        </tbody>
    </table>

    <?= Pagination::run($count, $limit, $offset, $sort); ?>
</div>

