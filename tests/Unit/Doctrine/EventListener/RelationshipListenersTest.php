<?php

declare(strict_types=1);

namespace Softspring\CmsSectionsPlugin\Tests\Unit\Doctrine\EventListener;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\Persistence\Mapping\RuntimeReflectionService;
use PHPUnit\Framework\TestCase;
use Softspring\CmsBundle\Entity\CompiledData;
use Softspring\CmsBundle\Entity\ContentVersion;
use Softspring\CmsSectionsPlugin\Doctrine\EventListener\CompiledDataSectionRelationshipListener;
use Softspring\CmsSectionsPlugin\Doctrine\EventListener\ContentVersionSectionRelationshipListener;
use Softspring\CmsSectionsPlugin\Entity\Section;
use Softspring\CmsSectionsPlugin\Entity\SectionVersion;

class RelationshipListenersTest extends TestCase
{
    public function testItMapsCompiledDataSectionVersionRelationship(): void
    {
        $metadata = $this->createMetadata(CompiledData::class);
        $entityManager = $this->createStub(EntityManagerInterface::class);
        $entityManager->method('getClassMetadata')->willReturn($this->createMetadata(SectionVersion::class));

        (new CompiledDataSectionRelationshipListener())->loadClassMetadata(new LoadClassMetadataEventArgs($metadata, $entityManager));

        self::assertTrue($metadata->hasAssociation('sectionVersion'));
        self::assertSame(SectionVersion::class, $metadata->getAssociationTargetClass('sectionVersion'));
        self::assertSame('compiled', $metadata->associationMappings['sectionVersion']['inversedBy']);
    }

    public function testItMapsContentVersionSectionsRelationship(): void
    {
        $metadata = $this->createMetadata(ContentVersion::class);
        $entityManager = $this->createStub(EntityManagerInterface::class);
        $entityManager->method('getClassMetadata')->willReturn($this->createMetadata(Section::class));

        (new ContentVersionSectionRelationshipListener())->loadClassMetadata(new LoadClassMetadataEventArgs($metadata, $entityManager));

        self::assertTrue($metadata->hasAssociation('sections'));
        self::assertSame(Section::class, $metadata->getAssociationTargetClass('sections'));
        self::assertSame('cms_content_version_sections', $metadata->associationMappings['sections']['joinTable']['name']);
    }

    public function testItIgnoresOtherClasses(): void
    {
        $metadata = $this->createMetadata(Section::class);
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects(self::never())->method('getClassMetadata');

        (new CompiledDataSectionRelationshipListener())->loadClassMetadata(new LoadClassMetadataEventArgs($metadata, $entityManager));
        (new ContentVersionSectionRelationshipListener())->loadClassMetadata(new LoadClassMetadataEventArgs($metadata, $entityManager));

        self::assertSame([], $metadata->associationMappings);
    }

    /**
     * @param class-string $class
     */
    private function createMetadata(string $class): ClassMetadata
    {
        $metadata = new ClassMetadata($class);
        $metadata->initializeReflection(new RuntimeReflectionService());

        return $metadata;
    }
}
