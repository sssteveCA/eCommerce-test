<?php

use EcommerceTest\Objects\Utente;
use EcommerceTest\Interfaces\Messages as Msg;

//pagina che effettua la richiesta POST: reset.php
ob_start();

require_once('../interfaces/messages.php');
require_once('../interfaces/userErrors.php');
require_once('../interfaces/mysqlVals.php');
require_once('config.php');
require_once('../objects/utente.php');
require_once('const.php');

$regex = '/^[a-z0-9]{64}$/i';
$messaggio = array();
if(isset($_REQUEST['chiave']) && preg_match($regex,$_REQUEST['chiave'])){
    if(isset($_POST['nuova'],$_POST['confNuova']) && $_POST['nuova'] != '' && $_POST['confNuova'] != ''){
        //$ajax = true se è stata effettuata una chiamata AJAX per eseguire lo script
        $ajax = (isset($_POST['ajax']) && $_POST['ajax'] == '1');
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
                $messaggio['done'] = '1';
                $mess = 'Password modificata';
            }
            else{
                 $mess = $utente->getStrError().'<br>';
                 $mess .= ' Linea n. '.__LINE__;
            }
        }//if($nuova == $conf){
        else{
            $mess = Msg::ERR_PWDNOTEQUAL;
        }
    }//if(isset($_POST['nuova'],$_POST['confNuova']) && $_POST['nuova'] != '' && $_POST['confNuova'] != ''){
    else{
        $mess = Msg::ERR_PWDNOTSETTED;
    }
}//if(isset($_REQUEST['chiave']) && preg_match($regex,$_REQUEST['chiave'])){
else{
    $mess = Msg::ERR_CODEINVALD;
}
if($ajax){
    $messaggio['msg'] = $mess;
    echo json_encode($messaggio);
}
//se non è stata fatta una chiamata con AJAX mostra la pagina HTML
else {
    $html = <<<HTML
<!DOCTYPE html>
<html lang="it">
    <head>
        <title>Recupera password</title>
        <meta charset="utf-8">
    </head>
    <body>
    {$mess}
    </body>
</html>
HTML;
    echo $html;
}
?>