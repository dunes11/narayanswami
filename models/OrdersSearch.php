<?php

namespace PHPMaker2023\demo2023;

use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Page class
 */
class OrdersSearch extends Orders
{
    use MessagesTrait;

    // Page ID
    public $PageID = "search";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Page object name
    public $PageObjName = "OrdersSearch";

    // View file path
    public $View = null;

    // Title
    public $Title = null; // Title for <title> tag

    // Rendering View
    public $RenderingView = false;

    // CSS class/style
    public $CurrentPageName = "orderssearch";

    // Page headings
    public $Heading = "";
    public $Subheading = "";
    public $PageHeader;
    public $PageFooter;

    // Page layout
    public $UseLayout = true;

    // Page terminated
    private $terminated = false;

    // Page heading
    public function pageHeading()
    {
        global $Language;
        if ($this->Heading != "") {
            return $this->Heading;
        }
        if (method_exists($this, "tableCaption")) {
            return $this->tableCaption();
        }
        return "";
    }

    // Page subheading
    public function pageSubheading()
    {
        global $Language;
        if ($this->Subheading != "") {
            return $this->Subheading;
        }
        if ($this->TableName) {
            return $Language->phrase($this->PageID);
        }
        return "";
    }

    // Page name
    public function pageName()
    {
        return CurrentPageName();
    }

    // Page URL
    public function pageUrl($withArgs = true)
    {
        $route = GetRoute();
        $args = RemoveXss($route->getArguments());
        if (!$withArgs) {
            foreach ($args as $key => &$val) {
                $val = "";
            }
            unset($val);
        }
        return rtrim(UrlFor($route->getName(), $args), "/") . "?";
    }

    // Show Page Header
    public function showPageHeader()
    {
        $header = $this->PageHeader;
        $this->pageDataRendering($header);
        if ($header != "") { // Header exists, display
            echo '<p id="ew-page-header">' . $header . '</p>';
        }
    }

    // Show Page Footer
    public function showPageFooter()
    {
        $footer = $this->PageFooter;
        $this->pageDataRendered($footer);
        if ($footer != "") { // Footer exists, display
            echo '<p id="ew-page-footer">' . $footer . '</p>';
        }
    }

    // Set field visibility
    public function setVisibility()
    {
        $this->OrderID->setVisibility();
        $this->CustomerID->setVisibility();
        $this->EmployeeID->setVisibility();
        $this->OrderDate->setVisibility();
        $this->RequiredDate->setVisibility();
        $this->ShippedDate->setVisibility();
        $this->ShipVia->setVisibility();
        $this->Freight->setVisibility();
        $this->ShipName->setVisibility();
        $this->ShipAddress->setVisibility();
        $this->ShipCity->setVisibility();
        $this->ShipRegion->setVisibility();
        $this->ShipPostalCode->setVisibility();
        $this->ShipCountry->setVisibility();
    }

