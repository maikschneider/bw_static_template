<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Static Template',
    'description' => 'Frontend plugin that renders fluid templates. Inject JSON data or FAL files into the templates. Perfect for fast template development with example data.',
    'category' => 'templates',
    'constraints' => [
        'depends' => [
            'typo3' => '12.4.0-12.4.99',
            'bw_jsoneditor' => '1.1.0-1.99.99',
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
    'version' => '3.0.0',
];
