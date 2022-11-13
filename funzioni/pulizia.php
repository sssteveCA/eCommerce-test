<?php

use Dotenv\Dotenv;
//use EcommerceTest\Interfaces\MySqlVals as Mv;
use EcommerceTest\Interfaces\Messages as Msg;
use EcommerceTest\Config as Cf;

ob_start();

require_once('config.php');
require_once('../config.php');
require_once('../interfaces/messages.php');
//require_once('../interfaces/mysqlVals.php');

$regex = '/^[a-z0-9]{1,64}$/i';
$time = time();
if(isset($_REQUEST['password']) && preg_match($regex,$_REQUEST['password'])){
    if($_REQUEST['password'] == $passwordPulizia){
        //apro la connessione al server MySQL
        $dotenv = Dotenv::createImmutable(__DIR__."../");
        $dotenv->safeLoad();
        $h = new mysqli($_ENV['MYSQL_HOSTNAME'],$_ENV['MYSQL_USERNAME'],$_ENV['MYSQL_PASSWORD'],$_ENV['MYSQL_DATABASE']);
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
                http_response_code(500);
                $mess = Msg::ERR_MYSQLQUERY;
            }
            $h->close();
        }//else di if($h->connect_errno){
    }//if($_REQUEST['password'] == $passwordPulizia){
    else{
        http_response_code(401);
        $mess = Msg::ERR_PWDWRONG;
    }
}//if(isset($_REQUEST['password']) && preg_match($regex,$_REQUEST['password'])){
else{
    http_response_code(400);
    $mess = Msg::ERR_CODEINVALD;
}
$f=fopen('log.txt', 'a');
fwrite($f, date('r', $time).': '.$mess."\r\n");
fclose($f);
?>