<?php

declare(strict_types=1);

namespace Softspring\CmsSectionsPlugin\Tests\Unit\EntityListener;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\PostLoadEventArgs;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use PHPUnit\Framework\TestCase;
use Softspring\CmsBundle\EntityTransformer\VersionTransformer;
use Softspring\CmsSectionsPlugin\EntityListener\SectionVersionListener;
use Softspring\CmsSectionsPlugin\Model\SectionVersionInterface;

class SectionVersionListenerTest extends TestCase
{
    public function testItTransformsAndUntransformsVersionsOnDoctrineEvents(): void
    {
        $version = $this->createStub(SectionVersionInterface::class);
        $entityManager = $this->createStub(EntityManagerInterface::class);
        $transformer = $this->createMock(VersionTransformer::class);
        $transformer->expects(self::once())->method('untransform')->with($version, $entityManager);
        $transformer->expects(self::exactly(2))->method('transform')->with($version, $entityManager);
        $listener = new SectionVersionListener($transformer);
        $preUpdateEvent = $this->createStub(PreUpdateEventArgs::class);
        $preUpdateEvent->method('getObjectManager')->willReturn($entityManager);

        $listener->postLoad($version, new PostLoadEventArgs($version, $entityManager));
        $listener->prePersist($version, new PrePersistEventArgs($version, $entityManager));
        $listener->preUpdate($version, $preUpdateEvent);
    }
}
