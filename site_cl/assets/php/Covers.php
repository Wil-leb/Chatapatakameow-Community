<?php

use App\core\Session;

$pdo = new PDO("mysql:host=127.0.0.1;dbname=willeb_cl","root","");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->exec("SET NAMES UTF8");

$req = $pdo->prepare("SELECT `cover_name` FROM `album_covers` WHERE `album_id` = :albumId");

$req->execute([":albumId" => $album["id"]]);

$cover = $req->fetchColumn();

$covFolder = "C:/xampp/secure/albums_cl/covers/";

$trueCovName = basename($cover);

$covPath = $covFolder.$trueCovName;

$data = file_get_contents($covPath);

$covExtension = pathinfo($trueCovName, PATHINFO_EXTENSION);

$base64 ='data:image/'.$covExtension.';base64,'.base64_encode($data);

echo '<img src="'.$base64.'">';