<?php

namespace PHPMaker2023\demo2023;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

/**
 * orders_by_product2 controller
 */
class OrdersByProduct2Controller extends ControllerBase
{
    // summary
    public function summary(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "OrdersByProduct2Summary");
    }

    // OrderByProducts
    public function OrderByProducts(Request $request, Response $response, array $args): Response
    {
        return $this->runChart($request, $response, $args, "OrdersByProduct2Summary", "OrderByProducts");
    }
}
