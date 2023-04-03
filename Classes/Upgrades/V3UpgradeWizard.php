<?php

declare(strict_types=1);

namespace Blueways\BwStaticTemplate\Upgrades;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Install\Attribute\UpgradeWizard;
use TYPO3\CMS\Install\Updates\DatabaseUpdatedPrerequisite;
use TYPO3\CMS\Install\Updates\UpgradeWizardInterface;

#[UpgradeWizard('bwStaticTemplate_v3UpgradeWizard')]
final class V3UpgradeWizard implements UpgradeWizardInterface
{
    public function getIdentifier(): string
    {
        return 'bwStaticTemplate_v3UpgradeWizard';
    }

    public function getTitle(): string
    {
        return 'Migrate bw_static_template elements';
    }

    public function getDescription(): string
    {
        return 'Moves tt_content "header" field (used for template name) and "bodytext" (used for json content) to separate fields';
    }

    public function executeUpdate(): bool
    {
        $qb = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tt_content');
        $qb->getRestrictions()->removeAll();
        $qb->update('tt_content')
            ->set('tx_bwstatictemplate_template_path', $qb->quoteIdentifier('header'), false)
            ->set('header', '')
            ->where(
                $qb->expr()->and(
                    $qb->expr()->eq('CType', $qb->createNamedParameter('bw_static_template')),
                    $qb->expr()->eq('tx_bwstatictemplate_template_path', $qb->createNamedParameter('')),
                    $qb->expr()->neq('header', $qb->createNamedParameter(''))
                )
            )
            ->executeStatement();

        $qb = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tt_content');
        $qb->getRestrictions()->removeAll();
        $qb->update('tt_content')
            ->set('tx_bwstatictemplate_json', $qb->quoteIdentifier('bodytext'), false)
            ->set('bodytext', '')
            ->where(
                $qb->expr()->and(
                    $qb->expr()->eq('CType', $qb->createNamedParameter('bw_static_template')),
                    $qb->expr()->isNull('tx_bwstatictemplate_json'),
                    $qb->expr()->neq('bodytext', $qb->createNamedParameter(''))
                )
            )
            ->executeStatement();

        return true;
    }

    public function updateNecessary(): bool
    {
        $qb = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tt_content');
        $qb->getRestrictions()->removeAll();
        $results = $qb->select('uid')
            ->from('tt_content')
            ->where(
                $qb->expr()->or(
                    $qb->expr()->and(
                        $qb->expr()->eq('tx_bwstatictemplate_template_path', $qb->createNamedParameter('')),
                        $qb->expr()->neq('header', $qb->createNamedParameter(''))
                    ),
                    $qb->expr()->and(
                        $qb->expr()->isNull('tx_bwstatictemplate_json'),
                        $qb->expr()->neq('bodytext', $qb->createNamedParameter(''))
                    )
                )
            )
            ->andWhere($qb->expr()->eq('CType', $qb->createNamedParameter('bw_static_template')))
            ->executeQuery();

        return (bool)$results->fetchOne();
    }

    public function getPrerequisites(): array
    {
        return [
            DatabaseUpdatedPrerequisite::class,
        ];
    }
}
