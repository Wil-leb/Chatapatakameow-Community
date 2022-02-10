<?php

namespace App\controller;

use App\controller\{AlbumFormController, AlbumModifController, CommentFormController, ContactMessageController, ImageDisplayController,
                    UserFormController};
use App\core\{Cookie, Https, Session};
use App\model\{Album, Comments, ContactMessage, User, Votes};
use \PDO;

class FrontController {

//*****A. Home view*****//
    public function home() {
        $title = "xxx - Accueil";
        
        $user = new User();
        $users = $user->findAllUsers();
        
        $this->render("home/home", ["title" => $title, "users" => $users]);                                    
    }
    
//*****B. Admin views*****//

//*****i. Dashboard*****//
    public function dashboard() {
        $title = "xxx - Tableau de bord administrateur";
        
        if(!Session::admin()) {
            header("Location: index.php?p=home");
        }
        
        else {
            $this->render("admin/dashboard", ["title" => $title]);
        }
    }
    
//*****ii. Album dashboard*****//
    public function albumDashboard() {
        $title = "xxx - Tableau de bord des albums";
        
        $album = new Album();
        $albums = $album->findAllAlbums();
        
        if(!Session::admin()) {
            header("Location: index.php?p=home");
        }
        
        else {

            if(isset($_POST["deleteAlbum"])) {
                $form = new AlbumFormController(new Album());
                $deleteMessages = $form->albumDeletionForm($_POST);
                // header("Refresh: 2");
            }

            $this->render("admin/albumDashboard", [
                                                    "title" => $title,
                                                    "albums" => $albums,
                                                    "deleteMessages" => ($deleteMessages) ?? null
                                                    ]);
        }
    }

//*****iii. Comment dashboard*****//
    public function commentDashboard() {
        $title = "xxx - Tableau de bord des commentaires";

        $comment = new Comments();
        $comments = $comment->findAllComments();
        $answers = $comment->findAllAnswers();

        if(!Session::admin()) {
            header("Location: index.php?p=home");
        }
        
        else {
            if(isset($_POST["adminDelComment"])) {
                $form = new CommentFormController(new Comments());
                $commentDelMsg = $form->commentDeletionForm($_POST);
                // header("Refresh: 2");
            }

            if(isset($_POST["adminDelAnswer"])) {
                $form = new CommentFormController(new Comments());
                $answerDelMsg = $form->answerDeletionForm($_POST);
                // header("Refresh: 2");
            }
            
            $this->render("admin/commentDashboard", [
                                                    "title" => $title,
                                                    "comments" => $comments,
                                                    "answers" => $answers,
                                                    "commentDelMsg" => ($commentDelMsg) ?? null,
                                                    "answerDelMsg" => ($answerDelMsg) ?? null
                                                    ]);
        }
    }

//*****iv. User modification*****//
    public function modifyUser() {
        $title = "xxx - Modification d'utilisateur";
        
        if(!array_key_exists("userId", $_GET)) {
            header("Location: index.php?p=userDashboard");
            exit;
        }

        else {
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
                $user = new User();
                $findUser = $user->findUserById($id);
                
                if(isset($_POST["emailChange"]) && $_POST["emailChange"] == "Confirmer le changement d'email") {
                    $form = new UserFormController(new User());
                    $emailMsg = $form->emailForm($_POST);

                    // if(!empty($emailMessages["success"])) {
                    //     header("Refresh: 2.5");
                    // }
                }
                
                if(isset($_POST["loginChange"]) && $_POST["loginChange"] == "Confirmer le changement de pseudo") {
                    $form = new UserFormController(new User());
                    $loginMsg = $form->loginForm($_POST);
                    
                    // if(!empty($loginMessages["success"])) {
                    //     header("Refresh: 3");
                    // }
                }
                
                if(isset($_POST["passwordChange"]) && $_POST["passwordChange"] == "Confirmer le changement de mot de passe") {
                    $form = new UserFormController(new User());
                    $passwordMsg = $form->passwordForm($_POST);
                    
                    // if(!empty($passwordMessages["success"])) {
                    //     header("Refresh: 3.5");
                    // }
                }
                
                if(isset($_POST["roleChange"]) && $_POST["roleChange"] == "Confirmer le changement de rôle") {
                    $form = new UserFormController(new User());
                    $roleMsg = $form->roleForm($_POST);

                    // if(!empty($roleMessages["success"])) {
                    //     header("Refresh: 4");
                    // }
                }
                
                $this->render("admin/modifyUser", [
                                                "title" => $title,
                                                "findUser" => $findUser,
                                                "emailMsg" => ($emailMsg) ?? null,
                                                "loginMsg" => ($loginMsg) ?? null,
                                                "passwordMsg" => ($passwordMsg) ?? null,
                                                "roleMsg" => ($roleMsg) ?? null
                                                ]);
            }
        }
    }

