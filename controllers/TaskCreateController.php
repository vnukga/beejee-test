<?php


namespace App\controllers;

use App\models\Task;
use App\src\ControllerAbstract;

/**
 * Class TaskCreateController
 * Creates new task
 *
 * @package App\controllers
 */
class TaskCreateController extends ControllerAbstract
{
    /**
     * Creation form error's
     *
     * @var array
     */
    private array $errors;

    /**
     * Running the controller
     */
    public function run() : void
    {
        if(!$this->request->isPost()){
            $this->render('task-create');
        }
        $post = $this->request->post();

        if(!$password = $post['name']) {
            $this->errors['name'] = 'Необходимо заполнить поле!';
        }
        if(!$password = $post['email']) {
            $this->errors['email'] = 'Необходимо заполнить поле!';
        }
        if(!$login = $post['text']) {
            $this->errors['text'] = 'Необходимо заполнить поле!';
        }
        if(isset($this->errors)) {
            $result = ['errors' => true, 'data' => $this->errors];
            $this->sendAjax($result);
            return;
        }

        if($this->request->isAjax()){
            $post = $this->request->post();
            $model = new Task();
            $model->load($post);
            $model->insert();

            if($errors = $model->getErrors()) {
                $result = ['errors' => true, 'data' => $errors];
            } else {
                $flash = 'Задача успешно создана!';
                $this->response->setFlash('success', $flash);
                $result = ['success' => true];
            }
            $this->sendAjax($result);
        }
    }
}