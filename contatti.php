<?php

use EcommerceTest\Interfaces\Paths as P;

session_start();

require_once("vendor/autoload.php");

//se un'utente ha effettuato il login
if(isset($_SESSION['logged'],$_SESSION['utente'],$_SESSION['welcome']) && $_SESSION['welcome'] != '' && $_SESSION['logged'] === true){
?>
<!DOCTYPE html>
<html lang="it">
    <head>
        <title>Contatti</title>
        <meta charset="utf-8">
        <link rel="stylesheet" href=<?php echo P::REL_CONTACTS_CSS; ?>>
        <link rel="stylesheet" href=<?php echo P::REL_BOOTSTRAP_CSS; ?>>
        <link rel="stylesheet" href=<?php echo P::REL_JQUERY_CSS; ?> >
        <link rel="stylesheet" href=<?php echo P::REL_JQUERYTHEME_CSS; ?> >
        <link rel="stylesheet" href=<?php echo P::REL_FOOTER_CSS; ?>>
        <script src=<?php echo P::REL_JQUERY_JS; ?>></script>
        <script src=<?php echo P::REL_JQUERYUI_JS; ?>></script>
        <script src=<?php echo P::REL_POPPER_JS; ?>></script>
        <script src=<?php echo P::REL_BOOTSTRAP_JS; ?>></script>
        <script src=<?php echo P::REL_FOOTER_JS; ?>></script>
        <script type="module" src="<?php echo P::REL_DIALOG_MESSAGE_JS; ?>"></script>
        <script type="module" src="<?php echo P::REL_LOGOUT_JS; ?>"></script>
        <script type="module" src="<?php echo P::REL_CONTACTS_JS; ?>"></script>
        <?php 
            if(file_exists('partials/privacy.php') && is_file('partials/privacy.php')){
                echo call_user_func('cookieBanner');
            }
         ?>
    </head>
    <body>
        <?php echo menu($_SESSION['welcome']);?>
        <div id="d1" class="contacts-div">
            <fieldset id="f1">
                <legend>Scrivi il tuo messaggio</legend>
                <div>
                    Contattaci per avere maggiori informazioni o segnalarci un problema
                </div>
                <form id="fContatti" method="post" action="funzioni/mail.php">
                    <div>
                        <label class="form-label" for="oggetto">Oggetto </label>
                        <input type="text" id="oggetto" class="form-control" name="oggetto">
                    </div>
                    <div>
                        <label class="form-label" for="messaggio">Messaggio </label>
                        <textarea class="form-control" id="messaggio" class="form-control" name="messaggio"></textarea>
                    </div>
                    <div class="d-flex justify-content-between justify-content-md-around">
                        <div class="d-flex justify-content-center align-items-center">
                            <button type="submit" id="invia" class="btn btn-primary">INVIA</button>
                            <div id="contacts-spinner" class="spinner-border ms-2 invisible" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                        <button type="reset" id="annulla" class="btn btn-danger">ANNULLA</button>
                    </div>
                </form>
            </fieldset>
        </div>
        <?php echo footer(); ?>
    </body>
</html>
<?php
}
else{
    echo ACCEDI1;
}
?>