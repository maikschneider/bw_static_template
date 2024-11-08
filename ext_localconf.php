<?php

use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

$versionInformation = GeneralUtility::makeInstance(Typo3Version::class);
if ($versionInformation->getMajorVersion() < 12) {
    ExtensionManagementUtility::addPageTSConfig('
      @import "EXT:bw_static_template/Configuration/page.tsconfig"
   ');
}
