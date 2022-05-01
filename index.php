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
        <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css" type="text/css">
        <link rel="stylesheet" href="css/accedi/accedi.min.css" type="text/css">
        <script src="bootstrap/js/bootstrap.min.js"></script>
        <script src="js/jquery-3.5.1.min.js"></script>
        <script src="js/accedi.js"></script>
    </head>
    <body>
        <!-- < ?php echo password_hash('123456',PASSWORD_DEFAULT); ?><br> -->
        <div class="my-container d-flex flex-column">
            <form id="formLogin" method="post" action="funzioni/login.php">
                <div class="mb-1">
                    <label for="nome" class="form-label">Email</label>
                    <input type="email" id="email" class="form-control" name="email">
                </div>
                <div class="mb-1">
                    <label for="password">Password</label>
                    <input type="password" id="password" class="form-control" name="password">
                </div>
                <div class="mb-1 form-check d-flex">
                    <input type="checkbox" id="show" class="form-check-input">
                    <label for="show" class="form-check-label">Mostra password</label>
                </div>
                <div>              
                    <input type="hidden" id="campo" name="campo" value="email">                
                    <input type="submit" id="submit"  class="btn btn-primary" value="ACCEDI">
                </div>
            </form>
            <div class="after-form ">
                Non hai ancora un account? <a href="registrati.php">Registrati</a>
            </div>
            <div class="after-form">
                Hai dimenticato la password? <a href="recupera.php">Clicca qui</a>
            </div>
        </div>
    </body>
</html>
<?php
}
?>