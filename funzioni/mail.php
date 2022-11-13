<?php

use Dotenv\Dotenv;
use EcommerceTest\Interfaces\Messages as Msg;
use EcommerceTest\Objects\Utente;
use EcommerceTest\Config as Cf;

session_start();

require_once('config.php');
require_once('../config.php');
require_once('../interfaces/messages.php');
require_once('../interfaces/userErrors.php');
//require_once('../interfaces/mysqlVals.php');
require_once("../vendor/autoload.php");
require_once('functions.php');
require_once('../objects/utente.php');
require('const.php');

$risultato = array();
$ajax = (isset($_POST['ajax']) && $_POST['ajax'] == 'true');
$ajax = true;
$risultato['msg'] = Msg::ERR_INVALIDOPERATION2;
$risultato['post'] = $_POST;
$dotenv = Dotenv::createImmutable(__DIR__."/../");
$dotenv->safeLoad();
$hostname = $_ENV['HOSTNAME'];

//se un'utente ha effettuato il login
if(isset($_SESSION['logged'],$_SESSION['utente'],$_SESSION['welcome']) && $_SESSION['welcome'] != '' && $_SESSION['logged'] === true){
    $utente = unserialize($_SESSION['utente']);
    $from = $utente->getEmail();
        //l'utente vuole contattare gli amministratori del sito
    if(isset($_POST['oggetto'],$_POST['messaggio']) && $_POST['oggetto'] != '' && $_POST['messaggio'] != ''){
        $oggetto = $_POST['oggetto'];
        $messaggio = $_POST['messaggio'];
        $to = 'admin@'.$hostname.'.lan';
        $headers = <<<HEADER
To: <{$to}>
From: <{$from}>
Reply-to: <{$from}>
HEADER;
        //invio la mail all'amministratore del sito
        $send = $utente->sendMail($to,$oggetto,$messaggio,$headers);
        if($send){
            $risultato['msg'] = Msg::EMAILSENT2;
        }
        else {
            http_response_code(500);
           $risultato['msg'] = Msg::ERR_EMAILSENDING2; 
        }
    }//if(isset($_POST['oggetto'],$_POST['messaggio']) && $_POST['oggetto'] != '' && $_POST['messaggio'] != ''){
    else {
        http_response_code(500);
        $risultato['msg'] = Msg::ERR_UNKNOWN;
    } 
    //se l'utente vuole contattare il venditore del prodotto visualizzato
    if(isset($_POST['oper']) && $_POST['oper'] == '3'){
        if(isset($_POST['emailTo'],$_POST['pOggetto'],$_POST['pMessaggio']) && preg_match(Utente::$regex['email'],$_POST['emailTo'])){
            $to = $_POST['emailTo'];
            $oggetto = $_POST['pOggetto'];
            $messaggio = $_POST['pMessaggio'];
            $headers = <<<HEADER
To: <{$to}>
From: <{$from}>
Reply-to: <{$from}>
HEADER;
            $send = $utente->sendMail($to,$oggetto,$messaggio,$headers);
            if($send){
                $risultato['msg'] = Msg::EMAILSENT1;
            }
            else {
                http_response_code(500);
                $risposta['msg'] = Msg::ERR_EMAILSENDING1;
            }
        }
        else {
            http_response_code(400);
            $risultato['msg'] = Msg::ERR_INVALIDDATA;
        }
    }//if(isset($_POST['oper']) && $_POST['oper'] == '3'){
}//if(isset($_SESSION['logged'],$_SESSION['utente'],$_SESSION['welcome']) && $_SESSION['welcome'] != '' && $_SESSION['logged'] === true){
else{
    //password dimenticata
    if(isset($_POST['email']) && $_POST['email'] != ''){
        $email = $_POST['email'];
        //codice per il recupero dell'account
        $dati = array();
        $dati['campo'] = 'email';
        $dati['registrato'] = '1';
        $dati['dimenticata'] = '1';
        $dati['email'] = $email;
        $utente = new Utente($dati);
        $valori = array();
        $valori['cambioPwd'] = $utente->getCambioPwd();
        $valori['dataCambioPwd'] = $utente->getDataCambioPwd();
        $where = array();
        $where['email'] = $utente->getEmail();
        $mod = $utente->update($valori,$where);
        if($mod){
            /*indirizzo assoluto della pagina reset.php
            REQUEST_SCHEME = protocollo utilizzato
            SERVER_NAME = nome del sito da cui lo script è eseguito
            SCRIPT_NAME = percorso dello script in esecuzione  */
            $indReset = dirname($_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].$_SERVER['SCRIPT_NAME'],2).'/reset.php';
            //URL con il codice per reimpostare la password
            $indResetCod = $indReset.'?codReset='.$utente->getCambioPwd();
            //inserisce $codReset in 'cambioPwd nel campo 'email' che ha $email
            $headers = <<<HEADER
From: Admin <noreply@{$hostname}.lan>
Reply-to: noreply@{$hostname}.lan
Content-type: text/html
MIME-Version: 1.0
HEADER;
//il messaggio viene inviato come pagina HTML
        $body = <<<HTML
<!DOCTYPE html>
<html lang="it">
    <head>
        <title>Recupera password</title>
        <meta charset="utf-8">
        <style>
            body{
                display: flex;
                justify-content: center;
            }
            div#pagina{
                background-color: cyan;
                padding: 40px;
            }
            div#account{
                background-color: lime;
                padding: 20px;
            }
            p{
                margin: 10px;
            }
            p#messaggio{
                font-size: 20px;
                font-weight: bold;
                color: blue;
            }
        </style>
    </head>
    <body>
        <div id="pagina">
            <p id="messaggio">Gentile utente, fai click sul link sottostante per reimpostare la password</p>
            <div id="account">
                    <p id="link"><a href="{$indResetCod}">{$indResetCod}</a></p>                   
            </div>
        </div>
    </body>
</html>
HTML;
            $send = $utente->sendMail($utente->getEmail(),'Recupero password',$body,$headers);
            if($send){
                $risultato['msg'] = Msg::EMAILRECOVERY;
            }
            else{
                http_response_code(500);
                $risultato['msg'] = Msg::ERR_EMAILSENDING1;
            }
        }//if($mod){
        else{
            http_response_code(500);
            $risultato['msg'] = $utente->getStrError();
        } 
    }
    else{
        http_response_code(400);
        $risultato['msg'] = Msg::ERR_EMAILINSERT;
    }
}//else di if(isset($_SESSION['logged'],$_SESSION['utente'],$_SESSION['welcome']) && $_SESSION['welcome'] != '' && $_SESSION['logged'] === true){
if($ajax)echo json_encode($risultato,JSON_UNESCAPED_UNICODE);
else{
    $html = <<<HTML
<!DOCTYPE html>
<html lang="it">
    <head>
        <title>Mail</title>
        <meta charset="utf-8">
    </head>
    <body>
{$risultato['msg']}
    </body>
</html>
HTML;
echo $html;
}
?>