//*****v. User dashboard*****//
    public function userDashboard() {
        $title = "xxx - Tableau de bord des utilisateurs";

        // $pdo = new PDO("mysql:host=127.0.0.1;dbname=willeb_cl","root","");
        // $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // $pdo->exec("SET NAMES UTF8");
        
        $user = new User();
        $users = $user->findAllUsers();

        // $countUser = new PaginationController();
        // $totalUsers = $countUser->countUsers();

        if(!Session::admin()) {
            header("Location: index.php?p=home");
        }
        
        else {

            // if(isset($_GET["page"]) && !empty($_GET["page"])) {
            //     $currentPage = (int) strip_tags($_GET["page"]);
            // }
            
            // else {
            //     $currentPage = 1;
            // }
            
            // $countUSer = $pdo->prepare("SELECT COUNT(*) AS `user_nb` FROM `user`");
            
            // $countUSer->execute();
            
            // $result = $countUSer->fetch();
            
            // $userNumber = (int) $result["user_nb"];
            
            // $perPage = 10;
            
            // $pages = ceil($userNumber / $perPage);
            
            // $firstUser = ($currentPage * $perPage) - $perPage;
            
            // $offset = $pdo->prepare("SELECT * FROM `user` LIMIT :firstUser, :perPage");

            // $offset->bindValue(":firstUser", $firstUser, PDO::PARAM_INT);
            // $offset->bindValue(":perPage", $perPage, PDO::PARAM_INT);
            
            // $offset->execute();
            
            // $allUsers = $offset->fetchAll(\PDO::FETCH_ASSOC);
            
            if(isset($_POST["deleteUser"])) {
                $form = new UserFormController(new User());
                $userDelMsg = $form->userDeletionForm($_POST);
                // header("Refresh: 2");
            }
            
            $this->render("admin/userDashboard", [
                                                "title" => $title,
                                                "users" => $users,
                                                // "page" => $pages,
                                                // "currentPage" => $currentPage,
                                                "userDelMsg" => ($userDelMsg) ?? null
                                                ]);
        }
    }
    
//*****C. Connection views*****//

