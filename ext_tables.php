<?php

call_user_func(
    function () {
        // Register icon
        $iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
        $iconRegistry->registerIcon(
            'tx_bwstatictemplate_pi1',
            \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
            ['source' => 'EXT:bw_static_template/Resources/Public/Icons/tt_content_pi1.svg']
        );

        // register css
        $GLOBALS['TBE_STYLES']['skins']['bw_static_template']['stylesheetDirectories'][] = 'EXT:bw_static_template/Resources/Public/Css/';
    }
);
