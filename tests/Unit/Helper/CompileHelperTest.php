<?php

declare(strict_types=1);

namespace Softspring\CmsSectionsPlugin\Tests\Unit\Helper;

use PHPUnit\Framework\TestCase;
use Softspring\CmsBundle\Config\CmsConfig;
use Softspring\CmsSectionsPlugin\Helper\CompileHelper;
use Softspring\CmsSectionsPlugin\Model\SectionVersionInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class CompileHelperTest extends TestCase
{
    public function testItRequiresEnabledAutocompileCurrentRequestAndSaveCompiled(): void
    {
        $version = $this->createStub(SectionVersionInterface::class);
        $requestStack = new RequestStack();
        $helper = new CompileHelper($this->createStub(CmsConfig::class), $requestStack, true, true);

        self::assertFalse($helper->sectionAutoCompileOnSave($version));

        $requestStack->push(new Request());

        self::assertTrue($helper->sectionAutoCompileOnSave($version));
        self::assertTrue($helper->sectionSaveCompiled($version));
        self::assertFalse((new CompileHelper($this->createStub(CmsConfig::class), $requestStack, false, true))->sectionAutoCompileOnSave($version));
        self::assertFalse((new CompileHelper($this->createStub(CmsConfig::class), $requestStack, true, false))->sectionAutoCompileOnSave($version));
    }
}
