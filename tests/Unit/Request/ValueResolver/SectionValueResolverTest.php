<?php

declare(strict_types=1);

namespace Softspring\CmsSectionsPlugin\Tests\Unit\Request\ValueResolver;

use Doctrine\ORM\EntityRepository;
use PHPUnit\Framework\TestCase;
use Softspring\CmsSectionsPlugin\Manager\SectionManagerInterface;
use Softspring\CmsSectionsPlugin\Model\SectionInterface;
use Softspring\CmsSectionsPlugin\Request\ValueResolver\SectionValueResolver;
use stdClass;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class SectionValueResolverTest extends TestCase
{
    public function testItResolvesSectionArgumentsById(): void
    {
        $section = $this->createStub(SectionInterface::class);
        $repository = $this->createMock(EntityRepository::class);
        $repository->expects(self::once())->method('findOneBy')->with(['id' => 'home'])->willReturn($section);
        $manager = $this->createStub(SectionManagerInterface::class);
        $manager->method('getRepository')->willReturn($repository);

        $request = new Request([], [], ['section' => 'home']);
        $argument = new ArgumentMetadata('section', SectionInterface::class, false, false, null);

        self::assertSame([$section], iterator_to_array((new SectionValueResolver($manager))->resolve($request, $argument)));
    }

    public function testItSkipsObjectsUnsupportedTypesAndMissingEntities(): void
    {
        $manager = $this->createStub(SectionManagerInterface::class);
        $manager->method('getRepository')->willReturn($this->createStub(EntityRepository::class));
        $resolver = new SectionValueResolver($manager);

        self::assertSame([], iterator_to_array($resolver->resolve(new Request([], [], ['section' => new stdClass()]), new ArgumentMetadata('section', SectionInterface::class, false, false, null))));
        self::assertSame([], iterator_to_array($resolver->resolve(new Request([], [], ['section' => 'home']), new ArgumentMetadata('section', stdClass::class, false, false, null))));
        self::assertSame([], iterator_to_array($resolver->resolve(new Request([], [], ['section' => 'missing']), new ArgumentMetadata('section', SectionInterface::class, false, false, null))));
    }
}
