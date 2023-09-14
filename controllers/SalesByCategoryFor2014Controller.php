<?php

namespace PHPMaker2023\demo2023;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

/**
 * Sales_by_Category_for_2014 controller
 */
class SalesByCategoryFor2014Controller extends ControllerBase
{
    // summary
    public function summary(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "SalesByCategoryFor2014Summary");
    }

    // SalesByCategory2014
    public function SalesByCategory2014(Request $request, Response $response, array $args): Response
    {
        return $this->runChart($request, $response, $args, "SalesByCategoryFor2014Summary", "SalesByCategory2014");
    }
}
