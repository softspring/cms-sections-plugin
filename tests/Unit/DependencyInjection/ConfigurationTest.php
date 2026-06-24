<?php

declare(strict_types=1);

namespace Softspring\CmsSectionsPlugin\Tests\Unit\DependencyInjection;

use PHPUnit\Framework\TestCase;
use Softspring\CmsSectionsPlugin\DependencyInjection\Configuration;
use Softspring\CmsSectionsPlugin\Entity\Section;
use Softspring\CmsSectionsPlugin\Entity\SectionVersion;
use Symfony\Component\Config\Definition\Processor;

class ConfigurationTest extends TestCase
{
    public function testItProvidesDefaultSectionConfiguration(): void
    {
        $config = (new Processor())->processConfiguration(new Configuration(), []);

        self::assertSame([
            'section' => [
                'class' => Section::class,
                'version_class' => SectionVersion::class,
                'find_field_name' => 'id',
                'save_compiled' => true,
                'autocompile_on_save' => false,
                'autocompile_on_publish' => true,
                'prefix_compiled' => '',
                'recompile' => true,
            ],
        ], $config);
    }
}
