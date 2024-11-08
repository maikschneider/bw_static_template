<?php

namespace Blueways\BwStaticTemplate\UserFunc;

use TYPO3\CMS\Core\Utility\GeneralUtility;

class ContentElementUserFunc
{
    public function getTemplateRootPath(string $content): string
    {
        if (str_starts_with($content, 'EXT:')) {
            return self::getTemplateRootPathFromPath($content);
        }
        return '';
    }

    public static function getTemplateRootPathFromPath(string $path): string
    {
        $pathSegments = GeneralUtility::trimExplode('/', $path, true);
        array_pop($pathSegments);
        return implode('/', $pathSegments);
    }

    public function getTemplateName(string $content): string
    {
        if (str_starts_with($content, 'EXT:')) {
            return self::getTemplateNameFromPath($content);
        }
        return $content;
    }

    public static function getTemplateNameFromPath(string $path): string
    {
        $pathSegments = GeneralUtility::trimExplode('/', $path, true);
        return array_pop($pathSegments) ?? '';
    }
}
