<?php

namespace App\model;
use App\core\Connect;

class Album extends Connect {
    
    protected $_pdo;
    public function __construct(){
        $this->_pdo = $this->connection();
    }

//*****A. Album addition*****//
    public function addAlbum(string $id, string $userId, string $userLogin, string $title, string $description) {
        $sql = "INSERT INTO `album` (`id`, `user_id`, `user_login`, `title`, `description`)
                VALUES (:id, :userId, :userLogin, :title, :description)";
        
        $query = $this->_pdo->prepare($sql);
        
        $query->execute([
                        ":id" => $id,
                        ":userId" => $userId,
                        ":userLogin" => $userLogin,
                        ":title" => $title,
                        ":description" => $description
                        ]);

        return $this->_pdo->lastInsertId();
    }
    
//*****B. Finding all the albums*****//
    public function findAllAlbums() {
        $sql = "SELECT `id`, `user_login`, `title`, `description`, `post_date`, `likes`, `dislikes` FROM `album`
                ORDER BY `post_date` DESC";
        
        $query = $this->_pdo->prepare($sql);
        
        $query->execute();
        
        return $query->fetchAll(\PDO::FETCH_ASSOC); 
    }

//*****C. Finding an album via its identifier*****//
    public function findAlbumById(string $albumId) {
        $sql = "SELECT `id`, `title`, `user_login`, `description`, `post_date`, `likes`, `dislikes` FROM `album` WHERE `id` = :albumId";
                
        $query = $this->_pdo->prepare($sql);
        
        $query->execute([":albumId" => $albumId]);
                
        return $query->fetch(\PDO::FETCH_ASSOC); 
    }

//*****D. Finding the albums of a specific user*****//
    public function findUserAlbums(string $userId) {
        $sql = "SELECT album.id, `user_id`, `title`, `description`, `post_date`, `likes`, `dislikes` FROM `album`
                LEFT OUTER JOIN `user` ON user.id = album.user_id WHERE user.id = :userId ORDER BY `post_date` DESC";
                    
        $query = $this->_pdo->prepare($sql);
        
        $query->execute([":userId" => $userId]);
        
        return $query->fetchAll(\PDO::FETCH_ASSOC);
    }

//*****E. Finding the cover of a specific album*****//
    public function findAlbumCover(string $albumId) {
        $sql = "SELECT album_covers.id, `album_id`, `cover_name` FROM `album_covers`
                LEFT OUTER JOIN `album` ON album.id = album_covers.album_id WHERE album.id = :albumId";
                    
        $query = $this->_pdo->prepare($sql);
        
        $query->execute([":albumId" => $albumId]);
        
        return $query->fetch(\PDO::FETCH_ASSOC);
    }

//*****F. Finding the pictures of a specific album*****//
    public function findAlbumPictures(string $albumId) {
        $sql = "SELECT album_pictures.id, `album_id`, `picture_name` FROM `album_pictures`
                LEFT OUTER JOIN `album` ON album.id = album_pictures.album_id WHERE album.id = :albumId";
                    
        $query = $this->_pdo->prepare($sql);
        
        $query->execute([":albumId" => $albumId]);
        
        return $query->fetchAll(\PDO::FETCH_ASSOC);
    }

//*****G. Album modification*****//
    public function updateAlbum(string $albumId, string $userId, string $userLogin, $title, string $description) {
        $sql = "UPDATE `album`
                SET `user_id` = :userId, `user_login` = :userLogin, `title` = :title, `description` = :description
                WHERE `id` = :albumId";
        
        $query = $this->_pdo->prepare($sql);
        
        $query->execute([
                        ":albumId" => $albumId,
                        ":userId" => $userId,
                        ":userLogin" => $userLogin,
                        ":title" => $title,
                        ":description" => $description
                        ]);
    }

//*****H. Cover replacement*****//
    public function replaceCover(string $coverId, string $albumId, string $coverName) {
                        
        $sql = "UPDATE `album_covers` SET `cover_name` = :coverName WHERE album_covers.id = :coverId AND `album_id` = :albumId";
        
        $query = $this->_pdo->prepare($sql);
        
        $query->execute([":coverId" => $coverId, ":albumId" => $albumId, ":coverName" => $coverName]);
    }

//*****I. Picture replacement*****//
    public function replacePicture(string $pictureId, string $albumId, string $pictureName) {
                    
        $sql = "UPDATE `album_pictures` SET `picture_name` = :pictureName WHERE album_pictures.id = :pictureId AND `album_id` = :albumId";
        
        $query = $this->_pdo->prepare($sql);
        
        $query->execute([":pictureId" => $pictureId, ":albumId" => $albumId, ":pictureName" => $pictureName]);
    }

//*****J. Picture addition*****//
    public function addExtraPictures(string $albumId, string $pictureName) {
            
        $sql = "INSERT INTO `album_pictures` (`album_id`, `picture_name`) VALUES (:albumId, :pictureName)";
        
        $query = $this->_pdo->prepare($sql);
        
        $query->execute([":albumId" => $albumId, ":pictureName" => $pictureName]);
    }

//*****K. Album deletion*****//
    public function deleteAlbum(string $albumId) {
            
        $sql = "DELETE FROM `album` WHERE `id` = :albumId";
                    
        $query = $this->_pdo->prepare($sql);
        
        $query->execute([":albumId" => $albumId]);
    }

// //*****L. Cover deletion*****//
//     public function deleteCover(string $coverId) {
                
//         $sql = "DELETE FROM `album_covers` WHERE `id` = :coverId";
                    
//         $query = $this->_pdo->prepare($sql);
        
//         $query->execute([":coverId" => $coverId]);
//     }

//*****M. Picture deletion*****//
    public function deletePicture(string $pictureId) {
                
        $sql = "DELETE FROM `album_pictures` WHERE `id` = :pictureId";
                    
        $query = $this->_pdo->prepare($sql);
        
        $query->execute([":pictureId" => $pictureId]);
    }

//*****END OF THE CLASS*****//
}