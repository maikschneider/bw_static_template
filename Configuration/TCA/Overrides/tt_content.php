<?php

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'Blueways.BwStaticTemplate',
    'Pi1',
    'LLL:EXT:bw_static_template/Resources/Private/Language/locallang.xlf:pi1.wizard.title',
    'tx_bwstatictemplate_pi1'
);
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['bwstatictemplate_pi1'] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
    'bwstatictemplate_pi1',
    'FILE:EXT:bw_static_template/Configuration/FlexForms/Pi1.xml'
);
