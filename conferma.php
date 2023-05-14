<?php

use Dotenv\Dotenv;
use EcommerceTest\Objects\Ordine;
use EcommerceTest\Objects\Prodotto;
use EcommerceTest\Objects\Utente;
use EcommerceTest\Interfaces\Paths as P;
use EcommerceTest\Objects\Templates\ConfirmTemplates;

session_start();

require_once("vendor/autoload.php");

//se un'utente ha effettuato il login
if(isset($_SESSION['logged'],$_SESSION['utente'],$_SESSION['welcome']) && $_SESSION['welcome'] != '' && $_SESSION['logged'] === true){
    $dotenv = Dotenv::createImmutable(__DIR__);
    $dotenv->safeLoad();
    //se il prodotto è una variabile di sessione oppure esiste l'ID di quel prodotto
    $datiOk = false;
    //se i dati del form della pagina compra.php esistono
    if(isset($_POST['nP']) && is_numeric($_POST['nP'])){
        if(isset($_SESSION['prodotto'])){

            $datiOk = true;
            $prodotto = unserializeProduct($_SESSION['prodotto']);
        }
        //se l'oggetto in sessione non c'è creo il l'oggetto stesso
        else if(!isset($_SESSION['prodotto']) && isset($_POST['idP'])){
            $datiP = array();
            $datiP['id'] = $_POST['idP'];
            try{
                $prodotto = new Prodotto($datiP);
                $datiOk = true;
            }
            catch(Exception $e){
                echo $e->getMessage().'<br>';
                echo ' Linea n. '.__LINE__;
            }
        }
        //se l'oggetto $prodotto esiste e ha tutti i dati richiesti
        if($datiOk){
            try{
                $utente = unserialize($_SESSION['utente']);
                $uBusiness = array();
                $uBusiness['registrato'] = '1';
                //devo passare la password per ottenere i dati dell'oggetto
                //$uBusiness['password'] = '123456';
                $uBusiness['id'] = $prodotto->getIdu(); //ID dell'utente che ha caricato l'annuncio
                $uVenditore = new Utente($uBusiness);
                //var_dump($uVenditore);
                if($uVenditore->getNumError() == 0 || $uVenditore->getNumError() == 1){
                    $pOrdinato = false; //true se esiste già un ordine del cliente dello stesso prodotto
                    //var_dump($prodotto);
                    $idOrdini = Ordine::getIdList($_ENV['MYSQL_HOSTNAME'],$_ENV['MYSQL_USERNAME'],$_ENV['MYSQL_PASSWORD'],$_ENV['MYSQL_DATABASE'],$_ENV['TABORD'],$_ENV['TABACC'],$utente->getUsername());
                    if($idOrdini != null){
                        foreach($idOrdini as $idOrdine){
                            try{
                                $ordine = new Ordine(array('id' => $idOrdine));
                                //se negli ordini effettuati dal cliente il prodotto esiste già
                                if($_POST['idP'] == $ordine->getIdp()){
                                    $idOrd = $idOrdine;
                                    $pOrdinato = true;
                                    $datiU = array();
                                    $datiU['quantita'] = $ordine->getQuantita()+$_POST['nP'];
                                    $tot = sprintf('%.2f',$_POST['tot']);
                                    $datiU['totale'] = $ordine->getTotale()+$tot;
                                    //modifico la quantità dell'ordine e il totale
                                    $update = $ordine->update($datiU);
                                    if(!$update){
                                        echo $ordine->getStrError().'<br>';
                                        echo $ordine->getMysqlError().'<br>';  
                                        echo $ordine->getQuery().'<br>';    
                                    }
                                }
                            }
                            catch(Exception $e){
                                echo $e->getMessage().'<br>';
                                echo ' Linea n. '.__LINE__;
                            }
                            if($pOrdinato)break;
                        }
                    }
                    $dati = array();
                    //se la pagina di provenienza è quella degli ordini
                    if(isset($_POST['ord']) && $_POST['ord'] == '1'){
                        $dati['id'] = $_POST['idO'];
                    }
                    else if($pOrdinato){
                        $dati['id'] = $idOrd;
                    }
                    //se l'ordine deve essere creato
                    else if(!$pOrdinato){
                        $dati['idc'] = $_POST['idC']; //id del cliente
                        $dati['idp'] = $_POST['idP']; //id del prodotto
                        $dati['idv'] = $prodotto->getIdu(); //id del venditore
                        $dati['quantita'] = $_POST['nP']; //quantità acquistata
                        $dati['totale'] = sprintf('%.2f',$_POST['tot']); //prezzo totale dell'ordine                
                    }
                    //url della pagina che l'utente visualizzerà al termine della transazione(indipendentemente dall'esito)
                    $return_url = dirname($_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].$_SERVER['SCRIPT_NAME']).'/success.php';
                    $return_url2 = dirname($_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].$_SERVER['SCRIPT_NAME']).'/success2.php';
                    //url della pagina che sarà visualizzato se l'utente abbana la procedura di pagamento
                    $cancel_url = dirname($_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].$_SERVER['SCRIPT_NAME']).'/cancel.php';
                    $cancel_url2 = dirname($_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].$_SERVER['SCRIPT_NAME']).'/cancel2.php';
                    /*URL del listener IPN(Instant Payment Notification), l'applicazione che riceverà e gestirà
                    le informazioni dal sito di Paypal */
                    $notify_url = dirname($_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].$_SERVER['SCRIPT_NAME']).'/ipn.php';
                    //echo '<script>console.log("'.$return_url.'");</script>';
                    try{
                        //var_dump($dati);
                        $ordine = new Ordine($dati);
                        if($ordine->getNumError() === 0){
                            $dati['idc'] = $ordine->getIdc();
                            $dati['idp'] = $ordine->getIdp();
                            $dati['idv'] = $ordine->getIdv();
                            $dati['quantita'] = $ordine->getQuantita();
                            $dati['totale'] = $ordine->getTotale();
                            //id univoco dell'ordine
                            $_SESSION['ido'] = $ordine->getId();
                            ?>
<!DOCTYPE html>
<html lang="it">
    <head>
        <title>Conferma ordine</title>
        <meta charset="utf-8">
        <link rel="stylesheet" href=<?php echo P::REL_CONFIRM_CSS; ?>>
        <link rel="stylesheet" href=<?php echo P::REL_BOOTSTRAP_CSS; ?>>
        <link rel="stylesheet" href=<?php echo P::REL_JQUERY_CSS; ?> >
        <link rel="stylesheet" href=<?php echo P::REL_JQUERYTHEME_CSS; ?> >
        <link rel="stylesheet" href=<?php echo P::REL_FOOTER_CSS; ?> >
        <script src=<?php echo P::REL_JQUERY_JS; ?>></script>
        <script src=<?php echo P::REL_JQUERYUI_JS; ?>></script>
        <script src=<?php echo P::REL_POPPER_JS; ?>></script>
        <script src=<?php echo P::REL_BOOTSTRAP_JS; ?>></script>
        <script src=<?php echo P::REL_FOOTER_JS; ?>></script>
        <script type="module" src=<?php echo P::REL_DIALOG_MESSAGE_JS; ?>></script>
        <script type="module" src="<?php echo P::REL_LOGOUT_JS; ?>"></script>
        <!-- <script src="js/dialog/dialog.js"></script>  -->
        <script type="module" src=<?php echo P::REL_CONFIRM_JS; ?>></script>
        <?php 
            if(file_exists('partials/privacy.php') && is_file('partials/privacy.php')){
                echo call_user_func('cookieBanner');
            }
         ?>
    </head>
    <body>
        <?php echo menu($_SESSION['welcome']);?>
        <div id="formContainer">
            <fieldset id="f1">
            <legend>Ordine</legend>
            <p>Fai click su 'PAGA' per acquistare il prodotto</p>
            <div id="divButtons">
                <!-- Form per l'accesso alla pagina Paypal apposita -->
                <div class="container-fluid">
                    <div class="row justify-content-center">
                        <div class="col-12 col-sm-4 col-lg-3">
                            <?php
                            $paymentFormData = [
                            'paypalPage' => $paypalPage, 'paypalMail' => $uVenditore->getPaypalMail(),
                            'returnUrl' => $return_url, 'cancelUrl' => $cancel_url, 'notifyUrl' => $notify_url,
                            'rm' => $rm, 'currency' => $currency, 'lc' => $lc, 'shipping' => $prodotto->getSpedizione(),
                            'productName' => addslashes($prodotto->getNome()), 'productId' => $prodotto->getId(),
                            'orderAmout' => $ordine->getTotale()
                        ];
                        echo ConfirmTemplates::paypalForm($paymentFormData);
                            ?>
                        </div>
                        <div class="col-12 col-sm-4 col-lg-3">
                            <?php
                            $addToCartFormData = [
                                'cartAction' => 'funzioni/cartMan.php', 'orderId' => $ordine->getId(), 'productId' => $prodotto->getId()
                            ];
                            echo ConfirmTemplates::addToCartForm($addToCartFormData);
                            ?>
                        </div>
                        <div class="col-12 col-sm-4 col-lg-3">
                            <?php
                            $goBackFormData = [
                                'backAction' => 'compra.php', 'idp' => $dati['idp'], 'qt' => $dati['quantita']
                            ];
                            echo ConfirmTemplates::goBackForm($goBackFormData);
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            </fieldset>
<?php
            //se l'acquirente e il venditore hanno un app Paypal collegata
            if($utente->getClientId() != null && $uVenditore->getClientId() != null){

?>
            <div id="paypalArea"></div>
            <div id="confirm" style="display:none;">
                <button id="confirmButton">Conferma</button>
            </div>
        </div>
        <script src="//www.paypalobjects.com/api/checkout.js"></script>
        <!-- PayPal In-Context Checkout script -->
        <script type="module">
            import {paypalButton} from './js/confirm/confirm.functions.js';
            var clientId = '<?php echo $uVenditore->getClientId(); ?>';
            var sbn_code = '<?php echo(SBN_CODE)?>';
            console.log("clientId = "+clientId);
            var client = {
                sandbox:  clientId
            };
            var environment = 'sandbox';
            /*var transaction = {
                transactions: [
                    {
                        amount: {
                            total:    '15.00',
                            currency: 'USD'
                        }
                    }
                ]
            };*/

            paypalButton(paypal,clientId,sbn_code);
        </script>
<?php
            }//if($utente->getClientId() != null && $uVenditore->getClientId() != null){
?>
        <?php echo footer(); ?>
    </body>
</html>
<?php
                        }//if($ordine->getNumError() === 0){
                        else{
                            echo $ordine->getStrError().'<br>';
                            echo $ordine->getMysqlError().'<br>';  
                            echo $ordine->getQuery().'<br>'; 
                            echo ' Linea n. '.__LINE__;
                        }
                    }
                    catch(Exception $e){
                        echo $e->getMessage().'<br>';
                        echo ' Linea n. '.__LINE__;
                    }
                }//if($uVenditore->getNumError() == 0 || $uVenditore->getNumError() == 1){
                else{
                    echo $uVenditore->getStrError().'<br>';
                    echo $uVenditore->getQuery().'<br>';
                    echo ' Linea n. '.__LINE__;
                } 
            }
            catch(Exception $e){
                echo $e->getMessage().'<br>';
                echo ' Linea n. '.__LINE__;
            }
        }//if($datiOk){
        else{
            echo 'Dati mancanti o incompleti<br>';
        }
    }//if(isset($_POST['nP']) && is_numeric($_POST['nP'])){
    else echo 'Errore nell \'inserimento dei dati<br>';   
}
else{
    echo ACCEDI1;
}
?>
