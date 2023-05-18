<?php

namespace EcommerceTest\Interfaces;

use EcommerceTest\Interfaces\Paths as P;

/**
 * CSS and JS loaded by HTML pages
 */
interface PageResources{

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
}

?>