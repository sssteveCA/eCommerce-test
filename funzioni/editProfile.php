<?php

use EcommerceTest\Objects\Utente;
use EcommerceTest\Interfaces\UserErrors as Ue;
use EcommerceTest\Interfaces\Messages as Msg;

session_start();

require_once('../interfaces/messages.php');
require_once('../interfaces/userErrors.php');
require_once('../interfaces/mysqlVals.php');
require_once('../objects/utente.php');
require_once('const.php');

//se un'utente ha effettuato il login
if(isset($_SESSION['logged'],$_SESSION['utente'],$_SESSION['welcome']) && $_SESSION['welcome'] != '' && $_SESSION['logged'] === true){
    $risposta = array();
    $risposta['post'] = $_POST;
    $utente = unserialize($_SESSION['utente']);
    //file_put_contents("log.txt","editProfile.php utente => ".var_export($utente,true)."\r\n",FILE_APPEND);
    $dati = array();
    $dati['campo'] = 'username';
    $dati['username'] = $utente->getUsername();
    $dati['registrato'] = true;
    try{
        $utente = new Utente($dati);
        //file_put_contents("log.txt","editProfile.php utente try => ".var_export($utente,true)."\r\n",FILE_APPEND);
        $errno = $utente->getNumError();
        if($errno == 0 || $errno == Ue::INCORRECTLOGINDATA){
            //file_put_contents("log.txt","editProfile.php utente errno 0 => ".var_export($utente,true)."\r\n",FILE_APPEND);
            $where = array();
            $where['username'] = $utente->getUsername();
            $risposta["msg"] = Msg::ERR_FORMINVALIDVALUE;     
            //verifica se il valore passato è composto solo da spazi
            $regex = '/(^$|^\s+$)/';
            //se l'utente vuole modificare lo username
            if(isset($_POST["username"],$_POST["user"]) && !preg_match($regex,$_POST["username"]) && $_POST["user"] == "1"){
                updateUsername();
            }//if(isset($_POST["username"]) && !preg_match($regex,$_POST["username"])){
            //se l'utente vuole modificare la password
            if(isset($_POST["oPwd"],$_POST["nPwd"],$_POST["confPwd"],$_POST["pwd"])
            && !preg_match($regex,$_POST["oPwd"]) 
            && !preg_match($regex,$_POST["nPwd"])
            && !preg_match($regex,$_POST["confPwd"]) && $_POST["pwd"] == "1"){
               updatePassword();
            }//if(isset($_POST["oPwd"],$_POST["nPwd"],$_POST["confPwd"])&& !preg_match($regex,$_POST["oPwd"]) && !preg_match($regex,$_POST["nPwd"])&& !preg_match($regex,$_POST["confPwd"])){
            //se l'utente vuole modificare i suoi dati personali
            if(isset($_POST['nome'],$_POST["cognome"],$_POST["indirizzo"],$_POST["numero"],$_POST["citta"],$_POST["cap"],$_POST["pers"])){
                if(!preg_match($regex,$_POST["nome"]) && !preg_match($regex,$_POST["cognome"]) && !preg_match($regex,$_POST["indirizzo"]) &&
                !preg_match($regex,$_POST["citta"]) && !preg_match($regex,$_POST["cap"]) && $_POST["pers"] == "1"){
                    $aggiorna = false;
                    $dati = array(
                        'nome' => $_POST['nome'],
                        'cognome' => $_POST['cognome'],
                        'indirizzo' => $_POST['indirizzo'],
                        'numero' => $_POST['numero'],
                        'citta' => $_POST['citta'],
                        'cap' => $_POST['cap']
                    );
                    //controlla i dati prima di aggiornarli
                    if($utente->valida($dati)){
                        //file_put_contents("log.txt","editProfile.php valida => true\r\n",FILE_APPEND);
                            $aggiorna = $utente->update($dati,$where);
                            $_SESSION['utente'] = serialize($utente);
                    }
                    else{
                        //file_put_contents("log.txt","editProfile.php valida => false\r\n",FILE_APPEND);
                    }
                    if($aggiorna){
                        $risposta["msg"] = Msg::PERSONALDATAUPDATED;
                        }
                        else {
                            $risposta["msg"] = Msg::ERR_PERSONALDATANOTUPDATED;
                        }
                }
            }//if(isset($_POST['nome']) && isset($_POST["cognome"]) && isset($_POST["indirizzo"]) && isset($_POST["numero"]) && isset($_POST["citta"]) && isset($_POST["cap"])){
        }//if($errno == 0 || $errno == UTENTEERR_INCORRECTLOGINDATA){
        else{
            $risposta['msg'] = $utente->getStrError();
        }
        $risposta["errore"] = $utente->getNumError();
        $risposta["query"] = $utente->getQuery();
        $risposta["queries"] = $utente->getQueries();
        echo json_encode($risposta);
    }
    catch(Exception $e){
        $risposta['msg'] = $e->getMessage();
    }
    
}//if(isset($_SESSION['logged'],$_SESSION['utente'],$_SESSION['welcome']) && $_SESSION['welcome'] != '' && $_SESSION['logged'] === true){
else{
    echo '<a href="accedi.php">Accedi</a> per poter vedere il contenuto di questa pagina<br>';
}

