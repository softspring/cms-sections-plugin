<?php

declare(strict_types=1);

namespace Softspring\CmsSectionsPlugin\Form\Resolver;

use Softspring\Component\DynamicFormType\Form\Resolver\DefaultTypeResolver;

class PluginTypeResolver extends DefaultTypeResolver
{
    public function getPossibleFormClasses(string $type): array
    {
        return [
            'Softspring\CmsSectionsPlugin\Form\Type\\'.ucfirst($type).'Type',
        ];
    }
}
