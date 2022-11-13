<?php
session_start();
require_once('../config.php');
require_once('../interfaces/messages.php');
require_once('../interfaces/orderErrors.php');
require_once('../interfaces/productErrors.php');
require_once('../interfaces/productsVals.php');
require_once('../interfaces/userErrors.php');
//require_once('../interfaces/mysqlVals.php');
require_once("../vendor/autoload.php");
require_once('../objects/utente.php');
require_once('../objects/prodotto.php');
require_once('../objects/ordine.php');
require_once('../objects/carrello.php');
require_once('paypalConfig.php');
require_once('config.php');
require_once('const.php');

use EcommerceTest\Interfaces\UserErrors as Ue;
use EcommerceTest\Interfaces\Messages as Msg;
use EcommerceTest\Objects\Ordine;
use EcommerceTest\Objects\Prodotto;
use EcommerceTest\Objects\Utente;
use EcommerceTest\Objects\Carrello;

$input = file_get_contents("php://input");
$post = json_decode($input,true);

$response = [
    'done' => false,
    'msg' => '',
    'sbn_code' => SBN_CODE,
    'post' => $post
];

$ajax = (isset($post['ajax']) && $post['ajax'] == '1');

if(isset($_SESSION['logged'],$_SESSION['utente'],$_SESSION['welcome']) && $_SESSION['welcome'] != '' && $_SESSION['logged'] === true){
    $user = unserialize($_SESSION['utente']);
    $oData = [];
    if(isset($post['oper'])){
        if($post['oper'] == '1'){
            //Show all items in the cart
            showCart($response,$user);
        }
        else if($post['oper'] == '2'){
            //Add an order to cart
            if(isset($post['ido'],$post['idp']) && is_numeric($post['ido']) && is_numeric($post['idp'])){
                $oData['id'] = $post['ido'];
                $idp = $post['idp'];
                try{
                    addOrderToCart($response,$idp,$user);
                }catch(Exception $e){
                    http_response_code(500);
                    $response['msg'] = $e->getMessage();
                    //$response['msg'] .= ' Linea n. '.__LINE__;
                }
            }//if(isset($post['ido'],$post['idp']) && is_numeric($post['ido']) && is_numeric($post['idp'])){
            else
                $response['msg'] = Msg::ERR_ORDERINVALIDDATA;
        }//else if($post['oper'] == '2'){
        else if($post['oper'] == '3'){
            //Delete an order fron cart
            if(isset($post['ido']) && is_numeric($post['ido'])){
                $oData['id'] = $post['ido'];
                try{
                    delOrderFromCart($response, $oData, $user);
                }catch(Exception $e){
                    http_response_code(500);
                    $risposta['msg'] = $e->getMessage();
                    //$risposta['msg'] .= ' Linea n. '.__LINE__;
                }
            }//if(isset($post['ido']) && is_numeric($post['ido'])){
            else{
                http_response_code(400);
               $response['msg'] = Msg::ERR_ORDERDELETEINVALIDID; 
            }          
        }//else if($post['oper'] == '3'){
    }//if(isset($post['oper'])){
}//if(isset($_SESSION['logged'],$_SESSION['utente'],$_SESSION['welcome']) && $_SESSION['welcome'] != '' && $_SESSION['logged'] === true){
else{
    http_response_code(401);
    $response['msg'] = Msg::ERR_NOTLOGGED;
}
    

if($ajax)
    echo json_encode($response);
else
    echo printHtml($response);

/**
 * Add one item to cart array response
 * 
 * */
function addItemToCart(array &$response, Prodotto $product,Ordine $order){
    $oArray = [
        'ido' => $order->getId(),
        'idp' => $product->getId()
    ];
    $idv = $order->getIdv();
    $aV = [
        'id' => $idv,
        'registrato' => '1'
    ];
    $seller = new Utente($aV);
    if($seller->getNumError() == 0 || $seller->getNumError() == Ue::INCORRECTLOGINDATA){
        $oArray['clientId'] = $seller->getClientId();
    }//if($seller->getNumError() == 0 || $seller->getNumError() == Ue::INCORRECTLOGINDATA){
    else{
        file_put_contents("log.txt",$seller->getStrError()."\r\n",FILE_APPEND);
    }
    $oArray['idv'] = $seller->getId();
    $oArray['nome'] = $product->getNome();
    $oArray['immagine'] = $product->getImmagine();
    $oArray['tipo'] = $product->getTipo();
    $oArray['prezzo'] = $product->getPrezzo();
    $oArray['quantita'] = $order->getQuantita();
    $oArray['spedizione'] = $product->getSpedizione();
    $oArray['totale'] = $order->getTotale();
    $response['carrello'][$idv][] = $oArray;
}

