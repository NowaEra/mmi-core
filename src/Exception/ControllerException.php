<?php
declare(strict_types=1);

namespace Mmi\Exception;

use Symfony\Component\HttpFoundation\Request;

/**
 * Class ControllerException
 * Package Mmi\Exception
 */
class ControllerException extends FrameworkException
{
    /**
     * @param Request    $request
     * @param int        $code
     * @param \Exception $previousException
     *
     * @return ControllerException
     */
    public static function createForControllerNotFound(Request $request, int $code = 0, \Exception $previousException = null): ControllerException
    {
        return new ControllerException(
            sprintf('Controller for path "%s" was not found', $request->getBasePath()),
            $code,
            $previousException
        );
    }

    /**
     * @param Request         $request
     * @param int             $code
     * @param \Exception|null $previousException
     *
     * @return ControllerException
     */
    public static function createForEmptyResponse(Request $request, int $code = 0, \Exception $previousException = null): ControllerException
    {
        return new ControllerException(
            sprintf('Response for path "%s" is empty.', $request->getBasePath()),
            $code,
            $previousException
        );
    }
}
