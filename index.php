<?php

use EcommerceTest\Interfaces\PageResources;
use EcommerceTest\Pages\HomePage;
use EcommerceTest\Interfaces\Paths as P;
use EcommerceTest\Interfaces\Constants as C;
use EcommerceTest\Pages\HomeLogged;
use EcommerceTest\Pages\HomePageLogged;
use EcommerceTest\Pages\RecoveryGet;
use EcommerceTest\Pages\RegisterGet;
use EcommerceTest\Response\Login;
use EcommerceTest\Response\RegisterPost;

session_start();

require_once('vendor/autoload.php');

/* echo '<pre>';
var_dump($_SERVER);
echo '</pre>'; */

$ajax = (isset($post[C::KEY_AJAX]) && $post[C::KEY_AJAX] == true);
$logged = (isset($_SESSION['logged'],$_SESSION['utente'],$_SESSION['welcome']) && $_SESSION['welcome'] != '' && $_SESSION['logged'] === true);
$uri = $_SERVER['REQUEST_URI'];


if($_SERVER['REQUEST_METHOD'] == 'GET'){
    if($uri == '/'){
        if($logged){
            $params = PageResources::HOME_GET_LOGGED;
            $params['menu']['welcome'] = $_SESSION['welcome'];
            echo HomePageLogged::content($params);
        }
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
    if($_SERVER['CONTENT_TYPE'] == "application/x-www-form-urlencoded"){
        $post = $_POST;
    }
    else{
        $data = file_get_contents("php://input");
        $post = json_decode($data,true);
    }
    if($uri == '/login'){
        if($logged) header("Location: /");
        else{
            ob_start();
            $params = ['post' => $post];
            $response = Login::content($params);
            http_response_code($response[C::KEY_CODE]);
            if($response['redirect']['do']) header($response['redirect']['url']);
            echo $response[C::KEY_MESSAGE];
        }
    }//if($uri == '/login'){
    else if($uri == '/register'){
        if($logged) header("Location: /");
        else{
            ob_start();
            $params = [ 'post' => $post ];
            $response = RegisterPost::content($params);
            http_response_code($response[C::KEY_CODE]);
            if($ajax) echo json_encode($response);
            else {
                if($response['redirect']['do']) header($response['redirect']['url']);
                echo $response[C::KEY_MESSAGE];
            }
        }
    }
}



?>