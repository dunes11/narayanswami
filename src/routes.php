<?php

namespace PHPMaker2023\demo2023;

use Slim\App;
use Slim\Routing\RouteCollectorProxy;
use Slim\Exception\HttpNotFoundException;

// Handle Routes
return function (App $app) {
    // cars
    $app->map(["GET","POST","OPTIONS"], '/carslist[/{ID}]', CarsController::class . ':list')->add(PermissionMiddleware::class)->setName('carslist-cars-list'); // list
    $app->map(["GET","POST","OPTIONS"], '/carsadd[/{ID}]', CarsController::class . ':add')->add(PermissionMiddleware::class)->setName('carsadd-cars-add'); // add
    $app->map(["GET","POST","OPTIONS"], '/carsview[/{ID}]', CarsController::class . ':view')->add(PermissionMiddleware::class)->setName('carsview-cars-view'); // view
    $app->map(["GET","POST","OPTIONS"], '/carsedit[/{ID}]', CarsController::class . ':edit')->add(PermissionMiddleware::class)->setName('carsedit-cars-edit'); // edit
    $app->map(["GET","POST","OPTIONS"], '/carsupdate', CarsController::class . ':update')->add(PermissionMiddleware::class)->setName('carsupdate-cars-update'); // update
    $app->map(["GET","POST","OPTIONS"], '/carsdelete[/{ID}]', CarsController::class . ':delete')->add(PermissionMiddleware::class)->setName('carsdelete-cars-delete'); // delete
    $app->map(["GET","POST","OPTIONS"], '/carssearch', CarsController::class . ':search')->add(PermissionMiddleware::class)->setName('carssearch-cars-search'); // search
    $app->map(["GET","POST","OPTIONS"], '/carsquery', CarsController::class . ':query')->add(PermissionMiddleware::class)->setName('carsquery-cars-query'); // query
    $app->group(
        '/cars',
        function (RouteCollectorProxy $group) {
            $group->map(["GET","POST","OPTIONS"], '/' . Config('LIST_ACTION') . '[/{ID}]', CarsController::class . ':list')->add(PermissionMiddleware::class)->setName('cars/list-cars-list-2'); // list
            $group->map(["GET","POST","OPTIONS"], '/' . Config('ADD_ACTION') . '[/{ID}]', CarsController::class . ':add')->add(PermissionMiddleware::class)->setName('cars/add-cars-add-2'); // add
            $group->map(["GET","POST","OPTIONS"], '/' . Config('VIEW_ACTION') . '[/{ID}]', CarsController::class . ':view')->add(PermissionMiddleware::class)->setName('cars/view-cars-view-2'); // view
            $group->map(["GET","POST","OPTIONS"], '/' . Config('EDIT_ACTION') . '[/{ID}]', CarsController::class . ':edit')->add(PermissionMiddleware::class)->setName('cars/edit-cars-edit-2'); // edit
            $group->map(["GET","POST","OPTIONS"], '/' . Config('UPDATE_ACTION') . '', CarsController::class . ':update')->add(PermissionMiddleware::class)->setName('cars/update-cars-update-2'); // update
            $group->map(["GET","POST","OPTIONS"], '/' . Config('DELETE_ACTION') . '[/{ID}]', CarsController::class . ':delete')->add(PermissionMiddleware::class)->setName('cars/delete-cars-delete-2'); // delete
            $group->map(["GET","POST","OPTIONS"], '/' . Config('SEARCH_ACTION') . '', CarsController::class . ':search')->add(PermissionMiddleware::class)->setName('cars/search-cars-search-2'); // search
            $group->map(["GET","POST","OPTIONS"], '/' . Config('QUERY_ACTION') . '', CarsController::class . ':query')->add(PermissionMiddleware::class)->setName('cars/query-cars-query-2'); // query
        }
    );

    // categories
    $app->map(["GET","POST","OPTIONS"], '/categorieslist[/{CategoryID}]', CategoriesController::class . ':list')->add(PermissionMiddleware::class)->setName('categorieslist-categories-list'); // list
    $app->map(["GET","POST","OPTIONS"], '/categoriesview[/{CategoryID}]', CategoriesController::class . ':view')->add(PermissionMiddleware::class)->setName('categoriesview-categories-view'); // view
    $app->map(["GET","POST","OPTIONS"], '/categoriesedit[/{CategoryID}]', CategoriesController::class . ':edit')->add(PermissionMiddleware::class)->setName('categoriesedit-categories-edit'); // edit
    $app->map(["GET","POST","OPTIONS"], '/categoriesdelete[/{CategoryID}]', CategoriesController::class . ':delete')->add(PermissionMiddleware::class)->setName('categoriesdelete-categories-delete'); // delete
    $app->map(["GET","POST","OPTIONS"], '/categoriessearch', CategoriesController::class . ':search')->add(PermissionMiddleware::class)->setName('categoriessearch-categories-search'); // search
    $app->group(
        '/categories',
        function (RouteCollectorProxy $group) {
            $group->map(["GET","POST","OPTIONS"], '/' . Config('LIST_ACTION') . '[/{CategoryID}]', CategoriesController::class . ':list')->add(PermissionMiddleware::class)->setName('categories/list-categories-list-2'); // list
            $group->map(["GET","POST","OPTIONS"], '/' . Config('VIEW_ACTION') . '[/{CategoryID}]', CategoriesController::class . ':view')->add(PermissionMiddleware::class)->setName('categories/view-categories-view-2'); // view
            $group->map(["GET","POST","OPTIONS"], '/' . Config('EDIT_ACTION') . '[/{CategoryID}]', CategoriesController::class . ':edit')->add(PermissionMiddleware::class)->setName('categories/edit-categories-edit-2'); // edit
            $group->map(["GET","POST","OPTIONS"], '/' . Config('DELETE_ACTION') . '[/{CategoryID}]', CategoriesController::class . ':delete')->add(PermissionMiddleware::class)->setName('categories/delete-categories-delete-2'); // delete
            $group->map(["GET","POST","OPTIONS"], '/' . Config('SEARCH_ACTION') . '', CategoriesController::class . ':search')->add(PermissionMiddleware::class)->setName('categories/search-categories-search-2'); // search
        }
    );

    // customers
    $app->map(["GET","POST","OPTIONS"], '/customerslist[/{CustomerID:.*}]', CustomersController::class . ':list')->add(PermissionMiddleware::class)->setName('customerslist-customers-list'); // list
    $app->map(["GET","POST","OPTIONS"], '/customersadd[/{CustomerID:.*}]', CustomersController::class . ':add')->add(PermissionMiddleware::class)->setName('customersadd-customers-add'); // add
    $app->map(["GET","POST","OPTIONS"], '/customersview[/{CustomerID:.*}]', CustomersController::class . ':view')->add(PermissionMiddleware::class)->setName('customersview-customers-view'); // view
    $app->map(["GET","POST","OPTIONS"], '/customersedit[/{CustomerID:.*}]', CustomersController::class . ':edit')->add(PermissionMiddleware::class)->setName('customersedit-customers-edit'); // edit
    $app->map(["GET","POST","OPTIONS"], '/customersdelete[/{CustomerID:.*}]', CustomersController::class . ':delete')->add(PermissionMiddleware::class)->setName('customersdelete-customers-delete'); // delete
    $app->map(["GET","POST","OPTIONS"], '/customerssearch', CustomersController::class . ':search')->add(PermissionMiddleware::class)->setName('customerssearch-customers-search'); // search
    $app->group(
        '/customers',
        function (RouteCollectorProxy $group) {
            $group->map(["GET","POST","OPTIONS"], '/' . Config('LIST_ACTION') . '[/{CustomerID:.*}]', CustomersController::class . ':list')->add(PermissionMiddleware::class)->setName('customers/list-customers-list-2'); // list
            $group->map(["GET","POST","OPTIONS"], '/' . Config('ADD_ACTION') . '[/{CustomerID:.*}]', CustomersController::class . ':add')->add(PermissionMiddleware::class)->setName('customers/add-customers-add-2'); // add
            $group->map(["GET","POST","OPTIONS"], '/' . Config('VIEW_ACTION') . '[/{CustomerID:.*}]', CustomersController::class . ':view')->add(PermissionMiddleware::class)->setName('customers/view-customers-view-2'); // view
            $group->map(["GET","POST","OPTIONS"], '/' . Config('EDIT_ACTION') . '[/{CustomerID:.*}]', CustomersController::class . ':edit')->add(PermissionMiddleware::class)->setName('customers/edit-customers-edit-2'); // edit
            $group->map(["GET","POST","OPTIONS"], '/' . Config('DELETE_ACTION') . '[/{CustomerID:.*}]', CustomersController::class . ':delete')->add(PermissionMiddleware::class)->setName('customers/delete-customers-delete-2'); // delete
            $group->map(["GET","POST","OPTIONS"], '/' . Config('SEARCH_ACTION') . '', CustomersController::class . ':search')->add(PermissionMiddleware::class)->setName('customers/search-customers-search-2'); // search
        }
    );

    // employees
    $app->map(["GET","POST","OPTIONS"], '/employeeslist[/{EmployeeID}]', EmployeesController::class . ':list')->add(PermissionMiddleware::class)->setName('employeeslist-employees-list'); // list
    $app->map(["GET","POST","OPTIONS"], '/employeesadd[/{EmployeeID}]', EmployeesController::class . ':add')->add(PermissionMiddleware::class)->setName('employeesadd-employees-add'); // add
    $app->map(["GET","POST","OPTIONS"], '/employeesview[/{EmployeeID}]', EmployeesController::class . ':view')->add(PermissionMiddleware::class)->setName('employeesview-employees-view'); // view
    $app->map(["GET","POST","OPTIONS"], '/employeesedit[/{EmployeeID}]', EmployeesController::class . ':edit')->add(PermissionMiddleware::class)->setName('employeesedit-employees-edit'); // edit
    $app->map(["GET","POST","OPTIONS"], '/employeesdelete[/{EmployeeID}]', EmployeesController::class . ':delete')->add(PermissionMiddleware::class)->setName('employeesdelete-employees-delete'); // delete
    $app->map(["GET","POST","OPTIONS"], '/employeessearch', EmployeesController::class . ':search')->add(PermissionMiddleware::class)->setName('employeessearch-employees-search'); // search
    $app->group(
        '/employees',
        function (RouteCollectorProxy $group) {
            $group->map(["GET","POST","OPTIONS"], '/' . Config('LIST_ACTION') . '[/{EmployeeID}]', EmployeesController::class . ':list')->add(PermissionMiddleware::class)->setName('employees/list-employees-list-2'); // list
            $group->map(["GET","POST","OPTIONS"], '/' . Config('ADD_ACTION') . '[/{EmployeeID}]', EmployeesController::class . ':add')->add(PermissionMiddleware::class)->setName('employees/add-employees-add-2'); // add
            $group->map(["GET","POST","OPTIONS"], '/' . Config('VIEW_ACTION') . '[/{EmployeeID}]', EmployeesController::class . ':view')->add(PermissionMiddleware::class)->setName('employees/view-employees-view-2'); // view
            $group->map(["GET","POST","OPTIONS"], '/' . Config('EDIT_ACTION') . '[/{EmployeeID}]', EmployeesController::class . ':edit')->add(PermissionMiddleware::class)->setName('employees/edit-employees-edit-2'); // edit
            $group->map(["GET","POST","OPTIONS"], '/' . Config('DELETE_ACTION') . '[/{EmployeeID}]', EmployeesController::class . ':delete')->add(PermissionMiddleware::class)->setName('employees/delete-employees-delete-2'); // delete
            $group->map(["GET","POST","OPTIONS"], '/' . Config('SEARCH_ACTION') . '', EmployeesController::class . ':search')->add(PermissionMiddleware::class)->setName('employees/search-employees-search-2'); // search
        }
    );

    // orderdetails
    $app->map(["GET","POST","OPTIONS"], '/orderdetailslist[/{keys:.*}]', OrderdetailsController::class . ':list')->add(PermissionMiddleware::class)->setName('orderdetailslist-orderdetails-list'); // list
    $app->map(["GET","POST","OPTIONS"], '/orderdetailsadd[/{keys:.*}]', OrderdetailsController::class . ':add')->add(PermissionMiddleware::class)->setName('orderdetailsadd-orderdetails-add'); // add
    $app->map(["GET","POST","OPTIONS"], '/orderdetailsview[/{keys:.*}]', OrderdetailsController::class . ':view')->add(PermissionMiddleware::class)->setName('orderdetailsview-orderdetails-view'); // view
    $app->map(["GET","POST","OPTIONS"], '/orderdetailsedit[/{keys:.*}]', OrderdetailsController::class . ':edit')->add(PermissionMiddleware::class)->setName('orderdetailsedit-orderdetails-edit'); // edit
    $app->map(["GET","POST","OPTIONS"], '/orderdetailsdelete[/{keys:.*}]', OrderdetailsController::class . ':delete')->add(PermissionMiddleware::class)->setName('orderdetailsdelete-orderdetails-delete'); // delete
    $app->map(["GET","POST","OPTIONS"], '/orderdetailssearch', OrderdetailsController::class . ':search')->add(PermissionMiddleware::class)->setName('orderdetailssearch-orderdetails-search'); // search
    $app->group(
        '/orderdetails',
        function (RouteCollectorProxy $group) {
            $group->map(["GET","POST","OPTIONS"], '/' . Config('LIST_ACTION') . '[/{keys:.*}]', OrderdetailsController::class . ':list')->add(PermissionMiddleware::class)->setName('orderdetails/list-orderdetails-list-2'); // list
            $group->map(["GET","POST","OPTIONS"], '/' . Config('ADD_ACTION') . '[/{keys:.*}]', OrderdetailsController::class . ':add')->add(PermissionMiddleware::class)->setName('orderdetails/add-orderdetails-add-2'); // add
            $group->map(["GET","POST","OPTIONS"], '/' . Config('VIEW_ACTION') . '[/{keys:.*}]', OrderdetailsController::class . ':view')->add(PermissionMiddleware::class)->setName('orderdetails/view-orderdetails-view-2'); // view
            $group->map(["GET","POST","OPTIONS"], '/' . Config('EDIT_ACTION') . '[/{keys:.*}]', OrderdetailsController::class . ':edit')->add(PermissionMiddleware::class)->setName('orderdetails/edit-orderdetails-edit-2'); // edit
            $group->map(["GET","POST","OPTIONS"], '/' . Config('DELETE_ACTION') . '[/{keys:.*}]', OrderdetailsController::class . ':delete')->add(PermissionMiddleware::class)->setName('orderdetails/delete-orderdetails-delete-2'); // delete
            $group->map(["GET","POST","OPTIONS"], '/' . Config('SEARCH_ACTION') . '', OrderdetailsController::class . ':search')->add(PermissionMiddleware::class)->setName('orderdetails/search-orderdetails-search-2'); // search
        }
    );

    // orders
    $app->map(["GET","POST","OPTIONS"], '/orderslist[/{OrderID}]', OrdersController::class . ':list')->add(PermissionMiddleware::class)->setName('orderslist-orders-list'); // list
    $app->map(["GET","POST","OPTIONS"], '/ordersadd[/{OrderID}]', OrdersController::class . ':add')->add(PermissionMiddleware::class)->setName('ordersadd-orders-add'); // add
    $app->map(["GET","POST","OPTIONS"], '/ordersview[/{OrderID}]', OrdersController::class . ':view')->add(PermissionMiddleware::class)->setName('ordersview-orders-view'); // view
    $app->map(["GET","POST","OPTIONS"], '/ordersedit[/{OrderID}]', OrdersController::class . ':edit')->add(PermissionMiddleware::class)->setName('ordersedit-orders-edit'); // edit
    $app->map(["GET","POST","OPTIONS"], '/ordersdelete[/{OrderID}]', OrdersController::class . ':delete')->add(PermissionMiddleware::class)->setName('ordersdelete-orders-delete'); // delete
    $app->map(["GET","POST","OPTIONS"], '/orderssearch', OrdersController::class . ':search')->add(PermissionMiddleware::class)->setName('orderssearch-orders-search'); // search
    $app->map(["GET","POST","OPTIONS"], '/ordersquery', OrdersController::class . ':query')->add(PermissionMiddleware::class)->setName('ordersquery-orders-query'); // query
    $app->group(
        '/orders',
        function (RouteCollectorProxy $group) {
            $group->map(["GET","POST","OPTIONS"], '/' . Config('LIST_ACTION') . '[/{OrderID}]', OrdersController::class . ':list')->add(PermissionMiddleware::class)->setName('orders/list-orders-list-2'); // list
            $group->map(["GET","POST","OPTIONS"], '/' . Config('ADD_ACTION') . '[/{OrderID}]', OrdersController::class . ':add')->add(PermissionMiddleware::class)->setName('orders/add-orders-add-2'); // add
            $group->map(["GET","POST","OPTIONS"], '/' . Config('VIEW_ACTION') . '[/{OrderID}]', OrdersController::class . ':view')->add(PermissionMiddleware::class)->setName('orders/view-orders-view-2'); // view
            $group->map(["GET","POST","OPTIONS"], '/' . Config('EDIT_ACTION') . '[/{OrderID}]', OrdersController::class . ':edit')->add(PermissionMiddleware::class)->setName('orders/edit-orders-edit-2'); // edit
            $group->map(["GET","POST","OPTIONS"], '/' . Config('DELETE_ACTION') . '[/{OrderID}]', OrdersController::class . ':delete')->add(PermissionMiddleware::class)->setName('orders/delete-orders-delete-2'); // delete
            $group->map(["GET","POST","OPTIONS"], '/' . Config('SEARCH_ACTION') . '', OrdersController::class . ':search')->add(PermissionMiddleware::class)->setName('orders/search-orders-search-2'); // search
            $group->map(["GET","POST","OPTIONS"], '/' . Config('QUERY_ACTION') . '', OrdersController::class . ':query')->add(PermissionMiddleware::class)->setName('orders/query-orders-query-2'); // query
        }
    );

    // products
    $app->map(["GET","POST","OPTIONS"], '/productslist[/{ProductID}]', ProductsController::class . ':list')->add(PermissionMiddleware::class)->setName('productslist-products-list'); // list
    $app->map(["GET","POST","OPTIONS"], '/productsadd[/{ProductID}]', ProductsController::class . ':add')->add(PermissionMiddleware::class)->setName('productsadd-products-add'); // add
    $app->map(["GET","POST","OPTIONS"], '/productsview[/{ProductID}]', ProductsController::class . ':view')->add(PermissionMiddleware::class)->setName('productsview-products-view'); // view
    $app->map(["GET","POST","OPTIONS"], '/productsedit[/{ProductID}]', ProductsController::class . ':edit')->add(PermissionMiddleware::class)->setName('productsedit-products-edit'); // edit
    $app->map(["GET","POST","OPTIONS"], '/productsdelete[/{ProductID}]', ProductsController::class . ':delete')->add(PermissionMiddleware::class)->setName('productsdelete-products-delete'); // delete
    $app->map(["GET","POST","OPTIONS"], '/productssearch', ProductsController::class . ':search')->add(PermissionMiddleware::class)->setName('productssearch-products-search'); // search
    $app->group(
        '/products',
        function (RouteCollectorProxy $group) {
            $group->map(["GET","POST","OPTIONS"], '/' . Config('LIST_ACTION') . '[/{ProductID}]', ProductsController::class . ':list')->add(PermissionMiddleware::class)->setName('products/list-products-list-2'); // list
            $group->map(["GET","POST","OPTIONS"], '/' . Config('ADD_ACTION') . '[/{ProductID}]', ProductsController::class . ':add')->add(PermissionMiddleware::class)->setName('products/add-products-add-2'); // add
            $group->map(["GET","POST","OPTIONS"], '/' . Config('VIEW_ACTION') . '[/{ProductID}]', ProductsController::class . ':view')->add(PermissionMiddleware::class)->setName('products/view-products-view-2'); // view
            $group->map(["GET","POST","OPTIONS"], '/' . Config('EDIT_ACTION') . '[/{ProductID}]', ProductsController::class . ':edit')->add(PermissionMiddleware::class)->setName('products/edit-products-edit-2'); // edit
            $group->map(["GET","POST","OPTIONS"], '/' . Config('DELETE_ACTION') . '[/{ProductID}]', ProductsController::class . ':delete')->add(PermissionMiddleware::class)->setName('products/delete-products-delete-2'); // delete
            $group->map(["GET","POST","OPTIONS"], '/' . Config('SEARCH_ACTION') . '', ProductsController::class . ':search')->add(PermissionMiddleware::class)->setName('products/search-products-search-2'); // search
        }
    );

    // shippers
    $app->map(["GET","POST","OPTIONS"], '/shipperslist[/{ShipperID}]', ShippersController::class . ':list')->add(PermissionMiddleware::class)->setName('shipperslist-shippers-list'); // list
    $app->map(["GET","POST","OPTIONS"], '/shipperssearch', ShippersController::class . ':search')->add(PermissionMiddleware::class)->setName('shipperssearch-shippers-search'); // search
    $app->group(
        '/shippers',
        function (RouteCollectorProxy $group) {
            $group->map(["GET","POST","OPTIONS"], '/' . Config('LIST_ACTION') . '[/{ShipperID}]', ShippersController::class . ':list')->add(PermissionMiddleware::class)->setName('shippers/list-shippers-list-2'); // list
            $group->map(["GET","POST","OPTIONS"], '/' . Config('SEARCH_ACTION') . '', ShippersController::class . ':search')->add(PermissionMiddleware::class)->setName('shippers/search-shippers-search-2'); // search
        }
    );

    // suppliers
    $app->map(["GET","POST","OPTIONS"], '/supplierslist[/{SupplierID}]', SuppliersController::class . ':list')->add(PermissionMiddleware::class)->setName('supplierslist-suppliers-list'); // list
    $app->map(["GET","POST","OPTIONS"], '/suppliersadd[/{SupplierID}]', SuppliersController::class . ':add')->add(PermissionMiddleware::class)->setName('suppliersadd-suppliers-add'); // add
    $app->map(["GET","POST","OPTIONS"], '/suppliersview[/{SupplierID}]', SuppliersController::class . ':view')->add(PermissionMiddleware::class)->setName('suppliersview-suppliers-view'); // view
    $app->map(["GET","POST","OPTIONS"], '/suppliersedit[/{SupplierID}]', SuppliersController::class . ':edit')->add(PermissionMiddleware::class)->setName('suppliersedit-suppliers-edit'); // edit
    $app->map(["GET","POST","OPTIONS"], '/suppliersupdate', SuppliersController::class . ':update')->add(PermissionMiddleware::class)->setName('suppliersupdate-suppliers-update'); // update
    $app->map(["GET","POST","OPTIONS"], '/suppliersdelete[/{SupplierID}]', SuppliersController::class . ':delete')->add(PermissionMiddleware::class)->setName('suppliersdelete-suppliers-delete'); // delete
    $app->map(["GET","POST","OPTIONS"], '/supplierssearch', SuppliersController::class . ':search')->add(PermissionMiddleware::class)->setName('supplierssearch-suppliers-search'); // search
    $app->group(
        '/suppliers',
        function (RouteCollectorProxy $group) {
            $group->map(["GET","POST","OPTIONS"], '/' . Config('LIST_ACTION') . '[/{SupplierID}]', SuppliersController::class . ':list')->add(PermissionMiddleware::class)->setName('suppliers/list-suppliers-list-2'); // list
            $group->map(["GET","POST","OPTIONS"], '/' . Config('ADD_ACTION') . '[/{SupplierID}]', SuppliersController::class . ':add')->add(PermissionMiddleware::class)->setName('suppliers/add-suppliers-add-2'); // add
            $group->map(["GET","POST","OPTIONS"], '/' . Config('VIEW_ACTION') . '[/{SupplierID}]', SuppliersController::class . ':view')->add(PermissionMiddleware::class)->setName('suppliers/view-suppliers-view-2'); // view
            $group->map(["GET","POST","OPTIONS"], '/' . Config('EDIT_ACTION') . '[/{SupplierID}]', SuppliersController::class . ':edit')->add(PermissionMiddleware::class)->setName('suppliers/edit-suppliers-edit-2'); // edit
            $group->map(["GET","POST","OPTIONS"], '/' . Config('UPDATE_ACTION') . '', SuppliersController::class . ':update')->add(PermissionMiddleware::class)->setName('suppliers/update-suppliers-update-2'); // update
            $group->map(["GET","POST","OPTIONS"], '/' . Config('DELETE_ACTION') . '[/{SupplierID}]', SuppliersController::class . ':delete')->add(PermissionMiddleware::class)->setName('suppliers/delete-suppliers-delete-2'); // delete
            $group->map(["GET","POST","OPTIONS"], '/' . Config('SEARCH_ACTION') . '', SuppliersController::class . ':search')->add(PermissionMiddleware::class)->setName('suppliers/search-suppliers-search-2'); // search
        }
    );

    // models
    $app->map(["GET","POST","OPTIONS"], '/modelslist[/{ID}]', ModelsController::class . ':list')->add(PermissionMiddleware::class)->setName('modelslist-models-list'); // list
    $app->map(["GET","POST","OPTIONS"], '/modelsadd[/{ID}]', ModelsController::class . ':add')->add(PermissionMiddleware::class)->setName('modelsadd-models-add'); // add
    $app->map(["GET","POST","OPTIONS"], '/modelsaddopt', ModelsController::class . ':addopt')->add(PermissionMiddleware::class)->setName('modelsaddopt-models-addopt'); // addopt
    $app->map(["GET","POST","OPTIONS"], '/modelsview[/{ID}]', ModelsController::class . ':view')->add(PermissionMiddleware::class)->setName('modelsview-models-view'); // view
    $app->map(["GET","POST","OPTIONS"], '/modelsedit[/{ID}]', ModelsController::class . ':edit')->add(PermissionMiddleware::class)->setName('modelsedit-models-edit'); // edit
    $app->map(["GET","POST","OPTIONS"], '/modelsdelete[/{ID}]', ModelsController::class . ':delete')->add(PermissionMiddleware::class)->setName('modelsdelete-models-delete'); // delete
    $app->map(["GET","POST","OPTIONS"], '/modelssearch', ModelsController::class . ':search')->add(PermissionMiddleware::class)->setName('modelssearch-models-search'); // search
    $app->group(
        '/models',
        function (RouteCollectorProxy $group) {
            $group->map(["GET","POST","OPTIONS"], '/' . Config('LIST_ACTION') . '[/{ID}]', ModelsController::class . ':list')->add(PermissionMiddleware::class)->setName('models/list-models-list-2'); // list
            $group->map(["GET","POST","OPTIONS"], '/' . Config('ADD_ACTION') . '[/{ID}]', ModelsController::class . ':add')->add(PermissionMiddleware::class)->setName('models/add-models-add-2'); // add
            $group->map(["GET","POST","OPTIONS"], '/' . Config('ADDOPT_ACTION') . '', ModelsController::class . ':addopt')->add(PermissionMiddleware::class)->setName('models/addopt-models-addopt-2'); // addopt
            $group->map(["GET","POST","OPTIONS"], '/' . Config('VIEW_ACTION') . '[/{ID}]', ModelsController::class . ':view')->add(PermissionMiddleware::class)->setName('models/view-models-view-2'); // view
            $group->map(["GET","POST","OPTIONS"], '/' . Config('EDIT_ACTION') . '[/{ID}]', ModelsController::class . ':edit')->add(PermissionMiddleware::class)->setName('models/edit-models-edit-2'); // edit
            $group->map(["GET","POST","OPTIONS"], '/' . Config('DELETE_ACTION') . '[/{ID}]', ModelsController::class . ':delete')->add(PermissionMiddleware::class)->setName('models/delete-models-delete-2'); // delete
            $group->map(["GET","POST","OPTIONS"], '/' . Config('SEARCH_ACTION') . '', ModelsController::class . ':search')->add(PermissionMiddleware::class)->setName('models/search-models-search-2'); // search
        }
    );

    // trademarks
    $app->map(["GET","POST","OPTIONS"], '/trademarkslist[/{ID}]', TrademarksController::class . ':list')->add(PermissionMiddleware::class)->setName('trademarkslist-trademarks-list'); // list
    $app->map(["GET","POST","OPTIONS"], '/trademarksadd[/{ID}]', TrademarksController::class . ':add')->add(PermissionMiddleware::class)->setName('trademarksadd-trademarks-add'); // add
    $app->map(["GET","POST","OPTIONS"], '/trademarksaddopt', TrademarksController::class . ':addopt')->add(PermissionMiddleware::class)->setName('trademarksaddopt-trademarks-addopt'); // addopt
    $app->map(["GET","POST","OPTIONS"], '/trademarksview[/{ID}]', TrademarksController::class . ':view')->add(PermissionMiddleware::class)->setName('trademarksview-trademarks-view'); // view
    $app->map(["GET","POST","OPTIONS"], '/trademarksedit[/{ID}]', TrademarksController::class . ':edit')->add(PermissionMiddleware::class)->setName('trademarksedit-trademarks-edit'); // edit
    $app->map(["GET","POST","OPTIONS"], '/trademarksdelete[/{ID}]', TrademarksController::class . ':delete')->add(PermissionMiddleware::class)->setName('trademarksdelete-trademarks-delete'); // delete
    $app->map(["GET","POST","OPTIONS"], '/trademarkssearch', TrademarksController::class . ':search')->add(PermissionMiddleware::class)->setName('trademarkssearch-trademarks-search'); // search
    $app->group(
        '/trademarks',
        function (RouteCollectorProxy $group) {
            $group->map(["GET","POST","OPTIONS"], '/' . Config('LIST_ACTION') . '[/{ID}]', TrademarksController::class . ':list')->add(PermissionMiddleware::class)->setName('trademarks/list-trademarks-list-2'); // list
            $group->map(["GET","POST","OPTIONS"], '/' . Config('ADD_ACTION') . '[/{ID}]', TrademarksController::class . ':add')->add(PermissionMiddleware::class)->setName('trademarks/add-trademarks-add-2'); // add
            $group->map(["GET","POST","OPTIONS"], '/' . Config('ADDOPT_ACTION') . '', TrademarksController::class . ':addopt')->add(PermissionMiddleware::class)->setName('trademarks/addopt-trademarks-addopt-2'); // addopt
            $group->map(["GET","POST","OPTIONS"], '/' . Config('VIEW_ACTION') . '[/{ID}]', TrademarksController::class . ':view')->add(PermissionMiddleware::class)->setName('trademarks/view-trademarks-view-2'); // view
            $group->map(["GET","POST","OPTIONS"], '/' . Config('EDIT_ACTION') . '[/{ID}]', TrademarksController::class . ':edit')->add(PermissionMiddleware::class)->setName('trademarks/edit-trademarks-edit-2'); // edit
            $group->map(["GET","POST","OPTIONS"], '/' . Config('DELETE_ACTION') . '[/{ID}]', TrademarksController::class . ':delete')->add(PermissionMiddleware::class)->setName('trademarks/delete-trademarks-delete-2'); // delete
            $group->map(["GET","POST","OPTIONS"], '/' . Config('SEARCH_ACTION') . '', TrademarksController::class . ':search')->add(PermissionMiddleware::class)->setName('trademarks/search-trademarks-search-2'); // search
        }
    );

    // userlevelpermissions
    $app->map(["GET","POST","OPTIONS"], '/userlevelpermissionslist[/{keys:.*}]', UserlevelpermissionsController::class . ':list')->add(PermissionMiddleware::class)->setName('userlevelpermissionslist-userlevelpermissions-list'); // list
    $app->map(["GET","POST","OPTIONS"], '/userlevelpermissionsadd[/{keys:.*}]', UserlevelpermissionsController::class . ':add')->add(PermissionMiddleware::class)->setName('userlevelpermissionsadd-userlevelpermissions-add'); // add
    $app->map(["GET","POST","OPTIONS"], '/userlevelpermissionsview[/{keys:.*}]', UserlevelpermissionsController::class . ':view')->add(PermissionMiddleware::class)->setName('userlevelpermissionsview-userlevelpermissions-view'); // view
    $app->map(["GET","POST","OPTIONS"], '/userlevelpermissionsedit[/{keys:.*}]', UserlevelpermissionsController::class . ':edit')->add(PermissionMiddleware::class)->setName('userlevelpermissionsedit-userlevelpermissions-edit'); // edit
    $app->map(["GET","POST","OPTIONS"], '/userlevelpermissionsdelete[/{keys:.*}]', UserlevelpermissionsController::class . ':delete')->add(PermissionMiddleware::class)->setName('userlevelpermissionsdelete-userlevelpermissions-delete'); // delete
    $app->group(
        '/userlevelpermissions',
        function (RouteCollectorProxy $group) {
            $group->map(["GET","POST","OPTIONS"], '/' . Config('LIST_ACTION') . '[/{keys:.*}]', UserlevelpermissionsController::class . ':list')->add(PermissionMiddleware::class)->setName('userlevelpermissions/list-userlevelpermissions-list-2'); // list
            $group->map(["GET","POST","OPTIONS"], '/' . Config('ADD_ACTION') . '[/{keys:.*}]', UserlevelpermissionsController::class . ':add')->add(PermissionMiddleware::class)->setName('userlevelpermissions/add-userlevelpermissions-add-2'); // add
            $group->map(["GET","POST","OPTIONS"], '/' . Config('VIEW_ACTION') . '[/{keys:.*}]', UserlevelpermissionsController::class . ':view')->add(PermissionMiddleware::class)->setName('userlevelpermissions/view-userlevelpermissions-view-2'); // view
            $group->map(["GET","POST","OPTIONS"], '/' . Config('EDIT_ACTION') . '[/{keys:.*}]', UserlevelpermissionsController::class . ':edit')->add(PermissionMiddleware::class)->setName('userlevelpermissions/edit-userlevelpermissions-edit-2'); // edit
            $group->map(["GET","POST","OPTIONS"], '/' . Config('DELETE_ACTION') . '[/{keys:.*}]', UserlevelpermissionsController::class . ':delete')->add(PermissionMiddleware::class)->setName('userlevelpermissions/delete-userlevelpermissions-delete-2'); // delete
        }
    );

    // userlevels
    $app->map(["GET","POST","OPTIONS"], '/userlevelslist[/{userlevelid}]', UserlevelsController::class . ':list')->add(PermissionMiddleware::class)->setName('userlevelslist-userlevels-list'); // list
    $app->map(["GET","POST","OPTIONS"], '/userlevelsadd[/{userlevelid}]', UserlevelsController::class . ':add')->add(PermissionMiddleware::class)->setName('userlevelsadd-userlevels-add'); // add
    $app->map(["GET","POST","OPTIONS"], '/userlevelsview[/{userlevelid}]', UserlevelsController::class . ':view')->add(PermissionMiddleware::class)->setName('userlevelsview-userlevels-view'); // view
    $app->map(["GET","POST","OPTIONS"], '/userlevelsedit[/{userlevelid}]', UserlevelsController::class . ':edit')->add(PermissionMiddleware::class)->setName('userlevelsedit-userlevels-edit'); // edit
    $app->map(["GET","POST","OPTIONS"], '/userlevelsdelete[/{userlevelid}]', UserlevelsController::class . ':delete')->add(PermissionMiddleware::class)->setName('userlevelsdelete-userlevels-delete'); // delete
    $app->group(
        '/userlevels',
        function (RouteCollectorProxy $group) {
            $group->map(["GET","POST","OPTIONS"], '/' . Config('LIST_ACTION') . '[/{userlevelid}]', UserlevelsController::class . ':list')->add(PermissionMiddleware::class)->setName('userlevels/list-userlevels-list-2'); // list
            $group->map(["GET","POST","OPTIONS"], '/' . Config('ADD_ACTION') . '[/{userlevelid}]', UserlevelsController::class . ':add')->add(PermissionMiddleware::class)->setName('userlevels/add-userlevels-add-2'); // add
            $group->map(["GET","POST","OPTIONS"], '/' . Config('VIEW_ACTION') . '[/{userlevelid}]', UserlevelsController::class . ':view')->add(PermissionMiddleware::class)->setName('userlevels/view-userlevels-view-2'); // view
            $group->map(["GET","POST","OPTIONS"], '/' . Config('EDIT_ACTION') . '[/{userlevelid}]', UserlevelsController::class . ':edit')->add(PermissionMiddleware::class)->setName('userlevels/edit-userlevels-edit-2'); // edit
            $group->map(["GET","POST","OPTIONS"], '/' . Config('DELETE_ACTION') . '[/{userlevelid}]', UserlevelsController::class . ':delete')->add(PermissionMiddleware::class)->setName('userlevels/delete-userlevels-delete-2'); // delete
        }
    );

    // order_details_extended
    $app->map(["GET","POST","OPTIONS"], '/orderdetailsextendedlist[/{OrderID}]', OrderDetailsExtendedController::class . ':list')->add(PermissionMiddleware::class)->setName('orderdetailsextendedlist-order_details_extended-list'); // list
    $app->group(
        '/order_details_extended',
        function (RouteCollectorProxy $group) {
            $group->map(["GET","POST","OPTIONS"], '/' . Config('LIST_ACTION') . '[/{OrderID}]', OrderDetailsExtendedController::class . ':list')->add(PermissionMiddleware::class)->setName('order_details_extended/list-order_details_extended-list-2'); // list
        }
    );

    // orders2
    $app->map(["GET", "POST", "OPTIONS"], '/orders2list/FreightByEmployees', Orders2Controller::class . ':FreightByEmployees')->add(PermissionMiddleware::class)->setName('orders2list-orders2-list-FreightByEmployees'); // FreightByEmployees
    $app->map(["GET","POST","OPTIONS"], '/orders2list[/{OrderID}]', Orders2Controller::class . ':list')->add(PermissionMiddleware::class)->setName('orders2list-orders2-list'); // list
    $app->map(["GET","POST","OPTIONS"], '/orders2add[/{OrderID}]', Orders2Controller::class . ':add')->add(PermissionMiddleware::class)->setName('orders2add-orders2-add'); // add
    $app->map(["GET","POST","OPTIONS"], '/orders2view[/{OrderID}]', Orders2Controller::class . ':view')->add(PermissionMiddleware::class)->setName('orders2view-orders2-view'); // view
    $app->map(["GET","POST","OPTIONS"], '/orders2edit[/{OrderID}]', Orders2Controller::class . ':edit')->add(PermissionMiddleware::class)->setName('orders2edit-orders2-edit'); // edit
    $app->map(["GET","POST","OPTIONS"], '/orders2update', Orders2Controller::class . ':update')->add(PermissionMiddleware::class)->setName('orders2update-orders2-update'); // update
    $app->map(["GET","POST","OPTIONS"], '/orders2delete[/{OrderID}]', Orders2Controller::class . ':delete')->add(PermissionMiddleware::class)->setName('orders2delete-orders2-delete'); // delete
    $app->group(
        '/orders2',
        function (RouteCollectorProxy $group) {
            $group->map(["GET","POST","OPTIONS"], '/' . Config('LIST_ACTION') . '[/{OrderID}]', Orders2Controller::class . ':list')->add(PermissionMiddleware::class)->setName('orders2/list-orders2-list-2'); // list
            $group->map(["GET","POST","OPTIONS"], '/' . Config('ADD_ACTION') . '[/{OrderID}]', Orders2Controller::class . ':add')->add(PermissionMiddleware::class)->setName('orders2/add-orders2-add-2'); // add
            $group->map(["GET","POST","OPTIONS"], '/' . Config('VIEW_ACTION') . '[/{OrderID}]', Orders2Controller::class . ':view')->add(PermissionMiddleware::class)->setName('orders2/view-orders2-view-2'); // view
            $group->map(["GET","POST","OPTIONS"], '/' . Config('EDIT_ACTION') . '[/{OrderID}]', Orders2Controller::class . ':edit')->add(PermissionMiddleware::class)->setName('orders2/edit-orders2-edit-2'); // edit
            $group->map(["GET","POST","OPTIONS"], '/' . Config('UPDATE_ACTION') . '', Orders2Controller::class . ':update')->add(PermissionMiddleware::class)->setName('orders2/update-orders2-update-2'); // update
            $group->map(["GET","POST","OPTIONS"], '/' . Config('DELETE_ACTION') . '[/{OrderID}]', Orders2Controller::class . ':delete')->add(PermissionMiddleware::class)->setName('orders2/delete-orders2-delete-2'); // delete
        }
    );

    // cars2
    $app->map(["GET","POST","OPTIONS"], '/cars2list[/{ID}]', Cars2Controller::class . ':list')->add(PermissionMiddleware::class)->setName('cars2list-cars2-list'); // list
    $app->group(
        '/cars2',
        function (RouteCollectorProxy $group) {
            $group->map(["GET","POST","OPTIONS"], '/' . Config('LIST_ACTION') . '[/{ID}]', Cars2Controller::class . ':list')->add(PermissionMiddleware::class)->setName('cars2/list-cars2-list-2'); // list
        }
    );

    // home
    $app->map(["GET", "POST", "OPTIONS"], '/home[/{params:.*}]', HomeController::class . ':custom')->add(PermissionMiddleware::class)->setName('home-home-custom'); // custom

    // news
    $app->map(["GET", "POST", "OPTIONS"], '/news[/{params:.*}]', NewsController::class . ':custom')->add(PermissionMiddleware::class)->setName('news-news-custom'); // custom

    // dji
    $app->map(["GET","POST","OPTIONS"], '/djilist[/{ID}]', DjiController::class . ':list')->add(PermissionMiddleware::class)->setName('djilist-dji-list'); // list
    $app->map(["GET","POST","OPTIONS"], '/djiadd[/{ID}]', DjiController::class . ':add')->add(PermissionMiddleware::class)->setName('djiadd-dji-add'); // add
    $app->map(["GET","POST","OPTIONS"], '/djiview[/{ID}]', DjiController::class . ':view')->add(PermissionMiddleware::class)->setName('djiview-dji-view'); // view
    $app->map(["GET","POST","OPTIONS"], '/djiedit[/{ID}]', DjiController::class . ':edit')->add(PermissionMiddleware::class)->setName('djiedit-dji-edit'); // edit
    $app->map(["GET","POST","OPTIONS"], '/djidelete[/{ID}]', DjiController::class . ':delete')->add(PermissionMiddleware::class)->setName('djidelete-dji-delete'); // delete
    $app->group(
        '/dji',
        function (RouteCollectorProxy $group) {
            $group->map(["GET","POST","OPTIONS"], '/' . Config('LIST_ACTION') . '[/{ID}]', DjiController::class . ':list')->add(PermissionMiddleware::class)->setName('dji/list-dji-list-2'); // list
            $group->map(["GET","POST","OPTIONS"], '/' . Config('ADD_ACTION') . '[/{ID}]', DjiController::class . ':add')->add(PermissionMiddleware::class)->setName('dji/add-dji-add-2'); // add
            $group->map(["GET","POST","OPTIONS"], '/' . Config('VIEW_ACTION') . '[/{ID}]', DjiController::class . ':view')->add(PermissionMiddleware::class)->setName('dji/view-dji-view-2'); // view
            $group->map(["GET","POST","OPTIONS"], '/' . Config('EDIT_ACTION') . '[/{ID}]', DjiController::class . ':edit')->add(PermissionMiddleware::class)->setName('dji/edit-dji-edit-2'); // edit
            $group->map(["GET","POST","OPTIONS"], '/' . Config('DELETE_ACTION') . '[/{ID}]', DjiController::class . ':delete')->add(PermissionMiddleware::class)->setName('dji/delete-dji-delete-2'); // delete
        }
    );

    // Quarterly_Orders_By_Product
    $app->map(["GET", "POST", "OPTIONS"], '/quarterlyordersbyproduct/OrdersByCategory', QuarterlyOrdersByProductController::class . ':OrdersByCategory')->add(PermissionMiddleware::class)->setName('quarterlyordersbyproduct-Quarterly_Orders_By_Product-crosstab-OrdersByCategory'); // OrdersByCategory
    $app->map(["GET", "POST", "OPTIONS"], '/quarterlyordersbyproduct', QuarterlyOrdersByProductController::class . ':crosstab')->add(PermissionMiddleware::class)->setName('quarterlyordersbyproduct-Quarterly_Orders_By_Product-crosstab'); // crosstab

    // Sales_By_Customer
    $app->map(["GET", "POST", "OPTIONS"], '/salesbycustomer', SalesByCustomerController::class . ':summary')->add(PermissionMiddleware::class)->setName('salesbycustomer-Sales_By_Customer-summary'); // summary

    // Sales_By_Customer_Compact
    $app->map(["GET", "POST", "OPTIONS"], '/salesbycustomercompact', SalesByCustomerCompactController::class . ':summary')->add(PermissionMiddleware::class)->setName('salesbycustomercompact-Sales_By_Customer_Compact-summary'); // summary

    // Alphabetical_List_of_Products
    $app->map(["GET", "POST", "OPTIONS"], '/alphabeticallistofproducts', AlphabeticalListOfProductsController::class . ':summary')->add(PermissionMiddleware::class)->setName('alphabeticallistofproducts-Alphabetical_List_of_Products-summary'); // summary

    // Products_By_Category
    $app->map(["GET", "POST", "OPTIONS"], '/productsbycategory', ProductsByCategoryController::class . ':summary')->add(PermissionMiddleware::class)->setName('productsbycategory-Products_By_Category-summary'); // summary

    // Sales_by_Category_for_2014
    $app->map(["GET", "POST", "OPTIONS"], '/salesbycategoryfor2014/SalesByCategory2014', SalesByCategoryFor2014Controller::class . ':SalesByCategory2014')->add(PermissionMiddleware::class)->setName('salesbycategoryfor2014-Sales_by_Category_for_2014-summary-SalesByCategory2014'); // SalesByCategory2014
    $app->map(["GET", "POST", "OPTIONS"], '/salesbycategoryfor2014', SalesByCategoryFor2014Controller::class . ':summary')->add(PermissionMiddleware::class)->setName('salesbycategoryfor2014-Sales_by_Category_for_2014-summary'); // summary

    // Sales_By_Year
    $app->map(["GET", "POST", "OPTIONS"], '/salesbyyear', SalesByYearController::class . ':summary')->add(PermissionMiddleware::class)->setName('salesbyyear-Sales_By_Year-summary'); // summary

    // Sales_By_Order
    $app->map(["GET", "POST", "OPTIONS"], '/salesbyorder', SalesByOrderController::class . ':summary')->add(PermissionMiddleware::class)->setName('salesbyorder-Sales_By_Order-summary'); // summary

    // Sales_By_Customer_2
    $app->map(["GET", "POST", "OPTIONS"], '/salesbycustomer2', SalesByCustomer2Controller::class . ':summary')->add(PermissionMiddleware::class)->setName('salesbycustomer2-Sales_By_Customer_2-summary'); // summary

    // Dashboard1
    $app->map(["GET", "POST", "OPTIONS"], '/dashboard1', Dashboard1Controller::class . ':dashboard')->add(PermissionMiddleware::class)->setName('dashboard1-Dashboard1-dashboard'); // dashboard

    // orders_by_product2
    $app->map(["GET", "POST", "OPTIONS"], '/ordersbyproduct2/OrderByProducts', OrdersByProduct2Controller::class . ':OrderByProducts')->add(PermissionMiddleware::class)->setName('ordersbyproduct2-orders_by_product2-summary-OrderByProducts'); // OrderByProducts
    $app->map(["GET", "POST", "OPTIONS"], '/ordersbyproduct2', OrdersByProduct2Controller::class . ':summary')->add(PermissionMiddleware::class)->setName('ordersbyproduct2-orders_by_product2-summary'); // summary

    // Gantt
    $app->map(["GET", "POST", "OPTIONS"], '/gantt', GanttController::class . ':summary')->add(PermissionMiddleware::class)->setName('gantt-Gantt-summary'); // summary

    // locations
    $app->map(["GET","POST","OPTIONS"], '/locationslist[/{ID}]', LocationsController::class . ':list')->add(PermissionMiddleware::class)->setName('locationslist-locations-list'); // list
    $app->group(
        '/locations',
        function (RouteCollectorProxy $group) {
            $group->map(["GET","POST","OPTIONS"], '/' . Config('LIST_ACTION') . '[/{ID}]', LocationsController::class . ':list')->add(PermissionMiddleware::class)->setName('locations/list-locations-list-2'); // list
        }
    );

    // locations2
    $app->map(["GET","POST","OPTIONS"], '/locations2list', Locations2Controller::class . ':list')->add(PermissionMiddleware::class)->setName('locations2list-locations2-list'); // list
    $app->group(
        '/locations2',
        function (RouteCollectorProxy $group) {
            $group->map(["GET","POST","OPTIONS"], '/' . Config('LIST_ACTION') . '', Locations2Controller::class . ':list')->add(PermissionMiddleware::class)->setName('locations2/list-locations2-list-2'); // list
        }
    );

    // locations3
    $app->map(["GET","POST","OPTIONS"], '/locations3list', Locations3Controller::class . ':list')->add(PermissionMiddleware::class)->setName('locations3list-locations3-list'); // list
    $app->group(
        '/locations3',
        function (RouteCollectorProxy $group) {
            $group->map(["GET","POST","OPTIONS"], '/' . Config('LIST_ACTION') . '', Locations3Controller::class . ':list')->add(PermissionMiddleware::class)->setName('locations3/list-locations3-list-2'); // list
        }
    );

    // calendar
    $app->map(["GET","POST","OPTIONS"], '/calendarlist[/{Id}]', CalendarController::class . ':list')->add(PermissionMiddleware::class)->setName('calendarlist-calendar-list'); // list
    $app->map(["GET","POST","OPTIONS"], '/calendaradd[/{Id}]', CalendarController::class . ':add')->add(PermissionMiddleware::class)->setName('calendaradd-calendar-add'); // add
    $app->map(["GET","POST","OPTIONS"], '/calendarview[/{Id}]', CalendarController::class . ':view')->add(PermissionMiddleware::class)->setName('calendarview-calendar-view'); // view
    $app->map(["GET","POST","OPTIONS"], '/calendaredit[/{Id}]', CalendarController::class . ':edit')->add(PermissionMiddleware::class)->setName('calendaredit-calendar-edit'); // edit
    $app->map(["GET","POST","OPTIONS"], '/calendardelete[/{Id}]', CalendarController::class . ':delete')->add(PermissionMiddleware::class)->setName('calendardelete-calendar-delete'); // delete
    $app->group(
        '/calendar',
        function (RouteCollectorProxy $group) {
            $group->map(["GET","POST","OPTIONS"], '/' . Config('LIST_ACTION') . '[/{Id}]', CalendarController::class . ':list')->add(PermissionMiddleware::class)->setName('calendar/list-calendar-list-2'); // list
            $group->map(["GET","POST","OPTIONS"], '/' . Config('ADD_ACTION') . '[/{Id}]', CalendarController::class . ':add')->add(PermissionMiddleware::class)->setName('calendar/add-calendar-add-2'); // add
            $group->map(["GET","POST","OPTIONS"], '/' . Config('VIEW_ACTION') . '[/{Id}]', CalendarController::class . ':view')->add(PermissionMiddleware::class)->setName('calendar/view-calendar-view-2'); // view
            $group->map(["GET","POST","OPTIONS"], '/' . Config('EDIT_ACTION') . '[/{Id}]', CalendarController::class . ':edit')->add(PermissionMiddleware::class)->setName('calendar/edit-calendar-edit-2'); // edit
            $group->map(["GET","POST","OPTIONS"], '/' . Config('DELETE_ACTION') . '[/{Id}]', CalendarController::class . ':delete')->add(PermissionMiddleware::class)->setName('calendar/delete-calendar-delete-2'); // delete
        }
    );

    // Calendar1
    $app->map(["GET", "POST", "OPTIONS"], '/calendar1', Calendar1Controller::class . ':calendar')->add(PermissionMiddleware::class)->setName('calendar1-Calendar1-calendar'); // calendar
    $app->map(["GET","POST","OPTIONS"], '/calendar1add[/{Id}]', Calendar1Controller::class . ':add')->add(PermissionMiddleware::class)->setName('calendar1add-Calendar1-add'); // add
    $app->map(["GET","OPTIONS"], '/calendar1view[/{Id}]', Calendar1Controller::class . ':view')->add(PermissionMiddleware::class)->setName('calendar1view-Calendar1-view'); // view
    $app->map(["GET","POST","OPTIONS"], '/calendar1edit[/{Id}]', Calendar1Controller::class . ':edit')->add(PermissionMiddleware::class)->setName('calendar1edit-Calendar1-edit'); // edit
    $app->map(["GET","POST","OPTIONS"], '/calendar1delete[/{Id}]', Calendar1Controller::class . ':delete')->add(PermissionMiddleware::class)->setName('calendar1delete-Calendar1-delete'); // delete

    // privacy
    $app->map(["GET","POST","OPTIONS"], '/privacy', OthersController::class . ':privacy')->add(PermissionMiddleware::class)->setName('privacy');

    // personal_data
    $app->map(["GET","POST","OPTIONS"], '/personaldata', OthersController::class . ':personaldata')->add(PermissionMiddleware::class)->setName('personaldata');

    // login
    $app->map(["GET","POST","OPTIONS"], '/login[/{provider}]', OthersController::class . ':login')->add(PermissionMiddleware::class)->setName('login');

    // reset_password
    $app->map(["GET","POST","OPTIONS"], '/resetpassword', OthersController::class . ':resetpassword')->add(PermissionMiddleware::class)->setName('resetpassword');

    // change_password
    $app->map(["GET","POST","OPTIONS"], '/changepassword', OthersController::class . ':changepassword')->add(PermissionMiddleware::class)->setName('changepassword');

    // register
    $app->map(["GET","POST","OPTIONS"], '/register', OthersController::class . ':register')->add(PermissionMiddleware::class)->setName('register');

    // userpriv
    $app->map(["GET","POST","OPTIONS"], '/userpriv', OthersController::class . ':userpriv')->add(PermissionMiddleware::class)->setName('userpriv');

    // logout
    $app->map(["GET","POST","OPTIONS"], '/logout', OthersController::class . ':logout')->add(PermissionMiddleware::class)->setName('logout');

    // barcode
    $app->map(["GET","OPTIONS"], '/barcode', OthersController::class . ':barcode')->add(PermissionMiddleware::class)->setName('barcode');

    // Swagger
    $app->get('/' . Config("SWAGGER_ACTION"), OthersController::class . ':swagger')->setName(Config("SWAGGER_ACTION")); // Swagger

    // Index
    $app->get('/[index]', OthersController::class . ':index')->add(PermissionMiddleware::class)->setName('index');

    // Route Action event
    if (function_exists(PROJECT_NAMESPACE . "Route_Action")) {
        if (Route_Action($app) === false) {
            return;
        }
    }

    /**
     * Catch-all route to serve a 404 Not Found page if none of the routes match
     * NOTE: Make sure this route is defined last.
     */
    $app->map(
        ['GET', 'POST', 'PUT', 'DELETE', 'PATCH'],
        '/{routes:.+}',
        function ($request, $response, $params) {
            throw new HttpNotFoundException($request, str_replace("%p", $params["routes"], Container("language")->phrase("PageNotFound")));
        }
    );
};
