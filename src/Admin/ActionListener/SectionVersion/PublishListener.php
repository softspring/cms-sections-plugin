<?php

namespace Softspring\CmsSectionsPlugin\Admin\ActionListener\SectionVersion;

use Softspring\CmsBundle\Compiler\CompileException;
use Softspring\CmsBundle\Helper\CmsHelper;
use Softspring\CmsBundle\Manager\RouteManagerInterface;
use Softspring\CmsBundle\Request\FlashNotifier;
use Softspring\CmsSectionsPlugin\Compiler\SectionVersionCompiler;
use Softspring\CmsSectionsPlugin\Manager\SectionManagerInterface;
use Softspring\CmsSectionsPlugin\Manager\SectionVersionManagerInterface;
use Softspring\CmsSectionsPlugin\Model\SectionInterface;
use Softspring\CmsSectionsPlugin\Model\SectionVersionInterface;
use Softspring\CmsSectionsPlugin\SfsCmsSectionsEvents;
use Softspring\Component\CrudlController\Event\ApplyEvent;
use Softspring\Component\CrudlController\Event\FailureEvent;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class PublishListener extends AbstractSectionVersionListener
{
    protected const ACTION_NAME = 'version_publish';

    public function __construct(
        SectionManagerInterface $sectionManager,
        SectionVersionManagerInterface $sectionVersionManager,
        RouteManagerInterface $routeManager,
        CmsHelper $cmsHelper,
        RouterInterface $router,
        FlashNotifier $flashNotifier,
        AuthorizationCheckerInterface $authorizationChecker,
        protected SectionVersionCompiler $sectionVersionCompiler,
        protected bool $sectionAutoCompileOnPublish,
    ) {
        parent::__construct($sectionManager, $sectionVersionManager, $routeManager, $cmsHelper, $router, $flashNotifier, $authorizationChecker);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            SfsCmsSectionsEvents::ADMIN_SECTION_VERSIONS_PUBLISH_INITIALIZE => [
                ['onLoadSectionEntity', 9],
            ],
            // SfsCmsSectionsEvents::ADMIN_SECTION_VERSIONS_PUBLISH_LOAD_ENTITY => [],
            SfsCmsSectionsEvents::ADMIN_SECTION_VERSIONS_PUBLISH_NOT_FOUND => [
                ['onNotFoundAddFlashAndRedirectToList', 0],
            ],
            // SfsCmsSectionsEvents::ADMIN_SECTION_VERSIONS_PUBLISH_FOUND => [],
            SfsCmsSectionsEvents::ADMIN_SECTION_VERSIONS_PUBLISH_APPLY => [
                ['onApply', 0],
            ],
            SfsCmsSectionsEvents::ADMIN_SECTION_VERSIONS_PUBLISH_SUCCESS => [
                ['onSuccessAddFlash', 10],
                ['onSuccessRedirectBack', 0],
            ],
            SfsCmsSectionsEvents::ADMIN_SECTION_VERSIONS_PUBLISH_FAILURE => [
                ['onFailureSaveErrors', 10],
                ['onFailureAddFlash', 10],
                ['onFailureRedirectBack', 0],
            ],
            SfsCmsSectionsEvents::ADMIN_SECTION_VERSIONS_PUBLISH_EXCEPTION => [
                ['onExceptionAddFlash', 10],
                ['onExceptionRedirectBack', 0],
            ],
        ];
    }

    public function onApply(ApplyEvent $event): void
    {
        /** @var SectionVersionInterface $version */
        $version = $event->getEntity();
        /** @var SectionInterface $section */
        $section = $event->getRequest()->attributes->get('section');

        if ($this->sectionAutoCompileOnPublish) {
            $version->cleanCompiled();
            $this->sectionVersionCompiler->compileAll($version);

            if ($version->hasCompileErrors()) {
                throw new CompileException('Compile errors occurred while publishing the section version.');
            }
        }

        $version->setKeep(true); // Keep the version after publishing

        $section->setPublishedVersion($version);
        $this->sectionManager->saveEntity($section);

        $event->setApplied(true);
    }

    public function onFailureSaveErrors(FailureEvent $event): void
    {
        // save compiled data if it was created, to allow to debug the issue
        $this->sectionVersionManager->saveEntity($event->getEntity());
    }

    public function onFailureAddFlash(FailureEvent $event): void
    {
        $this->flashNotifier->addTrans('error', 'admin_sections.version_publish.failed_flash', [
            '%exception%' => $event->getException()->getMessage(),
            // '%exception_details%' => $event->getException() instanceof CompileExceptionDetailsInterface ? $event->getException()->getDetails() : '',
        ], 'sfs_cms_admin');
    }
}
