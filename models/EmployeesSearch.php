<?php

namespace PHPMaker2023\demo2023;

use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Page class
 */
class EmployeesSearch extends Employees
{
    use MessagesTrait;

    // Page ID
    public $PageID = "search";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Page object name
    public $PageObjName = "EmployeesSearch";

    // View file path
    public $View = null;

    // Title
    public $Title = null; // Title for <title> tag

    // Rendering View
    public $RenderingView = false;

    // CSS class/style
    public $CurrentPageName = "employeessearch";

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
        $this->EmployeeID->setVisibility();
        $this->_Username->setVisibility();
        $this->LastName->setVisibility();
        $this->FirstName->setVisibility();
        $this->_Title->setVisibility();
        $this->TitleOfCourtesy->setVisibility();
        $this->BirthDate->setVisibility();
        $this->HireDate->setVisibility();
        $this->Address->setVisibility();
        $this->City->setVisibility();
        $this->Region->setVisibility();
        $this->PostalCode->setVisibility();
        $this->Country->setVisibility();
        $this->HomePhone->setVisibility();
        $this->Extension->setVisibility();
        $this->Photo->setVisibility();
        $this->Notes->setVisibility();
        $this->ReportsTo->setVisibility();
        $this->_Password->setVisibility();
        $this->_UserLevel->setVisibility();
        $this->_Email->setVisibility();
        $this->Activated->setVisibility();
        $this->_Profile->setVisibility();
    }

    // Constructor
    public function __construct()
    {
        parent::__construct();
        global $Language, $DashboardReport, $DebugTimer, $UserTable;
        $this->TableVar = 'employees';
        $this->TableName = 'employees';

        // Table CSS class
        $this->TableClass = "table table-striped table-bordered table-hover table-sm ew-desktop-table ew-search-table";

        // Initialize
        $GLOBALS["Page"] = &$this;

        // Language object
        $Language = Container("language");

        // Table object (employees)
        if (!isset($GLOBALS["employees"]) || get_class($GLOBALS["employees"]) == PROJECT_NAMESPACE . "employees") {
            $GLOBALS["employees"] = &$this;
        }

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'employees');
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
                    $result["view"] = $pageName == "employeesview"; // If View page, no primary button
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
                $this->Photo->OldUploadPath = $this->Photo->getUploadPath(); // PHP
                $this->Photo->UploadPath = $this->Photo->OldUploadPath;
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
            $key .= @$ar['EmployeeID'];
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
            $this->EmployeeID->Visible = false;
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
        $this->setupLookupOptions($this->TitleOfCourtesy);
        $this->setupLookupOptions($this->ReportsTo);
        $this->setupLookupOptions($this->_UserLevel);
        $this->setupLookupOptions($this->Activated);

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
                $srchStr = "employeeslist" . "?" . $srchStr;
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
        $this->buildSearchUrl($srchUrl, $this->EmployeeID); // EmployeeID
        $this->buildSearchUrl($srchUrl, $this->_Username); // Username
        $this->buildSearchUrl($srchUrl, $this->LastName); // LastName
        $this->buildSearchUrl($srchUrl, $this->FirstName); // FirstName
        $this->buildSearchUrl($srchUrl, $this->_Title); // Title
        $this->buildSearchUrl($srchUrl, $this->TitleOfCourtesy); // TitleOfCourtesy
        $this->buildSearchUrl($srchUrl, $this->BirthDate); // BirthDate
        $this->buildSearchUrl($srchUrl, $this->HireDate); // HireDate
        $this->buildSearchUrl($srchUrl, $this->Address); // Address
        $this->buildSearchUrl($srchUrl, $this->City); // City
        $this->buildSearchUrl($srchUrl, $this->Region); // Region
        $this->buildSearchUrl($srchUrl, $this->PostalCode); // PostalCode
        $this->buildSearchUrl($srchUrl, $this->Country); // Country
        $this->buildSearchUrl($srchUrl, $this->HomePhone); // HomePhone
        $this->buildSearchUrl($srchUrl, $this->Extension); // Extension
        $this->buildSearchUrl($srchUrl, $this->Photo); // Photo
        $this->buildSearchUrl($srchUrl, $this->Notes); // Notes
        $this->buildSearchUrl($srchUrl, $this->ReportsTo); // ReportsTo
        $this->buildSearchUrl($srchUrl, $this->_Password); // Password
        $this->buildSearchUrl($srchUrl, $this->_UserLevel); // UserLevel
        $this->buildSearchUrl($srchUrl, $this->_Email); // Email
        $this->buildSearchUrl($srchUrl, $this->Activated, true); // Activated
        $this->buildSearchUrl($srchUrl, $this->_Profile); // Profile
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

        // EmployeeID
        if ($this->EmployeeID->AdvancedSearch->get()) {
            $hasValue = true;
        }

        // Username
        if ($this->_Username->AdvancedSearch->get()) {
            $hasValue = true;
        }

        // LastName
        if ($this->LastName->AdvancedSearch->get()) {
            $hasValue = true;
        }

        // FirstName
        if ($this->FirstName->AdvancedSearch->get()) {
            $hasValue = true;
        }

        // Title
        if ($this->_Title->AdvancedSearch->get()) {
            $hasValue = true;
        }

        // TitleOfCourtesy
        if ($this->TitleOfCourtesy->AdvancedSearch->get()) {
            $hasValue = true;
        }

        // BirthDate
        if ($this->BirthDate->AdvancedSearch->get()) {
            $hasValue = true;
        }

        // HireDate
        if ($this->HireDate->AdvancedSearch->get()) {
            $hasValue = true;
        }

        // Address
        if ($this->Address->AdvancedSearch->get()) {
            $hasValue = true;
        }

        // City
        if ($this->City->AdvancedSearch->get()) {
            $hasValue = true;
        }

        // Region
        if ($this->Region->AdvancedSearch->get()) {
            $hasValue = true;
        }

        // PostalCode
        if ($this->PostalCode->AdvancedSearch->get()) {
            $hasValue = true;
        }

        // Country
        if ($this->Country->AdvancedSearch->get()) {
            $hasValue = true;
        }

        // HomePhone
        if ($this->HomePhone->AdvancedSearch->get()) {
            $hasValue = true;
        }

        // Extension
        if ($this->Extension->AdvancedSearch->get()) {
            $hasValue = true;
        }

        // Photo
        if ($this->Photo->AdvancedSearch->get()) {
            $hasValue = true;
        }

        // Notes
        if ($this->Notes->AdvancedSearch->get()) {
            $hasValue = true;
        }

        // ReportsTo
        if ($this->ReportsTo->AdvancedSearch->get()) {
            $hasValue = true;
        }

        // Password
        if ($this->_Password->AdvancedSearch->get()) {
            $hasValue = true;
        }

        // UserLevel
        if ($this->_UserLevel->AdvancedSearch->get()) {
            $hasValue = true;
        }

        // Email
        if ($this->_Email->AdvancedSearch->get()) {
            $hasValue = true;
        }

        // Activated
        if ($this->Activated->AdvancedSearch->get()) {
            $hasValue = true;
        }
        if (is_array($this->Activated->AdvancedSearch->SearchValue)) {
            $this->Activated->AdvancedSearch->SearchValue = implode(Config("MULTIPLE_OPTION_SEPARATOR"), $this->Activated->AdvancedSearch->SearchValue);
        }
        if (is_array($this->Activated->AdvancedSearch->SearchValue2)) {
            $this->Activated->AdvancedSearch->SearchValue2 = implode(Config("MULTIPLE_OPTION_SEPARATOR"), $this->Activated->AdvancedSearch->SearchValue2);
        }

        // Profile
        if ($this->_Profile->AdvancedSearch->get()) {
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

        // EmployeeID
        $this->EmployeeID->RowCssClass = "row";

        // Username
        $this->_Username->RowCssClass = "row";

        // LastName
        $this->LastName->RowCssClass = "row";

        // FirstName
        $this->FirstName->RowCssClass = "row";

        // Title
        $this->_Title->RowCssClass = "row";

        // TitleOfCourtesy
        $this->TitleOfCourtesy->RowCssClass = "row";

        // BirthDate
        $this->BirthDate->RowCssClass = "row";

        // HireDate
        $this->HireDate->RowCssClass = "row";

        // Address
        $this->Address->RowCssClass = "row";

        // City
        $this->City->RowCssClass = "row";

        // Region
        $this->Region->RowCssClass = "row";

        // PostalCode
        $this->PostalCode->RowCssClass = "row";

        // Country
        $this->Country->RowCssClass = "row";

        // HomePhone
        $this->HomePhone->RowCssClass = "row";

        // Extension
        $this->Extension->RowCssClass = "row";

        // Photo
        $this->Photo->RowCssClass = "row";

        // Notes
        $this->Notes->RowCssClass = "row";

        // ReportsTo
        $this->ReportsTo->RowCssClass = "row";

        // Password
        $this->_Password->RowCssClass = "row";

        // UserLevel
        $this->_UserLevel->RowCssClass = "row";

        // Email
        $this->_Email->RowCssClass = "row";

        // Activated
        $this->Activated->RowCssClass = "row";

        // Profile
        $this->_Profile->RowCssClass = "row";

        // View row
        if ($this->RowType == ROWTYPE_VIEW) {
            // EmployeeID
            $this->EmployeeID->ViewValue = $this->EmployeeID->CurrentValue;

            // Username
            $this->_Username->ViewValue = $this->_Username->CurrentValue;

            // LastName
            $this->LastName->ViewValue = $this->LastName->CurrentValue;

            // FirstName
            $this->FirstName->ViewValue = $this->FirstName->CurrentValue;

            // Title
            $this->_Title->ViewValue = $this->_Title->CurrentValue;

            // TitleOfCourtesy
            if (strval($this->TitleOfCourtesy->CurrentValue) != "") {
                $this->TitleOfCourtesy->ViewValue = $this->TitleOfCourtesy->optionCaption($this->TitleOfCourtesy->CurrentValue);
            } else {
                $this->TitleOfCourtesy->ViewValue = null;
            }

            // BirthDate
            $this->BirthDate->ViewValue = $this->BirthDate->CurrentValue;
            $this->BirthDate->ViewValue = FormatDateTime($this->BirthDate->ViewValue, $this->BirthDate->formatPattern());

            // HireDate
            $this->HireDate->ViewValue = $this->HireDate->CurrentValue;
            $this->HireDate->ViewValue = FormatDateTime($this->HireDate->ViewValue, $this->HireDate->formatPattern());

            // Address
            $this->Address->ViewValue = $this->Address->CurrentValue;

            // City
            $this->City->ViewValue = $this->City->CurrentValue;

            // Region
            $this->Region->ViewValue = $this->Region->CurrentValue;

            // PostalCode
            $this->PostalCode->ViewValue = $this->PostalCode->CurrentValue;

            // Country
            $this->Country->ViewValue = $this->Country->CurrentValue;

            // HomePhone
            $this->HomePhone->ViewValue = $this->HomePhone->CurrentValue;

            // Extension
            $this->Extension->ViewValue = $this->Extension->CurrentValue;

            // Photo
            $this->Photo->UploadPath = $this->Photo->getUploadPath(); // PHP
            if (!EmptyValue($this->Photo->Upload->DbValue)) {
                $this->Photo->ImageWidth = 120;
                $this->Photo->ImageHeight = 0;
                $this->Photo->ImageAlt = $this->Photo->alt();
                $this->Photo->ImageCssClass = "ew-image";
                $this->Photo->ViewValue = $this->Photo->Upload->DbValue;
            } else {
                $this->Photo->ViewValue = "";
            }

            // Notes
            $this->Notes->ViewValue = $this->Notes->CurrentValue;

            // ReportsTo
            $curVal = strval($this->ReportsTo->CurrentValue);
            if ($curVal != "") {
                $this->ReportsTo->ViewValue = $this->ReportsTo->lookupCacheOption($curVal);
                if ($this->ReportsTo->ViewValue === null) { // Lookup from database
                    $filterWrk = SearchFilter("`EmployeeID`", "=", $curVal, DATATYPE_NUMBER, "");
                    $sqlWrk = $this->ReportsTo->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCacheImpl($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->ReportsTo->Lookup->renderViewRow($rswrk[0]);
                        $this->ReportsTo->ViewValue = $this->ReportsTo->displayValue($arwrk);
                    } else {
                        $this->ReportsTo->ViewValue = $this->ReportsTo->CurrentValue;
                    }
                }
            } else {
                $this->ReportsTo->ViewValue = null;
            }

            // Password
            $this->_Password->ViewValue = $Language->phrase("PasswordMask");

            // UserLevel
            if ($Security->canAdmin()) { // System admin
                $curVal = strval($this->_UserLevel->CurrentValue);
                if ($curVal != "") {
                    $this->_UserLevel->ViewValue = $this->_UserLevel->lookupCacheOption($curVal);
                    if ($this->_UserLevel->ViewValue === null) { // Lookup from database
                        $filterWrk = SearchFilter("`userlevelid`", "=", $curVal, DATATYPE_NUMBER, "");
                        $sqlWrk = $this->_UserLevel->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                        $conn = Conn();
                        $config = $conn->getConfiguration();
                        $config->setResultCacheImpl($this->Cache);
                        $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                        $ari = count($rswrk);
                        if ($ari > 0) { // Lookup values found
                            $arwrk = $this->_UserLevel->Lookup->renderViewRow($rswrk[0]);
                            $this->_UserLevel->ViewValue = $this->_UserLevel->displayValue($arwrk);
                        } else {
                            $this->_UserLevel->ViewValue = $this->_UserLevel->CurrentValue;
                        }
                    }
                } else {
                    $this->_UserLevel->ViewValue = null;
                }
            } else {
                $this->_UserLevel->ViewValue = $Language->phrase("PasswordMask");
            }

            // Email
            $this->_Email->ViewValue = $this->_Email->CurrentValue;

            // Activated
            if (ConvertToBool($this->Activated->CurrentValue)) {
                $this->Activated->ViewValue = $this->Activated->tagCaption(1) != "" ? $this->Activated->tagCaption(1) : "Y";
            } else {
                $this->Activated->ViewValue = $this->Activated->tagCaption(2) != "" ? $this->Activated->tagCaption(2) : "N";
            }

            // Profile
            $this->_Profile->ViewValue = $this->_Profile->CurrentValue;
            if ($this->_Profile->ViewValue != null) {
                $this->_Profile->ViewValue = str_replace(["\r\n", "\n", "\r"], "<br>", $this->_Profile->ViewValue);
            }

            // EmployeeID
            $this->EmployeeID->HrefValue = "";
            $this->EmployeeID->TooltipValue = "";

            // Username
            $this->_Username->HrefValue = "";
            $this->_Username->TooltipValue = "";

            // LastName
            $this->LastName->HrefValue = "";
            $this->LastName->TooltipValue = "";

            // FirstName
            $this->FirstName->HrefValue = "";
            $this->FirstName->TooltipValue = "";

            // Title
            $this->_Title->HrefValue = "";
            $this->_Title->TooltipValue = "";

            // TitleOfCourtesy
            $this->TitleOfCourtesy->HrefValue = "";
            $this->TitleOfCourtesy->TooltipValue = "";

            // BirthDate
            $this->BirthDate->HrefValue = "";
            $this->BirthDate->TooltipValue = "";

            // HireDate
            $this->HireDate->HrefValue = "";
            $this->HireDate->TooltipValue = "";

            // Address
            $this->Address->HrefValue = "";
            $this->Address->TooltipValue = "";

            // City
            $this->City->HrefValue = "";
            $this->City->TooltipValue = "";

            // Region
            $this->Region->HrefValue = "";
            $this->Region->TooltipValue = "";

            // PostalCode
            $this->PostalCode->HrefValue = "";
            $this->PostalCode->TooltipValue = "";

            // Country
            $this->Country->HrefValue = "";
            $this->Country->TooltipValue = "";

            // HomePhone
            $this->HomePhone->HrefValue = "";
            $this->HomePhone->TooltipValue = "";

            // Extension
            $this->Extension->HrefValue = "";
            $this->Extension->TooltipValue = "";

            // Photo
            $this->Photo->UploadPath = $this->Photo->getUploadPath(); // PHP
            if (!EmptyValue($this->Photo->Upload->DbValue)) {
                $this->Photo->HrefValue = "%u"; // Add prefix/suffix
                $this->Photo->LinkAttrs["target"] = "_blank"; // Add target
                if ($this->isExport()) {
                    $this->Photo->HrefValue = FullUrl($this->Photo->HrefValue, "href");
                }
            } else {
                $this->Photo->HrefValue = "";
            }
            $this->Photo->ExportHrefValue = $this->Photo->UploadPath . $this->Photo->Upload->DbValue;
            $this->Photo->TooltipValue = "";
            if ($this->Photo->UseColorbox) {
                if (EmptyValue($this->Photo->TooltipValue)) {
                    $this->Photo->LinkAttrs["title"] = $Language->phrase("ViewImageGallery");
                }
                $this->Photo->LinkAttrs["data-rel"] = "employees_x_Photo";
                $this->Photo->LinkAttrs->appendClass("ew-lightbox");
            }

            // Notes
            $this->Notes->HrefValue = "";
            $this->Notes->TooltipValue = "";

            // ReportsTo
            $this->ReportsTo->HrefValue = "";
            $this->ReportsTo->TooltipValue = "";

            // Password
            $this->_Password->HrefValue = "";
            $this->_Password->TooltipValue = "";

            // UserLevel
            $this->_UserLevel->HrefValue = "";
            $this->_UserLevel->TooltipValue = "";

            // Email
            $this->_Email->HrefValue = "";
            $this->_Email->TooltipValue = "";

            // Activated
            $this->Activated->HrefValue = "";
            $this->Activated->TooltipValue = "";

            // Profile
            $this->_Profile->HrefValue = "";
            $this->_Profile->TooltipValue = "";
        } elseif ($this->RowType == ROWTYPE_SEARCH) {
            // EmployeeID
            $this->EmployeeID->setupEditAttributes();
            $this->EmployeeID->EditValue = HtmlEncode($this->EmployeeID->AdvancedSearch->SearchValue);
            $this->EmployeeID->PlaceHolder = RemoveHtml($this->EmployeeID->caption());

            // Username
            $this->_Username->setupEditAttributes();
            if (!$this->_Username->Raw) {
                $this->_Username->AdvancedSearch->SearchValue = HtmlDecode($this->_Username->AdvancedSearch->SearchValue);
            }
            $this->_Username->EditValue = HtmlEncode($this->_Username->AdvancedSearch->SearchValue);
            $this->_Username->PlaceHolder = RemoveHtml($this->_Username->caption());

            // LastName
            $this->LastName->setupEditAttributes();
            if (!$this->LastName->Raw) {
                $this->LastName->AdvancedSearch->SearchValue = HtmlDecode($this->LastName->AdvancedSearch->SearchValue);
            }
            $this->LastName->EditValue = HtmlEncode($this->LastName->AdvancedSearch->SearchValue);
            $this->LastName->PlaceHolder = RemoveHtml($this->LastName->caption());
            $this->LastName->setupEditAttributes();
            if (!$this->LastName->Raw) {
                $this->LastName->AdvancedSearch->SearchValue2 = HtmlDecode($this->LastName->AdvancedSearch->SearchValue2);
            }
            $this->LastName->EditValue2 = HtmlEncode($this->LastName->AdvancedSearch->SearchValue2);
            $this->LastName->PlaceHolder = RemoveHtml($this->LastName->caption());

            // FirstName
            $this->FirstName->setupEditAttributes();
            if (!$this->FirstName->Raw) {
                $this->FirstName->AdvancedSearch->SearchValue = HtmlDecode($this->FirstName->AdvancedSearch->SearchValue);
            }
            $this->FirstName->EditValue = HtmlEncode($this->FirstName->AdvancedSearch->SearchValue);
            $this->FirstName->PlaceHolder = RemoveHtml($this->FirstName->caption());

            // Title
            $this->_Title->setupEditAttributes();
            if (!$this->_Title->Raw) {
                $this->_Title->AdvancedSearch->SearchValue = HtmlDecode($this->_Title->AdvancedSearch->SearchValue);
            }
            $this->_Title->EditValue = HtmlEncode($this->_Title->AdvancedSearch->SearchValue);
            $this->_Title->PlaceHolder = RemoveHtml($this->_Title->caption());

            // TitleOfCourtesy
            $this->TitleOfCourtesy->setupEditAttributes();
            $this->TitleOfCourtesy->EditValue = $this->TitleOfCourtesy->options(true);
            $this->TitleOfCourtesy->PlaceHolder = RemoveHtml($this->TitleOfCourtesy->caption());

            // BirthDate
            $this->BirthDate->setupEditAttributes();
            $this->BirthDate->EditValue = HtmlEncode(FormatDateTime(UnFormatDateTime($this->BirthDate->AdvancedSearch->SearchValue, $this->BirthDate->formatPattern()), $this->BirthDate->formatPattern()));
            $this->BirthDate->PlaceHolder = RemoveHtml($this->BirthDate->caption());

            // HireDate
            $this->HireDate->setupEditAttributes();
            $this->HireDate->EditValue = HtmlEncode(FormatDateTime(UnFormatDateTime($this->HireDate->AdvancedSearch->SearchValue, $this->HireDate->formatPattern()), $this->HireDate->formatPattern()));
            $this->HireDate->PlaceHolder = RemoveHtml($this->HireDate->caption());

            // Address
            $this->Address->setupEditAttributes();
            if (!$this->Address->Raw) {
                $this->Address->AdvancedSearch->SearchValue = HtmlDecode($this->Address->AdvancedSearch->SearchValue);
            }
            $this->Address->EditValue = HtmlEncode($this->Address->AdvancedSearch->SearchValue);
            $this->Address->PlaceHolder = RemoveHtml($this->Address->caption());

            // City
            $this->City->setupEditAttributes();
            if (!$this->City->Raw) {
                $this->City->AdvancedSearch->SearchValue = HtmlDecode($this->City->AdvancedSearch->SearchValue);
            }
            $this->City->EditValue = HtmlEncode($this->City->AdvancedSearch->SearchValue);
            $this->City->PlaceHolder = RemoveHtml($this->City->caption());

            // Region
            $this->Region->setupEditAttributes();
            if (!$this->Region->Raw) {
                $this->Region->AdvancedSearch->SearchValue = HtmlDecode($this->Region->AdvancedSearch->SearchValue);
            }
            $this->Region->EditValue = HtmlEncode($this->Region->AdvancedSearch->SearchValue);
            $this->Region->PlaceHolder = RemoveHtml($this->Region->caption());

            // PostalCode
            $this->PostalCode->setupEditAttributes();
            if (!$this->PostalCode->Raw) {
                $this->PostalCode->AdvancedSearch->SearchValue = HtmlDecode($this->PostalCode->AdvancedSearch->SearchValue);
            }
            $this->PostalCode->EditValue = HtmlEncode($this->PostalCode->AdvancedSearch->SearchValue);
            $this->PostalCode->PlaceHolder = RemoveHtml($this->PostalCode->caption());

            // Country
            $this->Country->setupEditAttributes();
            if (!$this->Country->Raw) {
                $this->Country->AdvancedSearch->SearchValue = HtmlDecode($this->Country->AdvancedSearch->SearchValue);
            }
            $this->Country->EditValue = HtmlEncode($this->Country->AdvancedSearch->SearchValue);
            $this->Country->PlaceHolder = RemoveHtml($this->Country->caption());

            // HomePhone
            $this->HomePhone->setupEditAttributes();
            if (!$this->HomePhone->Raw) {
                $this->HomePhone->AdvancedSearch->SearchValue = HtmlDecode($this->HomePhone->AdvancedSearch->SearchValue);
            }
            $this->HomePhone->EditValue = HtmlEncode($this->HomePhone->AdvancedSearch->SearchValue);
            $this->HomePhone->PlaceHolder = RemoveHtml($this->HomePhone->caption());

            // Extension
            $this->Extension->setupEditAttributes();
            if (!$this->Extension->Raw) {
                $this->Extension->AdvancedSearch->SearchValue = HtmlDecode($this->Extension->AdvancedSearch->SearchValue);
            }
            $this->Extension->EditValue = HtmlEncode($this->Extension->AdvancedSearch->SearchValue);
            $this->Extension->PlaceHolder = RemoveHtml($this->Extension->caption());

            // Photo
            $this->Photo->setupEditAttributes();
            if (!$this->Photo->Raw) {
                $this->Photo->AdvancedSearch->SearchValue = HtmlDecode($this->Photo->AdvancedSearch->SearchValue);
            }
            $this->Photo->EditValue = HtmlEncode($this->Photo->AdvancedSearch->SearchValue);
            $this->Photo->PlaceHolder = RemoveHtml($this->Photo->caption());

            // Notes
            $this->Notes->setupEditAttributes();
            $this->Notes->EditValue = HtmlEncode($this->Notes->AdvancedSearch->SearchValue);
            $this->Notes->PlaceHolder = RemoveHtml($this->Notes->caption());

            // ReportsTo
            $this->ReportsTo->setupEditAttributes();
            $curVal = trim(strval($this->ReportsTo->AdvancedSearch->SearchValue));
            if ($curVal != "") {
                $this->ReportsTo->AdvancedSearch->ViewValue = $this->ReportsTo->lookupCacheOption($curVal);
            } else {
                $this->ReportsTo->AdvancedSearch->ViewValue = $this->ReportsTo->Lookup !== null && is_array($this->ReportsTo->lookupOptions()) && count($this->ReportsTo->lookupOptions()) > 0 ? $curVal : null;
            }
            if ($this->ReportsTo->AdvancedSearch->ViewValue !== null) { // Load from cache
                $this->ReportsTo->EditValue = array_values($this->ReportsTo->lookupOptions());
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = SearchFilter("`EmployeeID`", "=", $this->ReportsTo->AdvancedSearch->SearchValue, DATATYPE_NUMBER, "");
                }
                $sqlWrk = $this->ReportsTo->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCacheImpl($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->ReportsTo->EditValue = $arwrk;
            }
            $this->ReportsTo->PlaceHolder = RemoveHtml($this->ReportsTo->caption());

            // Password
            $this->_Password->setupEditAttributes(["class" => "ew-password-strength"]);
            $this->_Password->PlaceHolder = RemoveHtml($this->_Password->caption());

            // UserLevel
            $this->_UserLevel->setupEditAttributes();
            if (!$Security->canAdmin()) { // System admin
                $this->_UserLevel->EditValue = $Language->phrase("PasswordMask");
            } else {
                $curVal = trim(strval($this->_UserLevel->AdvancedSearch->SearchValue));
                if ($curVal != "") {
                    $this->_UserLevel->AdvancedSearch->ViewValue = $this->_UserLevel->lookupCacheOption($curVal);
                } else {
                    $this->_UserLevel->AdvancedSearch->ViewValue = $this->_UserLevel->Lookup !== null && is_array($this->_UserLevel->lookupOptions()) && count($this->_UserLevel->lookupOptions()) > 0 ? $curVal : null;
                }
                if ($this->_UserLevel->AdvancedSearch->ViewValue !== null) { // Load from cache
                    $this->_UserLevel->EditValue = array_values($this->_UserLevel->lookupOptions());
                } else { // Lookup from database
                    if ($curVal == "") {
                        $filterWrk = "0=1";
                    } else {
                        $filterWrk = SearchFilter("`userlevelid`", "=", $this->_UserLevel->AdvancedSearch->SearchValue, DATATYPE_NUMBER, "");
                    }
                    $sqlWrk = $this->_UserLevel->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCacheImpl($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    $arwrk = $rswrk;
                    $this->_UserLevel->EditValue = $arwrk;
                }
                $this->_UserLevel->PlaceHolder = RemoveHtml($this->_UserLevel->caption());
            }

            // Email
            $this->_Email->setupEditAttributes();
            if (!$this->_Email->Raw) {
                $this->_Email->AdvancedSearch->SearchValue = HtmlDecode($this->_Email->AdvancedSearch->SearchValue);
            }
            $this->_Email->EditValue = HtmlEncode($this->_Email->AdvancedSearch->SearchValue);
            $this->_Email->PlaceHolder = RemoveHtml($this->_Email->caption());

            // Activated
            $this->Activated->EditValue = $this->Activated->options(false);
            $this->Activated->PlaceHolder = RemoveHtml($this->Activated->caption());

            // Profile
            $this->_Profile->setupEditAttributes();
            $this->_Profile->EditValue = HtmlEncode($this->_Profile->AdvancedSearch->SearchValue);
            $this->_Profile->PlaceHolder = RemoveHtml($this->_Profile->caption());
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
        if (!CheckInteger($this->EmployeeID->AdvancedSearch->SearchValue)) {
            $this->EmployeeID->addErrorMessage($this->EmployeeID->getErrorMessage(false));
        }
        if (!CheckDate($this->BirthDate->AdvancedSearch->SearchValue, $this->BirthDate->formatPattern())) {
            $this->BirthDate->addErrorMessage($this->BirthDate->getErrorMessage(false));
        }
        if (!CheckDate($this->HireDate->AdvancedSearch->SearchValue, $this->HireDate->formatPattern())) {
            $this->HireDate->addErrorMessage($this->HireDate->getErrorMessage(false));
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
        $this->EmployeeID->AdvancedSearch->load();
        $this->_Username->AdvancedSearch->load();
        $this->LastName->AdvancedSearch->load();
        $this->FirstName->AdvancedSearch->load();
        $this->_Title->AdvancedSearch->load();
        $this->TitleOfCourtesy->AdvancedSearch->load();
        $this->BirthDate->AdvancedSearch->load();
        $this->HireDate->AdvancedSearch->load();
        $this->Address->AdvancedSearch->load();
        $this->City->AdvancedSearch->load();
        $this->Region->AdvancedSearch->load();
        $this->PostalCode->AdvancedSearch->load();
        $this->Country->AdvancedSearch->load();
        $this->HomePhone->AdvancedSearch->load();
        $this->Extension->AdvancedSearch->load();
        $this->Photo->AdvancedSearch->load();
        $this->Notes->AdvancedSearch->load();
        $this->ReportsTo->AdvancedSearch->load();
        $this->_Password->AdvancedSearch->load();
        $this->_UserLevel->AdvancedSearch->load();
        $this->_Email->AdvancedSearch->load();
        $this->Activated->AdvancedSearch->load();
        $this->_Profile->AdvancedSearch->load();
    }

    // Set up Breadcrumb
    protected function setupBreadcrumb()
    {
        global $Breadcrumb, $Language;
        $Breadcrumb = new Breadcrumb("home");
        $url = CurrentUrl();
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("employeeslist"), "", $this->TableVar, true);
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
                case "x_TitleOfCourtesy":
                    break;
                case "x_ReportsTo":
                    break;
                case "x__UserLevel":
                    break;
                case "x_Activated":
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
