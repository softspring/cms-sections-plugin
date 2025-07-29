<?php

namespace Softspring\CmsSectionsPlugin\Admin\ActionListener\Section;

use Softspring\CmsSectionsPlugin\SfsCmsSectionsEvents;
use Softspring\Component\CrudlController\Event\ApplyEvent;
use Softspring\Component\CrudlController\Event\SuccessEvent;

class UnpublishListener extends AbstractSectionListener
{
    protected const ACTION_NAME = 'unpublish';

    public static function getSubscribedEvents(): array
    {
        return [
            // SfsCmsSectionsEvents::ADMIN_SECTIONS_UNPUBLISH_INITIALIZE => [],
            // SfsCmsSectionsEvents::ADMIN_SECTIONS_UNPUBLISH_LOAD_ENTITY => [],
            SfsCmsSectionsEvents::ADMIN_SECTIONS_UNPUBLISH_NOT_FOUND => [
                ['onNotFoundAddFlashAndRedirectToList', 0],
            ],
            // SfsCmsSectionsEvents::ADMIN_SECTIONS_UNPUBLISH_FOUND => [],
            SfsCmsSectionsEvents::ADMIN_SECTIONS_UNPUBLISH_APPLY => [
                ['onApplyUnpublish', 0],
            ],
            SfsCmsSectionsEvents::ADMIN_SECTIONS_UNPUBLISH_SUCCESS => [
                ['onSuccessAddFlash', 10],
                ['onSuccessRedirectBack', 0],
            ],
            SfsCmsSectionsEvents::ADMIN_SECTIONS_UNPUBLISH_FAILURE => [
                ['onFailureAddFlash', 10],
                ['onFailureRedirectBack', 0],
            ],
            // SfsCmsSectionsEvents::ADMIN_SECTIONS_UNPUBLISH_EXCEPTION => [],
        ];
    }

    public function onApplyUnpublish(ApplyEvent $event): void
    {
        $entity = $event->getEntity();
        $entity->setPublishedVersion(null);
        $this->sectionManager->saveEntity($entity);
        $event->setApplied(true);
    }

    public function onSuccessAddFlash(SuccessEvent $event): void
    {
        $this->flashNotifier->addTrans('success', 'admin_sections.unpublish.has_been_unpublished_flash', [], 'sfs_cms_admin');
    }
}
