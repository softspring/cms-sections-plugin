<?php

namespace Softspring\CmsSectionsPlugin\Tests\Unit\Form\Admin\SectionVersion;

use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Softspring\CmsSectionsPlugin\Form\Admin\SectionVersion\VersionDeleteForm;
use Softspring\CmsSectionsPlugin\Model\SectionVersionInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VersionDeleteFormTest extends TestCase
{
    public function testItConfiguresOptions(): void
    {
        $resolver = new OptionsResolver();
        $form = new VersionDeleteForm($this->createMock(EntityManagerInterface::class));

        $form->configureOptions($resolver);

        self::assertSame([
            'data_class' => SectionVersionInterface::class,
            'validation_groups' => ['Default', 'delete'],
            'translation_domain' => 'sfs_cms_admin',
            'label_format' => 'admin_section_version.form.%name%.label',
        ], $resolver->resolve());
    }
}
