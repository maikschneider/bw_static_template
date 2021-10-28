<?php

namespace Blueways\BwStaticTemplate\Controller;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;
use TYPO3\CMS\Core\Resource\ResourceFactory;

class TemplateController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{

    public function showAction(): void
    {

        $files = [];
        $fileItemUids = $this->settings['files'];
        $fileItemUids = explode(',', $fileItemUids);

        if (!empty($fileItemUids) && count($fileItemUids) && $fileItemUids[0] !== "" && $fileItemUids[0] !== "0") {

            $resourceFactory = GeneralUtility::makeInstance(ResourceFactory::class);

            foreach($fileItemUids as $itemUid) {
                $fileReference = $resourceFactory->getFileReferenceObject($itemUid);
                $files[] = $fileReference;
            }
        }

        $json = $this->settings['json'];
        if ($json) {
            $data = json_decode($json, true);
            $this->view->assignMultiple((array)$data);
        }

        $configurationManager = $this->objectManager->get(ConfigurationManager::class);
        $typoscript = $configurationManager->getConfiguration(ConfigurationManager::CONFIGURATION_TYPE_FULL_TYPOSCRIPT);
        $typoscript = $this->replaceKeyDots($typoscript);

        $this->view->assign('typoscript', $typoscript);
        $this->view->assign('files', $files);
        $this->view->setTemplatePathAndFilename($this->settings['templateName']);
    }

    /**
     * @param $arr
     * @return mixed
     */
    private function replaceKeyDots($arr)
    {
        foreach ($arr as $key => $item) {
            if (is_array($item) && substr($key, -1) === '.') {
                $arr[substr($key, 0, -1)] = $this->replaceKeyDots($item);
                unset($arr[$key]);
            }
        }
        return $arr;
    }
}
