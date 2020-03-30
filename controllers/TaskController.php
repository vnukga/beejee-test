<?php


namespace App\controllers;


use App\models\Task;
use App\src\ControllerAbstract;

/**
 * Class TaskController
 *
 * Return list of tasks
 * @package App\controllers
 */
class TaskController extends ControllerAbstract
{
    /**
     * Running the controller
     */
    public function run()
    {
        $limit = $this->request->getParameterFromRequest('limit');
        $offset = $this->request->getParameterFromRequest('offset');
        $task = new Task();
        $tasks = $task->findAll($limit, $offset);
        $this->render('task',['tasks' => $tasks]);
    }
}