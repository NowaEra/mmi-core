<?php
declare(strict_types=1);

namespace Mmi\App;

use Mmi\DependencyInjection\FrameworkExtension;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Routing\RouteCollectionBuilder;

/**
 * Class AbstractKernel
 * @package Mmi\App
 */
abstract class AbstractKernel extends Kernel
{
    abstract protected function configureContainer(ContainerBuilder $builder, LoaderInterface $loader): void;

    /**
     * {@inheritdoc}
     */
    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(function (ContainerBuilder $container) use ($loader) {
            $container->registerExtension(new FrameworkExtension());
            $container->loadFromExtension('framework', [
                'router' => [
                    'resource' => 'kernel::loadRoutes',
                    'type' => 'service',
                ],
            ]);
            if ($this instanceof EventSubscriberInterface) {
                $container->register('kernel', static::class)
                          ->setSynthetic(true)
                          ->setPublic(true)
                          ->addTag('kernel.event_subscriber')
                ;
            }
            $this->configureContainer($container, $loader);

            $container->addObjectResource($this);
        });
    }

    /**
     * Returns an array of bundles to register.
     *
     * @return iterable|BundleInterface[] An iterable of bundle instances
     */
    public function registerBundles()
    {
        return [];
    }

    /**
     * @internal
     */
    public function loadRoutes(LoaderInterface $loader)
    {
        $routes = new RouteCollectionBuilder($loader);
        $this->configureRoutes($routes);

        return $routes->build();
    }
}
