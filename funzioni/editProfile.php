<?php

use EcommerceTest\Objects\Utente;
use EcommerceTest\Interfaces\UserErrors as Ue;
use EcommerceTest\Interfaces\Messages as Msg;

session_start();

require_once('../config.php');
require_once('../interfaces/messages.php');
require_once('../interfaces/userErrors.php');
//require_once('../interfaces/mysqlVals.php');
require_once("../vendor/autoload.php");
require_once('../objects/utente.php');
require_once('const.php');

$input = file_get_contents('php://input');
$post = json_decode($input,true);
$response = array();
$response['msg'] = '';

//se un'utente ha effettuato il login
if(isset($_SESSION['logged'],$_SESSION['utente'],$_SESSION['welcome']) && $_SESSION['welcome'] != '' && $_SESSION['logged'] === true){
    $response['post'] = $post;
    $utente = unserialize($_SESSION['utente']);
    $regex = '/(^$|^\s+$)/';
    //se l'utente vuole modificare lo username
    if(isset($post["username"],$post["user"]) && !preg_match($regex,$post["username"]) && $post["user"] == "1"){
        updateUsername();
    }//if(isset($post["username"],$post["user"]) && !preg_match($regex,$post["username"]) && $post["user"] == "1"){
    //se l'utente vuole modificare la password
    if(isset($post["oPwd"],$post["nPwd"],$post["confPwd"],$post["pwd"])
    && !preg_match($regex,$post["oPwd"]) 
    && !preg_match($regex,$post["nPwd"])
    && !preg_match($regex,$post["confPwd"]) && $post["pwd"] == "1"){
        updatePassword();
    }/*if(isset($post["oPwd"],$post["nPwd"],$post["confPwd"],$post["pwd"])
    && !preg_match($regex,$post["oPwd"]) 
    && !preg_match($regex,$post["nPwd"])
    && !preg_match($regex,$post["confPwd"]) && $post["pwd"] == "1"){*/
    //se l'utente vuole modificare i suoi dati personali
    if(isset($post['name'],$post["surname"],$post["address"],$post["number"],$post["city"],$post["zip"],$post["pers"])){
        if(!preg_match($regex,$post["name"]) && !preg_match($regex,$post["surname"]) && !preg_match($regex,$post["address"]) &&
        !preg_match($regex,$post["city"]) && !preg_match($regex,$post["zip"]) && $post["pers"] == "1"){
            updatePersonalData();
        }
        else http_response_code(400);
    }//if(isset($post['name'],$post["surname"],$post["address"],$post["number"],$post["city"],$post["zip"],$post["pers"])){isset($_POST["cap"])){
    else http_response_code(400);
    echo json_encode($response);
}//if(isset($_SESSION['logged'],$_SESSION['utente'],$_SESSION['welcome']) && $_SESSION['welcome'] != '' && $_SESSION['logged'] === true){
else{
    http_response_code(401);
    echo '<a href="../index.php">Accedi</a> per poter vedere il contenuto di questa pagina<br>';
}

//Update username field
function updateUsername(){
    global $post,$response,$utente;
    $data = array();
    $data['campo'] = 'username';
    $data['username'] = $utente->getUsername();
    $data['registrato'] = true;
    try{
        $utente = new Utente($data);
        $errno = $utente->getNumError();
        if($errno == 0 || $errno == Ue::INCORRECTLOGINDATA){
            $where = array();
            $where['username'] = $utente->getUsername();
            $response["msg"] = Msg::ERR_FORMINVALIDVALUE; 
            $data = array('username' => $post['username']);
            $aggiorna = $utente->update($data,$where);
            if($aggiorna){
                $response["msg"] = Msg::USERUPDATED;
                $_SESSION['welcome'] = '';
                if($utente->getSesso() == 'Maschio'){
                    $_SESSION['welcome'] = 'Benvenuto ';
                }
                else if($utente->getSesso() == 'Femmina'){
                    $_SESSION['welcome'] = 'Benvenuta ';
                }
                $_SESSION['welcome'] .= $utente->getUsername();
                $response["user"] = $utente->getUsername(); 
                $_SESSION['utente'] = serialize($utente);
            }
            else{
                http_response_code(500);
                $response["msg"] = Msg::ERR_USERNOTUPDATED;
            } 
        }//if($errno == 0 || $errno == Ue::INCORRECTLOGINDATA){
        else{
            http_response_code(400);
            $response['msg'] = $utente->getStrError();
        }
    }
    catch(Exception $e){
        http_response_code(500);
        $response['msg'] = $e->getMessage();
    }
    
}

