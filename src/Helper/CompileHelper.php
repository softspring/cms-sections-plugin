<?php

namespace Softspring\CmsSectionsPlugin\Helper;

use Softspring\CmsBundle\Config\CmsConfig;
use Softspring\CmsSectionsPlugin\Model\SectionVersionInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class CompileHelper
{
    public function __construct(
        protected CmsConfig $cmsConfig,
        protected RequestStack $requestStack,
        protected bool $sectionSaveCompiled,
        protected bool $sectionAutoCompileOnSave,
    ) {
    }

    public function sectionAutoCompileOnSave(SectionVersionInterface $version): bool
    {
        if (!$this->sectionAutoCompileOnSave) {
            return false;
        }

        if (!$this->requestStack->getCurrentRequest()) {
            return false; // not yet ready for render in fixtures, TODO improve this to allow render in fixtures
        }

        return $this->sectionSaveCompiled($version);
    }

    public function sectionSaveCompiled(SectionVersionInterface $version): bool
    {
        return $this->sectionSaveCompiled;
    }
}
