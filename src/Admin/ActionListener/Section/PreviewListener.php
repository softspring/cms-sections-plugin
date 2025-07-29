<?php

namespace Softspring\CmsSectionsPlugin\Admin\ActionListener\Section;

use Softspring\CmsBundle\Utils\SitesSorter;
use Softspring\CmsSectionsPlugin\Model\SectionInterface;
use Softspring\CmsSectionsPlugin\SfsCmsSectionsEvents;
use Softspring\Component\CrudlController\Event\ViewEvent;

class PreviewListener extends AbstractSectionListener
{
    protected const ACTION_NAME = 'preview';

    public static function getSubscribedEvents(): array
    {
        return [
            // SfsCmsSectionsEvents::ADMIN_SECTIONS_PREVIEW_INITIALIZE => [],
            // SfsCmsSectionsEvents::ADMIN_SECTIONS_PREVIEW_LOAD_ENTITY => [],
            SfsCmsSectionsEvents::ADMIN_SECTIONS_PREVIEW_NOT_FOUND => [
                ['onNotFoundAddFlashAndRedirectToList', 0],
            ],
            // SfsCmsSectionsEvents::ADMIN_SECTIONS_PREVIEW_FOUND => [],
            SfsCmsSectionsEvents::ADMIN_SECTIONS_PREVIEW_VIEW => [
                ['onViewAddSectionEntity', 10],
                ['onViewAddHelpers', 0],
            ],
            // SfsCmsSectionsEvents::ADMIN_SECTIONS_PREVIEW_EXCEPTION => [],
        ];
    }

    public function onViewAddHelpers(ViewEvent $event): void
    {
        /** @var SectionInterface $section */
        $section = $event->getRequest()->attributes->get('section');

        if ($event->getRequest()->query->get('version')) {
            $version = $this->sectionVersionManager->getRepository()->findOneBy([
                'section' => $section,
                'id' => $event->getRequest()->query->get('version'),
            ]);
        }

        $version = $version ?? $section->getLastVersion();

        $event->getData()['version'] = $version;
        $event->getData()['availableLocales'] = $this->cmsHelper->locale()->getEnabledLocales();
        $event->getData()['defaultLocale'] = $this->cmsHelper->locale()->getDefaultLocale();
        $event->getData()['sites'] = SitesSorter::sort($this->cmsHelper->config()->getSites());
    }
}
