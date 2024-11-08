<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Static Template',
    'description' => 'Content element to render fluid templates. Inject JSON data or FAL files into the templates. Perfect for fast template development with example data.',
    'category' => 'templates',
    'constraints' => [
        'depends' => [
            'typo3' => '12.4.0-13.4.99',
            'bw_jsoneditor' => '2.0.0-2.99.99',
        ],
        'conflicts' => [
        ],
    ],
    'autoload' => [
        'psr-4' => [
            'Blueways\\BwStaticTemplate\\' => 'Classes',
        ],
    ],
    'state' => 'stable',
    'author' => 'Maik Schneider',
    'author_email' => 'maik.schneider@xima.de',
    'author_company' => 'XIMA MEDIA GmbH',
    'version' => '4.0.0',
];
