<?php

session_start();

use App\core\Session;

$pdo = new PDO("mysql:host=127.0.0.1;dbname=willeb_cl","root","");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->exec("SET NAMES UTF8");

if(isset($_POST['view'])) {
    if($_POST["view"] !=  "") {
        // $update_query = $pdo->prepare("UPDATE `comments` SET `notification_read` = ? WHERE `notification_read` = ?");
        // $update_query->execute([1, 0]);
        $update_query = $pdo->prepare("UPDATE `comments` SET `notification_read` = 1 WHERE `notification_read` = 0 AND
                                        `album_author_id` = :publisherId ORDER BY `id` DESC");
        $update_query->execute([":publisherId" => $_SESSION["user"]["id"]]);
    }

    // $query = $pdo->prepare("SELECT `album_author_id`, `album_title`, `comment` FROM `comments`
    //                         WHERE `album_author_id` = :publisherId
    //                         ORDER BY `id` DESC LIMIT 5");
    $query = $pdo->prepare("SELECT `album_author_id`, `album_title`, `comment` FROM `comments` WHERE `album_author_id` = :publisherId
                            ORDER BY `id` DESC");
    $query->execute([":publisherId" => $_SESSION["user"]["id"]]);
    $output = "";

    if($query->RowCount() > 0) {
        $output = '<button id="delete-notifications" value="ON">Tout marquer comme lu</button>';

        while($row = $query->fetch()) {
            $output .= 
                '<p>
                    <strong>Nouveau commentaire pour ton album '.$row["album_title"].'</strong><br/>
                    <em>'.$row["comment"].'</em>
                </p>'
            ;
        }
    }
    
    // else {
    //     $output = '<p class="no-content">Aucune notification pour le moment</p>';
    // }

    // $status_query = $pdo->prepare("SELECT * FROM `comments` WHERE `notification_read` = ? GROUP BY `album_author_id`");
    $status_query = $pdo->prepare("SELECT * FROM `comments` WHERE `notification_read` = 0 AND `album_author_id` = :publisherId");
    // $status_query->execute([0]);
    $status_query->execute([":publisherId" => $_SESSION["user"]["id"]]);
    $count = $status_query->RowCount();
    $data = ["notification" => $output, "unseen_notification" => $count];

    echo json_encode($data);
}