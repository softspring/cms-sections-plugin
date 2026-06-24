<?php

declare(strict_types=1);

namespace Softspring\CmsSectionsPlugin\Tests\Unit\Form\Type;

use PHPUnit\Framework\TestCase;
use Softspring\CmsBundle\Config\CmsConfig;
use Softspring\CmsBundle\Helper\LocaleHelper;
use Softspring\CmsBundle\Model\VersionInterface;
use Softspring\CmsBundle\Model\SiteInterface;
use Softspring\CmsSectionsPlugin\Form\Type\SectionType;
use Softspring\CmsSectionsPlugin\Model\SectionInterface;
use Softspring\CmsSectionsPlugin\Render\SectionVersionRenderer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\ChoiceList\View\ChoiceView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;
use Doctrine\ORM\EntityManagerInterface;

class SectionTypeTest extends TestCase
{
    public function testItConfiguresSectionChoiceOptionsAndFinishesView(): void
    {
        $currentSection = $this->createSection('current', 'Current', true);
        $selectedSection = $this->createSection('footer', 'Footer', false, 3600, "Line 1\nLine 2");
        $site = $this->createStub(SiteInterface::class);
        $site->method('getId')->willReturn('main');
        $cmsConfig = $this->createStub(CmsConfig::class);
        $cmsConfig->method('getSites')->willReturn([$site]);
        $localeHelper = $this->createStub(LocaleHelper::class);
        $localeHelper->method('getEnabledLocales')->willReturn(['en', 'es']);
        $router = $this->createStub(RouterInterface::class);
        $router->method('generate')->willReturnCallback(fn (string $route, array $parameters): string => $route.'?'.http_build_query($parameters));
        $requestStack = new RequestStack();
        $requestStack->push(new Request([], [], ['section' => $currentSection]));
        $type = new SectionType(
            $this->createStub(EntityManagerInterface::class),
            $cmsConfig,
            $this->createStub(SectionVersionRenderer::class),
            $requestStack,
            $localeHelper,
            $router
        );
        $resolver = new OptionsResolver();

        self::assertSame('section', $type->getBlockPrefix());
        self::assertSame(EntityType::class, $type->getParent());

        $type->configureOptions($resolver);
        $options = $resolver->resolve();

        self::assertSame(SectionInterface::class, $options['class']);
        self::assertSame('Footer (draft)', $options['choice_label']($selectedSection));
        self::assertFalse($options['choice_filter']($currentSection));
        self::assertTrue($options['choice_filter']($selectedSection));

        $attr = $options['choice_attr']($selectedSection);
        self::assertStringContainsString('sfs_cms_admin_sections_details?section=footer', $attr['data-section-url']);
        self::assertSame('', $attr['data-section-draft']);
        self::assertSame(3600, $attr['data-section-ttl']);
        self::assertSame("Line 1<br />\nLine 2", $attr['data-section-notes']);
        self::assertStringContainsString('data-lang="en"', $attr['data-section-preview']);
        self::assertStringContainsString('data-site="main"', $attr['data-section-preview']);

        $view = new FormView();
        $view->vars['value'] = 'footer';
        $view->vars['choices'] = [
            new ChoiceView($selectedSection, 'footer', 'Footer', $attr),
        ];
        $type->finishView($view, $this->createStub(FormInterface::class), $options);

        self::assertSame($attr['data-section-preview'], $view->vars['section_preview']);
        self::assertSame($attr['data-section-notes'], $view->vars['section_notes']);
    }

    private function createSection(string $id, string $name, bool $published, ?int $ttl = null, ?string $notes = null): SectionInterface
    {
        $section = $this->createStub(SectionInterface::class);
        $section->method('getId')->willReturn($id);
        $section->method('getName')->willReturn($name);
        $section->method('getPublishedVersion')->willReturn($published ? $this->createStub(VersionInterface::class) : null);
        $section->method('getStatus')->willReturn($published ? 'published' : 'draft');
        $section->method('getExtra')->willReturnCallback(fn (string $key, mixed $default = null): mixed => 'ttl' === $key ? ($ttl ?? $default) : $default);
        $section->method('getNotes')->willReturn($notes);

        return $section;
    }
}
