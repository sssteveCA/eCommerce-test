<?php
session_start();

/* require_once('objects/utente.php');
require_once('objects/prodotto.php');
require_once('objects/ordine.php'); */
require_once('navbar.php');
require_once("funzioni/const.php");

if(isset($_SESSION['logged'],$_SESSION['utente'],$_SESSION['welcome']) && $_SESSION['welcome'] != '' && $_SESSION['logged'] === true){
?>
<!DOCTYPE html>
<html lang="it">
    <head>
        <title>Carrello</title>
        <meta charset="utf-8">
        <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
        <!-- <link rel="stylesheet" href="css/utente.css" type="text/css"> -->
        <link rel="stylesheet" href="css/carrello.css" type="text/css">
        <link rel="stylesheet" href="jqueryUI/jquery-ui.min.css" type="text/css">
        <link rel="stylesheet" href="jqueryUI/jquery-ui.theme.min.css" type="text/css">
        <script src="js/jquery-3.5.1.min.js"></script>
        <script src="jqueryUI/jquery-ui.min.js"></script>
        <script src="bootstrap/js/bootstrap.min.js"></script>
        <script src="js/dialog/dialog.js"></script>
        <script src="js/carrello.js"></script>
        <script src="js/logout.js"></script>
        <script src="//www.paypalobjects.com/api/checkout.js"></script>
    </head>
    <body>
        <?php echo menu($_SESSION['welcome']);?>
        <div id="carrello">
<?php
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