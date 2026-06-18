<?php

namespace Softspring\CmsSectionsPlugin\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Softspring\CmsSectionsPlugin\SfsCmsSectionsPlugin;

class SfsCmsSectionsPluginTest extends TestCase
{
    public function testItExposesAliasAndPath(): void
    {
        $plugin = new SfsCmsSectionsPlugin();

        self::assertSame('sfs_cms_sections', SfsCmsSectionsPlugin::getAlias());
        self::assertSame(dirname(__DIR__, 2), $plugin->getPath());
    }
}
