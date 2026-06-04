<?php

declare(strict_types=1);

namespace Softspring\CmsSectionsPlugin\Twig\Extension\Admin;

use Softspring\CmsBundle\Admin\Menu\MenuManager;
use Softspring\CmsSectionsPlugin\Model\SectionInterface;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;
use Twig\TwigFunction;

class AdminExtension extends AbstractExtension implements GlobalsInterface
{
    public function __construct(
        protected MenuManager $menuManager,
        protected bool $sectionRecompileEnabled,
    ) {
    }

    public function getGlobals(): array
    {
        return [
            'sfs_cms_admin_section_recompile_enabled' => $this->sectionRecompileEnabled,
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('sfs_cms_admin_section_menu', $this->getSectionMenu(...)),
        ];
    }

    public function getSectionMenu(string $current, SectionInterface $section): array
    {
        return $this->menuManager->getEntityMenu('section', $current, $section);
    }
}
