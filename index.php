<?php

use EcommerceTest\Pages\HomePage;
use EcommerceTest\Interfaces\Paths as P;
use EcommerceTest\Pages\RegisterGet;

session_start();

require_once('vendor/autoload.php');

/* echo '<pre>';
var_dump($_SERVER);
echo '</pre>'; */

$logged = (isset($_SESSION['logged'],$_SESSION['utente'],$_SESSION['welcome']) && $_SESSION['welcome'] != '' && $_SESSION['logged'] === true);
if($logged){

}
else{
    if($_SERVER['REQUEST_METHOD'] == 'GET'){
    $uri = $_SERVER['REQUEST_URI'];
    if($uri == '/'){
        $params = [
            'paths' => [
                'css' => [
                    'REL_BOOTSTRAP_CSS' => P::REL_BOOTSTRAP_CSS, 'REL_FOOTER_CSS' => P::REL_FOOTER_CSS, 'REL_JQUERY_CSS' => P::REL_JQUERY_CSS, 'REL_LOGINTO_CSS' => P::REL_LOGINTO_CSS, 
                ],
                'js' => [
                    'REL_BOOTSTRAP_JS' => P::REL_BOOTSTRAP_JS, 'REL_FOOTER_JS' =>  P::REL_FOOTER_JS, 'REL_JQUERY_JS' => P::REL_JQUERY_JS, 
                    'REL_LOGINTO_JS' => P::REL_LOGINTO_JS
                ],
            ]
        ];
        echo HomePage::content($params);
    }
    else if($uri == '/register'){
        $params = [
            'paths' => [
                'css' => [
                    'REL_BOOTSTRAP_CSS' => P::REL_BOOTSTRAP_CSS, 'REL_FOOTER_CSS' => P::REL_FOOTER_CSS, 'REL_JQUERY_CSS' => P::REL_JQUERY_CSS, 'REL_JQUERYTHEME_CSS' => P::REL_JQUERYTHEME_CSS, 'REL_LOGINTO_CSS' => P::REL_LOGINTO_CSS, 'REL_SUBSCRIBE_CSS' => P::REL_SUBSCRIBE_CSS
                ],
                'js' => [
                    'REL_BOOTSTRAP_JS' => P::REL_BOOTSTRAP_JS, 'REL_DIALOG_MESSAGE_JS' => P::REL_DIALOG_MESSAGE_JS, 'REL_FOOTER_JS' =>  P::REL_FOOTER_JS, 'REL_JQUERY_JS' => P::REL_JQUERY_JS, 'REL_JQUERYUI_JS' => P::REL_JQUERYUI_JS, 'REL_LOGINTO_JS' => P::REL_LOGINTO_JS, 'REL_SUBSCRIBE_JS' => P::REL_SUBSCRIBE_JS
                ],
            ]
        ];
        echo RegisterGet::content($params);
    }
    else{

    }
}
}



?>