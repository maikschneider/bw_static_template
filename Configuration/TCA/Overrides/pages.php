<?php

call_user_func(function () {
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::registerPageTSConfigFile(
        'bw_static_template',
        'Configuration/page.tsconfig',
        'Bw Static Template'
    );
});
