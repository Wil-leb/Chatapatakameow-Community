<?php

$picFolder = "C:/xampp/secure/albums_cl/pictures/original/";

$truePicName = basename(htmlspecialchars(trim($currentPicture["picture_name"])));

$picPath = $picFolder.$truePicName;

$data = file_get_contents($picPath);

$picExtension = pathinfo($truePicName, PATHINFO_EXTENSION);

$base64 ='data:image/'.$picExtension.';base64,'.base64_encode($data);

echo '<img src="'.$base64.'" id="current-name">';