//Update password field
function updatePassword(){
    global $post,$response,$utente;
    $data = array();
    $data['campo'] = 'username';
    $data['username'] = $utente->getUsername();
    $data['registrato'] = true;
    try{
        $utente = new Utente($data);
        $errno = $utente->getNumError();
        if($errno == 0 || $errno == Ue::INCORRECTLOGINDATA){
            $where = array();
            $where['username'] = $utente->getUsername();
            $response["msg"] = Msg::ERR_FORMINVALIDVALUE;  
            $passwordC = $utente->getPassword();
            /* file_put_contents("log.txt","PasswordC => ".var_export($passwordC,true)."\r\n",FILE_APPEND);
            file_put_contents("log.txt","POST => ".var_export($_POST,true)."\r\n",FILE_APPEND); */
            //se la password da sostituire è uguale a quella attuale
                if(password_verify($post["oPwd"],$passwordC)){
                    //se la nuova password è uguale a quella confermata
                    if($post["nPwd"] == $post["confPwd"]){
                    $new = array();
                    $new['password'] = $post['nPwd'];
                    $aggiorna = $utente->update($new,$where);
                    if($aggiorna){
                        $response["msg"] = Msg::PWDUPDATED;
                        $_SESSION['utente'] = serialize($utente);
                    }
                    else {
                        http_response_code(500);
                        $response["msg"] = Msg::ERR_PWDNOTUPDATED;
                    } 
                }//if($_POST["nPwd"] == $_POST["confPwd"]){
                else {
                    http_response_code(400);
                    $response["msg"] = Msg::ERR_PWDCONFDIFFERENT;
                }   
            }//if(password_verify($_POST["oPwd"],$passwordC)){
            else {
                http_response_code(401);
                $response["msg"] = Msg::ERR_PWDCURRENTWRONG;
            }
        }//if($errno == 0 || $errno == Ue::INCORRECTLOGINDATA){
    }
    catch(Exception $e){
        $response['msg'] = $e->getMessage();
    } 
}

//Update personal data
function updatePersonalData(){
    global $post,$response,$utente;
    $data = array();
    $data['campo'] = 'username';
    $data['username'] = $utente->getUsername();
    $data['registrato'] = true;
    try{
        $utente = new Utente($data);
        $errno = $utente->getNumError();
        if($errno == 0 || $errno == Ue::INCORRECTLOGINDATA){
            $where = array();
            $where['username'] = $utente->getUsername();
            $response["msg"] = Msg::ERR_FORMINVALIDVALUE; 
            $update = false;
            $data = array(
                'nome' => $post['name'],
                'cognome' => $post['surname'],
                'indirizzo' => $post['address'],
                'numero' => $post['number'],
                'citta' => $post['city'],
                'cap' => $post['zip'],
                'paypalMail' => $post['paypalMail'],
                'clientId' => $post['clientId']
            );
            if($utente->valida($data)){
                $update = $utente->update($data,$where);
                $_SESSION['utente'] = serialize($utente);
                if($update){
                    $response["msg"] = Msg::PERSONALDATAUPDATED;
                }
                else {
                    $response["msg"] = Msg::ERR_PERSONALDATANOTUPDATED;
                }
            }//if($utente->valida($dati)){
            else{
                $response['msg'] = Ue::INVALIDDATAFORMAT;
            }
        }//if($errno == 0 || $errno == Ue::INCORRECTLOGINDATA){
        $response["errore"] = $utente->getNumError();
        $response["query"] = $utente->getQuery();
        $response["queries"] = $utente->getQueries();
    }
    catch(Exception $e){
        $response['msg'] = $e->getMessage();
    }
}
?>