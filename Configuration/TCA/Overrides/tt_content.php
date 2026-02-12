<?php

// register CType
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTcaSelectItem(
    'tt_content',
    'CType',
    [
        'label' => 'LLL:EXT:bw_static_template/Resources/Private/Language/locallang.xlf:pi1.wizard.title',
        'value' => 'bw_static_template',
        'icon' => 'tx_bwstatictemplate_pi1',
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
            'eval' => 'trim',
            'required' => true,
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
                    'label' => '',
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
            'type' => 'user',
            'renderType' => 'jsonEditor',
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
        --div--;core.form.tabs:general,
            --palette--;;general,
            --palette--;;templates,
            --palette--;;json,
            assets,
        --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.appearance,
            --palette--;;frames,
            --palette--;;appearanceLinks,
        --div--;core.form.tabs:language,
            --palette--;;language,
        --div--;core.form.tabs:access,
            --palette--;;hidden,
            --palette--;;access,
        --div--;core.form.tabs:categories,
            categories,
        --div--;core.form.tabs:notes,
            rowDescription,
        --div--;core.form.tabs:extended,
    ',
];

$GLOBALS['TCA']['tt_content']['types']['bw_static_template']['previewRenderer'] = \Blueways\BwStaticTemplate\PreviewRenderer\BackendPreviewRender::class;
$GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes']['bw_static_template'] = 'tx_bwstatictemplate_pi1';
$GLOBALS['TCA']['tt_content']['ctrl']['label_alt'] .= ',tx_bwstatictemplate_template_path';
