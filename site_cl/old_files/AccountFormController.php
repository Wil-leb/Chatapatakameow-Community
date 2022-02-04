<?php

namespace App\controller;

use App\model\{User, Account};
use App\core\Session;

class AccountFormController {
    
    protected Account $_account;
    public function __construct(Account $account) {
        $this->_account = $account;
    }
    
//****A. Email form*****//
    public function emailForm(array $data) {
        $emailMessages = [];
        
        if($_POST["confirmEmailchange"]) {
            $id = $_SESSION["user"]["id"];

            $currentEmail = $data["currentEmail"];
            $newEmail = $data["newEmail"];
            $confirmNewemail = $data["confirmNewemail"];
            
            $exist = $this->_account->findUserByEmail($_SESSION["user"]["email"]);
            
            if(!empty($currentEmail) && !empty($newEmail) && !empty($confirmNewemail)) {
                if($currentEmail == $exist["email"]) {
                    if($newEmail == $confirmNewemail) {
                        $email = $this->_account->findUserByEmail($newEmail);
                    
                        if(!$email) {
                            if(!filter_var($newEmail, FILTER_VALIDATE_EMAIL) ||
                                !filter_var($confirmNewemail, FILTER_VALIDATE_EMAIL)) {
                                $emailMessages["errors"][] = "Le format de la nouvelle adresse électronique est invalide.";
                            }
                            
                            else {
                                $update = $this->_account->updateUserEmail($id, $newEmail);
                                $emailMessages["success"] = ["Ton adresse électronique a été modifiée avec succès."];
                            }                             
                        }
                         
                        else {
                            $emailMessages["errors"][] = "Cette adresse électronique est déjà utilisée pour un autre compte.";
                        }                           
                    }
                    
                    else {
                        $emailMessages["errors"][] = "La nouvelle adresse électronique et sa confirmation doivent correspondre.";
                    }
                }
                
                else {
                    $emailMessages["errors"][] = "L'adresse électronique actuelle est invalide.";
                }     
            }
        
            else {
                $emailMessages["errors"][] = "Veuilles remplir tous les champs.";
            }
        }
        
        return $emailMessages;     
    }
    
//*****B. Login form*****//
    public function loginForm(array $data) {
        $loginMessages = [];
        
        if($_POST["confirmLoginchange"]) {
            $id = $_SESSION["user"]["id"];
            
            $currentLogin = $data["currentLogin"];
            $newLogin = $data["newLogin"];
            $confirmNewlogin = $data["confirmNewlogin"];
            
            $exist = $this->_account->findUserByLogin($_SESSION["user"]["login"]);
            
            if(!empty($currentLogin) && !empty($newLogin) && !empty($confirmNewlogin)) {
                if($currentLogin == $exist["login"]) {
                    if($newLogin == $confirmNewlogin) {
                        $login = $this->_account->findUserByLogin($newLogin);
                    
                        if(!$login) {
                            $loginRegex = "/^[\p{L}\d\-_]+$/ui";
                            
                            if(!preg_match($loginRegex, $newLogin)) {
                                $loginMessages["errors"][] = "Caractères autorisés pour le nouveau pseudo : lettres, chiffres, tirets et underscores.";
                            }

                            elseif(strlen($newLogin) < 3 || strlen($newLogin) > 10) {
                                $loginMessages["errors"][] = "Le nouveau pseudo doit contenir entre trois et dix caractères.";
                            }
                            
                            else {
                                $update = $this->_account->updateUserLogin($id, $newLogin);
                                $loginMessages["success"] = ["Ton pseudo a été modifié avec succès."];
                            }                             
                        }
                         
                        else {
                            $loginMessages["errors"][] = "Ce pseudo est déjà utilisé pour un autre compte.";
                        }                           
                    }
                    
                    else {
                        $loginMessages["errors"][] = "Le nouveau pseudo et sa confirmation doivent correspondre.";
                    } 
                }
                
                else {
                    $loginMessages["errors"][] = "Le pseudo actuel est invalide.";
                }     
            }
        
            else {
                $loginMessages["errors"][] = "Veuilles remplir tous les champs.";
            }  
        }
        
        return $loginMessages; 
    }
    
//*****C. Password form*****//
    public function passwordForm(array $data) {        
        $passwordMessages = [];
        
        if($_POST["confirmPasswordchange"]) {
            $id = $_SESSION["user"]["id"];
            
            $currentPassword = $data["currentPassword"];
            $newPassword = $data["newPassword"];
            $confirmNewpassword = $data["confirmNewpassword"];
            
            $exist = $this->_account->findUserByLogin($_SESSION["user"]["login"]);
            
                if(!empty($currentPassword) && !empty($newPassword) && !empty($confirmNewpassword)) {
                    if($currentPassword == password_verify($currentPassword, $exist["password"])) {
                        if($newPassword == $confirmNewpassword) {
                            $update = $this->_account->updateUserPassword($id, $newPassword);
                            $newPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                            $passwordMessages["success"] = ["Ton mot de passe a été modifié avec succès."];
                        }
                        
                        else {
                            $passwordMessages["errors"][] = "Le nouveau mot de passe et sa confirmation doivent correspondre.";
                        }                        
                    }
                    
                    else {
                        $passwordMessages["errors"][] = "Le mot de passe actuel est invalide.";
                    }
                }
            
            else {
                $passwordMessages["errors"][] = "Veuilles remplir tous les champs.";
            }  
        }
        
        return $passwordMessages;     
    }
    
//*****END OF THE CLASS*****//    
}