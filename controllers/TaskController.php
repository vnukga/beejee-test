<?php


namespace App\controllers;


use App\models\Task;
use App\src\ControllerAbstract;

class TaskController extends ControllerAbstract
{
    public function run()
    {
        $limit = $this->request->getParameterFromRequest('limit');
        $offset = $this->request->getParameterFromRequest('offset');
        $task = new Task();
        $tasks = $task->findAll($limit, $offset);
        $this->render('task',['tasks' => $tasks]);
    }
}