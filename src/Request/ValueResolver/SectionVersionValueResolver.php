<?php

declare(strict_types=1);

namespace Softspring\CmsSectionsPlugin\Request\ValueResolver;

use Softspring\CmsSectionsPlugin\Manager\SectionVersionManagerInterface;
use Softspring\CmsSectionsPlugin\Model\SectionVersionInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

use function is_object;

class SectionVersionValueResolver implements ValueResolverInterface
{
    public function __construct(protected SectionVersionManagerInterface $manager)
    {
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        if (is_object($request->attributes->get($argument->getName()))) {
            return [];
        }

        if (SectionVersionInterface::class !== $argument->getType()) {
            return [];
        }

        $query = $request->attributes->get($argument->getName());
        $entity = $this->manager->getRepository()->findOneBy(['id' => $query]);

        if (!$entity) {
            return [];
        }

        return [$entity];
    }
}
