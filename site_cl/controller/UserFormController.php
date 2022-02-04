<?php

namespace App\controller;

use App\model\{User};
use App\core\{Cookie, Session};
use \PDO;

class UserFormController {

    public const LOGIN_REGEX = "/^[\p{L}0-9\-_]+$/ui";
    
    protected User $_user;

    public function __construct(User $user) {
        $this->_user = $user;
    }

//*****A. Registration*****//
    public function registrationForm(array $data) {
        if($_POST["register"]) {
            $registrationMsg = [];
            
            if(empty($data["email"]) || empty($data["login"]) || empty($data["password"]) || empty($data["confirmPassword"])) {
                $registrationMsg["errors"][] = "Veuilles remplir tous les champs.";
            }
            
            if(!empty($data["email"]) && !filter_var($data["email"], FILTER_VALIDATE_EMAIL)) {
                $registrationMsg["errors"][] = "Le format de l'adresse électronique est invalide.";
            }
            
            if(!empty($data["login"]) && strlen($data["login"]) < 3 || strlen($data["login"]) > 10) {
                $registrationMsg["errors"][] = "Ton pseudo doit contenir entre trois et dix caractères.";
            }
            
            if(!empty($data["login"]) && !preg_match(self::LOGIN_REGEX, $data["login"])) {
                $registrationMsg["errors"][] = "Caractères autorisés pour le pseudo : lettres, chiffres, tirets et underscores.";
            }
            
            if ($data["password"] !== $data["confirmPassword"] || $data["confirmPassword"] !== $data["password"]) {
                $registrationMsg["errors"][] = "Le mot de passe et sa confirmation doivent correspondre.";
            }
            
            $email = $this->_user->findEmail($data["email"]);

            if($email) {
                $registrationMsg["errors"][] = "Cette adresse électronique est déjà utilisée pour un autre compte.";
            }
            
            $login = $this->_user->findLogin($data["login"]);
        
            if($login) {
                $registrationMsg["errors"][] = "Ce pseudo est déjà utilisé pour un autre compte.";
            }

            if(!isset($data["acceptRules"]) || !isset($data["acceptPolicy"])) {
                $registrationMsg["errors"][] = "Veuilles accepter le règlement général et la politique de confidentialité.";
            }
            
            if(empty($registrationMsg["errors"])) {
                $key = "";
                $keyLength = 15;

                for($i = 1; $i < $keyLength; $i ++) {
                   $key .= mt_rand(0, 9);
                }

                $pdo = new PDO("mysql:host=127.0.0.1;dbname=willeb_cl","root","");
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $pdo->exec("SET NAMES UTF8");

                do {
                    $id = uniqid();
                } while($pdo->prepare("SELECT `id` FROM `user` WHERE `id` = $id") > 0);

                $this->_user->addUser($id, $data["email"], $data["login"], $data["password"], $key);
                
                $registrationMsg["success"] = ["Tu as été enregistré avec succès !"];

                $headers = "MIME-Version: 1.0\r\n";
                $headers .= 'From:"[VOUS]"<votremail@mail.com>'."\n";
                $headers .= 'Content-Type:text/html; charset="uft-8"'."\n";
                $headers .= 'Content-Transfer-Encoding: 8bit';

                $message ='
                <html>
                <body>
                    <div align="center">
                        <a href="http://localhost/site_cl/index.php?p=accountConfirmation&login='.urlencode($data['login']).'&key='.$key.'">Confirme la création de ton compte sur le site de la CL des xxx !</a>
                    </div>
                </body>
                </html>
                ';

                mail($data["email"], "Confirmation de compte", $message, $headers);
            }

            return $registrationMsg;
        }
    }

//*****B. Account confirmation*****//
public function accountConfForm() {
    if($_POST["confirmRegistration"]) {
        if(isset($_GET["login"], $_GET["key"]) AND !empty($_GET["login"]) AND !empty($_GET["key"])) {
            $accountConfMsg = [];
            $login = htmlspecialchars(urldecode($_GET["login"]));
            $key = htmlspecialchars($_GET["key"]);

            $pdo = new PDO("mysql:host=127.0.0.1;dbname=willeb_cl","root","");
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->exec("SET NAMES UTF8");

            $query = $pdo->prepare("SELECT `login`, `confirm_key`, `account_confirmed` FROM `user`
                                    WHERE `login` = :login AND `confirm_key` = :key");

            $query->execute([":login" => $login, ":key" => $key]);

            $userConfirm = $query->rowCount();

            if($userConfirm == 1) {
                $confirmInfo = $query->fetch();

                if($confirmInfo["account_confirmed"] == 0) {
                    $sql = $pdo->prepare("UPDATE `user` SET `account_confirmed` = 1 WHERE `login` = :login AND `confirm_key` = :key");
                    
                    $sql->execute([":login" => $login, ":key" => $key]);
                    
                    $accountConfMsg["success"] = ["Ta confirmation a été prise en compte !"];

                    $que = $pdo->prepare("SELECT `email` from `user` WHERE `login` = :login AND `confirm_key` = :key");

                    $que->execute([":login" => $login, ":key" => $key]);

                    $email = $que->fetchColumn();

                    $headers = "MIME-Version: 1.0\r\n";
                    $headers .= 'From:"[VOUS]"<votremail@mail.com>'."\n";
                    $headers .= 'Content-Type:text/html; charset="uft-8"'."\n";
                    $headers .= 'Content-Transfer-Encoding: 8bit';

                    $message = "Bonjour, tu as confirmé la création de ton compte. Bienvenue dans l'espace communautaire de la CL des xxx !";

                    mail($email, "Création de compte confirmée", $message, $headers);
                }

                else {
                    $accountConfMsg["errors"][] = "Tu as déjà confirmé la création de ton compte !";
                }
            }

            else {
                $accountConfMsg["errors"][] = "Cet utilisateur n'existe pas !";
            }

            // var_dump($_GET);

            return $accountConfMsg;
        }

        else {
            die("Hacking attempt!");
        }
    }
}

//*****C. Connection*****//
    public function connectionForm(array $data) {
        if($_POST["connect"]) {
            $connectionMsg = [];

            if(empty($data["login"]) || empty($data["password"])) {
                $connectionMsg["errors"][] = "Veuilles remplir tous les champs.";
            }

            else { 
                $exist = $this->_user->findLogin($data["login"]);

                    if($exist["account_confirmed"] == 0) {
                        $connectionMsg["errors"][] = "Tu n'as pas validé la création de ton compte. Pour ce faire, vérifie tes emails et clique sur le lien de confirmation. Pense à vérifier ton dossier de courrier indésirable.";
                    }

                    else {
                    
                    if(!$exist) {
                        $connectionMsg["errors"][] = "Le pseudo est invalide.";
                    }
                    
                    elseif(password_verify($data["password"], $exist["password"])) {
                        Session::setUserSession($exist);
                        (isset($data["rememberMe"])) ? Cookie::setCookie($data) : Cookie::deleteCookie($data);
                    }
                    
                    else {
                        $connectionMsg["errors"][] = "Le mot de passe est invalide.";
                    }
                }
            }
            
            if(empty($connectionMsg["errors"])) {
                $connectionMsg["success"] = ["Bonjour, ".$data["login"]];
            }

            return $connectionMsg;
        }
    }

//****D. Email modification*****//
    public function emailForm(array $data) {
        $emailMsg = [];
        
        if($_POST["emailChange"]) {
            $newEmail = $data["email"];
            $confirmEmail = $data["confirmEmail"];
            $currentEmail = $data["currentEmail"];

            if(Session::online() && !Session::admin()) {
                $id = $_SESSION["user"]["id"];
                $exist = $this->_user->findEmail($_SESSION["user"]["email"]);
                
                if(!empty($currentEmail) && !empty($newEmail) && !empty($confirmEmail)) {
                    if($currentEmail == $exist["email"]) {
                        if($newEmail == $confirmEmail) {
                            $email = $this->_user->findEmail($newEmail);
                        
                            if(!$email) {
                                if(!filter_var($newEmail, FILTER_VALIDATE_EMAIL) || !filter_var($confirmEmail, FILTER_VALIDATE_EMAIL)) {
                                    $emailMsg["errors"][] = "Le format de la nouvelle adresse électronique est invalide.";
                                }
                                
                                else {
                                    // $update = $this->_user->updateEmail($id, $newEmail);
                                    $emailMsg["success"] = ["Ton adresse électronique a été modifiée avec succès."];

                                    $message = "Bonjour, suite à ta demande, ton adresse électronique a été modifiée. Si tu n'es pas à l'origine de cette opération, contacte-moi.";
                                    $headers = 'Content-Type: text/plain; charset="utf-8"'." ";

                                    if(mail($exist["email"], "Modification d'adresse électronique", $message, $headers)) {
                                        $emailMsg["success"] = ["Mail de confirmation envoyé."]; 
                                    }
                                    
                                    else {
                                        $emailMsg["errors"][] = "Une erreur s'est produite. Si cela se réitère, contacte-moi.";
                                    }
                                }                             
                            }
                            
                            else {
                                $emailMsg["errors"][] = "Cette adresse électronique est déjà utilisée pour un autre compte.";
                            }                           
                        }
                        
                        else {
                            $emailMsg["errors"][] = "La nouvelle adresse électronique et sa confirmation doivent correspondre.";
                        }
                    }
                    
                    else {
                        $emailMsg["errors"][] = "L'adresse électronique actuelle est invalide.";
                    }     
                }
            
                else {
                    $emailMsg["errors"][] = "Veuilles remplir tous les champs.";
                }
            }

            elseif(Session::online() && Session::admin()) {
                $id = $_GET["userId"];
                $exist = $this->_user->findUserById($id);

                $trueId = $exist["id"];
                $trueEmail = $exist["email"];

                if($id != $trueId || $id == null || $currentEmail != $trueEmail || $currentEmail == null) {
                    die("Hacking attempt!");
                }
            
                else {
                    if(!empty($newEmail) && !empty($confirmEmail)) {
                        if($newEmail == $confirmEmail) {
                            $email = $this->_user->findEmail($newEmail);

                            if(!$email) {
                                if(!filter_var($newEmail, FILTER_VALIDATE_EMAIL) || !filter_var($confirmEmail, FILTER_VALIDATE_EMAIL)) {
                                    $emailMsg["errors"][] = "Le format de la nouvelle adresse électronique est invalide.";
                                }

                                else {
                                    // $update = $this->_user->updateEmail($id, $newEmail);
                                    $emailMsg["success"] = ["L'adresse électronique a été modifiée avec succès."];

                                    $message = "Bonjour, suite à ta demande, ton adresse électronique a été modifiée. Si tu n'es pas à l'origine de cette demande auprès de moi, contacte-moi.";
                                    $headers = 'Content-Type: text/plain; charset="utf-8"'." ";

                                    if(mail($trueEmail, "Modification d'adresse électronique", $message, $headers)) {
                                        $emailMsg["success"] = ["Mail de confirmation envoyé."]; 
                                    }
                                    
                                    else {
                                        $emailMsg["errors"][] = "Une erreur s'est produite. Si cela se réitère, contacte-moi.";
                                    }
                                }                           
                            }

                            else {
                                $emailMsg["errors"][] = "Cette adresse électronique est déjà utilisée pour un autre compte.";
                            }                        
                        }

                        else {
                            $emailMsg["errors"][] = "La nouvelle adresse électronique et sa confirmation doivent correspondre.";                    
                        }
                    }

                    else {
                        $emailMsg["errors"][] = "Veuilles remplir tous les champs."; 
                    }
                }
            }

            return $emailMsg;
        }     
    }
    
//*****E. Login modification*****//
    public function loginForm(array $data) {
        $loginMesg = [];
        
        if($_POST["loginChange"]) {
            $newLogin = $data["login"];
            $confirmLogin = $data["confirmLogin"];
            $currentLogin = $data["currentLogin"];

            if(Session::online() && !Session::admin()) {
                $id = $_SESSION["user"]["id"];
                $exist = $this->_user->findLogin($_SESSION["user"]["login"]);
                
                if(!empty($currentLogin) && !empty($newLogin) && !empty($confirmLogin)) {
                    if($currentLogin == $exist["login"]) {
                        if($newLogin == $confirmLogin) {
                            $login = $this->_user->findLogin($newLogin);
                        
                            if(!$login) {
                                if(!preg_match(self::LOGIN_REGEX, $newLogin)) {
                                    $loginMesg["errors"][] = "Caractères autorisés pour le nouveau pseudo : lettres, chiffres, tirets et underscores.";
                                }

                                elseif(strlen($newLogin) < 3 || strlen($newLogin) > 10) {
                                    $loginMesg["errors"][] = "Le nouveau pseudo doit contenir entre trois et dix caractères.";
                                }
                                
                                else {
                                    // $update = $this->_user->updateLogin($id, $newLogin);
                                    $loginMesg["success"] = ["Ton pseudo a été modifié avec succès."];

                                    $message = "Bonjour, suite à ta demande, ton pseudo a été modifié. Si tu n'es pas à l'origine de cette opération, contacte-moi.";
                                    $headers = 'Content-Type: text/plain; charset="utf-8"'." ";

                                    if(mail($exist["email"], "Modification de pseudo", $message, $headers)) {
                                        $loginMesg["success"] = ["Mail de confirmation envoyé."]; 
                                    }
                                    
                                    else {
                                        $loginMesg["errors"][] = "Une erreur s'est produite. Si cela se réitère, contacte-moi.";
                                    }
                                }                             
                            }
                            
                            else {
                                $loginMesg["errors"][] = "Ce pseudo est déjà utilisé pour un autre compte.";
                            }                           
                        }
                        
                        else {
                            $loginMesg["errors"][] = "Le nouveau pseudo et sa confirmation doivent correspondre.";
                        } 
                    }
                    
                    else {
                        $loginMesg["errors"][] = "Le pseudo actuel est invalide.";
                    }     
                }
            
                else {
                    $loginMesg["errors"][] = "Veuilles remplir tous les champs.";
                }
            }

            elseif(Session::online() && Session::admin()) {
                $id = $_GET["userId"];
                $exist = $this->_user->findUserById($id);

                $trueId = $exist["id"];
                $trueLogin = $exist["login"];
                $email = $exist["email"];

                if($id != $trueId || $id == null || $currentLogin != $trueLogin || $currentLogin == null) {
                    die("Hacking attempt!");
                }
            
                else {
                    if(!empty($newLogin) && !empty($confirmLogin)) {
                        if($newLogin == $confirmLogin) {
                            $login = $this->_user->findLogin($newLogin);

                            if(!$login) {
                                if(!preg_match(self::LOGIN_REGEX, $newLogin)) {
                                    $loginMesg["errors"][] = "Caractères autorisés pour le nouveau pseudo : lettres, chiffres, tirets et underscores.";
                                }

                                elseif(strlen($newLogin) < 3 || strlen($newLogin) > 10) {
                                    $loginMesg["errors"][] = "Le nouveau pseudo doit contenir entre trois et dix caractères.";
                                }

                                else {
                                    // $update = $this->_user->updateLogin($id, $newLogin);
                                    $loginMesg["success"] = ["Le pseudo a été modifié avec succès."];

                                    $message = "Bonjour, suite à ta demande, ton pseudo a été modifié. Si tu n'es pas à l'origine de cette demande auprès de moi, contacte-moi.";
                                    $headers = 'Content-Type: text/plain; charset="utf-8"'." ";

                                    if(mail($email, "Modification de pseudo", $message, $headers)) {
                                        $loginMesg["success"] = ["Mail de confirmation envoyé."]; 
                                    }
                                    
                                    else {
                                        $loginMesg["errors"][] = "Une erreur s'est produite. Si cela se réitère, contacte-moi.";
                                    }
                                }                            
                            }

                            else {
                                $loginMesg["errors"][] = "Ce pseudo est déjà utilisé pour un autre compte.";
                            }                          
                        }

                        else {
                            $loginMesg["errors"][] = "Le nouveau pseudo et sa confirmation doivent correspondre.";
                        }    
                    }

                    else {
                        $loginMesg["errors"][] = "Veuilles remplir tous les champs.";
                    }
                }
            }

            return $loginMesg;
        }
    }
    
//*****F. Password modification*****//
    public function passwordForm(array $data) {        
        $passwordMsg = [];
        
        if($_POST["passwordChange"]) {
            $newPassword = $data["password"];
            $confirmPassword = $data["confirmPassword"];
            
            if(Session::online() && !Session::admin()) {
                $id = $_SESSION["user"]["id"];
                $exist = $this->_user->findLogin($_SESSION["user"]["login"]);
                $currentPassword = $data["currentPassword"];
                
                if(!empty($currentPassword) && !empty($newPassword) && !empty($confirmPassword)) {
                    if($currentPassword == password_verify($currentPassword, $exist["password"])) {
                        if($newPassword == $confirmPassword) {
                            // $update = $this->_user->updatePassword($id, $newPassword);
                            // $newPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                            $passwordMsg["success"] = ["Ton mot de passe a été modifié avec succès."];

                            $message = "Bonjour, suite à ta demande, ton mot de passe a été modifié. Si tu n'es pas à l'origine de cette opération, contacte-moi.";
                                $headers = 'Content-Type: text/plain; charset="utf-8"'." ";

                            if(mail($exist["email"], "Modification de mot de passe", $message, $headers)) {
                                $passwordMsg["success"] = ["Mail de confirmation envoyé."]; 
                            }
                            
                            else {
                                $passwordMsg["errors"][] = "Une erreur s'est produite. Si cela se réitère, contacte-moi.";
                            }
                        }
                        
                        else {
                            $passwordMsg["errors"][] = "Le nouveau mot de passe et sa confirmation doivent correspondre.";
                        }                        
                    }
                    
                    else {
                        $passwordMsg["errors"][] = "Le mot de passe actuel est invalide.";
                    }
                }
                
                else {
                    $passwordMsg["errors"][] = "Veuilles remplir tous les champs.";
                }  
            }

            elseif(Session::online() && Session::admin()) {
                $id = $_GET["userId"];

                $exist = $this->_user->findUserById($id);

                $trueId = $exist["id"];
                $mail = $exist["email"];

                if($id != $trueId || $id == null) {
                    die("Hacking attempt!");
                }
            
                else {
                    if(!empty($newPassword) && !empty($confirmPassword)) {
                            if($newPassword == $confirmPassword) {
                                // $update = $this->_user->updatePassword($id, $newPassword);
                                // $newPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                                $passwordMsg["success"] = ["Le mot de passe a été modifié avec succès."];

                                $message = "Bonjour, suite à ta demande, ton mot de passe a été modifié. Si tu n'es pas à l'origine de cette demande auprès de moi, contacte-moi.";
                                $headers = 'Content-Type: text/plain; charset="utf-8"'." ";

                                if(mail($mail, "Modification de mot de passe", $message, $headers)) {
                                    $passwordMsg["success"] = ["Mail de confirmation envoyé."]; 
                                }
                                
                                else {
                                    $passwordMsg["errors"][] = "Une erreur s'est produite. Si cela se réitère, contacte-moi.";
                                }
                            }
                            
                            else {
                                $passwordMsg["errors"][] = "Le nouveau mot de passe et sa confirmation doivent correspondre.";
                            }
                    }
                
                    else {
                        $passwordMsg["errors"][] = "Veuilles remplir tous les champs.";
                    }
                }
            }

            return $passwordMsg;
        }
    }

//*****G. Login recovery*****//
public function forgotLoginForm(array $data) {
    $forgotLogMsg = [];
    
    if($_POST["recoverLogin"]) {
        if(!$data["mail"]) {
            $forgotLogMsg["errors"][] = "Veuilles remplir le champ.";
        }
        
        else {
            $exist = $this->_user->findEmail($data["mail"]);
        
            if(!$exist) {
                $forgotLogMsg["errors"][] = "L'email n'existe pas.";
            }
            
            else {
                $login = $exist["login"];

                $message = "Bonjour, suite à ta demande, voici ton pseudo : $login. Pense à conserver cette information dans un endroit sûr.";
                $headers = 'Content-Type: text/plain; charset="utf-8"'." ";

                if(mail($_POST["mail"], "Pseudo oublié", $message, $headers)) {
                    $forgotLogMsg["success"] = ["Pseudo envoyé."]; 
                }
                
                else {
                    $forgotLogMsg["errors"][] = "Une erreur s'est produite. Si cela se réitère, contacte-moi.";
                }
            }
        }

        return $forgotLogMsg;
    }
}

//*****H. Password recovery*****//
public function forgotPasswordForm(array $data) {        
    $forgotPassMsg = [];
    
    if($_POST["recoverPassword"]) {
        if(!$data["mail"]) {
            $forgotPassMsg["errors"][] = "Veuilles remplir le champ.";
        }
        
        else {
            $exist = $this->_user->findEmail($data["mail"]);
        
            if(!$exist) {
                $forgotPassMsg["errors"][] = "L'email n'existe pas.";
            }
            
            else {
                $password = uniqid();

                $message = "Bonjour, suite à ta demande, voici ton nouveau mot de passe : $password. Pense à le modifier dans ton espace.";
                $headers = 'Content-Type: text/plain; charset="utf-8"'." ";

                if(mail($_POST["mail"], "Mot de passe oublié", $message, $headers)) {
                    // $this->_user->updatePassword($exist["id"], $password);
                    // $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                    $forgotPassMsg["success"] = ["Nouveau mot de passe envoyé."]; 
                }
                
                else {
                    $forgotPassMsg["errors"][] = "Une erreur s'est produite. Si cela se réitère, contacte-moi.";
                }
            }
        }

        return $forgotPassMsg;
    }
}

//*****I. Role modification*****//
    public function roleForm(array $data) {
        $roleMsg = [];
          
        if($_POST["roleChange"]) {
            $id = $_GET["userId"];

            // $doc = new \DOMDocument;
            // $doc->loadHTML("<option>[choix du r&ocirc;le]</option>
            //                 <option>Visiteur non membre de la CL</option>
            //                 <option>Membre de la CL sans droits d'administration</option>
            //                 <option>Membre de la CL avec droits d'administration</option>");

            // $options = $doc->getElementsByTagName("option");

            // $nullOption = $options->item(0)->nodeValue;
            // $optionOne = $options->item(1)->nodeValue;
            // $optionTwo = $options->item(2)->nodeValue;
            // $optionThree = $options->item(3)->nodeValue;

            // echo "$nullOption, <br> $optionOne, <br> $optionTwo, <br> $optionThree <br>";

            $optionOne = ["0" => "Visiteur non membre de la CL"];
            $optionTwo = ["1" => "Membre de la CL sans droits d'administration"];
            $optionThree = ["2" => "Membre de la CL avec droits d'administration"];

            // $value = $data["role"];
            // $textOne = $optionOne[$value];
            // $textTwo = $optionTwo[$value];
            // $textThree = $optionThree[$value];
            // $textOne = array_search("Visiteur non membre de la CL", $optionOne);
            // $textTwo = array_search("Membre de la CL sans droits d'administration", $optionOne);
            // $textThree = array_search("Membre de la CL avec droits d'administration", $optionOne);

            $textOne = $optionOne["0"];
            $textTwo = $optionTwo["1"];
            $textThree = $optionThree["2"];

            // var_dump($textOne);
            // echo $optionOne["0"];

            $pdo = new PDO("mysql:host=127.0.0.1;dbname=willeb_cl","root","");
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->exec("SET NAMES UTF8");

            $query = $pdo->prepare("SELECT `id` FROM `user` WHERE `id` = :id");

            $query->execute([":id" => $id]);

            $trueId = $query->fetchColumn();

            if($id != $trueId || $id == null) {
                die("Hacking attempt!");
            }
        
            else {
                $userInfo = $this->_user->findUserById($id);

                if($data["role"] == "0") {
                    if(!str_contains($textOne, "Visiteur non membre de la CL")) {
                        die("Hacking attempt!");
                    }

                    // else {
                        // $updateRole = $this->_user->updateRole($id, $data["role"]);
                        $roleMsg["success"] = ["L'utilisateur ".$userInfo["login"]." n'est désormais ni membre de la CL ni administrateur du site."];
                    // }
                }

                elseif($data["role"] == "1") {
                    // $updateRole = $this->_user->updateRole($id, $data["role"]);
                    $roleMsg["success"] = ["L'utilisateur ".$userInfo["login"]." est désormais membre de la CL sans droits d'administration du site."];
                }

                elseif($data["role"] == "2") {
                    // $updateRole = $this->_user->updateRole($id, $data["role"]);
                    $roleMsg["success"] = ["L'utilisateur ".$userInfo["login"]." est désormais membre de la CL avec droits d'administration du site."];
                }

                elseif($data["role"] == "") {
                    // if(!str_contains($nullValue, $nullOption)) {
                    //     die("Hacking attempt!");
                    // }

                    // else {
                    $roleMsg["errors"] = ["Veuilles attribuer un rôle avant de valider le formulaire."];
                    // }
                }

                else {
                    die("Hacking attempt!");
                }

                // if($data["role"] == "" && str_contains($nullOption, "Visiteur non membre de la CL") || str_contains($nullOption, "Membre de la CL sans droits d'administration") || str_contains($nullOption, "Membre de la CL avec droits d'administration")) {
                //     die("Hacking attempt!");
                // }

                // if($data["role"] == "0" && str_contains($optionOne, "[choix du rôle]") || str_contains($optionOne, "Membre de la CL sans droits d'administration") || str_contains($optionOne, "Membre de la CL avec droits d'administration")) {
                //     die("Hacking attempt!");
                // }

                // elseif($data["role"] == "1" && str_contains($optionTwo, "[choix du rôle]") || str_contains($optionTwo, "Visiteur non membre de la CL") || str_contains($optionTwo, "Membre de la CL avec droits d'administration")) {
                //     die("Hacking attempt!");
                // }

                // elseif($data["role"] == "2" && str_contains($optionThree, "Membre de la CL avec droits d'administration")) {
                //     die("Hacking attempt!");
                // }

                // if($data["role"] == "" && str_contains($nullOption, "Visiteur non membre de la CL") || str_contains($nullOption, "Membre de la CL sans droits d'administration") || str_contains($nullOption, "Membre de la CL avec droits d'administration")) {
                //     die("Hacking attempt!");
                // }

                if($data["role"] == "0" && $optionOne["0"] != "Visiteur non membre de la CL") {
                    die("Hacking attempt!");
                }

                elseif($data["role"] == "1" && !str_contains($textTwo, "Membre de la CL sans droits d'administration")) {
                    die("Hacking attempt!");
                }

                elseif($data["role"] == "2" && !str_contains($textThree, "Membre de la CL avec droits d'administration")) {
                    die("Hacking attempt!");
                }

                // if($data["role"] == "0" && !str_contains($textOne, "Visiteur non membre de la CL")) {
                //     die("Hacking attempt!");
                // }

                // elseif($data["role"] == "1" && !str_contains($textTwo, "Membre de la CL sans droits d'administration")) {
                //     die("Hacking attempt!");
                // }

                // elseif($data["role"] == "2" && !str_contains($textThree, "Membre de la CL avec droits d'administration")) {
                //     die("Hacking attempt!");
                // }
            }

            echo $optionOne["0"];

            return $roleMsg;
        }
    }

//*****J. User deletion*****//
    public function userDeletionForm($id) {  
        $userDelMsg = [];
        
        if(isset($_POST["deleteUser"])) {
            $id = $_GET["userId"];

            $pdo = new PDO("mysql:host=127.0.0.1;dbname=willeb_cl","root","");
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->exec("SET NAMES UTF8");

            $query = $pdo->prepare("SELECT `id` FROM `user` WHERE `id` = :id");

            $query->execute([":id" => $id]);

            $trueId = $query->fetchColumn();

            if($id != $trueId || $id == null) {
                die("Hacking attempt!");
            }

            else {
                $findUser = $this->_user->findUserById($id);
                // $deleteUser = $this->_user->deleteUser($id);
                $userDelMsg["success"] = ["L'utilisateur ".$findUser["login"]." a été supprimé avec succès."];
            }

            return $userDelMsg;
        }
    }

//*****END OF THE CLASS*****// 
}