<?php

use App\core\Session;

$pdo = new PDO("mysql:host=127.0.0.1;dbname=willeb_cl","root","");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->exec("SET NAMES UTF8");
    
$query = $pdo->prepare("SELECT `thumbnail_name` FROM `album_thumbnails` WHERE `album_id` = :albumId");

$query->execute([":albumId" => $_GET["albumId"]]);

$pictures = $query->fetchAll();

foreach($thumbnails as $thumbnail) {
    $thumbFolder = "C:/xampp/secure/albums_cl/pictures/min/";

    $trueThumbName = basename($thumbnail["thumbnail_name"]);
    
    $thumbPath = $thumbFolder.$trueThumbName;

    $data = file_get_contents($thumbPath);

    $thumbExtension = pathinfo($trueThumbName, PATHINFO_EXTENSION);

    $base64 ='data:image/'.$thumbExtension.';base64,'.base64_encode($data);

    echo '<img src="'.$base64.'" id="slide">';

    // echo '<img src="'.$base64.'" id="slide" height="200" width="200">';
}