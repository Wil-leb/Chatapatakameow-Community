<?php

namespace App\model;

use App\core\Connect;

class Comments extends Connect {

    protected $_pdo;
    public function __construct() {
        $this->_pdo = $this->connection();
    }
    
//*****A. Comment addition*****//
    public function addComment(string $commentId, string $refAuthorId, string $albumId, string $albumTitle, string $userEmail,
                                string $userLogin, string $userIp, string $comment) {
        $sql = "INSERT INTO `comments` (`id`, `album_author_id`, `album_id`, `album_title`, `comment_email`, `comment_login`,
                                        `comment_ip`, `comment`)
                VALUES (:commentId, :refAuthorId, :albumId, :albumTitle, :userEmail, :userLogin, :userIp, :comment)";
                
        $query = $this->_pdo->prepare($sql);
        
        $query->execute([
                        ":commentId" => $commentId,
                        ":refAuthorId" => $refAuthorId,
                        ":albumId" => $albumId,
                        ":albumTitle" => $albumTitle,
                        ":userEmail" => $userEmail,
                        ":userLogin" => $userLogin,
                        ":userIp" => $userIp,
                        ":comment" => $comment
                        ]);
        
        return;
    }

//*****B. Answer addition*****//
    public function addAnswer(string $answerId, string $commentId, string $refAuthorId, string $refAuthorEmail, string $albumId,
                                string $albumTitle, string $userEmail, string $userLogin, string $userIp, string $answer) {
        $sql = "INSERT INTO `comment_answers` (`id`, `comment_id`, `comment_author_id`, `comment_author_email`, `album_id`,
                            `album_title`, `answer_email`, `answer_login`, `answer_ip`, `answer`)
                VALUES (:answerId, :commentId, :refAuthorId, :refAuthorEmail, :albumId, :albumTitle, :userEmail, :userLogin, :userIp,
                        :answer)";
                
        $query = $this->_pdo->prepare($sql);
        
        $query->execute([
                        ":answerId" => $answerId,
                        ":commentId" => $commentId,
                        ":refAuthorId" => $refAuthorId,
                        ":refAuthorEmail" => $refAuthorEmail,
                        ":albumId" => $albumId,
                        ":albumTitle" => $albumTitle,
                        ":userEmail" => $userEmail,
                        ":userLogin" => $userLogin,
                        ":userIp" => $userIp,
                        ":answer" => $answer
                        ]);
        
        return;
    }

//*****C. Finding all the comments*****//    
    public function findAllComments() {
        $sql = "SELECT `id`, `album_id`, `album_title`, `comment_email`, `comment_login`, `comment_ip`, `comment`, `post_date`,
                        `reports_number`
                FROM `comments` ORDER BY `album_title`, `post_date` DESC";
                    
        $query = $this->_pdo->prepare($sql);
        
        $query->execute();
        
        return $query->fetchAll(\PDO::FETCH_ASSOC);
    }

//*****D. Finding all the answers*****//    
public function findAllAnswers() {
    $sql = "SELECT `id`, `comment_id`, `album_id`, `album_title`, `answer_email`, `answer_login`, `answer_ip`, `answer`, `post_date`,
                    `reports_number`
            FROM `comment_answers` ORDER BY `album_title`, `comment_id`, `post_date` DESC";
                
    $query = $this->_pdo->prepare($sql);
    
    $query->execute();
    
    return $query->fetchAll(\PDO::FETCH_ASSOC);
}

//*****E. Finding the comments of a specific album*****//
    public function findAlbumComments(string $albumId) {
        $sql = "SELECT albums.id, comments.id, `album_id`, comments.album_title, `comment_email`, `comment_login`, `comment_ip`,
                        `comment`, comments.post_date, comments.likes, comments.dislikes FROM `albums`
                LEFT OUTER JOIN `comments` ON comments.album_id = albums.id
                WHERE (SELECT comments.post_date = MAX(comments.post_date)) AND albums.id = :albumId ORDER BY `post_date` DESC";
                    
        $query = $this->_pdo->prepare($sql);
        
        $query->execute([":albumId" => $albumId]);
        
        return $query->fetchAll(\PDO::FETCH_ASSOC);
    }

//*****F. Finding the answers of a specific comment*****//
    public function findCommentAnswers(string $commentId) {
        $sql = "SELECT comments.id, comment_answers.id, `comment_id`, comment_answers.album_title, `answer_email`, `answer_login`,
                        `answer_ip`, `answer`, comment_answers.post_date, comment_answers.likes, comment_answers.dislikes
                FROM `comments`
                LEFT OUTER JOIN `comment_answers` ON comment_answers.comment_id = comments.id
                WHERE (SELECT comment_answers.post_date = MAX(comment_answers.post_date)) AND comments.id = :commentId
                ORDER BY `post_date` DESC";
                    
        $query = $this->_pdo->prepare($sql);
        
        $query->execute([":commentId" => $commentId]);
        
        return $query->fetchAll(\PDO::FETCH_ASSOC);
    }

//*****G. Finding a comment via its identifier*****//
public function findCommentById(string $commentId) {
    $sql = "SELECT `id`, `comment_email`, `comment` FROM `comments` WHERE `id` = :commentId";
            
    $query = $this->_pdo->prepare($sql);
    
    $query->execute([":commentId" => $commentId]);
            
    return $query->fetch(\PDO::FETCH_ASSOC); 
}

//*****H. Finding a comment via its identifier*****//
public function findAnswerById(string $answerId) {
    $sql = "SELECT comment_answers.id, `answer_email`, `answer`, comments.comment FROM `comment_answers`
            LEFT OUTER JOIN `comments` ON comment_answers.comment_id = comments.id
            WHERE comment_answers.id = :answerId";
            
    $query = $this->_pdo->prepare($sql);
    
    $query->execute([":answerId" => $answerId]);
            
    return $query->fetch(\PDO::FETCH_ASSOC); 
}

//*****I. Comment modification*****//
    public function updateComment(string $commentId, string $comment) {
        $sql = "UPDATE `comments` SET `comment` = :comment WHERE `id` = :commentId";
        
        $query = $this->_pdo->prepare($sql);
        
        $query->execute([":commentId" => $commentId, ":comment" => $comment]);
    }

//*****J. Answer modification*****//
    public function updateAnswer(string $answerId, string $answer) {
        $sql = "UPDATE `comment_answers` SET `answer` = :answer WHERE `id` = :answerId";
        
        $query = $this->_pdo->prepare($sql);
        
        $query->execute([":answerId" => $answerId, ":answer" => $answer]);
    }

//*****K. Comment deletion*****//
    public function deleteComment(string $commentId) {
        $sql = "DELETE FROM `comments` WHERE `id` = :commentId";
                    
        $query = $this->_pdo->prepare($sql);
        
        $query->execute([":commentId" => $commentId]);
    }

//*****L. Answer deletion*****//
    public function deleteAnswer(string $answerId) {
        $sql = "DELETE FROM `comment_answers` WHERE `id` = :answerId";
                    
        $query = $this->_pdo->prepare($sql);
        
        $query->execute([":answerId" => $answerId]);  
    }
    
//*****END OF THE CLASS*****//   
}