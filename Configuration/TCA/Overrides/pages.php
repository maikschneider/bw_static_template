<?php

call_user_func(function () {
    /**
     * Default PageTS
     */
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::registerPageTSConfigFile(
        'bw_static_template',
        'Configuration/PageTS/All.txt',
        'Bw Static Template'
    );
});
