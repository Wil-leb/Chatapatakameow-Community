<?php

namespace App\controller;
use \PDO;

class PaginationController {

//*****A. User dashboard pagination******/
        public function countUsers() {
            $pdo = new PDO("mysql:host=127.0.0.1;dbname=willeb_cl","root","");
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->exec("SET NAMES UTF8");

            if(isset($_GET["page"]) && !empty($_GET["page"])) {
                $currentPage = (int) strip_tags($_GET["page"]);
            }

            else {
                $currentPage = 1;
            }

            $countUSer = $pdo->prepare("SELECT COUNT(*) AS `user_nb` FROM `user`");

            $countUSer->execute();

            $result = $countUSer->fetch();

            $userNumber = (int) $result["user_nb"];

            $perPage = 10;

            $pages = ceil($userNumber / $perPage);

            $firstUser = ($currentPage * $perPage) - $perPage;

            $offset = $pdo->prepare("SELECT * FROM `user` LIMIT :firstUser, :perPage");

            $offset->bindValue(":firstUser", $firstUser, \PDO::PARAM_INT);
            $offset->bindValue(":perPage", $perPage, \PDO::PARAM_INT);

            $offset->execute();

            $allUsers = $offset->fetchAll(\PDO::FETCH_ASSOC);
        }

//*****END OF THE CLASS*****//   
}