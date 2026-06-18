<?php

namespace Softspring\CmsSectionsPlugin\Tests\Unit\Form\Admin\SectionVersion;

use PHPUnit\Framework\TestCase;
use Softspring\CmsSectionsPlugin\Entity\Section;
use Softspring\CmsSectionsPlugin\Form\Admin\SectionVersion\VersionUpdateForm;
use Softspring\CmsSectionsPlugin\Model\SectionVersionInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VersionUpdateFormTest extends TestCase
{
    public function testItConfiguresOptions(): void
    {
        $resolver = new OptionsResolver();
        $form = new VersionUpdateForm();

        $form->configureOptions($resolver);

        $options = $resolver->resolve(['section' => new Section()]);

        self::assertSame(SectionVersionInterface::class, $options['data_class']);
        self::assertSame(['Default', 'update'], $options['validation_groups']);
        self::assertSame('sfs_cms_admin', $options['translation_domain']);
        self::assertNull($options['layout']);
        self::assertNull($options['section_type']);
        self::assertNull($options['section_config']);
    }

    public function testItRequiresSectionOption(): void
    {
        $resolver = new OptionsResolver();
        $form = new VersionUpdateForm();

        $form->configureOptions($resolver);

        $this->expectException(MissingOptionsException::class);

        $resolver->resolve();
    }

    public function testItBuildsNoteField(): void
    {
        $form = new VersionUpdateForm();

        $builder = $this->createMock(FormBuilderInterface::class);
        $builder->expects($this->once())
            ->method('add')
            ->with('note', TextType::class, ['required' => false])
            ->willReturnSelf();

        $form->buildForm($builder, []);
    }
}
