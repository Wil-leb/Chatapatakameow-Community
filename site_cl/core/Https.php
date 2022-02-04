<?php

namespace App\core;

class Https {
    
//*****A. User redirection*****//
    public static function redirect(string $path):void {
        header("Location: ".$path);
        exit;
    }
    
//*****B. Styling an active page*****//    
    public static function active(string $path) {
        return ($_GET["p"] === $path) ? "class = 'active'" : "";
    }

//*****END OF THE CLASS*****//
}