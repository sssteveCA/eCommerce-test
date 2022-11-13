<?php

use Dotenv\Dotenv;
use EcommerceTest\Objects\Ordine;
use EcommerceTest\Objects\Prodotto;
use EcommerceTest\Objects\Utente;
use EcommerceTest\Interfaces\UserErrors as Ue;
use EcommerceTest\Interfaces\Messages as Msg;

session_start();

require_once('../config.php');
require_once('../interfaces/messages.php');
require_once('../interfaces/orderErrors.php');
require_once('../interfaces/productErrors.php');
require_once('../interfaces/productsVals.php');
require_once('../interfaces/userErrors.php');
//require_once('../interfaces/mysqlVals.php');
require_once('config.php');
require_once("../vendor/autoload.php");
require_once('functions.php');
require_once('../objects/utente.php');
require_once('../objects/prodotto.php');
require_once('../objects/ordine.php');
require_once("const.php");

$risposta = array(
    'done' => false,
    'msg' => ''
);

//se un'utente ha effettuato il login
if(isset($_SESSION['logged'],$_SESSION['utente'],$_SESSION['welcome']) && $_SESSION['welcome'] != '' && $_SESSION['logged'] === true){
    $p = array(); //array da passare alla classe prodotto
    $v = array(); //array da passare alla classe utente(venditore)
    $utente = unserialize($_SESSION['utente']);
    $nomeUtente = $utente->getUsername();
    if(isset($_GET['oper'])){
        //se l'utente richiede gli ordini che ha effettuato
        if($_GET['oper'] == '0'){
            getOrders($risposta);
        }//if($_GET['oper'] == '0'){
        //l'utente richiede i dettagli di un ordine specifico
        else if($_GET['oper'] == '1' && isset($_GET['idOrd'])){
            getOrder($risposta);
        }//else if($_GET['oper'] == '1' && isset($_GET['idOrd'])){
        //l'utente vuole cancellare un determinato ordine
        else if($_GET['oper'] == '2' && isset($_GET['idOrd'])){
            deleteOrder($risposta, $utente);
            //else echo 'non esiste';
            //$canc = cancOrdine($_GET['idOrd'],$_SESSION['user']);
        }// else if($_GET['oper'] == '2' && isset($_GET['idOrd'])){
        //l'utente vuole aggiungere al carrello un ordine
         //l'utente vuole modificare la quantità di un ordine
         else if($_GET['oper'] == '3' && isset($_GET['idOrd']) && isset($_GET['quantita'])){
            editOrderQuantity($risposta);
        }//else if($_GET['oper'] == '3' && isset($_GET['idOrd']) && isset($_GET['quantita'])){
        //l utente vuole aggiungere al carrello un ordine
        else if($_GET['oper'] == '4' && isset($_GET['idOrd'])){
            addOrderToCart($risposta,$utente);
        }//else if($_GET['oper'] == '4' && isset($_GET['idOrd'])){
        //l'utente vuole pagare un ordine lasciato in sospeso
        else if($_GET['oper'] == '5' && isset($_GET['idOrd'])){
            payOrder($risposta);
        }//else if($_GET['oper'] == '5' && isset($_GET['idOrd'])){
        else{
            http_response_code(400);
            $risposta['msg'] = Msg::ERR_INVALIDOPERATION1;
        }
    }//if(isset($_GET['oper'])){
    else{
        http_response_code(400);
        $risposta['msg'] = Msg::ERR_NOOPERATION;
    }
    echo json_encode($risposta,JSON_UNESCAPED_UNICODE);

}//if(isset($_SESSION['logged'],$_SESSION['utente'],$_SESSION['welcome']) && $_SESSION['welcome'] != '' && $_SESSION['logged'] === true){
else{
    echo ACCEDI2;
}

