<?php

declare(strict_types=1);

namespace Softspring\CmsSectionsPlugin\Admin\ActionListener\Section;

use Softspring\CmsBundle\Helper\CmsHelper;
use Softspring\CmsBundle\Manager\RouteManagerInterface;
use Softspring\CmsBundle\Request\FlashNotifier;
use Softspring\CmsBundle\Translator\TranslatableContext;
use Softspring\CmsSectionsPlugin\Manager\SectionManagerInterface;
use Softspring\CmsSectionsPlugin\Manager\SectionVersionManagerInterface;
use Softspring\CmsSectionsPlugin\SfsCmsSectionsEvents;
use Softspring\Component\CrudlController\Event\SuccessEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class UpdateListener extends AbstractSectionListener
{
    protected const ACTION_NAME = 'update';

    public function __construct(
        SectionManagerInterface $sectionManager,
        SectionVersionManagerInterface $sectionVersionManager,
        RouteManagerInterface $routeManager,
        CmsHelper $cmsHelper,
        RouterInterface $router,
        FlashNotifier $flashNotifier,
        AuthorizationCheckerInterface $authorizationChecker,
        protected TranslatableContext $translatableContext,
    ) {
        parent::__construct($sectionManager, $sectionVersionManager, $routeManager, $cmsHelper, $router, $flashNotifier, $authorizationChecker);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            // SfsCmsSectionsEvents::ADMIN_SECTIONS_UPDATE_INITIALIZE => [],
            // SfsCmsSectionsEvents::ADMIN_SECTIONS_UPDATE_LOAD_ENTITY => [],
            SfsCmsSectionsEvents::ADMIN_SECTIONS_UPDATE_NOT_FOUND => [
                ['onNotFoundAddFlashAndRedirectToList', 0],
            ],
            // SfsCmsSectionsEvents::ADMIN_SECTIONS_UPDATE_FOUND => [],
            // SfsCmsSectionsEvents::ADMIN_SECTIONS_UPDATE_FORM_PREPARE => [],
            // SfsCmsSectionsEvents::ADMIN_SECTIONS_UPDATE_FORM_INIT => [],
            // SfsCmsSectionsEvents::ADMIN_SECTIONS_UPDATE_FORM_VALID => [],
            // SfsCmsSectionsEvents::ADMIN_SECTIONS_UPDATE_APPLY => [],
            SfsCmsSectionsEvents::ADMIN_SECTIONS_UPDATE_SUCCESS => [
                ['onSuccessAddFlash', 10],
                ['onSuccessRedirect', 0],
            ],
            SfsCmsSectionsEvents::ADMIN_SECTIONS_UPDATE_FAILURE => [
                ['onFailureAddFormError', 0],
            ],
            // SfsCmsSectionsEvents::ADMIN_SECTIONS_UPDATE_FORM_INVALID => [],
            // SfsCmsSectionsEvents::ADMIN_SECTIONS_UPDATE_VIEW => [],
            // SfsCmsSectionsEvents::ADMIN_SECTIONS_UPDATE_EXCEPTION => [],
        ];
    }

    public function onSuccessRedirect(SuccessEvent $event): void
    {
        $event->setResponse(new RedirectResponse($this->router->generate('sfs_cms_admin_sections_details', ['section' => $event->getEntity()])));
    }
}
