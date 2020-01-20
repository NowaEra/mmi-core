<?php
declare(strict_types=1);

namespace Mmi\App;

use Mmi\DependencyInjection\CoreExtension;
use Symfony\Component\Config\ConfigCache;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Dumper\PhpDumper;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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

    /** @var Container */
    protected $container;

    public function __construct(string $env, bool $debug = false)
    {
        $this->env   = $env;
        $this->debug = $debug;
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

    protected function boot(): void
    {
        if (true === $this->booted) {
            return;
        }
        $this->dumpContainer();

        $this->booted = true;
    }

    private function prepareContainer(ContainerBuilder $builder): void
    {
        $builder->getParameterBag()->add($this->getKernelParameters());
        $builder->registerExtension(new CoreExtension());
        $builder->set('kernel', $this);
    }

    private function dumpContainer(): void
    {
        $file = $this->getCacheDir() . '/' . $this->env . '/container.php';
        if (false === is_dir(dirname($file))) {
            mkdir(dirname($file), 0777, true);
        }
        $containerConfigCache = new ConfigCache($file, $this->debug);

        if (false === $containerConfigCache->isFresh()) {
            $containerBuilder = new ContainerBuilder();
            $this->prepareContainer($containerBuilder);
            $containerBuilder->compile();

            $dumper = new PhpDumper($containerBuilder);
            $containerConfigCache->write(
                $dumper->dump(['class' => 'MmiCachedContainer']),
                $containerBuilder->getResources()
            );
        }
        require_once $file;
        $this->container = new \MmiCachedContainer();
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
            'kernel.cache_dir'   => '%kernel.project_dir%/var/cache',
            'kernel.version'     => self::VERSION
        ];
    }

    protected function getHttpKernel(): HttpKernel
    {
        return $this->container->get('http_kernel');
    }

    public function handle(Request $request): Response
    {
        return $this->getHttpKernel()->handle($request);
    }
}
