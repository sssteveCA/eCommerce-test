<?php

use Dotenv\Dotenv;
use EcommerceTest\Objects\Utente;
use EcommerceTest\Interfaces\UserErrors as Ue;
use EcommerceTest\Interfaces\Messages as Msg;
use EcommerceTest\Interfaces\Constants as C;

session_start();

require_once('../config.php');
require_once('../interfaces/constants.php');
require_once('../interfaces/messages.php');
require_once('../interfaces/userErrors.php');
require_once('../interfaces/emailmanagerErrors.php');
require_once('../exceptions/notsetted.php');
//require_once('../interfaces/mysqlVals.php');
require_once('../vendor/autoload.php');
require_once('../traits/error.php');
require_once('../traits/emailmanager.trait.php');
require_once('../traits/sql.trait.php');
require_once('../traits/utente.trait.php');
require_once('../objects/emailmanager.php');
require_once('../objects/utente.php');
require_once('const.php');

$input = file_get_contents('php://input');
$post = json_decode($input,true);
$response = array();
$response[C::KEY_MESSAGE] = '';

//se un'utente ha effettuato il login
if(isset($_SESSION['logged'],$_SESSION['utente'],$_SESSION['welcome']) && $_SESSION['welcome'] != '' && $_SESSION['logged'] === true){
    $dotenv = Dotenv::createImmutable(__DIR__."/../");
    $dotenv->safeLoad();
    $response['post'] = $post;
    $utente = unserialize($_SESSION['utente']);
    $regex = '/(^$|^\s+$)/';
    //se l'utente vuole modificare lo username
    if(isset($post["username"],$post["user"]) && !preg_match($regex,$post["username"]) && $post["user"] == "1"){
        updateUsername($response);
    }//if(isset($post["username"],$post["user"]) && !preg_match($regex,$post["username"]) && $post["user"] == "1"){
    //se l'utente vuole modificare la password
    if(isset($post["oPwd"],$post["nPwd"],$post["confPwd"],$post["pwd"])
    && !preg_match($regex,$post["oPwd"]) 
    && !preg_match($regex,$post["nPwd"])
    && !preg_match($regex,$post["confPwd"]) && $post["pwd"] == "1"){
        updatePassword($response);
    }/*if(isset($post["oPwd"],$post["nPwd"],$post["confPwd"],$post["pwd"])
    && !preg_match($regex,$post["oPwd"]) 
    && !preg_match($regex,$post["nPwd"])
    && !preg_match($regex,$post["confPwd"]) && $post["pwd"] == "1"){*/
    //se l'utente vuole modificare i suoi dati personali
    if(isset($post['name'],$post["surname"],$post["address"],$post["number"],$post["city"],$post["zip"],$post["pers"])){
        if(!preg_match($regex,$post["name"]) && !preg_match($regex,$post["surname"]) && !preg_match($regex,$post["address"]) &&
        !preg_match($regex,$post["city"]) && !preg_match($regex,$post["zip"]) && $post["pers"] == "1"){
            updatePersonalData($response);
        }
        else http_response_code(400);
    }//if(isset($post['name'],$post["surname"],$post["address"],$post["number"],$post["city"],$post["zip"],$post["pers"])){isset($_POST["cap"])){
    else http_response_code(400);
    echo json_encode($response,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
}//if(isset($_SESSION['logged'],$_SESSION['utente'],$_SESSION['welcome']) && $_SESSION['welcome'] != '' && $_SESSION['logged'] === true){
else{
    http_response_code(401);
    echo '<a href="../index.php">Accedi</a> per poter vedere il contenuto di questa pagina<br>';
}

//Update username field
function updateUsername(array &$response){
    global $post,$utente;
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
            $response[C::KEY_MESSAGE] = Msg::ERR_FORMINVALIDVALUE; 
            $data = array('username' => $post['username']);
            $aggiorna = $utente->update($data,$where);
            if($aggiorna){
                $response[C::KEY_MESSAGE] = Msg::USERUPDATED;
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
                $response[C::KEY_MESSAGE] = Msg::ERR_USERNOTUPDATED;
            } 
        }//if($errno == 0 || $errno == Ue::INCORRECTLOGINDATA){
        else{
            http_response_code(400);
            $response[C::KEY_MESSAGE] = $utente->getStrError();
        }
    }
    catch(Exception $e){
        http_response_code(500);
        $response[C::KEY_MESSAGE] = $e->getMessage();
    }
    
}

//Update password field
function updatePassword(array &$response){
    global $post,$utente;
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
            $response[C::KEY_MESSAGE] = Msg::ERR_FORMINVALIDVALUE;  
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
                        $response[C::KEY_MESSAGE] = Msg::PWDUPDATED;
                        $_SESSION['utente'] = serialize($utente);
                    }
                    else {
                        http_response_code(500);
                        $response[C::KEY_MESSAGE] = Msg::ERR_PWDNOTUPDATED;
                    } 
                }//if($_POST["nPwd"] == $_POST["confPwd"]){
                else {
                    http_response_code(400);
                    $response[C::KEY_MESSAGE] = Msg::ERR_PWDCONFDIFFERENT;
                }   
            }//if(password_verify($_POST["oPwd"],$passwordC)){
            else {
                http_response_code(401);
                $response[C::KEY_MESSAGE] = Msg::ERR_PWDCURRENTWRONG;
            }
        }//if($errno == 0 || $errno == Ue::INCORRECTLOGINDATA){
    }
    catch(Exception $e){
        $response[C::KEY_MESSAGE] = $e->getMessage();
    } 
}

//Update personal data
function updatePersonalData(array &$response){
    global $post,$utente;
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
            $response[C::KEY_MESSAGE] = Msg::ERR_FORMINVALIDVALUE; 
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
                    $response[C::KEY_MESSAGE] = Msg::PERSONALDATAUPDATED;
                }
                else {
                    $response[C::KEY_MESSAGE] = Msg::ERR_PERSONALDATANOTUPDATED;
                }
            }//if($utente->valida($dati)){
            else{
                $response[C::KEY_MESSAGE] = Ue::MSG_INVALIDDATAFORMAT;
            }
        }//if($errno == 0 || $errno == Ue::INCORRECTLOGINDATA){
        $response["errore"] = $utente->getNumError();
        $response["query"] = $utente->getQuery();
        $response["queries"] = $utente->getQueries();
    }
    catch(Exception $e){
        $response[C::KEY_MESSAGE] = $e->getMessage();
    }
}
?>