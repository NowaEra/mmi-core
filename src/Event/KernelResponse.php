<?php
declare(strict_types=1);

namespace Mmi\Event;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * Class KernelResponse
 * Package Mmi\Event
 */
class KernelResponse extends Event
{
    /** @var Response */
    private $response;

    /**
     * KernelResponse constructor.
     *
     * @param Response $response
     */
    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    /**
     * @return Response
     */
    public function getResponse(): Response
    {
        return $this->response;
    }

    /**
     * @param Response $response
     *
     * @return KernelResponse
     */
    public function setResponse(Response $response): KernelResponse
    {
        $this->response = $response;

        return $this;
    }
}
