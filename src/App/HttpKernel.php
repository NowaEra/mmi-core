<?php
declare(strict_types=1);

namespace Mmi\App;

use Mmi\Event\KernelController;
use Mmi\Event\KernelRequest;
use Mmi\Event\KernelResponse;
use Mmi\Event\KernelTerminate;
use Mmi\Exception\ControllerException;
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
     * @throws \Exception
     */
    public function handle(Request $request): Response
    {
        try {
            $kernelRequest = new KernelRequest($request);
            $this->eventDispatcher->dispatch($kernelRequest);

            if (true === $kernelRequest->hasResponse()) {
                return $kernelRequest->getResponse();
            }

            if (null === $kernelRequest->getController()) {
                throw ControllerException::createForControllerNotFound($request);
            }

            $kernelController = new KernelController(
                $kernelRequest->getController(),
                $request
            );
            $this->eventDispatcher->dispatch($kernelRequest);

            if (null === $kernelController->getResponse()) {
                throw ControllerException::createForEmptyResponse($request);
            }

            $kernelResponse = new KernelResponse($kernelController->getResponse());
            $this->eventDispatcher->dispatch($kernelResponse);

            return $kernelResponse->getResponse();
        } catch (\Exception $exception) {
            $kernelException = new \Mmi\Event\KernelException($request, $exception);
            $this->eventDispatcher->dispatch($kernelException);

            if (null !== $response = $kernelException->getResponse()) {
                return $response;
            }

            throw $exception;
        }
    }

    public function terminate(): void
    {
        $this->eventDispatcher->dispatch(new KernelTerminate());
    }
}
