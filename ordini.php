<?php
session_start();

require_once('navbar.php');
require_once('funzioni/functions.php');
require_once("funzioni/const.php");

//se un'utente ha effettuato il login
if(isset($_SESSION['logged'],$_SESSION['utente'],$_SESSION['welcome']) && $_SESSION['welcome'] != '' && $_SESSION['logged'] === true){
    $utente = unserialize($_SESSION['utente']);
?>
<!DOCTYPE html>
<html lang="it">
    <head>
        <title>Ordini</title>
        <meta charset="utf-8">
        <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
        <!-- <link rel="stylesheet" href="css/utente.css"> -->
        <link rel="stylesheet" href="css/ordini.css">
        <link rel="stylesheet" href="jqueryUI/jquery-ui.min.css">
        <link rel="stylesheet" href="jqueryUI/jquery-ui.theme.min.css">
        <script src="bootstrap/js/bootstrap.min.js"></script>
        <script src="js/jquery-3.5.1.min.js"></script>
        <script src="jqueryUI/jquery-ui.min.js"></script>
        <script src="js/dialog/dialog.js"></script>
        <script src="js/ordini.js"></script>
        <script src="js/logout.js"></script>
    </head>
    <body>
        <?php echo menu($_SESSION['welcome']);?>
        <div id="ordiniT">
<?php
/*$ordini = getOrdini($_SESSION['user']);
if($ordini !== false){
    echo $ordini.'<br>';
}*/
?>
        </div>
    </body>
</html>
<?php
}
else{
    echo ACCEDI1;
}
?>