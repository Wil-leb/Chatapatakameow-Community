<?php

session_start();

use App\core\Session;

$pdo = new PDO("mysql:host=127.0.0.1;dbname=willeb_cl","root","");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->exec("SET NAMES UTF8");

if(isset($_POST['view'])) {
    if($_POST["view"] !=  "") {
        $update_query = $pdo->prepare("UPDATE `comments` SET `comment_notification` = ? WHERE `comment_notification` = ?");
        $update_query->execute([1, 0]);
    }

    $query = $pdo->prepare("SELECT comments.publisher_id, `album_title`, `comment` FROM `comments`
                            WHERE comments.publisher_id = :userId
                            GROUP BY comments.publisher_id
                            ORDER BY comments.id DESC LIMIT 5");
    $query->execute([":userId" => $_SESSION["user"]["id"]]);
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

    $status_query = $pdo->prepare("SELECT * FROM `comments` WHERE `comment_notification` = ?");
    $status_query->execute([0]);
    $count = $status_query->RowCount();
    $data = ["notification" => $output, "unseen_notification" => $count];

    echo json_encode($data);
}