<?php

use App\core\{Autoloader, Https};
use App\controller\{FrontController, AjaxController};

session_start();

require_once "./core/Autoloader.php";

Autoloader::register();

$routeur = new FrontController();

//*****1. Checking an AJAX URL, and redirecting to the Homepage if a URL does not exist*****//
if(isset($_GET["ajax"])) {
    $methodAjax = $_GET["ajax"];
    (method_exists(AjaxController::class, $methodAjax)) ? AjaxController::$methodAjax(): $routeur->home();
}
    
    
//*****2. Checking a PHP URL, and redirecting to the Homepage if a URL does not exist*****//
elseif(isset($_GET["p"])) {
    $method = $_GET["p"];
    (method_exists(FrontController::class, $method)) ? $routeur->$method(): $routeur->home();
}
    
//*****3. Redirecting to the Homepage by default, when entering the site, or if a URL does not exist*****//
else {
    header("Location: index.php?p=home");
    exit; 
}