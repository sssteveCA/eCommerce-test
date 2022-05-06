<?php

use EcommerceTest\Interfaces\Paths as P;

session_start();

require_once('interfaces/paths.php');
require_once('navbar.php');
require_once('interfaces/mysqlVals.php');
require_once('interfaces/userErrors.php');
require_once('objects/utente.php');
require_once("funzioni/const.php");

//se un'utente ha effettuato il login
if(isset($_SESSION['logged'],$_SESSION['utente'],$_SESSION['welcome']) && $_SESSION['welcome'] != '' && $_SESSION['logged'] === true){
    $oUtente = unserialize($_SESSION['utente']);
    //file_put_contents("log.txt","edit.php oUtente => ".var_export($oUtente,true)."\r\n",FILE_APPEND);
    //informazioni complete sull'utente $_SESSION['user']
    $utente = array();
    $utente['nome'] = $oUtente->getNome();
    $utente['cognome'] = $oUtente->getCognome();
    $utente['nascita'] = $oUtente->getNascita();
    $utente['sesso'] = $oUtente->getSesso();
    $utente['indirizzo'] = $oUtente->getIndirizzo();
    $utente['numero'] = $oUtente->getNumero();
    $utente['citta'] = $oUtente->getCitta();
    $utente['cap'] = $oUtente->getCap();
    $utente['username'] = $oUtente->getUsername();
    $utente['paypalMail'] = $oUtente->getPaypalMail();
    $utente['clientId'] = $oUtente->getClientId();
    //se le informazioni sono state ottenute senza problemi
?>
<!DOCTYPE html>
<html lang="it">
    <head>
        <title>Modifica profilo</title>
        <meta charset="utf-8">
        <link rel="stylesheet" href=<?php echo P::REL_EDIT_CSS; ?>>
        <link rel="stylesheet" href=<?php echo P::REL_BOOTSTRAP_CSS; ?>>
        <link rel="stylesheet" href=<?php echo P::REL_JQUERY_CSS; ?> >
        <link rel="stylesheet" href=<?php echo P::REL_JQUERYTHEME_CSS; ?> >
        <script src=<?php echo P::REL_JQUERY_JS; ?>></script>
        <script src=<?php echo P::REL_JQUERYUI_JS; ?>></script>
        <script src=<?php echo P::REL_BOOTSTRAP_JS; ?>></script>
        <script type="module" src=<?php echo P::REL_DIALOG_MESSAGE_JS; ?>></script>
        <script src=<?php echo P::REL_LOGOUT_JS; ?>></script>
        <script src=<?php echo P::REL_EDIT_JS; ?>></script>
    </head>
    <body>
    <div id="container">
        <?php echo menu($_SESSION['welcome']);?>
        <div id="container2">
            <h1>Modifica profilo</h1>
            <fieldset id="f1">
                <legend>Modifica username</legend>
                <form id="userEdit" method="post" action="funzioni/editProfile.php">
                    <input type="hidden" id="user" name="user" value="1">
                    <div>
                        <label for="newUser">Nuovo username</label>
                        <input type="text" id="newUser" name="username" value="<?php echo $utente['username']; ?>">
                    </div>
                    <div>
                        <input type="submit" id="bUser" value="OK">
                        <input type="reset" value="ANNULLA">
                    </div>
                </form>
            </fieldset>
            <fieldset id="f2">
                <legend>Modifica password</legend>
                <form id="pwdEdit" method="post" action="editProfile.php">
                    <input type="hidden" id="pwd" name="pwd" value="1">
                    <div>
                        <label for="oldPwd">Vecchia password</label>
                        <input type="password" id="oldPwd" name="oPwd">
                    </div>
                    <div>
                        <label for="newPwd">Nuova password</label>
                        <input type="password" id="newPwd" name="nPwd">
                    </div>
                    <div>
                        <label for="confPwd">Conferma nuova password</label>
                        <input type="password" id="confPwd" name="confPwd">
                    </div>
                    <div>
                        <input type="submit" id="bPwd" value="OK">
                        <input type="reset" value="ANNULLA">
                    </div>
                </form>
            </fieldset>
            <fieldset id="f3">
                <legend>utente personali</legend>
                <form id="dataEdit" method="post" action="editProfile.php">
                    <input type="hidden" id="pers" name="pers" value="1">
                    <div>
                        <label for="nome">Nome</label>
                        <input type="text" id="nome" name="nome" value="<?php echo $utente['nome']; ?>">
                    </div>
                    <div>
                        <label for="cognome">Cognome</label>
                        <input type="text" id="cognome" name="cognome" value="<?php echo $utente['cognome']; ?>">
                    </div>
                    <div>
                        <label for="indirizzo">Indirizzo</label>
                        <input type="text" id="indirizzo" name="indirizzo" value="<?php echo $utente['indirizzo']; ?>">
                    </div>
                    <div>
                        <label for="numero">Numero</label>
                        <input type="text" id="numero" name="numero" value="<?php echo $utente['numero']; ?>">
                    </div>
                    <div>
                        <label for="citta">Città</label>
                        <input type="text" id="citta" name="citta" value="<?php echo $utente['citta']; ?>">
                    </div>
                    <div>
                        <label for="cap">CAP</label>
                        <input type="text" id="cap" name="cap" value="<?php echo $utente['cap']; ?>">
                    </div>
                    <div>
                        <label for="paypalMail">Email business</label>
                        <input type="text" id="paypalMail" name="paypalMail" value="<?php echo $utente['paypalMail']; ?>">
                    </div>
                    <div>
                        <label for="clientId">Client ID</label>
                        <input type="text" id="clientId" name="clientId" value="<?php echo $utente['clientId']; ?>">
                    </div>
                    <div>
                        <input type="submit" id="butente" value="OK">
                        <input type="reset" value="ANNULLA">
                    </div>
                </form>
            </fieldset>
        </div>
    </body>
</html>
<?php
}//if(isset($_SESSION['logged'],$_SESSION['utente'],$_SESSION['welcome']) && $_SESSION['welcome'] != '' && $_SESSION['logged'] === true){
else{
    echo ACCEDI1;
}
?>
