<?php

declare(strict_types=1);

namespace Softspring\CmsSectionsPlugin\Form\Admin\SectionVersion;

use Softspring\CmsSectionsPlugin\Model\SectionInterface;
use Softspring\CmsSectionsPlugin\Model\SectionVersionInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VersionUpdateForm extends AbstractType implements VersionUpdateFormInterface
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SectionVersionInterface::class,
            'validation_groups' => ['Default', 'update'],
            'translation_domain' => 'sfs_cms_admin',
            'layout' => null,
            'section_type' => null,
            'section_config' => null,
            'label_format' => 'admin_sections.version_form.%name%.label',
        ]);

        $resolver->setRequired('section');
        $resolver->setAllowedTypes('section', [SectionInterface::class]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('note', TextType::class, [
            'required' => false,
        ]);
    }
}
