<?php

declare(strict_types=1);

namespace Softspring\CmsSectionsPlugin\Admin\ActionListener\Section;

class ListListener extends AbstractSectionListener
{
    protected const ACTION_NAME = 'list';

    public static function getSubscribedEvents(): array
    {
        return [
            // SfsCmsSectionsEvents::ADMIN_SECTIONS_LIST_INITIALIZE => [],
            // SfsCmsSectionsEvents::ADMIN_SECTIONS_LIST_FILTER_FORM_PREPARE => [],
            // SfsCmsSectionsEvents::ADMIN_SECTIONS_LIST_FILTER_FORM_INIT => [],
            // SfsCmsSectionsEvents::ADMIN_SECTIONS_LIST_FILTER => [],
            // SfsCmsSectionsEvents::ADMIN_SECTIONS_LIST_VIEW => [],
            // SfsCmsSectionsEvents::ADMIN_SECTIONS_LIST_EXCEPTION => [],
        ];
    }
}