//*****i. Account*****//
    public function account() {
        $title = "xxx - Compte";
        
        if(!Session::online()) {
            header("Location: index.php?p=home");
        }
        
        elseif(Session::admin()) {
            header("Location: index.php?p=dashboard");
        }
        
        else {
            if(isset($_POST["emailChange"]) && $_POST["emailChange"] == "Confirmer le changement d'email") {
                $form = new UserFormController(new User());
                $emailMsg = $form->emailForm($_POST);

                // if(!empty($emailMessages["success"])) {
                //     header("Refresh: 2");
                // }
            }
            
            if(isset($_POST["loginChange"]) && $_POST["loginChange"] == "Confirmer le changement de pseudo") {
                $form = new UserFormController(new User());
                $loginMsg = $form->loginForm($_POST);
                
                // if(!empty($loginMessages["success"])) {
                //     header("Refresh: 2.5");
                // }       
            }
            
            if(isset($_POST["passwordChange"]) && $_POST["passwordChange"] == "Confirmer le changement de mot de passe") {
                $form = new UserFormController(new User());
                $passwordMsg = $form->passwordForm($_POST);
                
                // if(!empty($passwordMessages["success"])) {
                //     header("Refresh: 3");
                // }    
            }

            if(isset($_POST["deleteAlbum"])) {
                $form = new AlbumFormController(new Album());
                $deleteMessages = $form->albumDeletionForm($_POST);
                
                // if(!empty($deleteMessages["success"])) {
                //     header("Refresh: 3.5");
                // }   
            }
            
            $album = new Album();
            $albums = $album->findUserAlbums($_SESSION["user"]["id"]);
            
            $this->render("connection/account", [
                                                "title" => $title,
                                                "emailMsg" => ($emailMsg) ?? null,
                                                "loginMsg" => ($loginMsg) ?? null,
                                                "passwordMsg" => ($passwordMsg) ?? null,
                                                "deleteMessages" => ($deleteMessages) ?? null,
                                                "albums" => $albums
                                                ]);
        }
    }

//*****ii. Account confirmation*****//
    public function accountConfirmation() {
        $title = "xxx - Confirmation de compte";

        $login = $_GET["login"];
        $key = $_GET["key"];

        if(isset($_POST["confirmRegistration"])) {
            $form = new UserFormController(new User());
            $accountConfMsg = $form->accountConfForm($_POST);
        }

            // if(!empty($accountConfMsg["success"])) {
            //     header("Refresh: 2");
            // }
        
            
        $this->render("connection/accountConfirmation", ["title" => $title,
                                                        "login" => $login,
                                                        "key" => $key,
                                                        "accountConfMsg" => ($accountConfMsg) ?? null]);
    }

//*****iii. Login forgotten*****//
    public function forgotLogin() {

        $title = "xxx - Récupération de pseudo";

        if($_POST) {
            $form = new UserFormController(new User()); 
            $forgotLogMsg = $form->forgotLoginForm($_POST);
        }

        $this->render("connection/forgotLogin", ["title" => $title, "forgotLogMsg" => ($forgotLogMsg) ?? null]);
    }

//*****iv. Password forgotten*****//
    public function forgotPassword() {

        $title = "xxx - Récupération de mot de passe";

        if($_POST) {
            $form = new UserFormController(new User()); 
            $forgotPassMsg = $form->forgotPasswordForm($_POST);
        }

        $this->render("connection/forgotPassword", ["title" => $title, "forgotPassMsg" => ($forgotPassMsg) ?? null]);
    }
    
//*****v. Log in*****//
    public function login() {
        $title = "xxx - Connexion";
        
        (Session::online()) ? Https::redirect("index.php") : "" ;
        
        if($_POST) {
            $form = new UserFormController(new User());
            $connectionMsg = $form->connectionForm($_POST);
        }
        
        $this->render("connection/login", ["title" => $title, "connectionMsg" => ($connectionMsg) ?? null, "cookie" => new Cookie]);
    }
    
//*****vi. Log out*****//
    public function logout() {
        Session::disconnect();
        header("Location: index.php");
        exit;
    }
    
//*****vii. Registration*****//
    public function register() {
        $title = "xxx - Inscription";
        
        (Session::online()) ? Https::redirect("index.php") : "" ; 
        
        if($_POST) {
            $form = new UserFormController(new User());
            $registrationMsg = $form->registrationForm($_POST);
            // header("Location: index.php?p=register");
        }
        
        $this->render("connection/register", ["title" => $title, "registrationMsg" => ($registrationMsg) ?? null]);
    }

//*****D. Contact view*****//
    public function contactForm() {
        $title = "xxx - Contact";
        
        if(Session::admin()) {
            header("Location: index.php?p=dashboard");
        }
        
        else {
            
            if($_POST) {
                $form = new ContactMessageController(new ContactMessage());
                $messages = $form->contactForm($_POST);
                header("Refresh: 2");
            }
        
            $this->render("contact/contactForm", ["title" => $title, "messages" => ($messages) ?? null]);
        }
    }
    
