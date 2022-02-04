<?php

namespace App\controller;
use App\model\{Album};
use App\controller\{AlbumFormController};
use \PDO;

class ImageDisplayController extends AlbumFormController {
    
    protected Album $_album;
    
    public function __construct(Album $album) {
        $this->_album = $album;
    }

//*****A. Cover display*****//
    public function displayCover() {
        $pdo = new PDO("mysql:host=127.0.0.1;dbname=willeb_cl","root","");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->exec("SET NAMES UTF8");

        $query = $pdo->prepare("SELECT `cover_name` FROM `album_covers`");

        $query->execute();

        $covers = $query->fetchAll();

        foreach($covers as $cover) {
            $covName = $cover["cover_name"];
            $img = parent::COV_SECURE_PATH.$covName;
            $extension = pathinfo($covName, PATHINFO_EXTENSION);

            switch($extension) {
                case "png":
                    $contentType = "image/png";
                    break;
                
                case "jpg":
                case "jpeg":
                    $contentType = "image/jpeg";
                    break;
            }

            header("Content-type: ".$contentType);
            readfile($img);
        }
    }


    // public function displayCover() {
    //     $coverNames = $this->_album->findCoverByAlbumId($_GET["albumId"]);

    //     foreach($coverNames as $coverName) {
    //         if(pathinfo($coverName["cover_name"], PATHINFO_EXTENSION) == "image/png") {
    //             header("Content-type: image/png");
    //             file_get_contents(parent::COV_SECURE_PATH.$coverName["cover_name"]);
    //             die($coverName["cover"]);
    //         }

    //         elseif(pathinfo($coverName["cover"], PATHINFO_EXTENSION) == "image/jpeg") {
    //             header("Content-type: image/jpeg");
    //             file_get_contents(parent::COV_SECURE_PATH.$coverName["cover"]);
    //             die($coverName["cover"]);
    //         }
    //     }
    // }

//*****B. Picture display*****//
    public function displayPictures() {
        $pictureName = $this->_album->findPicturesByAlbumId($_GET["albumId"]);

        for($i = 0; $i < $pictureName; $i ++) {
            if(pathinfo($pictureName["picture_name"][$i], PATHINFO_EXTENSION) == "image/png") {
                header("Content-type: image/png");
                file_get_contents(parent::PIC_SECURE_PATH.$pictureName["picture_name"][$i]);
                die($pictureName["picture_name"][$i]);
            }
    
            elseif(pathinfo($pictureName["picture_name"][$i], PATHINFO_EXTENSION) == "image/jpeg") {
                header("Content-type: image/jpeg");
                file_get_contents(parent::PIC_SECURE_PATH.$pictureName["picture_name"][$i]);
                die($pictureName["picture_name"][$i]);
            }
        }
    }

//*****END OF THE CLASS*****//   
}
