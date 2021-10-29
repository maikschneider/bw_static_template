<?php

namespace Blueways\BwStaticTemplate\DataProcessing;

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
        if ($processedData['data']['bodytext']) {
            $json = json_decode($processedData['data']['bodytext'], true);
            if ($json !== null) {
                $processedData = array_merge($processedData, (array)$json);
            }
        }

        return $processedData;
    }
}