//Update username field
function updateUsername(){
    global $risposta,$utente;
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
            $risposta["msg"] = Msg::ERR_FORMINVALIDVALUE; 
            $data = array('username' => $_POST['username']);
            $aggiorna = $utente->update($data,$where);
            if($aggiorna){
                $risposta["msg"] = Msg::USERUPDATED;
                $_SESSION['welcome'] = '';
                if($utente->getSesso() == 'Maschio'){
                    $_SESSION['welcome'] = 'Benvenuto ';
                }
                else if($utente->getSesso() == 'Femmina'){
                    $_SESSION['welcome'] = 'Benvenuta ';
                }
                $_SESSION['welcome'] .= $utente->getUsername();
                $risposta["user"] = $utente->getUsername(); 
                $_SESSION['utente'] = serialize($utente);
            }
            else $risposta["msg"] = Msg::ERR_USERNOTUPDATED;
        }//if($errno == 0 || $errno == Ue::INCORRECTLOGINDATA){
        else{
            $risposta['msg'] = $utente->getStrError();
        }
    }
    catch(Exception $e){
        $risposta['msg'] = $e->getMessage();
    }
    
}

//Update password field
function updatePassword(){
    global $risposta,$utente;
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
            $risposta["msg"] = Msg::ERR_FORMINVALIDVALUE;  
            $passwordC = $utente->getPassword();
            file_put_contents("log.txt","PasswordC => ".var_export($passwordC,true)."\r\n",FILE_APPEND);
            file_put_contents("log.txt","POST => ".var_export($_POST,true)."\r\n",FILE_APPEND);
            //se la password da sostituire è uguale a quella attuale
                if(password_verify($_POST["oPwd"],$passwordC)){
                    //se la nuova password è uguale a quella confermata
                    if($_POST["nPwd"] == $_POST["confPwd"]){
                    $new = array();
                    $new['password'] = $_POST['nPwd'];
                    $aggiorna = $utente->update($new,$where);
                    if($aggiorna){
                        $risposta["msg"] = Msg::PWDUPDATED;
                        $_SESSION['utente'] = serialize($utente);
                    }
                    else $risposta["msg"] = Msg::ERR_PWDNOTUPDATED;
                    }//if($_POST["nPwd"] == $_POST["confPwd"]){
                else $risposta["msg"] = Msg::ERR_PWDCONFDIFFERENT;
            }//if(password_verify($_POST["oPwd"],$passwordC)){
            else $risposta["msg"] = Msg::ERR_PWDCURRENTWRONG;
        }//if($errno == 0 || $errno == Ue::INCORRECTLOGINDATA){
    }
    catch(Exception $e){
        $risposta['msg'] = $e->getMessage();
    } 
}
?>