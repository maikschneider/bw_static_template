<?php

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

$tempFields = [
    'tx_bwstatictemplate_template_path' => [
        'label' => 'LLL:EXT:bw_static_template/Resources/Private/Language/locallang.xlf:template',
        'config' => [
            'type' => 'input',
            'size' => 60,
            'max' => 255,
            'eval' => 'trim,required',
        ],
    ],
    'tx_bwstatictemplate_from_file' => [
        'label' => 'LLL:EXT:bw_static_template/Resources/Private/Language/locallang.xlf:fromFile',
        'onChange' => 'reload',
        'config' => [
            'type' => 'check',
            'renderType' => 'checkboxToggle',
            'default' => 0,
            'items' => [
                [
                    0 => '',
                    'invertStateDisplay' => true,
                ],
            ],
        ],
    ],
    'tx_bwstatictemplate_file_path' => [
        'label' => 'LLL:EXT:bw_static_template/Resources/Private/Language/locallang.xlf:filePath',
        'displayCond' => 'FIELD:tx_bwstatictemplate_from_file:=:1',
        'config' => [
            'type' => 'input',
            'size' => 60,
            'max' => 255,
            'eval' => 'trim',
        ],
    ],
    'tx_bwstatictemplate_be_template' => [
        'label' => 'LLL:EXT:bw_static_template/Resources/Private/Language/locallang.xlf:beTemplate',
        'config' => [
            'type' => 'input',
            'size' => 60,
            'max' => 255,
            'eval' => 'trim',
            'placeholder' => 'LLL:EXT:bw_static_template/Resources/Private/Language/locallang.xlf:beTemplate.default',
        ],
    ],
    'tx_bwstatictemplate_json' => [
        'label' => 'LLL:EXT:bw_static_template/Resources/Private/Language/locallang.xlf:json',
        'displayCond' => 'FIELD:tx_bwstatictemplate_from_file:=:0',
        'config' => [
            'type' => 'input',
            'renderType' => 'jsonForm',
        ],
    ],
];

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('tt_content', $tempFields);

$GLOBALS['TCA']['tt_content']['palettes']['json'] = [
    'label' => 'LLL:EXT:bw_static_template/Resources/Private/Language/locallang.xlf:jsonPalette',
    'showitem' => 'tx_bwstatictemplate_from_file,--linebreak--,tx_bwstatictemplate_json,--linebreak--,tx_bwstatictemplate_file_path',
];

$GLOBALS['TCA']['tt_content']['palettes']['templates'] = [
    'label' => 'LLL:EXT:bw_static_template/Resources/Private/Language/locallang.xlf:templatePalette',
    'showitem' => 'tx_bwstatictemplate_template_path,--linebreak--,tx_bwstatictemplate_be_template',
];

$GLOBALS['TCA']['tt_content']['types']['bw_static_template'] = [
    'showitem' => '
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
            --palette--;;general,
            --palette--;;templates,
            --palette--;;json,
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
];

$GLOBALS['TCA']['tt_content']['types']['bw_static_template']['previewRenderer'] = \Blueways\BwStaticTemplate\PreviewRenderer\BackendPreviewRender::class;
$GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes']['bw_static_template'] = 'tx_bwstatictemplate_pi1';
$GLOBALS['TCA']['tt_content']['ctrl']['label_alt'] .= ',tx_bwstatictemplate_template_path';
