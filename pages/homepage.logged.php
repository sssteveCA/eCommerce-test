<?php

namespace EcommerceTest\Pages;
use EcommerceTest\Pages\Partials\Footer;
use EcommerceTest\Pages\Partials\NavbarLogged;

/**
 * Home page for logged users
 */
class HomePageLogged{
    public static function content(array $params): string{
        $html = <<<HTML
<!DOCTYPE html>
<html lang="it">
    <head>
        <title>Benvenuto</title>
        <meta charset="utf-8">
        <link rel="stylesheet" href="{$params['paths']['css']['REL_WELCOME_CSS']}">
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
        <script src="{$params['paths']['js']['REL_WELCOME_JS']}"></script>
HTML;
    if(file_exists('../partials/privacy.php') && is_file('../partials/privacy.php')){
        $html .= call_user_func('cookieBanner');
    }
    $html .= <<<HTML
    </head>
    <body>
HTML;
    $html .= NavbarLogged::content($params);
    $html .= <<<HTML
        <div id="search" class="d-flex flex-column flex-sm-row flex-grow-1">
                <form id="fSearch" class="flex-fill d-flex flex-column flex-sm-row justify-content-center justify-content-sm-start align-items-center" method="get" action="ricerca.php">
                    <input type="text" id="ricerca" name="ricerca">
                    <input type="submit" id="submit" class="btn btn-primary" value="RICERCA">
                </form>
                <p id="rAvanzata" class="flex-fill d-flex justify-content-center"><a href="/avanzata.php">Ricerca avanzata</a></p>
            </div>
            <div class="carousel-container">
                <div id="homepage-carousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <div class="carousel-item active" data-bs-interval="3000">
                            <img src="/img/altre/homepage_carousel/ecommerce-background1.jpg" class="d-block" alt="ECommerce">
                        </div>
                        <div class="carousel-item" data-bs-interval="3000">
                            <img src="/img/altre/homepage_carousel/ecommerce-background2.jpg" class="d-block" alt="ECommerce">
                        </div>
                        <div class="carousel-item" data-bs-interval="3000">
                            <img src="/img/altre/homepage_carousel/ecommerce-background3.jpg" class="d-block" alt="ECommerce">
                        </div>
                        <div class="carousel-item" data-bs-interval="3000">
                            <img src="/img/altre/homepage_carousel/ecommerce-background4.jpg" class="d-block" alt="ECommerce">
                        </div>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#homepage-carousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Precedente</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#homepage-carousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Successivo</span>
                    </button>
                </div>
            </div>
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