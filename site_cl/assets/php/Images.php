<?php

use App\core\Session;

$pdo = new PDO("mysql:host=127.0.0.1;dbname=willeb_cl","root","");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->exec("SET NAMES UTF8");
    
$query = $pdo->prepare("SELECT `picture_name` FROM `album_pictures` WHERE `album_id` = :albumId");

$query->execute([":albumId" => $_GET["albumId"]]);

$pictures = $query->fetchAll();

foreach($pictures as $picture) {
    $picFolder = "C:/xampp/secure/albums_cl/pictures/";

    $truePicName = basename($picture["picture_name"]);
    
    $picPath = $picFolder.$truePicName;

    $data = file_get_contents($picPath);

    $picExtension = pathinfo($truePicName, PATHINFO_EXTENSION);

    $base64 ='data:image/'.$picExtension.';base64,'.base64_encode($data);

    echo '<img src="'.$base64.'">';
}