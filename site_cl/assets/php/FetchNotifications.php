<?php

session_start();

use App\core\Session;

$pdo = new PDO("mysql:host=127.0.0.1;dbname=willeb_cl","root","");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->exec("SET NAMES UTF8");

if(isset($_POST['view'])) {
    if($_POST["view"] !=  "") {
        $commentUpdate = $pdo->prepare("UPDATE `comments` SET `notification_read` = 1 WHERE `notification_read` = 0 AND
                                        `album_author_id` = :publisherId ORDER BY `id` DESC");
        $commentUpdate->execute([":publisherId" => $_SESSION["user"]["id"]]);

        $answerUpdate = $pdo->prepare("UPDATE `comment_answers` SET `notification_read` = 1 WHERE `notification_read` = 0 AND
                                        `comment_author_id` = :publisherId ORDER BY `id` DESC");
        $answerUpdate->execute([":publisherId" => $_SESSION["user"]["id"]]);

        $voteUpdate = $pdo->prepare("UPDATE `votes` SET `notification_read` = 1 WHERE `notification_read` = 0 AND
                                        `publisher_id` = :publisherId ORDER BY `id` DESC");
        $voteUpdate->execute([":publisherId" => $_SESSION["user"]["id"]]);
    }

    $query = $pdo->prepare("SELECT `album_author_id`, `album_title`, `comment` FROM `comments` WHERE `album_author_id` = :publisherId
                            ORDER BY `id` DESC");
    $query->execute([":publisherId" => $_SESSION["user"]["id"]]);

    $req = $pdo->prepare("SELECT `comment_author_id`, `album_title`, `answer` FROM `comment_answers`
                            WHERE `comment_author_id` = :publisherId ORDER BY `id` DESC");
    $req->execute([":publisherId" => $_SESSION["user"]["id"]]);

    $que = $pdo->prepare("SELECT `publisher_id`, `vote` FROM `votes` WHERE `publisher_id` = :publisherId
                            ORDER BY `id` DESC");
    $que->execute([":publisherId" => $_SESSION["user"]["id"]]);

    $output = "";

    if($query->RowCount() > 0 || $req->RowCount() > 0 || $que->RowCount() > 0) {
        $output = '<button id="delete-notifications" value="ON">Tout marquer comme lu</button>';

        while($commentRow = $query->fetch()) {
            $output .= 
                '<p>
                    <strong>Nouveau commentaire pour ton album '.$commentRow["album_title"].'</strong><br/>
                    <em>'.$commentRow["comment"].'</em>
                </p>'
            ;
        }

        while($answerRow = $req->fetch()) {
            $output .= 
                '<p>
                    <strong>Nouvelle réponse à ton commentaire pour l\'album '.$answerRow["album_title"].'</strong><br/>
                    <em>'.$answerRow["answer"].'</em>
                </p>'
            ;
        }

        while($voteRow = $que->fetch()) {
            if($voteRow["category"] == "albums") {
                if($voteRow["vote"] == 1) {
                    $output .= 
                        '<p>
                            <strong>Nouveau like pour ton album '.$voteRow["ref_content"].'</strong><br/>
                        </p>'
                    ;
                }

                else {
                    $output .= 
                        '<p>
                            <strong>Nouveau dislike pour ton album '.$voteRow["ref_content"].'</strong><br/>
                        </p>'
                    ;
                }
            }

            if($voteRow["category"] == "comments") {
                if($voteRow["vote"] == 1) {
                    $output .= 
                        '<p>
                            <strong>Nouveau like pour ton commentaire '.$voteRow["ref_content"].'</strong><br/>
                        </p>'
                    ;
                }

                else {
                    $output .= 
                        '<p>
                            <strong>Nouveau dislike pour ton commentaire '.$voteRow["ref_content"].'</strong><br/>
                        </p>'
                    ;
                }
            }

            if($voteRow["category"] == "comment_answers") {
                if($voteRow["vote"] == 1) {
                    $output .= 
                        '<p>
                            <strong>Nouveau like pour ta réponse '.$voteRow["ref_content"].'</strong><br/>
                        </p>'
                    ;
                }

                else {
                    $output .= 
                        '<p>
                            <strong>Nouveau dislike pour ta réponse '.$voteRow["ref_content"].'</strong><br/>
                        </p>'
                    ;
                }
            }
        }
    }

    $commentStatus = $pdo->prepare("SELECT * FROM `comments` WHERE `notification_read` = 0 AND `album_author_id` = :publisherId");
    $commentStatus->execute([":publisherId" => $_SESSION["user"]["id"]]);

    $answerStatus = $pdo->prepare("SELECT * FROM `comment_answers` WHERE `notification_read` = 0 AND `comment_author_id` = :publisherId");
    $answerStatus->execute([":publisherId" => $_SESSION["user"]["id"]]);

    $voteStatus = $pdo->prepare("SELECT * FROM `votes` WHERE `notification_read` = 0 AND `publisher_id` = :publisherId");
    $voteStatus->execute([":publisherId" => $_SESSION["user"]["id"]]);

    $count = $commentStatus->RowCount() + $answerStatus->RowCount() + $voteStatus->RowCount();
    $data = ["notification" => $output, "unseen_notification" => $count];

    echo json_encode($data);
}