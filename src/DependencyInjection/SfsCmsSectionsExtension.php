<?php

namespace Softspring\CmsSectionsPlugin\DependencyInjection;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class SfsCmsSectionsExtension extends Extension implements PrependExtensionInterface
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $processor = new Processor();
        $configuration = new Configuration();
        $config = $processor->processConfiguration($configuration, $configs);
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../../config/services'));

        // configure section classes
        $container->setParameter('sfs_cms_sections.section.class', $config['section']['class']);
        $container->setParameter('sfs_cms_sections.section.version_class', $config['section']['version_class']);
        //        // configure section classes
        //        $container->setParameter('sfs_cms_sections.section.section_class', $config['section']['section_class']);
        //        $container->setParameter('sfs_cms_sections.section.section_version_class', $config['section']['section_version_class']);
        $container->setParameter('sfs_cms_sections.section.find_field_name', $config['section']['find_field_name'] ?? null);
        $container->setParameter('sfs_cms_sections.section.save_compiled', $config['section']['save_compiled'] ?? null);
        $container->setParameter('sfs_cms_sections.section.autocompile_on_save', $config['section']['autocompile_on_save'] ?? null);
        $container->setParameter('sfs_cms_sections.section.autocompile_on_publish', $config['section']['autocompile_on_publish'] ?? null);
        $container->setParameter('sfs_cms_sections.section.prefix_compiled', $config['section']['prefix_compiled'] ?? null);
        // $container->setParameter('sfs_cms_sections.section.cache.enabled', $config['section']['cache']['enabled'] ?? false);
        // $container->setParameter('sfs_cms_sections.section.cache.type', $config['section']['cache']['type'] ?? 'none');
        $container->setParameter('sfs_cms_sections.section.recompile_enabled', $config['section']['recompile'] ?? false);

        $this->processDataClasses($container);

        // load services
        $adminEnabled = $container->getParameter('sfs_cms.admin');
        $adminEnabled && $loader->load('controller/admin_sections.yaml');
        $adminEnabled && $loader->load('controller/admin_sections_version.yaml');
        $loader->load('services.yaml');
        $loader->load('admin_services.yaml');
        $loader->load('entity_transformer.yaml');

        if (class_exists('Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle')) {
            $loader->load('deprecated_param_converters.yaml');
        }

        if (interface_exists('Symfony\Component\HttpKernel\Controller\ValueResolverInterface')) {
            $loader->load('value_resolvers.yaml');
        }
    }

    protected function processDataClasses(ContainerBuilder $container): void
    {
        $superClassList = [];

        //        if (ArticleContent::class !== $container->getParameter('sfs_cms_sections.article.class')) {
        //            $superClassList[] = ArticleContent::class;
        //        }

        $container->setParameter('sfs_cms_sections.convert_superclass_list', $superClassList);
    }

    public function prepend(ContainerBuilder $container): void
    {
        // add a default config to force load target_entities, will be overwritten by ResolveDoctrineTargetEntityPass
        //        $doctrineConfig['orm']['resolve_target_entities'][ArticleContentInterface::class] = ArticleContent::class;

        // disable auto-mapping for this bundle to prevent mapping errors
        $doctrineConfig['orm']['mappings']['SfsCmsSectionsPlugin'] = [
            'is_bundle' => true,
            'mapping' => true,
        ];

        $container->prependExtensionConfig('doctrine', $doctrineConfig);

        $cmsConfig = [
            'collections' => [
                'vendor/softspring/cms-sections-plugin/cms',
            ],
        ];

        $container->prependExtensionConfig('sfs_cms', $cmsConfig);

        $container->prependExtensionConfig('doctrine_migrations', [
            'migrations_paths' => [
                'Softspring\SfsCmsSectionsPlugin\Migrations' => '@SfsCmsSectionsPlugin/src/Migrations',
            ],
        ]);
    }
}
