<?php

namespace PHPMaker2023\demo2023;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

/**
 * Calendar1 controller
 */
class Calendar1Controller extends ControllerBase
{
    // calendar
    public function calendar(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "Calendar1Calendar");
    }

    // add
    public function add(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "Calendar1Add");
    }

    // view
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "Calendar1View");
    }

    // edit
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "Calendar1Edit");
    }

    // delete
    public function delete(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "Calendar1Delete");
    }
}
