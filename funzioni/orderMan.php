<?php

use Dotenv\Dotenv;
use EcommerceTest\Objects\Ordine;
use EcommerceTest\Objects\Prodotto;
use EcommerceTest\Objects\Utente;
use EcommerceTest\Interfaces\UserErrors as Ue;
use EcommerceTest\Interfaces\Messages as Msg;
use EcommerceTest\Interfaces\Constants as C;

session_start();

require_once('../vendor/autoload.php');

$response = array(
    C::KEY_DONE => false,
    C::KEY_MESSAGE => ''
);

//se un'utente ha effettuato il login
if(isset($_SESSION['logged'],$_SESSION['utente'],$_SESSION['welcome']) && $_SESSION['welcome'] != '' && $_SESSION['logged'] === true){
    $dotenv = Dotenv::createImmutable(__DIR__."/../");
    $dotenv->safeLoad();
    $p = array(); //array da passare alla classe prodotto
    $v = array(); //array da passare alla classe utente(venditore)
    $utente = unserialize($_SESSION['utente']);
    $nomeUtente = $utente->getUsername();
    if(isset($_GET['oper'])){
        //se l'utente richiede gli ordini che ha effettuato
        if($_GET['oper'] == '0'){
            getOrders($response);
        }//if($_GET['oper'] == '0'){
        //l'utente richiede i dettagli di un ordine specifico
        else if($_GET['oper'] == '1' && isset($_GET['idOrd'])){
            getOrder($response);
        }//else if($_GET['oper'] == '1' && isset($_GET['idOrd'])){
        //l'utente vuole cancellare un determinato ordine
        else if($_GET['oper'] == '2' && isset($_GET['idOrd'])){
            deleteOrder($response, $utente);
            //else echo 'non esiste';
            //$canc = cancOrdine($_GET['idOrd'],$_SESSION['user']);
        }// else if($_GET['oper'] == '2' && isset($_GET['idOrd'])){
        //l'utente vuole aggiungere al carrello un ordine
         //l'utente vuole modificare la quantità di un ordine
         else if($_GET['oper'] == '3' && isset($_GET['idOrd']) && isset($_GET['quantita'])){
            editOrderQuantity($response);
        }//else if($_GET['oper'] == '3' && isset($_GET['idOrd']) && isset($_GET['quantita'])){
        //l utente vuole aggiungere al carrello un ordine
        else if($_GET['oper'] == '4' && isset($_GET['idOrd'])){
            addOrderToCart($response,$utente);
        }//else if($_GET['oper'] == '4' && isset($_GET['idOrd'])){
        //l'utente vuole pagare un ordine lasciato in sospeso
        else if($_GET['oper'] == '5' && isset($_GET['idOrd'])){
            payOrder($response);
        }//else if($_GET['oper'] == '5' && isset($_GET['idOrd'])){
        else{
            http_response_code(400);
            $response[C::KEY_MESSAGE] = Msg::ERR_INVALIDOPERATION1;
        }
    }//if(isset($_GET['oper'])){
    else{
        http_response_code(400);
        $response[C::KEY_MESSAGE] = Msg::ERR_NOOPERATION;
    }
    echo json_encode($response,JSON_UNESCAPED_UNICODE);

}//if(isset($_SESSION['logged'],$_SESSION['utente'],$_SESSION['welcome']) && $_SESSION['welcome'] != '' && $_SESSION['logged'] === true){
else{
    echo ACCEDI2;
}

