<?php

use EcommerceTest\Interfaces\PageResources;
use EcommerceTest\Interfaces\Paths as P;
use EcommerceTest\Interfaces\Constants as C;
use EcommerceTest\Interfaces\Messages as Msg;
use EcommerceTest\Pages\Cart;
use EcommerceTest\Pages\ContactsGuest;
use EcommerceTest\Pages\ContactsLogged;
use EcommerceTest\Pages\CookiePolicyGuest;
use EcommerceTest\Pages\CookiePolicyLogged;
use EcommerceTest\Pages\Edit;
use EcommerceTest\Pages\HomeLogged;
use EcommerceTest\Pages\HomePageGuest;
use EcommerceTest\Pages\HomePageLogged;
use EcommerceTest\Pages\Info;
use EcommerceTest\Pages\Insertion;
use EcommerceTest\Pages\Insertions;
use EcommerceTest\Pages\Orders;
use EcommerceTest\Pages\PrivacyPolicyGuest;
use EcommerceTest\Pages\PrivacyPolicyLogged;
use EcommerceTest\Pages\RecoveryGet;
use EcommerceTest\Pages\RegisterGet;
use EcommerceTest\Pages\ResetGet;
use EcommerceTest\Pages\TermsGuest;
use EcommerceTest\Pages\TermsLogged;
use EcommerceTest\Response\ContactsPost;
use EcommerceTest\Response\EditPassword;
use EcommerceTest\Response\EditUsername;
use EcommerceTest\Response\Login;
use EcommerceTest\Response\Order;
use EcommerceTest\Response\OrderAddToCart;
use EcommerceTest\Response\OrderDelete;
use EcommerceTest\Response\OrderEditQuantity;
use EcommerceTest\Response\OrdersAll;
use EcommerceTest\Response\RecoveryPost;
use EcommerceTest\Response\RegisterPost;
use EcommerceTest\Response\ResetPost;

session_start();

require_once('interfaces/orderErrors.php');
require_once('vendor/autoload.php');

/* echo '<pre>';
var_dump($_SERVER);
echo '</pre>'; */

$logged = (isset($_SESSION['logged'],$_SESSION['utente'],$_SESSION['welcome']) && $_SESSION['welcome'] != '' && $_SESSION['logged'] === true);
$uri = $_SERVER['REQUEST_URI'];

