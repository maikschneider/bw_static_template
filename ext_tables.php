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

        // register CType
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTcaSelectItem(
            'tt_content',
            'CType',
            [
                'LLL:EXT:bw_static_template/Resources/Private/Language/locallang.xlf:pi1.wizard.title',
                'bw_static_template',
                'tx_bwstatictemplate_pi1',
            ],
            'html',
            'after'
        );

        // register css
        $GLOBALS['TBE_STYLES']['skins']['bw_static_template']['stylesheetDirectories'][] = 'EXT:bw_static_template/Resources/Public/Css/';
    }
);
