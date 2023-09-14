<?php

namespace PHPMaker2023\demo2023;

// Menu Language
if ($Language && function_exists(PROJECT_NAMESPACE . "Config") && $Language->LanguageFolder == Config("LANGUAGE_FOLDER")) {
    $MenuRelativePath = "";
    $MenuLanguage = &$Language;
} else { // Compat reports
    $LANGUAGE_FOLDER = "../lang/";
    $MenuRelativePath = "../";
    $MenuLanguage = Container("language");
}

// Navbar menu
$topMenu = new Menu("navbar", true, true);
$topMenu->addMenuItem(10087, "mi_home", $MenuLanguage->MenuPhrase("10087", "MenuText"), $MenuRelativePath . "home", -1, "", AllowListMenu('{DFB61542-7FFC-43AB-88E7-31D7F8D95066}home.php'), false, false, "", "", true, false);
$topMenu->addMenuItem(10088, "mi_news", $MenuLanguage->MenuPhrase("10088", "MenuText"), $MenuRelativePath . "news", -1, "", AllowListMenu('{DFB61542-7FFC-43AB-88E7-31D7F8D95066}news.php'), false, false, "", "", true, false);
echo $topMenu->toScript();

// Sidebar menu
$sideMenu = new Menu("menu", true, false);
$sideMenu->addMenuItem(10148, "mi_locations", $MenuLanguage->MenuPhrase("10148", "MenuText"), $MenuRelativePath . "locationslist", -1, "", AllowListMenu('{DFB61542-7FFC-43AB-88E7-31D7F8D95066}locations'), false, false, "", "", false, true);
$sideMenu->addMenuItem(10149, "mi_locations2", $MenuLanguage->MenuPhrase("10149", "MenuText"), $MenuRelativePath . "locations2list", -1, "", AllowListMenu('{DFB61542-7FFC-43AB-88E7-31D7F8D95066}locations2'), false, false, "", "", false, true);
$sideMenu->addMenuItem(10150, "mi_locations3", $MenuLanguage->MenuPhrase("10150", "MenuText"), $MenuRelativePath . "locations3list", -1, "", AllowListMenu('{DFB61542-7FFC-43AB-88E7-31D7F8D95066}locations3'), false, false, "", "", false, true);
$sideMenu->addMenuItem(10087, "mi_home", $MenuLanguage->MenuPhrase("10087", "MenuText"), $MenuRelativePath . "home", -1, "", AllowListMenu('{DFB61542-7FFC-43AB-88E7-31D7F8D95066}home.php'), false, false, "", "", true, true);
$sideMenu->addMenuItem(10088, "mi_news", $MenuLanguage->MenuPhrase("10088", "MenuText"), $MenuRelativePath . "news", -1, "", AllowListMenu('{DFB61542-7FFC-43AB-88E7-31D7F8D95066}news.php'), false, false, "", "", true, true);
$sideMenu->addMenuItem(16, "mci_CARS_RELATED", $MenuLanguage->MenuPhrase("16", "MenuText"), "", -1, "", IsLoggedIn(), false, true, "fa-car", "", false, true);
$sideMenu->addMenuItem(1, "mi_cars", $MenuLanguage->MenuPhrase("1", "MenuText"), $MenuRelativePath . "carslist", 16, "", AllowListMenu('{DFB61542-7FFC-43AB-88E7-31D7F8D95066}cars'), false, false, "", "", false, true);
$sideMenu->addMenuItem(10086, "mi_cars2", $MenuLanguage->MenuPhrase("10086", "MenuText"), $MenuRelativePath . "cars2list", 16, "", AllowListMenu('{DFB61542-7FFC-43AB-88E7-31D7F8D95066}cars2'), false, false, "", "", false, true);
$sideMenu->addMenuItem(11, "mi_trademarks", $MenuLanguage->MenuPhrase("11", "MenuText"), $MenuRelativePath . "trademarkslist", 16, "", AllowListMenu('{DFB61542-7FFC-43AB-88E7-31D7F8D95066}trademarks'), false, false, "", "", false, true);
$sideMenu->addMenuItem(10, "mi_models", $MenuLanguage->MenuPhrase("10", "MenuText"), $MenuRelativePath . "modelslist", 16, "", AllowListMenu('{DFB61542-7FFC-43AB-88E7-31D7F8D95066}models'), false, false, "", "", false, true);
$sideMenu->addMenuItem(10109, "mci_OTHER_TABLES", $MenuLanguage->MenuPhrase("10109", "MenuText"), "", -1, "", true, false, true, "fa-table", "", false, true);
$sideMenu->addMenuItem(2, "mi_categories", $MenuLanguage->MenuPhrase("2", "MenuText"), $MenuRelativePath . "categorieslist", 10109, "", AllowListMenu('{DFB61542-7FFC-43AB-88E7-31D7F8D95066}categories'), false, false, "", "", false, true);
$sideMenu->addMenuItem(3, "mi_customers", $MenuLanguage->MenuPhrase("3", "MenuText"), $MenuRelativePath . "customerslist", 10109, "", AllowListMenu('{DFB61542-7FFC-43AB-88E7-31D7F8D95066}customers'), false, false, "", "", false, true);
$sideMenu->addMenuItem(5, "mi_orderdetails", $MenuLanguage->MenuPhrase("5", "MenuText"), $MenuRelativePath . "orderdetailslist?cmd=resetall", 10109, "", AllowListMenu('{DFB61542-7FFC-43AB-88E7-31D7F8D95066}orderdetails'), false, false, "", "", false, true);
$sideMenu->addMenuItem(6, "mi_orders", $MenuLanguage->MenuPhrase("6", "MenuText"), $MenuRelativePath . "orderslist?cmd=resetall", 10109, "", AllowListMenu('{DFB61542-7FFC-43AB-88E7-31D7F8D95066}orders'), false, false, "", "", false, true);
$sideMenu->addMenuItem(10085, "mi_orders2", $MenuLanguage->MenuPhrase("10085", "MenuText"), $MenuRelativePath . "orders2list", 10109, "", AllowListMenu('{DFB61542-7FFC-43AB-88E7-31D7F8D95066}orders2'), false, false, "", "", false, true);
$sideMenu->addMenuItem(7, "mi_products", $MenuLanguage->MenuPhrase("7", "MenuText"), $MenuRelativePath . "productslist?cmd=resetall", 10109, "", AllowListMenu('{DFB61542-7FFC-43AB-88E7-31D7F8D95066}products'), false, false, "", "", false, true);
$sideMenu->addMenuItem(8, "mi_shippers", $MenuLanguage->MenuPhrase("8", "MenuText"), $MenuRelativePath . "shipperslist", 10109, "", AllowListMenu('{DFB61542-7FFC-43AB-88E7-31D7F8D95066}shippers'), false, false, "", "", false, true);
$sideMenu->addMenuItem(9, "mi_suppliers", $MenuLanguage->MenuPhrase("9", "MenuText"), $MenuRelativePath . "supplierslist", 10109, "", AllowListMenu('{DFB61542-7FFC-43AB-88E7-31D7F8D95066}suppliers'), false, false, "", "", false, true);
$sideMenu->addMenuItem(12, "mi_order_details_extended", $MenuLanguage->MenuPhrase("12", "MenuText"), $MenuRelativePath . "orderdetailsextendedlist?cmd=resetall", 10109, "", AllowListMenu('{DFB61542-7FFC-43AB-88E7-31D7F8D95066}order details extended'), false, false, "", "", false, true);
$sideMenu->addMenuItem(10089, "mi_dji", $MenuLanguage->MenuPhrase("10089", "MenuText"), $MenuRelativePath . "djilist", 10109, "", AllowListMenu('{DFB61542-7FFC-43AB-88E7-31D7F8D95066}dji'), false, false, "", "", false, true);
$sideMenu->addMenuItem(17, "mci_ADMIN_ONLY", $MenuLanguage->MenuPhrase("17", "MenuText"), "", -1, "", IsLoggedIn(), false, true, "fa-key", "", false, true);
$sideMenu->addMenuItem(4, "mi_employees", $MenuLanguage->MenuPhrase("4", "MenuText"), $MenuRelativePath . "employeeslist", 17, "", AllowListMenu('{DFB61542-7FFC-43AB-88E7-31D7F8D95066}employees'), false, false, "", "", false, true);
$sideMenu->addMenuItem(10084, "mi_userlevels", $MenuLanguage->MenuPhrase("10084", "MenuText"), $MenuRelativePath . "userlevelslist", 17, "", AllowListMenu('{DFB61542-7FFC-43AB-88E7-31D7F8D95066}userlevels'), false, false, "", "", false, true);
$sideMenu->addMenuItem(10083, "mi_userlevelpermissions", $MenuLanguage->MenuPhrase("10083", "MenuText"), $MenuRelativePath . "userlevelpermissionslist", 17, "", AllowListMenu('{DFB61542-7FFC-43AB-88E7-31D7F8D95066}userlevelpermissions'), false, false, "", "", false, true);
$sideMenu->addMenuItem(10127, "mi_Dashboard1", $MenuLanguage->MenuPhrase("10127", "MenuText"), $MenuRelativePath . "dashboard1", -1, "", AllowListMenu('{DFB61542-7FFC-43AB-88E7-31D7F8D95066}Dashboard1'), false, false, "", "", false, true);
$sideMenu->addMenuItem(10152, "mi_Calendar1", $MenuLanguage->MenuPhrase("10152", "MenuText"), $MenuRelativePath . "calendar1", -1, "", AllowListMenu('{DFB61542-7FFC-43AB-88E7-31D7F8D95066}Calendar1'), false, false, "", "", false, true);
$sideMenu->addMenuItem(10144, "mci_REPORTS", $MenuLanguage->MenuPhrase("10144", "MenuText"), "", -1, "", true, false, true, "fa-file-alt", "", false, true);
$sideMenu->addMenuItem(10118, "mi_Quarterly_Orders_By_Product", $MenuLanguage->MenuPhrase("10118", "MenuText"), $MenuRelativePath . "quarterlyordersbyproduct", 10144, "", AllowListMenu('{DFB61542-7FFC-43AB-88E7-31D7F8D95066}Quarterly Orders By Product'), false, false, "", "", false, true);
$sideMenu->addMenuItem(10119, "mi_Sales_By_Customer", $MenuLanguage->MenuPhrase("10119", "MenuText"), $MenuRelativePath . "salesbycustomer", 10144, "", AllowListMenu('{DFB61542-7FFC-43AB-88E7-31D7F8D95066}Sales By Customer'), false, false, "", "", false, true);
$sideMenu->addMenuItem(10120, "mi_Sales_By_Customer_Compact", $MenuLanguage->MenuPhrase("10120", "MenuText"), $MenuRelativePath . "salesbycustomercompact", 10144, "", AllowListMenu('{DFB61542-7FFC-43AB-88E7-31D7F8D95066}Sales By Customer (Compact)'), false, false, "", "", false, true);
$sideMenu->addMenuItem(10121, "mi_Alphabetical_List_of_Products", $MenuLanguage->MenuPhrase("10121", "MenuText"), $MenuRelativePath . "alphabeticallistofproducts", 10144, "", AllowListMenu('{DFB61542-7FFC-43AB-88E7-31D7F8D95066}Alphabetical List of Products'), false, false, "", "", false, true);
$sideMenu->addMenuItem(10122, "mi_Products_By_Category", $MenuLanguage->MenuPhrase("10122", "MenuText"), $MenuRelativePath . "productsbycategory", 10144, "", AllowListMenu('{DFB61542-7FFC-43AB-88E7-31D7F8D95066}Products By Category'), false, false, "", "", false, true);
$sideMenu->addMenuItem(10123, "mi_Sales_by_Category_for_2014", $MenuLanguage->MenuPhrase("10123", "MenuText"), $MenuRelativePath . "salesbycategoryfor2014", 10144, "", AllowListMenu('{DFB61542-7FFC-43AB-88E7-31D7F8D95066}Sales by Category for 2014'), false, false, "", "", false, true);
$sideMenu->addMenuItem(10124, "mi_Sales_By_Year", $MenuLanguage->MenuPhrase("10124", "MenuText"), $MenuRelativePath . "salesbyyear", 10144, "", AllowListMenu('{DFB61542-7FFC-43AB-88E7-31D7F8D95066}Sales By Year'), false, false, "", "", false, true);
$sideMenu->addMenuItem(10126, "mi_Sales_By_Customer_2", $MenuLanguage->MenuPhrase("10126", "MenuText"), $MenuRelativePath . "salesbycustomer2", 10144, "", AllowListMenu('{DFB61542-7FFC-43AB-88E7-31D7F8D95066}Sales By Customer 2'), false, false, "", "", false, true);
$sideMenu->addMenuItem(10146, "mi_Gantt", $MenuLanguage->MenuPhrase("10146", "MenuText"), $MenuRelativePath . "gantt", 10144, "", AllowListMenu('{DFB61542-7FFC-43AB-88E7-31D7F8D95066}Gantt'), false, false, "", "", false, true);
echo $sideMenu->toScript();
