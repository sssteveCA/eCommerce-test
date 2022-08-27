<?php
session_start();
require_once('../config.php');
require_once('../interfaces/messages.php');
require_once('../interfaces/orderErrors.php');
require_once('../interfaces/productErrors.php');
require_once('../interfaces/productsVals.php');
require_once('../interfaces/userErrors.php');
require_once('../interfaces/mysqlVals.php');
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
$post = json_decode($input);

$response = [
    'sbn_code' => SBN_CODE
];

$ajax = (isset($post['ajax']) && $post['ajax'] == '1');

//Add one item to cart array response
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

//Show all the items in the cart
function showCart(Utente $user){
    //Orders id list added to cart
    $ordersCart = Carrello::getCartIdos($user->getUsername());
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