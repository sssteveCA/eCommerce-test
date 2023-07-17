<?php

namespace EcommerceTest\Interfaces;

use EcommerceTest\Interfaces\Paths as P;

/**
 * CSS and JS loaded by HTML pages
 */
interface PageResources{

    const CART_GET_LOGGED = [
        'paths' => [
            'css' => [
                'REL_BOOTSTRAP_CSS' => P::REL_BOOTSTRAP_CSS, 'REL_CART_CSS' => P::REL_CART_CSS, 'REL_FOOTER_CSS' => P::REL_FOOTER_CSS, 'REL_JQUERY_CSS' => P::REL_JQUERY_CSS, 'REL_JQUERYTHEME_CSS' => P::REL_JQUERYTHEME_CSS
            ],
            'js' => [
                'REL_BOOTSTRAP_JS' => P::REL_BOOTSTRAP_JS, 'REL_CART_JS' => P::REL_CART_JS, 'REL_FOOTER_JS' =>  P::REL_FOOTER_JS, 'REL_JQUERY_JS' => P::REL_JQUERY_JS, 'REL_JQUERYUI_JS' => P::REL_JQUERYUI_JS,
                'REL_LOGOUT_JS' => P::REL_LOGOUT_JS, 'REL_POPPER_JS' => P::REL_POPPER_JS,
            ],
        ]
    ];

    const CONTACTS_GET_GUEST = [
        'paths' => [
            'css' => [
                'REL_BOOTSTRAP_CSS' => P::REL_BOOTSTRAP_CSS, 'REL_CONTACTS_CSS' => P::REL_CONTACTS_CSS, 'REL_FOOTER_CSS' => P::REL_FOOTER_CSS, 'REL_JQUERY_CSS' => P::REL_JQUERY_CSS, 'REL_JQUERYTHEME_CSS' => P::REL_JQUERYTHEME_CSS
            ],
            'js' => [
                'REL_BOOTSTRAP_JS' => P::REL_BOOTSTRAP_JS, 'REL_CONTACTS_JS' => P::REL_CONTACTS_JS, 'REL_DIALOG_MESSAGE_JS' => P::REL_DIALOG_MESSAGE_JS, 'REL_FOOTER_JS' =>  P::REL_FOOTER_JS, 'REL_JQUERY_JS' => P::REL_JQUERY_JS, 'REL_JQUERYUI_JS' => P::REL_JQUERYUI_JS, 'REL_POPPER_JS' => P::REL_POPPER_JS,
            ],
        ]
    ];

    const CONTACTS_GET_LOGGED = [
        'paths' => [
            'css' => [
                'REL_BOOTSTRAP_CSS' => P::REL_BOOTSTRAP_CSS, 'REL_CONTACTS_CSS' => P::REL_CONTACTS_CSS, 'REL_FOOTER_CSS' => P::REL_FOOTER_CSS, 'REL_JQUERY_CSS' => P::REL_JQUERY_CSS, 'REL_JQUERYTHEME_CSS' => P::REL_JQUERYTHEME_CSS
            ],
            'js' => [
                'REL_BOOTSTRAP_JS' => P::REL_BOOTSTRAP_JS, 'REL_CONTACTS_JS' => P::REL_CONTACTS_JS, 'REL_DIALOG_MESSAGE_JS' => P::REL_DIALOG_MESSAGE_JS, 'REL_FOOTER_JS' =>  P::REL_FOOTER_JS, 'REL_JQUERY_JS' => P::REL_JQUERY_JS, 'REL_JQUERYUI_JS' => P::REL_JQUERYUI_JS, 'REL_LOGOUT_JS' => P::REL_LOGOUT_JS, 'REL_POPPER_JS' => P::REL_POPPER_JS,
            ],
        ]
    ];

    const COOKIE_POLICY_GET_GUEST = [
        'paths' => [
            'css' => [
                'REL_BOOTSTRAP_CSS' => P::REL_BOOTSTRAP_CSS, 'REL_FOOTER_CSS' => P::REL_FOOTER_CSS, 'REL_JQUERY_CSS' => P::REL_JQUERY_CSS, 'REL_JQUERYTHEME_CSS' => P::REL_JQUERYTHEME_CSS
            ],
            'js' => [
                'REL_BOOTSTRAP_JS' => P::REL_BOOTSTRAP_JS, 'REL_FOOTER_JS' =>  P::REL_FOOTER_JS, 'REL_JQUERY_JS' => P::REL_JQUERY_JS, 'REL_JQUERYUI_JS' => P::REL_JQUERYUI_JS, 'REL_POPPER_JS' => P::REL_POPPER_JS,
            ],
        ]
    ];

