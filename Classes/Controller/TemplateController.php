<?php

namespace Blueways\BwStaticTemplate\Controller;

use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;

class TemplateController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{

    /**
     * Render the plugin with selected template
     */
    public function showAction()
    {

        $files = [];
        $fileItemUids = $this->settings['files'];
        $fileItemUids = explode(',', $fileItemUids);

        if (!empty($fileItemUids) && sizeof($fileItemUids) && $fileItemUids[0] !== "") {

            /** @var \TYPO3\CMS\\Core\Resource\ResourceFactory $resourceFactory */
            $resourceFactory = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Resource\\ResourceFactory');

            for ($i = 0; $i < sizeof($fileItemUids); $i++) {
                $itemUid = $fileItemUids[$i];
                $fileReference = $resourceFactory->getFileReferenceObject($itemUid);
                array_push($files, $fileReference);
            }
        }

        $json = $this->settings['json'];
        if ($json) {
            $data = json_decode($json);
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
