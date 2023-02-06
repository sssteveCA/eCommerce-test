<?php

session_start();

use EcommerceTest\Objects\Privacy\TermsAndConditions;
use EcommerceTest\Interfaces\Paths as P;

require_once("interfaces/paths.php");
require_once("partials/privacy.php");
require_once("partials/navbar.php");
require_once("objects/privacy/termsandconditions.php");
require_once("partials/footer.php");

$bsCss = P::REL_BOOTSTRAP_CSS;
$jQueryCss = P::REL_JQUERY_CSS;
$jQueryUiCss = P::REL_JQUERYTHEME_CSS;
$footerCss = P::REL_FOOTER_CSS;
$popperJs = P::REL_POPPER_JS;
$bsJs = P::REL_BOOTSTRAP_JS;
$jQueryJs = P::REL_JQUERY_JS;
$jQueryUiJs = P::REL_JQUERYUI_JS;
$footerJs = P::REL_FOOTER_JS;
$logoutJs = P::REL_LOGOUT_JS;

$loggedCond = (isset($_SESSION['logged'],$_SESSION['utente'],$_SESSION['welcome']) && $_SESSION['welcome'] != '' && $_SESSION['logged'] === true);

$html = <<<HTML
<!DOCTYPE html>
<html lang="it">
    <head>
        <title>Termini e condizioni</title>
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

if($loggedCond) $html .= <<<HTML
<script type="module" src="{$logoutJs}"></script>
HTML;

$html .= cookieBanner();
$html .= <<<HTML
	</head>
	<body>
HTML;

if($loggedCond) $html .= menu($_SESSION['welcome']);

$html .= TermsAndConditions::getDocument();
$html .= footer();
$html .= <<<HTML
	</body>
</html>
HTML;

echo $html;
?>