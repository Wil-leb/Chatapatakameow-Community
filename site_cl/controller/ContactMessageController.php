<?php

namespace App\controller;

use App\model\ContactMessage;

class ContactMessageController {
    
    protected ContactMessage $_message;
    public function __construct(ContactMessage $message) {
        $this->_message = $message;
    }
       
    public function contactForm(array $data) {
        $messages = [];
        
        $lastName = $data["lastName"];
        $firstName = $data["firstName"];
        $messageContent = $data["message"];
        
        $regex = "/^[\p{L}\-\s]*$/i";
        $contentRegex = "/^[\p{L}\d\-\/();,:.!?\'&\"\s]+$/ui";

        if($_POST["sendMessage"]) {
            if(empty($data["lastName"]) || empty($data["firstName"]) || empty($data["email"]) || empty($data["message"])) {
                $messages["errors"][] = "Veuiles remplir tous les champs.";
            }
            
            if (!empty($data["email"]) && !filter_var($data["email"], FILTER_VALIDATE_EMAIL)) {
                $messages["errors"][] = "Le format de l'adresse électronique ets invalide.";
            }
            
            if(!empty($data["lastName"]) && !preg_match($regex, $lastName, $matches)
                || !empty($data["firstName"]) && !preg_match($regex, $firstName, $matches)) {
                $messages["errors"][] = "Caractères autorisés pour le nom et le prénom : lettres, tirets et espaces.";
            }
            
            if(!empty($data["message"]) && !preg_match($contentRegex, $messageContent, $matches)) {
                $messages["errors"][] = "Caractères autorisés pour le message : lettres, chiffres, tirets, slash, parenthèses, point-virgules, virgules, doubles points, points, points d'exclamation, points d'interrogation, apostrophes, esperluettes, guillemets droits et espaces.";
            }
            
            if(empty($messages["errors"])) {
                $this->_message->addMessage($data["lastName"], $data["firstName"], $data["email"], $data["message"]);
                $messages["success"] = ["Ton message a été envoyé avec succès. Tu recevras une réponse à l'adresse électronique renseignée d'ici 24 heures. Veuilles vérifier ton dossier de spams."];
            }
        }
        
        return $messages;
    }
 
//*****END OF THE CLASS*****//  
}