<?php

namespace App\model;

use App\core\Connect;

class Comments extends Connect {

    protected $_pdo;
    public function __construct() {
        $this->_pdo = $this->connection();
    }
    
//*****A. Comment addition*****//
    public function addComment(string $commentId, string $userEmail, string $userLogin, string $userIp, string $albumId, string $albumTitle,
                                string $comment) {
        $sql = "INSERT INTO `comments` (`id`, `user_email`, `user_login`, `user_ip`, `album_id`, `album_title`, `comment`)
                VALUES (:commentId, :userEmail, :userLogin, :userIp, :albumId, :albumTitle, :comment)";
                
        $query = $this->_pdo->prepare($sql);
        
        $query->execute([
                        ":commentId" => $commentId,
                        ":userEmail" => $userEmail,
                        ":userLogin" => $userLogin,
                        ":userIp" => $userIp,
                        ":albumId" => $albumId,
                        ":albumTitle" => $albumTitle,
                        ":comment" => $comment
                        ]);
        
        return;
    }

//*****B. Answer addition*****//
    public function addAnswer(string $answerId, string $userEmail, string $userLogin, string $userIp, string $commentId, string $albumTitle,
                                string $answer) {
        $sql = "INSERT INTO `comment_answers` (`id`, `user_email`, `user_login`, `user_ip`, `comment_id`, `album_title`, `answer`)
                VALUES (:answerId, :userEmail, :userLogin, :userIp, :commentId, :albumTitle, :answer)";
                
        $query = $this->_pdo->prepare($sql);
        
        $query->execute([
                        ":answerId" => $answerId,
                        ":userEmail" => $userEmail,
                        ":userLogin" => $userLogin,
                        ":userIp" => $userIp,
                        ":commentId" => $commentId,
                        ":albumTitle" => $albumTitle,
                        ":answer" => $answer
                        ]);
        
        return;
    }

//*****C. Finding all the comments*****//    
    public function findAllComments() {
        $sql = "SELECT `id`, `user_email`, `user_login`, `user_ip`, `album_title`, `comment`, `post_date` FROM `comments` ORDER BY `album_title`";
                    
        $query = $this->_pdo->prepare($sql);
        
        $query->execute();
        
        return $query->fetchAll(\PDO::FETCH_ASSOC);
    }

//*****D. Finding all the answers*****//    
public function findAllAnswers() {
    $sql = "SELECT `id`, `user_email`, `user_login`, `user_ip`, `comment_id`, `album_title`, `answer`, `post_date` FROM `comment_answers`
            ORDER BY `album_title`, `comment_id`, `post_date`";
                
    $query = $this->_pdo->prepare($sql);
    
    $query->execute();
    
    return $query->fetchAll(\PDO::FETCH_ASSOC);
}

//*****E. Finding the comments of a specific album*****//
    public function findAlbumComments(string $albumId) {
        $sql = "SELECT album.id, comments.id, comments.user_email, comments.user_login, comments.user_ip, `album_id`, comments.album_title,
                        `comment`, comments.post_date, comments.likes, comments.dislikes FROM `album`
                LEFT OUTER JOIN `comments` ON comments.album_id = album.id
                WHERE (SELECT comments.post_date = MAX(comments.post_date)) AND album.id = :albumId ORDER BY `post_date` DESC";
                    
        $query = $this->_pdo->prepare($sql);
        
        $query->execute([":albumId" => $albumId]);
        
        return $query->fetchAll(\PDO::FETCH_ASSOC);
    }

//*****F. Finding the answers of a specific comment*****//
    public function findCommentAnswers(string $commentId) {
        $sql = "SELECT comments.id, comment_answers.id, comment_answers.user_email, comment_answers.user_login, comment_answers.user_ip, 
                        `comment_id`, comment_answers.album_title, `answer`, comment_answers.post_date, comment_answers.likes,
                        comment_answers.dislikes
                FROM `comments`
                LEFT OUTER JOIN `comment_answers` ON comment_answers.comment_id = comments.id
                WHERE (SELECT comment_answers.post_date = MAX(comment_answers.post_date)) AND comments.id = :commentId
                ORDER BY `post_date`";
                    
        $query = $this->_pdo->prepare($sql);
        
        $query->execute([":commentId" => $commentId]);
        
        return $query->fetchAll(\PDO::FETCH_ASSOC);
    }

//*****G. Comment modification*****//
    public function updateComment(string $commentId, string $comment) {
        $sql = "UPDATE `comments` SET `comment` = :comment WHERE `id` = :commentId";
        
        $query = $this->_pdo->prepare($sql);
        
        $query->execute([":commentId" => $commentId, ":comment" => $comment]);
    }

//*****H. Answer modification*****//
    public function updateAnswer(string $answerId, string $answer) {
        $sql = "UPDATE `comment_answers` SET `answer` = :answer WHERE `id` = :answerId";
        
        $query = $this->_pdo->prepare($sql);
        
        $query->execute([":answerId" => $answerId, ":answer" => $answer]);
    }

//*****I. Comment deletion*****//
    public function deleteComment(string $commentId) {
        $sql = "DELETE FROM `comments` WHERE `id` = :commentId";
                    
        $query = $this->_pdo->prepare($sql);
        
        $query->execute([":commentId" => $commentId]);
    }

//*****J. Answer deletion*****//
    public function deleteAnswer(string $answerId) {
        $sql = "DELETE FROM `comment_answers` WHERE `id` = :answerId";
                    
        $query = $this->_pdo->prepare($sql);
        
        $query->execute([":answerId" => $answerId]);  
    }
    
//*****END OF THE CLASS*****//   
}