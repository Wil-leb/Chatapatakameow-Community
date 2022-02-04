<?php

namespace App\model;

use App\core\Connect;

class User extends Connect {
    
    protected $_pdo;
    public function __construct() {
        $this->_pdo = $this->connection();
    }
    
//*****A. User connection information addition*****//
    public function addUserConnection(string $id, string $email, string $login, string $password) {
        $password = password_hash($password, PASSWORD_DEFAULT);
        
        $sql = "INSERT INTO `user` (`id`, `email`, `login`, `password`) VALUES (:id, :email, :login, :password)";

        $query = $this->_pdo->prepare($sql);
        
        $query->execute([
                        ":id" => $id,
                        ":email" => $email,
                        ":login" => $login,
                        ":password" => $password
                        ]);
    }

//*****B. Finding all the users*****//
    public function findAllUsers() {
        
        $sql = "SELECT `id`, `email`, `login`, `password`, `role` FROM `user`";
                    
        $query = $this->_pdo->prepare($sql);
        
        $query->execute();
        
        return $query->fetchAll(\PDO::FETCH_ASSOC);
    }

//*****C. Finding a user via his/her email*****//    
    public function findUserByEmail(string $email) {
        
        $sql = "SELECT `email` FROM `user` WHERE email = :email";
                
        $query = $this->_pdo->prepare($sql);
        
        $query->execute([":email" => $email]);
            
        return $query->fetch(\PDO::FETCH_ASSOC);
    }

//*****D. Finding a user via his/her login*****//    
    public function findUserByLogin(string $login) {
        
        $sql = "SELECT `id`, `email`, `login`, `password`, `role` FROM `user` WHERE login = :login";
                
        $query = $this->_pdo->prepare($sql);
        
        $query->execute([":login" => $login]);
            
        return $query->fetch(\PDO::FETCH_ASSOC);
    }

//*****END OF THE User MODEL*****//
}