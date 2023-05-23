<?php

namespace EcommerceTest\Pages;

use EcommerceTest\Objects\Privacy\CookiePolicy;
use EcommerceTest\Pages\Partials\Footer;
use EcommerceTest\Pages\Partials\NavbarGuest;
use EcommerceTest\Pages\Partials\NavbarLogged;

/**
 * Cookie policy page
 */
class CookiePolicyPage{

    public static function content(array $params): string{
        $html = <<<HTML
<!DOCTYPE html>
<html lang="it">
    <head>
        <title>Cookie Policy</title>
        <meta charset="utf-8">
		<link rel="stylesheet" href="{$bsCss}">
		<link rel="stylesheet" href="{$jQueryCss}">
        <link rel="stylesheet" href="{$jQueryUiCss}">
		<link rel="stylesheet" href="{$footerCss}">
		<script src="{$jQueryJs}"></script>
        <script src="{$jQueryUiJs}"></script>
		<script src="{$popperJs}"></script>
        <script src="{$bsJs}"></script>
        <script src="{$footerJs}"></script>
HTML;
        $html .= cookieBanner();
        $html .= <<<HTML
	</head>
	<body>
HTML;
        if($params['logged']) $html .= NavbarLogged::content($params);
        else $html .= NavbarGuest::content();
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