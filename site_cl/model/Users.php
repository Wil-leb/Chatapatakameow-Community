<?php

namespace App\model;

use App\core\Connect;

class Users extends Connect {
    
    protected $_pdo;
    public function __construct() {
        $this->_pdo = $this->connection();
    }
    
//*****A. User addition*****//
    public function addUser(string $id, string $email, string $login, string $password, int $key) {
        $password = password_hash($password, PASSWORD_DEFAULT);
        
        $sql = "INSERT INTO `users` (`id`, `email`, `login`, `password`, `confirm_key`) VALUES (:id, :email, :login, :password, :key)";

        $query = $this->_pdo->prepare($sql);
        
        $query->execute([
                        ":id" => $id,
                        ":email" => $email,
                        ":login" => $login,
                        ":password" => $password,
                        ":key" => $key
                        ]);
    }

//*****B. Finding all the users*****//
    public function findAllUsers() {
        
        $sql = "SELECT `id`, `email`, `login`, `password`, `account_suspended`, `role` FROM `users`";
                    
        $query = $this->_pdo->prepare($sql);
        
        $query->execute();
        
        return $query->fetchAll(\PDO::FETCH_ASSOC);
    }

//*****C. Finding a user's email*****//
    public function findEmail(string $email) {
        
        $sql = "SELECT `id`, `email`, `login` FROM `users` WHERE `email` = :email";
                    
        $query = $this->_pdo->prepare($sql);
        
        $query->execute([":email" => $email]);
        
        return $query->fetch(\PDO::FETCH_ASSOC);
    }

//*****D. Finding a user's login*****//
    public function findLogin(string $login) {
            
        $sql = "SELECT `id`, `email`, `login`, `password`, `account_confirmed`, `account_suspended`, `role` FROM `users`
                WHERE `login` = :login";
                    
        $query = $this->_pdo->prepare($sql);
        
        $query->execute([":login" => $login]);
        
        return $query->fetch(\PDO::FETCH_ASSOC);
    }

//*****E. Finding a specific user*****//    
    public function findUserById(string $id) {
                    
        $sql = "SELECT `id`, `email`, `login`, `password`, `account_suspended`, `role` FROM `users` WHERE `id` = :id";
                    
        $query = $this->_pdo->prepare($sql);
        
        $query->execute([":id" => $id]);
        
        return $query->fetch(\PDO::FETCH_ASSOC);
    }

//*****F. Email modification*****//
    public function updateEmail(string $id, string $email) {
        $sql = "UPDATE `users` SET `email` = :email WHERE `id` = :id";
            
        $query = $this->_pdo->prepare($sql);

        $query->execute([":id" => $id, ":email" => $email]);
    }

//*****G. Login modification*****//
    public function updateLogin(string $id, string $login) {
            
        $sql = "UPDATE `users` SET `login` = :login WHERE `id` = :id";
            
        $query = $this->_pdo->prepare($sql);

        $query->execute([":id" => $id, ":login" => $login]);
    }

//*****H. Password modification*****//
    public function updatePassword(string $id, string $password) {
        $password = password_hash($password, PASSWORD_DEFAULT);
            
        $sql = "UPDATE `users` SET `password` = :password WHERE `id` = :id";
            
        $query = $this->_pdo->prepare($sql);

        $query->execute([":id" => $id, ":password" => $password]);                
    }

//*****I. Role modification*****//
    public function updateRole(string $id, int $role) {
        $sql = "UPDATE `user` SET `role` = :role WHERE `id` = :id";
            
        $query = $this->_pdo->prepare($sql);

        $query->execute([":id" => $id, ":role" => $role]);                 
    }

//*****J. Account suspension*****//
public function suspendAccount(string $id, int $state) {
    $sql = "UPDATE `users` SET `account_suspended` = :state WHERE `id` = :id";
        
    $query = $this->_pdo->prepare($sql);

    $query->execute([":id" => $id, ":state" => $state]);
}

//*****K. Account reactivation*****//
public function reactivateAccount(string $id, int $state) {
    $sql = "UPDATE `users` SET `account_suspended` = :state WHERE `id` = :id";
        
    $query = $this->_pdo->prepare($sql);

    $query->execute([":id" => $id, ":state" => $state]);
} 
    
//*****L. User deletion*****//
    public function deleteUser(string $id) {
            
        $sql = "DELETE FROM `users` WHERE `id` = :id";
                    
        $query = $this->_pdo->prepare($sql);
        
        $query->execute([":id" => $id]);
    }

//*****END OF THE User MODEL*****//
}