<?php

use Dotenv\Dotenv;
use EcommerceTest\Objects\Utente;
use EcommerceTest\Interfaces\Messages as Msg;
use EcommerceTest\Config as Cf;

require_once('config.php');
require_once('../config.php');
require_once('../interfaces/messages.php');
require_once('../interfaces/userErrors.php');
require_once('../interfaces/emailmanagerErrors.php');
require_once('../exceptions/notsetted.php');
//require_once('../interfaces/mysqlVals.php');
require_once('../vendor/autoload.php');
require_once('../traits/error.php');
require_once('../traits/emailmanager.trait.php');
require_once('functions.php');
require_once('../objects/emailmanager.php');
require_once('../objects/utente.php');
ob_start();

$dotenv = Dotenv::createImmutable(__DIR__."/../");
$dotenv->safeLoad();
$hostname = $_ENV['HOSTNAME'];
$input = file_get_contents("php://input");
$post = json_decode($input,true);
$response = array();
$response['msg'] = '';

$ajax = (isset($post['ajax']) && $post['ajax'] == true);

if(isset($post['name'],$post['surname'],$post['birth'],$post['sex'],$post['address'],$post['number'],$post['city'],$post['zip'],$post['email'],$post['username'],$post['password'],$post['confPass'])){
    if($post['password'] == $post['confPass']){
       $data = assign();
        $validDate = dateControl($data['nascita']);
        if($validDate){
            $sex = checkSex($data['sesso']);
            if($sex != null){
                if(!isset($data['paypalMail']))$data['paypalMail'] = null;
                $okMails = checkMails($data['email'],$data['paypalMail']);
                if($okMails == 1){
                    try{
                        $utente = new Utente($data);
                        //if there are no errors
                        if($utente->getNumError() == 0){
                            $params = array();
                            $params['codAut'] = $utente->getCodAut();
                            $url = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].$_SERVER['SCRIPT_NAME'];
                            $params['indAtt'] = dirname($url,2).'/attiva.php';
                            $params['indAttCod'] = $params['indAtt'].'?codAut='.$params['codAut'];
                            $headers = msg_headers();
                            $message = msg_body($params);
                            $from = "noreply@{$_ENV['HOSTNAME']}.lan";
                            //send activation email
                            $send = $utente->sendMail($utente->getEmail(),'Attivazione account',$message,$headers);
                            if($send){
                                $response['msg'] = Msg::SUBSCRIBECOMPLETED;
                                if(!$ajax)
                                    header('referesh:10;url=../index.php');
                            }//if($send){
                            else {
                                http_response_code(500);
                                $response['msg'] = $utente->getStrError();
                            }
                        }//if($utente->getNumError() == 0){
                        else{
                            http_response_code(400);
                            //$response['queries'] = $utente->getQueries();
                            $response['msg'] = $utente->getStrError();
                        }
                    }
                    catch(Exception $e){
                        http_response_code(500);
                        $response['msg'] = $e->getMessage();
                    }
                }//if($okMails == 1){
                else{
                    if($okMails == -1){
                        http_response_code(400);
                        $response['msg'] = Msg::ERR_EMAILINVALID;
                    }  
                    else if($okMails == -2){
                        http_response_code(400);
                        $response['msg'] = Msg::ERR_EMAILBUSINESSINVALID;
                    }       
                }
            }//if($sex != null){
            else{
                http_response_code(400);
                $response['msg'] = Msg::ERR_GENDERINVALID;
            }   
        }//if($validDate){
        $regOk = false; //true if subscribe is done successfully
    }//if($data['password'] == $data['confPass']){
    else{
        http_response_code(400);
        $response['msg'] = Msg::ERR_PWDNOTEQUAL;
    }
        
}//if(isset($data['nome'],$data['cognome'],$data['nascita'],$data['sesso'],$data['indirizzo'],$data['numero'],$data['citta'],$data['cap'],$data['email'],$data['user'],$data['password'],$data['confPass'])){
else{
    http_response_code(400);
    if($ajax)$response['msg'] = Msg::ERR_REQUIREDFIELDSNOTFILLED;
    else{
        $response['msg'] = 'Compila correttamente il form alla pagina <a href="../registrati.php">form</a> per eseguire questo script';
    }
}

if($ajax)echo json_encode($response,JSON_UNESCAPED_UNICODE);
else echo $response['msg'];

function assign(){
    global $post;
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

function dateControl($birth){
    $ok = false;
    $dataArr=explode('-',$birth);
    //check if date is valid
    if(isset($dataArr[0],$dataArr[1],$dataArr[2])) $ok = checkdate($dataArr[1],$dataArr[2],$dataArr[0]);
    return $ok;
}

function checkSex($sex){
    $sexStr = null;
    if((strcasecmp($sex,'M') == 0)||(strcasecmp($sex,'F') == 0)){
        if($sex == 'M')$sexStr = 'Maschio';
        else $sexStr = 'Femmina';
    }//if((strcasecmp($sex,'M') == 0)||(strcasecmp($sex,'F') == 0)){
    return $sexStr;
}

function checkMails($email,$paypalMail){
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

function msg_headers(){
    $hostname = $_ENV['HOSTNAME'];
    $headers = <<<HEADER
From: Admin <noreply@{$hostname}.lan>
Reply-to: <noreply@{$hostname}.lan>
Content-type: text/html
MIME-Version: 1.0
HEADER;
    return $headers;
}

function msg_body($params){
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
?>