<?php

declare(strict_types=1);

namespace Softspring\CmsSectionsPlugin\Tests\Unit\Manager;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\Persistence\Mapping\RuntimeReflectionService;
use PHPUnit\Framework\TestCase;
use Softspring\CmsBundle\Helper\CmsHelper;
use Softspring\CmsBundle\Manager\RouteManagerInterface;
use Softspring\CmsBundle\Model\VersionInterface;
use Softspring\CmsSectionsPlugin\Entity\Section;
use Softspring\CmsSectionsPlugin\Entity\SectionVersion;
use Softspring\CmsSectionsPlugin\Manager\SectionManager;
use Softspring\CmsSectionsPlugin\Manager\SectionVersionManagerInterface;
use Softspring\CmsSectionsPlugin\Model\SectionInterface;
use Softspring\CmsSectionsPlugin\Model\SectionVersionInterface;

class SectionManagerTest extends TestCase
{
    public function testItCreatesSectionsWithInitialVersion(): void
    {
        $manager = $this->createManager();

        $section = $manager->createEntity();

        self::assertInstanceOf(Section::class, $section);
        self::assertSame(0, $section->getLastVersionNumber());
        self::assertCount(1, $section->getVersions());
        self::assertSame($section->getLastVersion(), $section->getVersions()->first());
        self::assertSame(0, $section->getLastVersion()->getVersionNumber());
    }

    public function testItDuplicatesSectionsWithSupportingDuplicators(): void
    {
        $duplicator = new class {
            public bool $called = false;

            public function supports(SectionInterface $section): bool
            {
                return true;
            }

            public function duplicateData(SectionInterface $oldSection, SectionInterface $newSection): void
            {
                $this->called = true;
                $newSection->setNotes($oldSection->getNotes());
            }
        };
        $section = new Section();
        $section->setName('Home');
        $section->setNotes('notes');

        $copy = $this->createManager([$duplicator])->duplicateEntity($section);

        self::assertSame('Home (copy)', $copy->getName());
        self::assertSame('notes', $copy->getNotes());
        self::assertTrue($duplicator->called);
    }

    public function testItCreatesVersionsFromLastVersion(): void
    {
        $section = new Section();
        $previousVersion = new SectionVersion();
        $previousVersion->setData(['body' => 'old']);
        $section->addVersion($previousVersion);
        $section->setLastVersion($previousVersion);
        $section->setLastVersionNumber(4);

        $newVersion = $this->createManager()->createVersion($section, null, VersionInterface::ORIGIN_IMPORT);

        self::assertInstanceOf(SectionVersionInterface::class, $newVersion);
        self::assertSame(['body' => 'old'], $newVersion->getData());
        self::assertSame(5, $newVersion->getVersionNumber());
        self::assertSame(VersionInterface::ORIGIN_IMPORT, $newVersion->getOrigin());
        self::assertSame($newVersion, $section->getLastVersion());
    }

    private function createManager(iterable $duplicators = []): SectionManager
    {
        $entityManager = $this->createStub(EntityManagerInterface::class);
        $metadata = new ClassMetadata(Section::class);
        $metadata->initializeReflection(new RuntimeReflectionService());
        $entityManager->method('getClassMetadata')->willReturn($metadata);
        $versionManager = $this->createStub(SectionVersionManagerInterface::class);
        $versionManager->method('createEntity')->willReturnCallback(fn (): SectionVersion => new SectionVersion());

        return new SectionManager(
            $entityManager,
            $versionManager,
            $this->createStub(CmsHelper::class),
            $this->createStub(RouteManagerInterface::class),
            $duplicators
        );
    }
}