//*****E. Album views*****//

//*****i. Album publishers*****//

public function albumPublishers() {
    $title = "xxx - Nos auteurs";

    $user = new User();
    $users = $user->findAllUsers();
    
    $this->render("albums/albumPublishers", ["title" => $title, "users" => $users]);
}

//*****ii. Albums*****//
    public function albums() {
        if(!array_key_exists("albumId", $_GET)) {
            header("Location: index.php?p=home");
            exit;
        }

        else {
            $title = "xxx - Albums";
            
            $id = $_GET["albumId"];

            $pdo = new PDO("mysql:host=127.0.0.1;dbname=willeb_cl","root","");
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->exec("SET NAMES UTF8");

            $query = $pdo->prepare("SELECT `id` FROM `album` WHERE `id` = :id");

            $query->execute([":id" => $id]);

            $trueId = $query->fetchColumn();

            if($id != $trueId || $id == null) {
                die("Hacking attempt!");
            }

            else {
                $album = new Album();
                $albums = $album->findAlbumById($_GET["albumId"]);

                $vote = new Votes();

                $opinion = null;

                if(isset($_POST["likeAlb"]) || isset($_POST["dislikeAlb"])
                || isset($_POST["likeComm"]) || isset($_POST["dislikeComm"])
                || isset($_POST["likeAnsw"]) || isset($_POST["dislikeAnsw"])) {
                    $form = new CommentFormController(new Comments());
                    $form->voteForm($_POST);
                    $opinion = $vote->getClass($_POST["voteValue"]);
                }

                if(isset($_POST["postComment"]) && $_POST["postComment"] == "Publier le commentaire") {
                    $form = new CommentFormController(new Comments());
                    $commentMsg = $form->commentForm($_POST);
                    // header("Refresh: 2");
                }

                if(isset($_POST["postAnswer"]) && $_POST["postAnswer"] == "Publier la réponse") {
                    $form = new CommentFormController(new Comments());
                    $answerMsg = $form->answerForm($_POST);
                    // header("Refresh: 2");
                }

                if(isset($_POST["changeComment"]) && $_POST["changeComment"] == "Modifier le commentaire") {
                    $form = new CommentFormController(new Comments());
                    $comModifMsg = $form->commentModifForm($_POST);
                    // header("Refresh: 2");
                }

                if(isset($_POST["changeAnswer"]) && $_POST["changeAnswer"] == "Modifier la réponse") {
                    $form = new CommentFormController(new Comments());
                    $answModifMsg = $form->answerModifForm($_POST);
                    // header("Refresh: 2");
                }
                
                if(isset($_POST["deleteComment"])) {
                    $form = new CommentFormController(new Comments());
                    $commentDelMsg = $form->commentDeletionForm($_POST);
                    // header("Refresh: 2");
                }

                if(isset($_POST["deleteAnswer"])) {
                    $form = new CommentFormController(new Comments());
                    $answerDelMsg = $form->answerDeletionForm($_POST);
                    // header("Refresh: 2");
                }
                
                $this->render("albums/albums", [
                                        "title" => $title,
                                        "albums" => $albums,
                                        "opinion" => $opinion,
                                        "commentMsg" => ($commentMsg) ?? null,
                                        "answerMsg" => ($answerMsg) ?? null,
                                        "comModifMsg" => ($comModifMsg) ?? null,
                                        "answModifMsg" => ($answModifMsg) ?? null,
                                        "commentDelMsg" => ($commentDelMsg) ?? null,
                                        "answerDelMsg" => ($answerDelMsg) ?? null
                                        ]);
            }
        }
    }

