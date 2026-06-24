<?php

declare(strict_types=1);

namespace Softspring\CmsSectionsPlugin\Tests\Unit\Admin\Menu;

use PHPUnit\Framework\TestCase;
use Softspring\CmsBundle\Admin\Menu\MenuItem;
use Softspring\CmsBundle\Config\CmsConfig;
use Softspring\CmsSectionsPlugin\Admin\Menu\SectionMenuProvider;
use Softspring\CmsSectionsPlugin\Manager\SectionManagerInterface;
use Softspring\CmsSectionsPlugin\Model\SectionInterface;
use stdClass;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class SectionMenuProviderTest extends TestCase
{
    public function testItSupportsSectionMenusAndBuildsItems(): void
    {
        $section = $this->createStub(SectionInterface::class);
        $section->method('getId')->willReturn('home');
        $router = $this->createMock(RouterInterface::class);
        $router->method('generate')->willReturnCallback(fn (string $route, array $parameters): string => "/{$route}/{$parameters['section']}");
        $translator = $this->createStub(TranslatorInterface::class);
        $translator->method('trans')->willReturnArgument(0);
        $authorizationChecker = $this->createStub(AuthorizationCheckerInterface::class);
        $authorizationChecker->method('isGranted')->willReturnCallback(fn (string $permission): bool => 'PERMISSION_SFS_CMS_ADMIN_SECTION_DELETE' !== $permission);
        $provider = new SectionMenuProvider(
            $this->createStub(CmsConfig::class),
            $this->createStub(SectionManagerInterface::class),
            $router,
            $translator,
            $authorizationChecker
        );

        self::assertSame(255, SectionMenuProvider::getPriority());
        self::assertTrue($provider->supports('section', $section));
        self::assertFalse($provider->supports('other', $section));
        self::assertFalse($provider->supports('section', new stdClass()));

        $menu = $provider->getMenu([], 'content', $section);

        self::assertSame(['details', 'content', 'preview', 'versions', 'update', 'delete'], array_map(static fn (MenuItem $item): string => $item->getId(), $menu));
        self::assertTrue($menu[1]->isActive());
        self::assertFalse($menu[1]->isDisabled());
        self::assertTrue($menu[5]->isDisabled());
    }
}
