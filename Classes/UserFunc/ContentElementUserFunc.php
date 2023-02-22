<?php

namespace Blueways\BwStaticTemplate\UserFunc;

class ContentElementUserFunc
{
    public function getTemplateRootPath(string $content): string
    {
        if (strpos($content, 'EXT:') === 0) {
            return self::getTemplateRootPathFromPath($content);
        }
        return '';
    }

    public function getTemplateName(string $content): string
    {
        if (strpos($content, 'EXT:') === 0) {
            return self::getTemplateNameFromPath($content);
        }
        return $content;
    }

    public static function getTemplateRootPathFromPath(string $path): string
    {
        $pathSegments = explode('/', $path);
        array_pop($pathSegments);
        return implode('/', $pathSegments);
    }

    public static function getTemplateNameFromPath(string $path): string
    {
        $pathSegments = explode('/', $path);
        return array_pop($pathSegments);
    }
}
