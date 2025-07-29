<?php

namespace Softspring\CmsSectionsPlugin\DependencyInjection;

use Softspring\CmsSectionsPlugin\SfsCmsSectionsPlugin;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder(SfsCmsSectionsPlugin::getAlias());
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->arrayNode('section')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('class')->defaultValue('Softspring\CmsSectionsPlugin\Entity\Section')->end()
                        ->scalarNode('version_class')->defaultValue('Softspring\CmsSectionsPlugin\Entity\SectionVersion')->end()
                        ->scalarNode('find_field_name')->defaultValue('id')->end()
                        ->booleanNode('save_compiled')->defaultTrue()->end()
                        ->booleanNode('autocompile_on_save')->defaultFalse()->end()
                        ->booleanNode('autocompile_on_publish')->defaultTrue()->end()
                        ->scalarNode('prefix_compiled')->defaultValue('')->end()
                        /* @deprecated cache_last_modified since 5.3, will be removed in 6.0, use global sfs_cms.cache block */
                        // ->booleanNode('cache_last_modified')->defaultFalse()->end()
                        // ->arrayNode('cache')
                        //     ->children()
                        //         ->booleanNode('enabled')->end()
                        //         ->enumNode('type')->defaultNull()->values(['ttl', 'last_modified', 'none'])->end()
                        //     ->end()
                        // ->end()
                        ->booleanNode('recompile')->defaultTrue()->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
