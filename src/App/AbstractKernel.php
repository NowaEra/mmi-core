<?php
declare(strict_types=1);

namespace Mmi\App;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Symfony\Component\HttpKernel\Kernel as HttpKernel;

/**
 * Class AbstractKernel
 * @package Mmi\App
 */
abstract class AbstractKernel extends HttpKernel
{
    /**
     * @return ExtensionInterface[]
     */
    abstract protected function getExtensions(): array;

    /**
     * @param ContainerBuilder $container
     */
    protected function prepareContainer(ContainerBuilder $container)
    {
        foreach ($this->getExtensions() as $extension) {
            $container->registerExtension($extension);
        }

        parent::prepareContainer($container);
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
}
