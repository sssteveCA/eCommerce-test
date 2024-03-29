<?php
use EcommerceTest\Interfaces\Paths as P;

session_start();

require_once('vendor/autoload.php');

if(isset($_SESSION['logged'],$_SESSION['utente'],$_SESSION['welcome']) && $_SESSION['welcome'] != '' && $_SESSION['logged'] === true){
?>
<!DOCTYPE html>
<html lang="it">
    <head>
        <title>Benvenuto</title>
        <meta charset="utf-8">
        <link rel="stylesheet" href=<?php echo P::REL_WELCOME_CSS; ?>>
        <link rel="stylesheet" href=<?php echo P::REL_BOOTSTRAP_CSS; ?>>
        <link rel="stylesheet" href=<?php echo P::REL_JQUERY_CSS; ?> >
        <link rel="stylesheet" href=<?php echo P::REL_JQUERYTHEME_CSS; ?> >
        <link rel="stylesheet" href=<?php echo P::REL_FOOTER_CSS; ?>>
        <script src=<?php echo P::REL_JQUERY_JS; ?>></script>
        <script src=<?php echo P::REL_JQUERYUI_JS; ?>></script>
        <script src=<?php echo P::REL_POPPER_JS; ?>></script>
        <script src=<?php echo P::REL_BOOTSTRAP_JS; ?>></script>
        <script src=<?php echo P::REL_FOOTER_JS; ?>></script>
        <script type="module" src="<?php echo P::REL_DIALOG_MESSAGE_JS; ?>"></script>
        <script type="module" src="<?php echo P::REL_LOGOUT_JS; ?>"></script>
        <script src=<?php echo P::REL_WELCOME_JS; ?>></script>
        <?php 
            if(file_exists('partials/privacy.php') && is_file('partials/privacy.php')){
                echo call_user_func('cookieBanner');
            }
         ?>
    </head>
    <body>
    
        <?php echo menu($_SESSION['welcome']);?>
        <div id="search" class="d-flex flex-column flex-sm-row flex-grow-1">
            <form id="fSearch" class="flex-fill d-flex flex-column flex-sm-row justify-content-center justify-content-sm-start align-items-center" method="get" action="ricerca.php">
                <input type="text" id="ricerca" name="ricerca">
                <input type="submit" id="submit" class="btn btn-primary" value="RICERCA">
            </form>
            <p id="rAvanzata" class="flex-fill d-flex justify-content-center"><a href="avanzata.php">Ricerca avanzata</a></p>
        </div>
        <div class="carousel-container">
            <div id="homepage-carousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active" data-bs-interval="3000">
                        <img src="img/altre/homepage_carousel/ecommerce-background1.jpg" class="d-block" alt="ECommerce">
                    </div>
                    <div class="carousel-item" data-bs-interval="3000">
                        <img src="img/altre/homepage_carousel/ecommerce-background2.jpg" class="d-block" alt="ECommerce">
                    </div>
                    <div class="carousel-item" data-bs-interval="3000">
                        <img src="img/altre/homepage_carousel/ecommerce-background3.jpg" class="d-block" alt="ECommerce">
                    </div>
                    <div class="carousel-item" data-bs-interval="3000">
                        <img src="img/altre/homepage_carousel/ecommerce-background4.jpg" class="d-block" alt="ECommerce">
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
        <?php echo footer(); ?>
    </body>
</html>
<?php
}
else{
    echo ACCEDI1;
}
?>