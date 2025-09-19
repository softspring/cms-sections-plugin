<?php

namespace Softspring\CmsSectionsPlugin\Doctrine\EventListener;

use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\MappingException;
use Softspring\CmsBundle\Entity\ContentVersion;
use Softspring\CmsSectionsPlugin\Model\SectionInterface;

class ContentVersionSectionRelationshipListener
{
    /**
     * @throws MappingException
     */
    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs): void
    {
        /** @var ClassMetadata $metadata */
        $metadata = $eventArgs->getClassMetadata();
        $class = $metadata->getReflectionClass();

        if (ContentVersion::class !== $class->getName()) {
            return;
        }

        $sectionMetadata = $eventArgs->getObjectManager()->getClassMetadata(SectionInterface::class);

        $metadata->mapManyToMany([
            'fieldName' => 'sections',
            'targetEntity' => $sectionMetadata->getName(),
            'fetch' => ClassMetadata::FETCH_EXTRA_LAZY,
            'joinTable' => [
                'name' => 'cms_content_version_sections',
                'joinColumns' => [
                    [
                        'name' => 'content_version_id',
                        'referencedColumnName' => 'id',
                        'onDelete' => 'CASCADE',
                    ],
                ],
                'inverseJoinColumns' => [
                    [
                        'name' => 'section_id',
                        'referencedColumnName' => 'id',
                        'onDelete' => 'RESTRICT',
                    ],
                ],
            ],
        ]);
    }
}
