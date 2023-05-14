<?php

use Dotenv\Dotenv;
use EcommerceTest\Objects\Ordine;
use EcommerceTest\Objects\Carrello;
use EcommerceTest\Objects\Utente;
use EcommerceTest\Interfaces\UserErrors as Ue;
use EcommerceTest\Interfaces\Constants as C;

session_start();

require_once("vendor/autoload.php");

$ajax = (isset($_POST[C::KEY_AJAX]) && $_POST[C::KEY_AJAX] == '1');
$response = [
    C::KEY_DONE => false, C::KEY_MESSAGE => ''
];
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
                    //$response['idv'] = $venditore->getId();
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
                                                $response[C::KEY_MESSAGE] = $ordine->getStrError().'<br>';
                                                $response[C::KEY_MESSAGE] .= ' Linea n. '.__LINE__;
                                            }
                                        }
                                        //se l'ordine esaminato non è stato cancellato dal carrello
                                        else{
                                            $successo = false;
                                            $response[C::KEY_MESSAGE] = $ordine->getStrError().'<br>';
                                            $response[C::KEY_MESSAGE] .= ' Linea n. '.__LINE__;
                                        }
                                    }
                                    else{
                                        $response[C::KEY_MESSAGE] = $ordine->getStrError().'<br>';
                                        $response[C::KEY_MESSAGE] .= ' Linea n. '.__LINE__;
                                        break;
                                    }
                                }
                                catch(Exception $e){
                                    $response[C::KEY_MESSAGE] = $e->getMessage().'<br>';
                                    $response[C::KEY_MESSAGE] .= ' Linea n. '.__LINE__;
                                }
                            }
                        } //fine operazioni sugli ordini dei prodotti del venditore con id 'idv'
                    } //fine foreach
                } //errore non fatale nell'istanza venditore
                else{
                    $response[C::KEY_MESSAGE] = $venditore->getStrError().'<br>';
                    $response[C::KEY_MESSAGE] .= ' Linea n. '.__LINE__;
                }
            } // try di new Utente venditore
            catch(Exception $e){
                $response[C::KEY_MESSAGE] = $e->getMessage().'<br>';
                $response[C::KEY_MESSAGE] .= ' Linea n. '.__LINE__;
            }

        }//if(isset($_POST['clientId'],$_POST['txn_id']))
        else{
            $response[C::KEY_MESSAGE] = 'Uno o più dati richiesti non esistono';
        }
    }
    if($successo){
        $response[C::KEY_DONE] = true;
        $response[C::KEY_MESSAGE] = 'Pagamento ordini nel carrello completato con successo';
    }
}
else{
    if(!$ajax)$response[C::KEY_MESSAGE] = ACCEDI1;
    else $response[C::KEY_MESSAGE] = 'Sei stato disconnesso';
}
if(!$ajax){
    echo $response[C::KEY_MESSAGE].'<br>';
?>
    </body>
</html>
<?php
}
else{
    echo json_encode($response);
}