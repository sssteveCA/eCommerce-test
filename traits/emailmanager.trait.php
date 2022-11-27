<?php

namespace EcommerceTest\Traits;

use PHPMailer\PHPMailer\SMTP;

/**
 * This trait contains private methods of EmailManager class
 */
trait EmailManagerTrait{

    /**
     * Check if the needed values exist
     */
    private function checkExists(array $data): bool{
        if(!isset($data['from']))return false;
        if(!isset($data['to']))return false;
        if(!isset($data['subject']))return false;
        if(!isset($data['body']))return false;
        return true;
    }

    

    private function setEncoding(){
        $this->CharSet = 'UTF-8';
        $this->Encoding = 'base64';
    }

    private function setServerSettings(array $data){
        $this->SMTPDebug = SMTP::DEBUG_OFF;
        $this->isSMTP();
        $this->Host = isset($data['MAIL_HOST']) ? $data['MAIL_HOST'] : $_ENV['MAIL_HOST'];
        $this->SMTPAuth = true;
        $this->Username = isset($data['MAIL_USERNAME']) ? $data['MAIL_USERNAME'] : $_ENV['MAIL_USERNAME'];
        $this->Password = isset($data['MAIL_PASSWORD']) ? $data['MAIL_PASSWORD'] : $_ENV['MAIL_PASSWORD'];
        $this->SMTPSecure = false;
        $this->Port = isset($data['MAIL_PORT']) ? $data['MAIL_PORT'] : $_ENV['MAIL_PORT'];
    }

}
?>