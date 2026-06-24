<?php

declare(strict_types=1);

namespace Softspring\CmsSectionsPlugin\Tests\Unit\Twig\Extension\Admin;

use PHPUnit\Framework\TestCase;
use Softspring\CmsBundle\Admin\Menu\MenuManager;
use Softspring\CmsSectionsPlugin\Model\SectionInterface;
use Softspring\CmsSectionsPlugin\Twig\Extension\Admin\AdminExtension;
use Twig\TwigFunction;

class AdminExtensionTest extends TestCase
{
    public function testItExposesGlobalsFunctionsAndSectionMenu(): void
    {
        $section = $this->createStub(SectionInterface::class);
        $menu = ['details' => 'menu'];
        $menuManager = $this->createMock(MenuManager::class);
        $menuManager->expects(self::once())->method('getEntityMenu')->with('section', 'details', $section)->willReturn($menu);

        $extension = new AdminExtension($menuManager, true);

        self::assertSame(['sfs_cms_admin_section_recompile_enabled' => true], $extension->getGlobals());
        self::assertSame(['sfs_cms_admin_section_menu'], array_map(static fn (TwigFunction $function): string => $function->getName(), $extension->getFunctions()));
        self::assertSame($menu, $extension->getSectionMenu('details', $section));
    }
}
