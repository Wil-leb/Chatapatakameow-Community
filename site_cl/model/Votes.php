<?php

namespace App\model;

use App\core\Connect;

class Votes extends Connect {
    
    protected $_pdo;
    private $_formerVote;

    public function __construct() {
        $this->_pdo = $this->connection();
        $this->_formerVote;
    }

//*****A. Finding the existing votes of a specific element*****//
    private function recordExists(string $refId, string $category) {
        $sql = $this->_pdo->prepare("SELECT `id` FROM $category WHERE `id` = :id");
        
        $sql->execute([":id" => $refId]);

        if($sql->rowCount() == 0) {
            die("Impossible de voter pour un élément qui n'existe pas !");
        }
    }

//*****B. Vote addition*****//
    private function addVote(string $id, string $publisherId, string $refId, string $category, string $userIp, int $vote) {
        $this->recordExists($refId, $category);

        $sql = "SELECT `id`, `vote` FROM `votes` WHERE `publisher_id` = :publisherId AND `ref_id` = :refId AND `category` = :category
                AND `vote_ip` = :userIp";

        $query = $this->_pdo->prepare($sql);

        $query->execute([":publisherId" => $publisherId, ":refId" => $refId, ":category" => $category, ":userIp" => $userIp]);

        $voteRow = $query->fetch();

        if($voteRow) {
            if($voteRow["vote"] == $vote) {
                $query = $this->_pdo->prepare("DELETE FROM `votes` WHERE `id` = :id");

                $query->execute([':id' => $voteRow['id']]);

                return false;
            }

            $this->_formerVote = $voteRow;

            $sql = "UPDATE `votes` SET `vote` = :vote WHERE `id` = :id";

            $query = $this->_pdo->prepare($sql);

            $query->execute([":id" => $voteRow["id"], ":vote" => $vote]);

            return true;
        }

        $req = "INSERT INTO `votes` (`id`, `publisher_id`, `ref_id`, `category`, `vote_ip`, `vote`)
                VALUES (:id, :publisherId, :refId, :category, :userIp, :vote)";

        $query = $this->_pdo->prepare($req);
        
        $query->execute([
                        ":id" => $id,
                        ":publisherId" => $publisherId,
                        ":refId" => $refId,
                        ":category" => $category,
                        ":userIp" => $userIp,
                        ":vote" => $vote
                        ]);

        return true;
    }

//*****C. Like update*****//
    public function like(string $id, string $publisherId, string $refId, string $category, string $userIp) {
        if($this->addVote($id, $publisherId, $refId, $category, $userIp, 1)) {
            $sqlPart = "";

            if($this->_formerVote) {
                $sqlPart = ", `dislikes` = `dislikes` - 1";
            }

            $que = "UPDATE $category SET `likes` = `likes` + 1 $sqlPart WHERE `id` = :refId";

            $query = $this->_pdo->prepare($que);

            $query->execute([":refId" => $refId]);

            return true;
        }

        else {
            $query = $this->_pdo->prepare("UPDATE $category SET `likes` = `likes` - 1 WHERE `id` = :refId");
            $query->execute([":refId" => $refId]);
        }

        return false;
    }

//*****D. Dislike update*****//
    public function dislike(string $id, string $publisherId, string $refId, string $category, string $userIp) {
        if($this->addVote($id, $publisherId, $refId, $category, $userIp, -1)) {
            $sqlPart = "";

            if($this->_formerVote) {
                $sqlPart = ", `likes` = `likes` - 1";
            }

            $sql = "UPDATE $category SET `dislikes` = `dislikes` + 1 $sqlPart WHERE `id` = :refId";

            $query = $this->_pdo->prepare($sql);

            $query->execute([":refId" => $refId]);

            return true;
        }

        else {
            $query = $this->_pdo->prepare("UPDATE $category SET `dislikes` = `dislikes` - 1 WHERE `id` = :refId");
            $query->execute([":refId" => $refId]);
        }

        return false;
    }

//*****E. Modifying the (dis)like count according to the votes*****//
    public function updateVoteCount(string $refId, string $category) {
        $sql = "SELECT COUNT(id) AS voteCount, `vote` FROM `votes` WHERE `ref_id` = :refId AND `category` = :category GROUP BY `vote`";

        $query = $this->_pdo->prepare($sql);
        
        $query->execute([":refId" => $refId, ":category" => $category]);

        $votes = $query->fetchAll();

        $count = ["-1" => 0, "1" => 0];

        foreach($votes as $vote) {
            $count[$vote["vote"]] = $vote["voteCount"];
        }

        $req = "UPDATE $category SET `likes` = :likes, `dislikes` = :dislikes WHERE `id` = :refId";

        $query = $this->_pdo->prepare($req);

        $query->execute([":refId" => $refId, ":likes" => $count[1], ":dislikes" => $count[-1]]);

        return true;
    }

//*****END OF THE Votes MODEL*****//
}