<?php

namespace EcommerceTest\Objects;

use EcommerceTest\Exceptions\NotSettedException;
use EcommerceTest\Traits\EmailManagerTrait;
use EcommerceTest\Traits\Error;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use EcommerceTest\Interfaces\EmailManagerErrors as Eme;
use Exception;

class EmailManager extends PHPMailer implements Eme{

    use EmailManagerTrait, Error;

    /**
     * Sender email
     */
    private ?string $fromEmail;

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
        $this->sendMessage();
    }

    public function getFromEmail(){ return $this->fromEmail; }
    public function getToEmail(){ return $this->toEmail; }
    public function getSubject(){ return $this->subject; }
    public function getBody(){ return $this->body; }
    public function getError(){
        switch($this->errno){
            case Eme::ERR_EMAIL_SEND:
                $this->error = Eme::ERR_EMAIL_SEND_MSG;
                break;
            default:
                $this->error = null;
                break;
        }
    }

    private function sendMessage(){
        $this->errno = 0;
        try{
            if(isset($this->fromEmail))
                $this->setFrom($this->fromEmail,$this->fromEmail);
            $this->addAddress($this->toEmail,$this->toEmail);
            $this->Subject = $this->subject;
            $this->Body = $this->body;
            $this->AltBody = $this->body;
            $this->send();
        }catch(Exception $e){
            $this->errno = Eme::ERR_EMAIL_SEND;
            /* echo "EmailManager sendMessage exception => ".$e->getMessage()."\r\n";
            echo "Errore invio mail ".$this->ErrorInfo."\r\n"; */
        }
    }


    
}
?>