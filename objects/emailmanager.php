<?php

namespace EcommerceTest\Objects;

use EcommerceTest\Exceptions\NotSettedException;
use EcommerceTest\Traits\EmailManagerTrait;
use EcommerceTest\Traits\Error;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

class EmailManager extends PHPMailer{

    use EmailManagerTrait, Error;

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
        if(!$this->checkExists($data))
            throw new NotSettedException("");
        $this->assignValues($data);
        $this->setServerSettings($data);
        $this->setEncoding();
    }

    public function getFromEmail(){ return $this->fromEmail; }
    public function getToEmail(){ return $this->toEmail; }
    public function getSubject(){ return $this->subject; }
    public function getBody(){ return $this->body; }

    

    
}
?>