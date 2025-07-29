<?php

namespace Softspring\CmsSectionsPlugin\Entity;

use Softspring\CmsSectionsPlugin\Model\Section as SectionModel;

class Section extends SectionModel
{
    protected ?string $id = null;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function __toString(): string
    {
        return ''.$this->getId();
    }
}
