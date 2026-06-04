<?php

declare(strict_types=1);

namespace Softspring\CmsSectionsPlugin\Doctrine\EventListener;

use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\MappingException;
use Softspring\CmsBundle\Entity\CompiledData;
use Softspring\CmsSectionsPlugin\Model\SectionVersionInterface;

class CompiledDataSectionRelationshipListener
{
    /**
     * @throws MappingException
     */
    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs): void
    {
        /** @var ClassMetadata $metadata */
        $metadata = $eventArgs->getClassMetadata();
        $class = $metadata->getReflectionClass();

        if (CompiledData::class !== $class->getName()) {
            return;
        }

        $sectionVersionMetadata = $eventArgs->getObjectManager()->getClassMetadata(SectionVersionInterface::class);

        $metadata->mapManyToOne([
            'fieldName' => 'sectionVersion',
            'targetEntity' => $sectionVersionMetadata->getName(),
            'inversedBy' => 'compiled',
            'joinColumns' => [
                [
                    'name' => 'section_version_id',
                    'onDelete' => 'cascade',
                ],
            ],
            'isOwningSide' => true,
        ]);
    }
}
