<?php

namespace EcommerceTest\Pages\Partials;

class NavbarGuest{

    public static function content(array $params = []): string{
        $html = <<<HTML
<nav id="container" class="navbar navbar-expand-md navbar-light bg-light">
    <div id="menu" class="container-fluid">
        <a class="navbar-brand" href="#"></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-content" aria-controls="navbar-content" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div id="navbar-content" class="collapse navbar-collapse">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 flex-grow-1">
                <li id="home" class="nav-item flex-fill text-center">
                    <a class="nav-link" href="/">Home</a>
                </li>
                <li id="contatti" class="nav-item flex-fill text-center">
                    <a class="nav-link" href="/contacts_1">Contatti</a>
                </li>
HTML;
        $exist = (file_exists(__DIR__."/../../privacy_policy.php") && file_exists(__DIR__."/../../cookie_policy.php") && file_exists(__DIR__."/../../terms.php"));
        $areFiles = (is_file(__DIR__."/../privacy_policy.php") && is_file(__DIR__."/../../cookie_policy.php") && is_file(__DIR__."/../../terms.php"));
        if($exist && $areFiles){
            $html .= <<<HTML
            <li class="nav-item dropdown flex-fill text-center">
                <a id="navbar-orders" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Informativa</a>
                <ul class="dropdown-menu" aria-labelledby="navbar-orders">
                    <li><a class="dropdown-item" href="/privacy_policy.php">Privacy Policy</a></li>
                    <li><a class="dropdown-item" href="/cookie_policy.php">Cookie Policy</a></li>
                    <li><a class="dropdown-item" href="/terms.php">Termini del servizio</a></li>
                </ul>
            </li>
        HTML;
        }//if($exist && $areFiles){
        $html .= <<<HTML
        </ul>
    </div>
</nav>
HTML;
        return $html;
    }
}

?>