<?php

namespace EcommerceTest\Objects;

use EcommerceTest\Traits\Error;
use PHPMailer\PHPMailer\PHPMailer;

class EmailManager extends PHPMailer{

    use Error;

    /**
     * Sender email
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

    public function getToEmail(){ return $this->toEmail; }
    public function getSubject(){ return $this->subject; }
    public function getBody(){ return $this->body; }

    /**
     * Check if the needed values exist
     */
    private function checkExists(array $data): bool{
        if(isset($data['to']))return false;
        if(isset($data['subject']))return false;
        if(isset($data['body']))return false;
        return true;
    }
}
?>