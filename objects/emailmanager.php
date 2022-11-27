<?php

namespace EcommerceTest\Objects;

use EcommerceTest\Traits\Error;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

class EmailManager extends PHPMailer{

    use Error;

    /**
     * Sender email
     */
    private string $fromEmail;

    /**
     * Receiver email
     */
    private string $toEmail;

    /**
     * The subject of the mail
     */
    private string $subject;

    /**
     * The content of the mail
     */
    private string $body;

    public function __construct(array $data)
    {
        
    }

    public function getFromEmail(){ return $this->fromEmail; }
    public function getToEmail(){ return $this->toEmail; }
    public function getSubject(){ return $this->subject; }
    public function getBody(){ return $this->body; }

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