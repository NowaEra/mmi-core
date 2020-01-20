<?php
declare(strict_types=1);

namespace Mmi\App;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Dumper\PhpDumper;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;

/**
 * Class AbstractKernel
 * @package Mmi\App
 */
abstract class AbstractKernel
{
    const VERSION = "1.0.0";

    /** @var string */
    protected $env;

    /** @var bool */
    protected $debug;

    /** @var bool */
    protected $booted = false;

    public function __construct(string $env, bool $debug = false)
    {
        $this->env   = $env;
        $this->debug = $debug;
    }

    public function boot(): void
    {
        if (true === $this->booted) {
            return;
        }
        $builder = $this->getContainerBuilder();
        $builder->getParameterBag()->add($this->getKernelParameters());
        $this->dumpContainer();

        $this->booted = true;
    }

    private function dumpContainer(): void
    {
        $file = $this->getCacheDir() . '/' . $this->env . '/container.php';
        if (false === $this->debug && file_exists($file)) {
            require_once $file;
            $container = new MmiCachedContainer();
        } else {
            $containerBuilder = new ContainerBuilder();
            // ...
            $containerBuilder->compile();

            if (false === $this->debug) {
                $dumper = new PhpDumper($containerBuilder);
                file_put_contents(
                    $file,
                    $dumper->dump(['class' => 'MmiCachedContainer'])
                );
            }
        }
    }

    protected function getContainerBuilder(): ContainerBuilder
    {
        $builder = new ContainerBuilder();

        return $builder;
    }

    protected function getCacheDir(): string
    {
        return (realpath(
                $this->getProjectDir()
            ) ?: $this->getProjectDir()) . '/var/cache';
    }

    protected function getKernelParameters(): array
    {
        return [
            'kernel.project_dir' => realpath(
                $this->getProjectDir()
            ) ?: $this->getProjectDir(),
            'kernel.environment' => $this->env,
            'kernel.debug'       => $this->debug,
            'kernel.cache_dir'   => $this->getCacheDir(),
        ];
    }

    /**
     * @return ExtensionInterface[]
     */
    abstract protected function getExtensions(): array;

    /**
     * @return CompilerPassInterface[]
     */
    abstract protected function getCompilerPasses(): array;

    /**
     * @return string
     */
    abstract protected function getProjectDir(): string;
}
