<?php

namespace Softspring\CmsSectionsPlugin\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Softspring\CmsBundle\Model\Traits\TranslatableConfigTrait;
use Softspring\CmsBundle\Model\Traits\VersionableTrait;

/**
 * @property SectionVersionInterface[]|Collection $versions
 * @property SectionVersionInterface|null         $publishedVersion
 * @property SectionVersionInterface|null         $lastVersion
 */
abstract class Section implements SectionInterface
{
    use VersionableTrait;
    use TranslatableConfigTrait;

    protected ?string $name = null;

    protected ?array $extraData = null;

    protected ?string $notes = null;

    public function __construct()
    {
        $this->versions = new ArrayCollection();
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getExtraData(): array
    {
        return $this->extraData ?? [];
    }

    public function setExtraData(?array $extraData): void
    {
        $this->extraData = $extraData;
    }

    public function getExtra(string $key, mixed $default = null): mixed
    {
        return $this->extraData[$key] ?? $default;
    }

    public function setExtra(string $key, mixed $value): void
    {
        if (null === $this->extraData) {
            $this->extraData = [];
        }
        $this->extraData[$key] = $value;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(?string $notes): void
    {
        $this->notes = $notes;
    }
}