    // Constructor
    public function __construct()
    {
        parent::__construct();
        global $Language, $DashboardReport, $DebugTimer, $UserTable;
        $this->TableVar = 'orders';
        $this->TableName = 'orders';

        // Table CSS class
        $this->TableClass = "table table-striped table-bordered table-hover table-sm ew-desktop-table ew-search-table";

        // Initialize
        $GLOBALS["Page"] = &$this;

        // Language object
        $Language = Container("language");

        // Table object (orders)
        if (!isset($GLOBALS["orders"]) || get_class($GLOBALS["orders"]) == PROJECT_NAMESPACE . "orders") {
            $GLOBALS["orders"] = &$this;
        }

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'orders');
        }

        // Start timer
        $DebugTimer = Container("timer");

        // Debug message
        LoadDebugMessage();

        // Open connection
        $GLOBALS["Conn"] ??= $this->getConnection();

        // User table object
        $UserTable = Container("usertable");
    }

    // Get content from stream
    public function getContents(): string
    {
        global $Response;
        return is_object($Response) ? $Response->getBody() : ob_get_clean();
    }

    // Is lookup
    public function isLookup()
    {
        return SameText(Route(0), Config("API_LOOKUP_ACTION"));
    }

    // Is AutoFill
    public function isAutoFill()
    {
        return $this->isLookup() && SameText(Post("ajax"), "autofill");
    }

    // Is AutoSuggest
    public function isAutoSuggest()
    {
        return $this->isLookup() && SameText(Post("ajax"), "autosuggest");
    }

    // Is modal lookup
    public function isModalLookup()
    {
        return $this->isLookup() && SameText(Post("ajax"), "modal");
    }

    // Is terminated
    public function isTerminated()
    {
        return $this->terminated;
    }

    /**
     * Terminate page
     *
     * @param string $url URL for direction
     * @return void
     */
    public function terminate($url = "")
    {
        if ($this->terminated) {
            return;
        }
        global $TempImages, $DashboardReport, $Response;

        // Page is terminated
        $this->terminated = true;

        // Page Unload event
        if (method_exists($this, "pageUnload")) {
            $this->pageUnload();
        }

        // Global Page Unloaded event (in userfn*.php)
        Page_Unloaded();
        if (!IsApi() && method_exists($this, "pageRedirecting")) {
            $this->pageRedirecting($url);
        }

        // Close connection
        CloseConnections();

        // Return for API
        if (IsApi()) {
            $res = $url === true;
            if (!$res) { // Show response for API
                $ar = array_merge($this->getMessages(), $url ? ["url" => GetUrl($url)] : []);
                WriteJson($ar);
            }
            $this->clearMessages(); // Clear messages for API request
            return;
        } else { // Check if response is JSON
            if (StartsString("application/json", $Response->getHeaderLine("Content-type")) && $Response->getBody()->getSize()) { // With JSON response
                $this->clearMessages();
                return;
            }
        }

        // Go to URL if specified
        if ($url != "") {
            if (!Config("DEBUG") && ob_get_length()) {
                ob_end_clean();
            }

            // Handle modal response (Assume return to modal for simplicity)
            if ($this->IsModal) { // Show as modal
                $result = ["url" => GetUrl($url), "modal" => "1"];
                $pageName = GetPageName($url);
                if ($pageName != $this->getListUrl()) { // Not List page => View page
                    $result["caption"] = $this->getModalCaption($pageName);
                    $result["view"] = $pageName == "ordersview"; // If View page, no primary button
                } else { // List page
                    // $result["list"] = $this->PageID == "search"; // Refresh List page if current page is Search page
                    $result["error"] = $this->getFailureMessage(); // List page should not be shown as modal => error
                    $this->clearFailureMessage();
                }
                WriteJson($result);
            } else {
                SaveDebugMessage();
                Redirect(GetUrl($url));
            }
        }
        return; // Return to controller
    }

    // Get records from recordset
    protected function getRecordsFromRecordset($rs, $current = false)
    {
        $rows = [];
        if (is_object($rs)) { // Recordset
            while ($rs && !$rs->EOF) {
                $this->loadRowValues($rs); // Set up DbValue/CurrentValue
                $row = $this->getRecordFromArray($rs->fields);
                if ($current) {
                    return $row;
                } else {
                    $rows[] = $row;
                }
                $rs->moveNext();
            }
        } elseif (is_array($rs)) {
            foreach ($rs as $ar) {
                $row = $this->getRecordFromArray($ar);
                if ($current) {
                    return $row;
                } else {
                    $rows[] = $row;
                }
            }
        }
        return $rows;
    }

    // Get record from array
    protected function getRecordFromArray($ar)
    {
        $row = [];
        if (is_array($ar)) {
            foreach ($ar as $fldname => $val) {
                if (array_key_exists($fldname, $this->Fields) && ($this->Fields[$fldname]->Visible || $this->Fields[$fldname]->IsPrimaryKey)) { // Primary key or Visible
                    $fld = &$this->Fields[$fldname];
                    if ($fld->HtmlTag == "FILE") { // Upload field
                        if (EmptyValue($val)) {
                            $row[$fldname] = null;
                        } else {
                            if ($fld->DataType == DATATYPE_BLOB) {
                                $url = FullUrl(GetApiUrl(Config("API_FILE_ACTION") .
                                    "/" . $fld->TableVar . "/" . $fld->Param . "/" . rawurlencode($this->getRecordKeyValue($ar))));
                                $row[$fldname] = ["type" => ContentType($val), "url" => $url, "name" => $fld->Param . ContentExtension($val)];
                            } elseif (!$fld->UploadMultiple || !ContainsString($val, Config("MULTIPLE_UPLOAD_SEPARATOR"))) { // Single file
                                $url = FullUrl(GetApiUrl(Config("API_FILE_ACTION") .
                                    "/" . $fld->TableVar . "/" . Encrypt($fld->physicalUploadPath() . $val)));
                                $row[$fldname] = ["type" => MimeContentType($val), "url" => $url, "name" => $val];
                            } else { // Multiple files
                                $files = explode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $val);
                                $ar = [];
                                foreach ($files as $file) {
                                    $url = FullUrl(GetApiUrl(Config("API_FILE_ACTION") .
                                        "/" . $fld->TableVar . "/" . Encrypt($fld->physicalUploadPath() . $file)));
                                    if (!EmptyValue($file)) {
                                        $ar[] = ["type" => MimeContentType($file), "url" => $url, "name" => $file];
                                    }
                                }
                                $row[$fldname] = $ar;
                            }
                        }
                    } else {
                        $row[$fldname] = $val;
                    }
                }
            }
        }
        return $row;
    }

    // Get record key value from array
    protected function getRecordKeyValue($ar)
    {
        $key = "";
        if (is_array($ar)) {
            $key .= @$ar['OrderID'];
        }
        return $key;
    }

    /**
     * Hide fields for add/edit
     *
     * @return void
     */
    protected function hideFieldsForAddEdit()
    {
        if ($this->isAdd() || $this->isCopy() || $this->isGridAdd()) {
            $this->OrderID->Visible = false;
        }
    }

    // Lookup data
    public function lookup($ar = null)
    {
        global $Language, $Security;

        // Get lookup object
        $fieldName = $ar["field"] ?? Post("field");
        $lookup = $this->Fields[$fieldName]->Lookup;
        $name = $ar["name"] ?? Post("name");
        $isQuery = ContainsString($name, "query_builder_rule");
        if ($isQuery) {
            $lookup->FilterFields = []; // Skip parent fields if any
        }

        // Get lookup parameters
        $lookupType = $ar["ajax"] ?? Post("ajax", "unknown");
        $pageSize = -1;
        $offset = -1;
        $searchValue = "";
        if (SameText($lookupType, "modal") || SameText($lookupType, "filter")) {
            $searchValue = $ar["q"] ?? Param("q") ?? $ar["sv"] ?? Post("sv", "");
            $pageSize = $ar["n"] ?? Param("n") ?? $ar["recperpage"] ?? Post("recperpage", 10);
        } elseif (SameText($lookupType, "autosuggest")) {
            $searchValue = $ar["q"] ?? Param("q", "");
            $pageSize = $ar["n"] ?? Param("n", -1);
            $pageSize = is_numeric($pageSize) ? (int)$pageSize : -1;
            if ($pageSize <= 0) {
                $pageSize = Config("AUTO_SUGGEST_MAX_ENTRIES");
            }
        }
        $start = $ar["start"] ?? Param("start", -1);
        $start = is_numeric($start) ? (int)$start : -1;
        $page = $ar["page"] ?? Param("page", -1);
        $page = is_numeric($page) ? (int)$page : -1;
        $offset = $start >= 0 ? $start : ($page > 0 && $pageSize > 0 ? ($page - 1) * $pageSize : 0);
        $userSelect = Decrypt($ar["s"] ?? Post("s", ""));
        $userFilter = Decrypt($ar["f"] ?? Post("f", ""));
        $userOrderBy = Decrypt($ar["o"] ?? Post("o", ""));
        $keys = $ar["keys"] ?? Post("keys");
        $lookup->LookupType = $lookupType; // Lookup type
        $lookup->FilterValues = []; // Clear filter values first
        if ($keys !== null) { // Selected records from modal
            if (is_array($keys)) {
                $keys = implode(Config("MULTIPLE_OPTION_SEPARATOR"), $keys);
            }
            $lookup->FilterFields = []; // Skip parent fields if any
            $lookup->FilterValues[] = $keys; // Lookup values
            $pageSize = -1; // Show all records
        } else { // Lookup values
            $lookup->FilterValues[] = $ar["v0"] ?? $ar["lookupValue"] ?? Post("v0", Post("lookupValue", ""));
        }
        $cnt = is_array($lookup->FilterFields) ? count($lookup->FilterFields) : 0;
        for ($i = 1; $i <= $cnt; $i++) {
            $lookup->FilterValues[] = $ar["v" . $i] ?? Post("v" . $i, "");
        }
        $lookup->SearchValue = $searchValue;
        $lookup->PageSize = $pageSize;
        $lookup->Offset = $offset;
        if ($userSelect != "") {
            $lookup->UserSelect = $userSelect;
        }
        if ($userFilter != "") {
            $lookup->UserFilter = $userFilter;
        }
        if ($userOrderBy != "") {
            $lookup->UserOrderBy = $userOrderBy;
        }
        return $lookup->toJson($this, !is_array($ar)); // Use settings from current page
    }
    public $FormClassName = "ew-form ew-search-form";
    public $IsModal = false;
    public $IsMobileOrModal = false;

    /**
     * Page run
     *
     * @return void
     */
    public function run()
    {
        global $ExportType, $UserProfile, $Language, $Security, $CurrentForm, $SkipHeaderFooter;

        // Is modal
        $this->IsModal = ConvertToBool(Param("modal"));
        $this->UseLayout = $this->UseLayout && !$this->IsModal;

        // Use layout
        $this->UseLayout = $this->UseLayout && ConvertToBool(Param(Config("PAGE_LAYOUT"), true));

        // View
        $this->View = Get(Config("VIEW"));

        // Create form object
        $CurrentForm = new HttpForm();
        $this->CurrentAction = Param("action"); // Set up current action
        $this->setVisibility();

        // Set lookup cache
        if (!in_array($this->PageID, Config("LOOKUP_CACHE_PAGE_IDS"))) {
            $this->setUseLookupCache(false);
        }

        // Global Page Loading event (in userfn*.php)
        Page_Loading();

        // Page Load event
        if (method_exists($this, "pageLoad")) {
            $this->pageLoad();
        }

        // Hide fields for add/edit
        if (!$this->UseAjaxActions) {
            $this->hideFieldsForAddEdit();
        }
        // Use inline delete
        if ($this->UseAjaxActions) {
            $this->InlineDelete = true;
        }

        // Set up lookup cache
        $this->setupLookupOptions($this->CustomerID);
        $this->setupLookupOptions($this->EmployeeID);

        // Set up Breadcrumb
        $this->setupBreadcrumb();

        // Check modal
        if ($this->IsModal) {
            $SkipHeaderFooter = true;
        }
        $this->IsMobileOrModal = IsMobile() || $this->IsModal;

        // Get action
        $this->CurrentAction = Post("action");
        if ($this->isSearch()) {
            // Build search string for advanced search, remove blank field
            $this->loadSearchValues(); // Get search values
            $srchStr = $this->validateSearch() ? $this->buildAdvancedSearch() : "";
            if ($srchStr != "") {
                $srchStr = "orderslist" . "?" . $srchStr;
                // Do not return Json for UseAjaxActions
                if ($this->IsModal && $this->UseAjaxActions) {
                    $this->IsModal = false;
                }
                $this->terminate($srchStr); // Go to list page
                return;
            }
        }

        // Restore search settings from Session
        if (!$this->hasInvalidFields()) {
            $this->loadAdvancedSearch();
        }

        // Render row for search
        $this->RowType = ROWTYPE_SEARCH;
        $this->resetAttributes();
        $this->renderRow();

        // Set LoginStatus / Page_Rendering / Page_Render
        if (!IsApi() && !$this->isTerminated()) {
            // Setup login status
            SetupLoginStatus();

            // Pass login status to client side
            SetClientVar("login", LoginStatus());

            // Global Page Rendering event (in userfn*.php)
            Page_Rendering();

            // Page Render event
            if (method_exists($this, "pageRender")) {
                $this->pageRender();
            }

            // Render search option
            if (method_exists($this, "renderSearchOptions")) {
                $this->renderSearchOptions();
            }
        }
    }

    // Build advanced search
    protected function buildAdvancedSearch()
    {
        $srchUrl = "";
        $this->buildSearchUrl($srchUrl, $this->OrderID); // OrderID
        $this->buildSearchUrl($srchUrl, $this->CustomerID); // CustomerID
        $this->buildSearchUrl($srchUrl, $this->EmployeeID); // EmployeeID
        $this->buildSearchUrl($srchUrl, $this->OrderDate); // OrderDate
        $this->buildSearchUrl($srchUrl, $this->RequiredDate); // RequiredDate
        $this->buildSearchUrl($srchUrl, $this->ShippedDate); // ShippedDate
        $this->buildSearchUrl($srchUrl, $this->ShipVia); // ShipVia
        $this->buildSearchUrl($srchUrl, $this->Freight); // Freight
        $this->buildSearchUrl($srchUrl, $this->ShipName); // ShipName
        $this->buildSearchUrl($srchUrl, $this->ShipAddress); // ShipAddress
        $this->buildSearchUrl($srchUrl, $this->ShipCity); // ShipCity
        $this->buildSearchUrl($srchUrl, $this->ShipRegion); // ShipRegion
        $this->buildSearchUrl($srchUrl, $this->ShipPostalCode); // ShipPostalCode
        $this->buildSearchUrl($srchUrl, $this->ShipCountry); // ShipCountry
        if ($srchUrl != "") {
            $srchUrl .= "&";
        }
        $srchUrl .= "cmd=search";
        return $srchUrl;
    }

    // Build search URL
    protected function buildSearchUrl(&$url, $fld, $oprOnly = false)
    {
        global $CurrentForm;
        $wrk = "";
        $fldParm = $fld->Param;
        [
            "value" => $fldVal,
            "operator" => $fldOpr,
            "condition" => $fldCond,
            "value2" => $fldVal2,
            "operator2" => $fldOpr2
        ] = $CurrentForm->getSearchValues($fldParm);
        if (is_array($fldVal)) {
            $fldVal = implode(Config("MULTIPLE_OPTION_SEPARATOR"), $fldVal);
        }
        if (is_array($fldVal2)) {
            $fldVal2 = implode(Config("MULTIPLE_OPTION_SEPARATOR"), $fldVal2);
        }
        $fldDataType = $fld->DataType;
        $value = ConvertSearchValue($fldVal, $fldOpr, $fld); // For testing if numeric only
        $value2 = ConvertSearchValue($fldVal2, $fldOpr2, $fld); // For testing if numeric only
        $fldOpr = ConvertSearchOperator($fldOpr, $fld, $value);
        $fldOpr2 = ConvertSearchOperator($fldOpr2, $fld, $value2);
        if (in_array($fldOpr, ["BETWEEN", "NOT BETWEEN"])) {
            $isValidValue = $fldDataType != DATATYPE_NUMBER || $fld->VirtualSearch || IsNumericSearchValue($value, $fldOpr, $fld) && IsNumericSearchValue($value2, $fldOpr2, $fld);
            if ($fldVal != "" && $fldVal2 != "" && $isValidValue) {
                $wrk = "x_" . $fldParm . "=" . urlencode($fldVal) . "&y_" . $fldParm . "=" . urlencode($fldVal2) . "&z_" . $fldParm . "=" . urlencode($fldOpr);
            }
        } else {
            $isValidValue = $fldDataType != DATATYPE_NUMBER || $fld->VirtualSearch || IsNumericSearchValue($value, $fldOpr, $fld);
            if ($fldVal != "" && $isValidValue && IsValidOperator($fldOpr)) {
                $wrk = "x_" . $fldParm . "=" . urlencode($fldVal) . "&z_" . $fldParm . "=" . urlencode($fldOpr);
            } elseif (in_array($fldOpr, ["IS NULL", "IS NOT NULL", "IS EMPTY", "IS NOT EMPTY"]) || ($fldOpr != "" && $oprOnly && IsValidOperator($fldOpr))) {
                $wrk = "z_" . $fldParm . "=" . urlencode($fldOpr);
            }
            $isValidValue = $fldDataType != DATATYPE_NUMBER || $fld->VirtualSearch || IsNumericSearchValue($value2, $fldOpr2, $fld);
            if ($fldVal2 != "" && $isValidValue && IsValidOperator($fldOpr2)) {
                if ($wrk != "") {
                    $wrk .= "&v_" . $fldParm . "=" . urlencode($fldCond) . "&";
                }
                $wrk .= "y_" . $fldParm . "=" . urlencode($fldVal2) . "&w_" . $fldParm . "=" . urlencode($fldOpr2);
            } elseif (in_array($fldOpr2, ["IS NULL", "IS NOT NULL", "IS EMPTY", "IS NOT EMPTY"]) || ($fldOpr2 != "" && $oprOnly && IsValidOperator($fldOpr2))) {
                if ($wrk != "") {
                    $wrk .= "&v_" . $fldParm . "=" . urlencode($fldCond) . "&";
                }
                $wrk .= "w_" . $fldParm . "=" . urlencode($fldOpr2);
            }
        }
        if ($wrk != "") {
            if ($url != "") {
                $url .= "&";
            }
            $url .= $wrk;
        }
    }

    // Load search values for validation
    protected function loadSearchValues()
    {
        // Load search values
        $hasValue = false;

        // OrderID
        if ($this->OrderID->AdvancedSearch->get()) {
            $hasValue = true;
        }

        // CustomerID
        if ($this->CustomerID->AdvancedSearch->get()) {
            $hasValue = true;
        }

        // EmployeeID
        if ($this->EmployeeID->AdvancedSearch->get()) {
            $hasValue = true;
        }

        // OrderDate
        if ($this->OrderDate->AdvancedSearch->get()) {
            $hasValue = true;
        }

        // RequiredDate
        if ($this->RequiredDate->AdvancedSearch->get()) {
            $hasValue = true;
        }

        // ShippedDate
        if ($this->ShippedDate->AdvancedSearch->get()) {
            $hasValue = true;
        }

        // ShipVia
        if ($this->ShipVia->AdvancedSearch->get()) {
            $hasValue = true;
        }

        // Freight
        if ($this->Freight->AdvancedSearch->get()) {
            $hasValue = true;
        }

        // ShipName
        if ($this->ShipName->AdvancedSearch->get()) {
            $hasValue = true;
        }

        // ShipAddress
        if ($this->ShipAddress->AdvancedSearch->get()) {
            $hasValue = true;
        }

        // ShipCity
        if ($this->ShipCity->AdvancedSearch->get()) {
            $hasValue = true;
        }

        // ShipRegion
        if ($this->ShipRegion->AdvancedSearch->get()) {
            $hasValue = true;
        }

        // ShipPostalCode
        if ($this->ShipPostalCode->AdvancedSearch->get()) {
            $hasValue = true;
        }

        // ShipCountry
        if ($this->ShipCountry->AdvancedSearch->get()) {
            $hasValue = true;
        }
        return $hasValue;
    }

    // Render row values based on field settings
    public function renderRow()
    {
        global $Security, $Language, $CurrentLanguage;

        // Initialize URLs

        // Call Row_Rendering event
        $this->rowRendering();

        // Common render codes for all row types

        // OrderID
        $this->OrderID->RowCssClass = "row";

        // CustomerID
        $this->CustomerID->RowCssClass = "row";

        // EmployeeID
        $this->EmployeeID->RowCssClass = "row";

        // OrderDate
        $this->OrderDate->RowCssClass = "row";

        // RequiredDate
        $this->RequiredDate->RowCssClass = "row";

        // ShippedDate
        $this->ShippedDate->RowCssClass = "row";

        // ShipVia
        $this->ShipVia->RowCssClass = "row";

        // Freight
        $this->Freight->RowCssClass = "row";

        // ShipName
        $this->ShipName->RowCssClass = "row";

        // ShipAddress
        $this->ShipAddress->RowCssClass = "row";

        // ShipCity
        $this->ShipCity->RowCssClass = "row";

        // ShipRegion
        $this->ShipRegion->RowCssClass = "row";

        // ShipPostalCode
        $this->ShipPostalCode->RowCssClass = "row";

        // ShipCountry
        $this->ShipCountry->RowCssClass = "row";

        // View row
        if ($this->RowType == ROWTYPE_VIEW) {
            // OrderID
            $this->OrderID->ViewValue = $this->OrderID->CurrentValue;

            // CustomerID
            $curVal = strval($this->CustomerID->CurrentValue);
            if ($curVal != "") {
                $this->CustomerID->ViewValue = $this->CustomerID->lookupCacheOption($curVal);
                if ($this->CustomerID->ViewValue === null) { // Lookup from database
                    $filterWrk = SearchFilter("`CustomerID`", "=", $curVal, DATATYPE_STRING, "");
                    $sqlWrk = $this->CustomerID->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCacheImpl($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->CustomerID->Lookup->renderViewRow($rswrk[0]);
                        $this->CustomerID->ViewValue = $this->CustomerID->displayValue($arwrk);
                    } else {
                        $this->CustomerID->ViewValue = $this->CustomerID->CurrentValue;
                    }
                }
            } else {
                $this->CustomerID->ViewValue = null;
            }

            // EmployeeID
            $curVal = strval($this->EmployeeID->CurrentValue);
            if ($curVal != "") {
                $this->EmployeeID->ViewValue = $this->EmployeeID->lookupCacheOption($curVal);
                if ($this->EmployeeID->ViewValue === null) { // Lookup from database
                    $filterWrk = SearchFilter("`EmployeeID`", "=", $curVal, DATATYPE_NUMBER, "");
                    $sqlWrk = $this->EmployeeID->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCacheImpl($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->EmployeeID->Lookup->renderViewRow($rswrk[0]);
                        $this->EmployeeID->ViewValue = $this->EmployeeID->displayValue($arwrk);
                    } else {
                        $this->EmployeeID->ViewValue = $this->EmployeeID->CurrentValue;
                    }
                }
            } else {
                $this->EmployeeID->ViewValue = null;
            }

            // OrderDate
            $this->OrderDate->ViewValue = $this->OrderDate->CurrentValue;
            $this->OrderDate->ViewValue = FormatDateTime($this->OrderDate->ViewValue, $this->OrderDate->formatPattern());

            // RequiredDate
            $this->RequiredDate->ViewValue = $this->RequiredDate->CurrentValue;
            $this->RequiredDate->ViewValue = FormatDateTime($this->RequiredDate->ViewValue, $this->RequiredDate->formatPattern());

            // ShippedDate
            $this->ShippedDate->ViewValue = $this->ShippedDate->CurrentValue;
            $this->ShippedDate->ViewValue = FormatDateTime($this->ShippedDate->ViewValue, $this->ShippedDate->formatPattern());

            // ShipVia
            $this->ShipVia->ViewValue = $this->ShipVia->CurrentValue;

            // Freight
            $this->Freight->ViewValue = $this->Freight->CurrentValue;
            $this->Freight->ViewValue = FormatNumber($this->Freight->ViewValue, $this->Freight->formatPattern());

            // ShipName
            $this->ShipName->ViewValue = $this->ShipName->CurrentValue;

            // ShipAddress
            $this->ShipAddress->ViewValue = $this->ShipAddress->CurrentValue;

            // ShipCity
            $this->ShipCity->ViewValue = $this->ShipCity->CurrentValue;

            // ShipRegion
            $this->ShipRegion->ViewValue = $this->ShipRegion->CurrentValue;

            // ShipPostalCode
            $this->ShipPostalCode->ViewValue = $this->ShipPostalCode->CurrentValue;

            // ShipCountry
            $this->ShipCountry->ViewValue = $this->ShipCountry->CurrentValue;

            // OrderID
            $this->OrderID->HrefValue = "";
            $this->OrderID->TooltipValue = "";

            // CustomerID
            $this->CustomerID->HrefValue = "";
            $this->CustomerID->TooltipValue = "";

            // EmployeeID
            $this->EmployeeID->HrefValue = "";
            $this->EmployeeID->TooltipValue = "";

            // OrderDate
            $this->OrderDate->HrefValue = "";
            $this->OrderDate->TooltipValue = "";

            // RequiredDate
            $this->RequiredDate->HrefValue = "";
            $this->RequiredDate->TooltipValue = "";

            // ShippedDate
            $this->ShippedDate->HrefValue = "";
            $this->ShippedDate->TooltipValue = "";

            // ShipVia
            $this->ShipVia->HrefValue = "";
            $this->ShipVia->TooltipValue = "";

            // Freight
            $this->Freight->HrefValue = "";
            $this->Freight->TooltipValue = "";

            // ShipName
            $this->ShipName->HrefValue = "";
            $this->ShipName->TooltipValue = "";

            // ShipAddress
            $this->ShipAddress->HrefValue = "";
            $this->ShipAddress->TooltipValue = "";

            // ShipCity
            $this->ShipCity->HrefValue = "";
            $this->ShipCity->TooltipValue = "";

            // ShipRegion
            $this->ShipRegion->HrefValue = "";
            $this->ShipRegion->TooltipValue = "";

            // ShipPostalCode
            $this->ShipPostalCode->HrefValue = "";
            $this->ShipPostalCode->TooltipValue = "";

            // ShipCountry
            $this->ShipCountry->HrefValue = "";
            $this->ShipCountry->TooltipValue = "";
        } elseif ($this->RowType == ROWTYPE_SEARCH) {
            // OrderID
            $this->OrderID->setupEditAttributes();
            $this->OrderID->EditValue = HtmlEncode($this->OrderID->AdvancedSearch->SearchValue);
            $this->OrderID->PlaceHolder = RemoveHtml($this->OrderID->caption());

            // CustomerID
            $curVal = trim(strval($this->CustomerID->AdvancedSearch->SearchValue));
            if ($curVal != "") {
                $this->CustomerID->AdvancedSearch->ViewValue = $this->CustomerID->lookupCacheOption($curVal);
            } else {
                $this->CustomerID->AdvancedSearch->ViewValue = $this->CustomerID->Lookup !== null && is_array($this->CustomerID->lookupOptions()) && count($this->CustomerID->lookupOptions()) > 0 ? $curVal : null;
            }
            if ($this->CustomerID->AdvancedSearch->ViewValue !== null) { // Load from cache
                $this->CustomerID->EditValue = array_values($this->CustomerID->lookupOptions());
                if ($this->CustomerID->AdvancedSearch->ViewValue == "") {
                    $this->CustomerID->AdvancedSearch->ViewValue = $Language->phrase("PleaseSelect");
                }
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = SearchFilter("`CustomerID`", "=", $this->CustomerID->AdvancedSearch->SearchValue, DATATYPE_STRING, "");
                }
                $sqlWrk = $this->CustomerID->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCacheImpl($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                if ($ari > 0) { // Lookup values found
                    $arwrk = $this->CustomerID->Lookup->renderViewRow($rswrk[0]);
                    $this->CustomerID->AdvancedSearch->ViewValue = $this->CustomerID->displayValue($arwrk);
                } else {
                    $this->CustomerID->AdvancedSearch->ViewValue = $Language->phrase("PleaseSelect");
                }
                $arwrk = $rswrk;
                $this->CustomerID->EditValue = $arwrk;
            }
            $this->CustomerID->PlaceHolder = RemoveHtml($this->CustomerID->caption());

            // EmployeeID
            $this->EmployeeID->setupEditAttributes();
            $curVal = trim(strval($this->EmployeeID->AdvancedSearch->SearchValue));
            if ($curVal != "") {
                $this->EmployeeID->AdvancedSearch->ViewValue = $this->EmployeeID->lookupCacheOption($curVal);
            } else {
                $this->EmployeeID->AdvancedSearch->ViewValue = $this->EmployeeID->Lookup !== null && is_array($this->EmployeeID->lookupOptions()) && count($this->EmployeeID->lookupOptions()) > 0 ? $curVal : null;
            }
            if ($this->EmployeeID->AdvancedSearch->ViewValue !== null) { // Load from cache
                $this->EmployeeID->EditValue = array_values($this->EmployeeID->lookupOptions());
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = SearchFilter("`EmployeeID`", "=", $this->EmployeeID->AdvancedSearch->SearchValue, DATATYPE_NUMBER, "");
                }
                $sqlWrk = $this->EmployeeID->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCacheImpl($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->EmployeeID->EditValue = $arwrk;
            }
            $this->EmployeeID->PlaceHolder = RemoveHtml($this->EmployeeID->caption());

            // OrderDate
            $this->OrderDate->setupEditAttributes();
            $this->OrderDate->EditValue = HtmlEncode(FormatDateTime(UnFormatDateTime($this->OrderDate->AdvancedSearch->SearchValue, $this->OrderDate->formatPattern()), $this->OrderDate->formatPattern()));
            $this->OrderDate->PlaceHolder = RemoveHtml($this->OrderDate->caption());
            $this->OrderDate->setupEditAttributes();
            $this->OrderDate->EditValue2 = HtmlEncode(FormatDateTime(UnFormatDateTime($this->OrderDate->AdvancedSearch->SearchValue2, $this->OrderDate->formatPattern()), $this->OrderDate->formatPattern()));
            $this->OrderDate->PlaceHolder = RemoveHtml($this->OrderDate->caption());

            // RequiredDate
            $this->RequiredDate->setupEditAttributes();
            $this->RequiredDate->EditValue = HtmlEncode(FormatDateTime(UnFormatDateTime($this->RequiredDate->AdvancedSearch->SearchValue, $this->RequiredDate->formatPattern()), $this->RequiredDate->formatPattern()));
            $this->RequiredDate->PlaceHolder = RemoveHtml($this->RequiredDate->caption());

            // ShippedDate
            $this->ShippedDate->setupEditAttributes();
            $this->ShippedDate->EditValue = HtmlEncode(FormatDateTime(UnFormatDateTime($this->ShippedDate->AdvancedSearch->SearchValue, $this->ShippedDate->formatPattern()), $this->ShippedDate->formatPattern()));
            $this->ShippedDate->PlaceHolder = RemoveHtml($this->ShippedDate->caption());

            // ShipVia
            $this->ShipVia->setupEditAttributes();
            $this->ShipVia->EditValue = HtmlEncode($this->ShipVia->AdvancedSearch->SearchValue);
            $this->ShipVia->PlaceHolder = RemoveHtml($this->ShipVia->caption());

            // Freight
            $this->Freight->setupEditAttributes();
            $this->Freight->EditValue = HtmlEncode($this->Freight->AdvancedSearch->SearchValue);
            $this->Freight->PlaceHolder = RemoveHtml($this->Freight->caption());

            // ShipName
            $this->ShipName->setupEditAttributes();
            if (!$this->ShipName->Raw) {
                $this->ShipName->AdvancedSearch->SearchValue = HtmlDecode($this->ShipName->AdvancedSearch->SearchValue);
            }
            $this->ShipName->EditValue = HtmlEncode($this->ShipName->AdvancedSearch->SearchValue);
            $this->ShipName->PlaceHolder = RemoveHtml($this->ShipName->caption());

            // ShipAddress
            $this->ShipAddress->setupEditAttributes();
            if (!$this->ShipAddress->Raw) {
                $this->ShipAddress->AdvancedSearch->SearchValue = HtmlDecode($this->ShipAddress->AdvancedSearch->SearchValue);
            }
            $this->ShipAddress->EditValue = HtmlEncode($this->ShipAddress->AdvancedSearch->SearchValue);
            $this->ShipAddress->PlaceHolder = RemoveHtml($this->ShipAddress->caption());

            // ShipCity
            $this->ShipCity->setupEditAttributes();
            if (!$this->ShipCity->Raw) {
                $this->ShipCity->AdvancedSearch->SearchValue = HtmlDecode($this->ShipCity->AdvancedSearch->SearchValue);
            }
            $this->ShipCity->EditValue = HtmlEncode($this->ShipCity->AdvancedSearch->SearchValue);
            $this->ShipCity->PlaceHolder = RemoveHtml($this->ShipCity->caption());

            // ShipRegion
            $this->ShipRegion->setupEditAttributes();
            if (!$this->ShipRegion->Raw) {
                $this->ShipRegion->AdvancedSearch->SearchValue = HtmlDecode($this->ShipRegion->AdvancedSearch->SearchValue);
            }
            $this->ShipRegion->EditValue = HtmlEncode($this->ShipRegion->AdvancedSearch->SearchValue);
            $this->ShipRegion->PlaceHolder = RemoveHtml($this->ShipRegion->caption());

            // ShipPostalCode
            $this->ShipPostalCode->setupEditAttributes();
            if (!$this->ShipPostalCode->Raw) {
                $this->ShipPostalCode->AdvancedSearch->SearchValue = HtmlDecode($this->ShipPostalCode->AdvancedSearch->SearchValue);
            }
            $this->ShipPostalCode->EditValue = HtmlEncode($this->ShipPostalCode->AdvancedSearch->SearchValue);
            $this->ShipPostalCode->PlaceHolder = RemoveHtml($this->ShipPostalCode->caption());

            // ShipCountry
            $this->ShipCountry->setupEditAttributes();
            if (!$this->ShipCountry->Raw) {
                $this->ShipCountry->AdvancedSearch->SearchValue = HtmlDecode($this->ShipCountry->AdvancedSearch->SearchValue);
            }
            $this->ShipCountry->EditValue = HtmlEncode($this->ShipCountry->AdvancedSearch->SearchValue);
            $this->ShipCountry->PlaceHolder = RemoveHtml($this->ShipCountry->caption());
        }
        if ($this->RowType == ROWTYPE_ADD || $this->RowType == ROWTYPE_EDIT || $this->RowType == ROWTYPE_SEARCH) { // Add/Edit/Search row
            $this->setupFieldTitles();
        }

        // Call Row Rendered event
        if ($this->RowType != ROWTYPE_AGGREGATEINIT) {
            $this->rowRendered();
        }
    }

    // Validate search
    protected function validateSearch()
    {
        // Check if validation required
        if (!Config("SERVER_VALIDATE")) {
            return true;
        }
        if (!CheckInteger($this->OrderID->AdvancedSearch->SearchValue)) {
            $this->OrderID->addErrorMessage($this->OrderID->getErrorMessage(false));
        }
        if (!CheckDate($this->OrderDate->AdvancedSearch->SearchValue, $this->OrderDate->formatPattern())) {
            $this->OrderDate->addErrorMessage($this->OrderDate->getErrorMessage(false));
        }
        if (!CheckDate($this->OrderDate->AdvancedSearch->SearchValue2, $this->OrderDate->formatPattern())) {
            $this->OrderDate->addErrorMessage($this->OrderDate->getErrorMessage(false));
        }
        if (!CheckDate($this->RequiredDate->AdvancedSearch->SearchValue, $this->RequiredDate->formatPattern())) {
            $this->RequiredDate->addErrorMessage($this->RequiredDate->getErrorMessage(false));
        }
        if (!CheckDate($this->ShippedDate->AdvancedSearch->SearchValue, $this->ShippedDate->formatPattern())) {
            $this->ShippedDate->addErrorMessage($this->ShippedDate->getErrorMessage(false));
        }
        if (!CheckInteger($this->ShipVia->AdvancedSearch->SearchValue)) {
            $this->ShipVia->addErrorMessage($this->ShipVia->getErrorMessage(false));
        }
        if (!CheckNumber($this->Freight->AdvancedSearch->SearchValue)) {
            $this->Freight->addErrorMessage($this->Freight->getErrorMessage(false));
        }

        // Return validate result
        $validateSearch = !$this->hasInvalidFields();

        // Call Form_CustomValidate event
        $formCustomError = "";
        $validateSearch = $validateSearch && $this->formCustomValidate($formCustomError);
        if ($formCustomError != "") {
            $this->setFailureMessage($formCustomError);
        }
        return $validateSearch;
    }

    // Load advanced search
    public function loadAdvancedSearch()
    {
        $this->OrderID->AdvancedSearch->load();
        $this->CustomerID->AdvancedSearch->load();
        $this->EmployeeID->AdvancedSearch->load();
        $this->OrderDate->AdvancedSearch->load();
        $this->RequiredDate->AdvancedSearch->load();
        $this->ShippedDate->AdvancedSearch->load();
        $this->ShipVia->AdvancedSearch->load();
        $this->Freight->AdvancedSearch->load();
        $this->ShipName->AdvancedSearch->load();
        $this->ShipAddress->AdvancedSearch->load();
        $this->ShipCity->AdvancedSearch->load();
        $this->ShipRegion->AdvancedSearch->load();
        $this->ShipPostalCode->AdvancedSearch->load();
        $this->ShipCountry->AdvancedSearch->load();
    }

    // Set up Breadcrumb
    protected function setupBreadcrumb()
    {
        global $Breadcrumb, $Language;
        $Breadcrumb = new Breadcrumb("home");
        $url = CurrentUrl();
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("orderslist"), "", $this->TableVar, true);
        $pageId = "search";
        $Breadcrumb->add("search", $pageId, $url);
    }

    // Setup lookup options
    public function setupLookupOptions($fld)
    {
        if ($fld->Lookup !== null && $fld->Lookup->Options === null) {
            // Get default connection and filter
            $conn = $this->getConnection();
            $lookupFilter = "";

            // No need to check any more
            $fld->Lookup->Options = [];

            // Set up lookup SQL and connection
            switch ($fld->FieldVar) {
                case "x_CustomerID":
                    break;
                case "x_EmployeeID":
                    break;
                default:
                    $lookupFilter = "";
                    break;
            }

            // Always call to Lookup->getSql so that user can setup Lookup->Options in Lookup_Selecting server event
            $sql = $fld->Lookup->getSql(false, "", $lookupFilter, $this);

            // Set up lookup cache
            if (!$fld->hasLookupOptions() && $fld->UseLookupCache && $sql != "" && count($fld->Lookup->Options) == 0 && count($fld->Lookup->FilterFields) == 0) {
                $totalCnt = $this->getRecordCount($sql, $conn);
                if ($totalCnt > $fld->LookupCacheCount) { // Total count > cache count, do not cache
                    return;
                }
                $rows = $conn->executeQuery($sql)->fetchAll();
                $ar = [];
                foreach ($rows as $row) {
                    $row = $fld->Lookup->renderViewRow($row, Container($fld->Lookup->LinkTable));
                    $key = $row["lf"];
                    if (IsFloatType($fld->Type)) { // Handle float field
                        $key = (float)$key;
                    }
                    $ar[strval($key)] = $row;
                }
                $fld->Lookup->Options = $ar;
            }
        }
    }

    // Page Load event
    public function pageLoad()
    {
        //Log("Page Load");
    }

    // Page Unload event
    public function pageUnload()
    {
        //Log("Page Unload");
    }

    // Page Redirecting event
    public function pageRedirecting(&$url)
    {
        // Example:
        //$url = "your URL";
    }

    // Message Showing event
    // $type = ''|'success'|'failure'|'warning'
    public function messageShowing(&$msg, $type)
    {
        if ($type == 'success') {
            //$msg = "your success message";
        } elseif ($type == 'failure') {
            //$msg = "your failure message";
        } elseif ($type == 'warning') {
            //$msg = "your warning message";
        } else {
            //$msg = "your message";
        }
    }

    // Page Render event
    public function pageRender()
    {
        //Log("Page Render");
    }

    // Page Data Rendering event
    public function pageDataRendering(&$header)
    {
        // Example:
        //$header = "your header";
    }

    // Page Data Rendered event
    public function pageDataRendered(&$footer)
    {
        // Example:
        //$footer = "your footer";
    }

    // Page Breaking event
    public function pageBreaking(&$break, &$content)
    {
        // Example:
        //$break = false; // Skip page break, or
        //$content = "<div style=\"break-after:page;\"></div>"; // Modify page break content
    }

    // Form Custom Validate event
    public function formCustomValidate(&$customError)
    {
        // Return error message in $customError
        return true;
    }
}
