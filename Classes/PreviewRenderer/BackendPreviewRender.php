<?php

namespace Blueways\BwStaticTemplate\PreviewRenderer;

use TYPO3\CMS\Backend\Preview\StandardContentPreviewRenderer;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Backend\View\BackendLayout\Grid\GridColumnItem;
use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\MathUtility;

class BackendPreviewRender extends StandardContentPreviewRenderer
{
    public function renderPageModulePreviewContent(GridColumnItem $item): string
    {
        $content = '';
        $row = $item->getRecord();

        if ($row['bodytext']) {
            $table = $this->getJsonAsTable($row['bodytext']);
            $jsonDepth = $this->getJsonDepth($row['bodytext']);

            if ($jsonDepth < 10) {
                $content .= $this->linkEditContent($table, $row);
            } else {
                $content .= '<div class="jsonTablePreview jsonTablePreview--hidden" id="jsonTable' . $row['uid'] . '">';
                $content .= $this->linkEditContent($table, $row);
                $content .= '</div>';
                $onClick = 'document.getElementById(\'jsonTable' . $row['uid'] . '\').classList.remove(\'jsonTablePreview--hidden\')';
                $moreIcon = '<span class="icon icon-size-small icon-state-default"><svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve" viewBox="0 0 16 16"><g class="icon-color"><path d="m4.464 6.05-.707.707L8 11l4.243-4.243-.707-.707L8 9.586z"/></g></svg></span>';
                $moreText = $this->getLanguageService()->sL('LLL:EXT:bw_static_template/Resources/Private/Language/locallang.xlf:preview.more');
                $content .= '<p class="text-center"><button onclick="' . $onClick . '" type="button" class="btn btn-sm btn-link">' . $moreIcon . $moreText . '</button></p>';
            }
        }

        if ($row['assets']) {
            $assets = BackendUtility::thumbCode(
                $row,
                'tt_content',
                'assets',
                '',
                '',
                null,
                0,
                '',
                '',
                false
            );
            $content .= $this->linkEditContent($assets, $row);
        }

        return $content;
    }

    protected function getJsonAsTable(string $bodytext): string
    {
        try {
            $jsonData = json_decode($bodytext, true);
            $content = '<table class="table table-striped">';
            foreach ($jsonData as $key => $entry) {
                $content .= self::getTableRow($key, $entry);
            }
            $content .= '</table>';
            return $content;
        } catch (\Exception $e) {
            $message = $this->getLanguageService()->sL('LLL:EXT:bw_static_template/Resources/Private/Language/locallang.xlf:preview.jsonError');
            return '<div class="callout callout-danger">' . $message . '</div>';
        }
    }

    protected static function getTableRow($key, $value): string
    {
        $html = '<tr>';
        if (!MathUtility::canBeInterpretedAsInteger($key)) {
            $html .= '<td>' . $key . '</td>';
        }
        $html .= '<td>';
        if (is_array($value)) {
            $html .= '<table class="table table-striped table-sm">';
            foreach ($value as $key => $entry) {
                $html .= self::getTableRow($key, $entry);
            }
            $html .= '</table>';
        } else {
            $html .= $value;
        }
        $html .= '</td>';
        $html .= '</tr>';
        return $html;
    }

    protected function getJsonDepth(string $bodytext): int
    {
        try {
            $jsonData = json_decode($bodytext, true);

            $maxDepth = 0;
            foreach ($jsonData as $value) {
                $maxDepth++;
                $this->checkArrayDepthOfNextLevel($value, $maxDepth);
            }
            return $maxDepth;
        } catch (\Exception $e) {
            return 0;
        }
    }

    protected function checkArrayDepthOfNextLevel($value, &$maxDepth)
    {
        if (is_array($value)) {
            foreach ($value as $key2 => $deeperValue) {
                if ($key2) {
                    $maxDepth++;
                }
                $this->checkArrayDepthOfNextLevel($deeperValue, $maxDepth);
            }
        }
    }

    protected function getLanguageService(): LanguageService
    {
        return $GLOBALS['LANG'] ?? GeneralUtility::makeInstance(LanguageService::class);
    }
}
