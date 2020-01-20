<?php
declare(strict_types=1);

namespace Mmi\Event;

use Symfony\Component\HttpFoundation\Request;

/**
 * Class KernelController
 * Package Mmi\Event
 */
class KernelController extends AbstractResponseEvent
{
    /** @var callable */
    private $controller;

    /** @var Request */
    private $request;

    /**
     * KernelController constructor.
     *
     * @param callable $controller
     * @param Request  $request
     */
    public function __construct(callable $controller, Request $request)
    {
        $this->controller = $controller;
        $this->request    = $request;
    }

    /**
     * @return callable
     */
    public function getController(): callable
    {
        return $this->controller;
    }

    /**
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }

    /**
     * @param callable $controller
     *
     * @return KernelController
     */
    public function setController(callable $controller): KernelController
    {
        $this->controller = $controller;

        return $this;
    }

    /**
     * @param Request $request
     *
     * @return KernelController
     */
    public function setRequest(Request $request): KernelController
    {
        $this->request = $request;

        return $this;
    }
}
