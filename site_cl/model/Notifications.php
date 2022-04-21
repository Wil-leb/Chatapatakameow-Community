<?php

namespace App\model;

use App\core\Connect;

class Notifications extends Connect {
    
    protected $_pdo;
    private $_formerNotif;

    public function __construct() {
        $this->_pdo = $this->connection();
        $this->_formerNotif;
    }

//*****A. Notification addition*****//
    public function addNotification(string $id, string $publisherId, string $refId, string $refType) {
        $sql = "SELECT `id`, `ref_type` FROM `notifications` WHERE `publisher_id` = :publisherId AND `ref_id` = :refId
                AND `ref_type` = :refType";

        $query = $this->_pdo->prepare($sql);

        $query->execute([":publisherId" => $publisherId, ":refId" => $refId, ":refType" => $refType]);

        $notifRow = $query->fetch();

        if($notifRow) {
            if($notifRow["ref_type"] == $refType) {
                $query = $this->_pdo->prepare("DELETE FROM `notifications` WHERE `id` = :id");

                $query->execute([':id' => $notifRow['id']]);

                return false;
            }

            $this->_formerNotif = $notifRow;

            if($notifRow["ref_type"] == "album_like") {
                $sql = "UPDATE `notifications` SET `ref_type` = 'album_dislike' WHERE `id` = :id";

                $query = $this->_pdo->prepare($sql);

                $query->execute([":id" => $notifRow["id"]]);

                return true;
            }

            elseif($notifRow["ref_type"] == "album_dislike") {
                $sql = "UPDATE `notifications` SET `ref_type` = 'album_like' WHERE `id` = :id";

                $query = $this->_pdo->prepare($sql);

                $query->execute([":id" => $notifRow["id"]]);

                return true;
            }
        }

        $req = "INSERT INTO `notifications` (`id`, `publisher_id`, `ref_id`, `ref_type`) VALUES (:id, :publisherId, :refId, :refType)";

        $query = $this->_pdo->prepare($req);
        
        $query->execute([":id" => $id, ":publisherId" => $publisherId, ":refId" => $refId, ":refType" => $refType]);

        return true;
    }

//*****END OF THE Notifications MODEL*****//
}