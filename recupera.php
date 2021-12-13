<?php
session_start();
if(isset($_SESSION['mail'],$_SESSION['user'],$_SESSION['logged']) && $_SESSION['mail'] != '' && $_SESSION['user'] != '' && $_SESSION['logged'] === true){
    header('location: benvenuto.php');
}
//per il recupero della password non ci devono essere sessioni aperte
else{
?>
<!DOCTYPE html>
<html lang="it">
    <head>
        <title>Recupera password</title>
        <meta charset="utf-8">
        <link rel="stylesheet" href="css/recupera.css" type="text/css">
        <link rel="stylesheet" href="jqueryUI/jquery-ui.min.css" type="text/css">
        <link rel="stylesheet" href="jqueryUI/jquery-ui.theme.min.css" type="text/css">
        <script src="js/jquery-3.5.1.min.js"></script>
        <script src="jqueryUI/jquery-ui.min.js"></script>
        <script src="js/dialog/dialog.js"></script>
        <script src="js/recupera.js"></script>
    </head>
    <body>
        <div id="indietro">
            <a href="accedi.php"><img src="img/altre/indietro.png" alt="indietro" title="indietro"></a>
            <a href="accedi.php">Indietro</a>
        </div>
        <fieldset id="dRecupera">
            <legend>Recupera il tuo account</legend>
            <form id="fRecupera" method="post" action="funzioni/mail.php">
                <div>
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email">
                </div>
                <div>
                    <input type="submit" id="bOk" value="OK">
                </div>
            </form>
        </fieldset>
    </body>
</html>
<?php
}
?>