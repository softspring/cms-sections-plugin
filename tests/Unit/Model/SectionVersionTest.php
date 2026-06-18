<?php

namespace Softspring\CmsSectionsPlugin\Tests\Unit\Model;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Softspring\CmsBundle\Model\VersionableInterface;
use Softspring\CmsSectionsPlugin\Entity\Section;
use Softspring\CmsSectionsPlugin\Entity\SectionVersion;

class SectionVersionTest extends TestCase
{
    public function testItStoresSectionAsParent(): void
    {
        $section = new Section();
        $version = new SectionVersion();

        self::assertNull($version->getSection());
        self::assertNull($version->getParent());

        $version->setSection($section);

        self::assertSame($section, $version->getSection());
        self::assertSame($section, $version->getParent());

        $version->setParent(null);

        self::assertNull($version->getSection());
    }

    public function testItRejectsInvalidParent(): void
    {
        $version = new SectionVersion();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Parent must implement SectionInterface');

        $version->setParent($this->createMock(VersionableInterface::class));
    }

    public function testItInitializesCollections(): void
    {
        $version = new SectionVersion();

        self::assertCount(0, $version->getMedias());
        self::assertCount(0, $version->getRoutes());
        self::assertCount(0, $version->getSections());
        self::assertCount(0, $version->getCompiled());
    }
}
