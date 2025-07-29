<?php

namespace Softspring\CmsSectionsPlugin\Admin\ActionListener\SectionVersion;

use Softspring\CmsBundle\Helper\CmsHelper;
use Softspring\CmsBundle\Manager\RouteManagerInterface;
use Softspring\CmsBundle\Request\FlashNotifier;
use Softspring\CmsBundle\Translator\TranslatableContext;
use Softspring\CmsSectionsPlugin\Manager\SectionManagerInterface;
use Softspring\CmsSectionsPlugin\Manager\SectionVersionManagerInterface;
use Softspring\CmsSectionsPlugin\SfsCmsSectionsEvents;
use Softspring\Component\CrudlController\Event\SuccessEvent;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class DeleteListener extends AbstractSectionVersionListener
{
    protected const ACTION_NAME = 'version_delete';

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
            SfsCmsSectionsEvents::ADMIN_SECTION_VERSIONS_DELETE_INITIALIZE => [
                ['onLoadSectionEntity', 9],
            ],
            // SfsCmsSectionsEvents::ADMIN_SECTION_VERSIONS_DELETE_LOAD_ENTITY => [],
            SfsCmsSectionsEvents::ADMIN_SECTION_VERSIONS_DELETE_NOT_FOUND => [
                ['onNotFoundAddFlashAndRedirectToList', 0],
            ],
            // SfsCmsSectionsEvents::ADMIN_SECTION_VERSIONS_DELETE_FOUND => [],
            // SfsCmsSectionsEvents::ADMIN_SECTION_VERSIONS_DELETE_FORM_PREPARE => [],
            // SfsCmsSectionsEvents::ADMIN_SECTION_VERSIONS_DELETE_FORM_INIT => [],
            // SfsCmsSectionsEvents::ADMIN_SECTION_VERSIONS_DELETE_FORM_VALID => [],
            // SfsCmsSectionsEvents::ADMIN_SECTION_VERSIONS_DELETE_APPLY => [],
            SfsCmsSectionsEvents::ADMIN_SECTION_VERSIONS_DELETE_SUCCESS => [
                ['onSuccessAddFlash', 10],
                ['onSuccessRedirectBack', 0],
            ],
            SfsCmsSectionsEvents::ADMIN_SECTION_VERSIONS_DELETE_FAILURE => [
                ['onFailureAddFlash', 10],
                ['onFailureRedirectBack', 0],
            ],
            // SfsCmsSectionsEvents::ADMIN_SECTION_VERSIONS_DELETE_FORM_INVALID => [],
            SfsCmsSectionsEvents::ADMIN_SECTION_VERSIONS_DELETE_VIEW => [
                ['onViewAddEntities', 10],
            ],
            // SfsCmsSectionsEvents::ADMIN_SECTION_VERSIONS_DELETE_EXCEPTION => [],
        ];
    }

    public function onSuccessRedirectBack(SuccessEvent $event): void
    {
        $event->setResponse($this->redirectBack($event->getRequest()->attributes->get('section'), $event->getRequest()));
    }
}
