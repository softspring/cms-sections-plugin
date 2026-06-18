<?php

namespace Softspring\CmsSectionsPlugin\Tests\Unit\Form\Admin\Section;

use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Softspring\CmsSectionsPlugin\Form\Admin\Section\SectionDeleteForm;
use Softspring\CmsSectionsPlugin\Model\SectionInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SectionDeleteFormTest extends TestCase
{
    public function testItConfiguresOptions(): void
    {
        $resolver = new OptionsResolver();
        $form = new SectionDeleteForm($this->createMock(EntityManagerInterface::class));

        $form->configureOptions($resolver);

        self::assertSame([
            'data_class' => SectionInterface::class,
            'validation_groups' => ['Default', 'delete'],
            'translation_domain' => 'sfs_cms_admin',
            'label_format' => 'admin_sections.delete.form.%name%.label',
        ], $resolver->resolve());
    }
}
