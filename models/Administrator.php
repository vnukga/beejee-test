<?php


namespace App\models;

use App\src\Application;
use App\src\ModelAbstract;
use App\src\UserInterface;

class Administrator extends ModelAbstract implements UserInterface
{
    public $login;

    private $password_hash;

    private $auth_key;

    private $isGuest = true;

    public function fields(): array
    {
        return [
            'login' => $this->login,
            'password_hash' => $this->password_hash
        ];
    }

    public function getTableName(): string
    {
        return 'administrators';
    }

    public function class(): string
    {
        return self::class;
    }

    public function create(string $login, string $password)
    {
        $this->login = $login;
        $this->setPassword($password);
        $this->insert();
        echo 'Администратор с логином ' . $login . ' успешно создан!';
    }

    private function setPassword(string $password)
    {
        $this->password_hash = password_hash($password, PASSWORD_DEFAULT);
    }

    public function verifyPassword(string $password) : bool
    {
        return password_verify($password, $this->password_hash);
    }

    public function login(string $login, string $password): void
    {
        $administrator = $this->findOne(['login' => $login]);
        if(!$administrator) {
            return;
        }
        if ($administrator->verifyPassword($password)) {
            $this->isGuest = false;
            Application::app()->getRequest()->session()->startUserSession($administrator->login);
        }
    }

    public function logout() : void
    {
        $this->isGuest = true;
        Application::app()->getRequest()->session()->finishUserSession();
    }

    public function isGuest() : bool
    {
        if(Application::app()->getRequest()->session()->getUserSession()){
            $this->isGuest = false;
        }
        return $this->isGuest;
    }

    public function setIsGuest(bool $isGuest) : void
    {
        $this->isGuest = $isGuest;
    }

    public function getUsername()
    {
        return $this->login;
    }
}