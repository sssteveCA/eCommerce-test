<?php

session_start();
if(isset($_SESSION['user'],$_SESSION['logged']) && $_SESSION['user'] != '' && $_SESSION['logged']){
    header('location: benvenuto.php');
}
else
{
?>
<!DOCTYPE html>
<html lang="it">
    <head>
        <title>Accedi</title>
        <meta charset="utf-8">
        <link rel="stylesheet" href="css/accedi.css" type="text/css">
        <script src="js/jquery-3.5.1.min.js"></script>
        <script src="js/accedi.js"></script>
    </head>
    <body>
        <!-- < ?php echo password_hash('123456',PASSWORD_DEFAULT); ?><br> -->
        <form id="formLogin" method="post" action="funzioni/login.php">
            <div>
                <label for="nome">Email</label>
                <input type="email" id="email" name="email">
            </div>
            <div>
                <label for="password">Password</label>
                <input type="password" id="password" name="password">
            </div>
            <div>
                <input type="checkbox" id="show">
                <label for="show">Mostra password</label>
            </div>
            <div>              
                <input type="hidden" id="campo" name="campo" value="email">                
                <input type="submit" id="submit" value="ACCEDI">
            </div>
        </form>
        <div>
            Non hai ancora un account? <a href="registrati.php">Registrati</a>
        </div>
        <div>
            Hai dimenticato la password? <a href="recupera.php">Clicca qui</a>
        </div>
    </body>
</html>
<?php
}
?>