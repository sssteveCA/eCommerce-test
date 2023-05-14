<?php

use EcommerceTest\Config;
//Client Id of REST app
define("CLIENT_ID", "AQ-FlbKtljViykaUcwcbrsuQRfCgeKShBGzknTdE9zwD9uOudjUEypHdjA72kDpRxIMoyO0i7bBRQajw");

//ButtonSource Tracker Code
define("SBN_CODE","PP-DemoPortal-PPCredit-JSV4-php-REST");

$ajax =  (isset($_POST['ajax']) && $_POST['ajax'] == '1');

//email dell'acquirente
//$emailPersonal = 'sb-y7etr5055821@personal.example.com';
//email del venditore
//$emailBusiness = 'sb-svkih5058080@business.example.com';
//pagina di Paypal a cui verranno inviati i dati del form
$paypalPage = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
//$shopping_url = Config::HOME_URL.'/ricerca.php?ricerca=';
/*metodo di ritorno, ovvero come i dati verranno inviati da paypal alla pagina di ritorno scelta
0 = metodo GET per tutte le transazioni
1 = il metodo GET viene usato per il redirect del browser verso la pagina di ritornom senza inviare i dati della transazione
2 = viene usato il metodo POST per il redirect con invio dei dati della transazione */
$rm = '2';
//valuta utilizzata ('USD' per i dollari)
$currency = 'EUR';
//lingua usata dal ompratura per l'acquisto
$lc = 'IT';
//codice o ID personalizzato da associare al prodotto
//$custom = 'ABR24';
//Stato di provenienza del compratore
$state = 'Italia';
/*indica come il pulsante d'invio del form interagisce con il sistema di pagamento
_xclick = "Buy Now Button"
_cart = aggiungi o visualizza i prodotti al tuo carrello
_donations = donazioni */
$cmd = '_xclick';
//spese di spedizione
$shipping = '10.00';

?>