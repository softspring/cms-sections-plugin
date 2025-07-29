<?php

namespace Softspring\CmsSectionsPlugin\Admin\ActionListener\SectionVersion;

use Softspring\CmsBundle\Helper\CmsHelper;
use Softspring\CmsBundle\Manager\RouteManagerInterface;
use Softspring\CmsBundle\Request\FlashNotifier;
use Softspring\CmsSectionsPlugin\Compiler\SectionVersionCompiler;
use Softspring\CmsSectionsPlugin\Manager\SectionManagerInterface;
use Softspring\CmsSectionsPlugin\Manager\SectionVersionManagerInterface;
use Softspring\CmsSectionsPlugin\Model\SectionVersionInterface;
use Softspring\CmsSectionsPlugin\SfsCmsSectionsEvents;
use Softspring\Component\CrudlController\Event\ApplyEvent;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class ClearCompiledListener extends AbstractSectionVersionListener
{
    protected const ACTION_NAME = 'version_clear_compiled';

    public function __construct(
        SectionManagerInterface $sectionManager,
        SectionVersionManagerInterface $sectionVersionManager,
        RouteManagerInterface $routeManager,
        CmsHelper $cmsHelper,
        RouterInterface $router,
        FlashNotifier $flashNotifier,
        AuthorizationCheckerInterface $authorizationChecker,
        protected SectionVersionCompiler $sectionVersionCompiler,
    ) {
        parent::__construct($sectionManager, $sectionVersionManager, $routeManager, $cmsHelper, $router, $flashNotifier, $authorizationChecker);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            SfsCmsSectionsEvents::ADMIN_SECTION_VERSIONS_CLEAR_COMPILED_INITIALIZE => [
                ['onLoadSectionEntity', 9],
            ],
            // SfsCmsSectionsEvents::ADMIN_SECTION_VERSIONS_CLEAR_COMPILED_LOAD_ENTITY => [],
            SfsCmsSectionsEvents::ADMIN_SECTION_VERSIONS_CLEAR_COMPILED_NOT_FOUND => [
                ['onNotFoundAddFlashAndRedirectToList', 0],
            ],
            // SfsCmsSectionsEvents::ADMIN_SECTION_VERSIONS_CLEAR_COMPILED_FOUND => [],
            SfsCmsSectionsEvents::ADMIN_SECTION_VERSIONS_CLEAR_COMPILED_APPLY => [
                ['onApplyClearCompiled', 0],
            ],
            SfsCmsSectionsEvents::ADMIN_SECTION_VERSIONS_CLEAR_COMPILED_SUCCESS => [
                ['onSuccessAddFlash', 10],
                ['onSuccessRedirectBack', 0],
            ],
            SfsCmsSectionsEvents::ADMIN_SECTION_VERSIONS_CLEAR_COMPILED_FAILURE => [
                ['onFailureAddFlash', 10],
                ['onFailureRedirectBack', 0],
            ],
            SfsCmsSectionsEvents::ADMIN_SECTION_VERSIONS_CLEAR_COMPILED_EXCEPTION => [
                ['onExceptionAddFlash', 10],
                ['onExceptionRedirectBack', 0],
            ],
        ];
    }

    public function onApplyClearCompiled(ApplyEvent $event): void
    {
        /** @var SectionVersionInterface $entity */
        $entity = $event->getEntity();

        $entity->setKeep($event->getRequest()->attributes->get('recompile') ?: false);

        $entity->cleanCompiled();

        $this->sectionVersionManager->saveEntity($entity);

        $event->setApplied(true);
    }
}
