<?php

declare(strict_types=1);

namespace Softspring\CmsSectionsPlugin\Admin\ActionListener\SectionVersion;

use Softspring\CmsSectionsPlugin\SfsCmsSectionsEvents;
use Softspring\Component\CrudlController\Event\FormPrepareEvent;

class ListListener extends AbstractSectionVersionListener
{
    protected const ACTION_NAME = 'version_list';

    public static function getSubscribedEvents(): array
    {
        return [
            SfsCmsSectionsEvents::ADMIN_SECTION_VERSIONS_LIST_INITIALIZE => [
                ['onLoadSectionEntity', 9],
            ],
            SfsCmsSectionsEvents::ADMIN_SECTION_VERSIONS_LIST_FILTER_FORM_PREPARE => [
                ['onFilterFormPrepareResolve', 0],
            ],
            // SfsCmsSectionsEvents::ADMIN_SECTION_VERSIONS_LIST_FILTER_FORM_INIT => [],
            // SfsCmsSectionsEvents::ADMIN_SECTION_VERSIONS_LIST_FILTER => [],
            SfsCmsSectionsEvents::ADMIN_SECTION_VERSIONS_LIST_VIEW => [
                ['onViewAddEntities', 10],
            ],
            // SfsCmsSectionsEvents::ADMIN_SECTION_VERSIONS_LIST_EXCEPTION => [],
        ];
    }

    public function onFilterFormPrepareResolve(FormPrepareEvent $event): void
    {
        $event->setFormOptions([
            'method' => 'GET',
            'section' => $event->getRequest()->attributes->get('section'),
        ]);
    }

    //    public function onView(ViewEvent $event): void
    //    {
    //        parent::onView($event);
    //
    //        $event->getData()['list_page_view'] = $this->getOption($event->getRequest(), 'page_view');
    //        // 'filterForm' => $form->createView(),
    //        // 'read_route' => $config['read_route'] ?? null,
    //
    //        if ($event->getRequest()->isXmlHttpRequest()) {
    //            $event->setTemplate($this->getOption($event->getRequest(), 'page_view'));
    //        }
    //    }
}
