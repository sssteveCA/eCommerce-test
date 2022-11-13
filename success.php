<?php

use EcommerceTest\Objects\Ordine;

session_start();

require_once('config.php');
require_once('interfaces/orderErrors.php');
require_once('interfaces/userErrors.php');
//require_once('interfaces/mysqlVals.php');
require_once('vendor/autoload.php');
require_once('objects/ordine.php');
require_once('funzioni/config.php');
require_once('objects/utente.php');
require_once("funzioni/const.php");

//file_put_contents("log.txt","success.php => ".var_export($_POST,true)."\r\n",FILE_APPEND);

$ajax = (isset($_POST['ajax']) && $_POST['ajax'] == '1');
$risposta = array();
$msg = '';
//se un'utente ha effettuato il login
if(isset($_SESSION['logged'],$_SESSION['utente'],$_SESSION['welcome']) && $_SESSION['welcome'] != '' && $_SESSION['logged'] === true){
    $utente = unserialize($_SESSION['utente']);
    if(!$ajax){
        ?>
        <!DOCTYPE html>
        <html lang="it">
            <head>
                <title>Pagamento completato</title>
                <meta charset="utf-8">
            </head>
            <body>
        Il prodotto è stato acquistato<br>
        <a href="benvenuto.php">Torna alla pagina principale</a>
        <?php
        }
        /*echo '<pre>';
        echo 'post<br>';
        var_dump($_POST);
        echo 'get<br>';
        var_dump($_GET);
        echo '</pre>';*/
        
        if($_POST["payer_status"] == 'VERIFIED'){
            if(isset($_SESSION['ido'])){
                $dati = array();
                $dati['id'] = $_SESSION['ido'];
                try{
                    $ordine = new Ordine($dati);
                    //l'ordine era presente nel carrello
                    if($ordine->isCarrello() === true){
                        $del = $ordine->delFromCart($utente->getUsername());
                        if($del){
                            $valori = array();
                            $valori['tnx_id'] = $_POST['txn_id'];
                            $valori['pagato'] = '1';
                            $ordine->update($valori);
                            if($ordine->getNumError() == 0){
                                $risposta['msg'] = 'Pagamento effettuato con successo';
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
                    }//if($ordine->isCarrello() === true){
                    else{
                        $risposta['msg'] = 'Aggiungi al carrello il prodotto e riprova';
                    }
                }
                catch(Exception $e){
                    $risposta['msg'] = $e->getMessage().'<br>';
                    $risposta['msg'] .= ' Linea n. '.__LINE__;
                }
            }
            else{
                $risposta['msg'] = 'Id ordine inesistente';
            } 
        }//if($_POST["payer_status"] == 'VERIFIED'){
}
else{
    if(!$ajax)$risposta['msg'] = ACCEDI1;
    else $risposta['msg'] = 'ERRORE: l\' utente è stato disconnesso';
}


if(!$ajax){
    echo $risposta['msg'] .'<br>';
?>
    </body>
</html>
<?php
}
else{
    echo json_encode($risposta);
}
//file_put_contents("log.txt","success.php risposta => ".var_export($risposta,true)."\r\n");
?>