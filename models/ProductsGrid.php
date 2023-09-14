<?php

namespace PHPMaker2023\demo2023;

use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Page class
 */
class ProductsGrid extends Products
{
    use MessagesTrait;

    // Page ID
    public $PageID = "grid";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Page object name
    public $PageObjName = "ProductsGrid";

    // View file path
    public $View = null;

    // Title
    public $Title = null; // Title for <title> tag

    // Rendering View
    public $RenderingView = false;

    // Grid form hidden field names
    public $FormName = "fproductsgrid";
    public $FormActionName = "";
    public $FormBlankRowName = "";
    public $FormKeyCountName = "";

    // CSS class/style
    public $CurrentPageName = "productsgrid";

    // Page URLs
    public $AddUrl;
    public $EditUrl;
    public $DeleteUrl;
    public $ViewUrl;
    public $CopyUrl;
    public $ListUrl;

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
        $this->ProductID->setVisibility();
        $this->ProductName->setVisibility();
        $this->SupplierID->setVisibility();
        $this->CategoryID->setVisibility();
        $this->QuantityPerUnit->setVisibility();
        $this->UnitPrice->setVisibility();
        $this->UnitsInStock->setVisibility();
        $this->UnitsOnOrder->setVisibility();
        $this->ReorderLevel->setVisibility();
        $this->Discontinued->setVisibility();
        $this->EAN13->setVisibility();
    }

    // Constructor
    public function __construct()
    {
        parent::__construct();
        global $Language, $DashboardReport, $DebugTimer, $UserTable;
        $this->FormActionName = Config("FORM_ROW_ACTION_NAME");
        $this->FormBlankRowName = Config("FORM_BLANK_ROW_NAME");
        $this->FormKeyCountName = Config("FORM_KEY_COUNT_NAME");
        $this->TableVar = 'products';
        $this->TableName = 'products';

        // Table CSS class
        $this->TableClass = "table table-bordered table-hover table-sm ew-table";

        // CSS class name as context
        $this->ContextClass = CheckClassName($this->TableVar);
        AppendClass($this->TableGridClass, $this->ContextClass);

        // Fixed header table
        if (!$this->UseCustomTemplate) {
            $this->setFixedHeaderTable(Config("USE_FIXED_HEADER_TABLE"), Config("FIXED_HEADER_TABLE_HEIGHT"));
        }

        // Initialize
        $this->FormActionName .= "_" . $this->FormName;
        $this->OldKeyName .= "_" . $this->FormName;
        $this->FormBlankRowName .= "_" . $this->FormName;
        $this->FormKeyCountName .= "_" . $this->FormName;
        $GLOBALS["Grid"] = &$this;

        // Language object
        $Language = Container("language");

        // Table object (products)
        if (!isset($GLOBALS["products"]) || get_class($GLOBALS["products"]) == PROJECT_NAMESPACE . "products") {
            $GLOBALS["products"] = &$this;
        }
        $this->AddUrl = "productsadd";

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'products');
        }

        // Start timer
        $DebugTimer = Container("timer");

        // Debug message
        LoadDebugMessage();

        // Open connection
        $GLOBALS["Conn"] ??= $this->getConnection();

        // User table object
        $UserTable = Container("usertable");

        // List options
        $this->ListOptions = new ListOptions(["Tag" => "td", "TableVar" => $this->TableVar]);

        // Other options
        if (!$this->OtherOptions) {
            $this->OtherOptions = new ListOptionsArray();
        }

        // Grid-Add/Edit
        $this->OtherOptions["addedit"] = new ListOptions([
            "TagClassName" => "ew-add-edit-option",
            "UseDropDownButton" => false,
            "DropDownButtonPhrase" => $Language->phrase("ButtonAddEdit"),
            "UseButtonGroup" => true
        ]);
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
        unset($GLOBALS["Grid"]);
        if ($url === "") {
            return;
        }
        if (!IsApi() && method_exists($this, "pageRedirecting")) {
            $this->pageRedirecting($url);
        }

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
            SaveDebugMessage();
            Redirect(GetUrl($url));
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
            $key .= @$ar['ProductID'];
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
            $this->ProductID->Visible = false;
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

    // Class variables
    public $ListOptions; // List options
    public $ExportOptions; // Export options
    public $SearchOptions; // Search options
    public $OtherOptions; // Other options
    public $FilterOptions; // Filter options
    public $ImportOptions; // Import options
    public $ListActions; // List actions
    public $SelectedCount = 0;
    public $SelectedIndex = 0;
    public $ShowOtherOptions = false;
    public $DisplayRecords = 10;
    public $StartRecord;
    public $StopRecord;
    public $TotalRecords = 0;
    public $RecordRange = 10;
    public $PageSizes = "10,20,50,-1"; // Page sizes (comma separated)
    public $DefaultSearchWhere = ""; // Default search WHERE clause
    public $SearchWhere = ""; // Search WHERE clause
    public $SearchPanelClass = "ew-search-panel collapse show"; // Search Panel class
    public $SearchColumnCount = 0; // For extended search
    public $SearchFieldsPerRow = 1; // For extended search
    public $RecordCount = 0; // Record count
    public $InlineRowCount = 0;
    public $StartRowCount = 1;
    public $RowCount = 0;
    public $Attrs = []; // Row attributes and cell attributes
    public $RowIndex = 0; // Row index
    public $KeyCount = 0; // Key count
    public $MultiColumnGridClass = "row-cols-md";
    public $MultiColumnEditClass = "col-12 w-100";
    public $MultiColumnCardClass = "card h-100 ew-card";
    public $MultiColumnListOptionsPosition = "bottom-start";
    public $DbMasterFilter = ""; // Master filter
    public $DbDetailFilter = ""; // Detail filter
    public $MasterRecordExists;
    public $MultiSelectKey;
    public $Command;
    public $UserAction; // User action
    public $RestoreSearch = false;
    public $HashValue; // Hash value
    public $DetailPages;
    public $PageAction;
    public $RecKeys = [];
    public $IsModal = false;
    protected $FilterForModalActions = "";
    private $UseInfiniteScroll = false;

    /**
     * Load recordset from filter
     *
     * @return void
     */
    public function loadRecordsetFromFilter($filter)
    {
        // Set up list options
        $this->setupListOptions();

        // Search options
        $this->setupSearchOptions();

        // Other options
        $this->setupOtherOptions();

        // Set visibility
        $this->setVisibility();

        // Load recordset
        $this->TotalRecords = $this->loadRecordCount($filter);
        $this->StartRecord = 1;
        $this->StopRecord = $this->DisplayRecords;
        $this->CurrentFilter = $filter;
        $this->Recordset = $this->loadRecordset();

        // Set up pager
        $this->Pager = new PrevNextPager($this, $this->StartRecord, $this->DisplayRecords, $this->TotalRecords, $this->PageSizes, $this->RecordRange, $this->AutoHidePager, $this->AutoHidePageSizeSelector);
    }

    /**
     * Page run
     *
     * @return void
     */
    public function run()
    {
        global $ExportType, $UserProfile, $Language, $Security, $CurrentForm, $DashboardReport;

        // Multi column button position
        $this->MultiColumnListOptionsPosition = Config("MULTI_COLUMN_LIST_OPTIONS_POSITION");
        $DashboardReport = $DashboardReport || ConvertToBool(Param(Config("PAGE_DASHBOARD"), false));

        // Use layout
        $this->UseLayout = $this->UseLayout && ConvertToBool(Param(Config("PAGE_LAYOUT"), true));

        // View
        $this->View = Get(Config("VIEW"));

        // Get grid add count
        $gridaddcnt = Get(Config("TABLE_GRID_ADD_ROW_COUNT"), "");
        if (is_numeric($gridaddcnt) && $gridaddcnt > 0) {
            $this->GridAddRowCount = $gridaddcnt;
        }

        // Set up list options
        $this->setupListOptions();
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

        // Set up master detail parameters
        $this->setupMasterParms();

        // Setup other options
        $this->setupOtherOptions();

        // Set up lookup cache
        $this->setupLookupOptions($this->SupplierID);
        $this->setupLookupOptions($this->CategoryID);
        $this->setupLookupOptions($this->Discontinued);

        // Load default values for add
        $this->loadDefaultValues();

        // Update form name to avoid conflict
        if ($this->IsModal) {
            $this->FormName = "fproductsgrid";
        }

        // Set up page action
        $this->PageAction = CurrentPageUrl(false);

        // Set up infinite scroll
        $this->UseInfiniteScroll = ConvertToBool(Param("infinitescroll"));

        // Search filters
        $srchAdvanced = ""; // Advanced search filter
        $srchBasic = ""; // Basic search filter
        $filter = ""; // Filter
        $query = ""; // Query builder

        // Get command
        $this->Command = strtolower(Get("cmd", ""));

        // Set up records per page
        $this->setupDisplayRecords();

        // Handle reset command
        $this->resetCmd();

        // Hide list options
        if ($this->isExport()) {
            $this->ListOptions->hideAllOptions(["sequence"]);
            $this->ListOptions->UseDropDownButton = false; // Disable drop down button
            $this->ListOptions->UseButtonGroup = false; // Disable button group
        } elseif ($this->isGridAdd() || $this->isGridEdit() || $this->isMultiEdit() || $this->isConfirm()) {
            $this->ListOptions->hideAllOptions();
            $this->ListOptions->UseDropDownButton = false; // Disable drop down button
            $this->ListOptions->UseButtonGroup = false; // Disable button group
        }

        // Show grid delete link for grid add / grid edit
        if ($this->AllowAddDeleteRow) {
            if ($this->isGridAdd() || $this->isGridEdit()) {
                $item = $this->ListOptions["griddelete"];
                if ($item) {
                    $item->Visible = $Security->allowDelete(CurrentProjectID() . $this->TableName);
                }
            }
        }

        // Set up sorting order
        $this->setupSortOrder();

        // Restore display records
        if ($this->Command != "json" && $this->getRecordsPerPage() != "") {
            $this->DisplayRecords = $this->getRecordsPerPage(); // Restore from Session
        } else {
            $this->DisplayRecords = 10; // Load default
            $this->setRecordsPerPage($this->DisplayRecords); // Save default to Session
        }

        // Build filter
        $filter = "";
        if (!$Security->canList()) {
            $filter = "(0=1)"; // Filter all records
        }

        // Restore master/detail filter from session
        $this->DbMasterFilter = $this->getMasterFilterFromSession(); // Restore master filter from session
        $this->DbDetailFilter = $this->getDetailFilterFromSession(); // Restore detail filter from session
        AddFilter($filter, $this->DbDetailFilter);
        AddFilter($filter, $this->SearchWhere);

        // Load master record
        if ($this->CurrentMode != "add" && $this->DbMasterFilter != "" && $this->getCurrentMasterTable() == "categories") {
            $masterTbl = Container("categories");
            $rsmaster = $masterTbl->loadRs($this->DbMasterFilter)->fetchAssociative();
            $this->MasterRecordExists = $rsmaster !== false;
            if (!$this->MasterRecordExists) {
                $this->setFailureMessage($Language->phrase("NoRecord")); // Set no record found
                $this->terminate("categorieslist"); // Return to master page
                return;
            } else {
                $masterTbl->loadListRowValues($rsmaster);
                $masterTbl->RowType = ROWTYPE_MASTER; // Master row
                $masterTbl->renderListRow();
            }
        }

        // Set up filter
        if ($this->Command == "json") {
            $this->UseSessionForListSql = false; // Do not use session for ListSQL
            $this->CurrentFilter = $filter;
        } else {
            $this->setSessionWhere($filter);
            $this->CurrentFilter = "";
        }
        $this->Filter = $this->applyUserIDFilters($filter);
        if ($this->isGridAdd()) {
            if ($this->CurrentMode == "copy") {
                $this->TotalRecords = $this->listRecordCount();
                $this->StartRecord = 1;
                $this->DisplayRecords = $this->TotalRecords;
                $this->Recordset = $this->loadRecordset($this->StartRecord - 1, $this->DisplayRecords);
            } else {
                $this->CurrentFilter = "0=1";
                $this->StartRecord = 1;
                $this->DisplayRecords = $this->GridAddRowCount;
            }
            $this->TotalRecords = $this->DisplayRecords;
            $this->StopRecord = $this->DisplayRecords;
        } elseif (($this->isEdit() || $this->isCopy() || $this->isInlineInserted() || $this->isInlineUpdated()) && $this->UseInfiniteScroll) { // Get current record only
            $this->CurrentFilter = $this->isInlineUpdated() ? $this->getRecordFilter() : $this->getFilterFromRecordKeys();
            $this->TotalRecords = $this->listRecordCount();
            $this->StartRecord = 1;
            $this->StopRecord = $this->DisplayRecords;
            $this->Recordset = $this->loadRecordset();
        } elseif (
            $this->UseInfiniteScroll && $this->isGridInserted() ||
            $this->UseInfiniteScroll && ($this->isGridEdit() || $this->isGridUpdated()) ||
            $this->isMultiEdit() ||
            $this->UseInfiniteScroll && $this->isMultiUpdated()
        ) { // Get current records only
            $this->CurrentFilter = $this->FilterForModalActions; // Restore filter
            $this->TotalRecords = $this->listRecordCount();
            $this->StartRecord = 1;
            $this->StopRecord = $this->DisplayRecords;
            $this->Recordset = $this->loadRecordset();
        } else {
            $this->TotalRecords = $this->listRecordCount();
            $this->StartRecord = 1;
            $this->DisplayRecords = $this->TotalRecords; // Display all records
            $this->Recordset = $this->loadRecordset($this->StartRecord - 1, $this->DisplayRecords);
        }

        // API list action
        if (IsApi()) {
            if (Route(0) == Config("API_LIST_ACTION")) {
                if (!$this->isExport()) {
                    $rows = $this->getRecordsFromRecordset($this->Recordset);
                    $this->Recordset->close();
                    WriteJson([
                        "success" => true,
                        "action" => Config("API_LIST_ACTION"),
                        $this->TableVar => $rows,
                        "totalRecordCount" => $this->TotalRecords
                    ]);
                    $this->terminate(true);
                }
                return;
            } elseif ($this->getFailureMessage() != "") {
                WriteJson(["error" => $this->getFailureMessage()]);
                $this->clearFailureMessage();
                $this->terminate(true);
                return;
            }
        }

        // Render other options
        $this->renderOtherOptions();

        // Set up pager
        $this->Pager = new PrevNextPager($this, $this->StartRecord, $this->DisplayRecords, $this->TotalRecords, $this->PageSizes, $this->RecordRange, $this->AutoHidePager, $this->AutoHidePageSizeSelector);

        // Set ReturnUrl in header if necessary
        if ($returnUrl = Container("flash")->getFirstMessage("Return-Url")) {
            AddHeader("Return-Url", GetUrl($returnUrl));
        }

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

    // Get page number
    public function getPageNumber()
    {
        return ($this->DisplayRecords > 0 && $this->StartRecord > 0) ? ceil($this->StartRecord / $this->DisplayRecords) : 1;
    }

    // Set up number of records displayed per page
    protected function setupDisplayRecords()
    {
        $wrk = Get(Config("TABLE_REC_PER_PAGE"), "");
        if ($wrk != "") {
            if (is_numeric($wrk)) {
                $this->DisplayRecords = (int)$wrk;
            } else {
                if (SameText($wrk, "all")) { // Display all records
                    $this->DisplayRecords = -1;
                } else {
                    $this->DisplayRecords = 10; // Non-numeric, load default
                }
            }
            $this->setRecordsPerPage($this->DisplayRecords); // Save to Session
            // Reset start position
            $this->StartRecord = 1;
            $this->setStartRecordNumber($this->StartRecord);
        }
    }

    // Exit inline mode
    protected function clearInlineMode()
    {
        $this->UnitPrice->FormValue = ""; // Clear form value
        $this->LastAction = $this->CurrentAction; // Save last action
        $this->CurrentAction = ""; // Clear action
        $_SESSION[SESSION_INLINE_MODE] = ""; // Clear inline mode
    }

    // Switch to grid add mode
    protected function gridAddMode()
    {
        $this->CurrentAction = "gridadd";
        $_SESSION[SESSION_INLINE_MODE] = "gridadd";
        $this->hideFieldsForAddEdit();
    }

    // Switch to grid edit mode
    protected function gridEditMode()
    {
        $this->CurrentAction = "gridedit";
        $_SESSION[SESSION_INLINE_MODE] = "gridedit";
        $this->hideFieldsForAddEdit();
    }

    // Perform update to grid
    public function gridUpdate()
    {
        global $Language, $CurrentForm;
        $gridUpdate = true;

        // Get old recordset
        $this->CurrentFilter = $this->buildKeyFilter();
        if ($this->CurrentFilter == "") {
            $this->CurrentFilter = "0=1";
        }
        $sql = $this->getCurrentSql();
        $conn = $this->getConnection();
        if ($rs = $conn->executeQuery($sql)) {
            $rsold = $rs->fetchAllAssociative();
        }

        // Call Grid Updating event
        if (!$this->gridUpdating($rsold)) {
            if ($this->getFailureMessage() == "") {
                $this->setFailureMessage($Language->phrase("GridEditCancelled")); // Set grid edit cancelled message
            }
            $this->EventCancelled = true;
            return false;
        }
        $this->loadDefaultValues();
        $wrkfilter = "";
        $key = "";

        // Update row index and get row key
        $CurrentForm->resetIndex();
        $rowcnt = strval($CurrentForm->getValue($this->FormKeyCountName));
        if ($rowcnt == "" || !is_numeric($rowcnt)) {
            $rowcnt = 0;
        }

        // Update all rows based on key
        for ($rowindex = 1; $rowindex <= $rowcnt; $rowindex++) {
            $CurrentForm->Index = $rowindex;
            $this->setKey($CurrentForm->getValue($this->OldKeyName));
            $rowaction = strval($CurrentForm->getValue($this->FormActionName));

            // Load all values and keys
            if ($rowaction != "insertdelete" && $rowaction != "hide") { // Skip insert then deleted rows / hidden rows for grid edit
                $this->loadFormValues(); // Get form values
                if ($rowaction == "" || $rowaction == "edit" || $rowaction == "delete") {
                    $gridUpdate = $this->OldKey != ""; // Key must not be empty
                } else {
                    $gridUpdate = true;
                }

                // Skip empty row
                if ($rowaction == "insert" && $this->emptyRow()) {
                // Validate form and insert/update/delete record
                } elseif ($gridUpdate) {
                    if ($rowaction == "delete") {
                        $this->CurrentFilter = $this->getRecordFilter();
                        $gridUpdate = $this->deleteRows(); // Delete this row
                    } else {
                        if ($rowaction == "insert") {
                            $gridUpdate = $this->addRow(); // Insert this row
                        } else {
                            if ($this->OldKey != "") {
                                $this->SendEmail = false; // Do not send email on update success
                                $gridUpdate = $this->editRow(); // Update this row
                            }
                        } // End update
                        if ($gridUpdate) { // Get inserted or updated filter
                            AddFilter($wrkfilter, $this->getRecordFilter(), "OR");
                        }
                    }
                }
                if ($gridUpdate) {
                    if ($key != "") {
                        $key .= ", ";
                    }
                    $key .= $this->OldKey;
                } else {
                    $this->EventCancelled = true;
                    break;
                }
            }
        }
        if ($gridUpdate) {
            $this->FilterForModalActions = $wrkfilter;

            // Get new records
            $rsnew = $conn->fetchAllAssociative($sql);

            // Call Grid_Updated event
            $this->gridUpdated($rsold, $rsnew);
            $this->clearInlineMode(); // Clear inline edit mode
        } else {
            if ($this->getFailureMessage() == "") {
                $this->setFailureMessage($Language->phrase("UpdateFailed")); // Set update failed message
            }
        }
        return $gridUpdate;
    }

    // Build filter for all keys
    protected function buildKeyFilter()
    {
        global $CurrentForm;
        $wrkFilter = "";

        // Update row index and get row key
        $rowindex = 1;
        $CurrentForm->Index = $rowindex;
        $thisKey = strval($CurrentForm->getValue($this->OldKeyName));
        while ($thisKey != "") {
            $this->setKey($thisKey);
            if ($this->OldKey != "") {
                $filter = $this->getRecordFilter();
                if ($wrkFilter != "") {
                    $wrkFilter .= " OR ";
                }
                $wrkFilter .= $filter;
            } else {
                $wrkFilter = "0=1";
                break;
            }

            // Update row index and get row key
            $rowindex++; // Next row
            $CurrentForm->Index = $rowindex;
            $thisKey = strval($CurrentForm->getValue($this->OldKeyName));
        }
        return $wrkFilter;
    }

    // Perform grid add
    public function gridInsert()
    {
        global $Language, $CurrentForm;
        $rowindex = 1;
        $gridInsert = false;
        $conn = $this->getConnection();

        // Call Grid Inserting event
        if (!$this->gridInserting()) {
            if ($this->getFailureMessage() == "") {
                $this->setFailureMessage($Language->phrase("GridAddCancelled")); // Set grid add cancelled message
            }
            $this->EventCancelled = true;
            return false;
        }
        $this->loadDefaultValues();

        // Init key filter
        $wrkfilter = "";
        $addcnt = 0;
        $key = "";

        // Get row count
        $CurrentForm->resetIndex();
        $rowcnt = strval($CurrentForm->getValue($this->FormKeyCountName));
        if ($rowcnt == "" || !is_numeric($rowcnt)) {
            $rowcnt = 0;
        }

        // Insert all rows
        for ($rowindex = 1; $rowindex <= $rowcnt; $rowindex++) {
            // Load current row values
            $CurrentForm->Index = $rowindex;
            $rowaction = strval($CurrentForm->getValue($this->FormActionName));
            if ($rowaction != "" && $rowaction != "insert") {
                continue; // Skip
            }
            $rsold = null;
            if ($rowaction == "insert") {
                $this->OldKey = strval($CurrentForm->getValue($this->OldKeyName));
                $rsold = $this->loadOldRecord(); // Load old record
            }
            $this->loadFormValues(); // Get form values
            if (!$this->emptyRow()) {
                $addcnt++;
                $this->SendEmail = false; // Do not send email on insert success
                $gridInsert = $this->addRow($rsold); // Insert row (already validated by validateGridForm())
                if ($gridInsert) {
                    if ($key != "") {
                        $key .= Config("COMPOSITE_KEY_SEPARATOR");
                    }
                    $key .= $this->ProductID->CurrentValue;

                    // Add filter for this record
                    AddFilter($wrkfilter, $this->getRecordFilter(), "OR");
                } else {
                    $this->EventCancelled = true;
                    break;
                }
            }
        }
        if ($addcnt == 0) { // No record inserted
            $this->clearInlineMode(); // Clear grid add mode and return
            return true;
        }
        if ($gridInsert) {
            // Get new records
            $this->CurrentFilter = $wrkfilter;
            $this->FilterForModalActions = $wrkfilter;
            $sql = $this->getCurrentSql();
            $rsnew = $conn->fetchAllAssociative($sql);

            // Call Grid_Inserted event
            $this->gridInserted($rsnew);
            $this->clearInlineMode(); // Clear grid add mode
        } else {
            if ($this->getFailureMessage() == "") {
                $this->setFailureMessage($Language->phrase("InsertFailed")); // Set insert failed message
            }
        }
        return $gridInsert;
    }

    // Check if empty row
    public function emptyRow()
    {
        global $CurrentForm;
        if ($CurrentForm->hasValue("x_ProductName") && $CurrentForm->hasValue("o_ProductName") && $this->ProductName->CurrentValue != $this->ProductName->DefaultValue) {
            return false;
        }
        if ($CurrentForm->hasValue("x_SupplierID") && $CurrentForm->hasValue("o_SupplierID") && $this->SupplierID->CurrentValue != $this->SupplierID->DefaultValue) {
            return false;
        }
        if ($CurrentForm->hasValue("x_CategoryID") && $CurrentForm->hasValue("o_CategoryID") && $this->CategoryID->CurrentValue != $this->CategoryID->DefaultValue) {
            return false;
        }
        if ($CurrentForm->hasValue("x_QuantityPerUnit") && $CurrentForm->hasValue("o_QuantityPerUnit") && $this->QuantityPerUnit->CurrentValue != $this->QuantityPerUnit->DefaultValue) {
            return false;
        }
        if ($CurrentForm->hasValue("x_UnitPrice") && $CurrentForm->hasValue("o_UnitPrice") && $this->UnitPrice->CurrentValue != $this->UnitPrice->DefaultValue) {
            return false;
        }
        if ($CurrentForm->hasValue("x_UnitsInStock") && $CurrentForm->hasValue("o_UnitsInStock") && $this->UnitsInStock->CurrentValue != $this->UnitsInStock->DefaultValue) {
            return false;
        }
        if ($CurrentForm->hasValue("x_UnitsOnOrder") && $CurrentForm->hasValue("o_UnitsOnOrder") && $this->UnitsOnOrder->CurrentValue != $this->UnitsOnOrder->DefaultValue) {
            return false;
        }
        if ($CurrentForm->hasValue("x_ReorderLevel") && $CurrentForm->hasValue("o_ReorderLevel") && $this->ReorderLevel->CurrentValue != $this->ReorderLevel->DefaultValue) {
            return false;
        }
        if ($CurrentForm->hasValue("x_Discontinued") && $CurrentForm->hasValue("o_Discontinued") && ConvertToBool($this->Discontinued->CurrentValue) != ConvertToBool($this->Discontinued->DefaultValue)) {
            return false;
        }
        if ($CurrentForm->hasValue("x_EAN13") && $CurrentForm->hasValue("o_EAN13") && $this->EAN13->CurrentValue != $this->EAN13->DefaultValue) {
            return false;
        }
        return true;
    }

    // Validate grid form
    public function validateGridForm()
    {
        global $CurrentForm;

        // Get row count
        $CurrentForm->resetIndex();
        $rowcnt = strval($CurrentForm->getValue($this->FormKeyCountName));
        if ($rowcnt == "" || !is_numeric($rowcnt)) {
            $rowcnt = 0;
        }

        // Load default values for emptyRow checking
        $this->loadDefaultValues();

        // Validate all records
        for ($rowindex = 1; $rowindex <= $rowcnt; $rowindex++) {
            // Load current row values
            $CurrentForm->Index = $rowindex;
            $rowaction = strval($CurrentForm->getValue($this->FormActionName));
            if ($rowaction != "delete" && $rowaction != "insertdelete" && $rowaction != "hide") {
                $this->loadFormValues(); // Get form values
                if ($rowaction == "insert" && $this->emptyRow()) {
                    // Ignore
                } elseif (!$this->validateForm()) {
                    $this->ValidationErrors[$rowindex] = $this->getValidationErrors();
                    $this->EventCancelled = true;
                    return false;
                }
            }
        }
        return true;
    }

    // Get all form values of the grid
    public function getGridFormValues()
    {
        global $CurrentForm;
        // Get row count
        $CurrentForm->resetIndex();
        $rowcnt = strval($CurrentForm->getValue($this->FormKeyCountName));
        if ($rowcnt == "" || !is_numeric($rowcnt)) {
            $rowcnt = 0;
        }
        $rows = [];

        // Loop through all records
        for ($rowindex = 1; $rowindex <= $rowcnt; $rowindex++) {
            // Load current row values
            $CurrentForm->Index = $rowindex;
            $rowaction = strval($CurrentForm->getValue($this->FormActionName));
            if ($rowaction != "delete" && $rowaction != "insertdelete") {
                $this->loadFormValues(); // Get form values
                if ($rowaction == "insert" && $this->emptyRow()) {
                    // Ignore
                } else {
                    $rows[] = $this->getFieldValues("FormValue"); // Return row as array
                }
            }
        }
        return $rows; // Return as array of array
    }

    // Restore form values for current row
    public function restoreCurrentRowFormValues($idx)
    {
        global $CurrentForm;

        // Get row based on current index
        $CurrentForm->Index = $idx;
        $rowaction = strval($CurrentForm->getValue($this->FormActionName));
        $this->loadFormValues(); // Load form values
        // Set up invalid status correctly
        $this->resetFormError();
        if ($rowaction == "insert" && $this->emptyRow()) {
            // Ignore
        } else {
            $this->validateForm();
        }
    }

    // Reset form status
    public function resetFormError()
    {
        foreach ($this->Fields as $field) {
            $field->clearErrorMessage();
        }
    }

    // Set up sort parameters
    protected function setupSortOrder()
    {
        // Load default Sorting Order
        if ($this->Command != "json") {
            $defaultSort = ""; // Set up default sort
            if ($this->getSessionOrderBy() == "" && $defaultSort != "") {
                $this->setSessionOrderBy($defaultSort);
            }
        }

        // Check for "order" parameter
        if (Get("order") !== null) {
            $this->CurrentOrder = Get("order");
            $this->CurrentOrderType = Get("ordertype", "");
            $this->setStartRecordNumber(1); // Reset start position
        }

        // Update field sort
        $this->updateFieldSort();
    }

    // Reset command
    // - cmd=reset (Reset search parameters)
    // - cmd=resetall (Reset search and master/detail parameters)
    // - cmd=resetsort (Reset sort parameters)
    protected function resetCmd()
    {
        // Check if reset command
        if (StartsString("reset", $this->Command)) {
            // Reset master/detail keys
            if ($this->Command == "resetall") {
                $this->setCurrentMasterTable(""); // Clear master table
                $this->DbMasterFilter = "";
                $this->DbDetailFilter = "";
                        $this->CategoryID->setSessionValue("");
            }

            // Reset (clear) sorting order
            if ($this->Command == "resetsort") {
                $orderBy = "";
                $this->setSessionOrderBy($orderBy);
            }

            // Reset start position
            $this->StartRecord = 1;
            $this->setStartRecordNumber($this->StartRecord);
        }
    }

    // Set up list options
    protected function setupListOptions()
    {
        global $Security, $Language;

        // "griddelete"
        if ($this->AllowAddDeleteRow) {
            $item = &$this->ListOptions->add("griddelete");
            $item->CssClass = "text-nowrap";
            $item->OnLeft = true;
            $item->Visible = false; // Default hidden
        }

        // Add group option item ("button")
        $item = &$this->ListOptions->addGroupOption();
        $item->Body = "";
        $item->OnLeft = true;
        $item->Visible = false;

        // "view"
        $item = &$this->ListOptions->add("view");
        $item->CssClass = "text-nowrap";
        $item->Visible = $Security->canView();
        $item->OnLeft = true;

        // "edit"
        $item = &$this->ListOptions->add("edit");
        $item->CssClass = "text-nowrap";
        $item->Visible = $Security->canEdit();
        $item->OnLeft = true;

        // "copy"
        $item = &$this->ListOptions->add("copy");
        $item->CssClass = "text-nowrap";
        $item->Visible = $Security->canAdd();
        $item->OnLeft = true;

        // "delete"
        $item = &$this->ListOptions->add("delete");
        $item->CssClass = "text-nowrap";
        $item->Visible = $Security->canDelete();
        $item->OnLeft = true;

        // Drop down button for ListOptions
        $this->ListOptions->UseDropDownButton = true;
        $this->ListOptions->DropDownButtonPhrase = $Language->phrase("ButtonListOptions");
        $this->ListOptions->UseButtonGroup = true;
        if ($this->ListOptions->UseButtonGroup && IsMobile()) {
            $this->ListOptions->UseDropDownButton = true;
        }

        //$this->ListOptions->ButtonClass = ""; // Class for button group

        // Call ListOptions_Load event
        $this->listOptionsLoad();
        $item = $this->ListOptions[$this->ListOptions->GroupOptionName];
        $item->Visible = $this->ListOptions->groupOptionVisible();
    }

    // Set up list options (extensions)
    protected function setupListOptionsExt()
    {
            // Set up list options (to be implemented by extensions)
    }

    // Add "hash" parameter to URL
    public function urlAddHash($url, $hash)
    {
        return $this->UseAjaxActions ? $url : UrlAddQuery($url, "hash=" . $hash);
    }

    // Render list options
    public function renderListOptions()
    {
        global $Security, $Language, $CurrentForm, $UserProfile;
        $this->ListOptions->loadDefault();

        // Call ListOptions_Rendering event
        $this->listOptionsRendering();

        // Set up row action and key
        if ($CurrentForm && is_numeric($this->RowIndex) && $this->RowType != "view") {
            $CurrentForm->Index = $this->RowIndex;
            $actionName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormActionName);
            $oldKeyName = str_replace("k_", "k" . $this->RowIndex . "_", $this->OldKeyName);
            $blankRowName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormBlankRowName);
            if ($this->RowAction != "") {
                $this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $actionName . "\" id=\"" . $actionName . "\" value=\"" . $this->RowAction . "\">";
            }
            $oldKey = $this->getKey(false); // Get from OldValue
            if ($oldKeyName != "" && $oldKey != "") {
                $this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $oldKeyName . "\" id=\"" . $oldKeyName . "\" value=\"" . HtmlEncode($oldKey) . "\">";
            }
            if ($this->RowAction == "insert" && $this->isConfirm() && $this->emptyRow()) {
                $this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $blankRowName . "\" id=\"" . $blankRowName . "\" value=\"1\">";
            }
        }

        // "delete"
        if ($this->AllowAddDeleteRow) {
            if ($this->CurrentMode == "add" || $this->CurrentMode == "copy" || $this->CurrentMode == "edit") {
                $options = &$this->ListOptions;
                $options->UseButtonGroup = true; // Use button group for grid delete button
                $opt = $options["griddelete"];
                if (!$Security->allowDelete(CurrentProjectID() . $this->TableName) && is_numeric($this->RowIndex) && ($this->RowAction == "" || $this->RowAction == "edit")) { // Do not allow delete existing record
                    $opt->Body = "&nbsp;";
                } else {
                    $opt->Body = "<a class=\"ew-grid-link ew-grid-delete\" title=\"" . HtmlTitle($Language->phrase("DeleteLink")) . "\" data-caption=\"" . HtmlTitle($Language->phrase("DeleteLink")) . "\" data-ew-action=\"delete-grid-row\" data-rowindex=\"" . $this->RowIndex . "\">" . $Language->phrase("DeleteLink") . "</a>";
                }
            }
        }
        if ($this->CurrentMode == "view") {
            // "view"
            $opt = $this->ListOptions["view"];
            $viewcaption = HtmlTitle($Language->phrase("ViewLink"));
            if ($Security->canView()) {
                if ($this->ModalView && !IsMobile()) {
                    $opt->Body = "<a class=\"ew-row-link ew-view\" title=\"" . $viewcaption . "\" data-table=\"products\" data-caption=\"" . $viewcaption . "\" data-ew-action=\"modal\" data-action=\"view\" data-ajax=\"" . ($this->UseAjaxActions ? "true" : "false") . "\" data-url=\"" . HtmlEncode(GetUrl($this->ViewUrl)) . "\" data-btn=\"null\">" . $Language->phrase("ViewLink") . "</a>";
                } else {
                    $opt->Body = "<a class=\"ew-row-link ew-view\" title=\"" . $viewcaption . "\" data-caption=\"" . $viewcaption . "\" href=\"" . HtmlEncode(GetUrl($this->ViewUrl)) . "\">" . $Language->phrase("ViewLink") . "</a>";
                }
            } else {
                $opt->Body = "";
            }

            // "edit"
            $opt = $this->ListOptions["edit"];
            $editcaption = HtmlTitle($Language->phrase("EditLink"));
            if ($Security->canEdit()) {
                if ($this->ModalEdit && !IsMobile()) {
                    $opt->Body = "<a class=\"ew-row-link ew-edit\" title=\"" . $editcaption . "\" data-table=\"products\" data-caption=\"" . $editcaption . "\" data-ew-action=\"modal\" data-action=\"edit\" data-ajax=\"" . ($this->UseAjaxActions ? "true" : "false") . "\" data-url=\"" . HtmlEncode(GetUrl($this->EditUrl)) . "\" data-btn=\"SaveBtn\">" . $Language->phrase("EditLink") . "</a>";
                } else {
                    $opt->Body = "<a class=\"ew-row-link ew-edit\" title=\"" . $editcaption . "\" data-caption=\"" . $editcaption . "\" href=\"" . HtmlEncode(GetUrl($this->EditUrl)) . "\">" . $Language->phrase("EditLink") . "</a>";
                }
            } else {
                $opt->Body = "";
            }

            // "copy"
            $opt = $this->ListOptions["copy"];
            $copycaption = HtmlTitle($Language->phrase("CopyLink"));
            if ($Security->canAdd()) {
                if ($this->ModalAdd && !IsMobile()) {
                    $opt->Body = "<a class=\"ew-row-link ew-copy\" title=\"" . $copycaption . "\" data-table=\"products\" data-caption=\"" . $copycaption . "\" data-ew-action=\"modal\" data-action=\"add\" data-ajax=\"" . ($this->UseAjaxActions ? "true" : "false") . "\" data-url=\"" . HtmlEncode(GetUrl($this->CopyUrl)) . "\" data-btn=\"AddBtn\">" . $Language->phrase("CopyLink") . "</a>";
                } else {
                    $opt->Body = "<a class=\"ew-row-link ew-copy\" title=\"" . $copycaption . "\" data-caption=\"" . $copycaption . "\" href=\"" . HtmlEncode(GetUrl($this->CopyUrl)) . "\">" . $Language->phrase("CopyLink") . "</a>";
                }
            } else {
                $opt->Body = "";
            }

            // "delete"
            $opt = $this->ListOptions["delete"];
            if ($Security->canDelete()) {
                $deleteCaption = $Language->phrase("DeleteLink");
                $deleteTitle = HtmlTitle($deleteCaption);
                if ($this->UseAjaxActions) {
                    $opt->Body = "<a class=\"ew-row-link ew-delete\" data-ew-action=\"inline\" data-action=\"delete\" title=\"" . $deleteTitle . "\" data-caption=\"" . $deleteTitle . "\" data-key= \"" . HtmlEncode($this->getKey(true)) . "\" data-url=\"" . HtmlEncode(GetUrl($this->DeleteUrl)) . "\">" . $deleteCaption . "</a>";
                } else {
                    $opt->Body = "<a class=\"ew-row-link ew-delete\"" .
                        ($this->InlineDelete ? " data-ew-action=\"inline-delete\"" : "") .
                        " title=\"" . $deleteTitle . "\" data-caption=\"" . $deleteTitle . "\" href=\"" . HtmlEncode(GetUrl($this->DeleteUrl)) . "\">" . $deleteCaption . "</a>";
                }
            } else {
                $opt->Body = "";
            }
        } // End View mode
        $this->renderListOptionsExt();

        // Call ListOptions_Rendered event
        $this->listOptionsRendered();
    }

    // Render list options (extensions)
    protected function renderListOptionsExt()
    {
        // Render list options (to be implemented by extensions)
        global $Security, $Language;
    }

    // Set up other options
    protected function setupOtherOptions()
    {
        global $Language, $Security;
        $option = $this->OtherOptions["addedit"];
        $item = &$option->addGroupOption();
        $item->Body = "";
        $item->Visible = false;

        // Add
        if ($this->CurrentMode == "view") { // Check view mode
            $item = &$option->add("add");
            $addcaption = HtmlTitle($Language->phrase("AddLink"));
            $this->AddUrl = $this->getAddUrl();
            if ($this->ModalAdd && !IsMobile()) {
                $item->Body = "<a class=\"ew-add-edit ew-add\" title=\"" . $addcaption . "\" data-table=\"products\" data-caption=\"" . $addcaption . "\" data-ew-action=\"modal\" data-action=\"add\" data-ajax=\"" . ($this->UseAjaxActions ? "true" : "false") . "\" data-url=\"" . HtmlEncode(GetUrl($this->AddUrl)) . "\" data-btn=\"AddBtn\">" . $Language->phrase("AddLink") . "</a>";
            } else {
                $item->Body = "<a class=\"ew-add-edit ew-add\" title=\"" . $addcaption . "\" data-caption=\"" . $addcaption . "\" href=\"" . HtmlEncode(GetUrl($this->AddUrl)) . "\">" . $Language->phrase("AddLink") . "</a>";
            }
            $item->Visible = $this->AddUrl != "" && $Security->canAdd();
        }
    }

    // Create new column option
    public function createColumnOption($name)
    {
        $field = $this->Fields[$name] ?? false;
        if ($field && $field->Visible) {
            $item = new ListOption($field->Name);
            $item->Body = '<button class="dropdown-item">' .
                '<div class="form-check ew-dropdown-checkbox">' .
                '<div class="form-check-input ew-dropdown-check-input" data-field="' . $field->Param . '"></div>' .
                '<label class="form-check-label ew-dropdown-check-label">' . $field->caption() . '</label></div></button>';
            return $item;
        }
        return null;
    }

    // Render other options
    public function renderOtherOptions()
    {
        global $Language, $Security;
        $options = &$this->OtherOptions;
            if (in_array($this->CurrentMode, ["add", "copy", "edit"]) && !$this->isConfirm()) { // Check add/copy/edit mode
                if ($this->AllowAddDeleteRow) {
                    $option = $options["addedit"];
                    $option->UseDropDownButton = false;
                    $item = &$option->add("addblankrow");
                    $item->Body = "<a class=\"ew-add-edit ew-add-blank-row\" title=\"" . HtmlTitle($Language->phrase("AddBlankRow")) . "\" data-caption=\"" . HtmlTitle($Language->phrase("AddBlankRow")) . "\" data-ew-action=\"add-grid-row\">" . $Language->phrase("AddBlankRow") . "</a>";
                    $item->Visible = $Security->canAdd();
                    $this->ShowOtherOptions = $item->Visible;
                }
            }
            if ($this->CurrentMode == "view") { // Check view mode
                $option = $options["addedit"];
                $item = $option["add"];
                $this->ShowOtherOptions = $item && $item->Visible;
            }
    }

    // Set up Grid
    public function setupGrid()
    {
        global $CurrentForm;
        $this->StartRecord = 1;
        $this->StopRecord = $this->TotalRecords; // Show all records

        // Restore number of post back records
        if ($CurrentForm && ($this->isConfirm() || $this->EventCancelled)) {
            $CurrentForm->resetIndex();
            if ($CurrentForm->hasValue($this->FormKeyCountName) && ($this->isGridAdd() || $this->isGridEdit() || $this->isConfirm())) {
                $this->KeyCount = $CurrentForm->getValue($this->FormKeyCountName);
                $this->StopRecord = $this->StartRecord + $this->KeyCount - 1;
            }
        }
        $this->RecordCount = $this->StartRecord - 1;
        if ($this->Recordset && !$this->Recordset->EOF) {
            // Nothing to do
        } elseif ($this->isGridAdd() && !$this->AllowAddDeleteRow && $this->StopRecord == 0) { // Grid-Add with no records
            $this->StopRecord = $this->GridAddRowCount;
        } elseif ($this->isAdd() && $this->TotalRecords == 0) { // Inline-Add with no records
            $this->StopRecord = 1;
        }

        // Initialize aggregate
        $this->RowType = ROWTYPE_AGGREGATEINIT;
        $this->resetAttributes();
        $this->renderRow();
        if (($this->isGridAdd() || $this->isGridEdit())) { // Render template row first
            $this->RowIndex = '$rowindex$';
        }
    }

    // Set up Row
    public function setupRow()
    {
        global $CurrentForm;
        if ($this->isGridAdd() || $this->isGridEdit()) {
            if ($this->RowIndex === '$rowindex$') { // Render template row first
                $this->loadRowValues();

                // Set row properties
                $this->resetAttributes();
                $this->RowAttrs->merge(["data-rowindex" => $this->RowIndex, "id" => "r0_products", "data-rowtype" => ROWTYPE_ADD]);
                $this->RowAttrs->appendClass("ew-template");
                // Render row
                $this->RowType = ROWTYPE_ADD;
                $this->renderRow();

                // Render list options
                $this->renderListOptions();

                // Reset record count for template row
                $this->RecordCount--;
                return;
            }
        }
        if ($this->isGridAdd() || $this->isGridEdit() || $this->isConfirm() || $this->isMultiEdit()) {
            $this->RowIndex++;
            $CurrentForm->Index = $this->RowIndex;
            if ($CurrentForm->hasValue($this->FormActionName) && ($this->isConfirm() || $this->EventCancelled)) {
                $this->RowAction = strval($CurrentForm->getValue($this->FormActionName));
            } elseif ($this->isGridAdd()) {
                $this->RowAction = "insert";
            } else {
                $this->RowAction = "";
            }
        }

        // Set up key count
        $this->KeyCount = $this->RowIndex;

        // Init row class and style
        $this->resetAttributes();
        $this->CssClass = "";
        if ($this->isGridAdd()) {
            if ($this->CurrentMode == "copy") {
                $this->loadRowValues($this->Recordset); // Load row values
                $this->OldKey = $this->getKey(true); // Get from CurrentValue
            } else {
                $this->loadRowValues(); // Load default values
                $this->OldKey = "";
            }
        } else {
            $this->loadRowValues($this->Recordset); // Load row values
            $this->OldKey = $this->getKey(true); // Get from CurrentValue
        }
        $this->setKey($this->OldKey);
        $this->RowType = ROWTYPE_VIEW; // Render view
        if (($this->isAdd() || $this->isCopy()) && $this->InlineRowCount == 0 || $this->isGridAdd()) { // Add
            $this->RowType = ROWTYPE_ADD; // Render add
        }
        if ($this->isGridAdd() && $this->EventCancelled && !$CurrentForm->hasValue($this->FormBlankRowName)) { // Insert failed
            $this->restoreCurrentRowFormValues($this->RowIndex); // Restore form values
        }
        if ($this->isGridEdit()) { // Grid edit
            if ($this->EventCancelled) {
                $this->restoreCurrentRowFormValues($this->RowIndex); // Restore form values
            }
            if ($this->RowAction == "insert") {
                $this->RowType = ROWTYPE_ADD; // Render add
            } else {
                $this->RowType = ROWTYPE_EDIT; // Render edit
            }
        }
        if ($this->isGridEdit() && ($this->RowType == ROWTYPE_EDIT || $this->RowType == ROWTYPE_ADD) && $this->EventCancelled) { // Update failed
            $this->restoreCurrentRowFormValues($this->RowIndex); // Restore form values
        }
        if ($this->isConfirm()) { // Confirm row
            $this->restoreCurrentRowFormValues($this->RowIndex); // Restore form values
        }

        // Inline Add/Copy row (row 0)
        if ($this->RowType == ROWTYPE_ADD && ($this->isAdd() || $this->isCopy())) {
            $this->InlineRowCount++;
            $this->RecordCount--; // Reset record count for inline add/copy row
            if ($this->TotalRecords == 0) { // Reset stop record if no records
                $this->StopRecord = 0;
            }
        } else {
            // Inline Edit row
            if ($this->RowType == ROWTYPE_EDIT && $this->isEdit()) {
                $this->InlineRowCount++;
            }
            $this->RowCount++; // Increment row count
        }

        // Set up row attributes
        $this->RowAttrs->merge([
            "data-rowindex" => $this->RowCount,
            "data-key" => $this->getKey(true),
            "id" => "r" . $this->RowCount . "_products",
            "data-rowtype" => $this->RowType,
            "data-inline" => ($this->isAdd() || $this->isCopy() || $this->isEdit()) ? "true" : "false", // Inline-Add/Copy/Edit
            "class" => ($this->RowCount % 2 != 1) ? "ew-table-alt-row" : "",
        ]);
        if ($this->isAdd() && $this->RowType == ROWTYPE_ADD || $this->isEdit() && $this->RowType == ROWTYPE_EDIT) { // Inline-Add/Edit row
            $this->RowAttrs->appendClass("table-active");
        }

        // Render row
        $this->renderRow();

        // Render list options
        $this->renderListOptions();
    }

    // Get upload files
    protected function getUploadFiles()
    {
        global $CurrentForm, $Language;
    }

    // Load default values
    protected function loadDefaultValues()
    {
        $this->Discontinued->DefaultValue = $this->Discontinued->getDefault(); // PHP
        $this->Discontinued->OldValue = $this->Discontinued->DefaultValue;
        $this->EAN13->DefaultValue = $this->EAN13->getDefault(); // PHP
        $this->EAN13->OldValue = $this->EAN13->DefaultValue;
    }

    // Load form values
    protected function loadFormValues()
    {
        // Load from form
        global $CurrentForm;
        $CurrentForm->FormName = $this->FormName;
        $validate = !Config("SERVER_VALIDATE");

        // Check field name 'ProductID' first before field var 'x_ProductID'
        $val = $CurrentForm->hasValue("ProductID") ? $CurrentForm->getValue("ProductID") : $CurrentForm->getValue("x_ProductID");
        if (!$this->ProductID->IsDetailKey && !$this->isGridAdd() && !$this->isAdd()) {
            $this->ProductID->setFormValue($val);
        }

        // Check field name 'ProductName' first before field var 'x_ProductName'
        $val = $CurrentForm->hasValue("ProductName") ? $CurrentForm->getValue("ProductName") : $CurrentForm->getValue("x_ProductName");
        if (!$this->ProductName->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->ProductName->Visible = false; // Disable update for API request
            } else {
                $this->ProductName->setFormValue($val);
            }
        }
        if ($CurrentForm->hasValue("o_ProductName")) {
            $this->ProductName->setOldValue($CurrentForm->getValue("o_ProductName"));
        }

        // Check field name 'SupplierID' first before field var 'x_SupplierID'
        $val = $CurrentForm->hasValue("SupplierID") ? $CurrentForm->getValue("SupplierID") : $CurrentForm->getValue("x_SupplierID");
        if (!$this->SupplierID->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->SupplierID->Visible = false; // Disable update for API request
            } else {
                $this->SupplierID->setFormValue($val);
            }
        }
        if ($CurrentForm->hasValue("o_SupplierID")) {
            $this->SupplierID->setOldValue($CurrentForm->getValue("o_SupplierID"));
        }

        // Check field name 'CategoryID' first before field var 'x_CategoryID'
        $val = $CurrentForm->hasValue("CategoryID") ? $CurrentForm->getValue("CategoryID") : $CurrentForm->getValue("x_CategoryID");
        if (!$this->CategoryID->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->CategoryID->Visible = false; // Disable update for API request
            } else {
                $this->CategoryID->setFormValue($val);
            }
        }
        if ($CurrentForm->hasValue("o_CategoryID")) {
            $this->CategoryID->setOldValue($CurrentForm->getValue("o_CategoryID"));
        }

        // Check field name 'QuantityPerUnit' first before field var 'x_QuantityPerUnit'
        $val = $CurrentForm->hasValue("QuantityPerUnit") ? $CurrentForm->getValue("QuantityPerUnit") : $CurrentForm->getValue("x_QuantityPerUnit");
        if (!$this->QuantityPerUnit->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->QuantityPerUnit->Visible = false; // Disable update for API request
            } else {
                $this->QuantityPerUnit->setFormValue($val);
            }
        }
        if ($CurrentForm->hasValue("o_QuantityPerUnit")) {
            $this->QuantityPerUnit->setOldValue($CurrentForm->getValue("o_QuantityPerUnit"));
        }

        // Check field name 'UnitPrice' first before field var 'x_UnitPrice'
        $val = $CurrentForm->hasValue("UnitPrice") ? $CurrentForm->getValue("UnitPrice") : $CurrentForm->getValue("x_UnitPrice");
        if (!$this->UnitPrice->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->UnitPrice->Visible = false; // Disable update for API request
            } else {
                $this->UnitPrice->setFormValue($val, true, $validate);
            }
        }
        if ($CurrentForm->hasValue("o_UnitPrice")) {
            $this->UnitPrice->setOldValue($CurrentForm->getValue("o_UnitPrice"));
        }

        // Check field name 'UnitsInStock' first before field var 'x_UnitsInStock'
        $val = $CurrentForm->hasValue("UnitsInStock") ? $CurrentForm->getValue("UnitsInStock") : $CurrentForm->getValue("x_UnitsInStock");
        if (!$this->UnitsInStock->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->UnitsInStock->Visible = false; // Disable update for API request
            } else {
                $this->UnitsInStock->setFormValue($val, true, $validate);
            }
        }
        if ($CurrentForm->hasValue("o_UnitsInStock")) {
            $this->UnitsInStock->setOldValue($CurrentForm->getValue("o_UnitsInStock"));
        }

        // Check field name 'UnitsOnOrder' first before field var 'x_UnitsOnOrder'
        $val = $CurrentForm->hasValue("UnitsOnOrder") ? $CurrentForm->getValue("UnitsOnOrder") : $CurrentForm->getValue("x_UnitsOnOrder");
        if (!$this->UnitsOnOrder->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->UnitsOnOrder->Visible = false; // Disable update for API request
            } else {
                $this->UnitsOnOrder->setFormValue($val, true, $validate);
            }
        }
        if ($CurrentForm->hasValue("o_UnitsOnOrder")) {
            $this->UnitsOnOrder->setOldValue($CurrentForm->getValue("o_UnitsOnOrder"));
        }

        // Check field name 'ReorderLevel' first before field var 'x_ReorderLevel'
        $val = $CurrentForm->hasValue("ReorderLevel") ? $CurrentForm->getValue("ReorderLevel") : $CurrentForm->getValue("x_ReorderLevel");
        if (!$this->ReorderLevel->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->ReorderLevel->Visible = false; // Disable update for API request
            } else {
                $this->ReorderLevel->setFormValue($val, true, $validate);
            }
        }
        if ($CurrentForm->hasValue("o_ReorderLevel")) {
            $this->ReorderLevel->setOldValue($CurrentForm->getValue("o_ReorderLevel"));
        }

        // Check field name 'Discontinued' first before field var 'x_Discontinued'
        $val = $CurrentForm->hasValue("Discontinued") ? $CurrentForm->getValue("Discontinued") : $CurrentForm->getValue("x_Discontinued");
        if (!$this->Discontinued->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->Discontinued->Visible = false; // Disable update for API request
            } else {
                $this->Discontinued->setFormValue($val);
            }
        }
        if ($CurrentForm->hasValue("o_Discontinued")) {
            $this->Discontinued->setOldValue($CurrentForm->getValue("o_Discontinued"));
        }

        // Check field name 'EAN13' first before field var 'x_EAN13'
        $val = $CurrentForm->hasValue("EAN13") ? $CurrentForm->getValue("EAN13") : $CurrentForm->getValue("x_EAN13");
        if (!$this->EAN13->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->EAN13->Visible = false; // Disable update for API request
            } else {
                $this->EAN13->setFormValue($val);
            }
        }
        if ($CurrentForm->hasValue("o_EAN13")) {
            $this->EAN13->setOldValue($CurrentForm->getValue("o_EAN13"));
        }
    }

    // Restore form values
    public function restoreFormValues()
    {
        global $CurrentForm;
        if (!$this->isGridAdd() && !$this->isAdd()) {
            $this->ProductID->CurrentValue = $this->ProductID->FormValue;
        }
        $this->ProductName->CurrentValue = $this->ProductName->FormValue;
        $this->SupplierID->CurrentValue = $this->SupplierID->FormValue;
        $this->CategoryID->CurrentValue = $this->CategoryID->FormValue;
        $this->QuantityPerUnit->CurrentValue = $this->QuantityPerUnit->FormValue;
        $this->UnitPrice->CurrentValue = $this->UnitPrice->FormValue;
        $this->UnitsInStock->CurrentValue = $this->UnitsInStock->FormValue;
        $this->UnitsOnOrder->CurrentValue = $this->UnitsOnOrder->FormValue;
        $this->ReorderLevel->CurrentValue = $this->ReorderLevel->FormValue;
        $this->Discontinued->CurrentValue = $this->Discontinued->FormValue;
        $this->EAN13->CurrentValue = $this->EAN13->FormValue;
    }

    // Load recordset
    public function loadRecordset($offset = -1, $rowcnt = -1)
    {
        // Load List page SQL (QueryBuilder)
        $sql = $this->getListSql();

        // Load recordset
        if ($offset > -1) {
            $sql->setFirstResult($offset);
        }
        if ($rowcnt > 0) {
            $sql->setMaxResults($rowcnt);
        }
        $result = $sql->execute();
        $rs = new Recordset($result, $sql);

        // Call Recordset Selected event
        $this->recordsetSelected($rs);
        return $rs;
    }

    // Load records as associative array
    public function loadRows($offset = -1, $rowcnt = -1)
    {
        // Load List page SQL (QueryBuilder)
        $sql = $this->getListSql();

        // Load recordset
        if ($offset > -1) {
            $sql->setFirstResult($offset);
        }
        if ($rowcnt > 0) {
            $sql->setMaxResults($rowcnt);
        }
        $result = $sql->execute();
        return $result->fetchAllAssociative();
    }

    /**
     * Load row based on key values
     *
     * @return void
     */
    public function loadRow()
    {
        global $Security, $Language;
        $filter = $this->getRecordFilter();

        // Call Row Selecting event
        $this->rowSelecting($filter);

        // Load SQL based on filter
        $this->CurrentFilter = $filter;
        $sql = $this->getCurrentSql();
        $conn = $this->getConnection();
        $res = false;
        $row = $conn->fetchAssociative($sql);
        if ($row) {
            $res = true;
            $this->loadRowValues($row); // Load row values
        }
        return $res;
    }

    /**
     * Load row values from recordset or record
     *
     * @param Recordset|array $rs Record
     * @return void
     */
    public function loadRowValues($rs = null)
    {
        if (is_array($rs)) {
            $row = $rs;
        } elseif ($rs && property_exists($rs, "fields")) { // Recordset
            $row = $rs->fields;
        } else {
            $row = $this->newRow();
        }
        if (!$row) {
            return;
        }

        // Call Row Selected event
        $this->rowSelected($row);
        $this->ProductID->setDbValue($row['ProductID']);
        $this->ProductName->setDbValue($row['ProductName']);
        $this->SupplierID->setDbValue($row['SupplierID']);
        $this->CategoryID->setDbValue($row['CategoryID']);
        $this->QuantityPerUnit->setDbValue($row['QuantityPerUnit']);
        $this->UnitPrice->setDbValue($row['UnitPrice']);
        $this->UnitsInStock->setDbValue($row['UnitsInStock']);
        $this->UnitsOnOrder->setDbValue($row['UnitsOnOrder']);
        $this->ReorderLevel->setDbValue($row['ReorderLevel']);
        $this->Discontinued->setDbValue($row['Discontinued']);
        $this->EAN13->setDbValue($row['EAN13']);
    }

    // Return a row with default values
    protected function newRow()
    {
        $row = [];
        $row['ProductID'] = $this->ProductID->DefaultValue;
        $row['ProductName'] = $this->ProductName->DefaultValue;
        $row['SupplierID'] = $this->SupplierID->DefaultValue;
        $row['CategoryID'] = $this->CategoryID->DefaultValue;
        $row['QuantityPerUnit'] = $this->QuantityPerUnit->DefaultValue;
        $row['UnitPrice'] = $this->UnitPrice->DefaultValue;
        $row['UnitsInStock'] = $this->UnitsInStock->DefaultValue;
        $row['UnitsOnOrder'] = $this->UnitsOnOrder->DefaultValue;
        $row['ReorderLevel'] = $this->ReorderLevel->DefaultValue;
        $row['Discontinued'] = $this->Discontinued->DefaultValue;
        $row['EAN13'] = $this->EAN13->DefaultValue;
        return $row;
    }

    // Load old record
    protected function loadOldRecord()
    {
        // Load old record
        if ($this->OldKey != "") {
            $this->setKey($this->OldKey);
            $this->CurrentFilter = $this->getRecordFilter();
            $sql = $this->getCurrentSql();
            $conn = $this->getConnection();
            $rs = LoadRecordset($sql, $conn);
            if ($rs && ($row = $rs->fields)) {
                $this->loadRowValues($row); // Load row values
                return $row;
            }
        }
        $this->loadRowValues(); // Load default row values
        return null;
    }

    // Render row values based on field settings
    public function renderRow()
    {
        global $Security, $Language, $CurrentLanguage;

        // Initialize URLs
        $this->ViewUrl = $this->getViewUrl();
        $this->EditUrl = $this->getEditUrl();
        $this->CopyUrl = $this->getCopyUrl();
        $this->DeleteUrl = $this->getDeleteUrl();

        // Call Row_Rendering event
        $this->rowRendering();

        // Common render codes for all row types

        // ProductID

        // ProductName

        // SupplierID

        // CategoryID

        // QuantityPerUnit

        // UnitPrice

        // UnitsInStock

        // UnitsOnOrder

        // ReorderLevel

        // Discontinued

        // EAN13

        // View row
        if ($this->RowType == ROWTYPE_VIEW) {
            // ProductID
            $this->ProductID->ViewValue = $this->ProductID->CurrentValue;

            // ProductName
            $this->ProductName->ViewValue = $this->ProductName->CurrentValue;

            // SupplierID
            $curVal = strval($this->SupplierID->CurrentValue);
            if ($curVal != "") {
                $this->SupplierID->ViewValue = $this->SupplierID->lookupCacheOption($curVal);
                if ($this->SupplierID->ViewValue === null) { // Lookup from database
                    $filterWrk = SearchFilter("`SupplierID`", "=", $curVal, DATATYPE_NUMBER, "");
                    $sqlWrk = $this->SupplierID->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCacheImpl($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->SupplierID->Lookup->renderViewRow($rswrk[0]);
                        $this->SupplierID->ViewValue = $this->SupplierID->displayValue($arwrk);
                    } else {
                        $this->SupplierID->ViewValue = $this->SupplierID->CurrentValue;
                    }
                }
            } else {
                $this->SupplierID->ViewValue = null;
            }

            // CategoryID
            $curVal = strval($this->CategoryID->CurrentValue);
            if ($curVal != "") {
                $this->CategoryID->ViewValue = $this->CategoryID->lookupCacheOption($curVal);
                if ($this->CategoryID->ViewValue === null) { // Lookup from database
                    $filterWrk = SearchFilter("`CategoryID`", "=", $curVal, DATATYPE_NUMBER, "");
                    $sqlWrk = $this->CategoryID->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCacheImpl($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->CategoryID->Lookup->renderViewRow($rswrk[0]);
                        $this->CategoryID->ViewValue = $this->CategoryID->displayValue($arwrk);
                    } else {
                        $this->CategoryID->ViewValue = $this->CategoryID->CurrentValue;
                    }
                }
            } else {
                $this->CategoryID->ViewValue = null;
            }

            // QuantityPerUnit
            $this->QuantityPerUnit->ViewValue = $this->QuantityPerUnit->CurrentValue;

            // UnitPrice
            $this->UnitPrice->ViewValue = $this->UnitPrice->CurrentValue;
            $this->UnitPrice->ViewValue = FormatCurrency($this->UnitPrice->ViewValue, $this->UnitPrice->formatPattern());

            // UnitsInStock
            $this->UnitsInStock->ViewValue = $this->UnitsInStock->CurrentValue;

            // UnitsOnOrder
            $this->UnitsOnOrder->ViewValue = $this->UnitsOnOrder->CurrentValue;

            // ReorderLevel
            $this->ReorderLevel->ViewValue = $this->ReorderLevel->CurrentValue;

            // Discontinued
            if (ConvertToBool($this->Discontinued->CurrentValue)) {
                $this->Discontinued->ViewValue = $this->Discontinued->tagCaption(1) != "" ? $this->Discontinued->tagCaption(1) : "Yes";
            } else {
                $this->Discontinued->ViewValue = $this->Discontinued->tagCaption(2) != "" ? $this->Discontinued->tagCaption(2) : "No";
            }

            // EAN13
            $this->EAN13->ViewValue = $this->EAN13->CurrentValue;

            // ProductID
            $this->ProductID->HrefValue = "";
            $this->ProductID->TooltipValue = "";

            // ProductName
            $this->ProductName->HrefValue = "";
            $this->ProductName->TooltipValue = "";

            // SupplierID
            $this->SupplierID->HrefValue = "";
            $this->SupplierID->TooltipValue = "";

            // CategoryID
            $this->CategoryID->HrefValue = "";
            $this->CategoryID->TooltipValue = "";

            // QuantityPerUnit
            $this->QuantityPerUnit->HrefValue = "";
            $this->QuantityPerUnit->TooltipValue = "";

            // UnitPrice
            $this->UnitPrice->HrefValue = "";
            $this->UnitPrice->TooltipValue = "";

            // UnitsInStock
            $this->UnitsInStock->HrefValue = "";
            $this->UnitsInStock->TooltipValue = "";

            // UnitsOnOrder
            $this->UnitsOnOrder->HrefValue = "";
            $this->UnitsOnOrder->TooltipValue = "";

            // ReorderLevel
            $this->ReorderLevel->HrefValue = "";
            $this->ReorderLevel->TooltipValue = "";

            // Discontinued
            $this->Discontinued->HrefValue = "";
            $this->Discontinued->TooltipValue = "";

            // EAN13
            $this->EAN13->HrefValue = "";
            $this->EAN13->ExportHrefValue = PhpBarcode::barcode(true)->getHrefValue($this->EAN13->CurrentValue, 'EAN-13', 60);
            $this->EAN13->TooltipValue = "";
        } elseif ($this->RowType == ROWTYPE_ADD) {
            // ProductID

            // ProductName
            $this->ProductName->setupEditAttributes();
            if (!$this->ProductName->Raw) {
                $this->ProductName->CurrentValue = HtmlDecode($this->ProductName->CurrentValue);
            }
            $this->ProductName->EditValue = HtmlEncode($this->ProductName->CurrentValue);
            $this->ProductName->PlaceHolder = RemoveHtml($this->ProductName->caption());

            // SupplierID
            $curVal = trim(strval($this->SupplierID->CurrentValue));
            if ($curVal != "") {
                $this->SupplierID->ViewValue = $this->SupplierID->lookupCacheOption($curVal);
            } else {
                $this->SupplierID->ViewValue = $this->SupplierID->Lookup !== null && is_array($this->SupplierID->lookupOptions()) && count($this->SupplierID->lookupOptions()) > 0 ? $curVal : null;
            }
            if ($this->SupplierID->ViewValue !== null) { // Load from cache
                $this->SupplierID->EditValue = array_values($this->SupplierID->lookupOptions());
                if ($this->SupplierID->ViewValue == "") {
                    $this->SupplierID->ViewValue = $Language->phrase("PleaseSelect");
                }
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = SearchFilter("`SupplierID`", "=", $this->SupplierID->CurrentValue, DATATYPE_NUMBER, "");
                }
                $sqlWrk = $this->SupplierID->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCacheImpl($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                if ($ari > 0) { // Lookup values found
                    $arwrk = $this->SupplierID->Lookup->renderViewRow($rswrk[0]);
                    $this->SupplierID->ViewValue = $this->SupplierID->displayValue($arwrk);
                } else {
                    $this->SupplierID->ViewValue = $Language->phrase("PleaseSelect");
                }
                $arwrk = $rswrk;
                $this->SupplierID->EditValue = $arwrk;
            }
            $this->SupplierID->PlaceHolder = RemoveHtml($this->SupplierID->caption());

            // CategoryID
            $this->CategoryID->setupEditAttributes();
            if ($this->CategoryID->getSessionValue() != "") {
                $this->CategoryID->CurrentValue = GetForeignKeyValue($this->CategoryID->getSessionValue());
                $this->CategoryID->OldValue = $this->CategoryID->CurrentValue;
                $curVal = strval($this->CategoryID->CurrentValue);
                if ($curVal != "") {
                    $this->CategoryID->ViewValue = $this->CategoryID->lookupCacheOption($curVal);
                    if ($this->CategoryID->ViewValue === null) { // Lookup from database
                        $filterWrk = SearchFilter("`CategoryID`", "=", $curVal, DATATYPE_NUMBER, "");
                        $sqlWrk = $this->CategoryID->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                        $conn = Conn();
                        $config = $conn->getConfiguration();
                        $config->setResultCacheImpl($this->Cache);
                        $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                        $ari = count($rswrk);
                        if ($ari > 0) { // Lookup values found
                            $arwrk = $this->CategoryID->Lookup->renderViewRow($rswrk[0]);
                            $this->CategoryID->ViewValue = $this->CategoryID->displayValue($arwrk);
                        } else {
                            $this->CategoryID->ViewValue = $this->CategoryID->CurrentValue;
                        }
                    }
                } else {
                    $this->CategoryID->ViewValue = null;
                }
            } else {
                $curVal = trim(strval($this->CategoryID->CurrentValue));
                if ($curVal != "") {
                    $this->CategoryID->ViewValue = $this->CategoryID->lookupCacheOption($curVal);
                } else {
                    $this->CategoryID->ViewValue = $this->CategoryID->Lookup !== null && is_array($this->CategoryID->lookupOptions()) && count($this->CategoryID->lookupOptions()) > 0 ? $curVal : null;
                }
                if ($this->CategoryID->ViewValue !== null) { // Load from cache
                    $this->CategoryID->EditValue = array_values($this->CategoryID->lookupOptions());
                } else { // Lookup from database
                    if ($curVal == "") {
                        $filterWrk = "0=1";
                    } else {
                        $filterWrk = SearchFilter("`CategoryID`", "=", $this->CategoryID->CurrentValue, DATATYPE_NUMBER, "");
                    }
                    $sqlWrk = $this->CategoryID->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCacheImpl($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    $arwrk = $rswrk;
                    $this->CategoryID->EditValue = $arwrk;
                }
                $this->CategoryID->PlaceHolder = RemoveHtml($this->CategoryID->caption());
            }

            // QuantityPerUnit
            $this->QuantityPerUnit->setupEditAttributes();
            if (!$this->QuantityPerUnit->Raw) {
                $this->QuantityPerUnit->CurrentValue = HtmlDecode($this->QuantityPerUnit->CurrentValue);
            }
            $this->QuantityPerUnit->EditValue = HtmlEncode($this->QuantityPerUnit->CurrentValue);
            $this->QuantityPerUnit->PlaceHolder = RemoveHtml($this->QuantityPerUnit->caption());

            // UnitPrice
            $this->UnitPrice->setupEditAttributes();
            $this->UnitPrice->EditValue = HtmlEncode($this->UnitPrice->CurrentValue);
            $this->UnitPrice->PlaceHolder = RemoveHtml($this->UnitPrice->caption());
            if (strval($this->UnitPrice->EditValue) != "" && is_numeric($this->UnitPrice->EditValue)) {
                $this->UnitPrice->EditValue = FormatNumber($this->UnitPrice->EditValue, null);
            }

            // UnitsInStock
            $this->UnitsInStock->setupEditAttributes();
            $this->UnitsInStock->EditValue = HtmlEncode($this->UnitsInStock->CurrentValue);
            $this->UnitsInStock->PlaceHolder = RemoveHtml($this->UnitsInStock->caption());
            if (strval($this->UnitsInStock->EditValue) != "" && is_numeric($this->UnitsInStock->EditValue)) {
                $this->UnitsInStock->EditValue = $this->UnitsInStock->EditValue;
            }

            // UnitsOnOrder
            $this->UnitsOnOrder->setupEditAttributes();
            $this->UnitsOnOrder->EditValue = HtmlEncode($this->UnitsOnOrder->CurrentValue);
            $this->UnitsOnOrder->PlaceHolder = RemoveHtml($this->UnitsOnOrder->caption());
            if (strval($this->UnitsOnOrder->EditValue) != "" && is_numeric($this->UnitsOnOrder->EditValue)) {
                $this->UnitsOnOrder->EditValue = $this->UnitsOnOrder->EditValue;
            }

            // ReorderLevel
            $this->ReorderLevel->setupEditAttributes();
            $this->ReorderLevel->EditValue = HtmlEncode($this->ReorderLevel->CurrentValue);
            $this->ReorderLevel->PlaceHolder = RemoveHtml($this->ReorderLevel->caption());
            if (strval($this->ReorderLevel->EditValue) != "" && is_numeric($this->ReorderLevel->EditValue)) {
                $this->ReorderLevel->EditValue = $this->ReorderLevel->EditValue;
            }

            // Discontinued
            $this->Discontinued->EditValue = $this->Discontinued->options(false);
            $this->Discontinued->PlaceHolder = RemoveHtml($this->Discontinued->caption());

            // EAN13
            $this->EAN13->setupEditAttributes();
            if (!$this->EAN13->Raw) {
                $this->EAN13->CurrentValue = HtmlDecode($this->EAN13->CurrentValue);
            }
            $this->EAN13->EditValue = HtmlEncode($this->EAN13->CurrentValue);
            $this->EAN13->PlaceHolder = RemoveHtml($this->EAN13->caption());

            // Add refer script

            // ProductID
            $this->ProductID->HrefValue = "";

            // ProductName
            $this->ProductName->HrefValue = "";

            // SupplierID
            $this->SupplierID->HrefValue = "";

            // CategoryID
            $this->CategoryID->HrefValue = "";

            // QuantityPerUnit
            $this->QuantityPerUnit->HrefValue = "";

            // UnitPrice
            $this->UnitPrice->HrefValue = "";

            // UnitsInStock
            $this->UnitsInStock->HrefValue = "";

            // UnitsOnOrder
            $this->UnitsOnOrder->HrefValue = "";

            // ReorderLevel
            $this->ReorderLevel->HrefValue = "";

            // Discontinued
            $this->Discontinued->HrefValue = "";

            // EAN13
            $this->EAN13->HrefValue = "";
            $this->EAN13->ExportHrefValue = PhpBarcode::barcode(true)->getHrefValue($this->EAN13->CurrentValue, 'EAN-13', 60);
        } elseif ($this->RowType == ROWTYPE_EDIT) {
            // ProductID
            $this->ProductID->setupEditAttributes();
            $this->ProductID->EditValue = $this->ProductID->CurrentValue;

            // ProductName
            $this->ProductName->setupEditAttributes();
            if (!$this->ProductName->Raw) {
                $this->ProductName->CurrentValue = HtmlDecode($this->ProductName->CurrentValue);
            }
            $this->ProductName->EditValue = HtmlEncode($this->ProductName->CurrentValue);
            $this->ProductName->PlaceHolder = RemoveHtml($this->ProductName->caption());

            // SupplierID
            $curVal = trim(strval($this->SupplierID->CurrentValue));
            if ($curVal != "") {
                $this->SupplierID->ViewValue = $this->SupplierID->lookupCacheOption($curVal);
            } else {
                $this->SupplierID->ViewValue = $this->SupplierID->Lookup !== null && is_array($this->SupplierID->lookupOptions()) && count($this->SupplierID->lookupOptions()) > 0 ? $curVal : null;
            }
            if ($this->SupplierID->ViewValue !== null) { // Load from cache
                $this->SupplierID->EditValue = array_values($this->SupplierID->lookupOptions());
                if ($this->SupplierID->ViewValue == "") {
                    $this->SupplierID->ViewValue = $Language->phrase("PleaseSelect");
                }
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = SearchFilter("`SupplierID`", "=", $this->SupplierID->CurrentValue, DATATYPE_NUMBER, "");
                }
                $sqlWrk = $this->SupplierID->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCacheImpl($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                if ($ari > 0) { // Lookup values found
                    $arwrk = $this->SupplierID->Lookup->renderViewRow($rswrk[0]);
                    $this->SupplierID->ViewValue = $this->SupplierID->displayValue($arwrk);
                } else {
                    $this->SupplierID->ViewValue = $Language->phrase("PleaseSelect");
                }
                $arwrk = $rswrk;
                $this->SupplierID->EditValue = $arwrk;
            }
            $this->SupplierID->PlaceHolder = RemoveHtml($this->SupplierID->caption());

            // CategoryID
            $this->CategoryID->setupEditAttributes();
            if ($this->CategoryID->getSessionValue() != "") {
                $this->CategoryID->CurrentValue = GetForeignKeyValue($this->CategoryID->getSessionValue());
                $this->CategoryID->OldValue = $this->CategoryID->CurrentValue;
                $curVal = strval($this->CategoryID->CurrentValue);
                if ($curVal != "") {
                    $this->CategoryID->ViewValue = $this->CategoryID->lookupCacheOption($curVal);
                    if ($this->CategoryID->ViewValue === null) { // Lookup from database
                        $filterWrk = SearchFilter("`CategoryID`", "=", $curVal, DATATYPE_NUMBER, "");
                        $sqlWrk = $this->CategoryID->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                        $conn = Conn();
                        $config = $conn->getConfiguration();
                        $config->setResultCacheImpl($this->Cache);
                        $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                        $ari = count($rswrk);
                        if ($ari > 0) { // Lookup values found
                            $arwrk = $this->CategoryID->Lookup->renderViewRow($rswrk[0]);
                            $this->CategoryID->ViewValue = $this->CategoryID->displayValue($arwrk);
                        } else {
                            $this->CategoryID->ViewValue = $this->CategoryID->CurrentValue;
                        }
                    }
                } else {
                    $this->CategoryID->ViewValue = null;
                }
            } else {
                $curVal = trim(strval($this->CategoryID->CurrentValue));
                if ($curVal != "") {
                    $this->CategoryID->ViewValue = $this->CategoryID->lookupCacheOption($curVal);
                } else {
                    $this->CategoryID->ViewValue = $this->CategoryID->Lookup !== null && is_array($this->CategoryID->lookupOptions()) && count($this->CategoryID->lookupOptions()) > 0 ? $curVal : null;
                }
                if ($this->CategoryID->ViewValue !== null) { // Load from cache
                    $this->CategoryID->EditValue = array_values($this->CategoryID->lookupOptions());
                } else { // Lookup from database
                    if ($curVal == "") {
                        $filterWrk = "0=1";
                    } else {
                        $filterWrk = SearchFilter("`CategoryID`", "=", $this->CategoryID->CurrentValue, DATATYPE_NUMBER, "");
                    }
                    $sqlWrk = $this->CategoryID->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCacheImpl($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    $arwrk = $rswrk;
                    $this->CategoryID->EditValue = $arwrk;
                }
                $this->CategoryID->PlaceHolder = RemoveHtml($this->CategoryID->caption());
            }

            // QuantityPerUnit
            $this->QuantityPerUnit->setupEditAttributes();
            if (!$this->QuantityPerUnit->Raw) {
                $this->QuantityPerUnit->CurrentValue = HtmlDecode($this->QuantityPerUnit->CurrentValue);
            }
            $this->QuantityPerUnit->EditValue = HtmlEncode($this->QuantityPerUnit->CurrentValue);
            $this->QuantityPerUnit->PlaceHolder = RemoveHtml($this->QuantityPerUnit->caption());

            // UnitPrice
            $this->UnitPrice->setupEditAttributes();
            $this->UnitPrice->EditValue = HtmlEncode($this->UnitPrice->CurrentValue);
            $this->UnitPrice->PlaceHolder = RemoveHtml($this->UnitPrice->caption());
            if (strval($this->UnitPrice->EditValue) != "" && is_numeric($this->UnitPrice->EditValue)) {
                $this->UnitPrice->EditValue = FormatNumber($this->UnitPrice->EditValue, null);
            }

            // UnitsInStock
            $this->UnitsInStock->setupEditAttributes();
            $this->UnitsInStock->EditValue = HtmlEncode($this->UnitsInStock->CurrentValue);
            $this->UnitsInStock->PlaceHolder = RemoveHtml($this->UnitsInStock->caption());
            if (strval($this->UnitsInStock->EditValue) != "" && is_numeric($this->UnitsInStock->EditValue)) {
                $this->UnitsInStock->EditValue = $this->UnitsInStock->EditValue;
            }

            // UnitsOnOrder
            $this->UnitsOnOrder->setupEditAttributes();
            $this->UnitsOnOrder->EditValue = HtmlEncode($this->UnitsOnOrder->CurrentValue);
            $this->UnitsOnOrder->PlaceHolder = RemoveHtml($this->UnitsOnOrder->caption());
            if (strval($this->UnitsOnOrder->EditValue) != "" && is_numeric($this->UnitsOnOrder->EditValue)) {
                $this->UnitsOnOrder->EditValue = $this->UnitsOnOrder->EditValue;
            }

            // ReorderLevel
            $this->ReorderLevel->setupEditAttributes();
            $this->ReorderLevel->EditValue = HtmlEncode($this->ReorderLevel->CurrentValue);
            $this->ReorderLevel->PlaceHolder = RemoveHtml($this->ReorderLevel->caption());
            if (strval($this->ReorderLevel->EditValue) != "" && is_numeric($this->ReorderLevel->EditValue)) {
                $this->ReorderLevel->EditValue = $this->ReorderLevel->EditValue;
            }

            // Discontinued
            $this->Discontinued->EditValue = $this->Discontinued->options(false);
            $this->Discontinued->PlaceHolder = RemoveHtml($this->Discontinued->caption());

            // EAN13
            $this->EAN13->setupEditAttributes();
            if (!$this->EAN13->Raw) {
                $this->EAN13->CurrentValue = HtmlDecode($this->EAN13->CurrentValue);
            }
            $this->EAN13->EditValue = HtmlEncode($this->EAN13->CurrentValue);
            $this->EAN13->PlaceHolder = RemoveHtml($this->EAN13->caption());

            // Edit refer script

            // ProductID
            $this->ProductID->HrefValue = "";

            // ProductName
            $this->ProductName->HrefValue = "";

            // SupplierID
            $this->SupplierID->HrefValue = "";

            // CategoryID
            $this->CategoryID->HrefValue = "";

            // QuantityPerUnit
            $this->QuantityPerUnit->HrefValue = "";

            // UnitPrice
            $this->UnitPrice->HrefValue = "";

            // UnitsInStock
            $this->UnitsInStock->HrefValue = "";

            // UnitsOnOrder
            $this->UnitsOnOrder->HrefValue = "";

            // ReorderLevel
            $this->ReorderLevel->HrefValue = "";

            // Discontinued
            $this->Discontinued->HrefValue = "";

            // EAN13
            $this->EAN13->HrefValue = "";
            $this->EAN13->ExportHrefValue = PhpBarcode::barcode(true)->getHrefValue($this->EAN13->CurrentValue, 'EAN-13', 60);
        }
        if ($this->RowType == ROWTYPE_ADD || $this->RowType == ROWTYPE_EDIT || $this->RowType == ROWTYPE_SEARCH) { // Add/Edit/Search row
            $this->setupFieldTitles();
        }

        // Call Row Rendered event
        if ($this->RowType != ROWTYPE_AGGREGATEINIT) {
            $this->rowRendered();
        }
    }

    // Validate form
    protected function validateForm()
    {
        global $Language, $Security;

        // Check if validation required
        if (!Config("SERVER_VALIDATE")) {
            return true;
        }
        $validateForm = true;
        if ($this->ProductID->Visible && $this->ProductID->Required) {
            if (!$this->ProductID->IsDetailKey && EmptyValue($this->ProductID->FormValue)) {
                $this->ProductID->addErrorMessage(str_replace("%s", $this->ProductID->caption(), $this->ProductID->RequiredErrorMessage));
            }
        }
        if ($this->ProductName->Visible && $this->ProductName->Required) {
            if (!$this->ProductName->IsDetailKey && EmptyValue($this->ProductName->FormValue)) {
                $this->ProductName->addErrorMessage(str_replace("%s", $this->ProductName->caption(), $this->ProductName->RequiredErrorMessage));
            }
        }
        if ($this->SupplierID->Visible && $this->SupplierID->Required) {
            if (!$this->SupplierID->IsDetailKey && EmptyValue($this->SupplierID->FormValue)) {
                $this->SupplierID->addErrorMessage(str_replace("%s", $this->SupplierID->caption(), $this->SupplierID->RequiredErrorMessage));
            }
        }
        if ($this->CategoryID->Visible && $this->CategoryID->Required) {
            if (!$this->CategoryID->IsDetailKey && EmptyValue($this->CategoryID->FormValue)) {
                $this->CategoryID->addErrorMessage(str_replace("%s", $this->CategoryID->caption(), $this->CategoryID->RequiredErrorMessage));
            }
        }
        if ($this->QuantityPerUnit->Visible && $this->QuantityPerUnit->Required) {
            if (!$this->QuantityPerUnit->IsDetailKey && EmptyValue($this->QuantityPerUnit->FormValue)) {
                $this->QuantityPerUnit->addErrorMessage(str_replace("%s", $this->QuantityPerUnit->caption(), $this->QuantityPerUnit->RequiredErrorMessage));
            }
        }
        if ($this->UnitPrice->Visible && $this->UnitPrice->Required) {
            if (!$this->UnitPrice->IsDetailKey && EmptyValue($this->UnitPrice->FormValue)) {
                $this->UnitPrice->addErrorMessage(str_replace("%s", $this->UnitPrice->caption(), $this->UnitPrice->RequiredErrorMessage));
            }
        }
        if (!CheckNumber($this->UnitPrice->FormValue)) {
            $this->UnitPrice->addErrorMessage($this->UnitPrice->getErrorMessage(false));
        }
        if ($this->UnitsInStock->Visible && $this->UnitsInStock->Required) {
            if (!$this->UnitsInStock->IsDetailKey && EmptyValue($this->UnitsInStock->FormValue)) {
                $this->UnitsInStock->addErrorMessage(str_replace("%s", $this->UnitsInStock->caption(), $this->UnitsInStock->RequiredErrorMessage));
            }
        }
        if (!CheckInteger($this->UnitsInStock->FormValue)) {
            $this->UnitsInStock->addErrorMessage($this->UnitsInStock->getErrorMessage(false));
        }
        if ($this->UnitsOnOrder->Visible && $this->UnitsOnOrder->Required) {
            if (!$this->UnitsOnOrder->IsDetailKey && EmptyValue($this->UnitsOnOrder->FormValue)) {
                $this->UnitsOnOrder->addErrorMessage(str_replace("%s", $this->UnitsOnOrder->caption(), $this->UnitsOnOrder->RequiredErrorMessage));
            }
        }
        if (!CheckInteger($this->UnitsOnOrder->FormValue)) {
            $this->UnitsOnOrder->addErrorMessage($this->UnitsOnOrder->getErrorMessage(false));
        }
        if ($this->ReorderLevel->Visible && $this->ReorderLevel->Required) {
            if (!$this->ReorderLevel->IsDetailKey && EmptyValue($this->ReorderLevel->FormValue)) {
                $this->ReorderLevel->addErrorMessage(str_replace("%s", $this->ReorderLevel->caption(), $this->ReorderLevel->RequiredErrorMessage));
            }
        }
        if (!CheckInteger($this->ReorderLevel->FormValue)) {
            $this->ReorderLevel->addErrorMessage($this->ReorderLevel->getErrorMessage(false));
        }
        if ($this->Discontinued->Visible && $this->Discontinued->Required) {
            if ($this->Discontinued->FormValue == "") {
                $this->Discontinued->addErrorMessage(str_replace("%s", $this->Discontinued->caption(), $this->Discontinued->RequiredErrorMessage));
            }
        }
        if ($this->EAN13->Visible && $this->EAN13->Required) {
            if (!$this->EAN13->IsDetailKey && EmptyValue($this->EAN13->FormValue)) {
                $this->EAN13->addErrorMessage(str_replace("%s", $this->EAN13->caption(), $this->EAN13->RequiredErrorMessage));
            }
        }

        // Return validate result
        $validateForm = $validateForm && !$this->hasInvalidFields();

        // Call Form_CustomValidate event
        $formCustomError = "";
        $validateForm = $validateForm && $this->formCustomValidate($formCustomError);
        if ($formCustomError != "") {
            $this->setFailureMessage($formCustomError);
        }
        return $validateForm;
    }

    // Delete records based on current filter
    protected function deleteRows()
    {
        global $Language, $Security;
        if (!$Security->canDelete()) {
            $this->setFailureMessage($Language->phrase("NoDeletePermission")); // No delete permission
            return false;
        }
        $sql = $this->getCurrentSql();
        $conn = $this->getConnection();
        $rows = $conn->fetchAllAssociative($sql);
        if (count($rows) == 0) {
            $this->setFailureMessage($Language->phrase("NoRecord")); // No record found
            return false;
        }

        // Clone old rows
        $rsold = $rows;
        $successKeys = [];
        $failKeys = [];
        foreach ($rsold as $row) {
            $thisKey = "";
            if ($thisKey != "") {
                $thisKey .= Config("COMPOSITE_KEY_SEPARATOR");
            }
            $thisKey .= $row['ProductID'];

            // Call row deleting event
            $deleteRow = $this->rowDeleting($row);
            if ($deleteRow) { // Delete
                $deleteRow = $this->delete($row);
                if (!$deleteRow && !EmptyValue($this->DbErrorMessage)) { // Show database error
                    $this->setFailureMessage($this->DbErrorMessage);
                }
            }
            if ($deleteRow === false) {
                if ($this->UseTransaction) {
                    $successKeys = []; // Reset success keys
                    break;
                }
                $failKeys[] = $thisKey;
            } else {
                if (Config("DELETE_UPLOADED_FILES")) { // Delete old files
                    $this->deleteUploadedFiles($row);
                }

                // Call Row Deleted event
                $this->rowDeleted($row);
                $successKeys[] = $thisKey;
            }
        }

        // Any records deleted
        $deleteRows = count($successKeys) > 0;
        if (!$deleteRows) {
            // Set up error message
            if ($this->getSuccessMessage() != "" || $this->getFailureMessage() != "") {
                // Use the message, do nothing
            } elseif ($this->CancelMessage != "") {
                $this->setFailureMessage($this->CancelMessage);
                $this->CancelMessage = "";
            } else {
                $this->setFailureMessage($Language->phrase("DeleteCancelled"));
            }
        }
        return $deleteRows;
    }

    // Update record based on key values
    protected function editRow()
    {
        global $Security, $Language;
        $oldKeyFilter = $this->getRecordFilter();
        $filter = $this->applyUserIDFilters($oldKeyFilter);
        $conn = $this->getConnection();

        // Load old row
        $this->CurrentFilter = $filter;
        $sql = $this->getCurrentSql();
        $rsold = $conn->fetchAssociative($sql);
        if (!$rsold) {
            $this->setFailureMessage($Language->phrase("NoRecord")); // Set no record message
            return false; // Update Failed
        } else {
            // Save old values
            $this->loadDbValues($rsold);
        }

        // Set new row
        $rsnew = [];

        // ProductName
        $this->ProductName->setDbValueDef($rsnew, $this->ProductName->CurrentValue, $this->ProductName->ReadOnly);

        // SupplierID
        $this->SupplierID->setDbValueDef($rsnew, $this->SupplierID->CurrentValue, $this->SupplierID->ReadOnly);

        // CategoryID
        if ($this->CategoryID->getSessionValue() != "") {
            $this->CategoryID->ReadOnly = true;
        }
        $this->CategoryID->setDbValueDef($rsnew, $this->CategoryID->CurrentValue, $this->CategoryID->ReadOnly);

        // QuantityPerUnit
        $this->QuantityPerUnit->setDbValueDef($rsnew, $this->QuantityPerUnit->CurrentValue, $this->QuantityPerUnit->ReadOnly);

        // UnitPrice
        $this->UnitPrice->setDbValueDef($rsnew, $this->UnitPrice->CurrentValue, $this->UnitPrice->ReadOnly);

        // UnitsInStock
        $this->UnitsInStock->setDbValueDef($rsnew, $this->UnitsInStock->CurrentValue, $this->UnitsInStock->ReadOnly);

        // UnitsOnOrder
        $this->UnitsOnOrder->setDbValueDef($rsnew, $this->UnitsOnOrder->CurrentValue, $this->UnitsOnOrder->ReadOnly);

        // ReorderLevel
        $this->ReorderLevel->setDbValueDef($rsnew, $this->ReorderLevel->CurrentValue, $this->ReorderLevel->ReadOnly);

        // Discontinued
        $tmpBool = $this->Discontinued->CurrentValue;
        if ($tmpBool != "1" && $tmpBool != "0") {
            $tmpBool = !empty($tmpBool) ? "1" : "0";
        }
        $this->Discontinued->setDbValueDef($rsnew, $tmpBool, $this->Discontinued->ReadOnly);

        // EAN13
        $this->EAN13->setDbValueDef($rsnew, $this->EAN13->CurrentValue, $this->EAN13->ReadOnly);

        // Update current values
        $this->setCurrentValues($rsnew);

        // Call Row Updating event
        $updateRow = $this->rowUpdating($rsold, $rsnew);
        if ($updateRow) {
            if (count($rsnew) > 0) {
                $this->CurrentFilter = $filter; // Set up current filter
                $editRow = $this->update($rsnew, "", $rsold);
                if (!$editRow && !EmptyValue($this->DbErrorMessage)) { // Show database error
                    $this->setFailureMessage($this->DbErrorMessage);
                }
            } else {
                $editRow = true; // No field to update
            }
            if ($editRow) {
            }
        } else {
            if ($this->getSuccessMessage() != "" || $this->getFailureMessage() != "") {
                // Use the message, do nothing
            } elseif ($this->CancelMessage != "") {
                $this->setFailureMessage($this->CancelMessage);
                $this->CancelMessage = "";
            } else {
                $this->setFailureMessage($Language->phrase("UpdateCancelled"));
            }
            $editRow = false;
        }

        // Call Row_Updated event
        if ($editRow) {
            $this->rowUpdated($rsold, $rsnew);
        }
        return $editRow;
    }

    // Add record
    protected function addRow($rsold = null)
    {
        global $Language, $Security;

        // Set up foreign key field value from Session
        if ($this->getCurrentMasterTable() == "categories") {
            $this->CategoryID->Visible = true; // Need to insert foreign key
            $this->CategoryID->CurrentValue = $this->CategoryID->getSessionValue();
        }

        // Set new row
        $rsnew = [];

        // ProductName
        $this->ProductName->setDbValueDef($rsnew, $this->ProductName->CurrentValue, false);

        // SupplierID
        $this->SupplierID->setDbValueDef($rsnew, $this->SupplierID->CurrentValue, false);

        // CategoryID
        $this->CategoryID->setDbValueDef($rsnew, $this->CategoryID->CurrentValue, false);

        // QuantityPerUnit
        $this->QuantityPerUnit->setDbValueDef($rsnew, $this->QuantityPerUnit->CurrentValue, false);

        // UnitPrice
        $this->UnitPrice->setDbValueDef($rsnew, $this->UnitPrice->CurrentValue, strval($this->UnitPrice->CurrentValue) == "");

        // UnitsInStock
        $this->UnitsInStock->setDbValueDef($rsnew, $this->UnitsInStock->CurrentValue, strval($this->UnitsInStock->CurrentValue) == "");

        // UnitsOnOrder
        $this->UnitsOnOrder->setDbValueDef($rsnew, $this->UnitsOnOrder->CurrentValue, strval($this->UnitsOnOrder->CurrentValue) == "");

        // ReorderLevel
        $this->ReorderLevel->setDbValueDef($rsnew, $this->ReorderLevel->CurrentValue, strval($this->ReorderLevel->CurrentValue) == "");

        // Discontinued
        $tmpBool = $this->Discontinued->CurrentValue;
        if ($tmpBool != "1" && $tmpBool != "0") {
            $tmpBool = !empty($tmpBool) ? "1" : "0";
        }
        $this->Discontinued->setDbValueDef($rsnew, $tmpBool, strval($this->Discontinued->CurrentValue) == "");

        // EAN13
        $this->EAN13->setDbValueDef($rsnew, $this->EAN13->CurrentValue, false);

        // Update current values
        $this->setCurrentValues($rsnew);
        $conn = $this->getConnection();

        // Load db values from old row
        $this->loadDbValues($rsold);

        // Call Row Inserting event
        $insertRow = $this->rowInserting($rsold, $rsnew);
        if ($insertRow) {
            $addRow = $this->insert($rsnew);
            if ($addRow) {
            } elseif (!EmptyValue($this->DbErrorMessage)) { // Show database error
                $this->setFailureMessage($this->DbErrorMessage);
            }
        } else {
            if ($this->getSuccessMessage() != "" || $this->getFailureMessage() != "") {
                // Use the message, do nothing
            } elseif ($this->CancelMessage != "") {
                $this->setFailureMessage($this->CancelMessage);
                $this->CancelMessage = "";
            } else {
                $this->setFailureMessage($Language->phrase("InsertCancelled"));
            }
            $addRow = false;
        }
        if ($addRow) {
            // Call Row Inserted event
            $this->rowInserted($rsold, $rsnew);
        }
        return $addRow;
    }

    // Set up master/detail based on QueryString
    protected function setupMasterParms()
    {
        // Hide foreign keys
        $masterTblVar = $this->getCurrentMasterTable();
        if ($masterTblVar == "categories") {
            $masterTbl = Container("categories");
            $this->CategoryID->Visible = false;
            if ($masterTbl->EventCancelled) {
                $this->EventCancelled = true;
            }
        }
        $this->DbMasterFilter = $this->getMasterFilterFromSession(); // Get master filter from session
        $this->DbDetailFilter = $this->getDetailFilterFromSession(); // Get detail filter from session
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
                case "x_SupplierID":
                    break;
                case "x_CategoryID":
                    break;
                case "x_Discontinued":
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
        $this->setFixedHeaderTable(true, "mh-400px");
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

    // ListOptions Load event
    public function listOptionsLoad()
    {
        // Example:
        //$opt = &$this->ListOptions->Add("new");
        //$opt->Header = "xxx";
        //$opt->OnLeft = true; // Link on left
        //$opt->MoveTo(0); // Move to first column
    }

    // ListOptions Rendering event
    public function listOptionsRendering()
    {
        //Container("DetailTableGrid")->DetailAdd = (...condition...); // Set to true or false conditionally
        //Container("DetailTableGrid")->DetailEdit = (...condition...); // Set to true or false conditionally
        //Container("DetailTableGrid")->DetailView = (...condition...); // Set to true or false conditionally
    }

    // ListOptions Rendered event
    public function listOptionsRendered()
    {
        // Example:
        //$this->ListOptions["new"]->Body = "xxx";
    }
}
