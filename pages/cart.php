<?php

namespace EcommerceTest\Pages;
use EcommerceTest\Pages\Partials\Footer;
use EcommerceTest\Pages\Partials\NavbarLogged;

/**
 * Cart page
 */
class Cart{
    public static function content(array $params): string{
        $html = <<<HTML
<!DOCTYPE html>
<html lang="it">
    <head>
        <title>Carrello</title>
        <meta charset="utf-8">
        <link rel="stylesheet" href="{$params['paths']['css']['REL_CART_CSS']}">
        <link rel="stylesheet" href="{$params['paths']['css']['REL_BOOTSTRAP_CSS']}">
        <link rel="stylesheet" href="{$params['paths']['css']['REL_JQUERY_CSS']}" >
        <link rel="stylesheet" href="{$params['paths']['css']['REL_JQUERYTHEME_CSS']}" >
        <link rel="stylesheet" href="{$params['paths']['css']['REL_FOOTER_CSS']}" >
        <script src="{$params['paths']['js']['REL_JQUERY_JS']}"></script>
        <script src="{$params['paths']['js']['REL_JQUERYUI_JS']}"></script>
        <script src="{$params['paths']['js']['REL_POPPER_JS']}"></script>
        <script src="{$params['paths']['js']['REL_BOOTSTRAP_JS']}"></script>
        <script src="{$params['paths']['js']['REL_FOOTER_JS']}"></script>
        <script type="module" src="{$params['paths']['js']['REL_LOGOUT_JS']}"></script>
        <script type="module" src="{$params['paths']['js']['REL_CART_JS']}"></script>
        <script src="https://www.paypalobjects.com/api/checkout.js"></script>
HTML;
        if(file_exists('partials/privacy.php') && is_file('partials/privacy.php')){
            $html .= call_user_func('cookieBanner');
        }
        $html .= <<<HTML
    </head>
    <body>
HTML;
        $html .= NavbarLogged::content($params);
        $html .= <<<HTML
        <div id="ordiniT"></div>
        HTML;
        $html .= Footer::content();
        $html .= <<<HTML
    </body>
</html>        
HTML;
        return $html;
    }
}
?>