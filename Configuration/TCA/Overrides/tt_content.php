<?php

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
                'eval' => 'trim,required',
            ],
        ],
        'bodytext' => [
            'label' => 'LLL:EXT:bw_static_template/Resources/Private/Language/locallang.xlf:json',
            'config' => [
                'renderType' => 'jsonForm',
            ],
        ],
    ],
];

$GLOBALS['TCA']['tt_content']['types']['bw_static_template']['previewRenderer'] = \Blueways\BwStaticTemplate\PreviewRenderer\BackendPreviewRender::class;
$GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes']['bw_static_template'] = 'tx_bwstatictemplate_pi1';
