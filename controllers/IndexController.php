<?php


namespace App\controllers;

use App\models\Task;
use App\src\ControllerAbstract;

/**
 * Controller for index page
 *
 * Class IndexController
 * @package App\controllers
 */
class IndexController extends ControllerAbstract
{
    /**
     * Rows limit default value
     */
    const LIMIT_DEFAULT = 3;

    /**
     * Rows offset default value
     */
    const OFFSET_DEFAULT = 0;

    /**
     * Default sorting value
     */
    const SORT_DEFAULT = 'ID ASC';

    /**
     * Running the controller
     */
    public function run() : void
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