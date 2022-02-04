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

//*****A. Finding the existing votes of a specific album*****//
    private function recordExists(string $refId, string $category) {
        $sql = $this->_pdo->prepare("SELECT `id` FROM $category WHERE `id` = :id");

        // $query = $this->_pdo->prepare($sql);
        
        $sql->execute([":id" => $refId]);

        // $query->fetch(\PDO::FETCH_ASSOC);

        if($sql->rowCount() == 0) {
            die("Impossible de voter pour un album qui n'existe pas !");
        }
    }

//*****B. Vote addition*****//
    private function addVote(string $id, string $refId, string $category, string $userIp, int $vote) {
        $this->recordExists($refId, $category);

        $sql = "SELECT `id`, `vote` FROM `votes` WHERE `ref_id` = :refId AND `category` = :category AND `user_ip` = :userIp";

        $query = $this->_pdo->prepare($sql);

        $query->execute([":refId" => $refId, ":category" => $category, ":userIp" => $userIp]);

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

        $req = "INSERT INTO `votes` (`id`, `ref_id`, `category`, `user_ip`, `vote`) VALUES (:id, :refId, :category, :userIp, :vote)";

        $query = $this->_pdo->prepare($req);
        
        $query->execute([
                        ":id" => $id,
                        ":refId" => $refId,
                        ":category" => $category,
                        ":userIp" => $userIp,
                        ":vote" => $vote
                        ]);

        return true;
    }

//*****C. Like addition*****//
    public function like(string $id, string $refId, string $category, string $userIp) {
        if($this->addVote($id, $refId, $category, $userIp, 1)) {
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

//*****D. Dislike addition*****//
    public function dislike(string $id, string $refId, string $category, string $userIp) {
        if($this->addVote($id, $refId, $category, $userIp, -1)) {
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

//*****E. Finding a user's votes*****//
    public function findVotes(string $category, string $userIp) {
        $sql = "SELECT `id`, `ref_id` FROM `votes` WHERE `category` = :category AND `user_ip` = :userIp";
                    
        $query = $this->_pdo->prepare($sql);
        
        $query->execute([":category" => $category, ":userIp" => $userIp]);
        
        return $query->fetch();
    }

//*****F. Styling the thumb logos according to the votes*****//
    public function getClass(int $vote) {
        $sql = "SELECT `vote` FROM `votes` WHERE `vote` = :vote";

        $query = $this->_pdo->prepare($sql);
        
        $query->execute([":vote" => $vote]);

        $query->fetchAll(\PDO::FETCH_ASSOC);

        if($vote) {
            return $vote == 1 ? "is-liked" : "is-disliked"; 
        }

        return null;
    }

//*****G. Modifying the (dis)like count according to the votes*****//
    public function updateVotecount(string $refId, string $category) {
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