<?php
declare(strict_types=1);

namespace Mmi\Event;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * Class KernelRequestEvent
 * Package Mmi\Event
 */
class KernelRequestEvent extends Event
{
    /** @var Request */
    private $request;

    /** @var callable|null */
    private $controller;

    /**
     * KernelRequestEvent constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }

    /**
     * @return callable|null
     */
    public function getController(): ?callable
    {
        return $this->controller;
    }

    /**
     * @param callable|null $controller
     *
     * @return KernelRequestEvent
     */
    public function setController(?callable $controller): KernelRequestEvent
    {
        $this->controller = $controller;

        return $this;
    }
}
