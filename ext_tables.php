<?php

defined('TYPO3') or die();

call_user_func(
    function () {
        // register css
        $GLOBALS['TYPO3_CONF_VARS']['BE']['stylesheets']['bw_static_template'] = 'EXT:bw_static_template/Resources/Public/Css/Backend.css';
    }
);
