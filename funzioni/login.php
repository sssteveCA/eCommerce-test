<?php

use Dotenv\Dotenv;
use EcommerceTest\Objects\Utente;
use EcommerceTest\Interfaces\Messages as Msg;

ob_start();
session_start();

require_once("../vendor/autoload.php");

//ob_start();
$_SESSION['logged'] = false;
if(isset($_POST['email'],$_POST['password']) && $_POST['email'] != '' && $_POST['password'] != ''){
    $dotenv = Dotenv::createImmutable(__DIR__."/../");
    $dotenv->safeLoad();
    $dati = array();
    $dati['campo'] = 'email';
    $dati['email'] = $_POST['email'];
    $dati['password'] = $_POST['password'];
    $dati['registrato'] = '1';
    try{
        $utente = new Utente($dati);
        $err = $utente->getNumError();
        $login = $utente->isLogin();
        //accesso autorizzato
        if($err == 0 && $login){
            if($utente->getSesso() == 'Maschio') $_SESSION['welcome'] = 'Benvenuto '.$utente->getUsername();
            if($utente->getSesso() == 'Femmina') $_SESSION['welcome'] = 'Benvenuta '.$utente->getUsername();
            else $_SESSION['welcome'] = $utente->getUsername();
            $_SESSION['utente'] = serialize($utente);
            $_SESSION['logged'] = true;
            header('location: ../');
        }
        else if($err == 1){
            http_response_code(401);
            echo Msg::ERR_USERPWDWRONG.'<br>';
            header('refresh:7;url=../index.php');
        }
        else if($err == 2){
            http_response_code(401);
            echo Msg::ERR_ACTIVEACCOUNT.'<br>';
            header('refresh:10;url=../index.php');
        }
        else{
            http_response_code(500);
            echo Msg::ERR_UNKNOWN.'<br>';
        }
    }
    catch(Exception $e){
        http_response_code(500);
        echo $e->getMessage();
    } 
}//if(isset($_POST['email'],$_POST['password']) && $_POST['email'] != '' && $_POST['password'] != ''){
else{
    header('location: ../index.php');
}
?>