<?php

namespace App\controller;
use App\model\{Album, Comments, User, Votes};
use \PDO;

class CommentFormController {
        protected Comments $_comments;
        
        public function __construct(Comments $comments) {
                $this->_comments = $comments;
        }

//*****A. Comment addition form*****//
        public function addCommentform(array $data) {
                $addMessages = [];

                if($_POST['postComment']) {
                
                        if(!$data['email'] || !$data['login'] || !$data['comment']) {
                                $addMessages['errors'][] = 'Veuilles remplir tous les champs.';
                        }

                        if($data['email'] && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                                $addMessages['errors'][] = 'Le format de l\'adresse électronique est invalide.';
                        }

                        $allowedLength = 200;
                        $length = strlen($data['comment']);

                        if($length > $allowedLength) {
                                $addMessages['errors'][] = 'Le commentaire ne doit pas dépasser 200 caractères, espaces comprises.';
                        }

                        $loginRegex = '/^[\p{L}0-9\-_]+$/ui';
                        $textRegex = '/^[\p{L}\d\~\-_\/()\[\]{}@#\+\*=\^\%;,:.!?\'&"\s]+$/ui';

                        if($data['login'] && !preg_match($loginRegex, $data['login'])) {
                                $addMessages['errors'][] = 'Caractères autorisés pour le pseudo : lettres, chiffres, tirets et underscores.';
                        }

                        if($data['comment'] && !preg_match($textRegex, $data['comment'])) {
                                $addMessages['errors'][] = 'Caractères autorisés pour le commentaire : lettres, chiffres, tilde, tirets, underscores, slash, parenthèses, crochets, accolades, arobases, dièses, signes "+", signes "=", astérisques, accents circonflexes, signes "%", point-virgules, virgules, doubles points, points, points d\'exclamation, points d\'interrogation, apostrophes, esperluettes, guillemets droits et espaces.';
                        }

                        if(empty($addMessages['errors'])) {
                                $pdo = new PDO('mysql:host=127.0.0.1;dbname=willeb_cl','root','');
                                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                $pdo->exec('SET NAMES UTF8');

                                do {
                                        $id = uniqid();
                                } while($pdo->prepare("SELECT id FROM comments WHERE id = $id") > 0);

                                $commentAddition = $this->_comments->addComment($id, $data['email'], $data['login'], $_SERVER['REMOTE_ADDR'], $data['albumId'], $data['albumTitle'], $data['comment']);

                                $addMessages['success'] = ['Le commentaire a été publié avec succès.'];
                                return $addMessages;
                        }
                                
                        return $addMessages;
                }
        }

//*****B. Answer addition form*****//
        public function addAnswerform(array $data) {
                $answerMessages = [];

                if($_POST['postAnswer']) {
                
                        if(!$data['email'] || !$data['login'] || !$data['content']) {
                                $answerMessages['errors'][] = 'Veuilles remplir tous les champs.';
                        }

                        if($data['email'] && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                                $answerMessages['errors'][] = 'Le format de l\'adresse électronique est invalide.';
                        }

                        $allowedLength = 200;
                        $length = strlen($data['content']);

                        if($length > $allowedLength) {
                                $answerMessages['errors'][] = 'La réponse ne doit pas dépasser 200 caractères, espaces comprises.';
                        }

                        $loginRegex = '/^[\p{L}0-9\-_]+$/ui';
                        $textRegex = '/^[\p{L}\d\~\-_\/()\[\]{}@#\+\*=\^\%;,:.!?\'&"\s]+$/ui';

                        if($data['login'] && !preg_match($loginRegex, $data['login'])) {
                                $answerMessages['errors'][] = 'Caractères autorisés pour le pseudo : lettres, chiffres, tirets et underscores.';
                        }

                        if($data['content'] && !preg_match($textRegex, $data['content'])) {
                                $answerMessages['errors'][] = 'Caractères autorisés pour la réponse : lettres, chiffres, tilde, tirets, underscores, slash, parenthèses, crochets, accolades, arobases, dièses, signes "+", signes "=", astérisques, accents circonflexes, signes "%", point-virgules, virgules, doubles points, points, points d\'exclamation, points d\'interrogation, apostrophes, esperluettes, guillemets droits et espaces.';
                        }

                        if(empty($answerMessages['errors'])) {
                                $pdo = new PDO('mysql:host=127.0.0.1;dbname=willeb_cl','root','');
                                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                $pdo->exec('SET NAMES UTF8');

                                do {
                                        $id = uniqid();
                                } while($pdo->prepare("SELECT id FROM comment_answers WHERE id = $id") > 0);

                                $answerAddition = $this->_comments->addAnswer($id, $data['email'], $data['login'], $_SERVER['REMOTE_ADDR'], $data['commentId'], $data['albumTitle'], $data['content']);

                                $answerMessages['success'] = ['La réponse a été publiée avec succès.'];
                                return $answerMessages;
                        }
                                
                        return $answerMessages;
                }
        }

//*****C. Reply addition form*****//
// public function addReplyform(array $data) {
//         $replyMessages = [];

//         if($_POST['postReply']) {
        
//                 if(!$data['email'] || !$data['login'] || !$data['text']) {
//                         $replyMessages['errors'][] = 'Veuilles remplir tous les champs.';
//                 }

//                 if($data['email'] && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
//                         $replyMessages['errors'][] = 'Le format de l\'adresse électronique est invalide.';
//                 }

//                 $allowedLength = 200;
//                 $length = strlen($data['text']);

//                 if($length > $allowedLength) {
//                         $replyMessages['errors'][] = 'La réponse ne doit pas dépasser 200 caractères, espaces comprises.';
//                 }

//                 $loginRegex = '/^[\p{L}0-9\-_]+$/ui';
//                 $textRegex = '/^[\p{L}\d\-\/();,:.!?\'&"\s]+$/ui';

//                 if($data['login'] && !preg_match($loginRegex, $data['login'])) {
//                         $replyMessages['errors'][] = 'Caractères autorisés pour le pseudo : lettres, chiffres, tirets et underscores.';
//                 }

//                 if($data['text'] && !preg_match($textRegex, $data['text'])) {
//                         $replyMessages['errors'][] = 'Caractères autorisés pour la réponse : lettres, chiffres, tirets, slash, parenthèses, point-virgules, virgules, doubles points, points, points d\'exclamation, points d\'interrogation, apostrophes, esperluettes, guillemets droits et espaces.';
//                 }

//                 if(empty($replyMessages['errors'])) {
//                         $pdo = new PDO('mysql:host=127.0.0.1;dbname=willeb_cl','root','');
//                         $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//                         $pdo->exec('SET NAMES UTF8');

//                         do {
//                                 $id = uniqid();
//                         } while($pdo->prepare("SELECT id FROM answer_replies WHERE id = $id") > 0);

//                         $replyAddition = $this->_comments->addReply($id, $data['email'], $data['login'], $_SERVER['REMOTE_ADDR'],
                        // $data['answerId'], $data['text']);

//                         $replyMessages['success'] = ['La réponse a été publiée avec succès.'];
//                         return $replyMessages;
//                 }
                        
//                 return $replyMessages;
//         }
// }

//*****D. Vote addition form*****//
        public function addVoteform() {
                if($_SERVER['REQUEST_METHOD'] != 'POST') {
                        http_response_code(403);
                        die();
                }

                // if(isset($_SERVER['REMOTE_ADDR'])) {
                //         http_response_code(403);
                //         die('Tu as déjà voté pour cet album.');
                // }

                // sleep(2);
                
                $vote = new Votes();

                $pdo = new PDO('mysql:host=127.0.0.1;dbname=willeb_cl','root','');
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $pdo->exec('SET NAMES UTF8');

                if(isset($_SERVER['REMOTE_ADDR'])) {
                        $voteIds = $vote->findAllVotes();
                
                        foreach($voteIds as $voteId) {
                                $id = $voteId['id'];

                                $sql = "SELECT `id`, `user_ip` FROM votes WHERE `id` = :id AND `user_ip` = :userIp";
                
                                $query = $pdo->prepare($sql);
                                
                                $query->fetch(\PDO::FETCH_ASSOC);
                        }
                }
                
                $sql = "SELECT `id` FROM album WHERE `id` = :id";
        
                $query = $pdo->prepare($sql);
        
                $query->execute([':id' => $_GET['albumId']]);
        
                $post = $query->fetch(\PDO::FETCH_ASSOC);
                
                $vote->updateVotecount($_GET['albumId']);

                do {
                        $id = uniqid();
                } while($pdo->prepare("SELECT `id` FROM votes WHERE id = $id") > 0);

                if($_POST['vote'] == 1) {
                        $success = $vote->like($id, $_POST['albumId'], $_SERVER['REMOTE_ADDR'], $_POST['vote']);
                }

                else {
                        $success = $vote->dislike($id, $_POST['albumId'], $_SERVER['REMOTE_ADDR'], $_POST['vote']);
                }

                $sql = "SELECT `likes`, `dislikes` FROM album WHERE `id` = :id";

                $req = $pdo->prepare($sql);
        
                $req->execute([':id' => $_GET['albumId']]);

                header('Content-type: application/json');

                $record = $req->fetch(\PDO::FETCH_ASSOC);

                $record['success'] = $success;

                die(json_encode($record));
        }

//*****E. Comment deletion form*****//
        public function deleteCommentform($commentId) {
                $delcommMessages = [];
                
                if(isset($_POST['deleteComment'])) {
                        $commentId = $_POST['commentId'];
                        
                        $deleteComment = $this->_comments->deleteComment($id);
                        $delcommMessages['success'] = ['Le commentaire sélectionné pour l\'album '.$_POST['albumTitle'].' et ses réponses ont été supprimés avec succès.'];
                }
                
                return $delcommMessages;
        }

//*****F. Answer deletion form*****//
        public function deleteAnswerform($answerId) {
                $delanswMessages = [];
                
                if(isset($_POST['deleteAnswer'])) {
                        $answerId = $_POST['answerId'];
                        
                        $deleteAnswer = $this->_comments->deleteAnswer($id);
                        $delanswMessages['success'] = ['La réponse sélectionnée au commentaire n° '.$_POST['commentId'].' pour l\'album '.$_POST['albumTitle'].' a été supprimée avec succès.'];
                }
                
                return $delanswMessages;
        }

}