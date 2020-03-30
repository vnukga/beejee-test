<?php


namespace App\controllers;


use App\models\Task;
use App\src\ControllerAbstract;

class IndexController extends ControllerAbstract
{
    const LIMIT_DEFAULT = 3;

    const OFFSET_DEFAULT = 0;

    const SORT_DEFAULT = 'ID ASC';

    public function run()
    {
        $limit = $this->request->getParameterFromRequest('limit') ?? self::LIMIT_DEFAULT;
        $offset = $this->request->getParameterFromRequest('offset') ?? self::OFFSET_DEFAULT;
        $sort = $this->request->getParameterFromRequest('sort') ?? self::SORT_DEFAULT;
        $task = new Task();
        $count = $task->countAll();
        $tasks = $task->findAll($limit, $offset, [$sort]);
        $this->render('index',[
            'tasks' => $tasks,
            'count' => $count,
            'limit' => $limit,
            'offset' => $offset,
            'sort' => $sort
        ]);
    }
}