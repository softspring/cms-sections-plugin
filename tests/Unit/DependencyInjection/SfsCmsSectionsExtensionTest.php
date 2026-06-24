<?php

declare(strict_types=1);

namespace Softspring\CmsSectionsPlugin\Tests\Unit\DependencyInjection;

use PHPUnit\Framework\TestCase;
use Softspring\CmsSectionsPlugin\DependencyInjection\SfsCmsSectionsExtension;
use Softspring\CmsSectionsPlugin\Entity\Section;
use Softspring\CmsSectionsPlugin\Entity\SectionVersion;
use Softspring\CmsSectionsPlugin\Manager\SectionManagerInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class SfsCmsSectionsExtensionTest extends TestCase
{
    public function testItLoadsConfigurationParametersAndServices(): void
    {
        $container = new ContainerBuilder();
        $container->setParameter('sfs_cms.admin', true);

        (new SfsCmsSectionsExtension())->load([[
            'section' => [
                'class' => Section::class,
                'version_class' => SectionVersion::class,
                'find_field_name' => 'slug',
                'save_compiled' => false,
                'autocompile_on_save' => true,
                'autocompile_on_publish' => false,
                'prefix_compiled' => 'sections/',
                'recompile' => false,
            ],
        ]], $container);

        self::assertSame(Section::class, $container->getParameter('sfs_cms_sections.section.class'));
        self::assertSame(SectionVersion::class, $container->getParameter('sfs_cms_sections.section.version_class'));
        self::assertSame('slug', $container->getParameter('sfs_cms_sections.section.find_field_name'));
        self::assertFalse($container->getParameter('sfs_cms_sections.section.save_compiled'));
        self::assertTrue($container->getParameter('sfs_cms_sections.section.autocompile_on_save'));
        self::assertFalse($container->getParameter('sfs_cms_sections.section.autocompile_on_publish'));
        self::assertSame('sections/', $container->getParameter('sfs_cms_sections.section.prefix_compiled'));
        self::assertFalse($container->getParameter('sfs_cms_sections.section.recompile_enabled'));
        self::assertSame([], $container->getParameter('sfs_cms_sections.convert_superclass_list'));
        self::assertTrue($container->hasDefinition(SectionManagerInterface::class));
    }

    public function testItPrependsDoctrineCmsMigrationsAndAssetMapperConfiguration(): void
    {
        $container = new ContainerBuilder();
        $container->prependExtensionConfig('doctrine_migrations', [
            'migrations_paths' => ['Existing\Migrations' => 'existing/path'],
        ]);

        (new SfsCmsSectionsExtension())->prepend($container);

        self::assertSame([
            [
                'orm' => [
                    'mappings' => [
                        'SfsCmsSectionsPlugin' => [
                            'is_bundle' => true,
                            'mapping' => true,
                        ],
                    ],
                ],
            ],
        ], $container->getExtensionConfig('doctrine'));
        self::assertSame([
            [
                'collections' => [
                    'vendor/softspring/cms-sections-plugin/cms',
                ],
            ],
        ], $container->getExtensionConfig('sfs_cms'));
        self::assertSame([
            'Existing\Migrations' => 'existing/path',
            'Softspring\CmsSectionsPlugin\Migrations' => '@SfsCmsSectionsPlugin/src/Migrations',
        ], $container->getExtensionConfig('doctrine_migrations')[0]['migrations_paths']);
    }
}
