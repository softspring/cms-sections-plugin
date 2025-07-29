<?php

namespace Softspring\CmsSectionsPlugin\Admin\ActionListener\Section;

use Softspring\CmsSectionsPlugin\SfsCmsSectionsEvents;
use Softspring\Component\CrudlController\Event\ApplyEvent;

class CleanupVersionsListener extends AbstractSectionListener
{
    protected const ACTION_NAME = 'version_cleanup';

    public static function getSubscribedEvents(): array
    {
        return [
            // SfsCmsSectionsEvents::ADMIN_SECTION_CLEANUP_VERSIONS_INITIALIZE => [],
            // SfsCmsSectionsEvents::ADMIN_SECTION_CLEANUP_VERSIONS_LOAD_ENTITY => [],
            SfsCmsSectionsEvents::ADMIN_SECTION_CLEANUP_VERSIONS_NOT_FOUND => [
                ['onNotFoundAddFlashAndRedirectToList', 0],
            ],
            // SfsCmsSectionsEvents::ADMIN_SECTION_CLEANUP_VERSIONS_FOUND => [],
            SfsCmsSectionsEvents::ADMIN_SECTION_CLEANUP_VERSIONS_APPLY => [
                ['onApplyCleanupVersions', 0],
            ],
            SfsCmsSectionsEvents::ADMIN_SECTION_CLEANUP_VERSIONS_SUCCESS => [
                ['onSuccessAddFlash', 10],
                ['onSuccessRedirectBack', 0],
            ],
            // SfsCmsSectionsEvents::ADMIN_SECTION_CLEANUP_VERSIONS_FAILURE => [],
            // SfsCmsSectionsEvents::ADMIN_SECTION_CLEANUP_VERSIONS_EXCEPTION => [],
        ];
    }

    public function onApplyCleanupVersions(ApplyEvent $event): void
    {
        $entity = $event->getEntity();

        foreach ($entity->getVersions() as $version) {
            if ($version->deleteOnCleanup()) {
                $entity->removeVersion($version); // TODO THIS SHOULD REMOVE VERSIONS
                $this->sectionVersionManager->deleteEntity($version);
            }
        }
        $event->setApplied(true);
    }
}
