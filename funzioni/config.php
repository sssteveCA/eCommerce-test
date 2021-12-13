<?php
$attesa = 3600;
$accountsTable = 'accounts';
$prodottiTable = 'prodotti';
$ordiniTable = 'ordini';
$passwordPulizia='123456';
if($_SERVER['SERVER_NAME'] == 'localhost'){
    $mysqlHost = 'localhost';
    $mysqlUser = 'root';
    $mysqlPass = '';
    $mysqlDb = 'stefano';
}
else{
    $mysqlHost = '';
    $mysqlUser = '';
    $mysqlPass = '';
    $mysqlDb = '';
}

//paypal


/*//apro la connessione al server MySQL
$h = new mysqli($mysqlHost,$mysqlUser,$mysqlPass,$mysqlDb);
//errore
if($h->connect_errno){
    //echo 'Connessione a MySQL fallita: '.$h->connect_error;
    $result['errore'] = 'Connessione a MySQL fallita: '.$h->connect_error;
} 
$h->set_charset("utf8mb4");*/

function unserializeProduct($p) {
    $prodotto = unserialize($p);
    $prodotto->connesso=false;
    return $prodotto;
}

?>