<?php
session_start();
require_once("funzioni/const.php");

if(isset($_SESSION['logged'],$_SESSION['utente'],$_SESSION['welcome']) && $_SESSION['welcome'] != '' && $_SESSION['logged'] === true){
    $risposta['title'] = 'Pagamento cancellato';
    $risposta['msg'] = 'La tua transazione Paypal è stata cancellata<br><a href="benvenuto.php">Torna alla pagina principale</a>;'
?>
<?php
}
else{
    $risposta['title'] = 'Login';
    $risposta['msg'] = ACCEDI1;
}
?>
<!DOCTYPE html>
<html lang="it">
    <head>
        <title><?php echo $risposta['title'];?></title>
        <meta charset="utf-8">
    </head>
    <body>
<?php echo $risposta['msg']; ?>
    </body>
</html>