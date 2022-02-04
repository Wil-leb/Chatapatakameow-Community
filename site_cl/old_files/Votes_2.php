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

    private function recordExists($albumId) {
        $sql = "SELECT album.id, `album_id`, `user_ip`
                FROM `album`
                LEFT OUTER JOIN `votes` ON votes.album_id = album.id
                WHERE album.id = :albumId";

        $query = $this->_pdo->prepare($sql);
        
        $query->execute([':albumId' => $albumId]);

        $query->fetch(\PDO::FETCH_ASSOC);

        if($query->rowCount() == 0) {
            throw new Exception ('Impossible de voter pour un album qui n\'existe pas');
        }
    }
    
    private function addVote(string $id, string $albumId, string $userIp, int $vote) {
        $this->recordExists($albumId);

        $query = $this->_pdo->prepare("SELECT `id`, `vote` FROM `votes` WHERE `album_id` = :albumId AND `user_ip` = :userIp");

        $query->execute([
                        ':albumId' => $albumId,
                        ':userIp' => $userIp
                        ]);

        $voteRow = $query->fetch(\PDO::FETCH_ASSOC);

        if($voteRow) {
            if($voteRow['vote'] == $vote) {
                $query = $this->_pdo->prepare("DELETE FROM `votes` WHERE `id` = :id");

                $query->execute([':id' => $voteRow['id']]);

                return false;
            }

            $this->_formerVote = $voteRow;
            $query = $this->_pdo->prepare("UPDATE `votes` SET `vote` = :vote WHERE `id` = :id");

            $query->execute([
                            ':id' => $voteRow['id'],
                            ':vote' => $vote
                            ]);

            var_dump($voteRow);
            return true;
        }

        $query = $this->_pdo->prepare("INSERT INTO `votes` (`id`, `album_id`, `user_ip`, `vote`)
                                        VALUES (:id, :albumId, :userIp, :vote)");
        
        $query->execute([
                        ':id' => $id,
                        ':albumId' => $albumId,
                        ':userIp' => $userIp,
                        ':vote' => $vote
                        ]);

        return true;
    }

    public function like(string $id, string $albumId, string $userIp) {
        if($this->addVote($id, $albumId, $userIp, 1)) {
            $sqlPart = "";

            if($this->_formerVote) {
                $sqlPart = ", `dislikes` = `dislikes` - 1";
            }

            $query = $this->_pdo->prepare("UPDATE `album` SET `likes` = `likes` + 1 $sqlPart WHERE `id` = :albumId");

            $query->execute([':albumId' => $albumId]);

            return true;
        }
        
        else {
            $query = $this->_pdo->prepare("UPDATE `album` SET `likes` = `likes` - 1 WHERE `id` = :albumId");
            
            $query->execute([':albumId' => $albumId]);
        }

        return false;
    }
    
    public function dislike(string $id, string $albumId, string $userIp) {
        if($this->addVote($id, $albumId, $userIp, -1)) {
            $sqlPart = "";

            if($this->_formerVote) {
                $sqlPart = ", `likes` = `likes` - 1";
            }

            $query = $this->_pdo->prepare("UPDATE `album` SET `dislikes` = `dislikes` + 1 $sqlPart WHERE `id` = :albumId");

            $query->execute([':albumId' => $albumId]);

            return true;
        }
        
        else {

            $query = $this->_pdo->prepare("UPDATE `album` SET `dislikes` = `dislikes` - 1 $sqlPart WHERE `id` = :albumId");
            
            $query->execute([':albumId' => $albumId]);

        }

        return false;
    }

    public function findAllVotes() {
        
        $sql = "SELECT `id` FROM `votes`";
                    
        $query = $this->_pdo->prepare($sql);
        
        $query->execute();
        
        return $query->fetchAll(\PDO::FETCH_ASSOC);
        
    }

    public function getClass(int $vote) {
        $sql = "SELECT `vote` FROM `votes` WHERE `vote` = :vote";

        $query = $this->_pdo->prepare($sql);
        
        $query->execute([':vote' => $vote]);

        $query->fetchAll(\PDO::FETCH_ASSOC);

        if($vote) {
            return $vote == 1 ? 'is-liked' : 'is-disliked'; 
        }

        return null;
    }

    public function updateVotecount($albumId) {
        $sql = "SELECT COUNT(id) AS voteCount, `vote` FROM `votes` WHERE `album_id` = :albumId GROUP BY `vote`";

        $query = $this->_pdo->prepare($sql);
        
        $query->execute([':albumId' => $albumId]);

        $votes = $query->fetchAll(\PDO::FETCH_ASSOC);

        $count = [
                '-1' => 0,
                '1' => 0
                ];

        foreach($votes as $vote) {
            $count[$vote['vote']] = $vote['voteCount'];
        }

        $query = $this->_pdo->prepare("UPDATE `album` SET `likes` = :likes, `dislikes` = :dislikes WHERE `id` = :albumId");

        $query->execute([
                        ':albumId' => $albumId,
                        ':likes' => $count[1],
                        ':dislikes' => $count[-1]
                        ]);

        return true;
        
//*****END OF THE updateVotecount() METHOD*****//
    }

//*****END OF THE Votes MODEL*****//
}