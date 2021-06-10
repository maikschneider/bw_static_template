<?php

defined('TYPO3_MODE') || die();

$typo3Version = TYPO3\CMS\Core\Utility\VersionNumberUtility::convertVersionStringToArray(TYPO3\CMS\Core\Utility\VersionNumberUtility::getNumericTypo3Version());
$controllerName = $typo3Version['version_main'] > 10 ? \Blueways\BwStaticTemplate\Controller\TemplateController::class : 'Template';
$extensionName = $typo3Version['version_main'] > 10 ? 'BwStaticTemplate' : 'Blueways.BwStaticTemplate';

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    $extensionName,
    'Pi1',
    [
        $controllerName => 'show',
    ],
    // non-cacheable actions
    [
    ]
);

$GLOBALS['TYPO3_CONF_VARS']['SYS']['fluid']['namespaces']['static'] = [
    'Blueways\BwStaticTemplate\ViewHelpers',
];
