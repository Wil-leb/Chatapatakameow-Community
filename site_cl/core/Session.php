<?php

namespace App\core;

class Session {
    
//*****A. Closing a session*****//
    public static function disconnect(){
        session_start();
        session_destroy();
    }
    
//*****B. Opening a session*****//
    public static function setUserSession(array $sessions):void { 
        foreach($sessions as $sessionKey => $sessionValue) {
            $sessionValue = self::checkInput($sessionValue);
            $_SESSION["user"][$sessionKey] = $sessionValue;
        }
    }
    
//*****C. Session information*****//
    public static function checkInput($data) {
        if(is_numeric($data)) {
            return intval($data);
        }
        
        else {
            return htmlspecialchars($data);
        }
    }
    
//*****D. Checking a user connection*****//
    public static function online():bool {
        if (array_key_exists("user", $_SESSION)) {
            return true;
        }
        
        else {
            return false;
        }
    }

//*****E. Checking the visitor role*****//
    public static function visitor() {
        if (array_key_exists("user", $_SESSION)) {
            $role = $_SESSION["user"]["role"];
            
            if($role === 0) {
                return true;
            }
        
            else {
                return false;
            }
        }
    }

//*****F. Checking the FC member role*****//
    public static function member() {
        if (array_key_exists("user", $_SESSION)) {
            $role = $_SESSION["user"]["role"];
            
            if($role === 1) {
                return true;
            }
            
            else {
                return false;
            }
        }
    }
    
//*****G. Checking the website admin role*****//
    public static function admin() {
        if (array_key_exists("user", $_SESSION)) {
            $role = $_SESSION["user"]["role"];
            
            if($role === 2) {
                return true;
            }
            
            else {
                return false;
            }
        }
    }

//*****END OF THE CLASS*****//   
}