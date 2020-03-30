<?php


namespace App\models;

use App\src\Application;
use App\src\ModelAbstract;
use App\src\UserInterface;

/**
 * Class Administrator
 * Administrator entity model
 * @package App\models
 */
class Administrator extends ModelAbstract implements UserInterface
{
    /**
     * Administrator's login
     * @var string
     */
    public string $login;

    /**
     * Password's hash for administrator's authentication
     * @var string
     */
    private string $password_hash;

    /**
     * If site's user is guest
     * @var bool
     */
    private $isGuest = true;

    /**
     * Returns model's fields
     *
     * @return array
     */
    public function fields(): array
    {
        return [
            'login' => $this->login,
            'password_hash' => $this->password_hash
        ];
    }

    /**
     * Returns the name of administrators table
     *
     * @return string
     */
    public function getTableName(): string
    {
        return 'administrators';
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
     * Creates administrator's entity
     *
     * @param string $login
     * @param string $password
     */
    public function create(string $login, string $password)
    {
        $this->login = $login;
        $this->setPassword($password);
        $this->insert();
        echo 'Администратор с логином ' . $login . ' успешно создан!';
    }

    /**
     * Sets password for administrator
     *
     * @param string $password
     */
    private function setPassword(string $password)
    {
        $this->password_hash = password_hash($password, PASSWORD_DEFAULT);
    }

    /**
     * Verifies password from login form
     *
     * @param string $password
     * @return bool
     */
    public function verifyPassword(string $password) : bool
    {
        return password_verify($password, $this->password_hash);
    }

    /**
     * Logs user in
     *
     * @param string $login
     * @param string $password
     */
    public function login(string $login, string $password): void
    {
        $administrator = $this->findOne(['login' => $login]);
        if(!$administrator) {
            $this->setErrors();
            return;
        }
        if ($administrator->verifyPassword($password)) {
            $this->isGuest = false;
            Application::app()->getRequest()->session()->startUserSession($administrator->login);
        } else {
            $this->setErrors();
        }
    }

    /**
     * Logs current user out
     */
    public function logout() : void
    {
        $this->isGuest = true;
        Application::app()->getRequest()->session()->finishUserSession();
    }

    /**
     * Returns if user is guest
     *
     * @return bool
     */
    public function isGuest() : bool
    {
        if(Application::app()->getRequest()->session()->getUserSession()){
            $this->isGuest = false;
        }
        return $this->isGuest;
    }

    /**
     * Sets $isGuest value
     *
     * @param bool $isGuest
     */
    public function setIsGuest(bool $isGuest) : void
    {
        $this->isGuest = $isGuest;
    }

    /**
     * Returns username of current user
     *
     * @return string|null
     */
    public function getUsername() : ?string
    {
        return $this->login ?? null;
    }

    /**
     * Sets validation errors
     */
    private function setErrors() : void
    {
        $this->errors = [
            'login' => 'Логин или пароль введены неверно!',
            'password' => ''
        ];
    }
}