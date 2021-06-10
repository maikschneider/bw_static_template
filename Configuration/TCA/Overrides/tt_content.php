<?php

$typo3Version = TYPO3\CMS\Core\Utility\VersionNumberUtility::convertVersionStringToArray(TYPO3\CMS\Core\Utility\VersionNumberUtility::getNumericTypo3Version());
$extensionName = $typo3Version['version_main'] > 10 ? 'BwStaticTemplate' : 'Blueways.BwStaticTemplate';
$flexformName = $typo3Version['version_main'] > 10 ? 'Pi1_v11.xml' : 'Pi1.xml';

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    $extensionName,
    'Pi1',
    'LLL:EXT:bw_static_template/Resources/Private/Language/locallang.xlf:pi1.wizard.title',
    'tx_bwstatictemplate_pi1'
);
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['bwstatictemplate_pi1'] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
    'bwstatictemplate_pi1',
    'FILE:EXT:bw_static_template/Configuration/FlexForms/' . $flexformName
);
