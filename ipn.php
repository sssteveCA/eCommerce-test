<?php
//https://www.codexworld.com/paypal-standard-payment-gateway-integration-php/

use Dotenv\Dotenv;

session_start();
require_once('interfaces/emailmanagerErrors.php');
require_once('exceptions/notsetted.php');
require_once('vendor/autoload.php');
require_once('traits/error.php');
require_once('traits/emailmanager.trait.php');
require_once('traits/ordine.trait.php');
require_once('traits/prodotto.trait.php');
require_once('objects/emailmanager.php');
require_once('objects/utente.php');
require_once('objects/prodotto.php');
require_once('objects/ordine.php');
require_once('funzioni/config.php');

if(isset($_SESSION['logged'],$_SESSION['utente'],$_SESSION['welcome']) && $_SESSION['welcome'] != '' && $_SESSION['logged'] === true){
    //se esiste l'id univoco dell'ordine
    if(isset($_SESSION['ido']) && is_numeric($_SESSION['ido'])){
        $file = fopen('logIpn.txt','a');
/* 
 * Read POST data 
 * reading posted data directly from $_POST causes serialization 
 * issues with array data in POST. 
 * Reading raw POST data from input stream instead. 
 */ 
        $raw_post_data = file_get_contents('php://input');
        $raw_post_array = explode('&',$raw_post_data);
        $myPost = array();
        foreach($raw_post_array as $keyval){
            $keyval = explode('=',$keyval);
            if(count($keyval) == 2){
                $myPost[$keyval[0]] = urldecode($keyval[1]);
            }
        }
        // intercetta le variabili IPN inviate da PayPal
        $req = 'cmd=_notify-validate';
        /*if(function_exists('get_magic_quotes_gpc')){
            $get_magic_quotes_exists = true;
        }*/
        // legge l'intero contenuto dell'array POST
        foreach($myPost as $key => $value){
            $value = urlencode(stripslashes($value));
            $req .= "&$key=$value";
        }
        fwrite($file,"$res\n");

        $ch = curl_init($paypalPage);
        if($ch != FALSE){
            curl_setopt($ch,CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1); //HTTP/1.1
            curl_setopt($ch,CURLOPT_POST,1);
            //restituisce una stringa come risultato invece di mostralo direttamente su schermo
            curl_setopt($ch,CURLOPT_RETURNTRANSFER,1); 
            curl_setopt($ch,CURLOPT_POSTFIELDS,$req);
            curl_setopt($ch,CURLOPT_SSLVERSION,6); //CURL_SSLVERSION_TLSv1_2
            //verifica i certificati del server
            curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,1);
            curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,2);
            //chiude la connessione dopo un solo utilizzo
            curl_setopt($ch,CURLOPT_FORBID_REUSE,1);
            curl_setopt($ch,CURLOPT_CONNECTTIMEOUT, 60);
            curl_setopt($ch,CURLOPT_HTTPHEADER, array('Connection: Close','User-Agent: company-name'));
            $res = curl_exec($ch);
        }

        $tokens = explode("\r\n\r\n",trim($res));
        $res = trim(end($tokens));
        fwrite($file,"$res\n");
        if(strcasecmp($res, "VERIFIED") == 0){
            $dotenv = Dotenv::createImmutable(__DIR__);
            $dotenv->safeLoad();
            $item_number = $_POST['item_number']; 
            $txn_id = $_POST['txn_id']; 
            $payment_gross = $_POST['mc_gross']; 
            $currency_code = $_POST['mc_currency']; 
            $payment_status = $_POST['payment_status']; 
            $mysqli = new mysqli($_ENV['MYSQL_HOSTNAME'],$_ENV['MYSQL_USERNAME'],$_ENV['MYSQL_PASSWORD'],$_ENV['MYSQL_PASSWORD']);
            if($mysqli->connect_errno == 0){
                $mysqli->set_charset("utf8mb4");
                $ido = $_SESSION['ido'];
                unset($_SESSION['ido']);
                $query = <<<SQL
UPDATE `$ordiniTable` SET `pagato` = '1',`tnx_id` = '$tnx_id' WHERE `id` = '$ido' AND `pagato` = '0';
SQL;
                if($mysqli->query($query) !== FALSE){
                    echo 'Pagamento effettuato con successo<br>';
                }
                else{
                    //echo 'Query errata<br>';
                }
                $mysqli->close();
            }
        }
        // azione in caso di risposta negativa da parte di PayPal
        else if(strcasecmp($res,"INVALID") == 0){
            //echo 'Si è verificato un errore durante il pagamento<br>';
        }
        fclose($file);
    }
}

?>