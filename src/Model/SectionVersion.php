<?php

namespace Softspring\CmsSectionsPlugin\Model;

use Doctrine\Common\Collections\ArrayCollection;
use InvalidArgumentException;
use Softspring\CmsBundle\Model\Traits\CompilableTrait;
use Softspring\CmsBundle\Model\Traits\ContentDataTrait;
use Softspring\CmsBundle\Model\Traits\VersionTrait;
use Softspring\CmsBundle\Model\VersionableInterface;

abstract class SectionVersion implements SectionVersionInterface
{
    use ContentDataTrait;
    use CompilableTrait;
    use VersionTrait;

    protected ?SectionInterface $section = null;

    public function __construct()
    {
        $this->medias = new ArrayCollection();
        $this->routes = new ArrayCollection();
        $this->sections = new ArrayCollection();
        $this->compiled = new ArrayCollection();
    }

    public function getSection(): ?SectionInterface
    {
        return $this->section;
    }

    public function setSection(?SectionInterface $section): void
    {
        $this->section = $section;
    }

    public function setParent(?VersionableInterface $parent): void
    {
        if ($parent && !$parent instanceof SectionInterface) {
            throw new InvalidArgumentException('Parent must implement SectionInterface');
        }
        $this->setSection($parent);
    }

    public function getParent(): ?VersionableInterface
    {
        return $this->getSection();
    }
}
