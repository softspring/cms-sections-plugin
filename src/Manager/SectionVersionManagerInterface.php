<?php

declare(strict_types=1);

namespace Softspring\CmsSectionsPlugin\Manager;

use Doctrine\Common\Collections\Collection;
use Softspring\CmsBundle\Model\CompiledDataInterface;
use Softspring\CmsBundle\Model\SiteInterface;
use Softspring\CmsSectionsPlugin\Model\SectionInterface;
use Softspring\CmsSectionsPlugin\Model\SectionVersionInterface;
use Softspring\Component\CrudlController\Manager\CrudlEntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

interface SectionVersionManagerInterface extends CrudlEntityManagerInterface
{
    /**
     * @return SectionVersionInterface
     */
    public function createEntity(): object;

    public function duplicateEntity(SectionVersionInterface $sectionVersion, ?SectionInterface $section = null, ?string $originDescription = null): SectionVersionInterface;

    /**
     * @psalm-param SectionVersionInterface $entity
     */
    public function saveEntity(object $entity): void;

    /**
     * @psalm-param SectionVersionInterface $entity
     */
    public function deleteEntity(object $entity): void;

    public function getLatestVersions(SectionInterface $section, int $limit = 3): Collection;

    public function getCompiledContent(SectionVersionInterface $sectionVersion, Request $request, bool $throwExceptionOnCompileError = true): CompiledDataInterface;

    public function addLocale(SectionVersionInterface $sectionVersion, string $locale): void;

    public function addSite(SectionVersionInterface $sectionVersion, SiteInterface $site): void;
}
