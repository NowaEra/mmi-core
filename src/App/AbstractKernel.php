<?php
declare(strict_types=1);

namespace Mmi\App;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Kernel;

/**
 * Class AbstractKernel
 * @package Mmi\App
 */
abstract class AbstractKernel extends Kernel
{

    /**
     * @return ExtensionInterface[]
     */
    abstract protected function getExtensions(): array;

    /**
     * @return CompilerPassInterface[]
     */
    abstract protected function getCompilerPasses(): array;

    /**
     * @param ContainerBuilder $container
     */
    protected function build(ContainerBuilder $container): void
    {
        foreach ($this->getExtensions() as $extension) {
            $container->registerExtension($extension);
        }

        foreach ($this->getCompilerPasses() as $compilerPass) {
            $container->addCompilerPass($compilerPass);
        }

        parent::build($container);
    }
}
