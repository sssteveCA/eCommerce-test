<?php

use Dotenv\Dotenv;
use EcommerceTest\Objects\Ordine;
use EcommerceTest\Objects\Carrello;
use EcommerceTest\Objects\Utente;
use EcommerceTest\Interfaces\UserErrors as Ue;

session_start();
require_once('config.php');
//require_once('interfaces/mysqlVals.php');
require_once('interfaces/userErrors.php');
require_once('interfaces/orderErrors.php');
require_once('interfaces/emailmanagerErrors.php');
require_once('exceptions/notsetted.php');
require_once('vendor/autoload.php');
require_once('traits/error.php');
require_once('traits/emailmanager.trait.php');
require_once('traits/sql.trait.php');
require_once('objects/emailmanager.php');
require_once('objects/utente.php');
require_once('objects/carrello.php');
require_once('objects/ordine.php');
require_once("funzioni/const.php");
@include_once('partials/privacy.php');

$ajax = (isset($_POST['ajax']) && $_POST['ajax'] == '1');
$risposta = array();
$risposta['msg'] = '';
$risposta['done'] = false;
$successo = false;

if(isset($_SESSION['logged'],$_SESSION['utente'],$_SESSION['welcome']) && $_SESSION['welcome'] != '' && $_SESSION['logged'] === true){
    $dotenv = Dotenv::createImmutable(__DIR__);
    $dotenv->safeLoad();
    $utente = unserialize($_SESSION['utente']);
    $idc = $utente->getId();
    if(!$ajax){
?>
<!DOCTYPE html>
<html lang="it">
    <head>
        <title>Pagamento ordini carrello</title>
        <meta charset="utf-8">
        <?php 
            if(file_exists('partials/privacy.php') && is_file('partials/privacy.php')){
                echo call_user_func('cookieBanner');
            }
         ?>
    </head>
    <body>
<?php
    }
    //pagamento completato con successo
    if(isset($_POST['payer_status']) && $_POST["payer_status"] == 'VERIFIED'){
        //credenziali del venditore e id transazione 
        if(isset($_POST['clientId'],$_POST['txn_id'])){
            $clientId = $_POST['clientId'];
            $txn_id = $_POST['txn_id'];
            //informazioni da passare all'oggetto utente venditore
            $aV = array();
            $aV['registrato'] = '1';
            /*le informazioni sul venditore saranno ottenute verificando il clientId */
            $aV['campo'] = 'clientId';
            $aV['clientId'] = $clientId;
            try{
                $venditore = new Utente($aV);
                if($venditore->getNumError() == 0 || $venditore->getNumError() == Ue::INCORRECTLOGINDATA){
                    //$risposta['idv'] = $venditore->getId();
                    $idVend = $venditore->getId();
                    $ordiniCarr = Carrello::getCartIdos($utente->getUsername());
                    $successo = true; //tutte le operazioni sono andate a buon fine
                    foreach($ordiniCarr as $idv => $arrayOrd){
                        /*verranno modificati solo gli ordini dei prodotti di un venditore */
                        if($idv == $idVend){
                            foreach($arrayOrd as $idOrdine){
                                try{
                                    $ordine = new Ordine(array('id' => $idOrdine));
                                    if($ordine->getNumError() == 0){
                                        $del = $ordine->delFromCart($utente->getUsername());
                                        //se l'ordine esaminato è stato cancellato dal carrello
                                        if($del){
                                            $pagato = array();
                                            $pagato['pagato'] = '1';
                                            $pagato['tnx_id'] = $txn_id;
                                            //l'ordine è stato pagato
                                            $setPagato = $ordine->update($pagato);
                                            if($setPagato){
                                                //informazioni sull'ordine aggiornate
                                            }
                                            else{
                                                $successo = false;
                                                $risposta['msg'] = $ordine->getStrError().'<br>';
                                                $risposta['msg'] .= ' Linea n. '.__LINE__;
                                            }
                                        }
                                        //se l'ordine esaminato non è stato cancellato dal carrello
                                        else{
                                            $successo = false;
                                            $risposta['msg'] = $ordine->getStrError().'<br>';
                                            $risposta['msg'] .= ' Linea n. '.__LINE__;
                                        }
                                    }
                                    else{
                                        $risposta['msg'] = $ordine->getStrError().'<br>';
                                        $risposta['msg'] .= ' Linea n. '.__LINE__;
                                        break;
                                    }
                                }
                                catch(Exception $e){
                                    $risposta['msg'] = $e->getMessage().'<br>';
                                    $risposta['msg'] .= ' Linea n. '.__LINE__;
                                }
                            }
                        } //fine operazioni sugli ordini dei prodotti del venditore con id 'idv'
                    } //fine foreach
                } //errore non fatale nell'istanza venditore
                else{
                    $risposta['msg'] = $venditore->getStrError().'<br>';
                    $risposta['msg'] .= ' Linea n. '.__LINE__;
                }
            } // try di new Utente venditore
            catch(Exception $e){
                $risposta['msg'] = $e->getMessage().'<br>';
                $risposta['msg'] .= ' Linea n. '.__LINE__;
            }

        }//if(isset($_POST['clientId'],$_POST['txn_id']))
        else{
            $risposta['msg'] = 'Uno o più dati richiesti non esistono';
        }
    }
    if($successo){
        $risposta['done'] = true;
        $risposta['msg'] = 'Pagamento ordini nel carrello completato con successo';
    }
}
else{
    if(!$ajax)$risposta['msg'] = ACCEDI1;
    else $risposta['msg'] = 'Sei stato disconnesso';
}
if(!$ajax){
    echo $risposta['msg'].'<br>';
     echo '<pre>';
    echo 'GET';
    var_dump($_GET);
    echo 'POST';
    var_dump($_POST);
    echo '</pre>';
?>
    </body>
</html>
<?php
}
else{
    echo json_encode($risposta);
}