<?php

declare(strict_types=1);

namespace Softspring\CmsSectionsPlugin\Tests\Unit\Manager;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\Persistence\Mapping\RuntimeReflectionService;
use PHPUnit\Framework\TestCase;
use Softspring\CmsBundle\Helper\CmsHelper;
use Softspring\CmsBundle\Manager\CompiledDataManagerInterface;
use Softspring\CmsBundle\Model\SiteInterface;
use Softspring\CmsSectionsPlugin\Compiler\SectionVersionCompiler;
use Softspring\CmsSectionsPlugin\Entity\Section;
use Softspring\CmsSectionsPlugin\Entity\SectionVersion;
use Softspring\CmsSectionsPlugin\Helper\CompileHelper;
use Softspring\CmsSectionsPlugin\Manager\SectionVersionManager;

class SectionVersionManagerTest extends TestCase
{
    public function testItDuplicatesVersions(): void
    {
        $section = new Section();
        $version = new SectionVersion();
        $version->setSection($section);
        $version->setData(['body' => 'old']);

        $copy = $this->createManager()->duplicateEntity($version, null, 'copied');

        self::assertSame($section, $copy->getSection());
        self::assertSame(['body' => 'old'], $copy->getData());
        self::assertSame(SectionVersion::ORIGIN_DUPLICATE, $copy->getOrigin());
        self::assertSame('copied', $copy->getOriginDescription());
    }

    public function testItAddsLocalesRecursivelyToTranslatableFieldsAndFilters(): void
    {
        $version = new SectionVersion();
        $version->setData([
            [
                [
                    '_module' => 'hero',
                    'title' => ['_trans_id' => 'title', 'en' => 'Hello'],
                    'locale_filter' => ['en'],
                    'modules' => [
                        [
                            '_module' => 'nested',
                            'text' => ['_trans_id' => 'text', 'en' => 'Nested'],
                        ],
                    ],
                ],
            ],
        ]);

        $this->createManager()->addLocale($version, 'es');

        $data = $version->getData();
        self::assertNull($data[0][0]['title']['es']);
        self::assertSame(['en', 'es'], $data[0][0]['locale_filter']);
        self::assertNull($data[0][0]['modules'][0]['text']['es']);
    }

    public function testItAddsSitesRecursivelyToFilters(): void
    {
        $site = $this->createStub(SiteInterface::class);
        $version = new SectionVersion();
        $version->setData([
            [
                [
                    '_module' => 'hero',
                    'site_filter' => ['main'],
                    'modules' => [
                        [
                            '_module' => 'nested',
                            'site_filter' => ['main'],
                        ],
                    ],
                ],
            ],
        ]);

        $this->createManager()->addSite($version, $site);

        $data = $version->getData();
        self::assertSame(['main', $site], $data[0][0]['site_filter']);
        self::assertSame(['main', $site], $data[0][0]['modules'][0]['site_filter']);
    }

    private function createManager(): SectionVersionManager
    {
        $entityManager = $this->createStub(EntityManagerInterface::class);
        $metadata = new ClassMetadata(SectionVersion::class);
        $metadata->initializeReflection(new RuntimeReflectionService());
        $entityManager->method('getClassMetadata')->willReturn($metadata);

        return new SectionVersionManager(
            $entityManager,
            $this->createStub(CmsHelper::class),
            $this->createStub(SectionVersionCompiler::class),
            $this->createStub(CompiledDataManagerInterface::class),
            $this->createStub(CompileHelper::class)
        );
    }
}
