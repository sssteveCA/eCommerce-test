<?php

namespace EcommerceTest\Interfaces;

interface Constants{

    //Cookie
    const COOKIE_PREFERENCE_TIME = 3600;
    const GENERATED_LINK_TIME = 3600;

    //Keys
    const KEY_AJAX = 'ajax';

    const KEY_CODE = 'code';
    const KEY_DONE = 'done';
    const KEY_EMPTY = 'empty';
    const KEY_HTML = 'html';
    const KEY_MESSAGE = 'msg';

    //Paypal
   const PAYPAL_PAGE = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
    //const SHOPPING_URL = Config::HOME_URL.'/ricerca.php?ricerca=';
    const PAYPAL_RM = '2';
    const PAYPAL_CURRENCY = 'EUR';
    const PAYPAL_LC = 'IT';
    const PAYPAL_STATE = 'Italia';
    const PAYPAL_CMD = '_xclick';
    const PAYPAL_SBN_CODE = 'PP-DemoPortal-PPCredit-JSV4-php-REST';
    const PAYPAL_SHIPPING = '10.00';
}
?>