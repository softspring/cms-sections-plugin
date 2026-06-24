<?php

declare(strict_types=1);

namespace Softspring\CmsSectionsPlugin\Tests\Unit\EntityListener;

use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Softspring\CmsBundle\Model\CompiledDataInterface;
use Softspring\CmsSectionsPlugin\Compiler\SectionVersionCompiler;
use Softspring\CmsSectionsPlugin\EntityListener\SectionVersionCompileListener;
use Softspring\CmsSectionsPlugin\Helper\CompileHelper;
use Softspring\CmsSectionsPlugin\Model\SectionVersionInterface;

class SectionVersionCompileListenerTest extends TestCase
{
    public function testItAddsCompiledDataWhenAutocompileIsEnabled(): void
    {
        $compiledData = $this->createStub(CompiledDataInterface::class);
        $version = $this->createMock(SectionVersionInterface::class);
        $version->expects(self::once())->method('addCompiled')->with($compiledData);
        $helper = $this->createStub(CompileHelper::class);
        $helper->method('sectionAutoCompileOnSave')->with($version)->willReturn(true);
        $compiler = $this->createStub(SectionVersionCompiler::class);
        $compiler->method('compileAll')->with($version)->willReturn([$compiledData]);

        (new SectionVersionCompileListener($helper, $compiler))->prePersist($version, new PrePersistEventArgs($version, $this->createStub(EntityManagerInterface::class)));
    }

    public function testItSkipsWhenAutocompileIsDisabled(): void
    {
        $version = $this->createMock(SectionVersionInterface::class);
        $version->expects(self::never())->method('addCompiled');
        $helper = $this->createStub(CompileHelper::class);
        $helper->method('sectionAutoCompileOnSave')->willReturn(false);
        $compiler = $this->createMock(SectionVersionCompiler::class);
        $compiler->expects(self::never())->method('compileAll');

        (new SectionVersionCompileListener($helper, $compiler))->prePersist($version, new PrePersistEventArgs($version, $this->createStub(EntityManagerInterface::class)));
    }
}
