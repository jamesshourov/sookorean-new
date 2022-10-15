<?php

namespace Imdhemy\Purchases\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Imdhemy\Purchases\Handlers\HandlerFactory;

/**
 * Server notification controller
 *
 * This controller handles the incoming requests by the IAP service providers
 * and dispatches relevant events.
 */
class ServerNotificationController extends Controller
{
    /**
     * Handles the server notification request
     *
     * @param HandlerFactory $handlerFactory
     * @param Request $request
     *
     * @throws Exception
     */
    public function __invoke(HandlerFactory $handlerFactory, Request $request)
    {
        $handler = $handlerFactory->create((string)$request->get('provider'));

        $handler->execute();
    }
}
