<?php

namespace App\controller;
use App\model\{Album, Comments, User, Votes};
use App\core\{Session};
use \PDO;

class CommentFormController {
        protected Comments $_comments;

        public const LOGIN_REGEX = "/^[\p{L}0-9\-_]+$/ui";
        public const COMMENT_REGEX = "/^[\p{L}\d\~\-_\/()\[\]{}@#\+\*=\^\%;,:.!?\'&\"\s]+$/ui";
        
        public function __construct(Comments $comments) {
                $this->_comments = $comments;
        }

//*****A. Comment addition*****//
        public function commentForm(array $data) {
                $commentMsg = [];

                if($_POST["postComment"]) {
                        $albumId = $_GET["albumId"];

                        $album = new Album();

                        $exist = $album->findAlbumById($albumId);

                        $title = $exist["title"];

                        if(!$data["email"] || !$data["commentLogin"] || !$data["comment"]) {
                                $commentMsg["errors"][] = "Veuilles remplir tous les champs.";
                        }

                        if($data["email"] && !filter_var($data["email"], FILTER_VALIDATE_EMAIL)) {
                                $commentMsg["errors"][] = "Le format de l'adresse électronique est invalide.";
                        }

                        if($data["commentLogin"] && !preg_match(self::LOGIN_REGEX, $data["commentLogin"])) {
                                $commentMsg["errors"][] = "Caractères autorisés pour le pseudo : lettres, chiffres, tirets et underscores.";
                        }

                        if($data["comment"] && !preg_match(self::COMMENT_REGEX, $data["comment"])) {
                                $commentMsg["errors"][] = 'Caractères autorisés pour le commentaire : lettres, chiffres, tilde, tirets, underscores, slash, parenthèses, crochets, accolades, arobases, dièses, signes "+", signes "=", astérisques, accents circonflexes, signes "%", point-virgules, virgules, doubles points, points, points d\'exclamation, points d\'interrogation, apostrophes, esperluettes, guillemets droits et espaces.';
                        }

                        if(!isset($data["acceptRules"]) || !isset($data["acceptPolicy"])) {
                                $commentMsg["errors"][] = "Veuilles accepter le règlement général et la politique de confidentialité.";
                        }

                        if(empty($commentMsg["errors"])) {
                                $pdo = new PDO("mysql:host=127.0.0.1;dbname=willeb_cl","root","");
                                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                $pdo->exec("SET NAMES UTF8");

                                $query = $pdo->prepare("SELECT `user_id` FROM `album` WHERE album.id = :albumId");

                                $query->execute([":albumId" => $albumId]);

                                $userId = $query->fetchColumn();

                                $req = $pdo->prepare("SELECT `email` FROM `user`
                                                        LEFT OUTER JOIN `album`
                                                        ON user.login = album.user_login
                                                        WHERE album.id = :albumId");
                                                        
                                $req->execute([":albumId" => $albumId]);

                                $email = $req->fetchColumn();

                                // $message = "Bonjour, ton album ".$title." vient d'être commenté. Voici le commentaire :\n".$data["comment"]."";
                                // $headers = 'Content-Type: text/plain; charset="utf-8"'." ";

                                // if(mail($email, "Nouveau commentaire", $message, $headers)) {
                                //         $commentMsg["success"] = ["Mail de confirmation envoyé."];
                                // }
                                
                                // else {
                                //         $commentMsg["errors"][] = "Une erreur s'est produite. Si cela se réitère, contacte-moi.";
                                // }

                                // do {
                                //         $id = uniqid();
                                // } while($pdo->prepare("SELECT `id` FROM `comments` WHERE `id` = $id") > 0);

                                // $commentAddition = $this->_comments->addComment($id, $data["email"], $data["commentLogin"], $_SERVER["REMOTE_ADDR"], $albumId, $title, $data["comment"]);

                                $commentMsg["success"] = ["Le commentaire a été publié avec succès."];
                        }
                                
                        return $commentMsg;
                }
        }

//*****B. Answer addition*****//
        public function answerForm(array $data) {
                $answerMsg = [];

                if($_POST["postAnswer"]) {
                        // $commentId = $data["commentId"];
                        $albumId = $_GET["albumId"];

                        // $pdo = new PDO("mysql:host=127.0.0.1;dbname=willeb_cl","root","");
                        // $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                        // $pdo->exec("SET NAMES UTF8");

                        // $query = $pdo->prepare("SELECT `id` FROM `comments` WHERE `id` = :commentId");
                        // $query->execute([":commentId" => $commentId]);
                        // $trueCommId = $query->fetchColumn();

                        $album = new Album();

                        $exist = $album->findAlbumById($albumId);

                        $title = $exist["title"];

                        $commentLists = $this->_comments->findAlbumComments($albumId);

                        foreach($commentLists as $commentList) {
                                $id = $commentList["id"];
                        }
                        
                        // if($commentId != $trueCommId || $commentId == null) {
                        //         die("Hacking attempt!");
                        // }

                        // else {
                
                                if(!$data["email"] || !$data["commentLogin"] || !$data["answer"]) {
                                        $answerMsg["errors"][] = "Veuilles remplir tous les champs.";
                                }

                                if($data["email"] && !filter_var($data["email"], FILTER_VALIDATE_EMAIL)) {
                                        $answerMsg["errors"][] = "Le format de l'adresse électronique est invalide.";
                                }

                                if($data["commentLogin"] && !preg_match(self::LOGIN_REGEX, $data["commentLogin"])) {
                                        $answerMsg["errors"][] = "Caractères autorisés pour le pseudo : lettres, chiffres, tirets et underscores.";
                                }

                                if($data["answer"] && !preg_match(self::COMMENT_REGEX, $data["answer"])) {
                                        $answerMsg["errors"][] = 'Caractères autorisés pour la réponse : lettres, chiffres, tilde, tirets, underscores, slash, parenthèses, crochets, accolades, arobases, dièses, signes "+", signes "=", astérisques, accents circonflexes, signes "%", point-virgules, virgules, doubles points, points, points d\'exclamation, points d\'interrogation, apostrophes, esperluettes, guillemets droits et espaces.';
                                }

                                if(!isset($data["acceptRules"]) || !isset($data["acceptPolicy"])) {
                                        $answerMsg["errors"][] = "Veuilles accepter le règlement général et la politique de confidentialité.";
                                }


                                if(empty($answerMsg["errors"])) {
                                        $pdo = new PDO("mysql:host=127.0.0.1;dbname=willeb_cl","root","");
                                        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                        $pdo->exec("SET NAMES UTF8");
                                        
                                        $query = $pdo->prepare("SELECT `user_email` FROM `comments` WHERE comments.id = :commentId");

                                        $query->execute([":commentId" => $id]);

                                        $email = $query->fetchColumn();

                                        $url = "/site_cl/index.php?p=albums&commentId=".$id."";
                                        $array = explode("=", $url);
                                        $commentId = $array[2];

                                        var_dump($commentId);

                                        // $message = "Bonjour, tu viens de recevoir une réponse à ton commentaire pour l'album ".$title.". Voici la réponse :\n".$data["answer"]."";
                                        // $headers = 'Content-Type: text/plain; charset="utf-8"'." ";

                                        // if(mail($email, "Nouveau commentaire", $message, $headers)) {
                                        //         $commentMsg["success"] = ["Mail de confirmation envoyé."]; 
                                        // }
                                        
                                        // else {
                                        //         $commentMsg["errors"][] = "Une erreur s'est produite. Si cela se réitère, contacte-moi.";
                                        // }

                                        // do {
                                        //         $id = uniqid();
                                        // } while($pdo->prepare("SELECT `id` FROM `comment_answers` WHERE `id` = $id") > 0);

                                        // $answerAddition = $this->_comments->addAnswer($id, $data["email"], $data["commentLogin"], $_SERVER["REMOTE_ADDR"], $trueCommId, $albumId, $title, $data["answer"]);

                                        $answerMsg["success"] = ["La réponse a été publiée avec succès."];
                                }
                        // }
                                
                        return $answerMsg;
                }
        }

//*****C. Vote addition*****//
        public function voteForm() {
                $vote = new Votes();

                $pdo = new PDO("mysql:host=127.0.0.1;dbname=willeb_cl","root","");
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $pdo->exec("SET NAMES UTF8");

                if(isset($_POST["likeAlb"]) || isset($_POST["dislikeAlb"])) {
                        $albumId = $_POST["albumId"];
                        $trueId = $_GET["albumId"];
                        $voteValue = $_POST["voteValue"];
                        $category = "album";

                        if(isset($_POST["likeAlb"]) && $voteValue != "1" || isset($_POST["dislikeAlb"]) && $voteValue != "-1" ||
                                $voteValue == null || $albumId != $trueId || $albumId == null) {
                                die("Hacking attempt!");
                        }

                        else {
                                if(isset($_SERVER["REMOTE_ADDR"])) {
                                        do {
                                                $voteId = uniqid();
                                        } while($pdo->prepare("SELECT `id` FROM `votes` WHERE `id` = $voteId AND `category` = $category") > 0);

                                        if($_POST["voteValue"] == 1) {
                                                $vote->like($voteId, $albumId, $category, $_SERVER["REMOTE_ADDR"], $voteValue);
                                        }

                                        else {
                                                $vote->dislike($voteId, $albumId, $category, $_SERVER["REMOTE_ADDR"], $voteValue);
                                        }

                                        $vote->updateVotecount($albumId, $category);
                                }
                        }
                }

                if(isset($_POST["likeComm"]) || isset($_POST["dislikeComm"])) {
                        $commentId = $_POST["commentId"];
                        $voteValue = $_POST["voteValue"];
                        $category = "comments";

                        $sql = "SELECT `id` FROM `comments` WHERE `id` = :id";
                
                        $query = $pdo->prepare($sql);
                
                        $query->execute([":id" => $commentId]);

                        $trueId = $query->fetchColumn();
                
                        if(isset($_POST["likeComm"]) && $voteValue != "1" || isset($_POST["dislikeComm"]) && $voteValue != "-1" ||
                                $voteValue == null || $commentId != $trueId || $commentId == null) {
                                die("Hacking attempt!");
                        }

                        else {
                                if(isset($_SERVER["REMOTE_ADDR"])) {
                                        do {
                                                $voteId = uniqid();
                                        } while($pdo->prepare("SELECT `id` FROM `votes` WHERE `id` = $voteId AND `category` = $category") > 0);

                                        if($_POST["voteValue"] == 1) {
                                                $vote->like($voteId, $commentId, $category, $_SERVER["REMOTE_ADDR"], $voteValue);
                                        }

                                        else {
                                                $vote->dislike($voteId, $commentId, $category, $_SERVER["REMOTE_ADDR"], $voteValue);
                                        }

                                        $vote->updateVotecount($commentId, $category);
                                        var_dump($voteId);
                                }
                        }
                }

                if(isset($_POST["likeAnsw"]) || isset($_POST["dislikeAnsw"])) {
                        $answerId = $_POST["answerId"];
                        $voteValue = $_POST["voteValue"];
                        $category = "comment_answers";

                        $sql = "SELECT `id` FROM `comment_answers` WHERE `id` = :id";
                
                        $query = $pdo->prepare($sql);
                
                        $query->execute([":id" => $answerId]);

                        $trueId = $query->fetchColumn();
                
                        if(isset($_POST["likeAnsw"]) && $voteValue != "1" || isset($_POST["dislikeAnsw"]) && $voteValue != "-1" ||
                                $voteValue == null || $answerId != $trueId || $answerId == null) {
                                die("Hacking attempt!");
                        }

                        else {
                                if(isset($_SERVER["REMOTE_ADDR"])) {
                                        do {
                                                $voteId = uniqid();
                                        } while($pdo->prepare("SELECT `id` FROM `votes` WHERE `id` = $voteId AND `category` = $category") > 0);

                                        if($_POST["voteValue"] == 1) {
                                                $vote->like($voteId, $answerId, $category, $_SERVER["REMOTE_ADDR"], $voteValue);
                                        }

                                        else {
                                                $vote->dislike($voteId, $answerId, $category, $_SERVER["REMOTE_ADDR"], $voteValue);
                                        }

                                        $vote->updateVotecount($answerId, $category);
                                }
                        }
                }
        }

//*****D. Comment modification*****//
        public function commentModifForm(array $data) {
                $comModifMsg = [];

                $commentId;

                $pdo = new PDO("mysql:host=127.0.0.1;dbname=willeb_cl","root","");
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $pdo->exec("SET NAMES UTF8");

                // $query = $pdo->prepare("SELECT `id` FROM `comments` WHERE `album_id` = :albumId");

                // $query->execute([":albumId" => $_GET["albumId"]]);

                $query = $pdo->prepare("SELECT `id` FROM `comments`");

                $query->execute();

                $lines = $query->fetchAll();

                var_dump($lines);

                // var_dump($commentIds);

                // foreach($lines as $line) {
                // if($_POST["changeComment"]) {
                foreach($lines as $line) {
                        $commentId = $line["id"];
                // $commentId = $data["commentId"];
                // $albumId = $data["albumId"];
                // $trueAlbId = $_GET["albumId"];

                // $pdo = new PDO("mysql:host=127.0.0.1;dbname=willeb_cl","root","");
                // $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                // $pdo->exec("SET NAMES UTF8");

                // $query = $pdo->prepare("SELECT `id` FROM `comments` WHERE `album_id` = :albumId");

                // $query->execute([":albumId" => $_GET["albumId"]]);

                // $lines = $query->fetchAll(\PDO::FETCH_COLUMN);

                // var_dump($lines);

                // for($i = 0; $i < $lines; $i ++) {
                //         var_dump($lines[$i]);
                //         // $this->_commentId = $lines["id"][$i];
                // }

                // if($commentId != $trueCommId || $commentId == null || $albumId != $trueAlbId || $albumId == null) {
                //         die("Hacking attempt!");
                // }

                // else {

                //         if(!$data["comment"]) {
                //                 $comModifMsg["errors"][] = "Veuilles remplir le champ.";
                //         }

                //         if($data["comment"] && !preg_match(self::COMMENT_REGEX, $data["comment"])) {
                //                 $comModifMsg["errors"][] = 'Caractères autorisés pour le commentaire : lettres, chiffres, tilde, tirets, underscores, slash, parenthèses, crochets, accolades, arobases, dièses, signes "+", signes "=", astérisques, accents circonflexes, signes "%", point-virgules, virgules, doubles points, points, points d\'exclamation, points d\'interrogation, apostrophes, esperluettes, guillemets droits et espaces.';
                //         }

                //         if(!isset($data["acceptRules"]) || !isset($data["acceptPolicy"])) {
                //                 $comModifMsg["errors"][] = "Veuilles accepter le règlement général et la politique de confidentialité.";
                //         }

                //         if(empty($comModifMsg["errors"])) {
                //                 // $commentModification = $this->_comments->updateComment($line["id"], $data["comment"]);
                //                 $comModifMsg["success"] = ["Le commentaire a été modifié avec succès."];
                //                 var_dump($line["id"]);
                //         }
                // // }
                        
                //         return $comModifMsg;
                }

                if($_POST["changeComment"]) {
                        if(!$data["comment"]) {
                                $comModifMsg["errors"][] = "Veuilles remplir le champ.";
                        }

                        if($data["comment"] && !preg_match(self::COMMENT_REGEX, $data["comment"])) {
                                $comModifMsg["errors"][] = 'Caractères autorisés pour le commentaire : lettres, chiffres, tilde, tirets, underscores, slash, parenthèses, crochets, accolades, arobases, dièses, signes "+", signes "=", astérisques, accents circonflexes, signes "%", point-virgules, virgules, doubles points, points, points d\'exclamation, points d\'interrogation, apostrophes, esperluettes, guillemets droits et espaces.';
                        }

                        if(!isset($data["acceptRules"]) || !isset($data["acceptPolicy"])) {
                                $comModifMsg["errors"][] = "Veuilles accepter le règlement général et la politique de confidentialité.";
                        }

                        if(empty($comModifMsg["errors"])) {
                                // $commentModification = $this->_comments->updateComment($commentId, $data["comment"]);
                                $comModifMsg["success"] = ["Le commentaire a été modifié avec succès."];
                                var_dump($commentId);
                        }
                        
                        return $comModifMsg;
                }
        }

//*****E. Answer modification*****//
        public function answerModifForm(array $data) {
                $answModifMsg = [];

                if($_POST["changeAnswer"]) {
                        $answerId = $data["answerId"];
                        // $commentId = $data["commentId"];

                        $pdo = new PDO("mysql:host=127.0.0.1;dbname=willeb_cl","root","");
                        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                        $pdo->exec("SET NAMES UTF8");

                        // $query = $pdo->prepare("SELECT `id` FROM `comment_answers` WHERE `id` = :answerId AND `comment_id` = :commentId");

                        // $query->execute([":answerId" => $answerId, ":commentId" => $commentId]);

                        $query = $pdo->prepare("SELECT `id` FROM `comment_answers` WHERE `id` = :answerId");

                        $query->execute([":answerId" => $answerId]);

                        $trueAnswId = $query->fetchColumn();

                        // $req = $pdo->prepare("SELECT `comment_id` FROM `comment_answers` WHERE `id` = :answerId AND `comment_id` = :commentId");

                        // $req->execute([":answerId" => $answerId, ":commentId" => $commentId]);

                        // $trueCommId = $req->fetchColumn();

                        // if($answerId != $trueAnswId || $answerId == null || $commentId != $trueCommId || $commentId == null) {
                        //         die("Hacking attempt!");
                        // }

                        if($answerId != $trueAnswId || $answerId == null) {
                                die("Hacking attempt!");
                        }

                        else {

                                if(!$data["answer"]) {
                                        $answModifMsg["errors"][] = "Veuilles remplir le champ.";
                                }

                                if($data["answer"] && !preg_match(self::COMMENT_REGEX, $data["answer"])) {
                                        $answModifMsg["errors"][] = 'Caractères autorisés pour la réponse : lettres, chiffres, tilde, tirets, underscores, slash, parenthèses, crochets, accolades, arobases, dièses, signes "+", signes "=", astérisques, accents circonflexes, signes "%", point-virgules, virgules, doubles points, points, points d\'exclamation, points d\'interrogation, apostrophes, esperluettes, guillemets droits et espaces.';
                                }

                                if(!isset($data["acceptRules"]) || !isset($data["acceptPolicy"])) {
                                        $answModifMsg["errors"][] = "Veuilles accepter le règlement général et la politique de confidentialité.";
                                }

                                if(empty($answModifMsg["errors"])) {
                                        // $answerModification = $this->_comments->updateAnswer($answerId, $data["answer"]);
                                        $answModifMsg["success"] = ["La réponse a été modifié avec succès."];
                                }
                        }
                                
                        return $answModifMsg;
                }
        }

//*****F. Comment deletion*****//
        public function commentDeletionForm($commentId) {
                $commentDelMsg = [];

                if(!Session::online() && isset($_POST["deleteComment"])) {
                        $commentId = $_POST["commentId"];
                        // $albumId = $_POST["albumId"];
                        // $trueAlbId = $_GET["albumId"];

                        $pdo = new PDO("mysql:host=127.0.0.1;dbname=willeb_cl","root","");
                        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                        $pdo->exec("SET NAMES UTF8");

                        // $query = $pdo->prepare("SELECT `id` FROM `comments` WHERE `id` = :commentId AND `album_id` = :albumId");

                        // $query->execute([":commentId" => $commentId, ":albumId" => $albumId]);

                        $query = $pdo->prepare("SELECT `id` FROM `comments` WHERE `id` = :commentId");

                        $query->execute([":commentId" => $commentId]);

                        // $req = $pdo->prepare("SELECT `album_title` FROM `comments` WHERE `id` = :commentId AND `album_id` = :albumId");

                        // $req->execute([":commentId" => $commentId, ":albumId" => $albumId]);

                        $req = $pdo->prepare("SELECT `album_title` FROM `comments` WHERE `id` = :commentId");

                        $req->execute([":commentId" => $commentId]);

                        $trueCommId = $query->fetchColumn();
                        $title = $req->fetchColumn();

                        // if($commentId != $trueCommId || $commentId == null || $albumId != $trueAlbId || $albumId == null) {
                        //         die("Hacking attempt!");
                        // }

                        if($commentId != $trueCommId || $commentId == null) {
                                die("Hacking attempt!");
                        }

                        else {
                                // $deleteComment = $this->_comments->deleteComment($commentId);
                                $commentDelMsg["success"] = ["Le commentaire sélectionné pour l'album ".$title." et ses réponses ont été supprimés avec succès."];
                        }

                        var_dump($commentId, $trueCommId);
                }

                if(Session::admin() && isset($_POST["adminDelComment"])) {
                        $commentId = $_POST["commentId"];
                        $albumId = $_POST["albumId"];

                        $pdo = new PDO("mysql:host=127.0.0.1;dbname=willeb_cl","root","");
                        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                        $pdo->exec("SET NAMES UTF8");

                        $query = $pdo->prepare("SELECT `id` FROM `comments` WHERE `id` = :commentId AND `album_id` = :albumId");

                        $query->execute([":commentId" => $commentId, ":albumId" => $albumId]);

                        $que = $pdo->prepare("SELECT `album_id` FROM `comments` WHERE `id` = :commentId AND `album_id` = :albumId");

                        $que->execute([":commentId" => $commentId, ":albumId" => $albumId]);

                        $req = $pdo->prepare("SELECT `user_login` FROM `comments` WHERE `id` = :commentId");

                        $req->execute([":commentId" => $commentId]);

                        $trueCommId = $query->fetchColumn();
                        $trueAlbId = $que->fetchColumn();
                        $login = $req->fetchColumn();

                        if($commentId != $trueCommId || $commentId == null || $albumId != $trueAlbId || $albumId == null) {
                                die("Hacking attempt!");
                        }

                        else {
                                // $deleteComment = $this->_comments->deleteComment($commentId);
                                $commentDelMsg["success"] = ["Le commentaire sélectionné de l'utilisateur ".$login." et ses réponses ont été supprimés avec succès."];
                        }
                }
                
                return $commentDelMsg;
        }

//*****G. Answer deletion*****//
        public function answerDeletionForm($answerId) {
                $answerDelMsg = [];

                $answerId = $_POST["answerId"];
                $commentId = $_POST["commentId"];

                $pdo = new PDO("mysql:host=127.0.0.1;dbname=willeb_cl","root","");
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $pdo->exec("SET NAMES UTF8");

                $query = $pdo->prepare("SELECT `id` FROM `comment_answers` WHERE `id` = :answerId AND `comment_id` = :commentId");

                $query->execute([":answerId" => $answerId, ":commentId" => $commentId]);

                $que = $pdo->prepare("SELECT `album_title` FROM `comment_answers` WHERE `id` = :answerId AND `comment_id` = :commentId");

                $que->execute([":answerId" => $answerId, ":commentId" => $commentId]);

                $req = $pdo->prepare("SELECT `comment_id` FROM `comment_answers` WHERE `comment_id` = :commentId");

                $req->execute([":commentId" => $commentId]);

                $trueAnswId = $query->fetchColumn();
                $title = $que->fetchColumn();
                $trueCommId = $req->fetchColumn();
                
                if(!Session::admin() && isset($_POST["deleteAnswer"]) || Session::admin() && isset($_POST["adminDelAnswer"])) {
                        if($commentId != $trueCommId || $commentId == null || $answerId != $trueAnswId || $answerId == null) {
                                die("Hacking attempt!");
                        }

                        else {
                                // $deleteAnswer = $this->_comments->deleteAnswer($answerId);
                                $answerDelMsg["success"] = ["La réponse sélectionnée au commentaire n° ".$commentId." pour l'album ".$title." a été supprimée avec succès."];
                        }
                }
                
                return $answerDelMsg;
        }

//*****END OF THE CLASS*****//      
}