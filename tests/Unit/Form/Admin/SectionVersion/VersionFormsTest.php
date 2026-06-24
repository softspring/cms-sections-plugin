<?php

declare(strict_types=1);

namespace Softspring\CmsSectionsPlugin\Tests\Unit\Form\Admin\SectionVersion;

use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Softspring\CmsBundle\Helper\CmsHelper;
use Softspring\CmsBundle\Helper\LocaleHelper;
use Softspring\CmsSectionsPlugin\Form\Admin\SectionVersion\VersionCreateForm;
use Softspring\CmsSectionsPlugin\Form\Admin\SectionVersion\VersionDeleteForm;
use Softspring\CmsSectionsPlugin\Form\Admin\SectionVersion\VersionUpdateForm;
use Softspring\CmsSectionsPlugin\Model\SectionInterface;
use Softspring\CmsSectionsPlugin\Model\SectionVersionInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VersionFormsTest extends TestCase
{
    public function testCreateFormRequiresSection(): void
    {
        $form = new VersionCreateForm($this->createStub(EntityManagerInterface::class), $this->createStub(CmsHelper::class), $this->createStub(LocaleHelper::class));
        $resolver = new OptionsResolver();
        $form->configureOptions($resolver);

        $this->expectException(MissingOptionsException::class);
        $resolver->resolve();
    }

    public function testCreateFormAddsModuleCollections(): void
    {
        $section = $this->createStub(SectionInterface::class);
        $form = new VersionCreateForm($this->createStub(EntityManagerInterface::class), $this->createStub(CmsHelper::class), $this->createStub(LocaleHelper::class));
        $resolver = new OptionsResolver();
        $form->configureOptions($resolver);

        $options = $resolver->resolve(['section' => $section]);
        self::assertSame(SectionVersionInterface::class, $options['data_class']);

        $fields = [];
        $builder = $this->createMock(FormBuilderInterface::class);
        $builder->method('add')->willReturnCallback(function (string $name, ?string $type = null, array $options = []) use (&$fields, &$builder): FormBuilderInterface {
            $fields[$name] = [$type, $options];

            return $builder;
        });
        $form->buildForm($builder, $options);

        self::assertSame(['data', 'module_prototypes_collection'], array_keys($fields));
        self::assertTrue($fields['module_prototypes_collection'][1]['prototype']);
        self::assertFalse($fields['module_prototypes_collection'][1]['mapped']);
    }

    public function testUpdateFormConfiguresOptionsAndAddsNoteField(): void
    {
        $section = $this->createStub(SectionInterface::class);
        $form = new VersionUpdateForm();
        $resolver = new OptionsResolver();
        $form->configureOptions($resolver);
        $options = $resolver->resolve(['section' => $section]);

        self::assertSame(SectionVersionInterface::class, $options['data_class']);
        self::assertSame(['Default', 'update'], $options['validation_groups']);

        $fields = [];
        $builder = $this->createMock(FormBuilderInterface::class);
        $builder->method('add')->willReturnCallback(function (string $name, ?string $type = null, array $options = []) use (&$fields, &$builder): FormBuilderInterface {
            $fields[$name] = [$type, $options];

            return $builder;
        });
        $form->buildForm($builder, $options);

        self::assertSame(['note'], array_keys($fields));
        self::assertFalse($fields['note'][1]['required']);
    }

    public function testDeleteFormConfiguresDeleteOptions(): void
    {
        $resolver = new OptionsResolver();

        (new VersionDeleteForm($this->createStub(EntityManagerInterface::class)))->configureOptions($resolver);

        self::assertSame([
            'data_class' => SectionVersionInterface::class,
            'validation_groups' => ['Default', 'delete'],
            'translation_domain' => 'sfs_cms_admin',
            'label_format' => 'admin_section_version.form.%name%.label',
        ], $resolver->resolve());
    }
}
