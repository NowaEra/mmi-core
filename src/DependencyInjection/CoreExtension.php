<?php
declare(strict_types=1);

namespace Mmi\DependencyInjection;

use Mmi\App\HttpKernel;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class CoreExtension
 * Package Mmi\DependencyInjection
 */
class CoreExtension extends Extension
{
    /**
     * @param array            $configs
     * @param ContainerBuilder $container
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $this->registerEventDispatcher($container);
        $this->registerHttpKernel($container);
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
        $definition->addArgument(EventDispatcherInterface::class);
        $builder->setDefinition(HttpKernel::class, $definition);
        $builder->setAlias('http_kernel', HttpKernel::class);
    }
}
