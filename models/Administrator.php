<?php


namespace App\models;

use App\src\ModelAbstract;

class Administrator extends ModelAbstract
{
    public $login;

    private $password_hash;


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

    public function create(string $login, string $password)
    {
        $this->login = $login;
        $this->setPassword($password);
        $this->insert();
    }

    private function setPassword(string $password)
    {
        $this->password_hash = password_hash($password, PASSWORD_DEFAULT);
    }

    public function verifyPassword(string $password) : bool
    {
        return password_verify($password, $this->password_hash);
    }
}