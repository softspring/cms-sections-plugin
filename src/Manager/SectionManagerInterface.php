<?php

declare(strict_types=1);

namespace Softspring\CmsSectionsPlugin\Manager;

use Doctrine\ORM\EntityRepository;
use Softspring\CmsSectionsPlugin\Model\SectionInterface;
use Softspring\CmsSectionsPlugin\Model\SectionVersionInterface;
use Softspring\Component\CrudlController\Manager\CrudlEntityManagerInterface;

interface SectionManagerInterface extends CrudlEntityManagerInterface
{
    /**
     * @return SectionInterface
     */
    public function createEntity(): object;

    /**
     * @psalm-param SectionInterface $entity
     */
    public function saveEntity(object $entity): void;

    /**
     * @psalm-param SectionInterface $entity
     */
    public function deleteEntity(object $entity): void;

    public function duplicateEntity(SectionInterface $section): SectionInterface;

    public function createVersion(SectionInterface $section, ?SectionVersionInterface $prevVersion = null, ?int $origin = SectionVersionInterface::ORIGIN_UNKNOWN): SectionVersionInterface;

    public function getRepository(): EntityRepository;
}
