<?php

namespace App\controller;
use App\model\{Albums, Users};
use \PDO;

require_once("./assets/php/Guid.php");

class AlbumModifController {
    
        public const COV_SECURE_PATH = "C:/xampp/secure/albums_cl/covers/";
        public const PIC_SECURE_PATH = "C:/xampp/secure/albums_cl/pictures/original/";
        public const THUMB_SECURE_PATH = "C:/xampp/secure/albums_cl/pictures/min/";

        protected Albums $_album;
        
        public function __construct(Albums $album) {
                $this->_album = $album;
        }
  
//*****A. Description modification*****//
        public function descriptionModifForm() {
                $modifMessages = [];

                if($_POST["albumChanges"]) {
                        $albumId = $_GET["albumId"];

                        $pdo = new PDO("mysql:host=127.0.0.1;dbname=willeb_cl","root","");
                        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                        $pdo->exec("SET NAMES UTF8");
            
                        $query = $pdo->prepare("SELECT `id` FROM `albums` WHERE `id` = :albumId");
                        $query->execute([":albumId" => $albumId]);
                        $trueAlbId = $query->fetchColumn();
            
                        if($albumId != $trueAlbId || $albumId == null) {
                                die("Hacking attempt!");
                        }
            
                        else {
                                if(!$_POST["title"] || !$_POST["description"] || !$_FILES["cover"]["name"]) {
                                        $modifMessages["errors"][] = "Veuilles remplir tous les champs.";
                                }

                                $allowedTitlelength = 30;
                                $titleLength = strlen($_POST["title"]);

                                if($titleLength > $allowedTitlelength) {
                                        $modifMessages["errors"][] = "Le titre ne doit pas dépasser 30 caractères, espaces comprises.";
                                }

                                $allowedDescrlength = 200;
                                $descrLength = strlen($_POST["description"]);

                                if($descrLength > $allowedDescrlength) {
                                        $modifMessages["errors"][] = "La description ne doit pas dépasser 200 caractères, espaces comprises.";
                                }

                                $textRegex = "/^[\p{L}\d\-\/();,:.!?\'&\"\s]+$/ui";

                                if($_POST["title"] && !preg_match($textRegex, $_POST["title"]) ||
                                $_POST["description"] && !preg_match($textRegex, $_POST["description"])) {
                                        $modifMessages["errors"][] = "Caractères autorisés pour le titre et la description : lettres, chiffres, tirets, slash, parenthèses, point-virgules, virgules, doubles points, points, points d'exclamation, points d'interrogation, apostrophes, esperluettes, guillemets droits et espaces.";
                                }

                                $allowedCovsize = 3145728;
                                $covSize = filesize($_FILES["cover"]["tmp_name"]);

                                if($covSize > $allowedCovsize) {
                                        $modifMessages["errors"][] = "La couverture ne doit pas dépasser 3 Mo.";

                                        return $modifMessages;
                                }

                                if(!isset($_POST["acceptRules"]) || !isset($_POST["acceptPolicy"])) {
                                        $modifMessages["errors"][] = "Veuilles accepter le règlement général et la politique de confidentialité.";
                                }

                                if($_FILES["cover"]["error"] === 0) {
                                        $findAlbum = $this->_album->findAlbumById($albumId);

                                        $folder = self::COV_SECURE_PATH;
                                        $minAge = 10;
        
                                        $findCover = $this->_album->findAlbumCover($albumId);
                                        $trueCovName = $findCover["cover_name"];
        
                                        function deleteCover($folder, $trueCovName, $minAge) {
                                                $directory = opendir($folder);
        
                                                $covName = $trueCovName;
                                                
                                                        while(false !== ($trueCovName = readdir($directory))) {
                                                                $path = $folder.$trueCovName;
                                                                $info = pathinfo($path);
                                                                // $trueCovName = $_POST["coverName"];
                                                                $fileAge = time() - filemtime($path);
        
                                                                if($trueCovName != "." && $trueCovName != ".." && !is_dir($trueCovName) && $trueCovName == $covName && $fileAge > $minAge) {
                                                                        unlink($path);
                                                                }
                                                        }
                                                        
                                                closedir($directory);
                                        }

                                        $covType;
                                        $createCover;

                                        $newCovName = guidv4().".".pathinfo($_FILES["cover"]["name"], PATHINFO_EXTENSION);

                                        $imgRegex = "/^[a-z\d\-_][^.\s]*\.(png$)|^[a-z\d\-_][^.\s]*\.(jpe?g$)/i";

                                        $covInfo = finfo_open(FILEINFO_MIME_TYPE);
                                        $covMime = finfo_file($covInfo, $_FILES["cover"]["tmp_name"]);
                                        finfo_close($covInfo);

                                        if(!preg_match($imgRegex, $_FILES["cover"]["name"]) ||
                                        str_contains($newCovName, " ") ||
                                        !in_array($_FILES["cover"]["type"], ["image/jpeg","image/png"]) ||
                                        strpos($covMime, "image/") !== 0) {
                                                $modifMessages["errors"][] = "Caractères autorisés pour le nom de couverture : lettres sans accent / sans trémas / sans cédille, chiffres, tirets et underscores. Fichiers autorisés : images .jpg, .jpeg ou .png.";

                                                return $modifMessages;
                                        }

                                        if(empty($modifMessages["errors"])) {
                                                if(preg_match($imgRegex, $newCovName) && $_FILES["cover"]["type"] == "image/jpeg"
                                                && strpos($covMime, "image/") === 0) {
                                                        deleteCover($folder, $trueCovName, $minAge);
                                                        $covType = "jpeg";
                                                        $createCover = imagecreatefromjpeg($_FILES["cover"]["tmp_name"]);
                                                        imagejpeg($createCover, $folder.$newCovName);
                                                }

                                                elseif(preg_match($imgRegex, $newCovName) && $_FILES["cover"]["type"] == "image/png"
                                                && strpos($covMime, "image/") === 0) {
                                                        deleteCover($folder, $trueCovName, $minAge);
                                                        $covType = "png";
                                                        $createCover = imagecreatefrompng($_FILES["cover"]["tmp_name"]);
                                                        imagepng($createCover, $folder.$newCovName);
                                                }

                                                else {
                                                        $modifMessages["errors"][] = "La couverture n'a pas pu être uploadée.";

                                                        return $modifMessages;
                                                }

                                                $descriptionModification = $this->_album->updateAlbum($albumId, $_SESSION["user"]["id"], $_SESSION["user"]["login"], $_POST["title"], $_POST["description"]);

                                                $coverModification = $this->_album->replaceCover($findCover["id"], $albumId, $newCovName);

                                                $modifMessages["success"] = ["La description de l'album ".$findAlbum["title"]." a été modifiée avec succès."];

                                                return $modifMessages; 
                                        }
                                }
                        }
                                
                        return $modifMessages;
                }
        }

//*****C. Picture deletion*****//
        public function pictureDeletionForm() {  
                $deleteMessages = [];

                if(isset($_POST["deletePicture"])) {
                        $albumId = $_GET["albumId"];
                        $pictureId = $_POST["pictureId"];
                        $picName = $_POST["pictureName"];
                        $thumbnailId = $_POST["thumbnailId"];
                        $thumbName = $_POST["thumbName"];

                        $pdo = new PDO("mysql:host=127.0.0.1;dbname=willeb_cl","root","");
                        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                        $pdo->exec("SET NAMES UTF8");
            
                        $query = $pdo->prepare("SELECT `id` FROM `albums` WHERE `id` = :albumId");
                        $query->execute([":albumId" => $albumId]);
                        $trueAlbId = $query->fetchColumn();

                        $req = $pdo->prepare("SELECT `id`, `picture_name` FROM `album_pictures` WHERE `id` = :pictureId");
                        $req->execute([":pictureId" => $pictureId]);
                        $result = $req->fetch();
                        $truePicId = $result["id"];
                        $truePicName = $result["picture_name"];

                        $que = $pdo->prepare("SELECT `id`, `thumbnail_name` FROM `album_thumbnails` WHERE `id` = :thumbnailId");
                        $que->execute([":thumbnailId" => $thumbnailId]);
                        $line = $que->fetch();
                        $trueThumbId = $line["id"];
                        $trueThumbName = $line["thumbnail_name"];
            
                        if($albumId != $trueAlbId || $albumId == null || $pictureId != $truePicId || $pictureId == null || $thumbnailId != $trueThumbId || $thumbnailId == null ||$truePicName != $picName || $picName == null || $trueThumbName != $thumbName || $thumbName == null) {
                                die("Hacking attempt!");
                        }
            
                        else {
                                $findAlbum = $this->_album->findAlbumById($albumId);

                                $picFolder = self::PIC_SECURE_PATH;
                                $thumbFolder = self::THUMB_SECURE_PATH;
                                $minAge = 10;

                                function deletePicture($picFolder, $truePicName, $minAge) {
                                        $directory = opendir($picFolder);
                                        
                                                while(false !== ($truePicName = readdir($directory))) {
                                                        $path = $picFolder.$truePicName;
                                                        $info = pathinfo($path);
                                                        $picName = $_POST["pictureName"];
                                                        $fileAge = time() - filemtime($path);

                                                        if($truePicName != "." && $truePicName != ".." && !is_dir($truePicName)
                                                        && $truePicName == $picName && $fileAge > $minAge) {
                                                                unlink($path);
                                                        }
                                                }
                                                
                                        closedir($directory);
                                }

                                function deleteThumbnail($thumbFolder, $trueThumbName, $minAge) {
                                        $directory = opendir($thumbFolder);
                                        
                                                while(false !== ($trueThumbName = readdir($directory))) {
                                                        $path = $thumbFolder.$trueThumbName;
                                                        $info = pathinfo($path);
                                                        $thumbName = $_POST["thumbName"];
                                                        $fileAge = time() - filemtime($path);

                                                        if($trueThumbName != "." && $trueThumbName != ".." && !is_dir($trueThumbName)
                                                        && $trueThumbName == $thumbName && $fileAge > $minAge) {
                                                                unlink($path);
                                                        }
                                                }
                                                
                                        closedir($directory);
                                }

                                deletePicture($picFolder, $truePicName, $minAge);
                                $deletePicture = $this->_album->deletePicture($pictureId);
                                deleteThumbnail($thumbFolder, $trueThumbName, $minAge);
                                $deleteThumbnail = $this->_album->deleteThumbnail($thumbnailId);

                                $deleteMessages["success"] = ["L'image sélectionnée de l'album ".$findAlbum["title"]." a été supprimée avec succès."];
                        }

                        return $deleteMessages;
                }        
        }

//*****D. Picture replacement form*****//

