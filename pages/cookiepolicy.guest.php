<?php

namespace EcommerceTest\Pages;

use EcommerceTest\Objects\Privacy\CookiePolicy;
use EcommerceTest\Pages\Partials\Footer;
use EcommerceTest\Pages\Partials\NavbarGuest;
use EcommerceTest\Pages\Partials\NavbarLogged;

/**
 * Cookie policy page for guest users
 */
class CookiePolicyGuest{

    public static function content(array $params): string{
        $html = <<<HTML
<!DOCTYPE html>
<html lang="it">
    <head>
        <title>Cookie Policy</title>
        <meta charset="utf-8">
	<link rel="stylesheet" href="{$params['paths']['css']['REL_BOOTSTRAP_CSS']}">
	<link rel="stylesheet" href="{$params['paths']['css']['REL_FOOTER_CSS']}">
        <link rel="stylesheet" href="{$params['paths']['css']['REL_JQUERY_CSS']}">
	<link rel="stylesheet" href="{$params['paths']['css']['REL_JQUERYTHEME_CSS']}">
	<script src="{$params['paths']['js']['REL_BOOTSTRAP_JS']}"></script>
        <script src="{$params['paths']['js']['REL_FOOTER_JS']}"></script>
	<script src="{$params['paths']['js']['REL_JQUERY_JS']}"></script>
        <script src="{$params['paths']['js']['REL_JQUERYUI_JS']}"></script>
        <script src="{$params['paths']['js']['REL_POPPER_JS']}"></script>
HTML;
        $html .= cookieBanner();
        $html .= <<<HTML
	</head>
	<body>
HTML;
        $html .= NavbarGuest::content();
        $html .= CookiePolicy::getDocument();
        $html .= Footer::content();
        $html .= <<<HTML
	</body>
</html>
HTML;
        return $html;
    }
}
?>