    const COOKIE_POLICY_GET_LOGGED = [
        'paths' => [
            'css' => [
                'REL_BOOTSTRAP_CSS' => P::REL_BOOTSTRAP_CSS, 'REL_FOOTER_CSS' => P::REL_FOOTER_CSS, 'REL_JQUERY_CSS' => P::REL_JQUERY_CSS, 'REL_JQUERYTHEME_CSS' => P::REL_JQUERYTHEME_CSS
            ],
            'js' => [
                'REL_BOOTSTRAP_JS' => P::REL_BOOTSTRAP_JS, 'REL_FOOTER_JS' =>  P::REL_FOOTER_JS, 'REL_JQUERY_JS' => P::REL_JQUERY_JS, 'REL_JQUERYUI_JS' => P::REL_JQUERYUI_JS, 'REL_LOGOUT_JS' => P::REL_LOGOUT_JS, 'REL_POPPER_JS' => P::REL_POPPER_JS,
            ],
        ]
    ];

    const EDIT_GET_LOGGED = [
        'paths' => [
            'css' => [
                'REL_BOOTSTRAP_CSS' => P::REL_BOOTSTRAP_CSS, 'REL_EDIT_CSS' => P::REL_EDIT_CSS, 'REL_FOOTER_CSS' => P::REL_FOOTER_CSS, 'REL_JQUERY_CSS' => P::REL_JQUERY_CSS, 'REL_JQUERYTHEME_CSS' => P::REL_JQUERYTHEME_CSS, 
            ],
            'js' => [
                'REL_BOOTSTRAP_JS' => P::REL_BOOTSTRAP_JS, 'REL_EDIT_JS' => P::REL_EDIT_JS, 'REL_FOOTER_JS' =>  P::REL_FOOTER_JS, 'REL_JQUERY_JS' => P::REL_JQUERY_JS, 'REL_JQUERYUI_JS' => P::REL_JQUERYUI_JS, 'REL_LOGOUT_JS' => P::REL_LOGOUT_JS, 'REL_POPPER_JS' => P::REL_POPPER_JS, 
            ],
        ]
    ];
   
