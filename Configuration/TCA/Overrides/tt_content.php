<?php

$typo3Version = TYPO3\CMS\Core\Utility\VersionNumberUtility::convertVersionStringToArray(TYPO3\CMS\Core\Utility\VersionNumberUtility::getNumericTypo3Version());
$extensionName = $typo3Version['version_main'] >= 10 ? 'BwStaticTemplate' : 'Blueways.BwStaticTemplate';
$flexformName = $typo3Version['version_main'] >= 10 ? 'Pi1_v11.xml' : 'Pi1.xml';

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

$GLOBALS['TCA']['tt_content']['types']['bw_static_template'] = [
    'showitem' => '
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
            --palette--;;general,
            header,
            bodytext,
            assets,
        --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.appearance,
            --palette--;;frames,
            --palette--;;appearanceLinks,
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language,
            --palette--;;language,
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
            --palette--;;hidden,
            --palette--;;access,
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:categories,
            categories,
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:notes,
            rowDescription,
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:extended,
    ',
    'columnsOverrides' => [
        'header' => [
            'label' => 'LLL:EXT:bw_static_template/Resources/Private/Language/locallang.xlf:template',
            'config' => [
                'eval' => 'trim,required'
            ]
        ],
        'bodytext' => [
            'label' => 'LLL:EXT:bw_static_template/Resources/Private/Language/locallang.xlf:json',
            'config' => [
                'renderType' => 'jsonForm',
            ],
        ],
    ]
];
