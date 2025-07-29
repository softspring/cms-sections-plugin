<?php

namespace Softspring\CmsSectionsPlugin\Admin\ActionListener\SectionVersion;

use Softspring\CmsBundle\Helper\CmsHelper;
use Softspring\CmsBundle\Manager\RouteManagerInterface;
use Softspring\CmsBundle\Request\FlashNotifier;
use Softspring\CmsSectionsPlugin\Compiler\SectionVersionCompiler;
use Softspring\CmsSectionsPlugin\Manager\SectionManagerInterface;
use Softspring\CmsSectionsPlugin\Manager\SectionVersionManagerInterface;
use Softspring\CmsSectionsPlugin\SfsCmsSectionsEvents;
use Softspring\Component\CrudlController\Event\FormPrepareEvent;
use Softspring\Component\CrudlController\Event\SuccessEvent;
use Softspring\Component\CrudlController\Event\ViewEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class InfoListener extends AbstractSectionVersionListener
{
    protected const ACTION_NAME = 'version_info';

    public function __construct(
        SectionManagerInterface $sectionManager,
        SectionVersionManagerInterface $sectionVersionManager,
        RouteManagerInterface $routeManager,
        CmsHelper $cmsHelper,
        RouterInterface $router,
        FlashNotifier $flashNotifier,
        AuthorizationCheckerInterface $authorizationChecker,
        protected SectionVersionCompiler $sectionVersionCompiler,
        protected bool $sectionSaveCompiled,
    ) {
        parent::__construct($sectionManager, $sectionVersionManager, $routeManager, $cmsHelper, $router, $flashNotifier, $authorizationChecker);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            SfsCmsSectionsEvents::ADMIN_SECTION_VERSIONS_INFO_INITIALIZE => [
                ['onLoadSectionEntity', 9],
            ],
            // SfsCmsSectionsEvents::ADMIN_SECTION_VERSIONS_INFO_LOAD_ENTITY => [],
            SfsCmsSectionsEvents::ADMIN_SECTION_VERSIONS_INFO_NOT_FOUND => [
                ['onNotFoundAddFlashAndRedirectToList', 0],
            ],
            // SfsCmsSectionsEvents::ADMIN_SECTION_VERSIONS_INFO_FOUND => [],
            SfsCmsSectionsEvents::ADMIN_SECTION_VERSIONS_INFO_FORM_PREPARE => [
                ['onFormPrepareResolve', 0],
            ],
            // SfsCmsSectionsEvents::ADMIN_SECTION_VERSIONS_INFO_FORM_INIT => [],
            // SfsCmsSectionsEvents::ADMIN_SECTION_VERSIONS_INFO_FORM_VALID => [],
            // SfsCmsSectionsEvents::ADMIN_SECTION_VERSIONS_INFO_APPLY => [],
            SfsCmsSectionsEvents::ADMIN_SECTION_VERSIONS_INFO_SUCCESS => [
                ['onSuccess', 0],
            ],
            // SfsCmsSectionsEvents::ADMIN_SECTION_VERSIONS_INFO_FAILURE => [],
            // SfsCmsSectionsEvents::ADMIN_SECTION_VERSIONS_INFO_FORM_INVALID => [],
            SfsCmsSectionsEvents::ADMIN_SECTION_VERSIONS_INFO_VIEW => [
                ['onViewAddEntities', 10],
                ['onView', 0],
            ],
            // SfsCmsSectionsEvents::ADMIN_SECTION_VERSIONS_INFO_EXCEPTION => [],
        ];
    }

    public function onFormPrepareResolve(FormPrepareEvent $event): void
    {
        $event->setFormOptions([
            'section' => $event->getRequest()->attributes->get('section'),
        ]);
    }

    public function onView(ViewEvent $event): void
    {
        $version = $event->getRequest()->attributes->get('version');
        $event->getData()['version_entity'] = $version;
        $event->getData()['section_can_be_compiled'] = $this->sectionSaveCompiled;
        $event->getData()['section_can_compile_modules'] = $this->sectionSaveCompiled;
    }

    public function onSuccess(SuccessEvent $event): void
    {
        $version = $event->getEntity();
        $section = $version->getSection();

        $url = $this->router->generate('sfs_cms_admin_sections_version_info', ['section' => $section, 'version' => $version]);
        $event->setResponse(new RedirectResponse($url));
    }
}