//Orders list of logged user
function getOrders(array &$risposta){
    global $nomeUtente;
    $dotenv = Dotenv::createImmutable(__DIR__."../");
    $dotenv->safeLoad();
    $done = true;
    $ordiniCliente = Ordine::getIdList($_ENV['MYSQL_HOSTNAME'],$_ENV['MYSQL_USERNAME'],$_ENV['MYSQL_PASSWORD'],$_ENV['MYSQL_DATABASE'],$_ENV['TABORD'],$_ENV['TABACC'],$nomeUtente);
    $risposta['i'] = count($ordiniCliente);
    $risposta['tab'] = '1'; //se verrà creata la tabella con gli ordini
    $risposta['orders'] = array();
    if($risposta['i'] > 0){
        $i = 0;
        foreach($ordiniCliente as $v){
            try{
                $ordine = new Ordine(array('id' => $v));
                //var_dump($ordine->isCarrello());
                if($ordine->getNumError() == 0){
                    $datiOrdine = array(
                        'id' => $ordine->getId(),
                        'idc' =>$ordine->getIdc(),
                        'idp' => $ordine->getIdp(),
                        'idv' => $ordine->getIdv(),
                        'data' => $ordine->getData(),
                        'quantita' => $ordine->getQuantita(),
                        'totale' => $ordine->getTotale(),
                        'pagato' => ($ordine->isPagato())? '1':'0',
                        'carrello' => ($ordine->isCarrello())? '1':'0',
                    );
                    $risposta['orders'][$i]=$datiOrdine;
                    $_SESSION['ordini'][$ordine->getId()]=$datiOrdine;
                    $i++;
                }//if($ordine->getNumError() == 0){
                else{
                        http_response_code(400);
                        $risposta['msg'] = $ordine->getStrError().'<br>';
                        $risposta['msg'] .= ' Linea n. '.__LINE__;
                        $done = false;
                        break;
                }
            }
            catch(Exception $e){
                http_response_code(500);
                $risposta['msg'] = $e->getMessage();
                $risposta['msg'] .= ' Linea n. '.__LINE__;
                $done = false;
                break;
            }
        }//foreach($ordiniCliente as $v){  
    }//if($risposta['i'] > 0){
    $risposta['done']  = $done;
}

//Information about single order
function getOrder(array &$risposta){
    if(isset($_SESSION['ordini'][$_GET['idOrd']])){
        try{
            $ordine = new Ordine(array('id' => $_SESSION['ordini'][$_GET['idOrd']]['id']));
            if($ordine->getNumError() == 0){
                $prodotto = new Prodotto(array('id' => $ordine->getIdp()));
                if($prodotto->getNumError() == 0){
                    $vend = array();
                    $vend['id'] = $prodotto->getIdu();
                    $vend['registrato'] = '1';
                    $venditore = new Utente($vend);
                    if($venditore->getNumError() == 0 || $venditore->getNumError() == Ue::INCORRECTLOGINDATA){
                        $risposta['order']['nomeP'] = $prodotto->getNome();
                        $risposta['order']['tipo'] = $prodotto->getTipo();
                        $risposta['order']['prezzo'] = sprintf("%.2f",$prodotto->getPrezzo());
                        $risposta['order']['spedizione'] = sprintf("%.2f",$prodotto->getSpedizione());
                        $risposta['order']['quantita'] = $ordine->getQuantita();
                        $risposta['order']['stato'] = $prodotto->getStato();
                        $risposta['order']['citta'] = $prodotto->getCitta();
                        $risposta['order']['totale'] = sprintf("%.2f",$ordine->getTotale());
                        $risposta['order']['nome'] = $venditore->getNome();
                        $risposta['order']['cognome'] = $venditore->getCognome();
                        $risposta['order']['nascita'] = $venditore->getNascita();
                        $risposta['order']['indirizzo'] = $venditore->getIndirizzo();
                        $risposta['order']['numero'] = $venditore->getNumero();
                        $risposta['order']['citta'] = $venditore->getCitta();
                        $risposta['order']['cap'] = $venditore->getCap();
                        $risposta['order']['email'] = $venditore->getEmail();
                        $risposta['info'] = '1';
                        $risposta['done'] = true;
                    }//if($venditore->getNumError() == 0 || $venditore->getNumError() == Ue::INCORRECTLOGINDATA){
                    else{
                        http_response_code(400);
                        $risposta['msg'] = $venditore->getStrError().'<br>';
                        //$risposta['msg'] .= ' Linea n. '.__LINE__;
                    }
                }//if($prodotto->getNumError() == 0){
                else{
                    http_response_code(400);
                    $risposta['msg'] = $prodotto->getStrError().'<br>';
                    //$risposta['msg'] .= ' Linea n. '.__LINE__;
                }
            }//if($ordine->getNumError() == 0){
            else{
                http_response_code(400);
                $risposta['msg'] = $ordine->getStrError().'<br>';
                //$risposta['msg'] .= ' Linea n. '.__LINE__;
            }              
        }
        catch(Exception $e){
            http_response_code(500);
            $risposta['msg'] = $e->getMessage().'<br>';
            //$risposta['msg'] .= ' Linea n. '.__LINE__;
        }
    }//if(isset($_SESSION['ordini'][$_GET['idOrd']])){
}

