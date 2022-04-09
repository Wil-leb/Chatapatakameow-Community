<?php

namespace App\controller;
use App\model\{Albums, Comments, Reports, Users, Votes};
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

                        $album = new Albums();
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

                                do {
                                        $id = uniqid();
                                } while($pdo->prepare("SELECT `id` FROM `comments` WHERE `id` = $id") > 0);

                                $query = $pdo->prepare("SELECT users.id FROM `users` LEFT OUTER JOIN `albums`
                                                        ON users.id = albums.user_id WHERE albums.id = :albumId");
                                $query->execute([":albumId" => $albumId]);
                                $refAuthorId = $query->fetchColumn();

                                $commentAddition = $this->_comments->addComment($id, $refAuthorId, $albumId, $title, $data["email"],
                                $data["commentLogin"], $_SERVER["REMOTE_ADDR"], $data["comment"]);

                                $commentMsg["success"][] = "Le commentaire a été publié avec succès.";

                                $req = $pdo->prepare("SELECT `email` FROM `users` LEFT OUTER JOIN `comments`
                                                        ON users.id = comments.album_author_id WHERE comments.album_id = :albumId");
                                $req->execute([":albumId" => $albumId]);
                                $refAuthorEmail = $req->fetchColumn();

                                $message = "Bonjour, ton album ".$title." vient d'être commenté. Voici le commentaire :\n".$data["comment"]."";
                                $headers = 'Content-Type: text/plain; charset="utf-8"'." ";

                                // if(mail($refAuthorEmail, "Nouveau commentaire", $message, $headers)) {
                                        $commentMsg["success"][] = "Mail de confirmation envoyé.";
                                // }
                                
                                // else {
                                //         $commentMsg["errors"][] = "Une erreur s'est produite. Si cela se réitère, contacte-moi.";
                                // }
                        }

                        return $commentMsg;
                }
        }