//*****iii. Album addition*****//
    public function addAlbum() {
        $title = "xxx - Publication d'album";
        
        if(isset($_POST["postAlbum"])) {
            $form = new AlbumFormController(new Album());
            $addMessages = $form->albumAdditionForm($_POST);
            // header("Refresh: 2");
        }
        
        $this->render("albums/addAlbum", ["title" => $title, "addMessages" => ($addMessages) ?? null]);
    }

//*****iv. Album modification*****//
    public function modifyAlbum() {
        $title = "xxx - Modification d'album";
        
        if(!array_key_exists("albumId", $_GET)) {
            if(!Session::online()) {
                header("Location: index.php?p=home");
            }

            elseif(Session::admin()) {
                header("Location: index.php?p=albumDashboard");
            }
        }

        else {
            $id = $_GET["albumId"];

            $pdo = new PDO("mysql:host=127.0.0.1;dbname=willeb_cl","root","");
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->exec("SET NAMES UTF8");

            $query = $pdo->prepare("SELECT `id` FROM `album` WHERE `id` = :id");

            $query->execute([":id" => $id]);

            $trueId = $query->fetchColumn();

            if($id != $trueId || $id == null) {
                die("Hacking attempt!");
            }

            else {
                $album = new Album();
                $findAlbum = $album->findAlbumById($_GET["albumId"]);
                $currentCover = $album->findAlbumCover($_GET["albumId"]);
                $currentPictures = $album->findAlbumPictures($_GET["albumId"]);
                
                if(isset($_POST["albumChanges"]) && $_POST["albumChanges"] == "Confirmer la/les modification(s)") {
                    $form = new AlbumModifController(new Album());
                    $modifMessages = $form->descriptionModifForm($_POST);
                    // header("Refresh: 2");
                }

                if(isset($_POST["deletePicture"])) {
                    $form = new AlbumModifController(new Album());
                    $deleteMessages = $form->pictureDeletionForm($_POST);
                    // header("Refresh: 3");
                }
                
                if(isset($_POST["pictureChange"]) && $_POST["pictureChange"] == "Confirmer le remplacement d'image") {
                    $form = new AlbumModifController(new Album());
                    $replaceMessages = $form->pictureReplacementForm($_POST);
                    // header("Refresh: 2.5");
                }
                
                if(isset($_POST["pictureAddition"]) && $_POST["pictureAddition"] == "Confirmer l'ajout d'image(s)") {
                    $form = new AlbumModifController(new Album());
                    $extraPicMessages = $form->extraPicturesForm($_POST);
                    // header("Refresh: 3.5");
                }
                
                $this->render("albums/modifyAlbum", [
                                                    "title" => $title,
                                                    "findAlbum" => $findAlbum,
                                                    "currentCover" => $currentCover,
                                                    "currentPictures" => $currentPictures,
                                                    "modifMessages" => ($modifMessages) ?? null,
                                                    "deleteMessages" => ($deleteMessages) ?? null,
                                                    "replaceMessages" => ($replaceMessages) ?? null,
                                                    "extraPicMessages" => ($extraPicMessages) ?? null
                                                    ]);
            }
        }
    }
    
//*****F. Legal views*****//

//*****i. Cookies Page*****//
    public function cookies() {
        $title = "xxx - Politique des cookies";
        $this->render("legal/cookies", ["title" => $title]);
    }
    
//*****ii. Privacy Policy*****//
    public function privacyPolicy() {
        $title = "xxx - Politique de confidentialité";
        $this->render("legal/privacyPolicy", ["title" => $title]);
    }

//*****iii. Disclaimer*****//
    public function rules() {
        $title = "xxx - Règlement général";
        $this->render("legal/rules", ["title" => $title]);
    }
    
//*****G. Rendering the view's path*****//
    public function render(string $path, $array = []) {
        
        if(count($array) > 0) {
            foreach($array as $key => $value) {
                ${$key} = $value;
            }
        }
        
        $session = new Session;
        $https = new Https;

        $path = $path.".php";
        require "template/template.php";
    }

//*****END OF THE CLASS*****//
}