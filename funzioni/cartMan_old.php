<?php
session_start();
require_once('../config.php');
require_once('../interfaces/messages.php');
require_once('../interfaces/orderErrors.php');
require_once('../interfaces/productErrors.php');
require_once('../interfaces/productsVals.php');
require_once('../interfaces/userErrors.php');
//require_once('../interfaces/mysqlVals.php');
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

$ajax =  (isset($_POST['ajax']) && $_POST['ajax'] == '1');

if(isset($_SESSION['logged'],$_SESSION['utente'],$_SESSION['welcome']) && $_SESSION['welcome'] != '' && $_SESSION['logged'] === true){
    $utente = unserialize($_SESSION['utente']);
    $risposta = array();
    $risposta['sbn_code'] = SBN_CODE;
    $oDati = array();
    if(isset($_POST['oper'])){
        //mostra gli elementi nel carrello
        if($_POST['oper'] == '1'){
            //array che contiene le informazioni sugli ordini aggiunti al carrello
            $oArray = array();
            $idUtente = $utente->getId();
            //id degli ordini aggiunti al carrello
            $ordiniCarr = Carrello::getCartIdos($utente->getUsername());
            if(Carrello::nProdotti() > 0){
                $risposta['vuoto'] = '0';
                foreach ($ordiniCarr as $idv=>$arrayOrd){
                    foreach($arrayOrd as $idp) {
                        try{
                            $ordine = new Ordine(array('id' => $idp));
                            if($ordine->getNumError() == 0){
                                $prodotto = new Prodotto(array('id' => $ordine->getIdp()));
                                if($prodotto->getNumError() == 0){
                                    $oArray['ido'] = $ordine->getId();
                                    $oArray['idp'] = $prodotto->getId();
                                    $idv = $ordine->getIdv();
                                    try{
                                        $aV = array();
                                        $aV['id'] = $idv;
                                        $aV['registrato'] = '1';
                                        $venditore = new Utente($aV);
                                        if($venditore->getNumError() == 0 || $venditore->getNumError() == Ue::INCORRECTLOGINDATA){
                                            $oArray['clientId'] = $venditore->getClientId();
                                        }
                                        else{
                                            $risposta['msg'] = $venditore->getStrError();
                                            $risposta['msg'] .= ' Linea n. '.__LINE__;
                                            break;
                                        }
                                    }
                                    catch(Exception $e){
                                        $risposta['msg'] = $e->getMessage();
                                        $risposta['msg'] .= ' Linea n. '.__LINE__;
                                    } 
                                    $oArray['idv'] = $venditore->getId();
                                    $oArray['nome'] = $prodotto->getNome();
                                    $oArray['immagine'] = $prodotto->getImmagine();
                                    $oArray['tipo'] = $prodotto->getTipo();
                                    $oArray['prezzo'] = $prodotto->getPrezzo();
                                    $oArray['quantita'] = $ordine->getQuantita();
                                    $oArray['spedizione'] = $prodotto->getSpedizione();
                                    $oArray['totale'] = $ordine->getTotale();
                                    $risposta['carrello'][$idv][] = $oArray;
                                }
                                else{
                                    $risposta['msg'] = $prodotto->getStrError();
                                    $risposta['msg'] .= ' Linea n. '.__LINE__;
                                    break;
                                }
                            }
                            else{
                                $risposta['msg'] = $ordine->getStrError();
                                $risposta['msg'] .= ' Linea n. '.__LINE__;
                                break;
                            } 
                        }
                        catch(Exception $e){
                            $risposta['msg'] = $e->getMessage();
                            $risposta['msg'] .= ' Linea n. '.__LINE__;
                        }
                    }
                }
            }
            else{
                $risposta['vuoto'] = '1';
            }
        }
        //aggiunge un ordine al carrello
        else if($_POST['oper'] == '2'){
            if(isset($_POST['ido'],$_POST['idp']) && is_numeric($_POST['ido']) && is_numeric($_POST['idp'])){
                $oDati['id'] = $_POST['ido'];
                //id del prodotto che si vorrebbe aggiungere al carrello
                $idp = $_POST['idp'];
                try{
                    $ordine = new Ordine($oDati);
                    if($ordine->getNumError() == 0){
                        //id degli ordini presenti nel carrello dell'utente
                        $ordiniCarr = Carrello::getCartIdos($utente->getUsername());
                        $presente = false; //true = il prodotto che si vuole aggiungere è già nel carrello
                        foreach($ordiniCarr as $venditore){
                            foreach($venditore as $ido){
                                $oCarr = new Ordine(array('id' => $ido));
                                //se il prodotto è già presente nel carrello
                                if($idp == $oCarr->getIdp()){
                                    $presente = true;
                                    break;
                                }   
                            }
                            if($presente)break;
                        }
                        if(!$presente){
                            $okAdd = $ordine->addToCart($utente->getUsername());
                            if($okAdd){
                                $risposta['msg'] = Msg::ORDERADDEDCART;
                            }
                            else{
                                $risposta['msg'] = $ordine->getStrError().'<br>';
                                $risposta['msg'] .= ' Linea n. '.__LINE__;
                            }
                        }
                        else{
                            $risposta['msg'] = Msg::ERR_ALREALDYCART;   
                        }
                    }
                    else{
                        $risposta['msg'] = $ordine->getStrError().'<br>';
                        $risposta['msg'] .= ' Linea n. '.__LINE__;
                    } 
                }
                catch(Exception $e){
                    $risposta['msg'] = $e->getMessage().'<br>';
                    $risposta['msg'] .= ' Linea n. '.__LINE__;
                }
            }
            else $risposta['msg'] = Msg::ERR_ORDERINVALIDDATA;
        }
        //elimino un ordine del carrello
        else if($_POST['oper'] == '3'){
            if(isset($_POST['ido']) && is_numeric($_POST['ido'])){
                $oDati['id'] = $_POST['ido'];
                try{
                    $ordine = new Ordine($oDati);
                    if($ordine->getNumError() == 0){
                        $okDel = $ordine->delFromCart($utente->getUsername());
                        if($okDel){
                            $risposta['msg'] = Msg::ORDERDELETEDCART;
                            $risposta['del'] = '1';
                        }
                        else{
                            $risposta['msg'] = $ordine->getStrError().'<br>';
                            $risposta['msg'] .= ' Linea n. '.__LINE__;
                        }
                    }
                    else{ 
                        $risposta['msg'] = $ordine->getStrError().'<br>';
                        $risposta['msg'] .= ' Linea n. '.__LINE__;
                    }
                }
                catch(Exception $e){
                    $risposta['msg'] = $e->getMessage().'<br>';
                    $risposta['msg'] .= ' Linea n. '.__LINE__;
                }
            }
            else $risposta['msg'] = Msg::ERR_ORDERDELETEINVALIDID;
            
        }
        else{
            $risposta['msg'] = Msg::ERR_INVALIDOPERATION1;
        }
    }

}
if($ajax)echo json_encode($risposta);
else{
?>
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
            <?php echo $risposta['msg']; ?>
         </div>
    </body>
<?php
}
?>
