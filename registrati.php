<?php

use EcommerceTest\Interfaces\Paths as P;

session_start();

require_once('interfaces/paths.php');
@include_once('partials/privacy.php');
require('footer.php');

if(isset($_SESSION['user'],$_SESSION['logged']) && $_SESSION['user'] != '' && $_SESSION['logged']){
    echo '<a href="logout.php">Esci </a> dall\' account per registrarti';
}
else{
?>
<!DOCTYPE html>
<html lang="it">
    <head>
        <title>Registrati</title>
        <meta charset="utf-8">
        <link rel="stylesheet" href="<?php echo P::REL_SUBSCRIBE_CSS; ?>">
        <link rel="stylesheet" href="<?php echo P::REL_BOOTSTRAP_CSS; ?>">
        <link rel="stylesheet" href="<?php echo P::REL_FOOTER_CSS; ?>">
        <link rel="stylesheet" href="<?php echo P::REL_JQUERY_CSS; ?>" >
        <link rel="stylesheet" href="<?php echo P::REL_JQUERYTHEME_CSS; ?>" >
        <script src="<?php echo P::REL_JQUERY_JS; ?>"></script>
        <script src="<?php echo P::REL_JQUERYUI_JS; ?>"></script>
        <script src="<?php echo P::REL_BOOTSTRAP_JS; ?>"></script>
        <script src="<?php echo P::REL_FOOTER_JS; ?>"></script>
        <script type="module" src="<?php echo P::REL_DIALOG_MESSAGE_JS; ?>"></script>
        <script type="module" src="<?php echo P::REL_SUBSCRIBE_JS; ?>"></script>
        <?php 
            if(file_exists('partials/privacy.php') && is_file('partials/privacy.php')){
                echo call_user_func('cookieBanner');
            }
         ?>
    </head>
    <body>
        <div class="my-container">
            <div class="header d-flex align-items-center py-2">
                <h1 class="w-100 text-center">Registrati</h1>
            </div>
            <div id="indietro">
                <a href="index.php"><img src="img/altre/indietro.png" alt="indietro" title="indietro"></a>
                <a href="index.php">Indietro</a>
            </div>
            <form id="formReg" method="post" action="funzioni/nuovoAccount.php">
                <div>
                    <label for="nome" class="form-label">Nome</label>
                    <input type="text" id="nome" class="form-control" name="nome" required>
                </div>
                <div>
                    <label for="cognome" class="form-label">Cognome</label>
                    <input type="text" id="cognome" class="form-control" name="cognome" required>
                </div>
                <div>
                    <label for="nascita" class="form-label">Data di nascita</label>
                    <input type="date" id="nascita" class="form-control" name="nascita" min="1920-01-01" max="2001-12-31" required>
                </div>
                <div class="radios-div d-flex">
                    <div class="d-flex align-items-center me-4">
                        Genere
                    </div>
                    <div class="radios d-flex">
                        <div>
                            <input type="radio" id="m" name="sesso" value="M" checked>
                            <label for="m">Maschio</label>
                        </div>
                        <div>
                            <input type="radio" id="f" name="sesso" value="F">
                            <label for="f">Femmina</label>
                        </div>
                    </div>
                </div>
                <div>
                    <label for="indirizzo" class="form-label">Indirizzo</label>
                    <input type="text" id="indirizzo" class="form-control" name="indirizzo" required>
                </div>
                <div>
                    <label for="numero" class="form-label">Numero civico</label>
                    <input type="text" id="numero" class="form-control" name="numero" required>
                </div>
                <div>
                    <label for="citta" class="form-label">Residente a</label>
                    <input type="text" id="citta" class="form-control" name="citta" required>
                </div>
                <div>
                    <label for="cap" class="form-label">CAP</label>
                    <input type="text" id="cap" class="form-control" name="cap" required>
                </div>
                <div>
                    <label for="user" class="form-label">Nome utente</label>
                    <input type="text" id="user" class="form-control" name="user" required>
                </div>
                <div>
                    <label for="email" class="form-label">Email business*</label>
                    <input type="email" id="paypalMail" class="form-control" name="paypalMail">
                </div>
                <div>
                    <label for="email" class="form-label">ID venditore*</label>
                    <input type="email" id="clientId" class="form-control" name="clientId">
                </div>
                <div>
                    <label for="email" class="form-label">Indirizzo mail</label>
                    <input type="email" id="email" class="form-control" name="email" required>
                </div>
                <div>
                    <label for="password" class="form-label">Password</label>
                    <input type="password" id="password" class="form-control" name="password" required>
                </div>
                <div>
                    <label for="confPass" class="form-label">Conferma Password</label>
                    <input type="password" id="confPass" class="form-control" name="confPass" required>
                </div>
                <div>
                    <input type="checkbox" id="show" class="form-check-input">
                    <label for="show" class="form-check-label">Mostra password</label>
                </div>
                <div class="buttons d-flex justify-content-evenly">
                    <div class="d-flex align-items-center">
                        <button type="submit" id="submit" class="btn btn-primary">REGISTRATI</button>
                        <div id="spinner" class="spinner-border ms-2 invisible" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                    <div>
                        <button type="reset" id="reset" class="btn btn-danger">ANNULLA</button>
                    </div>
                </div>
                <p>*Richiesti quando l'utente deve caricare le inserzioni</p>
            </form>
        </div>
        <?php echo footer(); ?>
    </body>
</html>
<?php
}
?>