        public function pictureReplacementForm() {
                $replaceMessages = [];

                if($_POST["pictureChange"]) {
                        $albumId = $_GET["albumId"];
                        $pictureId = $_POST["pictureId"];
                        $picName = $_POST["currentPic"];
                        $thumbnailId = $_POST["thumbnailId"];
                        $thumbName = $_POST["currentThumb"];
                        $newThumbnail;

                        $pdo = new PDO("mysql:host=127.0.0.1;dbname=willeb_cl","root","");
                        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                        $pdo->exec("SET NAMES UTF8");
            
                        $query = $pdo->prepare("SELECT `id` FROM `albums` WHERE `id` = :albumId");
                        $query->execute([":albumId" => $albumId]);
                        $trueAlbId = $query->fetchColumn();

                        $req = $pdo->prepare("SELECT `id`, `picture_name` FROM `album_pictures` WHERE `id` = :pictureId");
                        $req->execute([":pictureId" => $pictureId]);
                        $result = $req->fetch();
                        $truePicId = $result["id"];
                        $truePicName = $result["picture_name"];

                        $que = $pdo->prepare("SELECT `id`, `thumbnail_name` FROM `album_thumbnails` WHERE `id` = :thumbnailId");
                        $que->execute([":thumbnailId" => $thumbnailId]);
                        $line = $que->fetch();
                        $trueThumbId = $line["id"];
                        $trueThumbName = $line["thumbnail_name"];

                        if($albumId != $trueAlbId || $albumId == null || $pictureId != $truePicId || $pictureId == null || $thumbnailId != $trueThumbId || $thumbnailId == null ||$truePicName != $picName || $picName == null || $trueThumbName != $thumbName || $thumbName == null) {
                                die("Hacking attempt!");
                        }

                        else {
                                $findAlbum = $this->_album->findAlbumById($albumId);

                                $picFolder = self::PIC_SECURE_PATH;
                                $thumbFolder = self::THUMB_SECURE_PATH;
                                $minAge = 10;

                                function deletePicture($picFolder, $truePicName, $minAge) {
                                        $directory = opendir($picFolder);
                                
                                        while(false !== ($truePicName = readdir($directory))) {
                                                $path = $picFolder.$truePicName;
                                                $info = pathinfo($path);
                                                $picName = $_POST["currentPic"];
                                                $fileAge = time() - filemtime($path);

                                                if($truePicName != "." && $truePicName != ".." && !is_dir($truePicName)
                                                && $truePicName == $picName && $fileAge > $minAge) {
                                                        unlink($path);
                                                }
                                        }
                                        
                                        closedir($directory);
                                }

                                function deleteThumbnail($thumbFolder, $trueThumbName, $minAge) {
                                        $directory = opendir($thumbFolder);
                                
                                        while(false !== ($trueThumbName = readdir($directory))) {
                                                $path = $thumbFolder.$trueThumbName;
                                                $info = pathinfo($path);
                                                $thumbName = $_POST["currentThumb"];
                                                $fileAge = time() - filemtime($path);

                                                if($trueThumbName != "." && $trueThumbName != ".." && !is_dir($trueThumbName)
                                                && $trueThumbName == $thumbName && $fileAge > $minAge) {
                                                        unlink($path);
                                                }
                                        }
                                        
                                        closedir($directory);
                                }
                                        
                                if(!$_FILES["newPicture"]["name"]) {
                                        $replaceMessages["errors"][] = "Veuilles remplir tous les champs.";
                                }

                                $allowedPicsize = 31457280;

                                $que = $pdo->prepare("SELECT `picture_name` FROM `album_pictures` WHERE `album_id` = :albumId");
                                $que->execute([":albumId" => $albumId]);
                                $findPictures = $que->fetchAll();
                                
                                $currentPicSize = 0;

                                foreach($findPictures as $findPicture) {
                                        $picSize = filesize($picFolder.$findPicture["picture_name"]);
                                        $currentPicSize += $picSize;
                                }

                                $totalPicSize = $currentPicSize + filesize($_FILES["newPicture"]["tmp_name"]);

                                if($totalPicSize > $allowedPicsize) {
                                        $replaceMessages["errors"][] = "Le total des images existantes et remplacées ne doit pas dépasser 30 Mo.";

                                        return $replaceMessages;
                                }

                                if(!isset($_POST["acceptRules"]) || !isset($_POST["acceptPolicy"])) {
                                        $replaceMessages["errors"][] = "Veuilles accepter le règlement général et la politique de confidentialité.";
                                }

                                if($_FILES["newPicture"]["error"] === 0) {
                                        $picType;
                                        $createPicture;
                                        $createThumbnail;
                                        $newWidth = 200;
                                        $newHeight = 200;

                                        $newPicName = guidv4().".".pathinfo($_FILES["newPicture"]["name"], PATHINFO_EXTENSION);

                                        $imgRegex = "/^[a-z\d\-_][^.\s]*\.(png$)|^[a-z\d\-_][^.\s]*\.(jpe?g$)/i";

                                        $picInfo = finfo_open(FILEINFO_MIME_TYPE);
                                        $picMime = finfo_file($picInfo, $_FILES["newPicture"]["tmp_name"]);
                                        finfo_close($picInfo);

                                        if(!preg_match($imgRegex, $_FILES["newPicture"]["name"]) ||
                                        str_contains($newPicName, " ") ||
                                        !in_array($_FILES["newPicture"]["type"], ["image/jpeg","image/png"]) ||
                                        strpos($picMime, "image/") !== 0) {
                                                $replaceMessages["errors"][] = "Caractères autorisés pour le nom d'image : lettres sans accent / sans trémas / sans cédille, chiffres, tirets et underscores. Fichiers autorisés : images .jpg, .jpeg ou .png.";

                                                return $replaceMessages;
                                        }

                                        if(empty($replaceMessages["errors"])) {
                                                if(preg_match($imgRegex, $newPicName) && $_FILES["newPicture"]["type"] == "image/jpeg"
                                                && strpos($picMime, "image/") === 0) {
                                                        deletePicture($picFolder, $truePicName, $minAge);
                                                        deleteThumbnail($thumbFolder, $trueThumbName, $minAge);

                                                        $picType = "jpeg";
                                                        $createPicture = imagecreatefromjpeg($_FILES["newPicture"]["tmp_name"]);
                                                        imagejpeg($createPicture, $picFolder.$newPicName);

                                                        list($originWidth, $originHeight) = getimagesize($picFolder.$newPicName);

                                                        $originRatio = $originWidth / $originHeight;

                                                        if($newWidth / $newHeight > $originRatio) {
                                                                $newWidth = $newHeight * $originRatio;
                                                        }

                                                        else {
                                                                $newHeight = $newWidth / $originRatio;
                                                        }

                                                        $resizedPic = imagecreatetruecolor($newWidth, $newHeight);

                                                        $newThumbnail = guidv4()."_min.".pathinfo($_FILES["newPicture"]["name"], PATHINFO_EXTENSION);

                                                        $createThumbnail = imagecreatefromjpeg($_FILES["newPicture"]["tmp_name"]);
                                                        imagecopyresampled($resizedPic, $createThumbnail, 0, 0, 0, 0, $newWidth, $newHeight, $originWidth, $originHeight);
                                                        imagejpeg($resizedPic, $thumbFolder.$newThumbnail, 100);
                                                }

                                                elseif(preg_match($imgRegex, $newPicName) && $_FILES["newPicture"]["type"] == "image/png" && strpos($picMime, "image/") === 0) {
                                                        deletePicture($picFolder, $truePicName, $minAge);
                                                        deleteThumbnail($thumbFolder, $trueThumbName, $minAge);

                                                        $picType = "png";
                                                        $createPicture = imagecreatefrompng($_FILES["newPicture"]["tmp_name"]);
                                                        imagepng($createPicture, $picFolder.$newPicName);

                                                        list($originWidth, $originHeight) = getimagesize($picFolder.$newPicName);

                                                        $originRatio = $originWidth / $originHeight;

                                                        if($newWidth / $newHeight > $originRatio) {
                                                                $newWidth = $newHeight * $originRatio;
                                                        }

                                                        else {
                                                                $newHeight = $newWidth / $originRatio;
                                                        }

                                                        $resizedPic = imagecreatetruecolor($newWidth, $newHeight);

                                                        $newThumbnail = guidv4()."_min.".pathinfo($_FILES["newPicture"]["name"], PATHINFO_EXTENSION);

                                                        $createThumbnail = imagecreatefrompng($_FILES["newPicture"]["tmp_name"]);
                                                        imagecopyresampled($resizedPic, $createThumbnail, 0, 0, 0, 0, $newWidth, $newHeight, $originWidth, $originHeight);
                                                        imagepng($resizedPic, $thumbFolder.$newThumbnail, 9);
                                                }

                                                else {
                                                        $modifMessages["errors"][] = "L'image n'a pas pu être uploadée.";
                                                        return $replaceMessages;
                                                }

                                                $pictureModification = $this->_album->replacePicture($pictureId, $albumId, $newPicName);
                                                $thumbnailModification = $this->_album->replaceThumbnail($thumbnailId, $albumId, $newThumbnail);

                                                $replaceMessages["success"] = ["L'image sélectionnée de l'album ".$findAlbum["title"]." a été modifiée avec succès."];

                                                return $replaceMessages; 
                                        }
                                }
                        }
                                
                        return $replaceMessages;
                }
        }
                
//*****E. Picture addition*****//
        public function extraPicturesForm(array $data) {
                $extraPicMessages = [];
                
                if($_POST["pictureAddition"]) {
                        $findAlbum = $this->_album->findAlbumById($_GET["albumId"]);
                        $extraPics = $_FILES["extraPictures"];
                        $thumbnails;
                        $extraPicsName = count($_FILES["extraPictures"]["name"]);
                
                        for($i = 0; $i < $extraPicsName; $i ++) {
                                if(!$extraPics["name"][$i]) {
                                        $extraPicMessages["errors"][] = "Veuilles uploader au moins une image.";
                                }
                        }

                        $pdo = new PDO("mysql:host=127.0.0.1;dbname=willeb_cl","root","");
                        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                        $pdo->exec("SET NAMES UTF8");

                        $que = $pdo->prepare("SELECT `picture_name` FROM `album_pictures` WHERE `album_id` = :albumId");
                        $que->execute([":albumId" => $_GET["albumId"]]);
                        $findPictures = $que->fetchAll();

                        $allowedPicsize = 31457280;
                        $currentPicSize = 0;

                        foreach($findPictures as $findPicture) {
                                $picSize = filesize(self::PIC_SECURE_PATH.$findPicture["picture_name"]);
                                $currentPicSize += $picSize;
                        }

                        for($i = 0; $i < $extraPicsName; $i ++) {
                                $totalPicSize = $currentPicSize + filesize($extraPics["tmp_name"][$i]);
        
                                if($totalPicSize > $allowedPicsize) {
                                        $extraPicMessages["errors"][] = "Le total des images existantes et ajoutées ne doit pas dépasser 30 Mo.";
        
                                        return $extraPicMessages;
                                }
                        }
                        
                        if(!isset($data["acceptRules"]) || !isset($data["acceptPolicy"])) {
                                $extraPicMessages["errors"][] = "Veuilles accepter le règlement général et la politique de confidentialité.";
                        }

                        $imgRegex = "/^[a-z\d][^.\s]*\.(png$)|^[a-z\d][^.\s]*\.(jpe?g$)/i";
                        
                        for($i = 0; $i < $extraPicsName; $i ++) {
                                if($_FILES["extraPictures"]["error"][0] === 0) {
                                        $picType;
                                        $createPic;
                                        $createThumbnail;
                                        $newWidth = 200;
                                        $newHeight = 200;
                                        
                                        $picInfo = finfo_open(FILEINFO_MIME_TYPE);
                                        $picMime = finfo_file($picInfo, $extraPics["tmp_name"][$i]);
                                        finfo_close($picInfo);

                                        if(!preg_match($imgRegex, $extraPics["name"][$i]) ||
                                        str_contains($extraPics["name"][$i], " ") ||
                                        !in_array($extraPics["type"][$i], ["image/jpeg", "image/png"]) ||
                                        strpos($picMime, "image/") !== 0) {
                                                $extraPicMessages["errors"][] = "Caractères autorisés pour les noms d'image : lettres sans accent / sans trémas / sans cédille, chiffres, tirets et underscores. Fichiers autorisés : images .jpg, .jpeg ou .png.";

                                                return $extraPicMessages;
                                        }

                                        if(empty($extraPicMessages["errors"])) {
                                                for($idx = 0; $idx < $extraPicsName; $idx ++) {
                                                        $extraPics[$idx] = guidv4().".".pathinfo($extraPics["name"][$idx], PATHINFO_EXTENSION);

                                                        if(preg_match($imgRegex, $extraPics["name"][$idx])
                                                        && $extraPics["type"][$idx] == "image/jpeg"
                                                        && strpos($picMime, "image/") === 0) {
                                                                $picType = "jpeg";
                                                                $createPic = imagecreatefromjpeg($extraPics["tmp_name"][$idx]);
                                                                imagejpeg($createPic, self::PIC_SECURE_PATH.$extraPics[$idx]);

                                                                list($originWidth, $originHeight) = getimagesize(self::PIC_SECURE_PATH.$extraPics[$idx]);

                                                                $originRatio = $originWidth / $originHeight;

                                                                if($newWidth / $newHeight > $originRatio) {
                                                                        $newWidth = $newHeight * $originRatio;
                                                                }

                                                                else {
                                                                        $newHeight = $newWidth / $originRatio;
                                                                }

                                                                $resizedPic = imagecreatetruecolor($newWidth, $newHeight);

                                                                $thumbnails[$idx] = guidv4()."_min.".pathinfo($extraPics["name"][$idx], PATHINFO_EXTENSION);

                                                                $createThumbnail = imagecreatefromjpeg($extraPics["tmp_name"][$idx]);
                                                                imagecopyresampled($resizedPic, $createThumbnail, 0, 0, 0, 0, $newWidth, $newHeight, $originWidth, $originHeight);
                                                                imagejpeg($resizedPic, self::THUMB_SECURE_PATH.$thumbnails[$idx], 100);
                                                        }

                                                        elseif(preg_match($imgRegex, $extraPics["name"][$idx])
                                                        && $extraPics["type"][$idx] == "image/png"
                                                        && strpos($picMime, "image/") === 0) {
                                                                $picType = "png";
                                                                $createPic = imagecreatefrompng($extraPics["tmp_name"][$idx]);
                                                                imagepng($createPic, self::PIC_SECURE_PATH.$extraPics[$idx]);

                                                                list($originWidth, $originHeight) = getimagesize(self::PIC_SECURE_PATH.$extraPics[$idx]);

                                                                $originRatio = $originWidth / $originHeight;

                                                                if($newWidth / $newHeight > $originRatio) {
                                                                        $newWidth = $newHeight * $originRatio;
                                                                }

                                                                else {
                                                                        $newHeight = $newWidth / $originRatio;
                                                                }

                                                                $resizedPic = imagecreatetruecolor($newWidth, $newHeight);

                                                                $thumbnails[$idx] = guidv4()."_min.".pathinfo($extraPics["name"][$idx], PATHINFO_EXTENSION);

                                                                $createThumbnail = imagecreatefrompng($extraPics["tmp_name"][$idx]);
                                                                imagecopyresampled($resizedPic, $createThumbnail, 0, 0, 0, 0, $newWidth, $newHeight, $originWidth, $originHeight);
                                                                imagepng($resizedPic, self::THUMB_SECURE_PATH.$thumbnails[$idx], 9);
                                                        }

                                                        else {
                                                                print_r($extraPics);
                                                                $extraPicMessages["errors"][] = "Aucune image n'a pu être uploadée.";
                                                                
                                                                return $extraPicMessages;
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
                                                                                ":albumId" => $_GET["albumId"],
                                                                                ":pictureName" => $extraPics[$idx]
                                                                                ]);

                                                        do {
                                                                $thumbnailId = uniqid();
                                                        } while($pdo->prepare("SELECT `id` FROM `album_thumbnails`
                                                                                WHERE `id` = $pictureId") > 0);

                                                        $latestPicId = $pictureId;

                                                        $newThumbnail = $pdo->prepare("INSERT INTO `album_thumbnails` (`id`,
                                                                                        `picture_id`, `album_id`, `thumbnail_name`)
                                                                                        VALUES (:id, :pictureId, :albumId,
                                                                                        :thumbnailName)");
                                                        
                                                        $newThumbnail->execute([
                                                                                ":id" => $thumbnailId,
                                                                                ":pictureId" => $latestPicId,
                                                                                ":albumId" => $_GET["albumId"],
                                                                                ":thumbnailName" => $thumbnails[$idx]
                                                                                ]);
                                                }

                                                $extraPicMessages["success"] = ["Chaque nouvelle image a été ajoutée à l'album ".$findAlbum["title"]." avec succès."];

                                                return $extraPicMessages;
                                        }
                                }
                        }
                        
                        return $extraPicMessages;
                }
        }

//*****END OF THE CLASS*****//
}