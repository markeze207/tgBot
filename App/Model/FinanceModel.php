<?php

namespace App\Model;

use PDO;

class FinanceModel extends BaseModel
{

    public function getAllUnique($offset)
    {
        $sth = $this->db->prepare("SELECT * FROM `all_biz` WHERE uniq = 1 LIMIT 6 OFFSET ".$offset);
        $sth->execute();
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllNumberType($offset)
    {
        $offset = (int)$offset;

        $sql = "
        SELECT DISTINCT name
        FROM all_biz WHERE uniq = 0
        LIMIT 6 OFFSET
    ".$offset;

        $sth = $this->db->prepare($sql);
        $sth->execute();

        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllNumber($name, $offset)
    {
        $sth = $this->db->prepare("SELECT * FROM `all_biz` WHERE name = :name 
                        ORDER BY `number` ASC LIMIT 6 OFFSET ".$offset);
        $sth->bindValue(':name', $name);
        $sth->execute();
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }
}
