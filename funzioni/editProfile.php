<?php

use EcommerceTest\Objects\Utente;
use EcommerceTest\Interfaces\UserErrors as Ue;

session_start();

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
        if($errno == 0 || $errno == UTENTEERR_INCORRECTLOGINDATA){
            //file_put_contents("log.txt","editProfile.php utente errno 0 => ".var_export($utente,true)."\r\n",FILE_APPEND);
            $where = array();
            $where['username'] = $utente->getUsername();
            $risposta["msg"] = "Nessun valore valido inserito nel form";     
            //verifica se il valore passato è composto solo da spazi
            $regex = '/(^$|^\s+$)/';
            //se l'utente vuole modificare lo username
            if(isset($_POST["username"],$_POST["user"]) && !preg_match($regex,$_POST["username"]) && $_POST["user"] == "1"){
                $dati = array('username' => $_POST['username']);
                $aggiorna = $utente->update($dati,$where);
                if($aggiorna){
                    $risposta["msg"] = "Username aggiornato";
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
                else $risposta["msg"] = "ERRORE: Username non aggiornato";
            }//if(isset($_POST["username"]) && !preg_match($regex,$_POST["username"])){
            //se l'utente vuole modificare la password
            if(isset($_POST["oPwd"],$_POST["nPwd"],$_POST["confPwd"],$_POST["pwd"])
            && !preg_match($regex,$_POST["oPwd"]) 
            && !preg_match($regex,$_POST["nPwd"])
            && !preg_match($regex,$_POST["confPwd"]) && $_POST["pwd"] == "1"){
                $passwordC = $utente->getPassword();
                file_put_contents("log.txt","passwordC => ".var_export($passwordC,true)."\r\n",FILE_APPEND);
                    //se la password da sostituire è uguale a quella attuale
                    if(password_verify($_POST["oPwd"],$passwordC)){
                            //se la nuova password è uguale a quella confermata
                            if($_POST["nPwd"] == $_POST["confPwd"]){
                                $nuovo = array();
                                $nuovo['password'] = $_POST['nPwd'];
                                $aggiorna = $utente->update($nuovo,$where); 
                                //update('username',$_SESSION["user"],'password',password_hash($_POST["nPwd"],PASSWORD_DEFAULT));
                                if($aggiorna){
                                    $risposta["msg"] = "Password aggiornata";
                                    $_SESSION['utente'] = serialize($utente);
                                }
                                else $risposta["msg"] = "ERRORE: password non aggiornata";
                            }//if($_POST["nPwd"] == $_POST["confPwd"]){
                            else $risposta["msg"] = 'La password da sostituire non coincide con quella attuale';
                        }//if(password_verify($_POST["oPwd"],$passwordC)){
                        else $risposta["msg"] = 'password attuale non corretta';
                
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
                        $risposta["msg"] = "Dati personali aggiornati con successo";
                        }
                        else {
                            $risposta["msg"] = "ERRORE: Dati personali non aggiornati";
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
?>