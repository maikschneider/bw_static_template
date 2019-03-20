<?php
defined('TYPO3_MODE') || die();

call_user_func(
    function () {

        /**
         * Register icon
         */
        $iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
        $iconRegistry->registerIcon(
            'tx_bwstatictemplate_pi1',
            \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
            ['source' => 'EXT:bw_static_template/Resources/Public/Icons/tt_content_pi1.svg']
        );
    }
);
