<?php

namespace EcommerceTest\Pages;

use EcommerceTest\Objects\Privacy\CookiePolicy;
use EcommerceTest\Objects\Privacy\TermsAndConditions;
use EcommerceTest\Pages\Partials\Footer;
use EcommerceTest\Pages\Partials\NavbarGuest;
use EcommerceTest\Pages\Partials\NavbarLogged;

/**
 * Terms page for logged users
 */
class TermsLogged{

    public static function content(array $params): string{
        $html = <<<HTML
<!DOCTYPE html>
<html lang="it">
    <head>
        <title>Termini e condizioni</title>
        <meta charset="utf-8">
	    <link rel="stylesheet" href="{$params['paths']['css']['REL_BOOTSTRAP_CSS']}">
	    <link rel="stylesheet" href="{$params['paths']['css']['REL_FOOTER_CSS']}">
        <link rel="stylesheet" href="{$params['paths']['css']['REL_JQUERY_CSS']}">
	    <link rel="stylesheet" href="{$params['paths']['css']['REL_JQUERYTHEME_CSS']}">
	    <script src="{$params['paths']['js']['REL_JQUERY_JS']}"></script>
        <script src="{$params['paths']['js']['REL_JQUERYUI_JS']}"></script>
        <script src="{$params['paths']['js']['REL_POPPER_JS']}"></script>
        <script src="{$params['paths']['js']['REL_BOOTSTRAP_JS']}"></script>
        <script src="{$params['paths']['js']['REL_FOOTER_JS']}"></script>
        <script src="{$params['paths']['js']['REL_LOGOUT_JS']}"></script>
HTML;
        $html .= cookieBanner();
        $html .= <<<HTML
	</head>
	<body>
HTML;
        $html .= NavbarLogged::content($params);
        $html .= TermsAndConditions::getDocument();
        $html .= Footer::content();
        $html .= <<<HTML
	</body>
</html>
HTML;
        return $html;
    }
}
?>