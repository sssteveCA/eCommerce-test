<?php

use EcommerceTest\Interfaces\MySqlVals as Mv;
use EcommerceTest\Interfaces\Messages as Msg;
use EcommerceTest\Config as Cf;

ob_start();

require_once('config.php');
require_once('../config.php');
require_once('../interfaces/messages.php');
require_once('../interfaces/mysqlVals.php');

$regex = '/^[a-z0-9]{1,64}$/i';
$time = time();
if(isset($_REQUEST['password']) && preg_match($regex,$_REQUEST['password'])){
    if($_REQUEST['password'] == $passwordPulizia){
        //apro la connessione al server MySQL
        $h = new mysqli(Cf::MYSQL_HOSTNAME,Cf::MYSQL_USERNAME,Cf::MYSQL_PASSWORD,Cf::MYSQL_DATABASE);
        //errore
        if($h->connect_errno){
            $mess = Msg::ERR_MYSQLCONN;
        }
        else{
            $h->set_charset("utf8mb4");
            $timeT = $time-$attesa;
            $query = <<<SQL
UPDATE `accounts` SET `dataCambioPwd` = NULL, `cambioPwd` = NULL
WHERE `dataCambioPwd` < '{$timeT}';
SQL;
            if($h->query($query) !== FALSE){
                $mess = $h->affected_rows.' righe pulite';
            }
            else{
                $mess = Msg::ERR_MYSQLQUERY;
            }
            $h->close();
        }//else di if($h->connect_errno){
    }//if($_REQUEST['password'] == $passwordPulizia){
    else{
        $mess = Msg::ERR_PWDWRONG;
    }
}//if(isset($_REQUEST['password']) && preg_match($regex,$_REQUEST['password'])){
else{
    $mess = Msg::ERR_CODEINVALD;
}
$f=fopen('log.txt', 'a');
fwrite($f, date('r', $time).': '.$mess."\r\n");
fclose($f);
?>