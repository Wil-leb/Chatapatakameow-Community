<?php

namespace App\core;

class Cookie {
    
//*****A. Cookie deletion*****//
    public static function deleteCookie(array $cookies):void {
        foreach($cookies as $cookieKey => $cookieValue) {
            setcookie($cookieKey);                                                
            unset($_COOKIE[$cookieKey]);
        }
    }
    
    
//*****B. Cookie creation*****//
    public static function setCookie(array $cookies):void {
        foreach($cookies as $cookieName => $cookieValue) {
            if($cookieName == "password") {
                $cookieValue = self::encrypt($cookieValue);
            }

            setcookie($cookieName, $cookieValue, time()+365*24*3600);
        }    
    }
    
    
//*****C. Finding a cookie*****//
    public static function checkCookie(string $cookieName) {
        if(array_key_exists($cookieName, $_COOKIE)) {
            if($cookieName == "password") {
                $_COOKIE[$cookieName] = self::decrypt($_COOKIE[$cookieName]);
            }
                                                          
            return "value='".$_COOKIE[$cookieName]."'"; 
        }
    }
    
    
//*****D. Cookie encryption*****//
    public static function encrypt(string $data) {
        $key = "12345678";
        $data = serialize($data);
        
        $td = mcrypt_module_open(MCRYPT_DES, "", MCRYPT_MODE_ECB, "");
        $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
        
        mcrypt_generic_init($td, $key, $iv);
        $data = base64_encode(mcrypt_generic($td, "!" . $data));
        mcrypt_generic_deinit($td);
        
        return $data;
    }
    
//*****E. Cookie decryption*****//
    public static function decrypt(string $data) {
        $key = "12345678";
               
        $td = mcrypt_module_open(MCRYPT_DES, "", MCRYPT_MODE_ECB, "");
        $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
        
        mcrypt_generic_init($td, $key, $iv);
        $data = mdecrypt_generic($td, base64_decode($data));
        mcrypt_generic_deinit($td);

        if (substr($data, 0, 1) != "!") {
            return false;
        }
        
        $data = substr($data, 1, strlen($data) - 1);
        
        return unserialize($data);
    }

//*****END OF THE CLASS*****//
}