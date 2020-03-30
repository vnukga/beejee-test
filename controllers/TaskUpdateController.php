<?php


namespace App\controllers;


use App\models\Task;
use App\src\ControllerAbstract;

class TaskUpdateController extends ControllerAbstract
{
    public function run()
    {
        $id = $this->request->getParameterFromRequest('id');
        if($id){
            $task = (new Task())->findOne(['id' => $id]);
        }
        if($this->request->isPost()) {
            $post = $this->request->post();
            if($task->text !== $post['text']) {
                $task->text = $post['text'];
                $task->is_edited = 1;
                $task->save();
                $flash = 'Текст задачи изменён';
                $this->response->setFlash('success', $flash);
            }
            $this->response->redirect('index');
        } else {
            $this->render('task-update', [
                'task' => $task
            ]);
        }
    }
}