//*****B. Answer addition*****//
        public function answerForm(array $data) {
                $answerMsg = [];
                        
                if($_POST["postAnswer"]) {
                        $albumId = $_GET["albumId"];
                        $commentId = $data["commentId"];

                        $album = new Albums();
                        $exist = $album->findAlbumById($albumId);
                        $title = $exist["title"];

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

                                do {
                                        $id = uniqid();
                                } while($pdo->prepare("SELECT `id` FROM `comment_answers` WHERE `id` = $id") > 0);

                                // $query = $pdo->prepare("SELECT users.id FROM `users` LEFT OUTER JOIN `albums`
                                //                         ON users.id = albums.user_id WHERE albums.id = :albumId");
                                // $query->execute([":albumId" => $albumId]);
                                // $refAuthorId = $query->fetchColumn();

                                // $answerAddition = $this->_comments->addAnswer($id, $refAuthorId, $commentId, $albumId, $title,
                                // $data["email"], $data["commentLogin"], $_SERVER["REMOTE_ADDR"], $data["answer"]);
                                
                                // $answerMsg["success"][] = "La réponse a été publiée avec succès.";

                                $req = $pdo->prepare("SELECT `comment_email`, `comment` FROM `comments` WHERE comments.id = :commentId");
                                $req->execute([":commentId" => $commentId]);
                                $result = $req->fetch();
                                $refAuthorEmail = $result["comment_email"];
                                $comment = $result["comment"];

                                $answerAddition = $this->_comments->addAnswer($id, $commentId, $refAuthorEmail, $albumId, $title,
                                $data["email"], $data["commentLogin"], $_SERVER["REMOTE_ADDR"], $data["answer"]);
                                
                                $answerMsg["success"][] = "La réponse a été publiée avec succès.";

                                $message = "Bonjour, tu viens de recevoir une réponse à ton commentaire ".$comment." pour l'album ".$title.". Voici la réponse :\n".$data["answer"]."";
                                $headers = 'Content-Type: text/plain; charset="utf-8"'." ";

                                // if(mail($refAuthorEmail, "Nouvelle réponse", $message, $headers)) {
                                        $answerMsg["success"][] = "Mail de confirmation envoyé.";
                                // }
                                
                                // else {
                                //         $commentMsg["errors"][] = "Une erreur s'est produite. Si cela se réitère, contacte-moi.";
                                // }
                        }

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
                        $category = "albums";

                        if($albumId != $trueId || $albumId == null) {
                                die("Hacking attempt!");
                        }

                        else {
                                if(isset($_SERVER["REMOTE_ADDR"])) {
                                        do {
                                                $voteId = uniqid();
                                        } while($pdo->prepare("SELECT `id` FROM `votes` WHERE `id` = $voteId
                                                                AND `category` = $category") > 0);

                                        $req = $pdo->prepare("SELECT users.id FROM `users` LEFT OUTER JOIN `albums`
                                                                ON users.login = albums.user_login
                                                                WHERE albums.id = :albumId");
                                        $req->execute([":albumId" => $albumId]);
                                        $publisherId = $req->fetchColumn();

                                        $album = new Albums();
                                        $exist = $album->findAlbumById($albumId);
                                        $title = $exist["title"];

                                        $que = $pdo->prepare("SELECT `email` FROM `users` LEFT OUTER JOIN `albums`
                                                                ON users.id = albums.user_id WHERE albums.id = :albumId");
                                        $que->execute([":albumId" => $albumId]);
                                        $refAuthorEmail = $que->fetchColumn();

                                        if(isset($_POST["likeAlb"])) {
                                                $vote->like($voteId, $publisherId, $albumId, $category, $_SERVER["REMOTE_ADDR"], 1);
                                                
                                                $message = "Bonjour, ton album ".$title." vient de recevoir un like.";
                                                $headers = 'Content-Type: text/plain; charset="utf-8"'." ";

                                                // if(mail($refAuthorEmail, "Nouveau vote", $message, $headers)) {
                                                        echo $message;
                                                // }
                                                
                                                // else {
                                                        
                                                // }
                                        }

                                        elseif(isset($_POST["dislikeAlb"])) {
                                                $vote->dislike($voteId, $publisherId, $albumId, $category, $_SERVER["REMOTE_ADDR"], -1);

                                                $message = "Bonjour, ton album ".$title." vient de recevoir un dislike.";
                                                $headers = 'Content-Type: text/plain; charset="utf-8"'." ";

                                                // if(mail($refAuthorEmail, "Nouveau vote", $message, $headers)) {
                                                        echo $message;
                                                // }
                                                
                                                // else {
                                                        
                                                // }
                                        }

                                        $vote->updateVoteCount($albumId, $category);
                                }
                        }
                }

                if(isset($_POST["likeComm"]) || isset($_POST["dislikeComm"])) {
                        $commentId = $_POST["commentId"];
                        $category = "comments";

                        $sql = "SELECT `id` FROM `comments` WHERE `id` = :id";
                        $query = $pdo->prepare($sql);
                        $query->execute([":id" => $commentId]);
                        $trueId = $query->fetchColumn();

                        if($commentId != $trueId || $commentId == null) {
                                die("Hacking attempt!");
                        }

                        else {
                                if(isset($_SERVER["REMOTE_ADDR"])) {
                                        do {
                                                $voteId = uniqid();
                                        } while($pdo->prepare("SELECT `id` FROM `votes` WHERE `id` = $voteId
                                                                AND `category` = $category") > 0);

                                        $req = $pdo->prepare("SELECT users.id FROM `users` LEFT OUTER JOIN `comments`
                                                                ON users.login = comments.comment_login
                                                                WHERE comments.id = :commentId");
                                        $req->execute([":commentId" => $commentId]);
                                        $publisherId = $req->fetchColumn();

                                        $findComment = new Comments();
                                        $exist = $findComment->findCommentById($commentId);
                                        $comment = $exist["comment"];
                                        $refAuthorEmail = $exist["comment_email"];

                                        if(isset($_POST["likeComm"])) {
                                                $vote->like($voteId, $publisherId, $commentId, $category, $_SERVER["REMOTE_ADDR"], 1);

                                                $message = "Bonjour, ton commentaire ".$comment." vient de recevoir un like.";
                                                $headers = 'Content-Type: text/plain; charset="utf-8"'." ";

                                                // if(mail($refAuthorEmail, "Nouveau vote", $message, $headers)) {
                                                        echo $message;
                                                // }
                                                
                                                // else {
                                                        
                                                // }
                                        }

                                        elseif(isset($_POST["dislikeComm"])) {
                                                $vote->dislike($voteId, $publisherId, $commentId, $category, $_SERVER["REMOTE_ADDR"],
                                                -1);

                                                $message = "Bonjour, ton commentaire ".$comment." vient de recevoir un dislike.";
                                                $headers = 'Content-Type: text/plain; charset="utf-8"'." ";

                                                // if(mail($refAuthorEmail, "Nouveau vote", $message, $headers)) {
                                                        echo $message;
                                                // }
                                                
                                                // else {
                                                        
                                                // }
                                        }

                                        $vote->updateVoteCount($commentId, $category);
                                }
                        }
                }

                if(isset($_POST["likeAnsw"]) || isset($_POST["dislikeAnsw"])) {
                        $answerId = $_POST["answerId"];
                        $category = "comment_answers";

                        $sql = "SELECT `id` FROM `comment_answers` WHERE `id` = :id";
                        $query = $pdo->prepare($sql);
                        $query->execute([":id" => $answerId]);
                        $trueId = $query->fetchColumn();

                        if($answerId != $trueId || $answerId == null) {
                                die("Hacking attempt!");
                        }

                        else {
                                if(isset($_SERVER["REMOTE_ADDR"])) {
                                        do {
                                                $voteId = uniqid();
                                        } while($pdo->prepare("SELECT `id` FROM `votes` WHERE `id` = $voteId
                                                                AND `category` = $category") > 0);

                                        $req = $pdo->prepare("SELECT users.id FROM `users` LEFT OUTER JOIN `comment_answers`
                                                        ON users.login = comment_answers.answer_login
                                                        WHERE comment_answers.id = :answerId");
                                        $req->execute([":answerId" => $answerId]);
                                        $publisherId = $req->fetchColumn();

                                        $findComment = new Comments();
                                        $exist = $findComment->findAnswerById($answerId);
                                        $answer = $exist["answer"];
                                        $comment = $exist["comment"];
                                        $refAuthorEmail = $exist["answer_email"];

                                        if(isset($_POST["likeAnsw"])) {
                                                $vote->like($voteId, $publisherId, $answerId, $category, $_SERVER["REMOTE_ADDR"], 1);

                                                $message = "Bonjour, ta réponse ".$answer." au commentaire ".$comment." vient de recevoir un like.";
                                                $headers = 'Content-Type: text/plain; charset="utf-8"'." ";

                                                // if(mail($refAuthorEmail, "Nouveau vote", $message, $headers)) {
                                                        echo $message;
                                                // }
                                                
                                                // else {
                                                        
                                                // }
                                        }

                                        elseif(isset($_POST["dislikeAnsw"])) {
                                                $vote->dislike($voteId, $publisherId, $answerId, $category, $_SERVER["REMOTE_ADDR"],
                                                -1);

                                                $message = "Bonjour, ta réponse ".$answer." au commentaire ".$comment." vient de recevoir un dislike.";
                                                $headers = 'Content-Type: text/plain; charset="utf-8"'." ";

                                                // if(mail($refAuthorEmail, "Nouveau vote", $message, $headers)) {
                                                        echo $message;
                                                // }
                                                
                                                // else {
                                                        
                                                // }
                                        }

                                        $vote->updateVoteCount($answerId, $category);
                                }
                        }
                }
        }

//*****D. Comment modification*****//
        public function commentModifForm(array $data) {
                $comModifMsg = [];

                if($_POST["changeComment"]) {
                        $commentId = $data["commentId"];

                        $pdo = new PDO("mysql:host=127.0.0.1;dbname=willeb_cl","root","");
                        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                        $pdo->exec("SET NAMES UTF8");

                        $query = $pdo->prepare("SELECT `id` FROM `comments` WHERE `id` = :commentId");
                        $query->execute([":commentId" => $commentId]);
                        $trueCommId = $query->fetchColumn();

                        if($commentId != $trueCommId || $commentId == null) {
                                die("Hacking attempt!");
                        }
                        
                        else {
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
                                }   
                        }

                        return $comModifMsg;
                }
        }

//*****E. Answer modification*****//
        public function answerModifForm(array $data) {
                $answModifMsg = [];

                if($_POST["changeAnswer"]) {
                        $answerId = $data["answerId"];
                        $commentId = $data["commentId"];

                        $pdo = new PDO("mysql:host=127.0.0.1;dbname=willeb_cl","root","");
                        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                        $pdo->exec("SET NAMES UTF8");

                        $query = $pdo->prepare("SELECT `id` FROM `comment_answers` WHERE `id` = :answerId");
                        $query->execute([":answerId" => $answerId]);
                        $trueAnswId = $query->fetchColumn();

                        $req = $pdo->prepare("SELECT `comment_id` FROM `comment_answers` WHERE `id` = :answerId AND `comment_id` = :commentId");
                        $req->execute([":answerId" => $answerId, ":commentId" => $commentId]);
                        $trueCommId = $req->fetchColumn();

                        if($answerId != $trueAnswId || $answerId == null || $commentId != $trueCommId || $commentId == null) {
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

                                        $answModifMsg["success"] = ["La réponse a été modifiée avec succès."];
                                }
                        }
                                
                        return $answModifMsg;
                }
        }

//*****F. Comment deletion*****//
        public function commentDeletionForm($commentId) {
                $commentDelMsg = [];
                
                if(isset($_POST["deleteComment"]) || Session::admin() && isset($_POST["adminDelComment"])) {
                        $commentId = $_POST["commentId"];

                        $pdo = new PDO("mysql:host=127.0.0.1;dbname=willeb_cl","root","");
                        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                        $pdo->exec("SET NAMES UTF8");

                        $query = $pdo->prepare("SELECT `id` FROM `comments` WHERE `id` = :commentId");
                        $query->execute([":commentId" => $commentId]);
                        $trueCommId = $query->fetchColumn();

                        if($commentId != $trueCommId || $commentId == null) {
                                die("Hacking attempt!");
                        }

                        else {
                                $req = $pdo->prepare("SELECT `album_title` FROM `comments` WHERE `id` = :commentId");
                                $req->execute([":commentId" => $commentId]);
                                $title = $req->fetchColumn();

                                if(isset($_POST["deleteComment"])) {
                                        // $deleteComment = $this->_comments->deleteComment($commentId);

                                        $commentDelMsg["success"] = ["Le commentaire sélectionné pour l'album ".$title." et ses réponses ont été supprimés avec succès."];
                                }

                                elseif(Session::admin() && isset($_POST["adminDelComment"])) {
                                        // $deleteComment = $this->_comments->deleteComment($commentId);

                                        $fetch = $pdo->prepare("SELECT `user_email`, `user_login`, `comment` FROM `comments`
                                                                WHERE `id` = :commentId");
                                        $fetch->execute([":commentId" => $commentId]);
                                        $result = $fetch->fetch();

                                        $commentDelMsg["success"][] = "Le commentaire sélectionné de l'utilisateur ".$result["user_login"]." pour l'album ".$title." et ses réponses ont été supprimés avec succès.";

                                        $message = "Bonjour, ton commentaire ".$result["comment"]." vient d'être modéré suite à des signalements. En cas de contestation, contacte-moi.";
                                        $headers = 'Content-Type: text/plain; charset="utf-8"'." ";

                                        // if(mail($result["user_email"], "Commentaire modéré", $message, $headers)) {
                                                $commentDelMsg["success"][] = "Mail de modération envoyé.";
                                        // }
                                        
                                        // else {
                                        //         $commentDelMsg["errors"][] = "Une erreur s'est produite. Si cela se réitère, réeesaie ultérieurement.";
                                        // }
                                }
                        }
                }
                
                return $commentDelMsg;
        }

//*****G. Answer deletion*****//
        public function answerDeletionForm($answerId) {
                $answerDelMsg = [];
                
                if(isset($_POST["deleteAnswer"]) || Session::admin() && isset($_POST["adminDelAnswer"])) {
                        $answerId = $_POST["answerId"];
                        $commentId = $_POST["commentId"];

                        $pdo = new PDO("mysql:host=127.0.0.1;dbname=willeb_cl","root","");
                        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                        $pdo->exec("SET NAMES UTF8");

                        $query = $pdo->prepare("SELECT `id` FROM `comment_answers` WHERE `id` = :answerId AND `comment_id` = :commentId");
                        $query->execute([":answerId" => $answerId, ":commentId" => $commentId]);
                        $trueAnswId = $query->fetchColumn();

                        $req = $pdo->prepare("SELECT `comment_id` FROM `comment_answers` WHERE `id` = :answerId AND `comment_id` = :commentId");
                        $req->execute([":answerId" => $answerId, ":commentId" => $commentId]);
                        $trueCommId = $req->fetchColumn();

                        if($answerId != $trueAnswId || $answerId == null || $commentId != $trueCommId || $commentId == null) {
                                die("Hacking attempt!");
                        }

                        else {
                                $que = $pdo->prepare("SELECT `album_title` FROM `comment_answers` WHERE `id` = :answerId AND `comment_id` = :commentId");
                                $que->execute([":answerId" => $answerId, ":commentId" => $commentId]);
                                $title = $que->fetchColumn();

                                if(isset($_POST["deleteAnswer"])) {
                                        // $deleteAnswer = $this->_comments->deleteAnswer($answerId);

                                        $fetch = $pdo->prepare("SELECT `comment` FROM `comments` WHERE `id` = :commentId");
                                        $fetch->execute([":commentId" => $commentId]);
                                        $comment = $fetch->fetchColumn();

                                        $answerDelMsg["success"] = ["La réponse sélectionnée au commentaire ".$comment." pour l'album ".$title." a été supprimée avec succès."];
                                }

                                elseif(Session::admin() && isset($_POST["adminDelAnswer"])) {
                                        // $deleteAnswer = $this->_comments->deleteAnswer($answerId);

                                        $fetch = $pdo->prepare("SELECT comment_answers.user_email, comment_answers.user_login,
                                                                `comment_id`, `answer`, `comment`
                                                                FROM `comment_answers`
                                                                LEFT OUTER JOIN `comments` ON comments.id = comment_answers.comment_id
                                                                WHERE comment_answers.id = :answerId AND `comment_id` = :commentId");
                                        $fetch->execute([":answerId" => $answerId, ":commentId" => $commentId]);
                                        $result = $fetch->fetch(\PDO::FETCH_ASSOC);

                                        $answerDelMsg["success"][] = "La réponse sélectionnée de l'utilisateur ".$result["user_login"]." au commentaire n° ".$commentId." pour l'album ".$title." a été supprimée avec succès.";

                                        $message = "Bonjour, ta réponse ".$result["answer"]." au commentaire ".$result["comment"]." vient d'être modérée suite à des signalements. En cas de contestation, contacte-moi.";
                                        $headers = 'Content-Type: text/plain; charset="utf-8"'." ";

                                        // if(mail($result["user_email"], "Réponse modérée", $message, $headers)) {
                                                $answerDelMsg["success"][] = "Mail de modération envoyé.";
                                        // }
                                        
                                        // else {
                                        //         $answerDelMsg["errors"][] = "Une erreur s'est produite. Si cela se réitère, réeesaie ultérieurement.";
                                        // }
                                }
                        }
                }
                
                return $answerDelMsg;
        }

//*****H. Report addition*****//
public function reportForm() {
        $report = new Reports();

        $pdo = new PDO("mysql:host=127.0.0.1;dbname=willeb_cl","root","");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->exec("SET NAMES UTF8");

        if(isset($_POST["reportComm"])) {
                $commentId = $_POST["commentId"];
                $category = "comments";

                $sql = "SELECT `id` FROM `comments` WHERE `id` = :id";
                $query = $pdo->prepare($sql);
                $query->execute([":id" => $commentId]);
                $trueId = $query->fetchColumn();

                if($commentId != $trueId || $commentId == null) {
                        die("Hacking attempt!");
                }

                else {
                        if(isset($_SERVER["REMOTE_ADDR"])) {
                                do {
                                        $reportId = uniqid();
                                } while($pdo->prepare("SELECT `id` FROM `reports` WHERE `id` = $reportId AND `category` = $category") > 0);

                                $req = $pdo->prepare("SELECT users.id FROM `users` LEFT OUTER JOIN `comments`
                                                        ON users.login = comments.comment_login
                                                        WHERE comments.id = :commentId");
                                $req->execute([":commentId" => $commentId]);
                                $publisherId = $req->fetchColumn();

                                $report->report($reportId, $publisherId, $commentId, $category, $_SERVER["REMOTE_ADDR"], 1);

                                $report->updateReportCount($commentId, $category);
                        }
                }
        }

        if(isset($_POST["reportAnsw"])) {
                $answerId = $_POST["answerId"];
                $category = "comment_answers";

                $sql = "SELECT `id` FROM `comment_answers` WHERE `id` = :id";
                $query = $pdo->prepare($sql);
                $query->execute([":id" => $answerId]);
                $trueId = $query->fetchColumn();

                if($answerId != $trueId || $answerId == null) {
                        die("Hacking attempt!");
                }

                else {
                        if(isset($_SERVER["REMOTE_ADDR"])) {
                                do {
                                        $reportId = uniqid();
                                } while($pdo->prepare("SELECT `id` FROM `reports` WHERE `id` = $reportId AND `category` = $category") > 0);

                                $req = $pdo->prepare("SELECT users.id FROM `users` LEFT OUTER JOIN `comment_answers`
                                                        ON users.login = comment_answers.answer_login
                                                        WHERE comment_answers.id = :answerId");
                                $req->execute([":answerId" => $answerId]);
                                $publisherId = $req->fetchColumn();

                                $report->report($reportId, $publisherId, $answerId, $category, $_SERVER["REMOTE_ADDR"], 1);

                                $report->updateReportCount($answerId, $category);
                        }
                }
        }
}

//*****END OF THE CLASS*****//      
}