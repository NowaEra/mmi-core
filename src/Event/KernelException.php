<?php
declare(strict_types=1);

namespace Mmi\Event;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * Class KernelException
 * Package Mmi\Event
 */
class KernelException extends Event
{
    /** @var Request */
    private $request;

    /** @var \Exception */
    private $exception;

    /** @var Response|null */
    private $response;

    /**
     * KernelException constructor.
     *
     * @param Request    $request
     * @param \Exception $exception
     */
    public function __construct(Request $request, \Exception $exception)
    {
        $this->request = $request;
        $this->exception = $exception;
    }

    /**
     * @return Response|null
     */
    public function getResponse(): ?Response
    {
        return $this->response;
    }

    /**
     * @param Response|null $response
     *
     * @return KernelException
     */
    public function setResponse(?Response $response): KernelException
    {
        $this->response = $response;

        return $this;
    }

    /**
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }

    /**
     * @return \Exception
     */
    public function getException(): \Exception
    {
        return $this->exception;
    }
}
