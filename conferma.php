<?php

use EcommerceTest\Objects\Ordine;
use EcommerceTest\Objects\Prodotto;
use EcommerceTest\Objects\Utente;

session_start();

require_once('navbar.php');
require_once('interfaces/mysqlVals.php');
require_once('interfaces/orderErrors.php');
require_once('interfaces/productErrors.php');
require_once('interfaces/productsVals.php');
require_once('interfaces/userErrors.php');
require_once('funzioni/functions.php');
require_once('objects/utente.php');
require_once('objects/prodotto.php');
require_once('objects/ordine.php');
require_once('funzioni/config.php');
require_once('funzioni/paypalConfig.php');
require_once("funzioni/const.php");

//se un'utente ha effettuato il login
if(isset($_SESSION['logged'],$_SESSION['utente'],$_SESSION['welcome']) && $_SESSION['welcome'] != '' && $_SESSION['logged'] === true){
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
                    $idOrdini = Ordine::getIdList($mysqlHost,$mysqlUser,$mysqlPass,$mysqlDb,$ordiniTable,$accountsTable,$utente->getUsername());
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
        <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
        <!-- <link rel="stylesheet" href="css/utente.css"> -->
        <link rel="stylesheet" href="css/conferma.css">
        <link rel="stylesheet" href="jqueryUI/jquery-ui.min.css">
        <link rel="stylesheet" href="jqueryUI/jquery-ui.theme.min.css">
        <script src="js/jquery-3.5.1.min.js"></script>
        <script src="jqueryUI/jquery-ui.min.js"></script>
        <script src="bootstrap/js/bootstrap.min.js"></script>
        <script src="js/dialog/dialog.js"></script>
        <script src="js/conferma.js"></script>
        <script src="js/logout.js"></script>
    </head>
    <body>
        <?php echo menu($_SESSION['welcome']);?>
        <fieldset id="f1">
            <legend>Ordine</legend>
            <p>Fai click su 'PAGA' per acquistare il prodotto</p>
            <div id="divButtons">
                <!-- Form per l'accesso alla pagina Paypal apposita -->
                <form id="paga" method="post" action="<?php echo $paypalPage; ?>">
                    <input type="hidden" name="business" value="<?php echo $uVenditore->getPaypalMail(); ?>">
                    <input type="hidden" name="cmd" value="_xclick">

                    <!-- informazioni sulla transazione -->
                    <input type="hidden" id="return" name="return" value="<?php echo $return_url; ?>">
                    <input type="hidden" id="cancel_return" name="cancel_return" value="<?php echo $cancel_url; ?>">
                    <input type="hidden" id="notify_url" name="notify_url" value="<?php echo $notify_url; ?>">
                    <input type="hidden" id="rm" name="rm" value="<?php echo $rm; ?>">
                    <input type="hidden" id="currency" name="currency_code" value="<?php echo $currency; ?>">
                    <input type="hidden" id="lc" name="lc" value="<?php echo $lc; ?>">
                    <input type="hidden" id="cbt" name="cbt" value="Continua">

                    <!-- informazioni sul pagamento -->
                    <input type="hidden" id="shipping" name="shipping" value="<?php echo $prodotto->getSpedizione(); ?>">
                    <!-- colore di sfondo della pagina di pagamento: 0 = bianco, 1 = nero -->
                    <input type="hidden" id="cs" name="cs" value="1">

                    <!-- informazioni sul prodotto -->
                    <input type="hidden" id="item_name" name="item_name" value="<?php echo addslashes($prodotto->getNome()); ?>"> 
                     <input type="hidden" id="item_number" name="item_number" value="<?php echo $prodotto->getId(); ?>"> 
                    <input type="hidden" id="amount" name="amount" value="<?php echo printf("%.2f",$ordine->getTotale()); ?>">
                    <!-- <input type="hidden" name="quantity" value="<?php //$ordine->getQuantita(); ?>"> -->

                    <!-- informazioni sulla vendita -->
                    <input type="hidden" id="custom" name="custom" value="<?php echo $prodotto->getId(); ?>">

                    <!-- informazioni sull'acquirente -->
                    <!-- <input type="hidden" name="first_name" value="<?php //echo $utente->getNome(); ?>">
                    <input type="hidden" name="last_name" value="<?php //echo $utente->getCognome(); ?>">
                    <input type="hidden" name="state" value="<?php //echo 'Italia' ?>">
                    <input type="hidden" name="city" value="<?php //echo $utente->getCitta(); ?>">
                    <input type="hidden" name="address1" value="<?php //echo $utente->getIndirizzo().','.$utente->getNumero(); ?>">
                    <input type="hidden" name="zip" value="<?php //echo $utente->getCap(); ?>"> -->
                    <!-- <input type="hidden" name="email" value="<?php //echo $utente->getEmail(); ?>"> -->
                    <!-- <input type="hidden" name="email" value="<?php //echo $emailPersonal; ?>"> -->

                    <input type="submit" id="bOk" value="PAGA">
                </form>
                <form id="cart" method="post" action="funzioni/cartMan.php">
                    <!-- oper = 1, aggiunge il prodotto al carrello -->
                    <input type="hidden" id="oper" name="oper" value="2">
                    <!-- ID dell'ordine -->
                    <input type="hidden" id="ido" name="ido" value="<?php echo $ordine->getId(); ?>">
                    <input type="hidden" id="idp" name="idp" value="<?php echo $prodotto->getId(); ?>">
                    <input type="submit" id="bCart" value="AGGIUNGI AL CARRELLO">
                </form>
                <form id="back" method="post" action="compra.php">
                    <input type="hidden" id="idp" name="idp" value="<?php echo $dati['idp']; ?>">
                    <input type="hidden" id="qt" name="qt" value="<?php echo $dati['quantita']; ?>">
                    <input type="submit" id="bInd" value="INDIETRO">
                </form>
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
        <script src="//www.paypalobjects.com/api/checkout.js"></script>
    <!-- PayPal In-Context Checkout script -->
    <script type="text/javascript">
        var clientId = '<?php echo $uVenditore->getClientId(); ?>';
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


       function handleResponse(result) {

            // var resultDOM = document.getElementById('paypal-execute-details').textContent;
            // document.getElementById('paypal-execute-details').textContent = JSON.stringify(result, null, 2);

            var resultDOM = JSON.stringify(result, null, 2);
            console.log(resultDOM);
            //var parseDOM = JSON.parse(resultDOM);
            //console.log(parseDOM);

            //$json_response = result;
            // console.log(result['id']);
            var payID = result['id'];

            var paymentState = result['state'];
            var finalAmount = result['transactions'][0]['amount']['total'];
            var currency = result['transactions'][0]['amount']['currency'];
            var transactionID= result['transactions'][0]['related_resources'][0]['sale']['id'];
            var status = result['payer']['status'];
            var payerFirstName = result['payer']['payer_info']['first_name'];
            var payerLastName = result['payer']['payer_info']['last_name'];
            var recipientName= result['payer']['payer_info']['shipping_address']['recipient_name'],FILTER_SANITIZE_SPECIAL_CHARS;
            var addressLine1= result['payer']['payer_info']['shipping_address']['line1'];
            var addressLine2 = result['payer']['payer_info']['shipping_address']['line2'];
            var city= result['payer']['payer_info']['shipping_address']['city'];
            var state= result['payer']['payer_info']['shipping_address']['state'];
            var postalCode =result['payer']['payer_info']['shipping_address']['postal_code'];
            var transactionType = result['intent'];
            // var countryCode= filter_var(result['payer']['payer_info']['shipping_address']['country_code'],FILTER_SANITIZE_SPECIAL_CHARS);
            /*if(status == 'VERIFIED'){
                message('Pagamento','auto','400px','Il pagamento è stato completato con successo','close');
              
            }
            else{
                message('Pagamento','auto','400px','Errore durante il pagamento','close');
            }
            $('#dialog').on('dialogclose',function(){
                    $('#dialog').remove();
                });*/
            $('#confirm').remove();
            var dati = {};
            dati['ajax'] = '1';
            dati['payer_status'] = status;
            dati['txn_id'] = transactionID;
            $.ajax({
                url : 'success.php',
                method : 'post',
                data : dati,
                success : function(risposta, stato, xhr){
                    console.log(risposta);
                    var risp = JSON.parse(risposta);
                    message('Pagamento','auto','400px',risp['msg'],'close');
                    $('#dialog').on('dialogclose',function(){
                        $('#dialog').remove();
                    });
                },
                error : function(xhr, stato, errore){
                },
                complete : function(xhr, stato){
                }
            });
        }


 paypal.Button.render({

        // Set your environment

        env: 'sandbox', // sandbox | production
        funding: {
              allowed: [ paypal.FUNDING.CREDIT ]
        },

        client: {
            sandbox: clientId
        },
        
        style: {
              label: 'credit',
              size:  'medium', // small | medium | large | responsive
              shape: 'pill',  // pill | rect
        },


        // Wait for the PayPal button to be clicked

        payment: function(actions) {

            var currency = document.getElementById('currency').value;
            var shipping_amt = document.getElementById('shipping').value;
            shipping_amt = parseFloat(shipping_amt).toFixed(2);
            shipping_amt = parseFloat(shipping_amt);

            var subtotal = document.getElementById('amount').value;
            subtotal = parseFloat(subtotal).toFixed(2);
            subtotal = parseFloat(subtotal);

            var total_amt = subtotal + shipping_amt;
            total_amt = parseFloat(total_amt).toFixed(2);
            total_amt = parseFloat(total_amt);
            console.log("currency "+currency);
            console.log("shipping_amt "+shipping_amt);
            console.log("subtotal "+subtotal);
            console.log("total_amt "+total_amt);
            return actions.payment.create({
             meta: {
                 partner_attribution_id: '<?php echo(SBN_CODE)?>'
             },
             payment: {
                 payer: {
                        payment_method: 'paypal',
                        external_selected_funding_instrument_type: 'PAY_UPON_INVOICE'
                    },
                 transactions: [
                     {
                         amount: {
                             total: total_amt ,
                             //total: 1000.12 ,
                             currency: currency,
                             details:
                             {
                                 subtotal: subtotal,
                                 //subtotal: 990.12,
                                 shipping: shipping_amt,
                                 //shipping: 10,
                             }
                         }
                     }
                 ]
             }
            });
        },

        // Wait for the payment to be authorized by the customer

        onAuthorize: function(data, actions) {

     return actions.payment.get().then(function(data) {       

      var currentShippingVal = data.transactions[0].amount.details.shipping;
      currentShippingVal = parseFloat(currentShippingVal).toFixed(2);
      currentShippingVal = parseFloat(currentShippingVal);
      var shipping = data.payer.payer_info.shipping_address;

      var currentTotal = data.transactions[0].amount.total;
      currentTotal = parseFloat(currentTotal).toFixed(2);
      currentTotal = parseFloat(currentTotal);

                console.log(shipping.recipient_name);
                console.log(shipping.line1);
                console.log(shipping.city);
                console.log(shipping.state);
                console.log(shipping.postal_code);
                console.log(shipping.country_code);

                 console.log(currentShippingVal);

                //total_amt =+ total_amt + shipping_amt_updated;

                document.querySelector('#paypalArea').style.display = 'none';
                document.querySelector('#confirm').style.display = 'block';

                // Listen for click on confirm button

                document.querySelector('#confirmButton').addEventListener('click', function() {

                    // Disable the button and show a loading message

                    document.querySelector('#confirm').innerText = 'Loading...';
                    document.querySelector('#confirm').disabled = true;

                    // Execute the payment
                    var currency = document.getElementById('currency').value;
                  var subtotal = currentTotal - currentShippingVal;

                    return actions.payment.execute(
                    {
                     transactions: [
                        {
                            amount: {
                                //total: 1000.12,
                                total: currentTotal,
                                currency: currency,
                                details: 
                                {
                                  subtotal: subtotal,
                                  //subtotal: 990.12,
                                  shipping: currentShippingVal,
                                  //shipping: 10,
                                }
                            }
                        }
                    ]    
                }).then(handleResponse);

              })      

            // return actions.payment.execute().then(handleResponse);
         })   
       } 

    }, '#paypalArea');
     </script>
<?php
                        }
?>
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
