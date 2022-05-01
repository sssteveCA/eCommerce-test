<?php
session_start();

//require_once('objects/utente.php');
require_once('navbar.php');
require_once("funzioni/const.php");

//se un'utente ha effettuato il login
if(isset($_SESSION['logged'],$_SESSION['utente'],$_SESSION['welcome']) && $_SESSION['welcome'] != '' && $_SESSION['logged'] === true){
?>
<!DOCTYPE html>
<html lang="it">
    <head>
        <title>Contatti</title>
        <meta charset="utf-8">
        <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
        <!-- <link rel="stylesheet" href="css/utente.css"> -->
        <link rel="stylesheet" href="css/contatti.css">
        <link rel="stylesheet" href="jqueryUI/jquery-ui.min.css">
        <link rel="stylesheet" href="jqueryUI/jquery-ui.theme.min.css">
        <script src="js/jquery-3.5.1.min.js"></script>
        <script src="jqueryUI/jquery-ui.min.js"></script>
        <script src="bootstrap/js/bootstrap.min.js"></script>
        <script src="js/dialog/dialog.js"></script>
        <script src="js/contatti.js"></script>
        <script src="js/logout.js"></script>
    </head>
    <body>
        <?php echo menu($_SESSION['welcome']);?>
        <div id="d1">
            <fieldset id="f1">
                <legend>Scrivi il tuo messaggio</legend>
                <div>
                    Contattaci per avere maggiori informazioni o segnalarci un problema
                </div>
                <form id="fContatti" method="post" action="funzioni/mail.php">
                    <div>
                        <label for="oggetto">Oggetto </label>
                        <input type="text" id="oggetto" name="oggetto">
                    </div>
                    <div>
                        <label for="messaggio">Messaggio </label>
                        <textarea id="messaggio" name="messaggio"></textarea>
                    </div>
                    <div>
                        <input type="submit" id="invia" value="INVIA">
                        <input type="reset" id="annulla" value="ANNULLA">
                    </div>
                </form>
            </fieldset>
        </div>
    </body>
</html>
<?php
}
else{
    echo ACCEDI1;
}
?>