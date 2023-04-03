<?php

defined('TYPO3') or die();

call_user_func(
    function () {
        // register css
        $GLOBALS['TBE_STYLES']['skins']['bw_static_template']['stylesheetDirectories'][] = 'EXT:bw_static_template/Resources/Public/Css/';
    }
);
