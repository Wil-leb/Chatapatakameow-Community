<?php

namespace App\controller;

use App\model\{User};
use App\core\{Cookie, Session};
use \PDO;

class FormController {
    
    protected User $_user;
    public function __construct(User $user) {
        $this->_user = $user;
    }
    
//*****A. Registration*****//
    public function registrationForm(array $data) {
        if($_POST["register"]) {
            $messages = [];
            $loginContent = $data["login"];
            $loginRegex = "/^[\p{L}0-9\-_]+$/ui";
            
            if(empty($data["login"]) || empty($data["password"]) || empty($data["confirmPassword"]) || empty($data["email"])) {
                $messages["errors"][] = "Veuilles remplir tous les champs.";
            }
            
            if(!empty($data["email"]) && !filter_var($data["email"], FILTER_VALIDATE_EMAIL)) {
                $messages["errors"][] = "Le format de l'adresse électronique est invalide.";
            }
            
            if(!empty($data["login"]) && strlen($data["login"]) < 3 || strlen($data["login"]) > 10) {
                $messages["errors"][] = "Ton pseudo doit contenir entre trois et dix caractères.";
            }
            
            if(!empty($data["login"]) && !preg_match($loginRegex, $loginContent)) {
                $messages["errors"][] = "Caractères autorisés pour le pseudo : lettres, chiffres, tirets et underscores.";
            }
            
            if ($data["password"] !== $data["confirmPassword"] || $data["confirmPassword"] !== $data["password"]) {
                $messages["errors"][] = "Le mot de passe et sa confirmation doivent correspondre.";
            }
            
            $email = $this->_user->findUserByEmail($data["email"]);

            if($email) {
                $messages["errors"][] = "Cette adresse électronique est déjà utilisée pour un autre compte.";
            }
            
            $login = $this->_user->findUserByLogin($data["login"]);
        
            if($login) {
                $messages["errors"][] = "Ce pseudo est déjà utilisé pour un autre compte.";
            }
            
            if(empty($messages["errors"])) {
                $pdo = new PDO("mysql:host=127.0.0.1;dbname=willeb_cl","root","");
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $pdo->exec("SET NAMES UTF8");

                do {
                    $id = uniqid();
                } while($pdo->prepare("SELECT `id` FROM `user` WHERE `id` = $id") > 0);

                $this->_user->addUserConnection($id, $data["email"], $data["login"], $data["password"]);
                
                $messages["success"] = ["Tu as été enregistré avec succès !"];
            }
        }
        
        return $messages;
    }
    
    
//*****2. Log in*****//
    public function loginForm(array $data) {
        if($_POST["connect"]) {
            if(empty($data["login"]) || empty($data["password"])) {
                $messages["errors"][] = "Veuilles remplir tous les champs.";
            }

            else { 
                $exist = $this->_user->findUserByLogin($data["login"]);
                
                if(!$exist) {
                    $messages["errors"][] = "Le pseudo est invalide.";
                }
                
                else if (password_verify($data["password"], $exist["password"])) {
                    Session::setUserSession($exist);
                    (isset($data["rememberMe"])) ? Cookie::setCookie($data) : Cookie::deleteCookie($data);
                }
                
                else {
                    $messages["errors"][] = "Le mot de passe est invalide.";
                }
            }
            
            if(empty($messages["errors"])) {
                $messages["success"] = ["Bonjour, ".$data["login"]];
            }
        }

        return $messages;
    }

//*****END OF THE CLASS*****//      
}