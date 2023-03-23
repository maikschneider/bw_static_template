<?php

namespace Blueways\BwStaticTemplate\DataProcessing;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\DataProcessorInterface;

class JsonProcessor implements DataProcessorInterface
{
    /**
     * Process data of a record to resolve File objects to the view
     *
     * @param ContentObjectRenderer $cObj The data of the content element or page
     * @param array $contentObjectConfiguration The configuration of Content Object
     * @param array $processorConfiguration The configuration of this processor
     * @param array $processedData Key/value store of processed data (e.g. to be passed to a Fluid View)
     * @return array the processed data as key/value store
     */
    public function process(
        ContentObjectRenderer $cObj,
        array $contentObjectConfiguration,
        array $processorConfiguration,
        array $processedData
    ): array {
        $jsonText = '';

        if (!$processedData['data']['tx_bwstatictemplate_from_file'] && $processedData['data']['bodytext']) {
            $jsonText = $processedData['data']['bodytext'];
        }

        if ($processedData['data']['tx_bwstatictemplate_from_file'] && $processedData['data']['tx_bwstatictemplate_file_path']) {
            if (strpos($processedData['data']['tx_bwstatictemplate_file_path'], 'http') === 0) {
                $filePath = $processedData['data']['tx_bwstatictemplate_file_path'];
            } else {
                $filePath = GeneralUtility::getFileAbsFileName($processedData['data']['tx_bwstatictemplate_file_path']);
            }
            $jsonText = $filePath ? file_get_contents($filePath) : '';
        }

        if ($jsonText) {
            $json = json_decode($jsonText, true);
            if ($json !== null) {
                $processedData = array_merge_recursive($processedData, (array)$json);
            }
        }

        return $processedData;
    }
}
