<?php

namespace App\controller;
use App\model\{Album, User};
use \PDO;

require_once("./assets/php/Guid.php");

class AlbumModifController {
    
        public const COV_SECURE_PATH = "C:/xampp/secure/albums_cl/covers/";
        public const PIC_SECURE_PATH = "C:/xampp/secure/albums_cl/pictures/";

        protected Album $_album;
        
        public function __construct(Album $album) {
                $this->_album = $album;
        }
  
//*****A. Description modification*****//
        public function descriptionModifForm() {
                $modifMessages = [];

                if($_POST["albumChanges"]) {
                        $albumId = $_GET["albumId"];
                        $findCover = $this->_album->findAlbumCover($albumId);
                        $coverName = $findCover["cover_name"];
                        $trueCovName = $_POST["coverName"];

                        $pdo = new PDO("mysql:host=127.0.0.1;dbname=willeb_cl","root","");
                        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                        $pdo->exec("SET NAMES UTF8");
            
                        $query = $pdo->prepare("SELECT `id` FROM `album` WHERE `id` = :albumId");
            
                        $query->execute([":albumId" => $albumId]);

                        $trueAlbId = $query->fetchColumn();
            
                        if($albumId != $trueAlbId || $albumId == null || $coverName != $trueCovName || $coverName == null) {
                                die("Hacking attempt!");
                        }
            
                        else {
                                $findAlbum = $this->_album->findAlbumById($albumId);

                                $folder = self::COV_SECURE_PATH;
                                $minAge = 10;

                                function deleteCover($folder, $coverName, $minAge) {
                                        $directory = opendir($folder);
                                        
                                                while(false !== ($coverName = readdir($directory))) {
                                                        $path = $folder.$coverName;
                                                        $info = pathinfo($path);
                                                        $trueCovName = $_POST["coverName"];
                                                        $fileAge = time() - filemtime($path);

                                                        if($coverName != "." && $coverName != ".." && !is_dir($coverName) && $trueCovName == $coverName && $fileAge > $minAge) {
                                                                unlink($path);
                                                        }
                                                }
                                                
                                        closedir($directory);
                                }
                        
                                if(!$_POST["title"] || !$_POST["description"] || !$_FILES["cover"]["name"]) {
                                        $modifMessages["errors"][] = "Veuilles remplir tous les champs.";
                                }

                                $allowedTitlelength = 30;
                                $titleLength = strlen($_POST["title"]);

                                $allowedDescrlength = 200;
                                $descrLength = strlen($_POST["description"]);

                                if($titleLength > $allowedTitlelength) {
                                        $modifMessages["errors"][] = "Le titre ne doit pas dépasser 30 caractères, espaces comprises.";
                                }

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
                                        $covType;

                                        $createCover;

                                        $newCovName = guidv4().".".pathinfo($_FILES["cover"]["name"], PATHINFO_EXTENSION);

                                        $imgRegex = "/^[a-z\d][^.\s]*\.(png$)|^[a-z\d][^.\s]*\.(jpe?g$)/i";

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
                                                // if(preg_match($imgRegex, $newCovName) && $_FILES["cover"]["type"] == "image/jpeg"
                                                // && strpos($covMime, "image/") === 0) {
                                                //         deleteCover($folder, $coverName, $minAge);
                                                //         $covType = "jpeg";
                                                //         $createCover = imagecreatefromjpeg($_FILES["cover"]["tmp_name"]);
                                                //         imagejpeg($createCover, $folder.$newCovName);
                                                // }

                                                // elseif(preg_match($imgRegex, $newCovName) && $_FILES["cover"]["type"] == "image/png"
                                                // && strpos($covMime, "image/") === 0) {
                                                //         deleteCover($folder, $coverName, $minAge);
                                                //         $covType = "png";
                                                //         $createCover = imagecreatefrompng($_FILES["cover"]["tmp_name"]);
                                                //         imagepng($createCover, $folder.$newCovName);
                                                // }

                                                // else {
                                                //         $modifMessages["errors"][] = "La couverture n'a pas pu être uploadée.";

                                                //         return $modifMessages;
                                                // }

                                                // $descriptionModification = $this->_album->updateAlbum($albumId, $_SESSION["user"]["id"], $_SESSION["user"]["login"], $_POST["title"], $_POST["description"]);

                                                // $coverModification = $this->_album->replaceCover($findCover["id"], $albumId, $newCovName);

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
                        $truePicName = $_POST["pictureName"];

                        $pdo = new PDO("mysql:host=127.0.0.1;dbname=willeb_cl","root","");
                        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                        $pdo->exec("SET NAMES UTF8");
            
                        $query = $pdo->prepare("SELECT `id` FROM `album` WHERE `id` = :albumId");
            
                        $query->execute([":albumId" => $albumId]);

                        $req = $pdo->prepare("SELECT `id` FROM `album_pictures` WHERE `id` = :pictureId");

                        $req->execute([":pictureId" => $pictureId]);

                        $que = $pdo->prepare("SELECT `picture_name` FROM `album_pictures` WHERE `picture_name` = :pictureName");

                        $que->execute([":pictureName" => $truePicName]);
            
                        $trueAlbId = $query->fetchColumn();
                        $truePicId = $req->fetchColumn();
                        $pictureName = $que->fetchColumn();
            
                        if($albumId != $trueAlbId || $albumId == null || $pictureId != $truePicId || $pictureId == null || $pictureName != $truePicName || $pictureName == null) {
                                die("Hacking attempt!");
                        }
            
                        else {
                                $findAlbum = $this->_album->findAlbumById($albumId);

                                // $folder = self::PIC_SECURE_PATH;
                                // $minAge = 10;

                                // function deletePicture($folder, $pictureName, $minAge) {
                                //         $directory = opendir($folder);
                                        
                                //                 while(false !== ($pictureName = readdir($directory))) {
                                //                         $path = $folder.$pictureName;
                                //                         $info = pathinfo($path);
                                //                         $truePicName = $_POST["pictureName"];
                                //                         $fileAge = time() - filemtime($path);

                                //                         if($pictureName != "." && $pictureName != ".." && !is_dir($pictureName) && $truePicName == $pictureName && $fileAge > $minAge) {
                                //                                 unlink($path);
                                //                         }
                                //                 }
                                                
                                //         closedir($directory);
                                // }

                                // deletePicture($folder, $pictureName, $minAge);
                                // $deletePicture = $this->_album->deletePicture($pictureId);

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
                        $picName = $_POST["currentName"];

                        $pdo = new PDO("mysql:host=127.0.0.1;dbname=willeb_cl","root","");
                        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                        $pdo->exec("SET NAMES UTF8");
            
                        $query = $pdo->prepare("SELECT `id` FROM `album` WHERE `id` = :albumId");
            
                        $query->execute([":albumId" => $albumId]);

                        $req = $pdo->prepare("SELECT `id` FROM `album_pictures` WHERE `id` = :pictureId");

                        $req->execute([":pictureId" => $pictureId]);
            
                        $trueAlbId = $query->fetchColumn();
                        $truePicId = $req->fetchColumn();
                        
                        $que = $pdo->prepare("SELECT `picture_name` FROM `album_pictures` WHERE `picture_name` = :pictureName");

                        $que->execute([":pictureName" => $picName]);

                        $pictureName = $que->fetchColumn();

                        if($albumId != $trueAlbId || $albumId == null || $pictureId != $truePicId || $pictureId == null || $pictureName != $picName || $picName == null) {
                                die("Hacking attempt!");
                        }

                        else {
                        
                                $findAlbum = $this->_album->findAlbumById($albumId);

                                $folder = self::PIC_SECURE_PATH;
                                $minAge = 10;

                                function deletePicture($folder, $pictureName, $minAge) {
                                        $directory = opendir($folder);
                                
                                        while(false !== ($pictureName = readdir($directory))) {
                                                $path = $folder.$pictureName;
                                                $info = pathinfo($path);
                                                $truePicName = $_POST["currentName"];
                                                $fileAge = time() - filemtime($path);

                                                if($pictureName != "." && $pictureName != ".." && !is_dir($pictureName) && $truePicName == $pictureName && $fileAge > $minAge) {
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
                                        $picSize = filesize(self::PIC_SECURE_PATH.$findPicture["picture_name"]);
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

                                        $newPicName = guidv4().".".pathinfo($_FILES["newPicture"]["name"], PATHINFO_EXTENSION);

                                        $imgRegex = "/^[a-z\d][^.\s]*\.(png$)|^[a-z\d][^.\s]*\.(jpe?g$)/i";

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
                                                // if(preg_match($imgRegex, $newPicName) && $_FILES["newPicture"]["type"] == "image/jpeg"
                                                // && strpos($picMime, "image/") === 0) {
                                                //         deletePicture($folder, $pictureName, $minAge);
                                                //         $picType = "jpeg";
                                                //         $createPicture = imagecreatefromjpeg($_FILES["newPicture"]["tmp_name"]);
                                                //         imagejpeg($createPicture, $folder.$newPicName);
                                                // }

                                                // elseif(preg_match($imgRegex, $newPicName) && $_FILES["newPicture"]["type"] == "image/png"
                                                // && strpos($picMime, "image/") === 0) {
                                                //         deletePicture($folder, $pictureName, $minAge);
                                                //         $picType = "png";
                                                //         $createPicture = imagecreatefrompng($_FILES["newPicture"]["tmp_name"]);
                                                //         imagepng($createPicture, $folder.$newPicName);
                                                // }

                                                // else {
                                                //         $modifMessages["errors"][] = "L'image n'a pas pu être uploadée.";
                                                //         return $replaceMessages;
                                                // }

                                                // $pictureModification = $this->_album->replacePicture($pictureId, $albumId, $newPicName);

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
                                                                // $createPic = imagecreatefromjpeg($extraPics["tmp_name"][$idx]);
                                                                // imagejpeg($createPic, self::PIC_SECURE_PATH.$extraPics[$idx]);
                                                        }

                                                        elseif(preg_match($imgRegex, $extraPics["name"][$idx])
                                                        && $extraPics["type"][$idx] == "image/png"
                                                        && strpos($picMime, "image/") === 0) {
                                                                $picType = "png";
                                                                // $createPic = imagecreatefrompng($extraPics["tmp_name"][$idx]);
                                                                // imagepng($createPic, self::PIC_SECURE_PATH.$extraPics[$idx]);
                                                        }

                                                        else {
                                                                print_r($extraPics);
                                                                $extraPicMessages["errors"][] = "Aucune image n'a pu être uploadée.";
                                                                
                                                                return $extraPicMessages;
                                                        }

                                                        // do {
                                                        //         $pictureId = uniqid();
                                                        // } while($pdo->prepare("SELECT `id` FROM `album_pictures`
                                                        //                         WHERE `id` = $pictureId") > 0);

                                                        // $newPicture = $pdo->prepare("INSERT INTO `album_pictures` (`id`, `album_id`,
                                                        //                                 `picture_name`)
                                                        //                                 VALUES (:id, :albumId, :pictureName)");
                                                        
                                                        // $newPicture->execute([
                                                        //                 ":id" => $pictureId,
                                                        //                 ":albumId" => $_GET["albumId"],
                                                        //                 ":pictureName" => $extraPics[$idx]
                                                        //                 ]);
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