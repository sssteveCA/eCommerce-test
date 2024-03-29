<?php
//navgation menu

//$welcome = $_SESSION['welcome']
function menu($welcome){
    $menu = <<<HTML
<nav id="container" class="navbar navbar-expand-md navbar-light bg-light">
    <div id="menu" class="container-fluid">
        <a class="navbar-brand" href="#">{$welcome}</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-content" aria-controls="navbar-content" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div id="navbar-content" class="collapse navbar-collapse">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 flex-grow-1">
                <li id="home" class="nav-item flex-fill text-center">
                    <a class="nav-link" href="/">Home</a>
                </li>
                <li class="nav-item dropdown flex-fill text-center">
                    <a id="navbar-profile" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Profilo</a>
                    <ul class="dropdown-menu" aria-labelledby="navbar-profile">
                        <li><a class="dropdown-item" href="info.php">Informazioni</a></li>
                        <li><a class="dropdown-item" href="edit.php">Modifica</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown flex-fill text-center">
                    <a id="navbar-orders" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Ordini</a>
                    <ul class="dropdown-menu" aria-labelledby="navbar-orders">
                        <li><a class="dropdown-item" href="ordini.php">I miei ordini</a></li>
                        <li><a class="dropdown-item" href="carrello.php">Carrello</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown flex-fill text-center">
                    <a id="navbar-product" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Prodotto</a>
                    <ul class="dropdown-menu" aria-labelledby="navbar-product">
                        <li><a class="dropdown-item" href="/">Cerca</a></li>
                        <li><a class="dropdown-item" href="crea.php">Crea inserzione</a></li>
                        <li><a class="dropdown-item" href="inserzioni.php">Le mie inserzioni</a></li>
                    </ul>
                </li>
                <li id="contatti" class="nav-item flex-fill text-center">
                    <a class="nav-link" href="contatti.php">Contatti</a>
                </li>
HTML;
                $exist = (file_exists(__DIR__."/../privacy_policy.php") && file_exists(__DIR__."/../cookie_policy.php") && file_exists(__DIR__."/../terms.php"));
                $areFiles = (is_file(__DIR__."/../privacy_policy.php") && is_file(__DIR__."/../cookie_policy.php") && is_file(__DIR__."/../terms.php"));
                if($exist && $areFiles){
                    $menu .= <<<HTML
                        <li class="nav-item dropdown flex-fill text-center">
                            <a id="navbar-orders" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Informativa</a>
                            <ul class="dropdown-menu" aria-labelledby="navbar-orders">
                                <li><a class="dropdown-item" href="privacy_policy.php">Privacy Policy</a></li>
                                <li><a class="dropdown-item" href="cookie_policy.php">Cookie Policy</a></li>
                                <li><a class="dropdown-item" href="terms.php">Termini del servizio</a></li>
                            </ul>
                        </li>
HTML;
                }//if($exist && $areFiles){
                $menu .= <<<HTML
                <li id="logout" class="nav-item flex-fill text-center">
                    <a class="nav-link" href="funzioni/logout.php">Esci</a>
                </li>
            </ul>
        </div>
</nav>
HTML;
    return $menu;
}

//menu without bootstrap
//$welcome = $_SESSION['welcome']
function oldMenu($welcome){
    $menu = <<<HTML
    <div id="container">
            <div id="menu">
            <div id="welcome">{$welcome}</div>
            <div id="profilo">
                Profilo
                <div>
                    <a href="info.php">Informazioni</a>
                    <a href="edit.php">Modifica</a>
                </div>
            </div>
            <div id="ordini">
                Ordini
                <div>
                    <a href="ordini.php">I miei ordini</a>
                    <a href="carrello.php">Carrello</a>
                </div>
            </div>
            <div id="prodotto">
                Prodotto
                <div>
                    <a href="/">Cerca</a>
                    <a href="crea.php">Crea inserzione</a>
                    <a href="inserzioni.php">Le mie inserzioni</a>
                </div>
            </div>
            <div id="contatti"><a href="contatti.php">Contatti</a></div>
            <div id="logout"><a href="funzioni/logout.php">Esci</a></div>
        </div>
    </div> 
HTML;
    return $menu;
}

?>