//Orders list of logged user
function getOrders(array &$response){
    global $nomeUtente;
    $done = true;
    $ordiniCliente = Ordine::getIdList($_ENV['MYSQL_HOSTNAME'],$_ENV['MYSQL_USERNAME'],$_ENV['MYSQL_PASSWORD'],$_ENV['MYSQL_DATABASE'],$_ENV['TABORD'],$_ENV['TABACC'],$nomeUtente);
    $response['i'] = count($ordiniCliente);
    $response['tab'] = '1'; //se verrà creata la tabella con gli ordini
    $response['orders'] = array();
    if($response['i'] > 0){
        $i = 0;
        foreach($ordiniCliente as $v){
            try{
                $ordine = new Ordine(array('id' => $v));
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
                    $response['orders'][$i]=$datiOrdine;
                    $_SESSION['ordini'][$ordine->getId()]=$datiOrdine;
                    $i++;
                }//if($ordine->getNumError() == 0){
                else{
                        http_response_code(400);
                        $response[C::KEY_MESSAGE] = $ordine->getStrError().'<br>';
                        $response[C::KEY_MESSAGE] .= ' Linea n. '.__LINE__;
                        $done = false;
                        break;
                }
            }
            catch(Exception $e){
                http_response_code(500);
                $response[C::KEY_MESSAGE] = $e->getMessage();
                $response[C::KEY_MESSAGE] .= ' Linea n. '.__LINE__;
                $done = false;
                break;
            }
        }//foreach($ordiniCliente as $v){  
    }//if($response['i'] > 0){
    $response[C::KEY_DONE]  = $done;
}

//Information about single order
function getOrder(array &$response){
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
                        $response['order']['nomeP'] = $prodotto->getNome();
                        $response['order']['tipo'] = $prodotto->getTipo();
                        $response['order']['prezzo'] = sprintf("%.2f",$prodotto->getPrezzo());
                        $response['order']['spedizione'] = sprintf("%.2f",$prodotto->getSpedizione());
                        $response['order']['quantita'] = $ordine->getQuantita();
                        $response['order']['stato'] = $prodotto->getStato();
                        $response['order']['citta'] = $prodotto->getCitta();
                        $response['order']['totale'] = sprintf("%.2f",$ordine->getTotale());
                        $response['order']['nome'] = $venditore->getNome();
                        $response['order']['cognome'] = $venditore->getCognome();
                        $response['order']['nascita'] = $venditore->getNascita();
                        $response['order']['indirizzo'] = $venditore->getIndirizzo();
                        $response['order']['numero'] = $venditore->getNumero();
                        $response['order']['citta'] = $venditore->getCitta();
                        $response['order']['cap'] = $venditore->getCap();
                        $response['order']['email'] = $venditore->getEmail();
                        $response['info'] = '1';
                        $response[C::KEY_DONE] = true;
                    }//if($venditore->getNumError() == 0 || $venditore->getNumError() == Ue::INCORRECTLOGINDATA){
                    else{
                        http_response_code(400);
                        $response[C::KEY_MESSAGE] = $venditore->getStrError().'<br>';
                        //$response[C::KEY_MESSAGE] .= ' Linea n. '.__LINE__;
                    }
                }//if($prodotto->getNumError() == 0){
                else{
                    http_response_code(400);
                    $response[C::KEY_MESSAGE] = $prodotto->getStrError().'<br>';
                    //$response[C::KEY_MESSAGE] .= ' Linea n. '.__LINE__;
                }
            }//if($ordine->getNumError() == 0){
            else{
                http_response_code(400);
                $response[C::KEY_MESSAGE] = $ordine->getStrError().'<br>';
                //$response[C::KEY_MESSAGE] .= ' Linea n. '.__LINE__;
            }              
        }
        catch(Exception $e){
            http_response_code(500);
            $response[C::KEY_MESSAGE] = $e->getMessage().'<br>';
            //$response[C::KEY_MESSAGE] .= ' Linea n. '.__LINE__;
        }
    }//if(isset($_SESSION['ordini'][$_GET['idOrd']])){
}

//User wants delete an order
function deleteOrder(array &$response,Utente $utente){
    if(isset($_SESSION['ordini'][$_GET['idOrd']])){
        //echo 'esiste ordini';
        try{
            $ordine = new Ordine(array('id' => $_GET['idOrd']));
            $ok = $ordine->cancOrdine($utente->getUsername());
            if($ok){
                $response[C::KEY_DONE] = true;
                $response[C::KEY_MESSAGE] = Msg::ORDERDELETED;
                $response['aggiorna'] = '1';
                unset($_SESSION['ordini']);
            }
            else{
                http_response_code(500);
                $response[C::KEY_MESSAGE] = $ordine->getStrError().'<br>';
                $response[C::KEY_MESSAGE] .= ' Linea n. '.__LINE__;
            }
        }
        catch(Exception $e){
            http_response_code(500);
            $response[C::KEY_MESSAGE] = $e->getMessage().'<br>';
            $response[C::KEY_MESSAGE] .= ' Linea n. '.__LINE__;
        }
    }//if(isset($_SESSION['ordini'][$_GET['idOrd']])){
}

