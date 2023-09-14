<?php

namespace PHPMaker2023\demo2023;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

/**
 * Dashboard1 controller
 */
class Dashboard1Controller extends ControllerBase
{
    // dashboard
    public function dashboard(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "Dashboard1");
    }
}
