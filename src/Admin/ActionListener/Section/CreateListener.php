<?php

declare(strict_types=1);

namespace Softspring\CmsSectionsPlugin\Admin\ActionListener\Section;

use Softspring\CmsSectionsPlugin\SfsCmsSectionsEvents;
use Softspring\Component\CrudlController\Event\SuccessEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;

class CreateListener extends AbstractSectionListener
{
    protected const ACTION_NAME = 'create';

    public static function getSubscribedEvents(): array
    {
        return [
            // SfsCmsSectionsEvents::ADMIN_SECTIONS_CREATE_INITIALIZE => [],
            // SfsCmsSectionsEvents::ADMIN_SECTIONS_CREATE_ENTITY => [],
            // SfsCmsSectionsEvents::ADMIN_SECTIONS_CREATE_FORM_PREPARE => [],
            // SfsCmsSectionsEvents::ADMIN_SECTIONS_CREATE_FORM_INIT => [],
            // SfsCmsSectionsEvents::ADMIN_SECTIONS_CREATE_FORM_VALID => [],
            // SfsCmsSectionsEvents::ADMIN_SECTIONS_CREATE_APPLY => [],
            SfsCmsSectionsEvents::ADMIN_SECTIONS_CREATE_SUCCESS => [
                ['onSuccessAddFlash', 10],
                ['onSuccessRedirect', 0],
            ],
            SfsCmsSectionsEvents::ADMIN_SECTIONS_CREATE_FAILURE => [
                ['onFailureAddFormError', 0],
            ],
            // SfsCmsSectionsEvents::ADMIN_SECTIONS_CREATE_FORM_INVALID => [],
            // SfsCmsSectionsEvents::ADMIN_SECTIONS_CREATE_VIEW => [],
            // SfsCmsSectionsEvents::ADMIN_SECTIONS_CREATE_EXCEPTION => [],
        ];
    }

    public function onSuccessRedirect(SuccessEvent $event): void
    {
        $event->setResponse(new RedirectResponse($this->router->generate('sfs_cms_admin_sections_content', ['section' => $event->getEntity()])));
    }
}
