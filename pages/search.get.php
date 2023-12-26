<?php

namespace EcommerceTest\Pages;

use Dotenv\Dotenv;
use Dotenv\Exception\InvalidFileException;
use EcommerceTest\Objects\AdvancedSearch;
use EcommerceTest\Pages\Partials\Footer;
use EcommerceTest\Pages\Partials\NavbarLogged;
use Exception;
use EcommerceTest\Interfaces\Constants as C;
use EcommerceTest\Interfaces\Messages as Msg;

/**
 * Search products result page
 */
class SearchGet{

    public static function content(array $params): array{
        $code = 200;
        $done = true;
        $html = <<<HTML
<html lang="it">
    <head>
        <title>Ricerca</title>
        <meta charset="utf-8">
        <link rel="stylesheet" href="{$params['paths']['css']['REL_SEARCH_CSS']}">
        <link rel="stylesheet" href="{$params['paths']['css']['REL_BOOTSTRAP_CSS']}">
        <link rel="stylesheet" href="{$params['paths']['css']['REL_JQUERY_CSS']}">
        <link rel="stylesheet" href="{$params['paths']['css']['REL_JQUERYTHEME_CSS']}">
        <link rel="stylesheet" href="{$params['paths']['css']['REL_FOOTER_CSS']}">
        <script src="{$params['paths']['js']['REL_JQUERY_JS']}"></script>
        <script src="{$params['paths']['js']['REL_JQUERYUI_JS']}"></script>
        <script src="{$params['paths']['js']['REL_POPPER_JS']}"></script>
        <script src="{$params['paths']['js']['REL_BOOTSTRAP_JS']}"></script>
        <script src="{$params['paths']['js']['REL_FOOTER_JS']}"></script>
        <script type="module" src="{$params['paths']['js']['REL_LOGOUT_JS']}"></script>
HTML;
        if(file_exists('partials/privacy.php') && is_file('partials/privacy.php')){
            $html .= call_user_func('cookieBanner');
        }
        $html .= <<<HTML
    </head>
    <body>
HTML;
        $html .= NavbarLogged::content($params);
        $session = $params['session'];
        try{
            $dotenv = Dotenv::createImmutable(__DIR__.'/../');
            $dotenv->load();
            $user = unserialize($session['utente']);
            $asData = [ 'user' => $user ];
            $asData = array_merge($asData,$params['get']);
            $advancedSearch = new AdvancedSearch($asData);
            $html .= $advancedSearch->getHtmlTable();
        }catch(InvalidFileException $e){
            $code = 400;
            $done = false;
            $message = $e->getMessage();
            $html .= <<<HTML
<div id="null" class="alert alert-danger text-center my-5" role="alert">{$message}</div>
HTML;
        }catch(Exception $e){
            $code = 500;
            $done = false;
            $message = Msg::ERR_ADVANCEDSEARCH;
            $html .= <<<HTML
<div id="null" class="alert alert-danger text-center my-5" role="alert">{$message}</div>
HTML;
         }
        $html .= Footer::content();
        $html .= <<<HTML
    </body>
</html>        
HTML;
        return [ C::KEY_CODE => $code, C::KEY_DONE => $done, C::KEY_HTML => $html ];
    }
}
?>