<?php

namespace Blueways\BwStaticTemplate\DataProcessing;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\DataProcessorInterface;

class JsonProcessor implements DataProcessorInterface
{
    /**
    * @param array<string, string> $contentObjectConfiguration
    * @param array<string, string> $processorConfiguration
    * @param array<string, mixed> $processedData
    * @return array<string, mixed>
    */
    public function process(
        ContentObjectRenderer $cObj,
        array $contentObjectConfiguration,
        array $processorConfiguration,
        array $processedData
    ): array {
        /** @var array<string, string|int|null> $data */
        $data = $processedData['data'] ?? [];
        $jsonText = '';

        if (!$data['tx_bwstatictemplate_from_file'] && $data['tx_bwstatictemplate_json']) {
            $jsonText = (string)$data['tx_bwstatictemplate_json'];
        }

        if ($data['tx_bwstatictemplate_from_file'] && $data['tx_bwstatictemplate_file_path']) {
            $filePath = (string)$data['tx_bwstatictemplate_file_path'];
            if (str_starts_with($filePath, 'http')) {
                $jsonText = GeneralUtility::getUrl($filePath);
            } else {
                $absPath = GeneralUtility::getFileAbsFileName($filePath);
                $jsonText = $absPath ? file_get_contents($absPath) : '';
            }
        }

        if ($jsonText) {
            /** @var array<string, mixed>|null $json */
            $json = json_decode((string)$jsonText, true);
            if ($json !== null) {
                /** @var array<string, mixed> $merged */
                $merged = array_merge_recursive($processedData, $json);
                $processedData = $merged;
            }
        }

        return $processedData;
    }
}
