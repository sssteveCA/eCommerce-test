<?php

use Dotenv\Dotenv;
use EcommerceTest\Objects\Utente;
use EcommerceTest\Interfaces\Messages as Msg;
use EcommerceTest\Interfaces\Constants as C;

//pagina che effettua la richiesta POST: reset.php
ob_start();

require_once('../config.php');
require_once('../interfaces/constants.php');
require_once('../interfaces/messages.php');
require_once('../interfaces/userErrors.php');
require_once('../interfaces/emailmanagerErrors.php');
require_once('../exceptions/notsetted.php');
//require_once('../interfaces/mysqlVals.php');
require_once('config.php');
require_once("../vendor/autoload.php");
require_once('../traits/error.php');
require_once('../traits/emailmanager.trait.php');
require_once('../traits/sql.trait.php');
require_once('../traits/utente.trait.php');
require_once('../objects/emailmanager.php');
require_once('../objects/utente.php');
require_once('const.php');

$regex = '/^[a-z0-9]{64}$/i';
$response = array();
if(isset($_REQUEST['chiave']) && preg_match($regex,$_REQUEST['chiave'])){
    if(isset($_POST['nuova'],$_POST['confNuova']) && $_POST['nuova'] != '' && $_POST['confNuova'] != ''){
        $dotenv = Dotenv::createImmutable(__DIR__."/../");
        $dotenv->safeLoad();
        //$ajax = true se è stata effettuata una chiamata AJAX per eseguire lo script
        $ajax = (isset($_POST[C::KEY_AJAX]) && $_POST[C::KEY_AJAX] == '1');
        $nuova = $_POST['nuova']; //nuova password
        $conf = $_POST['confNuova']; //conferma nuova password
        //se le due password coincidono
        if($nuova == $conf){
            $dati = array();
            $dati['campo'] = 'cambioPwd';
            $dati['registrato'] = '1';
            $dati['dimenticata'] = '1';
            $dati['nuovaP'] = $nuova;
            $dati['cambioPwd'] = $_REQUEST['chiave'];
            $dati['dataCambioPwd'] = time()-$attesa;
            $utente = new Utente($dati);
            if($utente->getNumError() == 0){
                $response[C::KEY_DONE] = '1';
                $mess = 'Password modificata';
            }
            else{
                http_response_code(400);
                 $mess = $utente->getStrError().'<br>';
                 $mess .= ' Linea n. '.__LINE__;
            }
        }//if($nuova == $conf){
        else{
            http_response_code(400);
            $mess = Msg::ERR_PWDNOTEQUAL;
        }
    }//if(isset($_POST['nuova'],$_POST['confNuova']) && $_POST['nuova'] != '' && $_POST['confNuova'] != ''){
    else{
        http_response_code(400);
        $mess = Msg::ERR_PWDNOTSETTED;
    }
}//if(isset($_REQUEST['chiave']) && preg_match($regex,$_REQUEST['chiave'])){
else{
    http_response_code(400);
    $mess = Msg::ERR_CODEINVALD;
}
if($ajax){
    $response[C::KEY_MESSAGE] = $mess;
    echo json_encode($response,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
}
//se non è stata fatta una chiamata con AJAX mostra la pagina HTML
else {
    $html = <<<HTML
<!DOCTYPE html>
<html lang="it">
    <head>
        <title>Recupera password</title>
        <meta charset="utf-8">
        <link rel="stylesheet" href={$css}>
    </head>
    <body>
    {$mess}
    </body>
</html>
HTML;
    echo $html;
}
?>