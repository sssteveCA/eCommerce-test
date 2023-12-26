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

function unserializeProduct($p) {
    $prodotto = unserialize($p);
    $prodotto->connesso=false;
    return $prodotto;
}

?>