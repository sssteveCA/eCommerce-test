<?php

use EcommerceTest\Interfaces\Paths as P;

require_once("vendor/autoload.php");

session_start();
if(isset($_SESSION['logged'],$_SESSION['utente'],$_SESSION['welcome']) && $_SESSION['welcome'] != '' && $_SESSION['logged'] === true){
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
        <link rel="stylesheet" href=<?php echo P::REL_LOGINTO_CSS; ?>>
        <link rel="stylesheet" href=<?php echo P::REL_FOOTER_CSS; ?>>
        <link rel="stylesheet" href=<?php echo P::REL_BOOTSTRAP_CSS; ?>>
        <link rel="stylesheet" href=<?php echo P::REL_JQUERY_CSS; ?> >
        <link rel="stylesheet" href=<?php echo P::REL_FOOTER_CSS; ?> >
        <script src=<?php echo P::REL_JQUERY_JS; ?>></script>
        <script src=<?php echo P::REL_BOOTSTRAP_JS; ?>></script>
        <script src=<?php echo P::REL_FOOTER_JS; ?>></script>
        <script src=<?php echo P::REL_LOGINTO_JS; ?>></script>
        <?php 
            if(file_exists('partials/privacy.php') && is_file('partials/privacy.php')){
                echo call_user_func('cookieBanner');
            }
         ?>
    </head>
    <body>
        <div class="my-container d-flex flex-column">
            <div class="header d-flex align-items-center py-2">
                <h1 class="w-100 text-center">Accedi</h1>
            </div>
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
                    <button type="submit" id="submit"  class="btn btn-primary">ACCEDI</button>
                    <button type="reset" id="reset" class="btn btn-danger">ANNULLA</button>
                </div>
            </form>
            <div class="after-form ">
                Non hai ancora un account? <a class="ps-1" href="registrati.php">Registrati</a>
            </div>
            <div class="after-form">
                Hai dimenticato la password? <a class="ps-1" href="recupera.php">Clicca qui</a>
            </div>
        </div>
        <?php echo footer(); ?>
    </body>
</html>
<?php
}
?>