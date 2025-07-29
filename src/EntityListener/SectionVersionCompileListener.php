<?php

namespace Softspring\CmsSectionsPlugin\EntityListener;

use Doctrine\ORM\Event\PrePersistEventArgs;
use Softspring\CmsSectionsPlugin\Compiler\SectionVersionCompiler;
use Softspring\CmsSectionsPlugin\Helper\CompileHelper;
use Softspring\CmsSectionsPlugin\Model\SectionVersionInterface;

class SectionVersionCompileListener
{
    public function __construct(
        protected CompileHelper $compileHelper,
        protected SectionVersionCompiler $sectionVersionCompiler,
    ) {
    }

    public function prePersist(SectionVersionInterface $sectionVersion, PrePersistEventArgs $event): void
    {
        if (!$this->compileHelper->sectionAutoCompileOnSave($sectionVersion)) {
            return;
        }

        foreach ($this->sectionVersionCompiler->compileAll($sectionVersion) as $compiledData) {
            $sectionVersion->addCompiled($compiledData);
        }
    }
}
