<?php

namespace App\model;

use App\core\Connect;

class Account extends Connect {

    protected $_pdo;
    public function __construct() {
        $this->_pdo = $this->connection();
    }
    
//*****A. Finding a user via his/her email*****//
    public function findUserByEmail(string $email) {
        $sql = "SELECT `email` FROM `user`  WHERE `email` = :email";
                
        $query = $this->_pdo->prepare($sql);
        
        $query->execute([":email" => $email]);
            
        return $query->fetch(\PDO::FETCH_ASSOC);
    }

//*****B. Finding a user via his/her login*****//
    public function findUserByLogin(string $login) {
        $sql = "SELECT `login`, `password` FROM `user` WHERE `login` = :login";
                
        $query = $this->_pdo->prepare($sql);
        
        $query->execute([":login" => $login]);
            
        return $query->fetch(\PDO::FETCH_ASSOC);
    }
    
//*****C. Email modification*****//
    public function updateUserEmail(int $id, string $email) {
        $sql = "UPDATE `user` SET `email` = :email WHERE `id` = :id";
            
        $query = $this->_pdo->prepare($sql);
    
        $query->execute([":id" => $id, ":email" => $email]);
    }
    
//*****D. Login modification*****// 
    public function updateUserLogin(int $id, string $login) {
        $sql = "UPDATE `user` SET `login` = :login WHERE `id` = :id";
            
        $query = $this->_pdo->prepare($sql);
    
        $query->execute([":id" => $id, ":login" => $login]);
    }
    
//*****E. Password modification*****//
    public function updateUserPassword(int $id, string $password) {
        $password = password_hash($password, PASSWORD_DEFAULT);
            
        $sql = "UPDATE `user` SET `password` = :password WHERE `id` = :id";
            
        $query = $this->_pdo->prepare($sql);
    
        $query->execute([":id" => $id, ":password" => $password]);
    }

//*****END OF THE CLASS*****//
}