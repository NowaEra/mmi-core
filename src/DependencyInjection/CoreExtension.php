<?php
declare(strict_types=1);

namespace Mmi\DependencyInjection;

use Mmi\App\HttpKernel;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class CoreExtension
 * Package Mmi\DependencyInjection
 */
class CoreExtension extends Extension implements PrependExtensionInterface
{
    /**
     * @param ContainerBuilder $container
     */
    public function prepend(ContainerBuilder $container)
    {
        $this->registerEventDispatcher($container);
        $this->registerHttpKernel($container);
    }

    /**
     * @param array            $configs
     * @param ContainerBuilder $container
     */
    public function load(array $configs, ContainerBuilder $container)
    {
    }

    private function registerEventDispatcher(ContainerBuilder $builder): void
    {
        $definition = new Definition(EventDispatcher::class);
        $builder->setDefinition(EventDispatcher::class, $definition);
        $builder->setAlias(EventDispatcherInterface::class, EventDispatcher::class);
    }

    private function registerHttpKernel(ContainerBuilder $builder): void
    {
        $definition = new Definition(HttpKernel::class);
        $definition->setPublic(true);
        $definition->addArgument(new Reference(EventDispatcherInterface::class));
        $builder->setDefinition(HttpKernel::class, $definition);
        $builder->setAlias('http_kernel', HttpKernel::class);
    }
}
