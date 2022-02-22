<?php

namespace App\controller;
use App\model\{Album, User};
use \PDO;

require_once("./assets/php/Guid.php");

class AlbumFormController {
    
        public const COV_SECURE_PATH = "C:/xampp/secure/albums_cl/covers/";
        public const PIC_SECURE_PATH = "C:/xampp/secure/albums_cl/pictures/";

        protected Album $_album;
        
        public function __construct(Album $album) {
                $this->_album = $album;
        }

//*****A. Album addition*****//
        public function albumAdditionForm(array $data) {
                $addMessages = [];

                if($_POST["postAlbum"]) {
                        $pictures = $_FILES["pictures"];
                        $picsName = count($_FILES["pictures"]["name"]);
                
                        if(!$data["title"] || !$data["description"] || !$_FILES["cover"]["name"]) {
                                $addMessages["errors"][] = "Veuilles remplir tous les champs.";
                        }

                        for($i = 0; $i < $picsName; $i ++) {
                                if(!$pictures["name"][$i]) {
                                        $addMessages["errors"][] = "Veuilles uploader au moins une image.";
                                }
                        }

                        $allowedTitlelength = 30;
                        $titleLength = strlen($data["title"]);

                        if($titleLength > $allowedTitlelength) {
                                $addMessages["errors"][] = "Le titre ne doit pas dépasser 30 caractères, espaces comprises.";
                        }

                        $allowedDescrlength = 200;
                        $descrLength = strlen($data["description"]);

                        if($descrLength > $allowedDescrlength) {
                                $addMessages["errors"][] = "La description ne doit pas dépasser 200 caractères, espaces comprises.";
                        }

                        $textRegex = "/^[\p{L}\d\-\/();,:.!?\'&\"\s]+$/ui";

                        if($data["title"] && !preg_match($textRegex, $data["title"]) ||
                        $data["description"] && !preg_match($textRegex, $data["description"])) {
                                $addMessages["errors"][] = "Caractères autorisés pour le titre et la description : lettres, chiffres, tirets, slash, parenthèses, point-virgules, virgules, doubles points, points, points d'exclamation, points d'interrogation, apostrophes, esperluettes, guillemets droits et espaces.";
                        }

                        $allowedCovsize = 3145728;
                        $covSize = filesize($_FILES["cover"]["tmp_name"]);

                        if($covSize > $allowedCovsize) {
                                $addMessages["errors"][] = "La couverture ne doit pas dépasser 3 Mo.";
                        }
                        
                        $allowedPicsize = 31457280;
                        $picSize = 0;

                        for($i = 0; $i < $picsName; $i ++) {
                                $picSize += filesize($_FILES["pictures"]["tmp_name"][$i]);

                                if($picSize > $allowedPicsize) {
                                        $addMessages["errors"][] = "Les images autres que la couverture ne doivent pas dépasser 30 Mo au total.";
                                        return $addMessages;
                                }
                        }
                        
                        if(!isset($data["acceptRules"]) || !isset($data["acceptPolicy"])) {
                                $addMessages["errors"][] = "Veuilles accepter le règlement général et la politique de confidentialité.";
                        }

                        $imgRegex = "/^[a-z\d\-_][^.\s]*\.(png$)|^[a-z\d\-_][^.\s]*\.(jpe?g$)/i";

                        for($i = 0; $i < $picsName; $i ++) {
                                if($_FILES["cover"]["error"] === 0 && $_FILES["pictures"]["error"][0] === 0) {
                                        $covType;
                                        $createCover;

                                        $covInfo = finfo_open(FILEINFO_MIME_TYPE);
                                        $covMime = finfo_file($covInfo, $_FILES["cover"]["tmp_name"]);
                                        finfo_close($covInfo);

                                        if(!preg_match($imgRegex, $_FILES["cover"]["name"])
                                        || str_contains($_FILES["cover"]["name"], " ")
                                        || !in_array($_FILES["cover"]["type"], ["image/jpeg", "image/png"])
                                        || strpos($covMime, "image/") !== 0) {
                                                $addMessages["errors"][] = "Caractères autorisés pour le nom de couverture : lettres sans accent / sans trémas / sans cédille, chiffres, tirets et underscores. Fichiers autorisés : images .jpg, .jpeg ou .png.";

                                                return $addMessages;
                                        }

                                        $picType;
                                        $createPic;
                                        
                                        $picInfo = finfo_open(FILEINFO_MIME_TYPE);
                                        $picMime = finfo_file($picInfo, $pictures["tmp_name"][$i]);
                                        finfo_close($picInfo);

                                        if(!preg_match($imgRegex, $pictures["name"][$i])
                                        || str_contains($pictures["name"][$i], " ")
                                        || !in_array($pictures["type"][$i], ["image/jpeg", "image/png"])
                                        || strpos($picMime, "image/") !== 0) {
                                                $addMessages["errors"][] = "Caractères autorisés pour les noms d'image : lettres sans accent / sans trémas / sans cédille, chiffres, tirets et underscores. Fichiers autorisés : images .jpg, .jpeg ou .png.";

                                                return $addMessages;
                                        }

                                        if(empty($addMessages["errors"])) {
                                                $covName = guidv4().".".pathinfo($_FILES["cover"]["name"], PATHINFO_EXTENSION);

                                                if(preg_match($imgRegex, $covName) && $_FILES["cover"]["type"] == "image/jpeg"
                                                && strpos($covMime, "image/") === 0) {
                                                        $covType = "jpeg";
                                                        $createCover = imagecreatefromjpeg($_FILES["cover"]["tmp_name"]);
                                                        imagejpeg($createCover, self::COV_SECURE_PATH.$covName);
                                                }

                                                elseif(preg_match($imgRegex, $covName) && $_FILES["cover"]["type"] == "image/png"
                                                && strpos($covMime, "image/") === 0) {
                                                        $covType = "png";
                                                        $createCover = imagecreatefrompng($_FILES["cover"]["tmp_name"]);
                                                        imagepng($createCover, self::COV_SECURE_PATH.$covName);
                                                }

                                                else {
                                                        echo $covName;
                                                        $addMessages["errors"][] = "La couverture n'a pas pu être uploadée.";

                                                        return $addMessages;
                                                }

                                                $pdo = new PDO("mysql:host=127.0.0.1;dbname=willeb_cl","root","");
                                                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                                $pdo->exec("SET NAMES UTF8");

                                                do {
                                                        $id = uniqid();
                                                } while($pdo->prepare("SELECT `id` FROM `album` WHERE `id` = $id") > 0);

                                                $albumAddition = $this->_album->addAlbum($id, $_SESSION["user"]["id"], $_SESSION["user"]["login"], $data["title"], $data["description"]);

                                                do {
                                                        $coverId = uniqid();
                                                } while($pdo->prepare("SELECT `id` FROM `album_covers` WHERE `id` = $coverId") > 0);

                                                $latestAlbumId = $id;

                                                $newcover = $pdo->prepare("INSERT INTO `album_covers` (`id`, `album_id`, `cover_name`)
                                                                                VALUES (:id, :albumId, :coverName)");
                                                
                                                $newcover->execute([
                                                                        ":id" => $coverId,
                                                                        ":albumId" => $latestAlbumId,
                                                                        ":coverName" => $covName
                                                                        ]);

                                                for($idx = 0; $idx < $picsName; $idx ++) {
                                                        $pictures[$idx] = guidv4().".".pathinfo($pictures["name"][$idx], PATHINFO_EXTENSION);

                                                        if(preg_match($imgRegex, $pictures["name"][$idx])
                                                        && $pictures["type"][$idx] == "image/jpeg"
                                                        && strpos($picMime, "image/") === 0) {
                                                                $picType = "jpeg";
                                                                $createPic = imagecreatefromjpeg($pictures["tmp_name"][$idx]);
                                                                imagejpeg($createPic, self::PIC_SECURE_PATH.$pictures[$idx]);
                                                        }

                                                        elseif(preg_match($imgRegex, $pictures["name"][$idx])
                                                        && $pictures["type"][$idx] == "image/png"
                                                        && strpos($picMime, "image/") === 0) {
                                                                $picType = "png";
                                                                $createPic = imagecreatefrompng($pictures["tmp_name"][$idx]);
                                                                imagepng($createPic, self::PIC_SECURE_PATH.$pictures[$idx]);
                                                        }

                                                        else {
                                                                print_r($pictures);
                                                                $addMessages["errors"][] = "Aucune image n'a pu être uploadée.";
                                                
                                                                return $addMessages;
                                                        }

                                                        do {
                                                                $pictureId = uniqid();
                                                        } while($pdo->prepare("SELECT `id` FROM `album_pictures`
                                                                                WHERE `id` = $pictureId") > 0);

                                                        $newPicture = $pdo->prepare("INSERT INTO `album_pictures` (`id`, `album_id`,
                                                                                        `picture_name`)
                                                                                        VALUES (:id, :albumId, :pictureName)");
                                                        
                                                        $newPicture->execute([
                                                                        ":id" => $pictureId,
                                                                        ":albumId" => $latestAlbumId,
                                                                        ":pictureName" => $pictures[$idx]
                                                                        ]);
                                                }

                                                $addMessages["success"] = ["L'album a été publié avec succès."];

                                                return $addMessages;
                                        }
                                }
                        }

                        return $addMessages;
                }
        }

//*****B. Album deletion*****//
        public function albumDeletionForm($albumId) {  
                $deleteMessages = [];

                if(isset($_POST["deleteAlbum"])) {
                        $albumId = $_POST["albumId"];
                        $coverName = $_POST["cover"];
                        $pictures = $_POST["picture"];
                        
                        $pdo = new PDO("mysql:host=127.0.0.1;dbname=willeb_cl","root","");
                        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                        $pdo->exec("SET NAMES UTF8");
            
                        $query = $pdo->prepare("SELECT `id` FROM `album` WHERE `id` = :albumId");
                        $query->execute([":albumId" => $albumId]);
                        $trueAlbId = $query->fetchColumn();

                        $findCover = $this->_album->findAlbumCover($albumId);
                        $trueCovName = $findCover["cover_name"];

                        $picNames = explode(" ", $pictures);

                        // echo ''.$albumId.'<br>'.$trueAlbId.'<br>'.$coverName.'<br>'.$trueCovName.'<br>'.$picNames.'';

                        $covFolder = self::COV_SECURE_PATH;
                        $picFolder = self::PIC_SECURE_PATH;
                        $minAge = 10;

                        function deleteCover($covFolder, $trueCovName, $minAge) {
                                $directory = opendir($covFolder);
                                
                                        while(false !== ($trueCovName = readdir($directory))) {
                                                $path = $covFolder.$trueCovName;
                                                $info = pathinfo($path);
                                                $coverName = $_POST["cover"];
                                                $fileAge = time() - filemtime($path);

                                                if($trueCovName != "." && $trueCovName != ".." && !is_dir($trueCovName) && $coverName == $trueCovName && $fileAge > $minAge) {
                                                        unlink($path);
                                                }
                                        }
                                        
                                closedir($directory);
                        }
        
                        function deletePicture() {
                        }

                        foreach($picNames as $picName) {
                                if(!empty($picName)) {
                                        $que = $pdo->prepare("SELECT `picture_name` FROM `album_pictures` WHERE `picture_name` = :pictureName");
                                        $que->execute([":pictureName" => $picName]);
                                        $truePicName = $que->fetchColumn();

                                        if($albumId != $trueAlbId || $albumId == null || $coverName != $trueCovName || $coverName == null || $picName != $truePicName || strlen($pictures) < strlen(implode(" ", $picNames))) {
                                                die("Hacking attempt!");
                                        }
                        
                                        else {
                                                $directory = opendir($picFolder);
                                                                
                                                        while(false !== ($truePicName = readdir($directory))) {
                                                                $path = $picFolder.$truePicName;
                                                                $info = pathinfo($path);
                                                                $fileAge = time() - filemtime($path);
                
                                                                if($truePicName != "." && $truePicName != ".." &&
                                                                        !is_dir($truePicName) && $picName == $truePicName
                                                                        && $fileAge > $minAge) {
                                                                        unlink($path);
                                                                }
                                                        }
                                                        
                                                closedir($directory);

                                                $findAlbum = $this->_album->findAlbumById($albumId);
                                                
                                                $deleteAlbum = $this->_album->deleteAlbum($albumId);
                                                deleteCover($covFolder, $trueCovName, $minAge);
                                                deletePicture($picFolder, $truePicName, $minAge);
        
                                                $deleteMessages["success"] = ["L'album ".$findAlbum["title"]." a été supprimé avec succès."];

                                                echo '<br>'.$albumId.'<br>'.$trueAlbId.'<br>'.$coverName.'<br>'.$trueCovName.'<br>'.$pictures.'<br>';

                                                var_dump($truePicName);
                                        }
                                }
                        }

                        return $deleteMessages;
                }
        }

//*****END OF THE CLASS*****//    
}