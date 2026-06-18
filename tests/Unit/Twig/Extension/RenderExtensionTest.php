<?php

namespace Softspring\CmsSectionsPlugin\Tests\Unit\Twig\Extension;

use Doctrine\ORM\EntityRepository;
use Exception;
use PHPUnit\Framework\TestCase;
use Softspring\CmsSectionsPlugin\Entity\Section;
use Softspring\CmsSectionsPlugin\Manager\SectionManagerInterface;
use Softspring\CmsSectionsPlugin\Twig\Extension\RenderExtension;
use stdClass;
use Twig\Environment;
use Twig\TwigFunction;

class RenderExtensionTest extends TestCase
{
    public function testItRegistersSectionFunctions(): void
    {
        $extension = new RenderExtension(
            $this->createMock(SectionManagerInterface::class),
            $this->createMock(Environment::class),
        );

        $names = array_map(static fn (TwigFunction $function): string => $function->getName(), $extension->getFunctions());

        self::assertSame([
            'sfs_cms_section_find',
            'sfs_cms_section_find_by_*',
            'sfs_cms_section',
            'sfs_cms_section_embed',
            'sfs_cms_section_embed_by_*',
            'sfs_cms_section_esi',
            'sfs_cms_section_esi_by_*',
            'sfs_cms_section_ajax',
            'sfs_cms_section_ajax_by_*',
        ], $names);
    }

    public function testFindAcceptsStringCriteria(): void
    {
        $section = new Section();
        $repository = $this->createMock(EntityRepository::class);
        $repository->expects($this->once())
            ->method('findOneBy')
            ->with(['id' => 'footer'], ['name' => 'ASC'])
            ->willReturn($section);

        $manager = $this->createMock(SectionManagerInterface::class);
        $manager->method('getRepository')->willReturn($repository);

        $extension = new RenderExtension($manager, $this->createMock(Environment::class));

        self::assertSame($section, $extension->find('footer', ['name' => 'ASC']));
    }

    public function testFindRejectsInvalidCriteria(): void
    {
        $extension = new RenderExtension(
            $this->createMock(SectionManagerInterface::class),
            $this->createMock(Environment::class),
        );

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Invalid criteria');

        $extension->find(new stdClass());
    }

    public function testItRendersEmbedEsiAndAjaxModes(): void
    {
        $section = new Section();
        $twig = $this->createMock(Environment::class);
        $twig->expects($this->exactly(3))
            ->method('render')
            ->willReturnCallback(function (string $template, array $context): string {
                self::assertSame('@module/section/render.html.twig', $template);

                return 'rendered-'.$context['mode'];
            });

        $extension = new RenderExtension($this->createMock(SectionManagerInterface::class), $twig);

        self::assertSame('rendered-embed', $extension->renderEmbed($section));
        self::assertSame('rendered-esi', $extension->renderEsi($section));
        self::assertSame('rendered-ajax', $extension->renderAjax($section));
    }

    public function testItReturnsHtmlCommentWhenSectionIsMissing(): void
    {
        $repository = $this->createMock(EntityRepository::class);
        $repository->method('findOneBy')->willReturn(null);

        $manager = $this->createMock(SectionManagerInterface::class);
        $manager->method('getRepository')->willReturn($repository);

        $extension = new RenderExtension($manager, $this->createMock(Environment::class));

        self::assertSame('<!-- section missing not found -->', $extension->renderEmbed('missing'));
    }
}
