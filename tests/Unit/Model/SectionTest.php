<?php

declare(strict_types=1);

namespace Softspring\CmsSectionsPlugin\Tests\Unit\Model;

use PHPUnit\Framework\TestCase;
use Softspring\CmsSectionsPlugin\Entity\Section;

class SectionTest extends TestCase
{
    public function testItStoresBasicSectionData(): void
    {
        $section = new Section();

        self::assertNull($section->getName());
        self::assertSame([], $section->getExtraData());
        self::assertNull($section->getNotes());

        $section->setName('Footer');
        $section->setExtraData(['ttl' => 3600]);
        $section->setExtra('layout', 'compact');
        $section->setNotes('Reusable footer section');

        self::assertSame('Footer', $section->getName());
        self::assertSame(['ttl' => 3600, 'layout' => 'compact'], $section->getExtraData());
        self::assertSame(3600, $section->getExtra('ttl'));
        self::assertSame('fallback', $section->getExtra('missing', 'fallback'));
        self::assertSame('Reusable footer section', $section->getNotes());
    }

    public function testItInitializesVersionsCollection(): void
    {
        $section = new Section();

        self::assertCount(0, $section->getVersions());
    }
}
