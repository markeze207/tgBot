<?php

namespace App\Controller;

use App\Model\UserModel;

class UserController
{
    private UserModel $userModel;

    public function __construct($userId)
    {
        $this->userModel = new UserModel($userId);
    }

    public function checkUser(): bool
    {
        return $this->userModel->checkUser();
    }

    public function createUser(): bool
    {
        return $this->userModel->createUser();
    }

    public function getUser()
    {
        return $this->userModel->getUser();
    }

    public function updateUser($data): bool
    {
        return $this->userModel->updateUser($data);
    }

    public function getAll()
    {
        return $this->userModel->getAll();
    }

    public function getCount()
    {
        return $this->userModel->getCount();
    }
}