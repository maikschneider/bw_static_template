<?php

namespace Blueways\BwStaticTemplate\Controller;

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

        $this->view->assign('files', $files);
        $this->view->setTemplatePathAndFilename($this->settings['templateName']);
    }
}
