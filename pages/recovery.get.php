<?php

namespace EcommerceTest\Pages;
use EcommerceTest\Pages\Partials\Footer;

/**
 * Password recovery form
 */
class RecoveryGet{

    public static function content(array $params): string{
        $html = <<<HTML
!DOCTYPE html>
<html lang="it">
    <head>
        <title>Recupera password</title>
        <meta charset="utf-8">
        <link rel="stylesheet" href=<?php echo P::REL_RECOVERY_CSS; ?>>
        <link rel="stylesheet" href=<?php echo P::REL_BOOTSTRAP_CSS; ?>>
        <link rel="stylesheet" href=<?php echo P::REL_JQUERY_CSS; ?> >
        <link rel="stylesheet" href=<?php echo P::REL_JQUERYTHEME_CSS; ?> >
        <link rel="stylesheet" href=<?php echo P::REL_FOOTER_CSS; ?>>
        <script src=<?php echo P::REL_JQUERY_JS; ?>></script>
        <script src=<?php echo P::REL_JQUERYUI_JS; ?>></script>
        <script src=<?php echo P::REL_BOOTSTRAP_JS; ?>></script>
        <script src=<?php echo P::REL_FOOTER_JS; ?>></script>
        <script type="module" src=<?php echo P::REL_DIALOG_MESSAGE_JS; ?>></script>
        <script type="module" src=<?php echo P::REL_RECOVERY_JS; ?>></script>
HTML;
        if(file_exists('partials/privacy.php') && is_file('partials/privacy.php')){
            $html .= call_user_func('cookieBanner');
        }
        $html .= <<<HTML
    </head>
    <body>
        <div id="indietro">
                <a href="/"><img src="/img/altre/indietro.png" alt="indietro" title="indietro"></a>
                <a href="/">Indietro</a>
            </div>
        <div class="my-container">
            <fieldset id="dRecupera">
                <legend class="text-center pb-4">Recupera il tuo account</legend>
                <form id="fRecupera" method="post" action="/funzioni/mail.php">
                    <div class="d-flex align-items-center">
                        <label for="email" class="form-label me-3">Email</label>
                        <input type="email" id="email" class="form-control" name="email">
                    </div>
                    <div class="pt-4 d-flex justify-content-center align-items-center">
                        <button type="submit" id="bOk" class="btn btn-primary">OK</button>
                        <div id="spinner" class="spinner-border ms-2 invisible" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </form>
            </fieldset>
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