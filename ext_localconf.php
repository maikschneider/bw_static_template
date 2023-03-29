<?php

defined('TYPO3') or die();

call_user_func(function () {

    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/install']['update']['bwStaticTemplate_v3UpgradeWizard'] = \Blueways\BwStaticTemplate\Upgrades\V3UpgradeWizard::class;

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScript(
        'bw_static_template',
        'setup',
        "@import 'EXT:bw_static_template/Configuration/TypoScript/setup.typoscript'"
    );
});
