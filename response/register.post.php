<?php

namespace EcommerceTest\Response;
use Dotenv\Dotenv;
use EcommerceTest\Objects\Utente;
use Exception;
use EcommerceTest\Interfaces\Constants as C;
use EcommerceTest\Interfaces\Messages as Msg;

/**
 * New Account post request
 */
class RegisterPost{

    public static function content(array $params): array{
        $post = $params['post'];
        if(isset($post['name'],$post['surname'],$post['birth'],$post['sex'],$post['address'],$post['number'],$post['city'],$post['zip'],$post['email'],$post['username'],$post['password'],$post['confPass'])){
            if($post['password'] == $post['confPass']){
                $data = RegisterPost::assign($post);
                $validDate = RegisterPost::dateControl($data['nascita']);
                if($validDate){
                    $sex = RegisterPost::checkSex($data['sesso']);
                    if($sex != null){
                        if(!isset($data['paypalMail']))$data['paypalMail'] = null;
                        $okMails = RegisterPost::checkMails($data['email'],$data['paypalMail']);
                        if($okMails == 1){
                            try{
                                $dotenv = Dotenv::createImmutable(__DIR__."/../");
                                $dotenv->load();
                                $utente = new Utente($data);
                                if($utente->getNumError() == 0){
                                    $url = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'];
                                    $params = [
                                        'codAut' => $utente->getCodAut(),
                                        'indAtt' => $url.'/activate',
                                    ];
                                    $params['indAttCod'] = $params['indAtt'].'/'.$params['codAut'];
                                    $headers = self::msg_headers();
                                    $message = self::msg_body($params);
                                    $from = "noreply@{$_ENV['HOSTNAME']}";
                                    $send = $utente->sendMail($utente->getEmail(),'Attivazione account',$message,$headers);
                                    if($send){
                                        return [
                                            C::KEY_CODE => 200, C::KEY_DONE => true, C::KEY_MESSAGE => Msg::SUBSCRIBECOMPLETED,
                                            'redirect' => [
                                                'do' => true, 'url' => 'referesh:10;url=../'
                                            ]
                                        ];
                                    }//if($send){      
                                    return [
                                        C::KEY_CODE => 500, C::KEY_DONE => false, C::KEY_MESSAGE => $utente->getStrError(),
                                        'redirect' => [
                                            'do' =>false, 'url' => ''
                                        ]
                                    ];    
                                }//if($utente->getNumError() == 0){
                                return [
                                    C::KEY_CODE => 400, C::KEY_DONE => true, C::KEY_MESSAGE => $utente->getStrError(),
                                    'redirect' => [
                                        'do' => true, 'url' => 'referesh:10;url=../'
                                    ]
                                ];
                            }catch(Exception $e){
                                return [
                                    C::KEY_CODE => 500, C::KEY_DONE => true, C::KEY_MESSAGE => $e->getMessage(),
                                    'redirect' => [
                                        'do' => false, 'url' => ''
                                    ]
                                ];
                            }
                        }//if($okMails == 1){
                            if($okMails == -1){
                                return [
                                    C::KEY_CODE => 400, C::KEY_DONE => true, C::KEY_MESSAGE => Msg::ERR_EMAILINVALID,
                                    'redirect' => [
                                        'do' => false, 'url' => ''
                                    ]
                                ];
                            } 
                            return [
                                C::KEY_CODE => 400, C::KEY_DONE => true, C::KEY_MESSAGE => Msg::ERR_EMAILBUSINESSINVALID,
                                'redirect' => [
                                    'do' => false, 'url' => ''
                                ]
                            ];
                    }//if($sex != null){
                    return [
                        C::KEY_CODE => 400, C::KEY_DONE => true, C::KEY_MESSAGE => Msg::ERR_GENDERINVALID,
                        'redirect' => [
                            'do' => false, 'url' => ''
                        ]
                    ];
                }//if($validDate){
            }//if($post['password'] == $post['confPass']){
            return [
                C::KEY_CODE => 400, C::KEY_DONE => true, C::KEY_MESSAGE => Msg::ERR_PWDNOTEQUAL,
                'redirect' => [
                    'do' => false, 'url' => ''
                ]
            ];  
        }//if(isset($post['name'],$post['surname'],$post['birth'],$post['sex'],$post['address'],$post['number'],$post['city'],$post['zip'],$post['email'],$post['username'],$post['password'],$post['confPass'])){
        return [
            C::KEY_CODE => 400, C::KEY_DONE => true, C::KEY_MESSAGE => Msg::ERR_REQUIREDFIELDSNOTFILLED,
            'redirect' => [
                'do' => false, 'url' => ''
            ]
        ];
    }

