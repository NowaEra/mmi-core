<?php
declare(strict_types=1);

namespace Mmi\App;

use Mmi\Event\KernelController;
use Mmi\Event\KernelRequestEvent;
use Mmi\Event\KernelResponse;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class HttpKernel
 * Package Mmi\App
 */
class HttpKernel
{
    /** @var EventDispatcherInterface */
    private $eventDispatcher;

    /**
     * HttpKernel constructor.
     *
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function handle(Request $request): Response
    {
        $kernelRequest = new KernelRequestEvent($request);
        $this->eventDispatcher->dispatch($kernelRequest);

        if (null === $kernelRequest->getController()) {
            throw new \Exception('No controller found');
        }

        $kernelController = new KernelController(
            $kernelRequest->getController(), $request
        );
        $this->eventDispatcher->dispatch($kernelRequest);

        if (null === $kernelController->getResponse()) {
            throw new \Exception('No response');
        }

        $kernelResponse = new KernelResponse($kernelController->getResponse());
        $this->eventDispatcher->dispatch($kernelResponse);

        return $kernelResponse->getResponse();
    }
}
