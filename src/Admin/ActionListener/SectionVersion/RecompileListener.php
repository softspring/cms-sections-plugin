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
use Softspring\Component\CrudlController\Event\InitializeEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class RecompileListener extends AbstractSectionVersionListener
{
    protected const ACTION_NAME = 'version_recompile';

    public function __construct(
        SectionManagerInterface $sectionManager,
        SectionVersionManagerInterface $sectionVersionManager,
        RouteManagerInterface $routeManager,
        CmsHelper $cmsHelper,
        RouterInterface $router,
        FlashNotifier $flashNotifier,
        AuthorizationCheckerInterface $authorizationChecker,
        protected SectionVersionCompiler $sectionVersionCompiler,
        protected bool $sectionRecompileEnabled,
    ) {
        parent::__construct($sectionManager, $sectionVersionManager, $routeManager, $cmsHelper, $router, $flashNotifier, $authorizationChecker);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            SfsCmsSectionsEvents::ADMIN_SECTION_VERSIONS_RECOMPILE_INITIALIZE => [
                ['onInitializeCheckEnabled', 20],
                ['onLoadSectionEntity', 9],
            ],
            // SfsCmsSectionsEvents::ADMIN_SECTION_VERSIONS_RECOMPILE_LOAD_ENTITY => [],
            SfsCmsSectionsEvents::ADMIN_SECTION_VERSIONS_RECOMPILE_NOT_FOUND => [
                ['onNotFoundAddFlashAndRedirectToList', 0],
            ],
            // SfsCmsSectionsEvents::ADMIN_SECTION_VERSIONS_RECOMPILE_FOUND => [],
            SfsCmsSectionsEvents::ADMIN_SECTION_VERSIONS_RECOMPILE_APPLY => [
                ['onApplyRecompile', 0],
            ],
            SfsCmsSectionsEvents::ADMIN_SECTION_VERSIONS_RECOMPILE_SUCCESS => [
                ['onSuccessAddFlash', 10],
                ['onSuccessRedirectBack', 0],
            ],
            SfsCmsSectionsEvents::ADMIN_SECTION_VERSIONS_RECOMPILE_FAILURE => [
                ['onFailureAddFlash', 10],
                ['onFailureRedirectBack', 0],
            ],
            SfsCmsSectionsEvents::ADMIN_SECTION_VERSIONS_RECOMPILE_EXCEPTION => [
                ['onExceptionAddFlash', 10],
                ['onExceptionRedirectBack', 0],
            ],
        ];
    }

    public function onInitializeCheckEnabled(InitializeEvent $event): void
    {
        if (!$this->sectionRecompileEnabled) {
            throw new NotFoundHttpException('Recompile is disabled');
        }
    }

    public function onApplyRecompile(ApplyEvent $event): void
    {
        /** @var SectionVersionInterface $entity */
        $entity = $event->getEntity();

        $entity->setKeep($event->getRequest()->attributes->get('recompile') ?: false);

        $entity->setCompileErrors(false);
        $entity->cleanCompiled();
        $this->sectionVersionCompiler->compileAll($entity);

        $this->sectionVersionManager->saveEntity($entity);

        $event->setApplied(true);
    }
}
