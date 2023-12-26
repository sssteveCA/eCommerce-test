<?php

use Dotenv\Dotenv;
use EcommerceTest\Objects\Ordine;
use EcommerceTest\Objects\Prodotto;
use EcommerceTest\Objects\Utente;
use EcommerceTest\Objects\Carrello;
use EcommerceTest\Interfaces\Messages as Msg;
use EcommerceTest\Interfaces\Constants as C;

ob_start();
session_start();

require_once('../vendor/autoload.php');


$response = [ C::KEY_MESSAGE => ''];
$ajax =  (isset($_POST[C::KEY_AJAX]) && $_POST[C::KEY_AJAX] == '1');

if(isset($_SESSION['logged'],$_SESSION['utente'],$_SESSION['welcome']) && $_SESSION['welcome'] != '' && $_SESSION['logged'] === true){
    $dotenv = Dotenv::createImmutable(__DIR__."/../");
    $dotenv->safeLoad();
    $utente = unserialize($_SESSION['utente']);
    $returnUrl = dirname($_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].$_SERVER['SCRIPT_NAME'],2).'/cartSuccess.php';
    $cancelUrl = dirname($_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].$_SERVER['SCRIPT_NAME'],2).'/cartCancel.php';
    //ID degli ordini contenuti nel carrello
    $ordiniCarr = Carrello::getCartIdos($utente->getUsername());
    if(Carrello::nProdotti() > 0){
        $carrello = array();
        foreach($ordiniCarr as $v){
            //$response[C::KEY_MESSAGE] .= "v = {$v}<br>";
            try{
                $ordine = new Ordine(array('id' => $v));
                if($ordine->getNumError() == 0){
                    //id del venditore
                    $idv = $ordine->getIdv();
                    //id dell'ordine
                    $ido = $ordine->getId(); 
                    //$response[C::KEY_MESSAGE] .= "idv = {$idv}<br>";
                    //array per ottenere le informazioni del venditore
                    $vArray = array();
                    $vArray['id'] = $idv;
                    $vArray['registrato'] = '1';
                    $venditore = new Utente($vArray);
                    if($venditore->getNumError() == 0 || $venditore->getNumError() == 1){
                        //Id del prodotto
                        $idp = $ordine->getIdp();
                        //$response[C::KEY_MESSAGE] .= "idp = {$idp}<br>";
                        $prodotto = new Prodotto(array('id' => $idp));
                        if($prodotto->getNumError() == 0){
                            //Inserisco le informazioni sugli ordini nell'array $carrello
                            $carrello[$ido]['email'] = $venditore->getPaypalMail();
                            $carrello[$ido]['idv'] = $venditore->getId();
                            $carrello[$ido]['nome'] = $prodotto->getNome();
                            $carrello[$ido]['prezzo'] = $prodotto->getPrezzo();
                            $carrello[$ido]['quantita'] = $ordine->getQuantita();
                            $carrello[$ido]['totale'] = $ordine->getTotale();
                            //$response[C::KEY_MESSAGE] .= $prodotto->getNome().'<br>';
                        }
                        else http_response_code(400);
                    }
                }
                else{
                    http_response_code(400);
                    $response[C::KEY_MESSAGE] = $ordine->getStrError();
                    break;
                } 
            }
            catch(Exception $e){
                http_response_code(500);
                $response[C::KEY_MESSAGE] = $e->getMessage();
            }
        }
    }
    else{
        $response[C::KEY_MESSAGE] = Msg::CARTEMPTY;
    }
    if(!empty($carrello)){
        //API Paypal
    }
}
else{
    http_response_code(401);
    $response[C::KEY_MESSAGE] = '<a href="../accedi.php">Accedi</a> per poter vedere il contenuto di questa pagina<br>';
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
            <?php echo $response[C::KEY_MESSAGE]; ?>
         </div>
    </body>
<?php
}
?>