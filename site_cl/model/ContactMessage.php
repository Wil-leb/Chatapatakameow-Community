<?php
namespace App\model;

use App\core\Connect;

class ContactMessage extends Connect {

    protected $_pdo;
    public function __construct() {
        $this->_pdo = $this->connection();
    }
    
//*****A. Message addition*****//
    public function addMessage(string $lastName, string $firstName, string $email, string $content) {
        
        $sql = "INSERT INTO `contact_message` (`visitor_last_name`, `visitor_first_name`, `visitor_email`, `message_content`)
                VALUES (TRIM(:lastName), TRIM(:firstName), :email, TRIM(:message))";
                
        $query = $this->_pdo->prepare($sql);
        
        $query->execute([
                        ":lastName" => $lastName,
                        ":firstName" => $firstName,
                        ":email" => $email,
                        ":message" => $content
                        ]);
    }

//*****END OF THE CLASS*****//
}