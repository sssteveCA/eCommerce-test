<?php
session_start();
require_once("funzioni/const.php");
require_once('navbar.php');

if(isset($_SESSION['logged'],$_SESSION['utente'],$_SESSION['welcome']) && $_SESSION['welcome'] != '' && $_SESSION['logged'] === true){
?>
<!DOCTYPE html>
<html lang="it">
    <head>
        <title>Benvenuto</title>
        <meta charset="utf-8">
        <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
        <!-- <link rel="stylesheet" href="css/utente.css"> -->
        <link rel="stylesheet" href="css/benvenuto/benvenuto.min.css">
        <link rel="stylesheet" href="jqueryUI/jquery-ui.min.css">
        <link rel="stylesheet" href="jqueryUI/jquery-ui.theme.min.css">
        <script src="js/jquery-3.5.1.min.js"></script>
        <script src="jqueryUI/jquery-ui.min.js"></script>
        <script src="bootstrap/js/bootstrap.min.js"></script>
        <script src="js/dialog/dialog.js"></script>
        <script src="js/benvenuto.js"></script>
        <script src="js/logout.js"></script>
    </head>
    <body>
    
        <?php echo menu($_SESSION['welcome']);?>
        <div id="search" class="d-flex flex-column flex-sm-row flex-grow-1">
            <form id="fSearch" class="flex-fill d-flex flex-column flex-sm-row justify-content-center justify-content-sm-start align-items-center" method="get" action="ricerca.php">
                <input type="text" id="ricerca" name="ricerca">
                <input type="submit" id="submit" class="btn btn-primary" value="RICERCA">
            </form>
            <p id="rAvanzata" class="flex-fill d-flex justify-content-center"><a href="avanzata.php">Ricerca avanzata</a></p>
        </div>
    </body>
</html>
<?php
}
else{
    echo ACCEDI1;
}
?>