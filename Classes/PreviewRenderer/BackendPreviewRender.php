<?php

namespace Blueways\BwStaticTemplate\PreviewRenderer;

use Blueways\BwStaticTemplate\UserFunc\ContentElementUserFunc;
use TYPO3\CMS\Backend\Preview\StandardContentPreviewRenderer;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Backend\View\BackendLayout\Grid\GridColumnItem;
use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\TypoScript\TypoScriptService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\MathUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Configuration\Exception\InvalidConfigurationTypeException;
use TYPO3\CMS\Fluid\View\StandaloneView;

class BackendPreviewRender extends StandardContentPreviewRenderer
{
    protected string $errorMessage = '';

    protected string $errorTitle = '';

    public function renderPageModulePreviewContent(GridColumnItem $item): string
    {
        $html = '';
        $row = $item->getRecord();

        // custom preview
        if ($row['tx_bwstatictemplate_be_template']) {
            $html = $this->renderFluidBackendTemplate($row);
        }

        // default view
        if (!$row['tx_bwstatictemplate_be_template']) {
            $html = $row['tx_bwstatictemplate_template_path'] ? '<p><strong>' . $row['tx_bwstatictemplate_template_path'] . '</strong></p>' : '';
            $html .= $this->renderTablePreview($row);
        }

        // error view
        if ($this->errorMessage) {
            $html = '<div class="callout callout-danger"><p class="callout-title">' . $this->errorTitle . '</p> ' . $this->errorMessage . '</div>';
        }

        // append images (not in custom preview)
        if (!$row['tx_bwstatictemplate_be_template'] && $row['assets']) {
            $html .= BackendUtility::thumbCode(
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
        }

        return $this->linkEditContent($html, $row);
    }

    /**
     * @param array<string, mixed> $row
     */
    protected function renderTablePreview(array $row): string
    {
        $json = $this->getJson($row);
        $table = $this->getJsonAsTable($json);
        $jsonDepth = $this->getJsonDepth($json);

        if ($this->errorMessage) {
            return '';
        }

        // full table
        if ($jsonDepth < 10) {
            return $table;
        }

        // crop table
        $content = '<div class="jsonTablePreview jsonTablePreview--hidden" id="jsonTable' . $row['uid'] . '">';
        $content .= $this->linkEditContent($table, $row);
        $content .= '</div>';
        $onClick = 'document.getElementById(\'jsonTable' . $row['uid'] . '\').classList.remove(\'jsonTablePreview--hidden\')';
        $moreIcon = '<span class="icon icon-size-small icon-state-default"><svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve" viewBox="0 0 16 16"><g class="icon-color"><path d="m4.464 6.05-.707.707L8 11l4.243-4.243-.707-.707L8 9.586z"/></g></svg></span>';
        $moreText = $this->getLanguageService()->sL('LLL:EXT:bw_static_template/Resources/Private/Language/locallang.xlf:preview.more');
        $content .= '<p class="text-center"><button onclick="' . $onClick . '" type="button" class="btn btn-sm btn-link">' . $moreIcon . $moreText . '</button></p>';

        return $content;
    }

    /**
     * @param array<string, mixed> $row
     * @return array<string, mixed>
     */
    protected function getJson(array $row): array
    {
        $jsonText = '';

        // fetch from file (or remote)
        if ($row['tx_bwstatictemplate_from_file'] && $row['tx_bwstatictemplate_file_path']) {
            $isRemoteUrl = strpos($row['tx_bwstatictemplate_file_path'], 'http') === 0;

            if ($isRemoteUrl) {
                $fileUrl = $row['tx_bwstatictemplate_file_path'];
                try {
                    $jsonText = GeneralUtility::getUrl($fileUrl);
                } catch (\Exception $e) {
                    $this->errorTitle = 'Error loading JSON';
                    $this->errorMessage = 'Could not fetch data from remote "' . $fileUrl . '"';
                    return [];
                }
            }

            if (!$isRemoteUrl) {
                $filePath = GeneralUtility::getFileAbsFileName($row['tx_bwstatictemplate_file_path']);
                if (file_exists($filePath)) {
                    $jsonText = file_get_contents($filePath);
                } else {
                    $this->errorTitle = 'Error loading JSON';
                    $this->errorMessage = 'Could not find file "' . $filePath . '"';
                    return [];
                }
            }
        }

        // use from database
        if (!$row['tx_bwstatictemplate_from_file'] && $row['tx_bwstatictemplate_json']) {
            $jsonText = $row['tx_bwstatictemplate_json'];
        }

        // empty json
        if (!$jsonText) {
            return [];
        }

        // decode
        try {
            return (array)json_decode($jsonText, true, 512, JSON_THROW_ON_ERROR);
        } catch (\Exception $e) {
            $this->errorTitle = 'Error decoding JSON';
            $this->errorMessage = 'No valid JSON data';
        }

        return [];
    }

    /**
     * @param array<string, mixed> $json
     */
    protected function getJsonAsTable(array $json): string
    {
        $content = '<table class="table table-striped">';
        foreach ($json as $key => $entry) {
            $content .= self::getTableRow($key, $entry);
        }
        $content .= '</table>';

        return $content;
    }

    /**
     * @param mixed $key
     * @param mixed $value
     */
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
            if (is_bool($value)) {
                $html .= var_export($value, true);
            } elseif ($value === null) {
                $html .= 'null';
            } else {
                $html .= $value;
            }
        }
        $html .= '</td>';
        $html .= '</tr>';
        return $html;
    }

    /**
     * @param array<string, mixed> $json
     */
    protected function getJsonDepth(array $json): int
    {
        $maxDepth = 0;
        foreach ($json as $value) {
            $maxDepth++;
            $this->checkArrayDepthOfNextLevel($value, $maxDepth);
        }
        return $maxDepth;
    }

    /**
     * @param mixed $value
     */
    protected function checkArrayDepthOfNextLevel($value, int &$maxDepth): void
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

    /**
     * @param array<string, mixed> $row
     * @throws InvalidConfigurationTypeException
     */
    protected function renderFluidBackendTemplate(array $row): string
    {
        $typoScript = GeneralUtility::makeInstance(ConfigurationManager::class)->getConfiguration(ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT);
        $typoScriptService = GeneralUtility::makeInstance(TypoScriptService::class);
        $viewSettings = $typoScriptService->convertTypoScriptArrayToPlainArray($typoScript['lib.']['contentElement.']);
        $templateName = $row['tx_bwstatictemplate_be_template'];

        // set template name and root path from EXT:name/Resources/Private/Templates/Show.html
        if (strpos($templateName, 'EXT:') === 0) {
            $templateRootPath = ContentElementUserFunc::getTemplateRootPathFromPath($templateName);
            $viewSettings['templateRootPaths'][9999999999] = $templateRootPath;
            $templateName = ContentElementUserFunc::getTemplateNameFromPath($templateName);
        }

        $view = GeneralUtility::makeInstance(StandaloneView::class);
        $view->setTemplateRootPaths($viewSettings['templateRootPaths']);
        $view->setLayoutRootPaths($viewSettings['layoutRootPaths']);
        $view->setPartialRootPaths($viewSettings['partialRootPaths']);
        $view->setTemplate($templateName);

        // insert data
        $json = $this->getJson($row);
        foreach ($json as $key => $value) {
            $view->assign($key, $value);
        }

        if ($this->errorMessage) {
            return '';
        }

        try {
            return $view->render() ?? '';
        } catch (\Exception $e) {
            $this->errorTitle = 'Error rendering preview';
            $this->errorMessage = $e->getMessage();
        }

        return '';
    }
}
