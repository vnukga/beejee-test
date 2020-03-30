<?php


namespace App\controllers;


use App\models\Task;
use App\src\ControllerAbstract;

class TaskCloseController extends ControllerAbstract
{
    public function run()
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