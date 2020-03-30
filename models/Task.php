<?php


namespace App\models;


use App\src\database\Connection;
use App\src\ModelAbstract;

class Task extends ModelAbstract
{
    public int $id;

    public string $name;

    public string $email;

    public string $text;

    public bool $is_closed = false;

    public bool $is_edited = false;

    public function __construct(Connection $connection = null)
    {
        parent::__construct($connection);
    }

    public function fields(): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'text' => $this->text,
            'is_closed' => $this->is_closed,
            'is_edited' => $this->is_edited,
        ];
    }

    public function getTableName(): string
    {
        return 'tasks';
    }

    public function getClassName(): string
    {
        return self::class;
    }

    public function validateEmail(string $email) : bool
    {
        if(filter_var($email, FILTER_VALIDATE_EMAIL)){
            return true;
        }
        $this->errors = [
            'email' => 'E-mail должен быть валиден!'
        ];
        return false;
    }
}