    private static function assign(array $post){
        $data = array();
        $data['nome'] = $post['name'];
        $data['cognome'] = $post['surname'];
        $data['nascita'] = $post['birth'];
        $data['sesso'] = $post['sex'];
        $data['indirizzo'] = $post['address'];
        $data['numero'] = $post['number'];
        $data['citta'] = $post['city'];
        $data['cap'] = $post['zip'];
        $data['username'] = $post['username'];
        //Not obligatory fields
        if(preg_match(Utente::$regex['paypalMail'],$post['paypalMail'] != ''))$data['paypalMail'] = $post['paypalMail'];
        if(preg_match(Utente::$regex['clientId'],$post['clientId'] != ''))$data['clientId'] = $post['clientId'];
        $data['email'] = $post['email'];
        $data['password'] = password_hash($post['password'],PASSWORD_DEFAULT);
        $data['registrato'] = false;
        return $data;
    }

    private static function checkMails($email,$paypalMail){
        $ok = 0;
        if(preg_match(Utente::$regex['email'],$email)){
            $ok = 1;
            if(isset($paypalMail)){
                if(!preg_match(Utente::$regex['paypalMail'],$paypalMail))$ok = -2;
            }//if(isset($paypalMail)){
        }//if(preg_match(Utente::$regex['email'],$email)){
        else $ok = -1;
        return $ok;
    }

    private static function checkSex($sex){
        $sexStr = null;
        if((strcasecmp($sex,'M') == 0)||(strcasecmp($sex,'F') == 0)){
            if($sex == 'M')$sexStr = 'Maschio';
            else $sexStr = 'Femmina';
        }//if((strcasecmp($sex,'M') == 0)||(strcasecmp($sex,'F') == 0)){
        return $sexStr;
    }

    private static function dateControl($birth){
        $ok = false;
        $dataArr=explode('-',$birth);
        //check if date is valid
        if(isset($dataArr[0],$dataArr[1],$dataArr[2])) $ok = checkdate($dataArr[1],$dataArr[2],$dataArr[0]);
        return $ok;
    }

    private static function msg_body($params){
        $msg = <<<HTML
<!DOCTYPE html>
<html lang="it">
    <head>
        <title>Attivazione account</title>
        <meta charset="utf-8">
        <style>
            body{
                display: flex;
                justify-content: center;
            }
            div#linkAtt{
                padding: 10px;
                background-color: orange;
                font-size: 20px;
            }
        </style>
    <head>
    <body>
        <div id="linkAtt">
Gentile utente, per completare l'attivazione dell'account clicca nel link sottostante:
<p><a href="{$params['indAttCod']}">{$params['indAttCod']}</a></p>
oppure vai all'indirizzo <p><a href="{$params['indAtt']}">{$params['indAtt']}</a></p> e incolla il seguente codice: 
<p>{$params['codAut']}</p>
        </div>
    </body>
</html>
HTML;
        return $msg;
    }

    private static function msg_headers(){
        $hostname = $_ENV['HOSTNAME'];
        $headers = <<<HEADER
From: Admin <noreply@{$hostname}.lan>
Reply-to: <noreply@{$hostname}.lan>
Content-type: text/html
MIME-Version: 1.0
HEADER;
        return $headers;
    }


}
?>