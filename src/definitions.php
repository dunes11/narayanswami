<?php

namespace PHPMaker2023\demo2023;

use Slim\Views\PhpRenderer;
use Slim\Csrf\Guard;
use Slim\HttpCache\CacheProvider;
use Slim\Flash\Messages;
use Psr\Container\ContainerInterface;
use Monolog\Logger;
use Monolog\Handler\RotatingFileHandler;
use Doctrine\DBAL\Logging\LoggerChain;
use Doctrine\DBAL\Logging\DebugStack;

use Hybridauth\Hybridauth;
use Hybridauth\Logger\Psr3LoggerWrapper;

return [
    "cache" => \DI\create(CacheProvider::class),
    "flash" => fn(ContainerInterface $c) => new Messages(),
    "view" => fn(ContainerInterface $c) => new PhpRenderer($GLOBALS["RELATIVE_PATH"] . "views/"),
    "audit" => fn(ContainerInterface $c) => (new Logger("audit"))->pushHandler(new AuditTrailHandler("log/audit.log")), // For audit trail
    "log" => fn(ContainerInterface $c) => (new Logger("log"))->pushHandler(new RotatingFileHandler($GLOBALS["RELATIVE_PATH"] . "log/log.log")),
    "sqllogger" => function (ContainerInterface $c) {
        $loggers = [];
        if (Config("DEBUG")) {
            $loggers[] = $c->get("debugstack");
        }
        return (count($loggers) > 0) ? new LoggerChain($loggers) : null;
    },
    "csrf" => fn(ContainerInterface $c) => new Guard($GLOBALS["ResponseFactory"], Config("CSRF_PREFIX")),
    "debugstack" => \DI\create(DebugStack::class),
    "debugsqllogger" => \DI\create(DebugSqlLogger::class),
    "security" => \DI\create(AdvancedSecurity::class),
    "profile" => \DI\create(UserProfile::class),
    "language" => \DI\create(Language::class),
    "timer" => \DI\create(Timer::class),
    "session" => \DI\create(HttpSession::class),
    "hybridauth" => function (ContainerInterface $c) {
        $authConfig = Config("AUTH_CONFIG");
        $providers = &$authConfig["providers"];
        foreach ($providers as $provider => &$config) {
            $config["callback"] ??= FullUrl("login/" . $provider, "auth"); // Set callback URL for each provider
        }
        $logger = new Psr3LoggerWrapper();
        $logger->setLogger($c->get("log"));
        return new Hybridauth($authConfig, null, null, $logger);
    },

    // Tables
    "cars" => \DI\create(Cars::class),
    "categories" => \DI\create(Categories::class),
    "customers" => \DI\create(Customers::class),
    "employees" => \DI\create(Employees::class),
    "orderdetails" => \DI\create(Orderdetails::class),
    "orders" => \DI\create(Orders::class),
    "products" => \DI\create(Products::class),
    "shippers" => \DI\create(Shippers::class),
    "suppliers" => \DI\create(Suppliers::class),
    "models" => \DI\create(Models::class),
    "trademarks" => \DI\create(Trademarks::class),
    "userlevelpermissions" => \DI\create(Userlevelpermissions::class),
    "userlevels" => \DI\create(Userlevels::class),
    "order_details_extended" => \DI\create(OrderDetailsExtended::class),
    "orders2" => \DI\create(Orders2::class),
    "cars2" => \DI\create(Cars2::class),
    "home" => \DI\create(Home::class),
    "news" => \DI\create(News::class),
    "dji" => \DI\create(Dji::class),
    "order_details_extended_2" => \DI\create(OrderDetailsExtended2::class),
    "sales_by_category_for_20142" => \DI\create(SalesByCategoryFor20142::class),
    "Quarterly_Orders_By_Product" => \DI\create(QuarterlyOrdersByProduct::class),
    "Sales_By_Customer" => \DI\create(SalesByCustomer::class),
    "Sales_By_Customer_Compact" => \DI\create(SalesByCustomerCompact::class),
    "Alphabetical_List_of_Products" => \DI\create(AlphabeticalListOfProducts::class),
    "Products_By_Category" => \DI\create(ProductsByCategory::class),
    "Sales_by_Category_for_2014" => \DI\create(SalesByCategoryFor2014::class),
    "Sales_By_Year" => \DI\create(SalesByYear::class),
    "Sales_By_Order" => \DI\create(SalesByOrder::class),
    "Sales_By_Customer_2" => \DI\create(SalesByCustomer2::class),
    "Dashboard1" => \DI\create(Dashboard1::class),
    "orders_by_product2" => \DI\create(OrdersByProduct2::class),
    "Gantt" => \DI\create(Gantt::class),
    "locations" => \DI\create(Locations::class),
    "locations2" => \DI\create(Locations2::class),
    "locations3" => \DI\create(Locations3::class),
    "calendar" => \DI\create(Calendar::class),
    "Calendar1" => \DI\create(Calendar1::class),

    // User table
    "usertable" => \DI\get("employees"),
];
