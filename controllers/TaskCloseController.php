<?php


namespace App\controllers;

use App\models\Task;
use App\src\ControllerAbstract;

/**
 * Class TaskCloseController
 * Closes the task found by id
 * @package App\controllers
 */
class TaskCloseController extends ControllerAbstract
{
    /**
     * Running the controller
     */
    public function run() : void
    {
        $id = $this->request->get('id');
        if($id){
            $task = (new Task())->findOne(['id' => $id]);
            $task->is_closed = 1;
            $task->save();
            $flash = 'Статус задачи изменён';
            $this->response->setFlash('success', $flash);
        }
        $refererUrl = $this->request->getPreviousUrl();
        $this->response->redirect($refererUrl);
    }
}