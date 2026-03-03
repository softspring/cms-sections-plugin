<?php

namespace Softspring\CmsSectionsPlugin\Admin\Menu;

use Softspring\CmsBundle\Admin\Menu\MenuItem;
use Softspring\CmsBundle\Config\CmsConfig;
use Softspring\CmsSectionsPlugin\Manager\SectionManagerInterface;
use Softspring\CmsSectionsPlugin\Model\SectionInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

abstract class AbstractSectionMenuProvider implements SectionMenuProviderInterface
{
    public function __construct(
        protected CmsConfig $cmsConfig,
        protected SectionManagerInterface $sectionManager,
        protected RouterInterface $router,
        protected TranslatorInterface $translator,
        protected AuthorizationCheckerInterface $authorizationChecker,
    ) {
    }

    public function supports(string $menuId, object $entity): bool
    {
        return 'section' === $menuId && $entity instanceof SectionInterface;
    }

    protected function getMenuItem(string $id, string $current, SectionInterface $section, ?string $isGranted = null): MenuItem
    {
        $text = $this->translator->trans("admin_sections.tabs_menu.$id", [], 'sfs_cms_admin');
        $url = $this->router->generate("sfs_cms_admin_sections_{$id}", ['section' => $section->getId()]);
        $active = $current === $id;
        $disabled = ('#' === $url) || ($isGranted && !$this->authorizationChecker->isGranted($isGranted, $section));

        return new MenuItem($id, $text, $url, $active, $disabled);
    }
}
