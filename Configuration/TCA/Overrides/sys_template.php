<?php
defined('TYPO3_MODE') || die();

call_user_func(function () {

    /**
     * TypoScript Tempalte
     */
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
        'bw_static_template',
        'Configuration/TypoScript',
        'Bw Static Template'
    );
});
