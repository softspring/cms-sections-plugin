<?php

declare(strict_types=1);

namespace Softspring\CmsSectionsPlugin\Tests\Unit\Form\Resolver;

use PHPUnit\Framework\TestCase;
use Softspring\CmsSectionsPlugin\Form\Resolver\PluginTypeResolver;

class PluginTypeResolverTest extends TestCase
{
    public function testItBuildsPossibleSectionPluginFormClasses(): void
    {
        self::assertSame([
            'Softspring\CmsSectionsPlugin\Form\Type\HeroType',
        ], (new PluginTypeResolver())->getPossibleFormClasses('hero'));
    }
}
