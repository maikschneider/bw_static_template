<?php

namespace Blueways\BwStaticTemplate\UserFunc;

class ContentElementUserFunc
{
    public function getTemplateRootPath(string $content): string
    {
        if (strpos($content, 'EXT:') === 0) {
            $pathSegments = explode('/', $content) ?: [];
            array_pop($pathSegments);
            return implode('/', $pathSegments);
        }
        return '';
    }

    public function getTemplateName(string $content): string
    {
        if (strpos($content, 'EXT:') === 0) {
            $pathSegments = explode('/', $content) ?: [];
            return array_pop($pathSegments);
        }
        return $content;
    }
}
