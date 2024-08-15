<?php

namespace App\Model;

use PDO;

abstract class BaseModel
{
    protected PDO $db;
    protected $table;

    public function __construct($table = null)
    {
        if ($table) {
            $this->table = $table;
        }
        $this->db = Database::getConnection();
    }

    public function getAll($offset, $noLimit = false)
    {
        if ($noLimit) {
            $sth = $this->db->prepare("SELECT * FROM `{$this->table}`");
        } else {
            $sth = $this->db->prepare("SELECT * FROM `{$this->table}` LIMIT 6 OFFSET ".$offset);
        }
        $sth->execute();
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getItem($id)
    {
        $sth = $this->db->prepare("SELECT * FROM `{$this->table}` WHERE `id` = ?");
        $sth->execute(array($id));
        return $sth->fetch(PDO::FETCH_ASSOC);
    }
}
