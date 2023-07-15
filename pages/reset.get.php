<?php

namespace EcommerceTest\Pages;

use Dotenv\Dotenv;
use EcommerceTest\Objects\Utente;
use EcommerceTest\Pages\Partials\NavbarGuest;
use Exception;
use EcommerceTest\Interfaces\Constants as C;
use EcommerceTest\Pages\Partials\Footer;

/**
 * HTML reset form class
 */
class ResetGet{

    public static function content(array $params): string{
        $html = <<<HTML
<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Recupera password</title>
        <link rel="stylesheet" href="{$params['paths']['css']['REL_BOOTSTRAP_CSS']}">
        <link rel="stylesheet" href="{$params['paths']['css']['REL_JQUERY_CSS']}" >
        <link rel="stylesheet" href="{$params['paths']['css']['REL_JQUERYTHEME_CSS']}">
        <link rel="stylesheet" href="{$params['paths']['css']['REL_FOOTER_CSS']}">
        <script src="{$params['paths']['js']['REL_JQUERY_JS']}"></script>
        <script src="{$params['paths']['js']['REL_JQUERYUI_JS']}"></script>
        <script src="{$params['paths']['js']['REL_BOOTSTRAP_JS']}"></script>
        <script src="{$params['paths']['js']['REL_FOOTER_J']}"></script>
        <script type="module" src="{$params['paths']['js']['REL_RESET_JS']}"></script>
HTML;
        if(file_exists('partials/privacy.php') && is_file('partials/privacy.php')){
            $html .= call_user_func('cookieBanner');
        }
        $html .= <<<HTML
    </head>
    <body>
HTML;
        $html .= NavbarGuest::content();
        $request = $params['request'];
        $regex = '/^[a-z0-9]{64}$/i';
        if(isset($request['codReset']) && preg_match($regex,$request['codReset'])){
            try{
                $dotenv = Dotenv::createImmutable(__DIR__."/../");
                $dotenv->safeLoad();
                $codReset = $request['codReset'];
                $time = time()-C::GENERATED_LINK_TIME;
                $user = new Utente([]);
                $exists = $user->Exists("`cambioPwd` = '$codReset' AND `dataCambioPwd` >= '$time'");
                if($exists == 1)
                    $html .= static::getPasswordRecoveryForm($codReset);
                else $html .= "Codice non valido";
            }catch(Exception $e){
                $html .= "Errore sconosciuto";
            }
        }
        else $html .= 'Formato codice non corretto';
        $html .= Footer::content();
        return $html;
    }

    /**
     * Get the password recovery form HTML
     * @param string $codReset
     * @return string
     */
    private static function getPasswordRecoveryForm(string $codReset): string{
        return <<<HTML
<fieldset id="f1">
    <legend>Recupero password</legend>
    <h2>Inserisci la nuova password</h2>
    <form action="funzioni/pRecovery.php" method="post" id="fRecupera">
        <div class="container">
            <div class="row my-5">
                <div class="col-12 col-md-5 col-lg-3">
                    <label for="nuova" class="form-label">Nuova password</label>
                </div>
                <div class="col-12 col-md-7 col-lg-9 mt-2 mt-md-0">
                    <input type="password" id="nuova" class="form-control" name="nuova">
                </div>
            </div>
            <div class="row my-5">
                <div class="col-12 col-md-5 col-lg-3">
                    <label for="confNuova" class="form-label">Conferma nuova password</label>
                </div>
                <div class="col-12 col-md-7 col-lg-9 mt-2 mt-md-0">
                    <input type="password" id="confNuova" class="form-control" name="confNuova">
                </div>
            </div>
            <div class="row my-5">
                <div class="col-12">
                    <input type="checkbox" id="showPass" class="form-check-input">
                    <label for="showPass" class="ms-2">Mostra password</label>
                </div>
            </div>
            <div class="row my-5">
                <div class="col-12 d-flex justify-content-center align-items-center">
                    <button type="submit" id="conferma" class="btn btn-primary">CONFERMA</button>
                    <div id="spinner" class="spinner-border ms-2 invisible" role="status">
                            <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" id="chiave" name="chiave" value="{$codReset}">
    </form>
</fieldset>
HTML;
    }
}
?>