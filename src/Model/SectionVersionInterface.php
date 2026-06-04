<?php

declare(strict_types=1);

namespace Softspring\CmsSectionsPlugin\Model;

use Softspring\CmsBundle\Model\CompilableInterface;
use Softspring\CmsBundle\Model\ContentDataInterface;
use Softspring\CmsBundle\Model\VersionInterface;

interface SectionVersionInterface extends VersionInterface, CompilableInterface, ContentDataInterface
{
    public function getId();

    public function getSection(): ?SectionInterface;

    public function setSection(?SectionInterface $section): void;
}
