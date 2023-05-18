<?php

namespace EcommerceTest\Response;
use Dotenv\Dotenv;
use EcommerceTest\Objects\Utente;
use Exception;
use EcommerceTest\Interfaces\Constants as C;
use EcommerceTest\Interfaces\Messages as Msg;

class ContactsPost{

    public static function content(array $params): array{
        $post = $params['post'];
        if(isset($post['oggetto'],$post['messaggio']) && $post['oggetto'] != '' && $post['messaggio'] != ''){
            try{
                $dotenv = Dotenv::createImmutable(__DIR__."/../");
                $dotenv->load();
                $oggetto = $_POST['oggetto'];
                $messaggio = $_POST['messaggio'];
                $to = 'admin@'.$_ENV['HOSTNAME'].'.lan';
                $session = $params['session'];
                $send = false;
                if(!empty($session)){
                    $user = unserialize($session['utente']);
                    $from = $user->getEmail();
                    $headers = <<<HEADER
To: <{$to}>
From: <{$from}>
Reply-to: <{$from}>
HEADER;
                    $send = $user->sendMail($to,$oggetto,$messaggio,$headers,$from);
                }// if(!empty($session)){
                else{
                    if(isset($post['email'])){
                        $from = $post['email'];
                        $headers = <<<HEADER
To: <{$to}>
From: <{$from}>
Reply-to: <{$from}>
HEADER;
                        $user = new Utente([]);
                        $send = $user->sendMail($to,$oggetto,$messaggio,$headers,$from);
                    }//if(isset($post['email'])){
                }//else of if(!empty($session)){
                if($send)
                    return [ C::KEY_CODE => 200, C::KEY_DONE => true, C::KEY_MESSAGE => Msg::EMAILSENT1];
                return [ C::KEY_CODE => 500, C::KEY_DONE => false, C::KEY_MESSAGE => Msg::ERR_EMAILSENDING1 ];
            }catch(Exception $e){
                return [ C::KEY_CODE => 500, C::KEY_DONE => false, C::KEY_MESSAGE => Msg::ERR_UNKNOWN ];
            }
        }//if(isset($post['oggetto'],$post['messaggio']) && $post['oggetto'] != '' && $post['messaggio'] != ''){
        return [ C::KEY_CODE => 400, C::KEY_DONE => false, C::KEY_MESSAGE => Msg::ERR_REQUIREDFIELDSNOTFILLED ];
    }
}
?>