    const HOME_GET_GUEST = [
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
    const HOME_GET_LOGGED = [
        'paths' => [
            'css' => [
                'REL_BOOTSTRAP_CSS' => P::REL_BOOTSTRAP_CSS, 'REL_FOOTER_CSS' => P::REL_FOOTER_CSS, 'REL_JQUERY_CSS' => P::REL_JQUERY_CSS, 'REL_JQUERYTHEME_CSS' => P::REL_JQUERYTHEME_CSS, 'REL_WELCOME_CSS' => P::REL_WELCOME_CSS
            ],
            'js' => [
                'REL_BOOTSTRAP_JS' => P::REL_BOOTSTRAP_JS, 'REL_DIALOG_MESSAGE_JS' => P::REL_DIALOG_MESSAGE_JS, 'REL_FOOTER_JS' =>  P::REL_FOOTER_JS, 'REL_JQUERY_JS' => P::REL_JQUERY_JS, 'REL_JQUERYUI_JS' => P::REL_JQUERYUI_JS, 'REL_LOGOUT_JS' => P::REL_LOGOUT_JS, 'REL_POPPER_JS' => P::REL_POPPER_JS, 'REL_WELCOME_JS' => P::REL_WELCOME_JS
            ],
        ]
    ];
    const INFO_GET_LOGGED = [
        'paths' => [
            'css' => [
                'REL_BOOTSTRAP_CSS' => P::REL_BOOTSTRAP_CSS, 'REL_FOOTER_CSS' => P::REL_FOOTER_CSS, 'REL_INFO_CSS' => P::REL_INFO_CSS, 'REL_JQUERY_CSS' => P::REL_JQUERY_CSS, 'REL_JQUERYTHEME_CSS' => P::REL_JQUERYTHEME_CSS, 
            ],
            'js' => [
                'REL_BOOTSTRAP_JS' => P::REL_BOOTSTRAP_JS, 'REL_FOOTER_JS' =>  P::REL_FOOTER_JS, 'REL_INFO_JS' => P::REL_INFO_JS, 'REL_JQUERY_JS' => P::REL_JQUERY_JS, 'REL_JQUERYUI_JS' => P::REL_JQUERYUI_JS, 'REL_LOGOUT_JS' => P::REL_LOGOUT_JS, 'REL_POPPER_JS' => P::REL_POPPER_JS,
            ],
        ]
    ];

    const INSERTION_GET_LOGGED = [
        'paths' => [
            'css' => [
                'REL_BOOTSTRAP_CSS' => P::REL_BOOTSTRAP_CSS, 'REL_CREATE_CSS' => P::REL_CREATE_CSS, 'REL_FOOTER_CSS' => P::REL_FOOTER_CSS, 'REL_JQUERY_CSS' => P::REL_JQUERY_CSS, 'REL_JQUERYTHEME_CSS' => P::REL_JQUERYTHEME_CSS, 
            ],
            'js' => [
                'REL_BOOTSTRAP_JS' => P::REL_BOOTSTRAP_JS, 'REL_CREATE_JS' => P::REL_CREATE_JS, 'REL_FOOTER_JS' =>  P::REL_FOOTER_JS, 'REL_JQUERY_JS' => P::REL_JQUERY_JS, 'REL_JQUERYUI_JS' => P::REL_JQUERYUI_JS, 'REL_LOGOUT_JS' => P::REL_LOGOUT_JS, 'REL_POPPER_JS' => P::REL_POPPER_JS,
            ],
        ]
    ];

    const INSERTIONS_GET_LOGGED = [
        'paths' => [
            'css' => [
                'REL_BOOTSTRAP_CSS' => P::REL_BOOTSTRAP_CSS, 'REL_FOOTER_CSS' => P::REL_FOOTER_CSS, 'REL_INSERTIONS_CSS' => P::REL_INSERTIONS_CSS, 'REL_JQUERY_CSS' => P::REL_JQUERY_CSS, 'REL_JQUERYTHEME_CSS' => P::REL_JQUERYTHEME_CSS, 
            ],
            'js' => [
                'REL_BOOTSTRAP_JS' => P::REL_BOOTSTRAP_JS, 'REL_FOOTER_JS' =>  P::REL_FOOTER_JS, 'REL_INSERTIONS_JS' => P::REL_INSERTIONS_JS, 'REL_JQUERY_JS' => P::REL_JQUERY_JS, 'REL_JQUERYUI_JS' => P::REL_JQUERYUI_JS, 'REL_LOGOUT_JS' => P::REL_LOGOUT_JS, 'REL_POPPER_JS' => P::REL_POPPER_JS,
            ],
        ]
    ];

    const ORDERS_GET_LOGGED = [
        'paths' => [
            'css' => [
                'REL_BOOTSTRAP_CSS' => P::REL_BOOTSTRAP_CSS, 'REL_FOOTER_CSS' => P::REL_FOOTER_CSS, 'REL_JQUERY_CSS' => P::REL_JQUERY_CSS, 'REL_JQUERYTHEME_CSS' => P::REL_JQUERYTHEME_CSS, 'REL_ORDERS_CSS' => P::REL_ORDERS_CSS
            ],
            'js' => [
                'REL_BOOTSTRAP_JS' => P::REL_BOOTSTRAP_JS, 'REL_FOOTER_JS' =>  P::REL_FOOTER_JS, 'REL_JQUERY_JS' => P::REL_JQUERY_JS, 'REL_JQUERYUI_JS' => P::REL_JQUERYUI_JS, 'REL_LOGOUT_JS' => P::REL_LOGOUT_JS, 'REL_ORDERS_JS' => P::REL_ORDERS_JS, 'REL_POPPER_JS' => P::REL_POPPER_JS
            ],
        ]
    ];

    const PRIVACY_POLICY_GET_GUEST = [
        'paths' => [
            'css' => [
                'REL_BOOTSTRAP_CSS' => P::REL_BOOTSTRAP_CSS, 'REL_FOOTER_CSS' => P::REL_FOOTER_CSS, 'REL_JQUERY_CSS' => P::REL_JQUERY_CSS, 'REL_JQUERYTHEME_CSS' => P::REL_JQUERYTHEME_CSS
            ],
            'js' => [
                'REL_BOOTSTRAP_JS' => P::REL_BOOTSTRAP_JS, 'REL_FOOTER_JS' =>  P::REL_FOOTER_JS, 'REL_JQUERY_JS' => P::REL_JQUERY_JS, 'REL_JQUERYUI_JS' => P::REL_JQUERYUI_JS, 'REL_POPPER_JS' => P::REL_POPPER_JS,
            ],
        ]
    ];

    const PRIVACY_POLICY_GET_LOGGED = [
        'paths' => [
            'css' => [
                'REL_BOOTSTRAP_CSS' => P::REL_BOOTSTRAP_CSS, 'REL_FOOTER_CSS' => P::REL_FOOTER_CSS, 'REL_JQUERY_CSS' => P::REL_JQUERY_CSS, 'REL_JQUERYTHEME_CSS' => P::REL_JQUERYTHEME_CSS
            ],
            'js' => [
                'REL_BOOTSTRAP_JS' => P::REL_BOOTSTRAP_JS, 'REL_FOOTER_JS' =>  P::REL_FOOTER_JS, 'REL_JQUERY_JS' => P::REL_JQUERY_JS, 'REL_JQUERYUI_JS' => P::REL_JQUERYUI_JS, 'REL_LOGOUT_JS' => P::REL_LOGOUT_JS, 'REL_POPPER_JS' => P::REL_POPPER_JS,
            ],
        ]
    ];
    const PRODUCT_GET_LOGGED = [
        'paths' => [
            'css' => [
                'REL_BOOTSTRAP_CSS' => P::REL_BOOTSTRAP_CSS, 'REL_FOOTER_CSS' => P::REL_FOOTER_CSS, 'REL_JQUERY_CSS' => P::REL_JQUERY_CSS, 'REL_JQUERYTHEME_CSS' => P::REL_JQUERYTHEME_CSS, 'REL_PRODUCT_CSS' => P::REL_PRODUCT_CSS
            ],
            'js' => [
                'REL_BOOTSTRAP_JS' => P::REL_BOOTSTRAP_JS, 'REL_FOOTER_JS' =>  P::REL_FOOTER_JS, 'REL_JQUERY_JS' => P::REL_JQUERY_JS, 'REL_JQUERYUI_JS' => P::REL_JQUERYUI_JS, 'REL_LOGOUT_JS' => P::REL_LOGOUT_JS, 'REL_PRODUCT_JS' => P::REL_PRODUCT_JS
            ],
        ]
    ];

    const RECOVERY_GET_GUEST = [
        'paths' => [
            'css' => [
                'REL_BOOTSTRAP_CSS' => P::REL_BOOTSTRAP_CSS, 'REL_FOOTER_CSS' => P::REL_FOOTER_CSS, 'REL_JQUERY_CSS' => P::REL_JQUERY_CSS, 'REL_JQUERYTHEME_CSS' => P::REL_JQUERYTHEME_CSS, 'REL_RECOVERY_CSS' => P::REL_RECOVERY_CSS
            ],
            'js' => [
                'REL_BOOTSTRAP_JS' => P::REL_BOOTSTRAP_JS, 'REL_DIALOG_MESSAGE_JS' => P::REL_DIALOG_MESSAGE_JS, 'REL_FOOTER_JS' =>  P::REL_FOOTER_JS, 'REL_JQUERY_JS' => P::REL_JQUERY_JS, 'REL_JQUERYUI_JS' => P::REL_JQUERYUI_JS, 'REL_RECOVERY_JS' => P::REL_RECOVERY_JS
            ],
        ]
    ];
    const REGISTER_GET_GUEST = [
        'paths' => [
            'css' => [
                'REL_BOOTSTRAP_CSS' => P::REL_BOOTSTRAP_CSS, 'REL_FOOTER_CSS' => P::REL_FOOTER_CSS, 'REL_JQUERY_CSS' => P::REL_JQUERY_CSS, 'REL_JQUERYTHEME_CSS' => P::REL_JQUERYTHEME_CSS, 'REL_SUBSCRIBE_CSS' => P::REL_SUBSCRIBE_CSS
            ],
            'js' => [
                'REL_BOOTSTRAP_JS' => P::REL_BOOTSTRAP_JS, 'REL_DIALOG_MESSAGE_JS' => P::REL_DIALOG_MESSAGE_JS, 'REL_FOOTER_JS' =>  P::REL_FOOTER_JS, 'REL_JQUERY_JS' => P::REL_JQUERY_JS, 'REL_JQUERYUI_JS' => P::REL_JQUERYUI_JS,  'REL_SUBSCRIBE_JS' => P::REL_SUBSCRIBE_JS
            ],
        ]
    ];

    const RESET_GET_GUEST = [
        'paths' => [
            'css' => [
                'REL_BOOTSTRAP_CSS' => P::REL_BOOTSTRAP_CSS, 'REL_FOOTER_CSS' => P::REL_FOOTER_CSS, 'REL_JQUERY_CSS' => P::REL_JQUERY_CSS, 'REL_JQUERYTHEME_CSS' => P::REL_JQUERYTHEME_CSS, 'REL_RESET_CSS' => P::REL_RESET_CSS
            ],
            'js' => [
                'REL_BOOTSTRAP_JS' => P::REL_BOOTSTRAP_JS, 'REL_FOOTER_JS' =>  P::REL_FOOTER_JS, 'REL_JQUERY_JS' => P::REL_JQUERY_JS, 'REL_JQUERYUI_JS' => P::REL_JQUERYUI_JS, 'REL_RESET_JS' => P::REL_RESET_JS
            ],
        ]
    ];

    const RESET_POST_GUEST = [
        'paths' => [
            'css' => [
                'REL_BOOTSTRAP_CSS' => P::REL_BOOTSTRAP_CSS, 'REL_FOOTER_CSS' => P::REL_FOOTER_CSS, 'REL_JQUERY_CSS' => P::REL_JQUERY_CSS, 'REL_JQUERYTHEME_CSS' => P::REL_JQUERYTHEME_CSS
            ],
            'js' => [
                'REL_BOOTSTRAP_JS' => P::REL_BOOTSTRAP_JS, 'REL_FOOTER_JS' =>  P::REL_FOOTER_JS, 'REL_JQUERY_JS' => P::REL_JQUERY_JS, 'REL_JQUERYUI_JS' => P::REL_JQUERYUI_JS
            ],
        ]
    ];

    const SEARCH_GET_LOGGED = [
        'paths' => [
            'css' => [
                'REL_BOOTSTRAP_CSS' => P::REL_BOOTSTRAP_CSS, 'REL_FOOTER_CSS' => P::REL_FOOTER_CSS, 'REL_JQUERY_CSS' => P::REL_JQUERY_CSS, 'REL_JQUERYTHEME_CSS' => P::REL_JQUERYTHEME_CSS, 'REL_SEARCH_CSS' => P::REL_SEARCH_CSS
            ],
            'js' => [
                'REL_BOOTSTRAP_JS' => P::REL_BOOTSTRAP_JS, 'REL_FOOTER_JS' =>  P::REL_FOOTER_JS, 'REL_JQUERY_JS' => P::REL_JQUERY_JS, 'REL_JQUERYUI_JS' => P::REL_JQUERYUI_JS, 'REL_LOGOUT_JS' => P::REL_LOGOUT_JS, 'REL_POPPER_JS' => P::REL_POPPER_JS
            ],
        ]
    ];

    const TERMS_GET_GUEST = [
        'paths' => [
            'css' => [
                'REL_BOOTSTRAP_CSS' => P::REL_BOOTSTRAP_CSS, 'REL_FOOTER_CSS' => P::REL_FOOTER_CSS, 'REL_JQUERY_CSS' => P::REL_JQUERY_CSS, 'REL_JQUERYTHEME_CSS' => P::REL_JQUERYTHEME_CSS
            ],
            'js' => [
                'REL_BOOTSTRAP_JS' => P::REL_BOOTSTRAP_JS, 'REL_FOOTER_JS' =>  P::REL_FOOTER_JS, 'REL_JQUERY_JS' => P::REL_JQUERY_JS, 'REL_JQUERYUI_JS' => P::REL_JQUERYUI_JS, 'REL_POPPER_JS' => P::REL_POPPER_JS
            ],
        ]
    ];

    const TERMS_GET_LOGGED = [
        'paths' => [
            'css' => [
                'REL_BOOTSTRAP_CSS' => P::REL_BOOTSTRAP_CSS, 'REL_FOOTER_CSS' => P::REL_FOOTER_CSS, 'REL_JQUERY_CSS' => P::REL_JQUERY_CSS, 'REL_JQUERYTHEME_CSS' => P::REL_JQUERYTHEME_CSS
            ],
            'js' => [
                'REL_BOOTSTRAP_JS' => P::REL_BOOTSTRAP_JS, 'REL_FOOTER_JS' =>  P::REL_FOOTER_JS, 'REL_JQUERY_JS' => P::REL_JQUERY_JS, 'REL_JQUERYUI_JS' => P::REL_JQUERYUI_JS, 'REL_LOGOUT_JS' => P::REL_LOGOUT_JS, 'REL_POPPER_JS' => P::REL_POPPER_JS
            ],
        ]
    ];
}

?>