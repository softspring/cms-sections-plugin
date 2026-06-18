<?php

namespace Softspring\CmsSectionsPlugin\Tests\Unit\Form\Admin\Section;

use PHPUnit\Framework\TestCase;
use Softspring\CmsBundle\Translator\TranslatableContext;
use Softspring\CmsSectionsPlugin\Form\Admin\Section\SectionCreateForm;
use Softspring\CmsSectionsPlugin\Model\SectionInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SectionCreateFormTest extends TestCase
{
    public function testItConfiguresOptionsFromTranslatableContext(): void
    {
        $resolver = new OptionsResolver();
        $form = new SectionCreateForm($this->createTranslatableContext());

        $form->configureOptions($resolver);

        $options = $resolver->resolve();

        self::assertSame(SectionInterface::class, $options['data_class']);
        self::assertSame(['Default', 'create'], $options['validation_groups']);
        self::assertSame('sfs_cms_admin', $options['translation_domain']);
        self::assertSame('admin_sections.form.%name%.label', $options['label_format']);
        self::assertSame('en', $options['default_locale']);
        self::assertSame(['en', 'es'], $options['locales']);
    }

    public function testItBuildsExpectedFields(): void
    {
        $form = new SectionCreateForm($this->createTranslatableContext());
        $calls = [];

        $builder = $this->createMock(FormBuilderInterface::class);
        $builder->method('add')
            ->willReturnCallback(function (string $name, ?string $type = null, array $options = []) use (&$calls, $builder): FormBuilderInterface {
                $calls[$name] = [$type, $options];

                return $builder;
            });

        $form->buildForm($builder, ['default_locale' => 'en', 'locales' => ['en', 'es']]);

        self::assertSame(TextType::class, $calls['name'][0]);
        self::assertSame(NumberType::class, $calls['ttl'][0]);
        self::assertSame('extraData[ttl]', $calls['ttl'][1]['property_path']);
        self::assertSame(ChoiceType::class, $calls['defaultLocale'][0]);
        self::assertSame('en', $calls['defaultLocale'][1]['default_value']);
        self::assertSame(ChoiceType::class, $calls['locales'][0]);
        self::assertSame(['en'], $calls['locales'][1]['default_value']);
        self::assertSame(TextareaType::class, $calls['notes'][0]);
    }

    private function createTranslatableContext(): TranslatableContext
    {
        $context = $this->createMock(TranslatableContext::class);
        $context->method('getDefaultLocale')->willReturn('en');
        $context->method('getEnabledLocales')->willReturn(['en', 'es']);

        return $context;
    }
}
