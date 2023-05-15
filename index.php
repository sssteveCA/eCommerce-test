<?php

use EcommerceTest\Interfaces\PageResources;
use EcommerceTest\Pages\HomePage;
use EcommerceTest\Interfaces\Paths as P;
use EcommerceTest\Pages\RecoveryGet;
use EcommerceTest\Pages\RegisterGet;

session_start();

require_once('vendor/autoload.php');

echo '<pre>';
var_dump($_SERVER);
echo '</pre>';

$logged = (isset($_SESSION['logged'],$_SESSION['utente'],$_SESSION['welcome']) && $_SESSION['welcome'] != '' && $_SESSION['logged'] === true);
$uri = $_SERVER['REQUEST_URI'];


if($_SERVER['REQUEST_METHOD'] == 'GET'){
    if($uri == '/'){
        if($logged){}
        else{
            echo HomePage::content(PageResources::HOME_GET_GUEST);
        } 
    }
    else if($uri == '/recovery'){
        if($logged) header("Location: /");
        else{
            echo RecoveryGet::content(PageResources::RECOVERY_GET_GUEST);
        }
    }
    else if($uri == '/register'){
        if($logged) header("Location: /");
        else{
            echo RegisterGet::content(PageResources::REGISTER_GET_GUEST);
        } 
    }
    else{

    }
}
else if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $data = file_get_contents("php://input");
    $post = json_decode($data,true);
    if($uri == '/login'){
        if($logged) header("Location: /");
        else{
            ob_start();
        }
    }
}



?>