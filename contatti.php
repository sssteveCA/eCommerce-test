<?php
session_start();

//require_once('objects/utente.php');
require_once("funzioni/const.php");

//se un'utente ha effettuato il login
if(isset($_SESSION['logged'],$_SESSION['utente'],$_SESSION['welcome']) && $_SESSION['welcome'] != '' && $_SESSION['logged'] === true){
?>
<!DOCTYPE html>
<html lang="it">
    <head>
        <title>Contatti</title>
        <meta charset="utf-8">
        <link rel="stylesheet" href="css/utente.css" type="text/css">
        <link rel="stylesheet" href="css/contatti.css" type="text/css">
        <link rel="stylesheet" href="jqueryUI/jquery-ui.min.css" type="text/css">
        <link rel="stylesheet" href="jqueryUI/jquery-ui.theme.min.css" type="text/css">
        <script src="js/jquery-3.5.1.min.js"></script>
        <script src="jqueryUI/jquery-ui.min.js"></script>
        <script src="js/dialog/dialog.js"></script>
        <script src="js/contatti.js"></script>
        <script src="js/logout.js"></script>
    </head>
    <body>
    <div id="container">
            <div id="menu">
                <div id="welcome"><?php echo $_SESSION['welcome']; ?></div>
                <div id="profilo">
                    Profilo
                    <div>
                        <a href="info.php">Informazioni</a>
                        <a href="edit.php">Modifica</a>
                    </div>
                </div>
                <div id="ordini">
                    Ordini
                    <div>
                        <a href="ordini.php">I miei ordini</a>
                        <a href="carrello.php">Carrello</a>
                    </div>
                </div>
                <div id="prodotto">
                    Prodotto
                    <div>
                        <a href="benvenuto.php">Cerca</a>
                        <a href="crea.php">Crea inserzione</a>
                        <a href="inserzioni.php">Le mie inserzioni</a>
                    </div>
                </div>
                <div id="contatti"><a href="contatti.php">Contatti</a></div>
                <div id="logout"><a href="funzioni/logout.php">Esci</a></div>
            </div>
        </div>
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
        </div>
    </body>
</html>
<?php
}
else{
    echo ACCEDI1;
}
?>