/**
 * Add order to the cart
 */
function addOrderToCart(array $oData, string|int $idp, Utente $user){
    $order = new Ordine($oData);
    if($order->getNumError() == 0){
        //Id of orders inside the cart
        $ordersCart = Carrello::getCartIdos($user->getUsername());
        $already_in = false;
        foreach($ordersCart as $seller){
            foreach($seller as $ido){
                $oCarr = new Ordine(array('id' => $ido));
                //If product is already inside th cart
                if($idp == $oCarr->getIdp()){
                    $already_in = true;
                    break;
                }  
            }//foreach($seller as $ido){
            if($already_in)break;
        }//foreach($ordersCart as $seller){
        if(!$already_in){
            $okAdd = $order->addToCart($user->getUsername());
            if($okAdd){
                $response['msg'] = Msg::ORDERADDEDCART;
            }
            else{
                http_response_code(400);
                $response['msg'] = $order->getStrError();
                //$response['msg'] .= ' Linea n. '.__LINE__;
            }
        }//if(!$already_in){
        else{
            http_response_code(400);
            $response['msg'] = Msg::ERR_ALREALDYCART;   
        }
    }//if($ordine->getNumError() == 0){
    else{
        http_response_code(400);
        $response['msg'] = $order->getStrError();
        //$response['msg'] .= ' Linea n. '.__LINE__;
    } 
}

/**
 * Delete the order from cart
 */
function delOrderFromCart(array &$response, array $oData, Utente $user){
    $order = new Ordine($oData);
    if($order->getNumError() == 0){
        $okDel = $order->delFromCart($user->getUsername());
        if($okDel){
            $response['msg'] = Msg::ORDERDELETEDCART;
            $response['del'] = '1';
            $response['done'] = true;
        }
        else{
            http_response_code(400);
            $response['msg'] = $order->getStrError();
            //$response['msg'] .= ' Linea n. '.__LINE__;
        }
    }//if($order->getNumError() == 0){
    else{ 
        http_response_code(400);
        $response['msg'] = $order->getStrError();
        //$risposta['msg'] .= ' Linea n. '.__LINE__;
    }
}

/**
 * Print HTML if this is non AJAX request
 */
function printHtml(array $response): string {
    $html = <<<HTML
<!DOCTYPE html>
<html lang="it">
    <head>
        <title>Carrello</title>
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
            {$response['msg']}
         </div>
    </body>
</html>
HTML;
    return $html;
}

/**
 * Show all items in the cart
 */
function showCart(array &$response, Utente $user){
    //Orders id list added to cart
    $ordersCart = Carrello::getCartIdos($user->getUsername());
    $response['done'] = true;
    $response['n_orders'] = 0;
    if(Carrello::nProdotti() > 0){
        $response['vuoto'] = '0';
        foreach ($ordersCart as $idv => $ordersArr){
            foreach($ordersArr as $idp) {
                try{
                    $order = new Ordine(array('id' => $idp));
                    if($order->getNumError() == 0){
                        $product = new Prodotto(array('id' => $order->getIdp()));
                        if($product->getNumError() == 0){
                            addItemToCart($response,$product,$order);
                            $response['n_orders']++;
                        }//if($product->getNumError() == 0){
                        else{
                            $response['msg'] = $product->getStrError();
                            //$response['msg'] .= ' Linea n. '.__LINE__;
                            break;
                        }
                    }//if($order->getNumError() == 0){
                    else{
                        $risposta['msg'] = $order->getStrError();
                        //$risposta['msg'] .= ' Linea n. '.__LINE__;
                        break;
                    } 
                }catch(Exception $e){
                    $response['msg'] = $e->getMessage();
                    //$response['msg'] .= ' Linea n. '.__LINE__;
                }
            }//foreach($ordersArr as $idp) {
        }//foreach ($ordersCart as $idv=>$arrayOrd){
    }//if(Carrello::nProdotti() > 0){
    else
        $response['vuoto'] = '1';
}
?>