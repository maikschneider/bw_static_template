<?php

defined('TYPO3_MODE') || die();

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'Blueways.BwStaticTemplate',
    'Pi1',
    [
        'Template' => 'show',
    ],
    // non-cacheable actions
    [
    ]
);