//User wants delete an order
function deleteOrder(array &$risposta,Utente $utente){
    if(isset($_SESSION['ordini'][$_GET['idOrd']])){
        //echo 'esiste ordini';
        try{
            $ordine = new Ordine(array('id' => $_GET['idOrd']));
            $ok = $ordine->cancOrdine($utente->getUsername());
            if($ok){
                $risposta['done'] = true;
                $risposta['msg'] = Msg::ORDERDELETED;
                $risposta['aggiorna'] = '1';
                unset($_SESSION['ordini']);
            }
            else{
                http_response_code(500);
                $risposta['msg'] = $ordine->getStrError().'<br>';
                $risposta['msg'] .= ' Linea n. '.__LINE__;
            }
        }
        catch(Exception $e){
            http_response_code(500);
            $risposta['msg'] = $e->getMessage().'<br>';
            $risposta['msg'] .= ' Linea n. '.__LINE__;
        }
    }//if(isset($_SESSION['ordini'][$_GET['idOrd']])){
}

//User wants edit order quantity
function editOrderQuantity(array &$risposta){
    if(isset($_SESSION['ordini'][$_GET['idOrd']])){
        file_put_contents('log.txt',var_export($_GET,true)."\r\n",FILE_APPEND);
        $quantita = $_GET['quantita'];
        if(is_numeric($quantita) && $quantita > 0){
            try{
                $ordine = new Ordine(array('id' => $_GET['idOrd']));
                if($ordine->getNumError() == 0){
                    $idp = $ordine->getIdp();
                    $prodotto = new Prodotto(array('id' => $idp));
                    if($prodotto->getNumError() == 0){
                        $prezzo = $prodotto->getPrezzo();
                        $spedizione = $prodotto->getSpedizione();
                        $aQt = array();
                        $aQt['quantita'] = $quantita;
                        $aQt['totale'] = $quantita*($prezzo+$spedizione);
                        $aQt['totale'] = sprintf("%.2f",$aQt['totale']);
                        $update = $ordine->update($aQt);
                        if($update){
                            $risposta['msg'] = Msg::ORDERAMOUNTUPDATED;
                            $risposta['aggiorna'] = '1';
                            $risposta['done'] = true;
                        }
                        else{
                            http_response_code(500);
                            $risposta['msg'] = Msg::ERR_ORDERAMOUNTNOTPDATED;
                        }
                    }//if($prodotto->getNumError() == 0){
                    else{
                        http_response_code(400);
                        $risposta['msg'] = $prodotto->getStrError().'<br>';
                        $risposta['msg'] .= ' Linea n. '.__LINE__;
                    }
                }//if($ordine->getNumError() == 0){
                else{
                    http_response_code(400);
                    $risposta['msg'] = $ordine->getStrError().'<br>';
                    $risposta['msg'] .= ' Linea n. '.__LINE__;
                }
            }
            catch(Exception $e){
                http_response_code(500);
                $risposta['msg'] = $e->getMessage().'<br>';
                $risposta['msg'] .= ' Linea n. '.__LINE__;
            }
        }//if(is_numeric($quantita) && $quantita > 0){
        else{
            http_response_code(400);
            $risposta['msg'] = Msg::ERR_ORDERINVALIDAMOUNT;
        }
    }//if(isset($_SESSION['ordini'][$_GET['idOrd']])){
    else{
        http_response_code(400);
        $risposta['msg'] = Msg::ERR_ORDERINVALID;
    }
}

//user wants add an order to cart
function addOrderToCart(array &$risposta, Utente $utente){
    if(isset($_SESSION['ordini'][$_GET['idOrd']])){
        try{
            $ordine = new Ordine(array('id' => $_GET['idOrd']));
            if($ordine->getNumError() == 0){
                $carrello = $ordine->isCarrello();
                if(!$carrello){
                    $aggiungi = $ordine->addToCart($utente->getUsername());
                    if($aggiungi){
                        $risposta['done'] = true;
                        $risposta['msg'] = Msg::ORDERINSERTEDCART;
                        $risposta['aggiorna'] = '1';
                    }
                    else{                           
                        $risposta['msg'] = $ordine->getStrError().'<br>';
                        $risposta['msg'] .= ' Linea n. '.__LINE__;
                    }
                }//if(!$carrello){
                else $risposta['msg'] = Msg::ERR_ORDERALREALDYCART;
            }//if($ordine->getNumError() == 0){
            else{
                $risposta['msg'] = $ordine->getStrError().'<br>';
                $risposta['msg'] .= ' Linea n. '.__LINE__;
            }
        }
        catch(Exception $e){
            $risposta['msg'] = $e->getMessage().'<br>';
            $risposta['msg'] .= ' Linea n. '.__LINE__;
        }
    }// if(isset($_SESSION['ordini'][$_GET['idOrd']])){
    else{
        $risposta['msg'] = Msg::ERR_ORDERINVALID;
    }
}

//user wants pay a pending order
function payOrder(array &$risposta){
    if(isset($_SESSION['ordini'][$_GET['idOrd']])){
    }
    else{
        http_response_code(400);
        $risposta['msg'] = Msg::ERR_ORDERINVALID;
    }
}
?>