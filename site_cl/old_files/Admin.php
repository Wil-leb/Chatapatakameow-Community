<?php

namespace App\model;

use App\core\Connect;

class Admin extends Connect {
    
    protected $_pdo;
    public function __construct(){
        $this->_pdo = $this->connection();
    }
    
//*****A. Comment deletion*****//
    public function deleteComment(string $id) {
        $sql = "DELETE FROM `comments` WHERE `id` = :commentId";
                    
        $query = $this->_pdo->prepare($sql);
        
        $query->execute([":commentId" => $id]);
    }

//*****B. Answer deletion*****//
    public function deleteAnswer(string $id) {
        $sql = "DELETE FROM `comment_answers` WHERE `id` = :answerId";
                    
        $query = $this->_pdo->prepare($sql);
        
        $query->execute([":answerId" => $id]);
    }

//*****C. User deletion*****//
    public function deleteUser(string $id) {
        
        $sql = "DELETE FROM `user` WHERE `id` = :userId";
                    
        $query = $this->_pdo->prepare($sql);
        
        $query->execute([":userId" => $id]);
    }

//*****D. Finding a user via his/her email*****//
    public function findUserByEmail(string $email) {
            
        $sql = "SELECT `email` FROM `user` WHERE `email` = :email";
                
        $query = $this->_pdo->prepare($sql);
        
        $query->execute([":email" => $email]);
            
        return $query->fetch(\PDO::FETCH_ASSOC);
    }
    
//*****E. Finding a user via his/her login*****//
    public function findUserByLogin(string $login) {
        
        $sql = "SELECT `login`, `password` FROM `user` WHERE `login` = :login";
                
        $query = $this->_pdo->prepare($sql);
        
        $query->execute([":login" => $login]);
            
        return $query->fetch(\PDO::FETCH_ASSOC);
    }
    
//*****F. Finding a user via his/her identifier*****//
    public function findUserById(string $id) {
                
        $sql = "SELECT `id`, `email`, `login`, `role` FROM `user` WHERE `id` = :id";
                    
        $query = $this->_pdo->prepare($sql);
        
        $query->execute([":id" => $id]);
        
        return $query->fetch(\PDO::FETCH_ASSOC);
    }

//*****G. Email modification*****//
    public function updateUserEmail(string $id, string $email) {
        $sql = "UPDATE `user` SET `email` = :email WHERE `id` = :id";
            
        $query = $this->_pdo->prepare($sql);

        $query->execute([":id" => $id, ":email" => $email]);
    }

//*****H. Login modification*****//
    public function updateUserLogin(string $id, string $login) {
            
        $sql = "UPDATE `user` SET `login` = :login WHERE `id` = :id";
            
        $query = $this->_pdo->prepare($sql);

        $query->execute([":id" => $id, ":login" => $login]);
    }

//*****I. Password modification*****//
    public function updateUserPassword(string $id, string $password) {
        $password = password_hash($password, PASSWORD_DEFAULT);
            
        $sql = "UPDATE `user` SET `password` = :password WHERE `id` = :id";
            
        $query = $this->_pdo->prepare($sql);

        $query->execute([":id" => $id, ":password" => $password]);                
    }

//*****J. Role modification*****//
    public function updateRole(string $id, $role) {
        $sql = "UPDATE `user` SET `role` = :role WHERE `id` = :id";
            
        $query = $this->_pdo->prepare($sql);

        $query->execute([":id" => $id, ":role" => $role]);                 
    }

//*****END OF THE CLASS*****//
}