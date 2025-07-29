<?php

namespace Softspring\CmsSectionsPlugin;

use Softspring\CmsBundle\DependencyInjection\Compiler\AddTwigBundlesNamespacesPass;
use Softspring\CmsBundle\Plugin\SfsCmsPlugin;
use Softspring\CmsSectionsPlugin\DependencyInjection\Compiler\ResolveDoctrineTargetEntityPass;
use Softspring\CmsSectionsPlugin\Model\SectionInterface;
use Softspring\CmsSectionsPlugin\Model\SectionVersionInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class SfsCmsSectionsPlugin extends SfsCmsPlugin
{
    public static function getAlias(): string
    {
        return 'sfs_cms_sections';
    }

    public function getPath(): string
    {
        return \dirname(__DIR__);
    }

    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        // allow overriding bundles templates
        $container->addCompilerPass(new AddTwigBundlesNamespacesPass($this->getPath().'/templates'));

        // add interfaces to doctrine target entity resolver
        $container->addCompilerPass(new ResolveDoctrineTargetEntityPass());
    }

    protected function getTargetEntities(): array
    {
        return [
            [
                'parameterName' => 'sfs_cms_sections.section.class',
                'interface' => SectionInterface::class,
                'required' => true,
            ],
            [
                'parameterName' => 'sfs_cms_sections.section.version_class',
                'interface' => SectionVersionInterface::class,
                'required' => true,
            ],
        ];
    }

    protected function getTargetEntitiesMappings(): array
    {
        $basePath = realpath(__DIR__.'/../config/doctrine-mapping/');

        return [
            "$basePath/model" => 'Softspring\CmsSectionsPlugin\Model',
            "$basePath/entities" => 'Softspring\CmsSectionsPlugin\Entity',
        ];
    }
}
