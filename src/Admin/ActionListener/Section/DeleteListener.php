<?php

declare(strict_types=1);

namespace Softspring\CmsSectionsPlugin\Admin\ActionListener\Section;

use Softspring\CmsSectionsPlugin\SfsCmsSectionsEvents;
use Softspring\Component\CrudlController\Event\SuccessEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;

class DeleteListener extends AbstractSectionListener
{
    protected const ACTION_NAME = 'delete';

    public static function getSubscribedEvents(): array
    {
        return [
            // SfsCmsSectionsEvents::ADMIN_SECTIONS_DELETE_INITIALIZE => [],
            // SfsCmsSectionsEvents::ADMIN_SECTIONS_DELETE_LOAD_ENTITY => [],
            SfsCmsSectionsEvents::ADMIN_SECTIONS_DELETE_NOT_FOUND => [
                ['onNotFoundAddFlashAndRedirectToList', 0],
            ],
            // SfsCmsSectionsEvents::ADMIN_SECTIONS_DELETE_FOUND => [],
            // SfsCmsSectionsEvents::ADMIN_SECTIONS_DELETE_FORM_PREPARE => [],
            // SfsCmsSectionsEvents::ADMIN_SECTIONS_DELETE_FORM_INIT => [],
            // SfsCmsSectionsEvents::ADMIN_SECTIONS_DELETE_FORM_VALID => [],
            // SfsCmsSectionsEvents::ADMIN_SECTIONS_DELETE_APPLY => [],
            SfsCmsSectionsEvents::ADMIN_SECTIONS_DELETE_SUCCESS => [
                ['onSuccessAddFlash', 10],
                ['onSuccessRedirect', 0],
            ],
            SfsCmsSectionsEvents::ADMIN_SECTIONS_DELETE_FAILURE => [
                ['onFailureAddFormError', 0],
            ],
            // SfsCmsSectionsEvents::ADMIN_SECTIONS_DELETE_FORM_INVALID => [],
            // SfsCmsSectionsEvents::ADMIN_SECTIONS_DELETE_VIEW => [],
            // SfsCmsSectionsEvents::ADMIN_SECTIONS_DELETE_EXCEPTION => [],
        ];
    }

    public function onSuccessRedirect(SuccessEvent $event): void
    {
        $event->setResponse(new RedirectResponse($this->router->generate('sfs_cms_admin_sections_list')));
    }
}
