<?php

declare(strict_types=1);

namespace Softspring\CmsSectionsPlugin\Tests\Unit\Form\Admin\Section;

use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Softspring\CmsBundle\Translator\TranslatableContext;
use Softspring\CmsSectionsPlugin\Form\Admin\Section\SectionCreateForm;
use Softspring\CmsSectionsPlugin\Form\Admin\Section\SectionDeleteForm;
use Softspring\CmsSectionsPlugin\Form\Admin\Section\SectionUpdateForm;
use Softspring\CmsSectionsPlugin\Model\SectionInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SectionFormsTest extends TestCase
{
    public function testCreateAndUpdateFormsConfigureOptionsAndFields(): void
    {
        foreach ([new SectionCreateForm($this->createTranslatableContext()), new SectionUpdateForm($this->createTranslatableContext())] as $form) {
            $resolver = new OptionsResolver();
            $form->configureOptions($resolver);

            $options = $resolver->resolve();

            self::assertSame(SectionInterface::class, $options['data_class']);
            self::assertSame('en', $options['default_locale']);
            self::assertSame(['en', 'es'], $options['locales']);
            self::assertSame('sfs_cms_admin', $options['translation_domain']);

            $fields = [];
            $builder = $this->createMock(FormBuilderInterface::class);
            $builder->method('add')->willReturnCallback(function (string $name, ?string $type = null, array $options = []) use (&$fields, &$builder): FormBuilderInterface {
                $fields[$name] = [$type, $options];

                return $builder;
            });

            $form->buildForm($builder, $options);

            self::assertSame(['name', 'ttl', 'defaultLocale', 'locales', 'notes'], array_keys($fields));
            self::assertSame('extraData[ttl]', $fields['ttl'][1]['property_path']);
            self::assertSame(['en', 'es'], array_values($fields['defaultLocale'][1]['choices']));
            self::assertSame(['en'], $fields['locales'][1]['default_value']);
        }
    }

    public function testDeleteFormConfiguresDeleteOptions(): void
    {
        $resolver = new OptionsResolver();

        (new SectionDeleteForm($this->createStub(EntityManagerInterface::class)))->configureOptions($resolver);

        self::assertSame([
            'data_class' => SectionInterface::class,
            'validation_groups' => ['Default', 'delete'],
            'translation_domain' => 'sfs_cms_admin',
            'label_format' => 'admin_sections.delete.form.%name%.label',
        ], $resolver->resolve());
    }

    private function createTranslatableContext(): TranslatableContext
    {
        $context = $this->createStub(TranslatableContext::class);
        $context->method('getDefaultLocale')->willReturn('en');
        $context->method('getEnabledLocales')->willReturn(['en', 'es']);

        return $context;
    }
}
