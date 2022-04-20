<?php

namespace App\model;

use App\core\Connect;

class Notifications extends Connect {
    
    protected $_pdo;

    public function __construct() {
        $this->_pdo = $this->connection();
    }

//*****A. Notification addition*****//
    public function addNotification(string $id, string $publisherId, string $refId, string $refType) {
        $sql = "INSERT INTO `notifications` (`id`, `publisher_id`, `ref_id`, `ref_type`)
                VALUES (:id, :publisherId, :refId, :refType)";

        $query = $this->_pdo->prepare($sql);
        
        $query->execute([":id" => $id, ":publisherId" => $publisherId, ":refId" => $refId, ":refType" => $refType]);

        return;
    }

//*****C. Notification deletion*****//
    public function deleteNotification(string $notifId) {
        $sql = "DELETE FROM `notifications` WHERE `id` = :notifId";

        $query = $this->_pdo->prepare($sql);
        
        $query->execute([":notifId" => $notifId]);
    }

//*****END OF THE Notifications MODEL*****//
}