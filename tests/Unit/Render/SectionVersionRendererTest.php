<?php

declare(strict_types=1);

namespace Softspring\CmsSectionsPlugin\Tests\Unit\Render;

use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;
use Softspring\CmsBundle\Render\Error\RenderErrorList;
use Softspring\CmsBundle\Render\Isolated\IsolatedRunner;
use Softspring\CmsBundle\Render\Module\ModuleRenderer;
use Softspring\CmsSectionsPlugin\Model\SectionVersionInterface;
use Softspring\CmsSectionsPlugin\Render\SectionVersionRenderer;
use Symfony\Component\HttpFoundation\Request;
use Twig\Environment;

class SectionVersionRendererTest extends TestCase
{
    public function testItRendersEveryModuleInsideAnIsolatedRequest(): void
    {
        $request = new Request();
        $version = $this->createMock(SectionVersionInterface::class);
        $version->expects(self::once())->method('getMedias')->willReturn(new ArrayCollection());
        $version->expects(self::once())->method('getRoutes')->willReturn(new ArrayCollection());
        $version->method('getData')->willReturn([
            ['_module' => 'hero'],
            ['_module' => 'cta'],
        ]);
        $moduleRenderer = $this->createMock(ModuleRenderer::class);
        $moduleRenderer->expects(self::exactly(2))
            ->method('render')
            ->willReturnCallback(fn (array $moduleData, array &$collector): string => "rendered-{$moduleData['_module']}");
        $isolatedRunner = $this->createMock(IsolatedRunner::class);
        $isolatedRunner->expects(self::once())
            ->method('isolateRequestRender')
            ->with($request, self::isType('callable'))
            ->willReturnCallback(fn (Request $request, callable $callback): string => $callback($request, $this->createStub(Environment::class), $moduleRenderer));

        $renderer = new SectionVersionRenderer($isolatedRunner);

        self::assertSame('rendered-herorendered-cta', $renderer->render($version, $request, new RenderErrorList()));
        self::assertSame([], $renderer->getDebugCollectorData());
    }
}
