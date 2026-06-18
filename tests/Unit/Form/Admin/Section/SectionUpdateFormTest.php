<?php

namespace Softspring\CmsSectionsPlugin\Tests\Unit\Form\Admin\Section;

use PHPUnit\Framework\TestCase;
use Softspring\CmsBundle\Translator\TranslatableContext;
use Softspring\CmsSectionsPlugin\Form\Admin\Section\SectionUpdateForm;
use Softspring\CmsSectionsPlugin\Model\SectionInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Count;

class SectionUpdateFormTest extends TestCase
{
    public function testItConfiguresOptions(): void
    {
        $resolver = new OptionsResolver();
        $form = new SectionUpdateForm($this->createTranslatableContext());

        $form->configureOptions($resolver);

        $options = $resolver->resolve();

        self::assertSame(SectionInterface::class, $options['data_class']);
        self::assertSame(['Default', 'update'], $options['validation_groups']);
        self::assertSame('en', $options['default_locale']);
        self::assertSame(['en', 'fr'], $options['locales']);
    }

    public function testItRequiresAtLeastOneLocale(): void
    {
        $form = new SectionUpdateForm($this->createTranslatableContext());
        $calls = [];

        $builder = $this->createMock(FormBuilderInterface::class);
        $builder->method('add')
            ->willReturnCallback(function (string $name, ?string $type = null, array $options = []) use (&$calls, $builder): FormBuilderInterface {
                $calls[$name] = [$type, $options];

                return $builder;
            });

        $form->buildForm($builder, ['default_locale' => 'en', 'locales' => ['en', 'fr']]);

        self::assertSame(ChoiceType::class, $calls['locales'][0]);
        self::assertInstanceOf(Count::class, $calls['locales'][1]['constraints']);
    }

    private function createTranslatableContext(): TranslatableContext
    {
        $context = $this->createMock(TranslatableContext::class);
        $context->method('getDefaultLocale')->willReturn('en');
        $context->method('getEnabledLocales')->willReturn(['en', 'fr']);

        return $context;
    }
}
