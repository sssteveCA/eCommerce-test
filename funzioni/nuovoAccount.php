<?php

use EcommerceTest\Objects\Utente;

require_once('../interfaces/userErrors.php');
require_once('../interfaces/mysqlVals.php');
require_once('functions.php');
require_once('../objects/utente.php');
ob_start();

$ajax = (isset($_POST['ajax']) && $_POST['ajax'] == '1');
$risposta = array();
$msg = '';

if(isset($_POST['nome'],$_POST['cognome'],$_POST['nascita'],$_POST['sesso'],$_POST['indirizzo'],$_POST['numero'],$_POST['citta'],$_POST['cap'],$_POST['email'],$_POST['user'],$_POST['password'])){
    $dati = array();
    $dati['nome'] = $_POST['nome'];
    $dati['cognome'] = $_POST['cognome'];
    $dati['nascita'] = $_POST['nascita'];
    $dati['sesso'] = $_POST['sesso'];
    $dati['indirizzo'] = $_POST['indirizzo'];
    $dati['numero'] = $_POST['numero'];
    $dati['citta'] = $_POST['citta'];
    $dati['cap'] = $_POST['cap'];
    //campi non obbligatori
    if(preg_match(Utente::$regex['paypalMail'],$_POST['paypalMail'] != ''))$dati['paypalMail'] = $_POST['paypalMail'];
    if(preg_match(Utente::$regex['clientId'],$_POST['clientId'] != ''))$dati['clientId'] = $_POST['clientId'];
    $dati['email'] = $_POST['email'];
    $dati['username'] = $_POST['user'];
    $dati['password'] = password_hash($_POST['password'],PASSWORD_DEFAULT);
    $dati['registrato'] = false;
    $dataCorretta = false;
    $dataArr=explode('-',$dati['nascita']);
    //verifico se la data è valida
    if(isset($dataArr[0],$dataArr[1],$dataArr[2])) $dataCorretta = checkdate($dataArr[1],$dataArr[2],$dataArr[0]);
    $regOk = false; //true se la registrazione ha avuto successo
    if($dataCorretta){
        //verifico se il sesso ha valore valido
        if((strcasecmp($dati['sesso'],'M') == 0)||(strcasecmp($dati['sesso'],'F') == 0)){
            if($dati['sesso'] == 'M')$dati['sesso'] = 'Maschio';
            else $dati['sesso'] = 'Femmina';
            //controllo se la mail è valida con un'espressione regolare
            $regex = '/^[a-zA-Z-_0-9]{4,20}@([a-z]{3,15}\.){1,6}[a-z]{2,10}$/';
            if(preg_match($regex,$dati['email'])){
                //se la mail Paypal esiste ma non è in un formato valido
                $paypalOk = (isset($dati['paypalMail']) && !preg_match($regex,$dati['paypalMail']));
                if(!$paypalOk){
                    try{
                        $utente = new Utente($dati);
                        //se non ci sono errori
                        if($utente->getNumError() == 0){
                            $codAut = $utente->getCodAut();
                            $indAtt = dirname($_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].$_SERVER['SCRIPT_NAME'],2).'/attiva.php';
                            $indAttCod = $indAtt.'?codAut='.$codAut;                    
                            $headers = <<<HEADER
From: Admin <noreply@localhost.lan>
Reply-to: <noreply@localhost.lan>
Content-type: text/html
MIME-Version: 1.0
HEADER;
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
<p><a href="{$indAttCod}">{$indAttCod}</a></p>
oppure vai all'indirizzo <p><a href="{$indAtt}">{$indAtt}</a></p> e incolla il seguente codice: 
<p>{$codAut}</p>
        </div>
    </body>
</html>
HTML;
                                    //email di attivazione account
                                    $utente->sendMail($utente->getEmail(),'Attivazione account',$msg,$headers);
                                    $regOk = true;
                            }//if($utente->getNumError() == 0){
                            else{
                                $risposta['msg'] = $utente->getStrError().'<br>';
                                //$risposta['msg'] .= ' Linea n. '.__LINE__;
                                /*echo 'Errore n. '.$utente->getNumError().'<br>';
                                echo 'Query. '.$utente->getQuery().'<br>';*/
                            }
                        }
                        catch(Exception $e){
                            $risposta['msg'] = $e->getMessage();
                        }
                    }//if(!$paypalOk){
                    else{
                        $risposta['msg'] = 'La mail business non è valida';
                    }
                }// if(preg_match($regex,$dati['email'])){
                else{
                    $risposta['msg'] = 'La mail che hai inserito non è valida';
                }
            }//if((strcasecmp($dati['sesso'],'M') == 0)||(strcasecmp($dati['sesso'],'F') == 0)){
            else{
                $risposta['msg'] = 'Il genere inserito non è valido';
            }
            
        }//if($dataCorretta){
        else{
            $risposta['msg'] = 'La data inserita non è valida';
        }
        //redirect alla pagina per loggarsi se la registrazione ha avuto successo
        if($regOk){
            $risposta['msg'] = 'Registrazione completata con successo,<br>attiva l\' account accedendo alla tua casella di posta';
            if(!$ajax)
                header('refresh:10;url=../accedi.php');
            
        }
        //redirect al form di registrazione se l'account non è stato creato
        else{
            if(!$ajax)header('refresh:10;url=../registrati.php');
        }
}//if(isset($_POST['nome'],$_POST['cognome'],$_POST['nascita'],$_POST['sesso'],$_POST['indirizzo'],$_POST['numero'],$_POST['citta'],$_POST['cap'],$_POST['email'],$_POST['user'],$_POST['password'])){
else{
    if($ajax)$risposta['msg'] = 'Uno o più campi obbligatori non sono stati compilati';
    $risposta['msg'] = 'Compila correttamente il form alla pagina <a href="../registrati.php">form</a> per eseguire questo script';
}
if($ajax)echo json_encode($risposta);
else echo $risposta['msg'];
?>