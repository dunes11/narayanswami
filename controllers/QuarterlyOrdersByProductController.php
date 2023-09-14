<?php

namespace PHPMaker2023\demo2023;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

/**
 * Quarterly_Orders_By_Product controller
 */
class QuarterlyOrdersByProductController extends ControllerBase
{
    // crosstab
    public function crosstab(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "QuarterlyOrdersByProductCrosstab");
    }

    // OrdersByCategory
    public function OrdersByCategory(Request $request, Response $response, array $args): Response
    {
        return $this->runChart($request, $response, $args, "QuarterlyOrdersByProductCrosstab", "OrdersByCategory");
    }
}
