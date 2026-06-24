<?php

declare(strict_types=1);

namespace Softspring\CmsSectionsPlugin\Tests\Unit\Request\ValueResolver;

use Doctrine\ORM\EntityRepository;
use PHPUnit\Framework\TestCase;
use Softspring\CmsSectionsPlugin\Manager\SectionVersionManagerInterface;
use Softspring\CmsSectionsPlugin\Model\SectionVersionInterface;
use Softspring\CmsSectionsPlugin\Request\ValueResolver\SectionVersionValueResolver;
use stdClass;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class SectionVersionValueResolverTest extends TestCase
{
    public function testItResolvesSectionVersionArgumentsById(): void
    {
        $version = $this->createStub(SectionVersionInterface::class);
        $repository = $this->createMock(EntityRepository::class);
        $repository->expects(self::once())->method('findOneBy')->with(['id' => 'v1'])->willReturn($version);
        $manager = $this->createStub(SectionVersionManagerInterface::class);
        $manager->method('getRepository')->willReturn($repository);

        $request = new Request([], [], ['version' => 'v1']);
        $argument = new ArgumentMetadata('version', SectionVersionInterface::class, false, false, null);

        self::assertSame([$version], iterator_to_array((new SectionVersionValueResolver($manager))->resolve($request, $argument)));
    }

    public function testItSkipsObjectsUnsupportedTypesAndMissingEntities(): void
    {
        $manager = $this->createStub(SectionVersionManagerInterface::class);
        $manager->method('getRepository')->willReturn($this->createStub(EntityRepository::class));
        $resolver = new SectionVersionValueResolver($manager);

        self::assertSame([], iterator_to_array($resolver->resolve(new Request([], [], ['version' => new stdClass()]), new ArgumentMetadata('version', SectionVersionInterface::class, false, false, null))));
        self::assertSame([], iterator_to_array($resolver->resolve(new Request([], [], ['version' => 'v1']), new ArgumentMetadata('version', stdClass::class, false, false, null))));
        self::assertSame([], iterator_to_array($resolver->resolve(new Request([], [], ['version' => 'missing']), new ArgumentMetadata('version', SectionVersionInterface::class, false, false, null))));
    }
}
