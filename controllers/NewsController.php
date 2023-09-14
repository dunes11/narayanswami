<?php

namespace PHPMaker2023\demo2023;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

/**
 * news controller
 */
class NewsController extends ControllerBase
{
    // custom
    public function custom(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "News");
    }
}
