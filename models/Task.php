<?php


namespace App\models;

use App\src\database\Connection;
use App\src\ModelAbstract;

/**
 * Class Task
 * Task entity model
 * 
 * @package App\models
 */
class Task extends ModelAbstract
{
    /**
     * id of entity
     * @var int 
     */
    public int $id;

    /**
     * Task's author's name
     * 
     * @var string 
     */
    public string $name;

    /**
     * Task's author's email
     * 
     * @var string 
     */
    public string $email;

    /**
     * Task's text
     * 
     * @var string 
     */
    public string $text;

    /**
     * If task is closed
     * 
     * @var bool 
     */
    public bool $is_closed = false;

    /**
     * If task was edited by administrator
     * 
     * @var bool 
     */
    public bool $is_edited = false;

    /**
     * Task constructor.
     * @param Connection|null $connection
     */
    public function __construct(Connection $connection = null)
    {
        parent::__construct($connection);
    }

    /**
     * Returns model's fields
     * 
     * @return array
     */
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

    /**
     * Returns model's table name
     * 
     * @return string
     */
    public function getTableName(): string
    {
        return 'tasks';
    }

    /**
     * Returns self classname
     * 
     * @return string
     */
    public function getClassName(): string
    {
        return self::class;
    }

    /**
     * Validates author's email
     * 
     * @param string $email
     * @return bool
     */
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