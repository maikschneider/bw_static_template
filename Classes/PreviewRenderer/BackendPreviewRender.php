<?php

namespace Blueways\BwStaticTemplate\PreviewRenderer;

use Blueways\BwStaticTemplate\UserFunc\ContentElementUserFunc;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;
use TYPO3\CMS\Backend\Preview\StandardContentPreviewRenderer;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Backend\View\BackendLayout\Grid\GridColumnItem;
use TYPO3\CMS\Core\Domain\RecordInterface;
use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\Page\JavaScriptModuleInstruction;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\TypoScript\TypoScriptService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\MathUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Fluid\View\StandaloneView;

#[Autoconfigure(public: true)]
class BackendPreviewRender extends StandardContentPreviewRenderer
{
    protected string $errorMessage = '';

    protected string $errorTitle = '';
    public function __construct(private readonly PageRenderer $pageRenderer, private readonly ConfigurationManager $configurationManager)
    {
    }

    public function renderPageModulePreviewContent(GridColumnItem $item): string
    {
        $html = '';
        $record = $item->getRecord();

        // v14: getRecord() returns RecordInterface; v13: returns array
        /** @var array<string, string|int|null> $row */
        $row = $record instanceof RecordInterface ? $item->getRow() : $record;

        // custom preview
        if ($row['tx_bwstatictemplate_be_template']) {
            $html = $this->renderFluidBackendTemplate($row);
        }

        // default view
        if (!$row['tx_bwstatictemplate_be_template']) {
            $html = $row['tx_bwstatictemplate_template_path'] ? '<p><strong>' . $row['tx_bwstatictemplate_template_path'] . '</strong></p>' : '';
            $html = $this->linkEditContent($html, $record);
            $pageRenderer = $this->pageRenderer;
            $pageRenderer->getJavaScriptRenderer()->addJavaScriptModuleInstruction(
                JavaScriptModuleInstruction::create('@maikschneider/bw-static-template/backend.js')->instance()
            );
            $html .= $this->renderTablePreview($row, $record);
        }

        // error view
        if ($this->errorMessage) {
            $html = '<div class="callout callout-danger"><div class="callout-icon"></div><div class="callout-content"><h3>' . $this->errorTitle . '</h3><p class="mb-0">' . $this->errorMessage . '</p></div></div>';
        }

        // append images (not in custom preview)
        if (!$row['tx_bwstatictemplate_be_template'] && $row['assets']) {
            if ($record instanceof RecordInterface) {
                // TYPO3 v14: thumbCode removed, use getThumbCodeUnlinked via RecordInterface
                if ($record->has('assets') && ($assets = $record->get('assets'))) {
                    /** @var iterable<\TYPO3\CMS\Core\Resource\FileReference>|\TYPO3\CMS\Core\Resource\FileReference $assets */
                    $image = $this->getThumbCodeUnlinked($assets);
                }
            } else {
                // TYPO3 v13
                /** @phpstan-ignore staticMethod.notFound */
                $image = (string)BackendUtility::thumbCode(
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
            $html .= $this->linkEditContent($image, $record);
        }

        return $html;
    }

    /**
    * @param array<string, string|int|null> $row
    * @param RecordInterface|array<string, string|int|null> $record
    */
    protected function renderTablePreview(array $row, RecordInterface|array $record): string
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
        if ($record instanceof RecordInterface) {
            $content .= $this->linkEditContent($table, $record);
        }
        $content .= '</div>';
        $moreIcon = '<span class="icon icon-size-small icon-state-default"><svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve" viewBox="0 0 16 16"><g class="icon-color"><path d="m4.464 6.05-.707.707L8 11l4.243-4.243-.707-.707L8 9.586z"/></g></svg></span>';
        $moreText = $this->getLanguageService()->sL('LLL:EXT:bw_static_template/Resources/Private/Language/locallang.xlf:preview.more');
        $content .= '<p class="text-center"><button type="button" data-json-table="jsonTable' . $row['uid'] . '" class="btn btn-sm btn-link">' . $moreIcon . $moreText . '</button></p>';

        return $content;
    }

    /**
    * @param array<string, string|int|null> $row
    * @return array<string, mixed>
    */
    protected function getJson(array $row): array
    {
        $jsonText = '';

        // fetch from file (or remote)
        if ($row['tx_bwstatictemplate_from_file'] && $row['tx_bwstatictemplate_file_path']) {
            $filePathOrUrl = (string)$row['tx_bwstatictemplate_file_path'];
            $isRemoteUrl = GeneralUtility::isValidUrl($filePathOrUrl);

            if ($isRemoteUrl) {
                try {
                    $jsonText = GeneralUtility::getUrl($filePathOrUrl);
                } catch (\Exception $e) {
                    $this->errorTitle = 'Error loading JSON';
                    $this->errorMessage = 'Could not fetch data from remote "' . $filePathOrUrl . '"';
                    return [];
                }
            }

            if (!$isRemoteUrl) {
                $filePath = GeneralUtility::getFileAbsFileName($filePathOrUrl);
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
            $jsonText = (string)$row['tx_bwstatictemplate_json'];
        }

        // empty json
        if (!$jsonText) {
            return [];
        }

        // decode
        try {
            /** @var array<string, mixed> $decoded */
            $decoded = json_decode($jsonText, true, 512, JSON_THROW_ON_ERROR);
            return $decoded;
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
        /** @var LanguageService $languageService */
        $languageService = $GLOBALS['LANG'] ?? GeneralUtility::makeInstance(LanguageService::class);
        return $languageService;
    }

    /**
    * @param array<string, string|int|null> $row
    */
    protected function renderFluidBackendTemplate(array $row): string
    {
        /** @var array<string, mixed> $typoScript */
        $typoScript = $this->configurationManager->getConfiguration(ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT);
        $typoScriptService = GeneralUtility::makeInstance(TypoScriptService::class);
        /** @var array<string, mixed> $libConfig */
        $libConfig = $typoScript['lib.'] ?? [];
        /** @var array{templateRootPaths: array<int, string>, partialRootPaths: array<int, string>, layoutRootPaths: array<int, string>} $viewSettings */
        $viewSettings = $typoScriptService->convertTypoScriptArrayToPlainArray((array)($libConfig['contentElement.'] ?? []));
        $templateName = (string)$row['tx_bwstatictemplate_be_template'];

        // set template name and root path from EXT:name/Resources/Private/Templates/Show.html
        if (str_starts_with($templateName, 'EXT:')) {
            $templateRootPath = ContentElementUserFunc::getTemplateRootPathFromPath($templateName);
            $viewSettings['templateRootPaths'][9999999999] = $templateRootPath;
            $templateName = ContentElementUserFunc::getTemplateNameFromPath($templateName);
        }

        if (class_exists(StandaloneView::class)) {
            // TYPO3 v12/v13
            $view = GeneralUtility::makeInstance(StandaloneView::class);
            $view->getRenderingContext()->getTemplatePaths()->setTemplateRootPaths($viewSettings['templateRootPaths']);
            $view->getRenderingContext()->getTemplatePaths()->setLayoutRootPaths($viewSettings['layoutRootPaths']);
            $view->getRenderingContext()->getTemplatePaths()->setPartialRootPaths($viewSettings['partialRootPaths']);
            $view->getRenderingContext()->setControllerAction($templateName);
        } else {
            // TYPO3 v14+ (StandaloneView removed)
            $viewFactoryData = new \TYPO3\CMS\Core\View\ViewFactoryData(
                templateRootPaths: $viewSettings['templateRootPaths'],
                partialRootPaths: $viewSettings['partialRootPaths'],
                layoutRootPaths: $viewSettings['layoutRootPaths'],
                request: ($GLOBALS['TYPO3_REQUEST'] ?? null) instanceof \Psr\Http\Message\ServerRequestInterface ? $GLOBALS['TYPO3_REQUEST'] : null,
            );
            /** @var \TYPO3\CMS\Core\View\ViewFactoryInterface $viewFactory */
            $viewFactory = GeneralUtility::getContainer()->get(\TYPO3\CMS\Core\View\ViewFactoryInterface::class);
            $view = $viewFactory->create($viewFactoryData);
        }

        /** @var PageRenderer $pageRenderer */
        $pageRenderer = $this->pageRenderer;
        $pageRenderer->getJavaScriptRenderer()->addJavaScriptModuleInstruction(
            JavaScriptModuleInstruction::create('@maikschneider/bw-static-template/backend.js')->instance($row['uid'])
        );

        // insert data
        $json = $this->getJson($row);
        foreach ($json as $key => $value) {
            $view->assign($key, $value);
        }

        if ($this->errorMessage) {
            return '';
        }

        try {
            $html = class_exists(StandaloneView::class) ? $view->render() : $view->render($templateName);
            return is_string($html) ? $html : '';
        } catch (\Exception $e) {
            $this->errorTitle = 'Error rendering preview';
            $this->errorMessage = $e->getMessage();
        }

        return '';
    }
}
