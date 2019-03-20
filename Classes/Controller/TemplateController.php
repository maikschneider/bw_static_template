<?php

namespace Blueways\BwStaticTemplate\Controller;

class TemplateController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    public function showAction()
    {
        $resourceFactory = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Resource\\ResourceFactory');
        $files = array();
        $fileItemUids = $this->settings['files'];
        $fileItemUids = explode(',', $fileItemUids);

        if (!empty($fileItemUids)) {
            $arraySize = sizeof($fileItemUids);
            for ($i = 0; $i < $arraySize; $i++) {

                $itemUid = $fileItemUids[$i];

                $fileReference = $resourceFactory->getFileReferenceObject($itemUid);
                $fileArray = $fileReference->getProperties();
                array_push($files, $fileArray);
            }
        }

        $json = $this->settings['json'];
        if($json) {
            $data = json_decode($json);
            $this->view->assignMultiple((array)$data);
        }

        $this->view->assign('files', $files);
        $this->view->setTemplatePathAndFilename($this->settings['templateName']);
    }
}
