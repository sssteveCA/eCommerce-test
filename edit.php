<?php

use Dotenv\Dotenv;
use EcommerceTest\Interfaces\Paths as P;

session_start();

require_once('config.php');
require_once('interfaces/paths.php');
require_once('partials/navbar.php');
//require_once('interfaces/mysqlVals.php');
require_once('interfaces/userErrors.php');
require_once('interfaces/emailmanagerErrors.php');
require_once('exceptions/notsetted.php');
require_once('vendor/autoload.php');
require_once('traits/error.php');
require_once('traits/emailmanager.trait.php');
require_once('objects/emailmanager.php');
require_once('objects/utente.php');
require_once("funzioni/const.php");
require('partials/footer.php');
@include_once('partials/privacy.php');

//se un'utente ha effettuato il login
if(isset($_SESSION['logged'],$_SESSION['utente'],$_SESSION['welcome']) && $_SESSION['welcome'] != '' && $_SESSION['logged'] === true){
    $dotenv = Dotenv::createImmutable(__DIR__);
    $dotenv->safeLoad();
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
    file_put_contents("log.txt",var_export($utente,true)."\r\n",FILE_APPEND);
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
        <link rel="stylesheet" href=<?php echo P::REL_FOOTER_CSS; ?>>
        <script src=<?php echo P::REL_JQUERY_JS; ?>></script>
        <script src=<?php echo P::REL_JQUERYUI_JS; ?>></script>
        <script src=<?php echo P::REL_POPPER_JS; ?>></script>
        <script src=<?php echo P::REL_BOOTSTRAP_JS; ?>></script>
        <script src=<?php echo P::REL_FOOTER_JS; ?>></script>
        <script type="module" src=<?php echo P::REL_DIALOG_MESSAGE_JS; ?>></script>
        <script type="module" src="<?php echo P::REL_LOGOUT_JS; ?>"></script>
        <script src="js/dialog/dialog.js"></script> <!-- temporary -->
        <script type="module" src=<?php echo P::REL_EDIT_JS; ?>></script>
        <?php 
            if(file_exists('partials/privacy.php') && is_file('partials/privacy.php')){
                echo call_user_func('cookieBanner');
            }
         ?>
    </head>
    <body>
    <div id="container">
        <?php echo menu($_SESSION['welcome']);?>
        <div id="container2">
            <h1>Modifica profilo</h1>
            <fieldset id="f1" class="my-3">
                <legend>Modifica username</legend>
                <form id="userEdit" method="post" action="funzioni/editProfile.php">
                    <input type="hidden" id="user" name="user" value="1">
                    <div>
                        <label class="form-label" for="newUser">Nuovo username</label>
                        <input type="text" id="newUser" class="form-control" name="username" value="<?php echo $utente['username']; ?>">
                    </div>
                    <div>
                        <button type="submit" id="bUser" class="btn btn-primary">OK</button>
                        <button type="reset" class="btn btn-danger">ANNULLA</button>
                    </div>
                </form>
            </fieldset>
            <fieldset id="f2" class="my-3">
                <legend>Modifica password</legend>
                <form id="pwdEdit" method="post" action="funzioni/editProfile.php">
                    <input type="hidden" id="pwd" name="pwd" value="1">
                    <div>
                        <label class="form-label" for="oldPwd">Vecchia password</label>
                        <input type="password" id="oldPwd" class="form-control" name="oPwd">
                    </div>
                    <div>
                        <label class="form-label" for="newPwd">Nuova password</label>
                        <input type="password" id="newPwd" class="form-control" name="nPwd">
                    </div>
                    <div>
                        <label class="form-label" for="confPwd">Conferma nuova password</label>
                        <input type="password" id="confPwd" class="form-control" name="confPwd">
                    </div>
                    <div>
                        <button type="submit" id="bPwd" class="btn btn-primary">OK</button>
                        <button type="reset" class="btn btn-danger">ANNULLA</button>
                    </div>
                </form>
            </fieldset>
            <fieldset id="f3" class="my-3">
                <legend>Dati personali</legend>
                <form id="dataEdit" method="post" action="funzioni/editProfile.php">
                    <input type="hidden" id="pers" name="pers" value="1">
                    <div>
                        <label class="form-label" for="nome">Nome</label>
                        <input type="text" id="nome" class="form-control" name="nome" value="<?php echo $utente['nome']; ?>">
                    </div>
                    <div>
                        <label class="form-label" for="cognome">Cognome</label>
                        <input type="text" id="cognome" class="form-control" name="cognome" value="<?php echo $utente['cognome']; ?>">
                    </div>
                    <div>
                        <label class="form-label" for="indirizzo">Indirizzo</label>
                        <input type="text" id="indirizzo" class="form-control" name="indirizzo" value="<?php echo $utente['indirizzo']; ?>">
                    </div>
                    <div>
                        <label class="form-label" for="numero">Numero</label>
                        <input type="text" id="numero" class="form-control" name="numero" value="<?php echo $utente['numero']; ?>">
                    </div>
                    <div>
                        <label class="form-label" for="citta">Città</label>
                        <input type="text" id="citta" class="form-control" name="citta" value="<?php echo $utente['citta']; ?>">
                    </div>
                    <div>
                        <label class="form-label" for="cap">CAP</label>
                        <input type="text" id="cap" class="form-control" name="cap" value="<?php echo $utente['cap']; ?>">
                    </div>
                    <div>
                        <label class="form-label" for="paypalMail">Email business</label>
                        <input type="text" id="paypalMail" class="form-control" name="paypalMail" value="<?php echo $utente['paypalMail']; ?>">
                    </div>
                    <div>
                        <label class="form-label" for="clientId">Client ID</label>
                        <input type="text" id="clientId" class="form-control" name="clientId" value="<?php echo $utente['clientId']; ?>">
                    </div>
                    <div>
                        <button type="submit" id="butente" class="btn btn-primary">OK</button>
                        <button type="reset" class="btn btn-danger">ANNULLA</button>
                    </div>
                </form>
            </fieldset>
        </div>
        <?php echo footer(); ?>
    </body>
</html>
<?php
}//if(isset($_SESSION['logged'],$_SESSION['utente'],$_SESSION['welcome']) && $_SESSION['welcome'] != '' && $_SESSION['logged'] === true){
else{
    echo ACCEDI1;
}
?>
