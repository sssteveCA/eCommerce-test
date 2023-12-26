<?php

namespace EcommerceTest\Pages; 

use Dotenv\Dotenv;
use EcommerceTest\Pages\Partials\Footer;
use EcommerceTest\Pages\Partials\NavbarLogged;
use Exception;

/**
 * Accout info edit page
 */
class Edit{

    public static function content(array $params): string{
        try{
            $dotenv = Dotenv::createImmutable(__DIR__);
            $dotenv->safeLoad();
            $user = unserialize($params['session']['utente']);
            $user = [
                'nome' => $user->getNome(), 'cognome' => $user->getCognome(), 'nascita' => $user->getNascita(),
                'sesso' => $user->getSesso(), 'indirizzo' => $user->getIndirizzo(), 'numero' => $user->getNumero(),
                'citta' => $user->getCitta(), 'cap' => $user->getCap(), 'username' => $user->getUsername(),
                'paypalMail' => $user->getPaypalMail(), 'clientId' => $user->getClientId(),
            ];
            $output = <<<HTML
<div id="container2">
            <h1>Modifica profilo</h1>
            <fieldset id="f1" class="my-3">
                <legend>Modifica username</legend>
                <form id="userEdit" method="post" action="funzioni/editProfile.php">
                    <input type="hidden" id="user" name="user" value="1">
                    <div>
                        <label class="form-label" for="newUser">Nuovo username</label>
                        <input type="text" id="newUser" class="form-control" name="username" value="{$user['username']}">
                    </div>
                    <div>
                        <button type="submit" id="bUser" class="btn btn-primary">OK</button>
                        <button type="reset" class="btn btn-danger">ANNULLA</button>
                    </div>
                </form>
            </fieldset>
            <fieldset id="f2" class="my-3">
                <legend>Modifica password</legend>
                <form id="pwdEdit" method="post" action="funzioni/editProfile.php">
                    <input type="hidden" id="pwd" name="pwd" value="1">
                    <div>
                        <label class="form-label" for="oldPwd">Vecchia password</label>
                        <input type="password" id="oldPwd" class="form-control" name="oPwd">
                    </div>
                    <div>
                        <label class="form-label" for="newPwd">Nuova password</label>
                        <input type="password" id="newPwd" class="form-control" name="nPwd">
                    </div>
                    <div>
                        <label class="form-label" for="confPwd">Conferma nuova password</label>
                        <input type="password" id="confPwd" class="form-control" name="confPwd">
                    </div>
                    <div>
                        <button type="submit" id="bPwd" class="btn btn-primary">OK</button>
                        <button type="reset" class="btn btn-danger">ANNULLA</button>
                    </div>
                </form>
            </fieldset>
            <fieldset id="f3" class="my-3">
                <legend>Dati personali</legend>
                <form id="dataEdit" method="post" action="funzioni/editProfile.php">
                    <input type="hidden" id="pers" name="pers" value="1">
                    <div>
                        <label class="form-label" for="nome">Nome</label>
                        <input type="text" id="nome" class="form-control" name="nome" value="{$user['nome']}">
                    </div>
                    <div>
                        <label class="form-label" for="cognome">Cognome</label>
                        <input type="text" id="cognome" class="form-control" name="cognome" value="{$user['cognome']}">
                    </div>
                    <div>
                        <label class="form-label" for="indirizzo">Indirizzo</label>
                        <input type="text" id="indirizzo" class="form-control" name="indirizzo" value="{$user['indirizzo']}">
                    </div>
                    <div>
                        <label class="form-label" for="numero">Numero</label>
                        <input type="text" id="numero" class="form-control" name="numero" value="{$user['numero']}">
                    </div>
                    <div>
                        <label class="form-label" for="citta">Città</label>
                        <input type="text" id="citta" class="form-control" name="citta" value="{$user['citta']}">
                    </div>
                    <div>
                        <label class="form-label" for="cap">CAP</label>
                        <input type="text" id="cap" class="form-control" name="cap" value="{$user['cap']}">
                    </div>
                    <div>
                        <label class="form-label" for="paypalMail">Email business</label>
                        <input type="text" id="paypalMail" class="form-control" name="paypalMail" value="{$user['paypalMail']}">
                    </div>
                    <div>
                        <label class="form-label" for="clientId">Client ID</label>
                        <input type="text" id="clientId" class="form-control" name="clientId" value="{$user['clientId']}">
                    </div>
                    <div>
                        <button type="submit" id="butente" class="btn btn-primary">OK</button>
                        <button type="reset" class="btn btn-danger">ANNULLA</button>
                    </div>
                </form>
            </fieldset>
        </div>
HTML;
        }catch(Exception $e){
            $output = "";
        }
        $html = <<<HTML
<!DOCTYPE html>
<html lang="it">
    <head>
        <title>Modifica profilo</title>
        <meta charset="utf-8">
        <link rel="stylesheet" href="{$params['paths']['css']['REL_EDIT_CSS']}">
        <link rel="stylesheet" href="{$params['paths']['css']['REL_BOOTSTRAP_CSS']}">
        <link rel="stylesheet" href="{$params['paths']['css']['REL_JQUERY_CSS']}" >
        <link rel="stylesheet" href="{$params['paths']['css']['REL_JQUERYTHEME_CSS']}" >
        <link rel="stylesheet" href="{$params['paths']['css']['REL_FOOTER_CSS']}">
        <script src="{$params['paths']['js']['REL_JQUERY_JS']}"></script>
        <script src="{$params['paths']['js']['REL_JQUERYUI_JS']}"></script>
        <script src="{$params['paths']['js']['REL_POPPER_JS']}"></script>
        <script src="{$params['paths']['js']['REL_BOOTSTRAP_JS']}"></script>
        <script src="{$params['paths']['js']['REL_FOOTER_JS']}"></script>
        <script type="module" src="{$params['paths']['js']['REL_LOGOUT_JS']}"></script>
        <script type="module" src="{$params['paths']['js']['REL_EDIT_JS']}"></script>
HTML;
        if(file_exists('../partials/privacy.php') && is_file('../partials/privacy.php')){
            $html .= call_user_func('cookieBanner');
        }
        $html .= <<<HTML
    </head>
    <body>
HTML;
    $html .= NavbarLogged::content($params);
    $html .= $output;
    $html .= Footer::content();
    $html .= <<<HTML
    </body>
</html>
HTML;
        return $html;
    }
}

?>