//User wants edit order quantity
function editOrderQuantity(array &$response){
    if(isset($_SESSION['ordini'][$_GET['idOrd']])){
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
                            $response[C::KEY_MESSAGE] = Msg::ORDERAMOUNTUPDATED;
                            $response['aggiorna'] = '1';
                            $response[C::KEY_DONE] = true;
                        }
                        else{
                            http_response_code(500);
                            $response[C::KEY_MESSAGE] = Msg::ERR_ORDERAMOUNTNOTPDATED;
                        }
                    }//if($prodotto->getNumError() == 0){
                    else{
                        http_response_code(400);
                        $response[C::KEY_MESSAGE] = $prodotto->getStrError().'<br>';
                        $response[C::KEY_MESSAGE] .= ' Linea n. '.__LINE__;
                    }
                }//if($ordine->getNumError() == 0){
                else{
                    http_response_code(400);
                    $response[C::KEY_MESSAGE] = $ordine->getStrError().'<br>';
                    $response[C::KEY_MESSAGE] .= ' Linea n. '.__LINE__;
                }
            }
            catch(Exception $e){
                http_response_code(500);
                $response[C::KEY_MESSAGE] = $e->getMessage().'<br>';
                $response[C::KEY_MESSAGE] .= ' Linea n. '.__LINE__;
            }
        }//if(is_numeric($quantita) && $quantita > 0){
        else{
            http_response_code(400);
            $response[C::KEY_MESSAGE] = Msg::ERR_ORDERINVALIDAMOUNT;
        }
    }//if(isset($_SESSION['ordini'][$_GET['idOrd']])){
    else{
        http_response_code(400);
        $response[C::KEY_MESSAGE] = Msg::ERR_ORDERINVALID;
    }
}

//user wants add an order to cart
function addOrderToCart(array &$response, Utente $utente){
    if(isset($_SESSION['ordini'][$_GET['idOrd']])){
        try{
            $ordine = new Ordine(array('id' => $_GET['idOrd']));
            if($ordine->getNumError() == 0){
                $carrello = $ordine->isCarrello();
                if(!$carrello){
                    $aggiungi = $ordine->addToCart($utente->getUsername());
                    if($aggiungi){
                        $response[C::KEY_DONE] = true;
                        $response[C::KEY_MESSAGE] = Msg::ORDERINSERTEDCART;
                        $response['aggiorna'] = '1';
                    }
                    else{                           
                        $response[C::KEY_MESSAGE] = $ordine->getStrError().'<br>';
                        $response[C::KEY_MESSAGE] .= ' Linea n. '.__LINE__;
                    }
                }//if(!$carrello){
                else $response[C::KEY_MESSAGE] = Msg::ERR_ORDERALREALDYCART;
            }//if($ordine->getNumError() == 0){
            else{
                $response[C::KEY_MESSAGE] = $ordine->getStrError().'<br>';
                $response[C::KEY_MESSAGE] .= ' Linea n. '.__LINE__;
            }
        }
        catch(Exception $e){
            $response[C::KEY_MESSAGE] = $e->getMessage().'<br>';
            $response[C::KEY_MESSAGE] .= ' Linea n. '.__LINE__;
        }
    }// if(isset($_SESSION['ordini'][$_GET['idOrd']])){
    else{
        $response[C::KEY_MESSAGE] = Msg::ERR_ORDERINVALID;
    }
}

//user wants pay a pending order
function payOrder(array &$response){
    if(isset($_SESSION['ordini'][$_GET['idOrd']])){
    }
    else{
        http_response_code(400);
        $response[C::KEY_MESSAGE] = Msg::ERR_ORDERINVALID;
    }
}
?>