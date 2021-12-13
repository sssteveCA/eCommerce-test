<?php
ob_start();
require_once('config.php');
$regex = '/^[a-z0-9]{1,64}$/i';
$time = time();
if(isset($_REQUEST['password']) && preg_match($regex,$_REQUEST['password'])){
    if($_REQUEST['password'] == $passwordPulizia){
        //apro la connessione al server MySQL
        $h = new mysqli('localhost','root','','stefano');
        //errore
        if($h->connect_errno){
            $mess = 'Errore durante la connessione a MySql';
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
                $mess = 'Query errata';
            }
            $h->close();
        }
    }
    else{
        $mess = 'Password errata';
    }
}
else{
    $mess = 'Codice non valido';
}
$f=fopen('log.txt', 'a');
fwrite($f, date('r', $time).': '.$mess."\r\n");
fclose($f);
?>