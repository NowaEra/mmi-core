<?php
declare(strict_types=1);

namespace Mmi\Event;

use Symfony\Component\HttpFoundation\Response;

/**
 * Trait EventWithResponseTrait
 * Package Mmi\Event
 */
trait EventWithResponseTrait
{
    /** @var Response|null */
    protected $response;

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
     * @return EventWithResponseTrait
     */
    public function setResponse(?Response $response): EventWithResponseTrait
    {
        $this->response = $response;

        return $this;
    }

    /**
     * @return bool
     */
    public function hasResponse(): bool
    {
        return null !== $this->response;
    }
}
