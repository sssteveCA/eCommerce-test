<?php

use EcommerceTest\Objects\Ordine;
use EcommerceTest\Objects\Prodotto;
use EcommerceTest\Objects\Utente;
use EcommerceTest\Objects\Carrello;
use EcommerceTest\Interfaces\Messages as Msg;

ob_start();
session_start();
require_once('../config.php');
require_once('../interfaces/mysqlVals.php');
require_once('../interfaces/messages.php');
require_once('../interfaces/orderErrors.php');
require_once('../interfaces/productErrors.php');
require_once("../interfaces/userErrors.php");
require_once('../objects/carrello.php');
require_once('../objects/ordine.php');
require_once('../objects/prodotto.php');
require_once('../objects/utente.php');
require_once('const.php');

$risposta = array();
$risposta['msg'] = '';
$ajax =  (isset($_POST['ajax']) && $_POST['ajax'] == '1');

if(isset($_SESSION['logged'],$_SESSION['utente'],$_SESSION['welcome']) && $_SESSION['welcome'] != '' && $_SESSION['logged'] === true){
    $utente = unserialize($_SESSION['utente']);
    $returnUrl = dirname($_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].$_SERVER['SCRIPT_NAME'],2).'/cartSuccess.php';
    $cancelUrl = dirname($_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].$_SERVER['SCRIPT_NAME'],2).'/cartCancel.php';
    //ID degli ordini contenuti nel carrello
    $ordiniCarr = Carrello::getCartIdos($utente->getUsername());
    if(Carrello::nProdotti() > 0){
        $carrello = array();
        foreach($ordiniCarr as $v){
            //$risposta['msg'] .= "v = {$v}<br>";
            try{
                $ordine = new Ordine(array('id' => $v));
                if($ordine->getNumError() == 0){
                    //id del venditore
                    $idv = $ordine->getIdv();
                    //id dell'ordine
                    $ido = $ordine->getId(); 
                    //$risposta['msg'] .= "idv = {$idv}<br>";
                    //array per ottenere le informazioni del venditore
                    $vArray = array();
                    $vArray['id'] = $idv;
                    $vArray['registrato'] = '1';
                    $venditore = new Utente($vArray);
                    if($venditore->getNumError() == 0 || $venditore->getNumError() == 1){
                        //Id del prodotto
                        $idp = $ordine->getIdp();
                        //$risposta['msg'] .= "idp = {$idp}<br>";
                        $prodotto = new Prodotto(array('id' => $idp));
                        if($prodotto->getNumError() == 0){
                            //Inserisco le informazioni sugli ordini nell'array $carrello
                            $carrello[$ido]['email'] = $venditore->getPaypalMail();
                            $carrello[$ido]['idv'] = $venditore->getId();
                            $carrello[$ido]['nome'] = $prodotto->getNome();
                            $carrello[$ido]['prezzo'] = $prodotto->getPrezzo();
                            $carrello[$ido]['quantita'] = $ordine->getQuantita();
                            $carrello[$ido]['totale'] = $ordine->getTotale();
                            //$risposta['msg'] .= $prodotto->getNome().'<br>';
                        }
                        else http_response_code(400);
                    }
                }
                else{
                    http_response_code(400);
                    $risposta['msg'] = $ordine->getStrError();
                    break;
                } 
            }
            catch(Exception $e){
                http_response_code(500);
                $risposta['msg'] = $e->getMessage();
            }
        }
    }
    else{
        $risposta['msg'] = Msg::CARTEMPTY;
    }
    if(!empty($carrello)){
        //API Paypal
    }
}
else{
    http_response_code(401);
    $risposta['msg'] = '<a href="../accedi.php">Accedi</a> per poter vedere il contenuto di questa pagina<br>';
}
if($ajax){}
else{
?>
<!DOCTYPE html>
<html lang="it">
    <head>
        <title>Paga carrello</title>
        <meta charset="utf-8">
        <style>
            div{
                padding: 20px;
            }
            img{
                width: 60px;
                height: 60px;
            }
        </style>
    </head>
    <body>
        <div id="indietro">
            <a href="../carrello.php"><img src="../img/altre/indietro.png" alt="indietro" title="indietro"></a>
            <a href="../carrello.php">Indietro</a>
        </div>
         <div>
            <?php echo $risposta['msg']; ?>
         </div>
    </body>
<?php
}
?>