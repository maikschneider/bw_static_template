<?php

namespace Blueways\BwStaticTemplate\Updates;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Install\Updates\RepeatableInterface;
use TYPO3\CMS\Install\Updates\UpgradeWizardInterface;

class PluginUpdateWizard implements UpgradeWizardInterface
{

    public function getIdentifier(): string
    {
        return 'bwStaticTemplate_pluginUpdateWizard';
    }

    public function getTitle(): string
    {
        return 'Migrate Static Template plugins';
    }

    public function getDescription(): string
    {
        return 'Converts all plugin elements of bw_static_template to new custom content elements.';
    }

    public function executeUpdate(): bool
    {
        $ttContentUpdates = $this->getTtContentUpdates();

        foreach ($ttContentUpdates ?? [] as $uid => $update) {
            $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tt_content');
            $queryBuilder
                ->update('tt_content')
                ->set('pi_flexform', null)
                ->set('header', $update['header'])
                ->set('bodytext', $update['bodytext'])
                ->set('CType', 'bw_static_template')
                ->where(
                    $queryBuilder->expr()->eq('uid', $uid)
                )
                ->execute();

            foreach ($update['sysFileReferenceUpdates'] ?? [] as $sysRefUpdate) {
                $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('sys_file_reference');
                $queryBuilder
                    ->update('sys_file_reference')
                    ->set('fieldname', 'assets')
                    ->where(
                        $queryBuilder->expr()->eq('uid', $sysRefUpdate)
                    )
                    ->execute();
            }
        }

        return 1;
    }

    protected function getTtContentUpdates()
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tt_content');
        $queryBuilder->getRestrictions()->removeAll();
        $plugins = $queryBuilder
            ->select('t.uid', 't.pi_flexform', 'r.uid as ruid')
            ->from('tt_content', 't')
            ->innerJoin('t', 'sys_file_reference', 'r',
                $queryBuilder->expr()->eq('r.uid_foreign', $queryBuilder->quoteIdentifier('t.uid')))
            ->where(
                $queryBuilder->expr()->andX(
                    $queryBuilder->expr()->eq('t.list_type',
                        $queryBuilder->createNamedParameter('bwstatictemplate_pi1', \PDO::PARAM_STR)),
                    $queryBuilder->expr()->eq('r.tablenames',
                        $queryBuilder->createNamedParameter('tt_content', \PDO::PARAM_STR)),
                    $queryBuilder->expr()->eq('r.fieldname',
                        $queryBuilder->createNamedParameter('fal', \PDO::PARAM_STR))
                )
            )
            ->execute()
            ->fetchAll();

        $ttContentUpdates = [];

        foreach ($plugins ?? [] as $plugin) {
            $ttContentUpdates[$plugin['uid']] = $ttContentUpdates[$plugin['uid']] ?? [];
            $ttContentUpdates[$plugin['uid']]['sysFileReferenceUpdates'] = $ttContentUpdates[$plugin['uid']]['sysFileReferenceUpdates'] ?? [];
            $ttContentUpdates[$plugin['uid']]['sysFileReferenceUpdates'][] = $plugin['ruid'];

            $ttContentUpdates[$plugin['uid']]['bodytext'] = '{}';
            $flexForm = GeneralUtility::xml2array($plugin['pi_flexform']);
            if (!isset($flexForm['data'], $flexForm['data']['sDEF'], $flexForm['data']['sDEF']['lDEF'])) {
                continue;
            }
            $ttContentUpdates[$plugin['uid']]['header'] = $flexForm['data']['sDEF']['lDEF']['settings.templateName']['vDEF'];
            $ttContentUpdates[$plugin['uid']]['bodytext'] = $flexForm['data']['sDEF']['lDEF']['settings.json']['vDEF'];
        }

        return $ttContentUpdates;
    }

    public function updateNecessary(): bool
    {
        return (bool)count($this->getTtContentUpdates());
    }

    public function getPrerequisites(): array
    {
        return [];
    }
}