if($_SERVER['REQUEST_METHOD'] == 'GET'){
    $ajax = (isset($_GET[C::KEY_AJAX]) && $_GET[C::KEY_AJAX] == true);
    if($uri == '/'){
        if($logged){
            $params = PageResources::HOME_GET_LOGGED;
            $params['session'] = $_SESSION;
            echo HomePageLogged::content($params);
        }
        else{
            echo HomePageGuest::content(PageResources::HOME_GET_GUEST);
        } 
    }
    else if($uri == '/cart'){
       if($logged){
            $params = PageResources::CART_GET_LOGGED;
            $params['session'] = $_SESSION;
            echo Cart::content($params);
       }
       else header("Location: /"); 
    }
    else if($uri == '/contacts_1'){
        if($logged){
            $params = PageResources::CONTACTS_GET_LOGGED;
            $params['session'] = $_SESSION;
            echo ContactsLogged::content($params);
        }
        else echo ContactsGuest::content(PageResources::CONTACTS_GET_GUEST);
    }
    else if($uri == '/cookie_policy'){
        if($logged){
            $params = PageResources::COOKIE_POLICY_GET_LOGGED;
            $params['session'] = $_SESSION;
            echo CookiePolicyLogged::content($params);
        }
        else echo CookiePolicyGuest::content(PageResources::COOKIE_POLICY_GET_GUEST);
    }
    else if($uri == '/edit'){
        if($logged){
            $params = PageResources::EDIT_GET_LOGGED;
            $params['session'] = $_SESSION;
            echo Edit::content($params);
        }
        else header("Location: /");
    }
    else if($uri == '/info'){
        if($logged){
            $params = PageResources::INFO_GET_LOGGED;
            $params['session'] = $_SESSION;
            echo Info::content($params);
        }
        else header("Location: /");
    }//else if($uri == '/info'){
    else if($uri == '/insertion'){
        if($logged){
            $params = PageResources::INSERTION_GET_LOGGED;
            $params['session'] = $_SESSION;
            echo Insertion::content($params);
        }
        else header("Location: /");
    }//else if($uri == '/insertion'){
    else if($uri == '/insertions'){
        if($logged){
            $params = PageResources::INSERTIONS_GET_LOGGED;
            $params['session'] = $_SESSION;
            echo Insertions::content($params);
        }
        else header("Location: /");
    }//else if($uri == '/insertions'){
    else if($uri == '/logout'){
        unset($_SESSION['logged'],$_SESSION['welcome'],$_SESSION['utente']);
        session_destroy();
        header('location: /');
    }else if($uri == '/orders'){
        if($logged){
            $params = PageResources::ORDERS_GET_LOGGED;
            $params['session'] = $_SESSION;
            echo Orders::content($params);
        }
        else header("Location: /");
    }
    else if(preg_match('/^\/orders\/(\d+)/',$uri,$matches)){
        if($logged){
            $params = [
                'get' => [ 'idOrd' => $matches[1] ],
                'session' => $_SESSION
            ];
            $response = Order::content($params);
            http_response_code($response[C::KEY_CODE]);
            echo json_encode($response,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
        }
        else{
            if($ajax){
                http_response_code(401);
                echo json_encode([C::KEY_DONE => false, C::KEY_MESSAGE => Msg::ERR_UNAUTHORIZED],JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE); 
            } 
            else header("Location: /");
        }
    }//else if(preg_match('/^orders\/\\d+$/',$uri)){
    else if($uri == '/orders/all'){
        if($logged){
            $params = ['session' => $_SESSION];
            $response = OrdersAll::content($params);
            http_response_code($response[C::KEY_CODE]);
            echo json_encode($response,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
        }
        else{
            if($ajax){
                http_response_code(401);
                echo json_encode([C::KEY_DONE => false, C::KEY_MESSAGE => Msg::ERR_UNAUTHORIZED],JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE); 
            } 
            else header("Location: /");
        }
    }
    else if($uri == '/privacy_policy'){
        if($logged){
            $params = PageResources::PRIVACY_POLICY_GET_LOGGED;
            $params['session'] = $_SESSION;
            echo PrivacyPolicyLogged::content($params);
        }
        else echo PrivacyPolicyGuest::content(PageResources::PRIVACY_POLICY_GET_GUEST);
    }
    else if($uri == '/recovery'){
        if($logged) header("Location: /");
        else echo RecoveryGet::content(PageResources::RECOVERY_GET_GUEST);
    }
    else if($uri == '/register'){
        if($logged) header("Location: /");
        else echo RegisterGet::content(PageResources::REGISTER_GET_GUEST);
        
    }
    else if(preg_match('/^\/reset\/([a-z0-9]{50,150})$/i',$uri,$matches)){
        if($logged) header("Location: /");
        else{
           $params = PageResources::RESET_GET_GUEST;
           $params['request'] = ['codReset' => $matches[1]];
           echo ResetGet::content($params); 
        } 
    }
    else if($uri == '/terms'){
        if($logged){
            $params = PageResources::TERMS_GET_LOGGED;
            $params['session'] = $_SESSION;
            echo TermsLogged::content($params);
        }
        else echo TermsGuest::content(PageResources::TERMS_GET_GUEST);
    }
    else{

    }
}
else if($_SERVER['REQUEST_METHOD'] == 'POST'){
    if(isset($_SERVER['CONTENT_TYPE']) && $_SERVER['CONTENT_TYPE'] == "application/x-www-form-urlencoded"){
        $post = $_POST;
    }
    else{
        $data = file_get_contents("php://input");
        $post = json_decode($data,true);
    }
    $ajax = (isset($post[C::KEY_AJAX]) && $post[C::KEY_AJAX] == true);
    if($uri == '/contacts'){
        $params = ['post' => $post, 'session' => $_SESSION];
        $response = ContactsPost::content($params);
        http_response_code($response[C::KEY_CODE]);
        if($ajax) echo json_encode($response,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
        else echo $response[C::KEY_MESSAGE];
    }//if($uri == '/contacts'){
    else if($uri == '/edit/password'){
        if($logged){
            $params = ['post' => $post, 'session' => $_SESSION];
            $response = EditPassword::content($params);
            http_response_code($response[C::KEY_CODE]);
            if($ajax) echo json_encode($response,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
            else echo $response[C::KEY_MESSAGE];
        }
        else{
            if($ajax){
                http_response_code(401);
                echo json_encode([C::KEY_DONE => false, C::KEY_MESSAGE => Msg::ERR_UNAUTHORIZED],JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE); 
            } 
            else header("Location: /");
        }
    }//else if($uri == '/edit/password'){
    else if($uri == '/edit/username'){
        if($logged){
            $params = ['post' => $post, 'session' => $_SESSION];
            $response = EditUsername::content($params);
            http_response_code($response[C::KEY_CODE]);
            if($ajax) echo json_encode($response,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
            else echo $response[C::KEY_MESSAGE];
        }
        else{
            if($ajax){
                http_response_code(401);
                echo json_encode([C::KEY_DONE => false, C::KEY_MESSAGE => Msg::ERR_UNAUTHORIZED],JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE); 
            } 
            else header("Location: /");
        }
    }//else if($uri == '/edit/username'){
    else if($uri == '/login'){
        if($logged){
            if($ajax){
                http_response_code(403);
                echo json_encode([C::KEY_DONE => false, C::KEY_MESSAGE => Msg::ERR_GUESTREQUIRED],JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
            }
            else header("Location: /");
        } 
        else{
            ob_start();
            $params = ['post' => $post];
            $response = Login::content($params);
            http_response_code($response[C::KEY_CODE]);
            if($ajax) echo json_encode($response,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
            else{
                if($response['redirect']['do']) header($response['redirect']['url']);
                echo $response[C::KEY_MESSAGE];
            } 
        }
    }//else if($uri == '/login'){
    else if($uri == '/recovery'){
        if($logged){
           if($ajax){
             http_response_code(403);
             echo json_encode([C::KEY_DONE => false, C::KEY_MESSAGE => Msg::ERR_GUESTREQUIRED],JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
           }
           else header("Location: /");
        }
        else{
            $params = ['post' => $post];
            $response = RecoveryPost::content($params);
            http_response_code($response[C::KEY_CODE]);
            if($ajax) echo json_encode($response,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
            else echo RecoveryPost::nonAjaxRequest($response[C::KEY_MESSAGE]);
        }
    }//else if($uri = '/recovery'){
    else if($uri == '/register'){
        if($logged){
            if($ajax){
                http_response_code(403);
                echo json_encode([C::KEY_DONE => false, C::KEY_MESSAGE => Msg::ERR_GUESTREQUIRED],JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
            }
            else header("Location: /");
        }
        else{
            ob_start();
            $params = [ 'post' => $post ];
            $response = RegisterPost::content($params);
            http_response_code($response[C::KEY_CODE]);
            if($ajax) echo json_encode($response,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
            else {
                if($response['redirect']['do']) header($response['redirect']['url']);
                echo $response[C::KEY_MESSAGE];
            }
        }
    }//else if($uri == '/register'){
    else if($uri == '/reset'){
        if($logged){
            if($ajax){
                http_response_code(403);
                echo json_encode([C::KEY_DONE => false, C::KEY_MESSAGE => Msg::ERR_GUESTREQUIRED],JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
            }
            else header("Location: /");
        }
        else{
            $params = ['post' => $post ];
            $response = ResetPost::content($params);
            http_response_code($response[C::KEY_CODE]);
            if($ajax)echo json_encode($response,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
            else echo $response[C::KEY_HTML];
        }
    }
}

else if($_SERVER['REQUEST_METHOD'] == 'PUT'){
    $data = file_get_contents("php://input");
    $put = json_decode($data,true);
    $ajax = (isset($put[C::KEY_AJAX]) && $put[C::KEY_AJAX] == true);
    if($uri == '/orders/addtocart'){
        if($logged){
            $params = ['put' => $put, 'session' => $_SESSION];
            $response = OrderAddToCart::content($params);
            http_response_code($response[C::KEY_CODE]);
            echo json_encode($response,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
        }
        else{
            if($ajax){
                http_response_code(401);
                echo json_encode([C::KEY_DONE => false, C::KEY_MESSAGE => Msg::ERR_UNAUTHORIZED],JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE); 
            } 
            else header("Location: /");
        } 
    }//if($uri == '/orders/addtocart'){
    else if($uri == '/orders/editquantity'){
        if($logged){
            $params = ['put' => $put, 'session' => $_SESSION];
            $response = OrderEditQuantity::content($params);
            http_response_code($response[C::KEY_CODE]);
            echo json_encode($response,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
        }
        else{
            if($ajax){
                http_response_code(401);
                echo json_encode([C::KEY_DONE => false, C::KEY_MESSAGE => Msg::ERR_UNAUTHORIZED],JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE); 
            } 
            else header("Location: /");
        }
    }//else if($uri == '/orders/editquantity'){
}

if($_SERVER['REQUEST_METHOD'] == 'DELETE'){
    $ajax = (isset($_GET[C::KEY_AJAX]) && $_GET[C::KEY_AJAX] == true);
    if(preg_match('/^\/orders\/(\d+)/',$uri,$matches)){
        if($logged){
            $params = [ 'delete' => ['id' => $matches[1]], 'session' => $_SESSION ];
            $response = OrderDelete::content($params);
            http_response_code($response[C::KEY_CODE]);
            if($response[C::KEY_DONE]) unset($_SESSION['ordini']);
            echo json_encode($response,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
        }
        else{
            if($ajax){
                http_response_code(401);
                echo json_encode([C::KEY_DONE => false, C::KEY_MESSAGE => Msg::ERR_UNAUTHORIZED],JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE); 
            } 
            else header("Location: /");
        } 
    }//else if(preg_match('/^\/orders\/(\d)/',$uri,$matches)){
}


?>