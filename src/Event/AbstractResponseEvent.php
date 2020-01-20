<?php
declare(strict_types=1);

namespace Mmi\Event;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * Class AbstractResponseEvent
 * Package Mmi\Event
 */
abstract class AbstractResponseEvent extends Event
{
    /** @var Response|null */
    private $response;

    /**
     * @return Response|null
     */
    public function getResponse(): ?Response
    {
        return $this->response;
    }

    /**
     * @param Response|null $response
     */
    public function setResponse(?Response $response): void
    {
        $this->response = $response;
    }
}
