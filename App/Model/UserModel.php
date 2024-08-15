<?php

namespace App\Model;

use PDO;
use PDOException;

class UserModel
{
    public PDO $db;

    public $userId;

    public function __construct($userId)
    {
        $this->userId = $userId;
        $this->db = Database::getConnection();
    }

    public function checkUser(): bool
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM users WHERE user_id = :id");
        $stmt->bindParam(':id', $this->userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    public function createUser(): bool
    {
        $stmt = $this->db->prepare("
            INSERT INTO users (`user_id`, `peremen`) 
            VALUES ({$this->userId}, 0)
        ");
        return $stmt->execute();
    }

    public function updateUser(array $userData): bool
    {
        $fields = [];
        foreach ($userData as $key => $value) {
            $fields[] = "$key = :$key";
        }
        $fieldsStr = implode(', ', $fields);

        $stmt = $this->db->prepare("
            UPDATE users 
            SET $fieldsStr 
            WHERE user_id = :userId
        ");
        $stmt->bindParam(':userId', $this->userId, PDO::PARAM_INT);

        foreach ($userData as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        return $stmt->execute();
    }

    public function getUser()
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE user_id = :id");
        $stmt->bindParam(':id', $this->userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function getAll()
    {
        $stmt = $this->db->prepare("SELECT * FROM users");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getCount()
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM users");
        $stmt->execute();
        return $stmt->fetchColumn();
    }
}