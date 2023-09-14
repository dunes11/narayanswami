<?php

namespace PHPMaker2023\demo2023;

use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Page class
 */
class Orders2List extends Orders2
{
    use MessagesTrait;

    // Page ID
    public $PageID = "list";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Page object name
    public $PageObjName = "Orders2List";

    // View file path
    public $View = null;

    // Title
    public $Title = null; // Title for <title> tag

    // Rendering View
    public $RenderingView = false;

    // Grid form hidden field names
    public $FormName = "forders2list";
    public $FormActionName = "";
    public $FormBlankRowName = "";
    public $FormKeyCountName = "";

    // CSS class/style
    public $CurrentPageName = "orders2list";

    // Page URLs
    public $AddUrl;
    public $EditUrl;
    public $DeleteUrl;
    public $ViewUrl;
    public $CopyUrl;
    public $ListUrl;

    // Update URLs
    public $InlineAddUrl;
    public $InlineCopyUrl;
    public $InlineEditUrl;
    public $GridAddUrl;
    public $GridEditUrl;
    public $MultiEditUrl;
    public $MultiDeleteUrl;
    public $MultiUpdateUrl;

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
        $this->ShipVia->Visible = false;
        $this->Freight->setVisibility();
        $this->ShipName->Visible = false;
        $this->ShipAddress->setVisibility();
        $this->ShipCity->setVisibility();
        $this->ShipRegion->Visible = false;
        $this->ShipPostalCode->Visible = false;
        $this->ShipCountry->setVisibility();
    }

    // Constructor
    public function __construct()
    {
        parent::__construct();
        global $Language, $DashboardReport, $DebugTimer, $UserTable;
        $this->FormActionName = Config("FORM_ROW_ACTION_NAME");
        $this->FormBlankRowName = Config("FORM_BLANK_ROW_NAME");
        $this->FormKeyCountName = Config("FORM_KEY_COUNT_NAME");
        $this->TableVar = 'orders2';
        $this->TableName = 'orders2';

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
        $GLOBALS["Page"] = &$this;

        // Language object
        $Language = Container("language");

        // Table object (orders2)
        if (!isset($GLOBALS["orders2"]) || get_class($GLOBALS["orders2"]) == PROJECT_NAMESPACE . "orders2") {
            $GLOBALS["orders2"] = &$this;
        }

        // Page URL
        $pageUrl = $this->pageUrl(false);

        // Initialize URLs
        $this->AddUrl = "orders2add";
        $this->InlineAddUrl = $pageUrl . "action=add";
        $this->GridAddUrl = $pageUrl . "action=gridadd";
        $this->GridEditUrl = $pageUrl . "action=gridedit";
        $this->MultiEditUrl = $pageUrl . "action=multiedit";
        $this->MultiDeleteUrl = "orders2delete";
        $this->MultiUpdateUrl = "orders2update";

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'orders2');
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

        // Export options
        $this->ExportOptions = new ListOptions(["TagClassName" => "ew-export-option"]);

        // Import options
        $this->ImportOptions = new ListOptions(["TagClassName" => "ew-import-option"]);

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

        // Detail tables
        $this->OtherOptions["detail"] = new ListOptions(["TagClassName" => "ew-detail-option"]);
        // Actions
        $this->OtherOptions["action"] = new ListOptions(["TagClassName" => "ew-action-option"]);

        // Column visibility
        $this->OtherOptions["column"] = new ListOptions([
            "TableVar" => $this->TableVar,
            "TagClassName" => "ew-column-option",
            "ButtonGroupClass" => "ew-column-dropdown",
            "UseDropDownButton" => true,
            "DropDownButtonPhrase" => $Language->phrase("Columns"),
            "DropDownAutoClose" => "outside",
            "UseButtonGroup" => false
        ]);

        // Filter options
        $this->FilterOptions = new ListOptions(["TagClassName" => "ew-filter-option"]);

        // List actions
        $this->ListActions = new ListActions();
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
                    $result["view"] = $pageName == "orders2view"; // If View page, no primary button
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
                        if ($fld->DataType == DATATYPE_MEMO && $fld->MemoMaxLength > 0) {
                            $val = TruncateMemo($val, $fld->MemoMaxLength, $fld->TruncateMemoRemoveHtml);
                        }
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
    public $DisplayRecords = 30;
    public $StartRecord;
    public $StopRecord;
    public $TotalRecords = 0;
    public $RecordRange = 10;
    public $PageSizes = "10,20,30,50,-1"; // Page sizes (comma separated)
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
    public $TopContentClass = "ew-top";
    public $MiddleContentClass = "ew-middle";
    public $BottomContentClass = "ew-bottom";
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

        // Is modal
        $this->IsModal = ConvertToBool(Param("modal"));

        // Use layout
        $this->UseLayout = $this->UseLayout && ConvertToBool(Param(Config("PAGE_LAYOUT"), true));

        // View
        $this->View = Get(Config("VIEW"));

        // Create form object
        $CurrentForm = new HttpForm();

        // Get export parameters
        $custom = "";
        if (Param("export") !== null) {
            $this->Export = Param("export");
            $custom = Param("custom", "");
        } else {
            $this->setExportReturnUrl(CurrentUrl());
        }
        $ExportType = $this->Export; // Get export parameter, used in header
        if ($ExportType != "") {
            global $SkipHeaderFooter;
            $SkipHeaderFooter = true;
        }
        $this->CurrentAction = Param("action"); // Set up current action

        // Get grid add count
        $gridaddcnt = Get(Config("TABLE_GRID_ADD_ROW_COUNT"), "");
        if (is_numeric($gridaddcnt) && $gridaddcnt > 0) {
            $this->GridAddRowCount = $gridaddcnt;
        }

        // Set up list options
        $this->setupListOptions();

        // Setup export options
        $this->setupExportOptions();
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

        // Setup other options
        $this->setupOtherOptions();

        // Set up custom action (compatible with old version)
        foreach ($this->CustomActions as $name => $action) {
            $this->ListActions->add($name, $action);
        }

        // Set up lookup cache
        $this->setupLookupOptions($this->CustomerID);
        $this->setupLookupOptions($this->EmployeeID);

        // Load default values for add
        $this->loadDefaultValues();

        // Update form name to avoid conflict
        if ($this->IsModal) {
            $this->FormName = "forders2grid";
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

        // Process list action first
        if ($this->processListAction()) { // Ajax request
            $this->terminate();
            return;
        }

        // Set up records per page
        $this->setupDisplayRecords();

        // Handle reset command
        $this->resetCmd();

        // Set up Breadcrumb
        if (!$this->isExport()) {
            $this->setupBreadcrumb();
        }

        // Check QueryString parameters
        if (Get("action") !== null) {
            $this->CurrentAction = Get("action");
        } else {
            if (Post("action") !== null && Post("action") !== $this->UserAction) {
                $this->CurrentAction = Post("action"); // Get action
            } elseif (Session(SESSION_INLINE_MODE) == "gridedit") { // Previously in grid edit mode
                if (Get(Config("TABLE_START_REC")) !== null || Get(Config("TABLE_PAGE_NUMBER")) !== null) { // Stay in grid edit mode if paging
                    $this->gridEditMode();
                } else { // Reset grid edit
                    $this->clearInlineMode();
                }
            } elseif (Session(SESSION_INLINE_MODE) == "multiedit") { // Previously in multi edit mode
                $this->clearInlineMode(); // Reset grid edit
            }
        }

        // Clear inline mode
        if ($this->isCancel()) {
            $this->clearInlineMode();
        }

        // Switch to grid edit mode
        if ($this->isGridEdit()) {
            $this->gridEditMode();
        }

        // Switch to multi edit mode
        if ($this->isMultiEdit()) {
            $recKeys = $this->getRecordKeys();
            if (count($recKeys) > 0) {
                foreach ($recKeys as $keys) {
                    $this->RecKeys[] = is_array($keys) ? implode(Config("COMPOSITE_KEY_SEPARATOR"), $keys) : $keys;
                }
                $this->FilterForModalActions = $this->getFilterFromRecordKeys();
                $this->multiEditMode();
            } else {
                $this->clearInlineMode();
                if ($this->IsModal) {
                    WriteJson(["error" => $Language->phrase("NoRecord")]);
                    $this->terminate();
                    return;
                }
            }
        }

        // Grid Update
        if (IsPost() && ($this->isGridUpdate() || $this->isMultiUpdate() || $this->isGridOverwrite()) && (Session(SESSION_INLINE_MODE) == "gridedit" || Session(SESSION_INLINE_MODE) == "multiedit")) {
            if ($this->validateGridForm()) {
                $gridUpdate = $this->gridUpdate();
            } else {
                $gridUpdate = false;
            }
            if ($gridUpdate) {
                // Handle modal grid edit and multi edit, redirect to list page directly
                if ($this->IsModal && !$this->UseAjaxActions) {
                    $this->terminate("orders2list");
                    return;
                }
            } else {
                $this->EventCancelled = true;
                if ($this->UseAjaxActions) {
                    WriteJson([
                        "success" => false,
                        "validation" => $this->ValidationErrors,
                        "error" => $this->getFailureMessage()
                    ]);
                    $this->clearFailureMessage();
                    $this->terminate();
                    return;
                }
                if ($this->isMultiUpdate()) { // Stay in Multi-Edit mode
                    $this->FilterForModalActions = $this->getFilterFromRecords($this->getGridFormValues());
                    $this->multiEditMode();
                } else { // Stay in grid edit mode
                    $this->gridEditMode();
                }
            }
        }

        // Switch to inline edit mode
        if ($this->isEdit()) {
            $this->inlineEditMode();
        // Inline Update
        } elseif (IsPost() && ($this->isUpdate() || $this->isOverwrite()) && Session(SESSION_INLINE_MODE) == "edit") {
            $this->setKey(Post($this->OldKeyName));
            // Return JSON error message if UseAjaxActions
            if (!$this->inlineUpdate() && $this->UseAjaxActions) {
                WriteJson([ "success" => false, "validation" => $this->getValidationErrors(), "error" => $this->getFailureMessage() ]);
                $this->clearFailureMessage();
                $this->terminate();
                return;
            }
        }

        // Switch to inline add mode
        if ($this->isAdd() || $this->isCopy()) {
            $this->inlineAddMode();
        // Insert Inline
        } elseif (IsPost() && $this->isInsert() && Session(SESSION_INLINE_MODE) == "add") {
            $this->setKey(Post($this->OldKeyName));
            // Return JSON error message if UseAjaxActions
            if (!$this->inlineInsert() && $this->UseAjaxActions) {
                WriteJson([ "success" => false, "validation" => $this->getValidationErrors(), "error" => $this->getFailureMessage() ]);
                $this->clearFailureMessage();
                $this->terminate();
                return;
            }
        }

        // Switch to grid add mode
        if ($this->isGridAdd()) {
            $this->gridAddMode();
            // Grid Insert
        } elseif (IsPost() && $this->isGridInsert() && Session(SESSION_INLINE_MODE) == "gridadd") {
            if ($this->validateGridForm()) {
                $gridInsert = $this->gridInsert();
            } else {
                $gridInsert = false;
            }
            if ($gridInsert) {
                // Handle modal grid add, redirect to list page directly
                if ($this->IsModal && !$this->UseAjaxActions) {
                    $this->terminate("orders2list");
                    return;
                }
            } else {
                $this->EventCancelled = true;
                if ($this->UseAjaxActions) {
                    WriteJson([
                        "success" => false,
                        "validation" => $this->ValidationErrors,
                        "error" => $this->getFailureMessage()
                    ]);
                    $this->clearFailureMessage();
                    $this->terminate();
                    return;
                }
                $this->gridAddMode(); // Stay in grid add mode
            }
        }

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

        // Hide options
        if ($this->isExport() || !(EmptyValue($this->CurrentAction) || $this->isSearch())) {
            $this->ExportOptions->hideAllOptions();
            $this->FilterOptions->hideAllOptions();
            $this->ImportOptions->hideAllOptions();
        }

        // Hide other options
        if ($this->isExport()) {
            $this->OtherOptions->hideAllOptions();
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

        // Get default search criteria
        AddFilter($this->DefaultSearchWhere, $this->basicSearchWhere(true));

        // Get basic search values
        $this->loadBasicSearchValues();

        // Process filter list
        if ($this->processFilterList()) {
            $this->terminate();
            return;
        }

        // Restore search parms from Session if not searching / reset / export
        if (($this->isExport() || $this->Command != "search" && $this->Command != "reset" && $this->Command != "resetall") && $this->Command != "json" && $this->checkSearchParms()) {
            $this->restoreSearchParms();
        }

        // Call Recordset SearchValidated event
        $this->recordsetSearchValidated();

        // Set up sorting order
        $this->setupSortOrder();

        // Get basic search criteria
        if (!$this->hasInvalidFields()) {
            $srchBasic = $this->basicSearchWhere();
        }

        // Restore display records
        if ($this->Command != "json" && $this->getRecordsPerPage() != "") {
            $this->DisplayRecords = $this->getRecordsPerPage(); // Restore from Session
        } else {
            $this->DisplayRecords = 30; // Load default
            $this->setRecordsPerPage($this->DisplayRecords); // Save default to Session
        }

        // Load search default if no existing search criteria
        if (!$this->checkSearchParms() && !$query) {
            // Load basic search from default
            $this->BasicSearch->loadDefault();
            if ($this->BasicSearch->Keyword != "") {
                $srchBasic = $this->basicSearchWhere(); // Save to session
            }
        }

        // Build search criteria
        if ($query) {
            AddFilter($this->SearchWhere, $query);
        } else {
            AddFilter($this->SearchWhere, $srchAdvanced);
            AddFilter($this->SearchWhere, $srchBasic);
        }

        // Call Recordset_Searching event
        $this->recordsetSearching($this->SearchWhere);

        // Save search criteria
        if ($this->Command == "search" && !$this->RestoreSearch) {
            $this->setSearchWhere($this->SearchWhere); // Save to Session
            $this->StartRecord = 1; // Reset start record counter
            $this->setStartRecordNumber($this->StartRecord);
        } elseif ($this->Command != "json" && !$query) {
            $this->SearchWhere = $this->getSearchWhere();
        }

        // Build filter
        $filter = "";
        if (!$Security->canList()) {
            $filter = "(0=1)"; // Filter all records
        }
        AddFilter($filter, $this->DbDetailFilter);
        AddFilter($filter, $this->SearchWhere);

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
            $this->CurrentFilter = "0=1";
            $this->StartRecord = 1;
            $this->DisplayRecords = $this->GridAddRowCount;
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
            if ($this->DisplayRecords <= 0 || ($this->isExport() && $this->ExportAll)) { // Display all records
                $this->DisplayRecords = $this->TotalRecords;
            }
            if (!($this->isExport() && $this->ExportAll)) { // Set up start record position
                $this->setupStartRecord();
            }
            $this->Recordset = $this->loadRecordset($this->StartRecord - 1, $this->DisplayRecords);

            // Set no record found message
            if ((EmptyValue($this->CurrentAction) || $this->isSearch()) && $this->TotalRecords == 0) {
                if (!$Security->canList()) {
                    $this->setWarningMessage(DeniedMessage());
                }
                if ($this->SearchWhere == "0=101") {
                    $this->setWarningMessage($Language->phrase("EnterSearchCriteria"));
                } else {
                    $this->setWarningMessage($Language->phrase("NoRecord"));
                }
            }
        }

        // Set up list action columns
        foreach ($this->ListActions->Items as $listaction) {
            if ($listaction->Allow) {
                if ($listaction->Select == ACTION_MULTIPLE) { // Show checkbox column if multiple action
                    $this->ListOptions["checkbox"]->Visible = true;
                } elseif ($listaction->Select == ACTION_SINGLE) { // Show list action column
                        $this->ListOptions["listactions"]->Visible = true; // Set visible if any list action is allowed
                }
            }
        }

        // Search options
        $this->setupSearchOptions();

        // Set up search panel class
        if ($this->SearchWhere != "") {
            if ($query) { // Hide search panel if using QueryBuilder
                RemoveClass($this->SearchPanelClass, "show");
            } else {
                AppendClass($this->SearchPanelClass, "show");
            }
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
                    $this->DisplayRecords = 30; // Non-numeric, load default
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
        $this->Freight->FormValue = ""; // Clear form value
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

    // Switch to Multi Edit mode
    protected function multiEditMode()
    {
        $this->CurrentAction = "multiedit";
        $_SESSION[SESSION_INLINE_MODE] = "multiedit";
        $this->hideFieldsForAddEdit();
    }

    // Switch to Inline Edit mode
    protected function inlineEditMode()
    {
        global $Security, $Language;
        if (!$Security->canEdit()) {
            return false; // Edit not allowed
        }
        $inlineEdit = true;
        if (($keyValue = Get("OrderID") ?? Route("OrderID")) !== null) {
            $this->OrderID->setQueryStringValue($keyValue);
        } elseif (IsApi() && ($keyValue = Route(2)) !== null) {
            $this->OrderID->setQueryStringValue($keyValue);
        } else {
            $inlineEdit = false;
        }
        if ($inlineEdit) {
            if ($this->loadRow()) {
                $this->OldKey = $this->getKey(true); // Get from CurrentValue
                $this->setKey($this->OldKey); // Set to OldValue
                $_SESSION[SESSION_INLINE_MODE] = "edit"; // Enable inline edit
            }
        }
        return true;
    }

    // Perform update to Inline Edit record
    protected function inlineUpdate()
    {
        global $Language, $CurrentForm;
        $CurrentForm->Index = 1;
        $this->loadFormValues(); // Get form values

        // Validate form
        $inlineUpdate = true;
        if (!$this->validateForm()) {
            $inlineUpdate = false; // Form error, reset action
        } else {
            $inlineUpdate = false;
            $this->SendEmail = true; // Send email on update success
            $inlineUpdate = $this->editRow(); // Update record
        }
        if ($inlineUpdate) { // Update success
            if ($this->getSuccessMessage() == "") {
                $this->setSuccessMessage($Language->phrase("UpdateSuccess")); // Set up success message
            }
            $this->clearInlineMode(); // Clear inline edit mode
        } else {
            if ($this->getFailureMessage() == "") {
                $this->setFailureMessage($Language->phrase("UpdateFailed")); // Set update failed message
            }
            $this->EventCancelled = true; // Cancel event
            $this->CurrentAction = "edit"; // Stay in edit mode
        }
        return $inlineUpdate;
    }

    // Check Inline Edit key
    public function checkInlineEditKey()
    {
        if (!SameString($this->OrderID->OldValue, $this->OrderID->CurrentValue)) {
            return false;
        }
        return true;
    }

    // Switch to Inline Add mode
    protected function inlineAddMode()
    {
        global $Security, $Language;
        if (!$Security->canAdd()) {
            return false; // Add not allowed
        }
        if ($this->isCopy()) {
            if (($keyValue = Get("OrderID") ?? Route("OrderID")) !== null) {
                $this->OrderID->setQueryStringValue($keyValue);
            } elseif (IsApi() && ($keyValue = Route(2)) !== null) {
                $this->OrderID->setQueryStringValue($keyValue);
            } else {
                $this->CurrentAction = "add";
            }
            $this->OldKey = $this->getKey(true); // Get from CurrentValue
        } else {
            $this->OldKey = ""; // Clear old record key
        }
        $this->setKey($this->OldKey); // Set to OldValue
        $_SESSION[SESSION_INLINE_MODE] = "add"; // Enable inline add
        return true;
    }

    // Perform update to Inline Add/Copy record
    protected function inlineInsert()
    {
        global $Language, $CurrentForm;
        $rsold = $this->loadOldRecord(); // Load old record
        $CurrentForm->Index = 0;
        $this->loadFormValues(); // Get form values

        // Validate form
        if (!$this->validateForm()) {
            $this->EventCancelled = true; // Set event cancelled
            $this->CurrentAction = "add"; // Stay in add mode
            return false;
        }
        $this->SendEmail = true; // Send email on add success
        if ($this->addRow($rsold)) { // Add record
            if ($this->getSuccessMessage() == "") {
                $this->setSuccessMessage($Language->phrase("AddSuccess")); // Set up add success message
            }
            $this->clearInlineMode(); // Clear inline add mode
            return true;
        } else { // Add failed
            $this->EventCancelled = true; // Set event cancelled
            $this->CurrentAction = "add"; // Stay in add mode
            return false;
        }
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

        // Begin transaction
        if ($this->UseTransaction) {
            $conn->beginTransaction();
        }
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
            if ($this->UseTransaction) { // Commit transaction
                $conn->commit();
            }
            $this->FilterForModalActions = $wrkfilter;

            // Get new records
            $rsnew = $conn->fetchAllAssociative($sql);

            // Call Grid_Updated event
            $this->gridUpdated($rsold, $rsnew);
            if ($this->getSuccessMessage() == "") {
                $this->setSuccessMessage($Language->phrase("UpdateSuccess")); // Set up update success message
            }
            $this->clearInlineMode(); // Clear inline edit mode
        } else {
            if ($this->UseTransaction) { // Rollback transaction
                $conn->rollback();
            }
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

        // Begin transaction
        if ($this->UseTransaction) {
            $conn->beginTransaction();
        }

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
                    $key .= $this->OrderID->CurrentValue;

                    // Add filter for this record
                    AddFilter($wrkfilter, $this->getRecordFilter(), "OR");
                } else {
                    $this->EventCancelled = true;
                    break;
                }
            }
        }
        if ($addcnt == 0) { // No record inserted
            $this->setFailureMessage($Language->phrase("NoAddRecord"));
            $gridInsert = false;
        }
        if ($gridInsert) {
            if ($this->UseTransaction) { // Commit transaction
                $conn->commit();
            }

            // Get new records
            $this->CurrentFilter = $wrkfilter;
            $this->FilterForModalActions = $wrkfilter;
            $sql = $this->getCurrentSql();
            $rsnew = $conn->fetchAllAssociative($sql);

            // Call Grid_Inserted event
            $this->gridInserted($rsnew);
            if ($this->getSuccessMessage() == "") {
                $this->setSuccessMessage($Language->phrase("InsertSuccess")); // Set up insert success message
            }
            $this->clearInlineMode(); // Clear grid add mode
        } else {
            if ($this->UseTransaction) { // Rollback transaction
                $conn->rollback();
            }
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
        if ($CurrentForm->hasValue("x_CustomerID") && $CurrentForm->hasValue("o_CustomerID") && $this->CustomerID->CurrentValue != $this->CustomerID->DefaultValue) {
            return false;
        }
        if ($CurrentForm->hasValue("x_EmployeeID") && $CurrentForm->hasValue("o_EmployeeID") && $this->EmployeeID->CurrentValue != $this->EmployeeID->DefaultValue) {
            return false;
        }
        if ($CurrentForm->hasValue("x_OrderDate") && $CurrentForm->hasValue("o_OrderDate") && $this->OrderDate->CurrentValue != $this->OrderDate->DefaultValue) {
            return false;
        }
        if ($CurrentForm->hasValue("x_RequiredDate") && $CurrentForm->hasValue("o_RequiredDate") && $this->RequiredDate->CurrentValue != $this->RequiredDate->DefaultValue) {
            return false;
        }
        if ($CurrentForm->hasValue("x_ShippedDate") && $CurrentForm->hasValue("o_ShippedDate") && $this->ShippedDate->CurrentValue != $this->ShippedDate->DefaultValue) {
            return false;
        }
        if ($CurrentForm->hasValue("x_Freight") && $CurrentForm->hasValue("o_Freight") && $this->Freight->CurrentValue != $this->Freight->DefaultValue) {
            return false;
        }
        if ($CurrentForm->hasValue("x_ShipAddress") && $CurrentForm->hasValue("o_ShipAddress") && $this->ShipAddress->CurrentValue != $this->ShipAddress->DefaultValue) {
            return false;
        }
        if ($CurrentForm->hasValue("x_ShipCity") && $CurrentForm->hasValue("o_ShipCity") && $this->ShipCity->CurrentValue != $this->ShipCity->DefaultValue) {
            return false;
        }
        if ($CurrentForm->hasValue("x_ShipCountry") && $CurrentForm->hasValue("o_ShipCountry") && $this->ShipCountry->CurrentValue != $this->ShipCountry->DefaultValue) {
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

    // Get list of filters
    public function getFilterList()
    {
        global $UserProfile;

        // Initialize
        $filterList = "";
        $savedFilterList = "";
        $filterList = Concat($filterList, $this->OrderID->AdvancedSearch->toJson(), ","); // Field OrderID
        $filterList = Concat($filterList, $this->CustomerID->AdvancedSearch->toJson(), ","); // Field CustomerID
        $filterList = Concat($filterList, $this->EmployeeID->AdvancedSearch->toJson(), ","); // Field EmployeeID
        $filterList = Concat($filterList, $this->OrderDate->AdvancedSearch->toJson(), ","); // Field OrderDate
        $filterList = Concat($filterList, $this->RequiredDate->AdvancedSearch->toJson(), ","); // Field RequiredDate
        $filterList = Concat($filterList, $this->ShippedDate->AdvancedSearch->toJson(), ","); // Field ShippedDate
        $filterList = Concat($filterList, $this->ShipVia->AdvancedSearch->toJson(), ","); // Field ShipVia
        $filterList = Concat($filterList, $this->Freight->AdvancedSearch->toJson(), ","); // Field Freight
        $filterList = Concat($filterList, $this->ShipName->AdvancedSearch->toJson(), ","); // Field ShipName
        $filterList = Concat($filterList, $this->ShipAddress->AdvancedSearch->toJson(), ","); // Field ShipAddress
        $filterList = Concat($filterList, $this->ShipCity->AdvancedSearch->toJson(), ","); // Field ShipCity
        $filterList = Concat($filterList, $this->ShipRegion->AdvancedSearch->toJson(), ","); // Field ShipRegion
        $filterList = Concat($filterList, $this->ShipPostalCode->AdvancedSearch->toJson(), ","); // Field ShipPostalCode
        $filterList = Concat($filterList, $this->ShipCountry->AdvancedSearch->toJson(), ","); // Field ShipCountry
        if ($this->BasicSearch->Keyword != "") {
            $wrk = "\"" . Config("TABLE_BASIC_SEARCH") . "\":\"" . JsEncode($this->BasicSearch->Keyword) . "\",\"" . Config("TABLE_BASIC_SEARCH_TYPE") . "\":\"" . JsEncode($this->BasicSearch->Type) . "\"";
            $filterList = Concat($filterList, $wrk, ",");
        }

        // Return filter list in JSON
        if ($filterList != "") {
            $filterList = "\"data\":{" . $filterList . "}";
        }
        if ($savedFilterList != "") {
            $filterList = Concat($filterList, "\"filters\":" . $savedFilterList, ",");
        }
        return ($filterList != "") ? "{" . $filterList . "}" : "null";
    }

    // Process filter list
    protected function processFilterList()
    {
        global $UserProfile;
        if (Post("ajax") == "savefilters") { // Save filter request (Ajax)
            $filters = Post("filters");
            $UserProfile->setSearchFilters(CurrentUserName(), "forders2srch", $filters);
            WriteJson([["success" => true]]); // Success
            return true;
        } elseif (Post("cmd") == "resetfilter") {
            $this->restoreFilterList();
        }
        return false;
    }

    // Restore list of filters
    protected function restoreFilterList()
    {
        // Return if not reset filter
        if (Post("cmd") !== "resetfilter") {
            return false;
        }
        $filter = json_decode(Post("filter"), true);
        $this->Command = "search";

        // Field OrderID
        $this->OrderID->AdvancedSearch->SearchValue = @$filter["x_OrderID"];
        $this->OrderID->AdvancedSearch->SearchOperator = @$filter["z_OrderID"];
        $this->OrderID->AdvancedSearch->SearchCondition = @$filter["v_OrderID"];
        $this->OrderID->AdvancedSearch->SearchValue2 = @$filter["y_OrderID"];
        $this->OrderID->AdvancedSearch->SearchOperator2 = @$filter["w_OrderID"];
        $this->OrderID->AdvancedSearch->save();

        // Field CustomerID
        $this->CustomerID->AdvancedSearch->SearchValue = @$filter["x_CustomerID"];
        $this->CustomerID->AdvancedSearch->SearchOperator = @$filter["z_CustomerID"];
        $this->CustomerID->AdvancedSearch->SearchCondition = @$filter["v_CustomerID"];
        $this->CustomerID->AdvancedSearch->SearchValue2 = @$filter["y_CustomerID"];
        $this->CustomerID->AdvancedSearch->SearchOperator2 = @$filter["w_CustomerID"];
        $this->CustomerID->AdvancedSearch->save();

        // Field EmployeeID
        $this->EmployeeID->AdvancedSearch->SearchValue = @$filter["x_EmployeeID"];
        $this->EmployeeID->AdvancedSearch->SearchOperator = @$filter["z_EmployeeID"];
        $this->EmployeeID->AdvancedSearch->SearchCondition = @$filter["v_EmployeeID"];
        $this->EmployeeID->AdvancedSearch->SearchValue2 = @$filter["y_EmployeeID"];
        $this->EmployeeID->AdvancedSearch->SearchOperator2 = @$filter["w_EmployeeID"];
        $this->EmployeeID->AdvancedSearch->save();

        // Field OrderDate
        $this->OrderDate->AdvancedSearch->SearchValue = @$filter["x_OrderDate"];
        $this->OrderDate->AdvancedSearch->SearchOperator = @$filter["z_OrderDate"];
        $this->OrderDate->AdvancedSearch->SearchCondition = @$filter["v_OrderDate"];
        $this->OrderDate->AdvancedSearch->SearchValue2 = @$filter["y_OrderDate"];
        $this->OrderDate->AdvancedSearch->SearchOperator2 = @$filter["w_OrderDate"];
        $this->OrderDate->AdvancedSearch->save();

        // Field RequiredDate
        $this->RequiredDate->AdvancedSearch->SearchValue = @$filter["x_RequiredDate"];
        $this->RequiredDate->AdvancedSearch->SearchOperator = @$filter["z_RequiredDate"];
        $this->RequiredDate->AdvancedSearch->SearchCondition = @$filter["v_RequiredDate"];
        $this->RequiredDate->AdvancedSearch->SearchValue2 = @$filter["y_RequiredDate"];
        $this->RequiredDate->AdvancedSearch->SearchOperator2 = @$filter["w_RequiredDate"];
        $this->RequiredDate->AdvancedSearch->save();

        // Field ShippedDate
        $this->ShippedDate->AdvancedSearch->SearchValue = @$filter["x_ShippedDate"];
        $this->ShippedDate->AdvancedSearch->SearchOperator = @$filter["z_ShippedDate"];
        $this->ShippedDate->AdvancedSearch->SearchCondition = @$filter["v_ShippedDate"];
        $this->ShippedDate->AdvancedSearch->SearchValue2 = @$filter["y_ShippedDate"];
        $this->ShippedDate->AdvancedSearch->SearchOperator2 = @$filter["w_ShippedDate"];
        $this->ShippedDate->AdvancedSearch->save();

        // Field ShipVia
        $this->ShipVia->AdvancedSearch->SearchValue = @$filter["x_ShipVia"];
        $this->ShipVia->AdvancedSearch->SearchOperator = @$filter["z_ShipVia"];
        $this->ShipVia->AdvancedSearch->SearchCondition = @$filter["v_ShipVia"];
        $this->ShipVia->AdvancedSearch->SearchValue2 = @$filter["y_ShipVia"];
        $this->ShipVia->AdvancedSearch->SearchOperator2 = @$filter["w_ShipVia"];
        $this->ShipVia->AdvancedSearch->save();

        // Field Freight
        $this->Freight->AdvancedSearch->SearchValue = @$filter["x_Freight"];
        $this->Freight->AdvancedSearch->SearchOperator = @$filter["z_Freight"];
        $this->Freight->AdvancedSearch->SearchCondition = @$filter["v_Freight"];
        $this->Freight->AdvancedSearch->SearchValue2 = @$filter["y_Freight"];
        $this->Freight->AdvancedSearch->SearchOperator2 = @$filter["w_Freight"];
        $this->Freight->AdvancedSearch->save();

        // Field ShipName
        $this->ShipName->AdvancedSearch->SearchValue = @$filter["x_ShipName"];
        $this->ShipName->AdvancedSearch->SearchOperator = @$filter["z_ShipName"];
        $this->ShipName->AdvancedSearch->SearchCondition = @$filter["v_ShipName"];
        $this->ShipName->AdvancedSearch->SearchValue2 = @$filter["y_ShipName"];
        $this->ShipName->AdvancedSearch->SearchOperator2 = @$filter["w_ShipName"];
        $this->ShipName->AdvancedSearch->save();

        // Field ShipAddress
        $this->ShipAddress->AdvancedSearch->SearchValue = @$filter["x_ShipAddress"];
        $this->ShipAddress->AdvancedSearch->SearchOperator = @$filter["z_ShipAddress"];
        $this->ShipAddress->AdvancedSearch->SearchCondition = @$filter["v_ShipAddress"];
        $this->ShipAddress->AdvancedSearch->SearchValue2 = @$filter["y_ShipAddress"];
        $this->ShipAddress->AdvancedSearch->SearchOperator2 = @$filter["w_ShipAddress"];
        $this->ShipAddress->AdvancedSearch->save();

        // Field ShipCity
        $this->ShipCity->AdvancedSearch->SearchValue = @$filter["x_ShipCity"];
        $this->ShipCity->AdvancedSearch->SearchOperator = @$filter["z_ShipCity"];
        $this->ShipCity->AdvancedSearch->SearchCondition = @$filter["v_ShipCity"];
        $this->ShipCity->AdvancedSearch->SearchValue2 = @$filter["y_ShipCity"];
        $this->ShipCity->AdvancedSearch->SearchOperator2 = @$filter["w_ShipCity"];
        $this->ShipCity->AdvancedSearch->save();

        // Field ShipRegion
        $this->ShipRegion->AdvancedSearch->SearchValue = @$filter["x_ShipRegion"];
        $this->ShipRegion->AdvancedSearch->SearchOperator = @$filter["z_ShipRegion"];
        $this->ShipRegion->AdvancedSearch->SearchCondition = @$filter["v_ShipRegion"];
        $this->ShipRegion->AdvancedSearch->SearchValue2 = @$filter["y_ShipRegion"];
        $this->ShipRegion->AdvancedSearch->SearchOperator2 = @$filter["w_ShipRegion"];
        $this->ShipRegion->AdvancedSearch->save();

        // Field ShipPostalCode
        $this->ShipPostalCode->AdvancedSearch->SearchValue = @$filter["x_ShipPostalCode"];
        $this->ShipPostalCode->AdvancedSearch->SearchOperator = @$filter["z_ShipPostalCode"];
        $this->ShipPostalCode->AdvancedSearch->SearchCondition = @$filter["v_ShipPostalCode"];
        $this->ShipPostalCode->AdvancedSearch->SearchValue2 = @$filter["y_ShipPostalCode"];
        $this->ShipPostalCode->AdvancedSearch->SearchOperator2 = @$filter["w_ShipPostalCode"];
        $this->ShipPostalCode->AdvancedSearch->save();

        // Field ShipCountry
        $this->ShipCountry->AdvancedSearch->SearchValue = @$filter["x_ShipCountry"];
        $this->ShipCountry->AdvancedSearch->SearchOperator = @$filter["z_ShipCountry"];
        $this->ShipCountry->AdvancedSearch->SearchCondition = @$filter["v_ShipCountry"];
        $this->ShipCountry->AdvancedSearch->SearchValue2 = @$filter["y_ShipCountry"];
        $this->ShipCountry->AdvancedSearch->SearchOperator2 = @$filter["w_ShipCountry"];
        $this->ShipCountry->AdvancedSearch->save();
        $this->BasicSearch->setKeyword(@$filter[Config("TABLE_BASIC_SEARCH")]);
        $this->BasicSearch->setType(@$filter[Config("TABLE_BASIC_SEARCH_TYPE")]);
    }

    // Show list of filters
    public function showFilterList()
    {
        global $Language;

        // Initialize
        $filterList = "";
        $captionClass = $this->isExport("email") ? "ew-filter-caption-email" : "ew-filter-caption";
        $captionSuffix = $this->isExport("email") ? ": " : "";
        if ($this->BasicSearch->Keyword != "") {
            $filterList .= "<div><span class=\"" . $captionClass . "\">" . $Language->phrase("BasicSearchKeyword") . "</span>" . $captionSuffix . $this->BasicSearch->Keyword . "</div>";
        }

        // Show Filters
        if ($filterList != "") {
            $message = "<div id=\"ew-filter-list\" class=\"callout callout-info d-table\"><div id=\"ew-current-filters\">" .
                $Language->phrase("CurrentFilters") . "</div>" . $filterList . "</div>";
            $this->messageShowing($message, "");
            Write($message);
        } else { // Output empty tag
            Write("<div id=\"ew-filter-list\"></div>");
        }
    }

    // Return basic search WHERE clause based on search keyword and type
    public function basicSearchWhere($default = false)
    {
        global $Security;
        $searchStr = "";
        if (!$Security->canSearch()) {
            return "";
        }

        // Fields to search
        $searchFlds = [];
        $searchFlds[] = &$this->CustomerID;
        $searchFlds[] = &$this->ShipName;
        $searchFlds[] = &$this->ShipAddress;
        $searchFlds[] = &$this->ShipCity;
        $searchFlds[] = &$this->ShipRegion;
        $searchFlds[] = &$this->ShipPostalCode;
        $searchFlds[] = &$this->ShipCountry;
        $searchKeyword = $default ? $this->BasicSearch->KeywordDefault : $this->BasicSearch->Keyword;
        $searchType = $default ? $this->BasicSearch->TypeDefault : $this->BasicSearch->Type;

        // Get search SQL
        if ($searchKeyword != "") {
            $ar = $this->BasicSearch->keywordList($default);
            $searchStr = GetQuickSearchFilter($searchFlds, $ar, $searchType, Config("BASIC_SEARCH_ANY_FIELDS"), $this->Dbid);
            if (!$default && in_array($this->Command, ["", "reset", "resetall"])) {
                $this->Command = "search";
            }
        }
        if (!$default && $this->Command == "search") {
            $this->BasicSearch->setKeyword($searchKeyword);
            $this->BasicSearch->setType($searchType);

            // Clear rules for QueryBuilder
            $this->setSessionRules("");
        }
        return $searchStr;
    }

    // Check if search parm exists
    protected function checkSearchParms()
    {
        // Check basic search
        if ($this->BasicSearch->issetSession()) {
            return true;
        }
        return false;
    }

    // Clear all search parameters
    protected function resetSearchParms()
    {
        // Clear search WHERE clause
        $this->SearchWhere = "";
        $this->setSearchWhere($this->SearchWhere);

        // Clear basic search parameters
        $this->resetBasicSearchParms();
    }

    // Load advanced search default values
    protected function loadAdvancedSearchDefault()
    {
        return false;
    }

    // Clear all basic search parameters
    protected function resetBasicSearchParms()
    {
        $this->BasicSearch->unsetSession();
    }

    // Restore all search parameters
    protected function restoreSearchParms()
    {
        $this->RestoreSearch = true;

        // Restore basic search values
        $this->BasicSearch->load();
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
            $this->updateSort($this->OrderID); // OrderID
            $this->updateSort($this->CustomerID); // CustomerID
            $this->updateSort($this->EmployeeID); // EmployeeID
            $this->updateSort($this->OrderDate); // OrderDate
            $this->updateSort($this->RequiredDate); // RequiredDate
            $this->updateSort($this->ShippedDate); // ShippedDate
            $this->updateSort($this->Freight); // Freight
            $this->updateSort($this->ShipAddress); // ShipAddress
            $this->updateSort($this->ShipCity); // ShipCity
            $this->updateSort($this->ShipCountry); // ShipCountry
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
            // Reset search criteria
            if ($this->Command == "reset" || $this->Command == "resetall") {
                $this->resetSearchParms();
            }

            // Reset (clear) sorting order
            if ($this->Command == "resetsort") {
                $orderBy = "";
                $this->setSessionOrderBy($orderBy);
                $this->OrderID->setSort("");
                $this->CustomerID->setSort("");
                $this->EmployeeID->setSort("");
                $this->OrderDate->setSort("");
                $this->RequiredDate->setSort("");
                $this->ShippedDate->setSort("");
                $this->ShipVia->setSort("");
                $this->Freight->setSort("");
                $this->ShipName->setSort("");
                $this->ShipAddress->setSort("");
                $this->ShipCity->setSort("");
                $this->ShipRegion->setSort("");
                $this->ShipPostalCode->setSort("");
                $this->ShipCountry->setSort("");
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

        // List actions
        $item = &$this->ListOptions->add("listactions");
        $item->CssClass = "text-nowrap";
        $item->OnLeft = true;
        $item->Visible = false;
        $item->ShowInButtonGroup = false;
        $item->ShowInDropDown = false;

        // "checkbox"
        $item = &$this->ListOptions->add("checkbox");
        $item->Visible = $Security->canEdit();
        $item->OnLeft = true;
        $item->Header = "<div class=\"form-check\"><input type=\"checkbox\" name=\"key\" id=\"key\" class=\"form-check-input\" data-ew-action=\"select-all-keys\"></div>";
        if ($item->OnLeft) {
            $item->moveTo(0);
        }
        $item->ShowInDropDown = false;
        $item->ShowInButtonGroup = false;

        // "sequence"
        $item = &$this->ListOptions->add("sequence");
        $item->CssClass = "text-nowrap";
        $item->Visible = true;
        $item->OnLeft = true; // Always on left
        $item->ShowInDropDown = false;
        $item->ShowInButtonGroup = false;

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
        $this->setupListOptionsExt();
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
            if ($this->isGridAdd() || $this->isGridEdit()) {
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

        // "sequence"
        $opt = $this->ListOptions["sequence"];
        $opt->Body = FormatSequenceNumber($this->RecordCount);
        $pageUrl = $this->pageUrl(false);

        // "copy"
        $opt = $this->ListOptions["copy"];
        if ($this->isInlineAddRow() || $this->isInlineCopyRow()) { // Inline Add/Copy
            $this->ListOptions->CustomItem = "copy"; // Show copy column only
            $divClass = $opt->OnLeft ? " class=\"text-end\"" : "";
            $insertCaption = $Language->phrase("InsertLink");
            $insertTitle = HtmlTitle($insertCaption);
            $cancelCaption = $Language->phrase("CancelLink");
            $cancelTitle = HtmlTitle($cancelCaption);
            $inlineInsertUrl = GetUrl($this->pageName());
            if ($this->UseAjaxActions) {
                $opt->Body = <<<INLINEADDAJAX
                    <div{$divClass}>
                        <button type="button" class="ew-grid-link ew-inline-insert" title="{$insertTitle}" data-caption="{$insertTitle}" data-ew-action="inline" data-action="insert" data-key="" data-url="{$inlineInsertUrl}">{$insertCaption}</button>
                        <button type="button" class="ew-grid-link ew-inline-cancel" title="{$cancelTitle}" data-caption="{$cancelTitle}" data-ew-action="inline" data-action="cancel">{$cancelCaption}</button>
                    </div>
                    INLINEADDAJAX;
            } else {
                $cancelurl = $this->addMasterUrl($pageUrl . "action=cancel");
                $opt->Body = <<<INLINEADD
                    <div{$divClass}>
                        <button class="ew-grid-link ew-inline-insert" title="{$insertTitle}" data-caption="{$insertTitle}" form="forders2list" formaction="{$inlineInsertUrl}">{$insertCaption}</button>
                        <a class="ew-grid-link ew-inline-cancel" title="{$cancelTitle}" data-caption="{$insertTitle}" href="{$cancelurl}">{$cancelCaption}</a>
                        <input type="hidden" name="action" id="action" value="insert">
                    </div>
                    INLINEADD;
            }
            return;
        }

        // "edit"
        $opt = $this->ListOptions["edit"];
        if ($this->isInlineEditRow()) { // Inline-Edit
            $this->ListOptions->CustomItem = "edit"; // Show edit column only
            $cancelurl = $this->addMasterUrl($pageUrl . "action=cancel");
                $divClass = $opt->OnLeft ? " class=\"text-end\"" : "";
                $updateCaption = $Language->phrase("UpdateLink");
                $updateTitle = HtmlTitle($updateCaption);
                $cancelCaption = $Language->phrase("CancelLink");
                $cancelTitle = HtmlTitle($cancelCaption);
                $oldKey = HtmlEncode($this->getKey(true));
                $inlineUpdateUrl = HtmlEncode(GetUrl($this->urlAddHash($this->pageName(), "r" . $this->RowCount . "_" . $this->TableVar)));
                if ($this->UseAjaxActions) {
                    $inlineCancelUrl = $this->InlineEditUrl . "?action=cancel";
                    $opt->Body = <<<INLINEEDITAJAX
                    <div{$divClass}>
                        <button type="button" class="ew-grid-link ew-inline-update" title="{$updateTitle}" data-caption="{$updateTitle}" data-ew-action="inline" data-action="update" data-key="{$oldKey}" data-url="{$inlineUpdateUrl}">{$updateCaption}</button>
                        <button type="button" class="ew-grid-link ew-inline-cancel" title="{$cancelTitle}" data-caption="{$cancelTitle}" data-ew-action="inline" data-action="cancel" data-key="{$oldKey}" data-url="{$inlineCancelUrl}">{$cancelCaption}</button>
                    </div>
                    INLINEEDITAJAX;
                } else {
                    $opt->Body = <<<INLINEEDIT
                    <div{$divClass}>
                        <button class="ew-grid-link ew-inline-update" title="{$updateTitle}" data-caption="{$updateTitle}" form="forders2list" formaction="{$inlineUpdateUrl}">{$updateCaption}</button>
                        <a class="ew-grid-link ew-inline-cancel" title="{$cancelTitle}" data-caption="{$updateTitle}" href="{$cancelurl}">{$cancelCaption}</a>
                        <input type="hidden" name="action" id="action" value="update">
                    </div>
                    INLINEEDIT;
                }
            $opt->Body .= "<input type=\"hidden\" name=\"" . $this->OldKeyName . "\" id=\"" . $this->OldKeyName . "\" value=\"" . HtmlEncode($this->OrderID->CurrentValue) . "\">";
            return;
        }
        if ($this->CurrentMode == "view") {
            // "view"
            $opt = $this->ListOptions["view"];
            $viewcaption = HtmlTitle($Language->phrase("ViewLink"));
            if ($Security->canView()) {
                if ($this->ModalView && !IsMobile()) {
                    $opt->Body = "<a class=\"ew-row-link ew-view\" title=\"" . $viewcaption . "\" data-table=\"orders2\" data-caption=\"" . $viewcaption . "\" data-ew-action=\"modal\" data-action=\"view\" data-ajax=\"" . ($this->UseAjaxActions ? "true" : "false") . "\" data-url=\"" . HtmlEncode(GetUrl($this->ViewUrl)) . "\" data-btn=\"null\">" . $Language->phrase("ViewLink") . "</a>";
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
                    $opt->Body = "<a class=\"ew-row-link ew-edit\" title=\"" . $editcaption . "\" data-table=\"orders2\" data-caption=\"" . $editcaption . "\" data-ew-action=\"modal\" data-action=\"edit\" data-ajax=\"" . ($this->UseAjaxActions ? "true" : "false") . "\" data-url=\"" . HtmlEncode(GetUrl($this->EditUrl)) . "\" data-btn=\"SaveBtn\">" . $Language->phrase("EditLink") . "</a>";
                } else {
                    $opt->Body = "<a class=\"ew-row-link ew-edit\" title=\"" . $editcaption . "\" data-caption=\"" . $editcaption . "\" href=\"" . HtmlEncode(GetUrl($this->EditUrl)) . "\">" . $Language->phrase("EditLink") . "</a>";
                }
                $inlineEditCaption = $Language->phrase("InlineEditLink");
                $inlineEditTitle = HtmlTitle($inlineEditCaption);
                if ($this->UseAjaxActions) {
                    $opt->Body .= "<a class=\"ew-row-link ew-inline-edit\" title=\"" . $inlineEditTitle . "\" data-caption=\"" . $inlineEditTitle . "\" data-ew-action=\"inline\" data-action=\"edit\" data-key=\"" . HtmlEncode($this->getKey(true)) . "\" data-url=\"" . HtmlEncode($this->InlineEditUrl) . "\">" . $inlineEditCaption . "</a>";
                } else {
                    $opt->Body .= "<a class=\"ew-row-link ew-inline-edit\" title=\"" . $inlineEditTitle . "\" data-caption=\"" . $inlineEditTitle . "\" href=\"" . HtmlEncode($this->urlAddHash(GetUrl($this->InlineEditUrl), "r" . $this->RowCount . "_" . $this->TableVar)) . "\">" . $inlineEditCaption . "</a>";
                }
            } else {
                $opt->Body = "";
            }

            // "copy"
            $opt = $this->ListOptions["copy"];
            $copycaption = HtmlTitle($Language->phrase("CopyLink"));
            if ($Security->canAdd()) {
                if ($this->ModalAdd && !IsMobile()) {
                    $opt->Body = "<a class=\"ew-row-link ew-copy\" title=\"" . $copycaption . "\" data-table=\"orders2\" data-caption=\"" . $copycaption . "\" data-ew-action=\"modal\" data-action=\"add\" data-ajax=\"" . ($this->UseAjaxActions ? "true" : "false") . "\" data-url=\"" . HtmlEncode(GetUrl($this->CopyUrl)) . "\" data-btn=\"AddBtn\">" . $Language->phrase("CopyLink") . "</a>";
                } else {
                    $opt->Body = "<a class=\"ew-row-link ew-copy\" title=\"" . $copycaption . "\" data-caption=\"" . $copycaption . "\" href=\"" . HtmlEncode(GetUrl($this->CopyUrl)) . "\">" . $Language->phrase("CopyLink") . "</a>";
                }
                $inlineCopyCaption = $Language->phrase("InlineCopyLink");
                $inlineCopyTitle = HtmlTitle($inlineCopyCaption);
                if ($this->UseAjaxActions) {
                    $opt->Body .= "<a class=\"ew-row-link ew-inline-copy\" title=\"" . $inlineCopyTitle . "\" data-caption=\"" . $inlineCopyTitle . "\" data-ew-action=\"inline\" data-action=\"copy\" data-position=\"top\" data-key=\"" . HtmlEncode($this->getKey(true)) . "\" data-url=\"" . HtmlEncode($this->InlineCopyUrl) . "\">" . $inlineCopyCaption . "</a>";
                } else {
                    $opt->Body .= "<a class=\"ew-row-link ew-inline-copy\" title=\"" . $inlineCopyTitle . "\" data-caption=\"" . $inlineCopyTitle . "\" href=\"" . HtmlEncode(GetUrl($this->InlineCopyUrl)) . "\">" . $inlineCopyCaption . "</a>";
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

        // Set up list action buttons
        $opt = $this->ListOptions["listactions"];
        if ($opt && !$this->isExport() && !$this->CurrentAction) {
            $body = "";
            $links = [];
            foreach ($this->ListActions->Items as $listaction) {
                $action = $listaction->Action;
                $allowed = $listaction->Allow;
                if ($listaction->Select == ACTION_SINGLE && $allowed) {
                    $caption = $listaction->Caption;
                    $icon = ($listaction->Icon != "") ? "<i class=\"" . HtmlEncode(str_replace(" ew-icon", "", $listaction->Icon)) . "\" data-caption=\"" . HtmlTitle($caption) . "\"></i> " : "";
                    $link = "<li><button type=\"button\" class=\"dropdown-item ew-action ew-list-action\" data-caption=\"" . HtmlTitle($caption) . "\" data-ew-action=\"submit\" form=\"forders2list\" data-key=\"" . $this->keyToJson(true) . "\"" . $listaction->toDataAttrs() . ">" . $icon . " " . $listaction->Caption . "</button></li>";
                    if ($link != "") {
                        $links[] = $link;
                        if ($body == "") { // Setup first button
                            $body = "<button type=\"button\" class=\"btn btn-default ew-action ew-list-action\" title=\"" . HtmlTitle($caption) . "\" data-caption=\"" . HtmlTitle($caption) . "\" data-ew-action=\"submit\" form=\"forders2list\" data-key=\"" . $this->keyToJson(true) . "\"" . $listaction->toDataAttrs() . ">" . $icon . " " . $listaction->Caption . "</button>";
                        }
                    }
                }
            }
            if (count($links) > 1) { // More than one buttons, use dropdown
                $body = "<button type=\"button\" class=\"dropdown-toggle btn btn-default ew-actions\" title=\"" . HtmlTitle($Language->phrase("ListActionButton")) . "\" data-bs-toggle=\"dropdown\">" . $Language->phrase("ListActionButton") . "</button>";
                $content = "";
                foreach ($links as $link) {
                    $content .= "<li>" . $link . "</li>";
                }
                $body .= "<ul class=\"dropdown-menu" . ($opt->OnLeft ? "" : " dropdown-menu-right") . "\">" . $content . "</ul>";
                $body = "<div class=\"btn-group btn-group-sm\">" . $body . "</div>";
            }
            if (count($links) > 0) {
                $opt->Body = $body;
            }
        }

        // "checkbox"
        $opt = $this->ListOptions["checkbox"];
        $opt->Body = "<div class=\"form-check\"><input type=\"checkbox\" id=\"key_m_" . $this->RowCount . "\" name=\"key_m[]\" class=\"form-check-input ew-multi-select\" value=\"" . HtmlEncode($this->OrderID->CurrentValue) . "\" data-ew-action=\"select-key\"></div>";
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
        $options = &$this->OtherOptions;
        $option = $options["addedit"];

        // Add
        $item = &$option->add("add");
        $addcaption = HtmlTitle($Language->phrase("AddLink"));
        if ($this->ModalAdd && !IsMobile()) {
            $item->Body = "<a class=\"ew-add-edit ew-add\" title=\"" . $addcaption . "\" data-table=\"orders2\" data-caption=\"" . $addcaption . "\" data-ew-action=\"modal\" data-action=\"add\" data-ajax=\"" . ($this->UseAjaxActions ? "true" : "false") . "\" data-url=\"" . HtmlEncode(GetUrl($this->AddUrl)) . "\" data-btn=\"AddBtn\">" . $Language->phrase("AddLink") . "</a>";
        } else {
            $item->Body = "<a class=\"ew-add-edit ew-add\" title=\"" . $addcaption . "\" data-caption=\"" . $addcaption . "\" href=\"" . HtmlEncode(GetUrl($this->AddUrl)) . "\">" . $Language->phrase("AddLink") . "</a>";
        }
        $item->Visible = $this->AddUrl != "" && $Security->canAdd();

        // Inline Add
        $item = &$option->add("inlineadd");
        if ($this->UseAjaxActions) {
            $item->Body = "<button class=\"ew-add-edit ew-inline-add\" title=\"" . $Language->phrase("InlineAddLink", true) . "\" data-caption=\"" . $Language->phrase("InlineAddLink", true) . "\" data-ew-action=\"inline\" data-action=\"add\" data-position=\"top\" data-url=\"" . HtmlEncode(GetUrl($this->InlineAddUrl)) . "\">" . $Language->phrase("InlineAddLink") . "</button>";
        } else {
            $item->Body = "<a class=\"ew-add-edit ew-inline-add\" title=\"" . HtmlTitle($Language->phrase("InlineAddLink")) . "\" data-caption=\"" . HtmlTitle($Language->phrase("InlineAddLink")) . "\" href=\"" . HtmlEncode(GetUrl($this->InlineAddUrl)) . "\">" . $Language->phrase("InlineAddLink") . "</a>";
        }
        $item->Visible = $this->InlineAddUrl != "" && $Security->canAdd();
        $item = &$option->add("gridadd");
        if ($this->ModalGridAdd && !IsMobile()) {
            $item->Body = "<button class=\"ew-add-edit ew-grid-add\" title=\"" . $Language->phrase("GridAddLink", true) . "\" data-caption=\"" . $Language->phrase("GridAddLink", true) . "\" data-ew-action=\"modal\" data-btn=\"AddBtn\" data-url=\"" . HtmlEncode(GetUrl($this->GridAddUrl)) . "\">" . $Language->phrase("GridAddLink") . "</button>";
        } else {
            $item->Body = "<a class=\"ew-add-edit ew-grid-add\" title=\"" . $Language->phrase("GridAddLink", true) . "\" data-caption=\"" . $Language->phrase("GridAddLink", true) . "\" href=\"" . HtmlEncode(GetUrl($this->GridAddUrl)) . "\">" . $Language->phrase("GridAddLink") . "</a>";
        }
        $item->Visible = $this->GridAddUrl != "" && $Security->canAdd();

        // Add grid edit
        $option = $options["addedit"];
        $item = &$option->add("gridedit");
        if ($this->ModalGridEdit && !IsMobile()) {
            $item->Body = "<a class=\"ew-add-edit ew-grid-edit\" title=\"" . $Language->phrase("GridEditLink", true) . "\" data-caption=\"" . $Language->phrase("GridEditLink", true) . "\" data-ew-action=\"modal\" data-btn=\"GridSaveLink\" data-url=\"" . HtmlEncode(GetUrl($this->GridEditUrl)) . "\">" . $Language->phrase("GridEditLink") . "</a>";
        } else {
            $item->Body = "<a class=\"ew-add-edit ew-grid-edit\" title=\"" . $Language->phrase("GridEditLink", true) . "\" data-caption=\"" . $Language->phrase("GridEditLink", true) . "\" href=\"" . HtmlEncode(GetUrl($this->GridEditUrl)) . "\">" . $Language->phrase("GridEditLink") . "</a>";
        }
        $item->Visible = $this->GridEditUrl != "" && $Security->canEdit();
        $option = $options["action"];

        // Add multi update
        $item = &$option->add("multiupdate");
        $item->Body = "<button type=\"button\" class=\"ew-action ew-multi-update\" title=\"" .
            $Language->phrase("UpdateSelectedLink", true) . "\" data-table=\"orders2\" data-caption=\"" .
            $Language->phrase("UpdateSelectedLink", true) . "\" form=\"forders2list\" data-ew-action=\"" .
            ($this->ModalUpdate && !IsMobile() ? "modal" : "submit") . "\"" .
            ($this->ModalUpdate && !IsMobile() ? " data-action=\"update\"" : "") .
            ($this->UseAjaxActions ? " data-ajax=\"true\"" : "") .
            " data-url=\"" . GetUrl($this->MultiUpdateUrl) . "\">" . $Language->phrase("UpdateSelectedLink") . "</button>";
        $item->Visible = $Security->canEdit();

        // Add multi edit
        $item = &$option->add("multiedit");
        $item->Body = "<button type=\"button\" class=\"ew-action ew-multi-edit\" title=\"" .
            $Language->phrase("EditSelectedLink", true) . "\" data-table=\"orders2\" data-caption=\"" .
            $Language->phrase("EditSelectedLink", true) . "\" form=\"forders2list\" data-ew-action=\"" .
            ($this->ModalMultiEdit && !IsMobile() ? "modal" : "submit") . "\"" .
            ($this->ModalMultiEdit && !IsMobile() ? " data-action=\"multiedit\" data-btn=\"SaveBtn\"" : "") .
            ($this->UseAjaxActions ? " data-ajax=\"true\"" : "") .
            " data-url=\"" . GetUrl($this->MultiEditUrl) . "\">" . $Language->phrase("EditSelectedLink") . "</button>";
        $item->Visible = $this->MultiEditUrl != "" && $Security->canEdit();

        // Show column list for column visibility
        if ($this->UseColumnVisibility) {
            $option = $this->OtherOptions["column"];
            $item = &$option->addGroupOption();
            $item->Body = "";
            $item->Visible = $this->UseColumnVisibility;
            $option->add("OrderID", $this->createColumnOption("OrderID"));
            $option->add("CustomerID", $this->createColumnOption("CustomerID"));
            $option->add("EmployeeID", $this->createColumnOption("EmployeeID"));
            $option->add("OrderDate", $this->createColumnOption("OrderDate"));
            $option->add("RequiredDate", $this->createColumnOption("RequiredDate"));
            $option->add("ShippedDate", $this->createColumnOption("ShippedDate"));
            $option->add("Freight", $this->createColumnOption("Freight"));
            $option->add("ShipAddress", $this->createColumnOption("ShipAddress"));
            $option->add("ShipCity", $this->createColumnOption("ShipCity"));
            $option->add("ShipCountry", $this->createColumnOption("ShipCountry"));
        }

        // Set up options default
        foreach ($options as $name => $option) {
            if ($name != "column") { // Always use dropdown for column
                $option->UseDropDownButton = true;
                $option->UseButtonGroup = true;
            }
            //$option->ButtonClass = ""; // Class for button group
            $item = &$option->addGroupOption();
            $item->Body = "";
            $item->Visible = false;
        }
        $options["addedit"]->DropDownButtonPhrase = $Language->phrase("ButtonAddEdit");
        $options["detail"]->DropDownButtonPhrase = $Language->phrase("ButtonDetails");
        $options["action"]->DropDownButtonPhrase = $Language->phrase("ButtonActions");

        // Filter button
        $item = &$this->FilterOptions->add("savecurrentfilter");
        $item->Body = "<a class=\"ew-save-filter\" data-form=\"forders2srch\" data-ew-action=\"none\">" . $Language->phrase("SaveCurrentFilter") . "</a>";
        $item->Visible = true;
        $item = &$this->FilterOptions->add("deletefilter");
        $item->Body = "<a class=\"ew-delete-filter\" data-form=\"forders2srch\" data-ew-action=\"none\">" . $Language->phrase("DeleteFilter") . "</a>";
        $item->Visible = true;
        $this->FilterOptions->UseDropDownButton = true;
        $this->FilterOptions->UseButtonGroup = !$this->FilterOptions->UseDropDownButton;
        $this->FilterOptions->DropDownButtonPhrase = $Language->phrase("Filters");

        // Add group option item
        $item = &$this->FilterOptions->addGroupOption();
        $item->Body = "";
        $item->Visible = false;
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
        if (!$this->isGridAdd() && !$this->isGridEdit() && !$this->isMultiEdit()) { // Not grid add/grid edit/multi edit mode
            $option = $options["action"];
            // Set up list action buttons
            foreach ($this->ListActions->Items as $listaction) {
                if ($listaction->Select == ACTION_MULTIPLE) {
                    $item = &$option->add("custom_" . $listaction->Action);
                    $caption = $listaction->Caption;
                    $icon = ($listaction->Icon != "") ? '<i class="' . HtmlEncode($listaction->Icon) . '" data-caption="' . HtmlEncode($caption) . '"></i>' . $caption : $caption;
                    $item->Body = '<button type="button" class="btn btn-default ew-action ew-list-action" title="' . HtmlEncode($caption) . '" data-caption="' . HtmlEncode($caption) . '" data-ew-action="submit" form="forders2list"' . $listaction->toDataAttrs() . '>' . $icon . '</button>';
                    $item->Visible = $listaction->Allow;
                }
            }

            // Hide multi edit, grid edit and other options
            if ($this->TotalRecords <= 0) {
                $option = $options["addedit"];
                $item = $option["gridedit"];
                if ($item) {
                    $item->Visible = false;
                }
                $option = $options["action"];
                $option->hideAllOptions();
            }
        } else { // Grid add/grid edit/multi edit mode
            // Hide all options first
            foreach ($options as $option) {
                $option->hideAllOptions();
            }
            $pageUrl = $this->pageUrl(false);

            // Grid-Add
            if ($this->isGridAdd()) {
                    if ($this->AllowAddDeleteRow) {
                        // Add add blank row
                        $option = $options["addedit"];
                        $option->UseDropDownButton = false;
                        $item = &$option->add("addblankrow");
                        $item->Body = "<a type=\"button\" class=\"ew-add-edit ew-add-blank-row\" title=\"" . HtmlTitle($Language->phrase("AddBlankRow")) . "\" data-caption=\"" . HtmlTitle($Language->phrase("AddBlankRow")) . "\" data-ew-action=\"add-grid-row\">" . $Language->phrase("AddBlankRow") . "</a>";
                        $item->Visible = $Security->canAdd();
                    }
                if (!($this->ModalGridAdd && !IsMobile())) {
                    $option = $options["action"];
                    $option->UseDropDownButton = false;
                    // Add grid insert
                    $item = &$option->add("gridinsert");
                    $item->Body = "<button class=\"ew-action ew-grid-insert\" title=\"" . HtmlTitle($Language->phrase("GridInsertLink")) . "\" data-caption=\"" . HtmlTitle($Language->phrase("GridInsertLink")) . "\" form=\"forders2list\" formaction=\"" . GetUrl($this->pageName()) . "\">" . $Language->phrase("GridInsertLink") . "</button>";
                    // Add grid cancel
                    $item = &$option->add("gridcancel");
                    $cancelurl = $this->addMasterUrl($pageUrl . "action=cancel");
                    $item->Body = "<a type=\"button\" class=\"ew-action ew-grid-cancel\" title=\"" . HtmlTitle($Language->phrase("GridCancelLink")) . "\" data-caption=\"" . HtmlTitle($Language->phrase("GridCancelLink")) . "\" href=\"" . $cancelurl . "\">" . $Language->phrase("GridCancelLink") . "</a>";
                }
            }

            // Grid-Edit
            if ($this->isGridEdit()) {
                    if ($this->AllowAddDeleteRow) {
                        // Add add blank row
                        $option = $options["addedit"];
                        $option->UseDropDownButton = false;
                        $item = &$option->add("addblankrow");
                        $item->Body = "<button class=\"ew-add-edit ew-add-blank-row\" title=\"" . HtmlTitle($Language->phrase("AddBlankRow")) . "\" data-caption=\"" . HtmlTitle($Language->phrase("AddBlankRow")) . "\" data-ew-action=\"add-grid-row\">" . $Language->phrase("AddBlankRow") . "</button>";
                        $item->Visible = $Security->canAdd();
                    }
                if (!($this->ModalGridEdit && !IsMobile())) {
                    $option = $options["action"];
                    $option->UseDropDownButton = false;
                        $item = &$option->add("gridsave");
                        $item->Body = "<button class=\"ew-action ew-grid-save\" title=\"" . HtmlTitle($Language->phrase("GridSaveLink")) . "\" data-caption=\"" . HtmlTitle($Language->phrase("GridSaveLink")) . "\" form=\"forders2list\" formaction=\"" . GetUrl($this->pageName()) . "\">" . $Language->phrase("GridSaveLink") . "</button>";
                        $item = &$option->add("gridcancel");
                        $cancelurl = $this->addMasterUrl($pageUrl . "action=cancel");
                        $item->Body = "<a class=\"ew-action ew-grid-cancel\" title=\"" . HtmlTitle($Language->phrase("GridCancelLink")) . "\" data-caption=\"" . HtmlTitle($Language->phrase("GridCancelLink")) . "\" href=\"" . $cancelurl . "\">" . $Language->phrase("GridCancelLink") . "</a>";
                }
            }

            // Multi-Edit
            if ($this->isMultiEdit()) {
                if (!$this->ModalMultiEdit) {
                    $option = $options["action"];
                    $option->UseDropDownButton = false;
                        $item = &$option->add("gridsave");
                        $item->Body = "<button class=\"ew-action ew-grid-save\" title=\"" . HtmlTitle($Language->phrase("GridSaveLink")) . "\" data-caption=\"" . HtmlTitle($Language->phrase("GridSaveLink")) . "\" form=\"forders2list\" formaction=\"" . GetUrl($this->pageName()) . "\">" . $Language->phrase("GridSaveLink") . "</button>";
                        $item = &$option->add("gridcancel");
                        $cancelurl = $this->addMasterUrl($pageUrl . "action=cancel");
                        $item->Body = "<a class=\"ew-action ew-grid-cancel\" title=\"" . HtmlTitle($Language->phrase("GridCancelLink")) . "\" data-caption=\"" . HtmlTitle($Language->phrase("GridCancelLink")) . "\" href=\"" . $cancelurl . "\">" . $Language->phrase("GridCancelLink") . "</a>";
                }
            }
        }
    }

    // Process list action
    protected function processListAction()
    {
        global $Language, $Security, $Response;
        $userlist = "";
        $user = "";
        $filter = $this->getFilterFromRecordKeys();
        $userAction = Post("action", "");
        if ($filter != "" && $userAction != "") {
            // Clear current action
            $this->CurrentAction = "";
            $this->UserAction = $userAction;
            // Check permission first
            $actionCaption = $userAction;
            if (array_key_exists($userAction, $this->ListActions->Items)) {
                $actionCaption = $this->ListActions[$userAction]->Caption;
                if (!$this->ListActions[$userAction]->Allow) {
                    $errmsg = str_replace('%s', $actionCaption, $Language->phrase("CustomActionNotAllowed"));
                    if (Post("ajax") == $userAction) { // Ajax
                        echo "<p class=\"text-danger\">" . $errmsg . "</p>";
                        return true;
                    } else {
                        $this->setFailureMessage($errmsg);
                        return false;
                    }
                }
            }
            $this->CurrentFilter = $filter;
            $sql = $this->getCurrentSql();
            $conn = $this->getConnection();
            $rows = ExecuteRows($sql, $conn);
            $count = count($rows);
            $this->ActionValue = Post("actionvalue");

            // Call row action event
            if ($count > 0) {
                if ($this->UseTransaction) {
                    $conn->beginTransaction();
                }
                $this->SelectedCount = $count;
                $this->SelectedIndex = 0;
                foreach ($rows as $row) {
                    $this->SelectedIndex++;
                    $processed = $this->rowCustomAction($userAction, $row);
                    if (!$processed) {
                        break;
                    }
                }
                if ($processed) {
                    if ($this->UseTransaction) { // Commit transaction
                        $conn->commit();
                    }
                    if ($this->getSuccessMessage() == "" && !ob_get_length() && !$Response->getBody()->getSize()) { // No output
                        $this->setSuccessMessage(str_replace('%s', $actionCaption, $Language->phrase("CustomActionCompleted"))); // Set up success message
                    }
                } else {
                    if ($this->UseTransaction) { // Rollback transaction
                        $conn->rollback();
                    }

                    // Set up error message
                    if ($this->getSuccessMessage() != "" || $this->getFailureMessage() != "") {
                        // Use the message, do nothing
                    } elseif ($this->CancelMessage != "") {
                        $this->setFailureMessage($this->CancelMessage);
                        $this->CancelMessage = "";
                    } else {
                        $this->setFailureMessage(str_replace('%s', $actionCaption, $Language->phrase("CustomActionFailed")));
                    }
                }
            }
            if (Post("ajax") == $userAction) { // Ajax
                if ($this->getSuccessMessage() != "") {
                    echo "<p class=\"text-success\">" . $this->getSuccessMessage() . "</p>";
                    $this->clearSuccessMessage(); // Clear message
                }
                if ($this->getFailureMessage() != "") {
                    echo "<p class=\"text-danger\">" . $this->getFailureMessage() . "</p>";
                    $this->clearFailureMessage(); // Clear message
                }
                return true;
            }
        }
        return false; // Not ajax request
    }

    // Set up Grid
    public function setupGrid()
    {
        global $CurrentForm;
        if ($this->ExportAll && $this->isExport()) {
            $this->StopRecord = $this->TotalRecords;
        } else {
            // Set the last record to display
            if ($this->TotalRecords > $this->StartRecord + $this->DisplayRecords - 1) {
                $this->StopRecord = $this->StartRecord + $this->DisplayRecords - 1;
            } else {
                $this->StopRecord = $this->TotalRecords;
            }
        }

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
        if ($this->isAdd() || $this->isCopy() || $this->isInlineInserted()) {
            $this->RowIndex = 0;
            if ($this->UseInfiniteScroll) {
                $this->StopRecord = $this->StartRecord; // Show this record only
            }
        }
        if ($this->isEdit()) {
            $this->RowIndex = 1;
        }
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
                $this->RowAttrs->merge(["data-rowindex" => $this->RowIndex, "id" => "r0_orders2", "data-rowtype" => ROWTYPE_ADD]);
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
        if ($this->isCopy() && $this->InlineRowCount == 0 && !$this->loadRow()) { // Inline copy
            $this->CurrentAction = "add";
        }
        if ($this->isAdd() && $this->InlineRowCount == 0 || $this->isGridAdd()) {
            $this->loadRowValues(); // Load default values
            $this->OldKey = "";
            $this->setKey($this->OldKey);
        } elseif ($this->isInlineInserted() && $this->UseInfiniteScroll) {
            // Nothing to do, just use current values
        } elseif (!($this->isCopy() && $this->InlineRowCount == 0)) {
            $this->loadRowValues($this->Recordset); // Load row values
            if ($this->isGridEdit() || $this->isMultiEdit()) {
                $this->OldKey = $this->getKey(true); // Get from CurrentValue
                $this->setKey($this->OldKey);
            }
        }
        $this->RowType = ROWTYPE_VIEW; // Render view
        if (($this->isAdd() || $this->isCopy()) && $this->InlineRowCount == 0 || $this->isGridAdd()) { // Add
            $this->RowType = ROWTYPE_ADD; // Render add
        }
        if ($this->isGridAdd() && $this->EventCancelled && !$CurrentForm->hasValue($this->FormBlankRowName)) { // Insert failed
            $this->restoreCurrentRowFormValues($this->RowIndex); // Restore form values
        }
        if ($this->isEdit() || $this->isInlineUpdated() || $this->isInlineEditCancelled()) { // Inline edit/updated/cancelled
            if ($this->checkInlineEditKey() && $this->InlineRowCount == 0) {
                if ($this->isEdit()) { // Inline edit
                    $this->RowAction = "edit";
                    $this->RowType = ROWTYPE_EDIT; // Render edit
                } else { // Inline updated
                    $this->RowAction = "";
                    $this->RowType = ROWTYPE_VIEW; // Render view
                    $this->RowAttrs["data-oldkey"] = $this->getKey(); // Set up old key
                }
            } elseif ($this->UseInfiniteScroll) {
                $this->RowAction = "hide";
            }
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
        if ($this->isMultiEdit()) { // Multi edit
            if ($this->EventCancelled) {
                $this->restoreCurrentRowFormValues($this->RowIndex); // Restore form values
            }
            $this->RowType = ROWTYPE_EDIT; // Render edit
        }
        if ($this->isEdit() && $this->RowType == ROWTYPE_EDIT && $this->EventCancelled) { // Update failed
            $CurrentForm->Index = 1;
            $this->restoreFormValues(); // Restore form values
        }
        if ($this->isGridEdit() && ($this->RowType == ROWTYPE_EDIT || $this->RowType == ROWTYPE_ADD) && $this->EventCancelled) { // Update failed
            $this->restoreCurrentRowFormValues($this->RowIndex); // Restore form values
        }
        if ($this->isMultiEdit() && $this->RowType == ROWTYPE_EDIT && $this->EventCancelled) { // Update failed
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
            "id" => "r" . $this->RowCount . "_orders2",
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

    // Load default values
    protected function loadDefaultValues()
    {
    }

    // Load basic search values
    protected function loadBasicSearchValues()
    {
        $this->BasicSearch->setKeyword(Get(Config("TABLE_BASIC_SEARCH"), ""), false);
        if ($this->BasicSearch->Keyword != "" && $this->Command == "") {
            $this->Command = "search";
        }
        $this->BasicSearch->setType(Get(Config("TABLE_BASIC_SEARCH_TYPE"), ""), false);
    }

    // Load form values
    protected function loadFormValues()
    {
        // Load from form
        global $CurrentForm;
        $validate = !Config("SERVER_VALIDATE");

        // Check field name 'OrderID' first before field var 'x_OrderID'
        $val = $CurrentForm->hasValue("OrderID") ? $CurrentForm->getValue("OrderID") : $CurrentForm->getValue("x_OrderID");
        if (!$this->OrderID->IsDetailKey && !$this->isGridAdd() && !$this->isAdd()) {
            $this->OrderID->setFormValue($val);
        }

        // Check field name 'CustomerID' first before field var 'x_CustomerID'
        $val = $CurrentForm->hasValue("CustomerID") ? $CurrentForm->getValue("CustomerID") : $CurrentForm->getValue("x_CustomerID");
        if (!$this->CustomerID->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->CustomerID->Visible = false; // Disable update for API request
            } else {
                $this->CustomerID->setFormValue($val);
            }
        }
        if ($CurrentForm->hasValue("o_CustomerID")) {
            $this->CustomerID->setOldValue($CurrentForm->getValue("o_CustomerID"));
        }

        // Check field name 'EmployeeID' first before field var 'x_EmployeeID'
        $val = $CurrentForm->hasValue("EmployeeID") ? $CurrentForm->getValue("EmployeeID") : $CurrentForm->getValue("x_EmployeeID");
        if (!$this->EmployeeID->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->EmployeeID->Visible = false; // Disable update for API request
            } else {
                $this->EmployeeID->setFormValue($val);
            }
        }
        if ($CurrentForm->hasValue("o_EmployeeID")) {
            $this->EmployeeID->setOldValue($CurrentForm->getValue("o_EmployeeID"));
        }

        // Check field name 'OrderDate' first before field var 'x_OrderDate'
        $val = $CurrentForm->hasValue("OrderDate") ? $CurrentForm->getValue("OrderDate") : $CurrentForm->getValue("x_OrderDate");
        if (!$this->OrderDate->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->OrderDate->Visible = false; // Disable update for API request
            } else {
                $this->OrderDate->setFormValue($val, true, $validate);
            }
            $this->OrderDate->CurrentValue = UnFormatDateTime($this->OrderDate->CurrentValue, $this->OrderDate->formatPattern());
        }
        if ($CurrentForm->hasValue("o_OrderDate")) {
            $this->OrderDate->setOldValue($CurrentForm->getValue("o_OrderDate"));
        }

        // Check field name 'RequiredDate' first before field var 'x_RequiredDate'
        $val = $CurrentForm->hasValue("RequiredDate") ? $CurrentForm->getValue("RequiredDate") : $CurrentForm->getValue("x_RequiredDate");
        if (!$this->RequiredDate->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->RequiredDate->Visible = false; // Disable update for API request
            } else {
                $this->RequiredDate->setFormValue($val, true, $validate);
            }
            $this->RequiredDate->CurrentValue = UnFormatDateTime($this->RequiredDate->CurrentValue, $this->RequiredDate->formatPattern());
        }
        if ($CurrentForm->hasValue("o_RequiredDate")) {
            $this->RequiredDate->setOldValue($CurrentForm->getValue("o_RequiredDate"));
        }

        // Check field name 'ShippedDate' first before field var 'x_ShippedDate'
        $val = $CurrentForm->hasValue("ShippedDate") ? $CurrentForm->getValue("ShippedDate") : $CurrentForm->getValue("x_ShippedDate");
        if (!$this->ShippedDate->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->ShippedDate->Visible = false; // Disable update for API request
            } else {
                $this->ShippedDate->setFormValue($val, true, $validate);
            }
            $this->ShippedDate->CurrentValue = UnFormatDateTime($this->ShippedDate->CurrentValue, $this->ShippedDate->formatPattern());
        }
        if ($CurrentForm->hasValue("o_ShippedDate")) {
            $this->ShippedDate->setOldValue($CurrentForm->getValue("o_ShippedDate"));
        }

        // Check field name 'Freight' first before field var 'x_Freight'
        $val = $CurrentForm->hasValue("Freight") ? $CurrentForm->getValue("Freight") : $CurrentForm->getValue("x_Freight");
        if (!$this->Freight->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->Freight->Visible = false; // Disable update for API request
            } else {
                $this->Freight->setFormValue($val, true, $validate);
            }
        }
        if ($CurrentForm->hasValue("o_Freight")) {
            $this->Freight->setOldValue($CurrentForm->getValue("o_Freight"));
        }

        // Check field name 'ShipAddress' first before field var 'x_ShipAddress'
        $val = $CurrentForm->hasValue("ShipAddress") ? $CurrentForm->getValue("ShipAddress") : $CurrentForm->getValue("x_ShipAddress");
        if (!$this->ShipAddress->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->ShipAddress->Visible = false; // Disable update for API request
            } else {
                $this->ShipAddress->setFormValue($val);
            }
        }
        if ($CurrentForm->hasValue("o_ShipAddress")) {
            $this->ShipAddress->setOldValue($CurrentForm->getValue("o_ShipAddress"));
        }

        // Check field name 'ShipCity' first before field var 'x_ShipCity'
        $val = $CurrentForm->hasValue("ShipCity") ? $CurrentForm->getValue("ShipCity") : $CurrentForm->getValue("x_ShipCity");
        if (!$this->ShipCity->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->ShipCity->Visible = false; // Disable update for API request
            } else {
                $this->ShipCity->setFormValue($val);
            }
        }
        if ($CurrentForm->hasValue("o_ShipCity")) {
            $this->ShipCity->setOldValue($CurrentForm->getValue("o_ShipCity"));
        }

        // Check field name 'ShipCountry' first before field var 'x_ShipCountry'
        $val = $CurrentForm->hasValue("ShipCountry") ? $CurrentForm->getValue("ShipCountry") : $CurrentForm->getValue("x_ShipCountry");
        if (!$this->ShipCountry->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->ShipCountry->Visible = false; // Disable update for API request
            } else {
                $this->ShipCountry->setFormValue($val);
            }
        }
        if ($CurrentForm->hasValue("o_ShipCountry")) {
            $this->ShipCountry->setOldValue($CurrentForm->getValue("o_ShipCountry"));
        }
    }

    // Restore form values
    public function restoreFormValues()
    {
        global $CurrentForm;
        if (!$this->isGridAdd() && !$this->isAdd()) {
            $this->OrderID->CurrentValue = $this->OrderID->FormValue;
        }
        $this->CustomerID->CurrentValue = $this->CustomerID->FormValue;
        $this->EmployeeID->CurrentValue = $this->EmployeeID->FormValue;
        $this->OrderDate->CurrentValue = $this->OrderDate->FormValue;
        $this->OrderDate->CurrentValue = UnFormatDateTime($this->OrderDate->CurrentValue, $this->OrderDate->formatPattern());
        $this->RequiredDate->CurrentValue = $this->RequiredDate->FormValue;
        $this->RequiredDate->CurrentValue = UnFormatDateTime($this->RequiredDate->CurrentValue, $this->RequiredDate->formatPattern());
        $this->ShippedDate->CurrentValue = $this->ShippedDate->FormValue;
        $this->ShippedDate->CurrentValue = UnFormatDateTime($this->ShippedDate->CurrentValue, $this->ShippedDate->formatPattern());
        $this->Freight->CurrentValue = $this->Freight->FormValue;
        $this->ShipAddress->CurrentValue = $this->ShipAddress->FormValue;
        $this->ShipCity->CurrentValue = $this->ShipCity->FormValue;
        $this->ShipCountry->CurrentValue = $this->ShipCountry->FormValue;
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
            if (!$this->EventCancelled) {
                $this->HashValue = $this->getRowHash($row); // Get hash value for record
            }
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
        $this->OrderID->setDbValue($row['OrderID']);
        $this->CustomerID->setDbValue($row['CustomerID']);
        $this->EmployeeID->setDbValue($row['EmployeeID']);
        $this->OrderDate->setDbValue($row['OrderDate']);
        $this->RequiredDate->setDbValue($row['RequiredDate']);
        $this->ShippedDate->setDbValue($row['ShippedDate']);
        $this->ShipVia->setDbValue($row['ShipVia']);
        $this->Freight->setDbValue($row['Freight']);
        $this->ShipName->setDbValue($row['ShipName']);
        $this->ShipAddress->setDbValue($row['ShipAddress']);
        $this->ShipCity->setDbValue($row['ShipCity']);
        $this->ShipRegion->setDbValue($row['ShipRegion']);
        $this->ShipPostalCode->setDbValue($row['ShipPostalCode']);
        $this->ShipCountry->setDbValue($row['ShipCountry']);
    }

    // Return a row with default values
    protected function newRow()
    {
        $row = [];
        $row['OrderID'] = $this->OrderID->DefaultValue;
        $row['CustomerID'] = $this->CustomerID->DefaultValue;
        $row['EmployeeID'] = $this->EmployeeID->DefaultValue;
        $row['OrderDate'] = $this->OrderDate->DefaultValue;
        $row['RequiredDate'] = $this->RequiredDate->DefaultValue;
        $row['ShippedDate'] = $this->ShippedDate->DefaultValue;
        $row['ShipVia'] = $this->ShipVia->DefaultValue;
        $row['Freight'] = $this->Freight->DefaultValue;
        $row['ShipName'] = $this->ShipName->DefaultValue;
        $row['ShipAddress'] = $this->ShipAddress->DefaultValue;
        $row['ShipCity'] = $this->ShipCity->DefaultValue;
        $row['ShipRegion'] = $this->ShipRegion->DefaultValue;
        $row['ShipPostalCode'] = $this->ShipPostalCode->DefaultValue;
        $row['ShipCountry'] = $this->ShipCountry->DefaultValue;
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
        $this->InlineEditUrl = $this->getInlineEditUrl();
        $this->CopyUrl = $this->getCopyUrl();
        $this->InlineCopyUrl = $this->getInlineCopyUrl();
        $this->DeleteUrl = $this->getDeleteUrl();

        // Call Row_Rendering event
        $this->rowRendering();

        // Common render codes for all row types

        // OrderID

        // CustomerID

        // EmployeeID

        // OrderDate

        // RequiredDate

        // ShippedDate

        // ShipVia

        // Freight

        // ShipName

        // ShipAddress

        // ShipCity

        // ShipRegion

        // ShipPostalCode

        // ShipCountry

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
            $this->Freight->ViewValue = FormatCurrency($this->Freight->ViewValue, $this->Freight->formatPattern());

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

            // Freight
            $this->Freight->HrefValue = "";
            $this->Freight->TooltipValue = "";

            // ShipAddress
            $this->ShipAddress->HrefValue = "";
            $this->ShipAddress->TooltipValue = "";

            // ShipCity
            $this->ShipCity->HrefValue = "";
            $this->ShipCity->TooltipValue = "";

            // ShipCountry
            $this->ShipCountry->HrefValue = "";
            $this->ShipCountry->TooltipValue = "";
        } elseif ($this->RowType == ROWTYPE_ADD) {
            // OrderID

            // CustomerID
            $this->CustomerID->setupEditAttributes();
            $curVal = trim(strval($this->CustomerID->CurrentValue));
            if ($curVal != "") {
                $this->CustomerID->ViewValue = $this->CustomerID->lookupCacheOption($curVal);
            } else {
                $this->CustomerID->ViewValue = $this->CustomerID->Lookup !== null && is_array($this->CustomerID->lookupOptions()) && count($this->CustomerID->lookupOptions()) > 0 ? $curVal : null;
            }
            if ($this->CustomerID->ViewValue !== null) { // Load from cache
                $this->CustomerID->EditValue = array_values($this->CustomerID->lookupOptions());
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = SearchFilter("`CustomerID`", "=", $this->CustomerID->CurrentValue, DATATYPE_STRING, "");
                }
                $sqlWrk = $this->CustomerID->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCacheImpl($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->CustomerID->EditValue = $arwrk;
            }
            $this->CustomerID->PlaceHolder = RemoveHtml($this->CustomerID->caption());

            // EmployeeID
            $this->EmployeeID->setupEditAttributes();
            $curVal = trim(strval($this->EmployeeID->CurrentValue));
            if ($curVal != "") {
                $this->EmployeeID->ViewValue = $this->EmployeeID->lookupCacheOption($curVal);
            } else {
                $this->EmployeeID->ViewValue = $this->EmployeeID->Lookup !== null && is_array($this->EmployeeID->lookupOptions()) && count($this->EmployeeID->lookupOptions()) > 0 ? $curVal : null;
            }
            if ($this->EmployeeID->ViewValue !== null) { // Load from cache
                $this->EmployeeID->EditValue = array_values($this->EmployeeID->lookupOptions());
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = SearchFilter("`EmployeeID`", "=", $this->EmployeeID->CurrentValue, DATATYPE_NUMBER, "");
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
            $this->OrderDate->EditValue = HtmlEncode(FormatDateTime($this->OrderDate->CurrentValue, $this->OrderDate->formatPattern()));
            $this->OrderDate->PlaceHolder = RemoveHtml($this->OrderDate->caption());

            // RequiredDate
            $this->RequiredDate->setupEditAttributes();
            $this->RequiredDate->EditValue = HtmlEncode(FormatDateTime($this->RequiredDate->CurrentValue, $this->RequiredDate->formatPattern()));
            $this->RequiredDate->PlaceHolder = RemoveHtml($this->RequiredDate->caption());

            // ShippedDate
            $this->ShippedDate->setupEditAttributes();
            $this->ShippedDate->EditValue = HtmlEncode(FormatDateTime($this->ShippedDate->CurrentValue, $this->ShippedDate->formatPattern()));
            $this->ShippedDate->PlaceHolder = RemoveHtml($this->ShippedDate->caption());

            // Freight
            $this->Freight->setupEditAttributes();
            $this->Freight->EditValue = HtmlEncode($this->Freight->CurrentValue);
            $this->Freight->PlaceHolder = RemoveHtml($this->Freight->caption());
            if (strval($this->Freight->EditValue) != "" && is_numeric($this->Freight->EditValue)) {
                $this->Freight->EditValue = FormatNumber($this->Freight->EditValue, null);
            }

            // ShipAddress
            $this->ShipAddress->setupEditAttributes();
            if (!$this->ShipAddress->Raw) {
                $this->ShipAddress->CurrentValue = HtmlDecode($this->ShipAddress->CurrentValue);
            }
            $this->ShipAddress->EditValue = HtmlEncode($this->ShipAddress->CurrentValue);
            $this->ShipAddress->PlaceHolder = RemoveHtml($this->ShipAddress->caption());

            // ShipCity
            $this->ShipCity->setupEditAttributes();
            if (!$this->ShipCity->Raw) {
                $this->ShipCity->CurrentValue = HtmlDecode($this->ShipCity->CurrentValue);
            }
            $this->ShipCity->EditValue = HtmlEncode($this->ShipCity->CurrentValue);
            $this->ShipCity->PlaceHolder = RemoveHtml($this->ShipCity->caption());

            // ShipCountry
            $this->ShipCountry->setupEditAttributes();
            if (!$this->ShipCountry->Raw) {
                $this->ShipCountry->CurrentValue = HtmlDecode($this->ShipCountry->CurrentValue);
            }
            $this->ShipCountry->EditValue = HtmlEncode($this->ShipCountry->CurrentValue);
            $this->ShipCountry->PlaceHolder = RemoveHtml($this->ShipCountry->caption());

            // Add refer script

            // OrderID
            $this->OrderID->HrefValue = "";

            // CustomerID
            $this->CustomerID->HrefValue = "";

            // EmployeeID
            $this->EmployeeID->HrefValue = "";

            // OrderDate
            $this->OrderDate->HrefValue = "";

            // RequiredDate
            $this->RequiredDate->HrefValue = "";

            // ShippedDate
            $this->ShippedDate->HrefValue = "";

            // Freight
            $this->Freight->HrefValue = "";

            // ShipAddress
            $this->ShipAddress->HrefValue = "";

            // ShipCity
            $this->ShipCity->HrefValue = "";

            // ShipCountry
            $this->ShipCountry->HrefValue = "";
        } elseif ($this->RowType == ROWTYPE_EDIT) {
            // OrderID
            $this->OrderID->setupEditAttributes();
            $this->OrderID->EditValue = $this->OrderID->CurrentValue;

            // CustomerID
            $this->CustomerID->setupEditAttributes();
            $curVal = trim(strval($this->CustomerID->CurrentValue));
            if ($curVal != "") {
                $this->CustomerID->ViewValue = $this->CustomerID->lookupCacheOption($curVal);
            } else {
                $this->CustomerID->ViewValue = $this->CustomerID->Lookup !== null && is_array($this->CustomerID->lookupOptions()) && count($this->CustomerID->lookupOptions()) > 0 ? $curVal : null;
            }
            if ($this->CustomerID->ViewValue !== null) { // Load from cache
                $this->CustomerID->EditValue = array_values($this->CustomerID->lookupOptions());
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = SearchFilter("`CustomerID`", "=", $this->CustomerID->CurrentValue, DATATYPE_STRING, "");
                }
                $sqlWrk = $this->CustomerID->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCacheImpl($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->CustomerID->EditValue = $arwrk;
            }
            $this->CustomerID->PlaceHolder = RemoveHtml($this->CustomerID->caption());

            // EmployeeID
            $this->EmployeeID->setupEditAttributes();
            $curVal = trim(strval($this->EmployeeID->CurrentValue));
            if ($curVal != "") {
                $this->EmployeeID->ViewValue = $this->EmployeeID->lookupCacheOption($curVal);
            } else {
                $this->EmployeeID->ViewValue = $this->EmployeeID->Lookup !== null && is_array($this->EmployeeID->lookupOptions()) && count($this->EmployeeID->lookupOptions()) > 0 ? $curVal : null;
            }
            if ($this->EmployeeID->ViewValue !== null) { // Load from cache
                $this->EmployeeID->EditValue = array_values($this->EmployeeID->lookupOptions());
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = SearchFilter("`EmployeeID`", "=", $this->EmployeeID->CurrentValue, DATATYPE_NUMBER, "");
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
            $this->OrderDate->EditValue = HtmlEncode(FormatDateTime($this->OrderDate->CurrentValue, $this->OrderDate->formatPattern()));
            $this->OrderDate->PlaceHolder = RemoveHtml($this->OrderDate->caption());

            // RequiredDate
            $this->RequiredDate->setupEditAttributes();
            $this->RequiredDate->EditValue = HtmlEncode(FormatDateTime($this->RequiredDate->CurrentValue, $this->RequiredDate->formatPattern()));
            $this->RequiredDate->PlaceHolder = RemoveHtml($this->RequiredDate->caption());

            // ShippedDate
            $this->ShippedDate->setupEditAttributes();
            $this->ShippedDate->EditValue = HtmlEncode(FormatDateTime($this->ShippedDate->CurrentValue, $this->ShippedDate->formatPattern()));
            $this->ShippedDate->PlaceHolder = RemoveHtml($this->ShippedDate->caption());

            // Freight
            $this->Freight->setupEditAttributes();
            $this->Freight->EditValue = HtmlEncode($this->Freight->CurrentValue);
            $this->Freight->PlaceHolder = RemoveHtml($this->Freight->caption());
            if (strval($this->Freight->EditValue) != "" && is_numeric($this->Freight->EditValue)) {
                $this->Freight->EditValue = FormatNumber($this->Freight->EditValue, null);
            }

            // ShipAddress
            $this->ShipAddress->setupEditAttributes();
            if (!$this->ShipAddress->Raw) {
                $this->ShipAddress->CurrentValue = HtmlDecode($this->ShipAddress->CurrentValue);
            }
            $this->ShipAddress->EditValue = HtmlEncode($this->ShipAddress->CurrentValue);
            $this->ShipAddress->PlaceHolder = RemoveHtml($this->ShipAddress->caption());

            // ShipCity
            $this->ShipCity->setupEditAttributes();
            if (!$this->ShipCity->Raw) {
                $this->ShipCity->CurrentValue = HtmlDecode($this->ShipCity->CurrentValue);
            }
            $this->ShipCity->EditValue = HtmlEncode($this->ShipCity->CurrentValue);
            $this->ShipCity->PlaceHolder = RemoveHtml($this->ShipCity->caption());

            // ShipCountry
            $this->ShipCountry->setupEditAttributes();
            if (!$this->ShipCountry->Raw) {
                $this->ShipCountry->CurrentValue = HtmlDecode($this->ShipCountry->CurrentValue);
            }
            $this->ShipCountry->EditValue = HtmlEncode($this->ShipCountry->CurrentValue);
            $this->ShipCountry->PlaceHolder = RemoveHtml($this->ShipCountry->caption());

            // Edit refer script

            // OrderID
            $this->OrderID->HrefValue = "";

            // CustomerID
            $this->CustomerID->HrefValue = "";

            // EmployeeID
            $this->EmployeeID->HrefValue = "";

            // OrderDate
            $this->OrderDate->HrefValue = "";

            // RequiredDate
            $this->RequiredDate->HrefValue = "";

            // ShippedDate
            $this->ShippedDate->HrefValue = "";

            // Freight
            $this->Freight->HrefValue = "";

            // ShipAddress
            $this->ShipAddress->HrefValue = "";

            // ShipCity
            $this->ShipCity->HrefValue = "";

            // ShipCountry
            $this->ShipCountry->HrefValue = "";
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
        if ($this->OrderID->Visible && $this->OrderID->Required) {
            if (!$this->OrderID->IsDetailKey && EmptyValue($this->OrderID->FormValue)) {
                $this->OrderID->addErrorMessage(str_replace("%s", $this->OrderID->caption(), $this->OrderID->RequiredErrorMessage));
            }
        }
        if ($this->CustomerID->Visible && $this->CustomerID->Required) {
            if (!$this->CustomerID->IsDetailKey && EmptyValue($this->CustomerID->FormValue)) {
                $this->CustomerID->addErrorMessage(str_replace("%s", $this->CustomerID->caption(), $this->CustomerID->RequiredErrorMessage));
            }
        }
        if ($this->EmployeeID->Visible && $this->EmployeeID->Required) {
            if (!$this->EmployeeID->IsDetailKey && EmptyValue($this->EmployeeID->FormValue)) {
                $this->EmployeeID->addErrorMessage(str_replace("%s", $this->EmployeeID->caption(), $this->EmployeeID->RequiredErrorMessage));
            }
        }
        if ($this->OrderDate->Visible && $this->OrderDate->Required) {
            if (!$this->OrderDate->IsDetailKey && EmptyValue($this->OrderDate->FormValue)) {
                $this->OrderDate->addErrorMessage(str_replace("%s", $this->OrderDate->caption(), $this->OrderDate->RequiredErrorMessage));
            }
        }
        if (!CheckDate($this->OrderDate->FormValue, $this->OrderDate->formatPattern())) {
            $this->OrderDate->addErrorMessage($this->OrderDate->getErrorMessage(false));
        }
        if ($this->RequiredDate->Visible && $this->RequiredDate->Required) {
            if (!$this->RequiredDate->IsDetailKey && EmptyValue($this->RequiredDate->FormValue)) {
                $this->RequiredDate->addErrorMessage(str_replace("%s", $this->RequiredDate->caption(), $this->RequiredDate->RequiredErrorMessage));
            }
        }
        if (!CheckDate($this->RequiredDate->FormValue, $this->RequiredDate->formatPattern())) {
            $this->RequiredDate->addErrorMessage($this->RequiredDate->getErrorMessage(false));
        }
        if ($this->ShippedDate->Visible && $this->ShippedDate->Required) {
            if (!$this->ShippedDate->IsDetailKey && EmptyValue($this->ShippedDate->FormValue)) {
                $this->ShippedDate->addErrorMessage(str_replace("%s", $this->ShippedDate->caption(), $this->ShippedDate->RequiredErrorMessage));
            }
        }
        if (!CheckDate($this->ShippedDate->FormValue, $this->ShippedDate->formatPattern())) {
            $this->ShippedDate->addErrorMessage($this->ShippedDate->getErrorMessage(false));
        }
        if ($this->Freight->Visible && $this->Freight->Required) {
            if (!$this->Freight->IsDetailKey && EmptyValue($this->Freight->FormValue)) {
                $this->Freight->addErrorMessage(str_replace("%s", $this->Freight->caption(), $this->Freight->RequiredErrorMessage));
            }
        }
        if (!CheckNumber($this->Freight->FormValue)) {
            $this->Freight->addErrorMessage($this->Freight->getErrorMessage(false));
        }
        if ($this->ShipAddress->Visible && $this->ShipAddress->Required) {
            if (!$this->ShipAddress->IsDetailKey && EmptyValue($this->ShipAddress->FormValue)) {
                $this->ShipAddress->addErrorMessage(str_replace("%s", $this->ShipAddress->caption(), $this->ShipAddress->RequiredErrorMessage));
            }
        }
        if ($this->ShipCity->Visible && $this->ShipCity->Required) {
            if (!$this->ShipCity->IsDetailKey && EmptyValue($this->ShipCity->FormValue)) {
                $this->ShipCity->addErrorMessage(str_replace("%s", $this->ShipCity->caption(), $this->ShipCity->RequiredErrorMessage));
            }
        }
        if ($this->ShipCountry->Visible && $this->ShipCountry->Required) {
            if (!$this->ShipCountry->IsDetailKey && EmptyValue($this->ShipCountry->FormValue)) {
                $this->ShipCountry->addErrorMessage(str_replace("%s", $this->ShipCountry->caption(), $this->ShipCountry->RequiredErrorMessage));
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
            $thisKey .= $row['OrderID'];

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

        // CustomerID
        $this->CustomerID->setDbValueDef($rsnew, $this->CustomerID->CurrentValue, $this->CustomerID->ReadOnly);

        // EmployeeID
        $this->EmployeeID->setDbValueDef($rsnew, $this->EmployeeID->CurrentValue, $this->EmployeeID->ReadOnly);

        // OrderDate
        $this->OrderDate->setDbValueDef($rsnew, UnFormatDateTime($this->OrderDate->CurrentValue, $this->OrderDate->formatPattern()), $this->OrderDate->ReadOnly);

        // RequiredDate
        $this->RequiredDate->setDbValueDef($rsnew, UnFormatDateTime($this->RequiredDate->CurrentValue, $this->RequiredDate->formatPattern()), $this->RequiredDate->ReadOnly);

        // ShippedDate
        $this->ShippedDate->setDbValueDef($rsnew, UnFormatDateTime($this->ShippedDate->CurrentValue, $this->ShippedDate->formatPattern()), $this->ShippedDate->ReadOnly);

        // Freight
        $this->Freight->setDbValueDef($rsnew, $this->Freight->CurrentValue, $this->Freight->ReadOnly);

        // ShipAddress
        $this->ShipAddress->setDbValueDef($rsnew, $this->ShipAddress->CurrentValue, $this->ShipAddress->ReadOnly);

        // ShipCity
        $this->ShipCity->setDbValueDef($rsnew, $this->ShipCity->CurrentValue, $this->ShipCity->ReadOnly);

        // ShipCountry
        $this->ShipCountry->setDbValueDef($rsnew, $this->ShipCountry->CurrentValue, $this->ShipCountry->ReadOnly);

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

    // Load row hash
    protected function loadRowHash()
    {
        $filter = $this->getRecordFilter();

        // Load SQL based on filter
        $this->CurrentFilter = $filter;
        $sql = $this->getCurrentSql();
        $conn = $this->getConnection();
        $row = $conn->fetchAssociative($sql);
        $this->HashValue = $row ? $this->getRowHash($row) : ""; // Get hash value for record
    }

    // Get Row Hash
    public function getRowHash(&$rs)
    {
        if (!$rs) {
            return "";
        }
        $row = ($rs instanceof Recordset) ? $rs->fields : $rs;
        $hash = "";
        $hash .= GetFieldHash($row['CustomerID']); // CustomerID
        $hash .= GetFieldHash($row['EmployeeID']); // EmployeeID
        $hash .= GetFieldHash($row['OrderDate']); // OrderDate
        $hash .= GetFieldHash($row['RequiredDate']); // RequiredDate
        $hash .= GetFieldHash($row['ShippedDate']); // ShippedDate
        $hash .= GetFieldHash($row['Freight']); // Freight
        $hash .= GetFieldHash($row['ShipAddress']); // ShipAddress
        $hash .= GetFieldHash($row['ShipCity']); // ShipCity
        $hash .= GetFieldHash($row['ShipCountry']); // ShipCountry
        return md5($hash);
    }

    // Add record
    protected function addRow($rsold = null)
    {
        global $Language, $Security;

        // Set new row
        $rsnew = [];

        // CustomerID
        $this->CustomerID->setDbValueDef($rsnew, $this->CustomerID->CurrentValue, false);

        // EmployeeID
        $this->EmployeeID->setDbValueDef($rsnew, $this->EmployeeID->CurrentValue, false);

        // OrderDate
        $this->OrderDate->setDbValueDef($rsnew, UnFormatDateTime($this->OrderDate->CurrentValue, $this->OrderDate->formatPattern()), false);

        // RequiredDate
        $this->RequiredDate->setDbValueDef($rsnew, UnFormatDateTime($this->RequiredDate->CurrentValue, $this->RequiredDate->formatPattern()), false);

        // ShippedDate
        $this->ShippedDate->setDbValueDef($rsnew, UnFormatDateTime($this->ShippedDate->CurrentValue, $this->ShippedDate->formatPattern()), false);

        // Freight
        $this->Freight->setDbValueDef($rsnew, $this->Freight->CurrentValue, strval($this->Freight->CurrentValue) == "");

        // ShipAddress
        $this->ShipAddress->setDbValueDef($rsnew, $this->ShipAddress->CurrentValue, false);

        // ShipCity
        $this->ShipCity->setDbValueDef($rsnew, $this->ShipCity->CurrentValue, false);

        // ShipCountry
        $this->ShipCountry->setDbValueDef($rsnew, $this->ShipCountry->CurrentValue, false);

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

    // Get export HTML tag
    protected function getExportTag($type, $custom = false)
    {
        global $Language;
        if ($type == "print" || $custom) { // Printer friendly / custom export
            $pageUrl = $this->pageUrl(false);
            $exportUrl = GetUrl($pageUrl . "export=" . $type . ($custom ? "&amp;custom=1" : ""));
        } else { // Export API URL
            $exportUrl = GetApiUrl(Config("API_EXPORT_ACTION") . "/" . $type . "/" . $this->TableVar);
        }
        if (SameText($type, "excel")) {
            if ($custom) {
                return "<button type=\"button\" class=\"btn btn-default ew-export-link ew-excel\" title=\"" . HtmlEncode($Language->phrase("ExportToExcel", true)) . "\" data-caption=\"" . HtmlEncode($Language->phrase("ExportToExcel", true)) . "\" form=\"forders2list\" data-url=\"$exportUrl\" data-ew-action=\"export\" data-export=\"excel\" data-custom=\"true\" data-export-id=\"" . session_id() . "\" data-export-selected=\"false\">" . $Language->phrase("ExportToExcel") . "</button>";
            } else {
                return "<a href=\"$exportUrl\" class=\"btn btn-default ew-export-link ew-excel\" title=\"" . HtmlEncode($Language->phrase("ExportToExcel", true)) . "\" data-caption=\"" . HtmlEncode($Language->phrase("ExportToExcel", true)) . "\">" . $Language->phrase("ExportToExcel") . "</a>";
            }
        } elseif (SameText($type, "word")) {
            if ($custom) {
                return "<button type=\"button\" class=\"btn btn-default ew-export-link ew-word\" title=\"" . HtmlEncode($Language->phrase("ExportToWord", true)) . "\" data-caption=\"" . HtmlEncode($Language->phrase("ExportToWord", true)) . "\" form=\"forders2list\" data-url=\"$exportUrl\" data-ew-action=\"export\" data-export=\"word\" data-custom=\"true\" data-export-id=\"" . session_id() . "\" data-export-selected=\"false\">" . $Language->phrase("ExportToWord") . "</button>";
            } else {
                return "<a href=\"$exportUrl\" class=\"btn btn-default ew-export-link ew-word\" title=\"" . HtmlEncode($Language->phrase("ExportToWord", true)) . "\" data-caption=\"" . HtmlEncode($Language->phrase("ExportToWord", true)) . "\">" . $Language->phrase("ExportToWord") . "</a>";
            }
        } elseif (SameText($type, "pdf")) {
            if ($custom) {
                return "<button type=\"button\" class=\"btn btn-default ew-export-link ew-pdf\" title=\"" . HtmlEncode($Language->phrase("ExportToPdf", true)) . "\" data-caption=\"" . HtmlEncode($Language->phrase("ExportToPdf", true)) . "\" form=\"forders2list\" data-url=\"$exportUrl\" data-ew-action=\"export\" data-export=\"pdf\" data-custom=\"true\" data-export-id=\"" . session_id() . "\" data-export-selected=\"false\">" . $Language->phrase("ExportToPdf") . "</button>";
            } else {
                return "<a href=\"$exportUrl\" class=\"btn btn-default ew-export-link ew-pdf\" title=\"" . HtmlEncode($Language->phrase("ExportToPdf", true)) . "\" data-caption=\"" . HtmlEncode($Language->phrase("ExportToPdf", true)) . "\">" . $Language->phrase("ExportToPdf") . "</a>";
            }
        } elseif (SameText($type, "html")) {
            return "<a href=\"$exportUrl\" class=\"btn btn-default ew-export-link ew-html\" title=\"" . HtmlEncode($Language->phrase("ExportToHtml", true)) . "\" data-caption=\"" . HtmlEncode($Language->phrase("ExportToHtml", true)) . "\">" . $Language->phrase("ExportToHtml") . "</a>";
        } elseif (SameText($type, "xml")) {
            return "<a href=\"$exportUrl\" class=\"btn btn-default ew-export-link ew-xml\" title=\"" . HtmlEncode($Language->phrase("ExportToXml", true)) . "\" data-caption=\"" . HtmlEncode($Language->phrase("ExportToXml", true)) . "\">" . $Language->phrase("ExportToXml") . "</a>";
        } elseif (SameText($type, "csv")) {
            return "<a href=\"$exportUrl\" class=\"btn btn-default ew-export-link ew-csv\" title=\"" . HtmlEncode($Language->phrase("ExportToCsv", true)) . "\" data-caption=\"" . HtmlEncode($Language->phrase("ExportToCsv", true)) . "\">" . $Language->phrase("ExportToCsv") . "</a>";
        } elseif (SameText($type, "email")) {
            $url = $custom ? ' data-url="' . $exportUrl . '"' : '';
            return '<button type="button" class="btn btn-default ew-export-link ew-email" title="' . $Language->phrase("ExportToEmail", true) . '" data-caption="' . $Language->phrase("ExportToEmail", true) . '" form="forders2list" data-ew-action="email" data-custom="false" data-hdr="' . $Language->phrase("ExportToEmail", true) . '" data-exported-selected="false"' . $url . '>' . $Language->phrase("ExportToEmail") . '</button>';
        } elseif (SameText($type, "print")) {
            return "<a href=\"$exportUrl\" class=\"btn btn-default ew-export-link ew-print\" title=\"" . HtmlEncode($Language->phrase("PrinterFriendly", true)) . "\" data-caption=\"" . HtmlEncode($Language->phrase("PrinterFriendly", true)) . "\">" . $Language->phrase("PrinterFriendly") . "</a>";
        }
    }

    // Set up export options
    protected function setupExportOptions()
    {
        global $Language, $Security;

        // Printer friendly
        $item = &$this->ExportOptions->add("print");
        $item->Body = $this->getExportTag("print");
        $item->Visible = true;

        // Export to Excel
        $item = &$this->ExportOptions->add("excel");
        $item->Body = $this->getExportTag("excel");
        $item->Visible = true;

        // Export to Word
        $item = &$this->ExportOptions->add("word");
        $item->Body = $this->getExportTag("word");
        $item->Visible = true;

        // Export to HTML
        $item = &$this->ExportOptions->add("html");
        $item->Body = $this->getExportTag("html");
        $item->Visible = true;

        // Export to XML
        $item = &$this->ExportOptions->add("xml");
        $item->Body = $this->getExportTag("xml");
        $item->Visible = true;

        // Export to CSV
        $item = &$this->ExportOptions->add("csv");
        $item->Body = $this->getExportTag("csv");
        $item->Visible = true;

        // Export to PDF
        $item = &$this->ExportOptions->add("pdf");
        $item->Body = $this->getExportTag("pdf");
        $item->Visible = false;

        // Export to Email
        $item = &$this->ExportOptions->add("email");
        $item->Body = $this->getExportTag("email");
        $item->Visible = true;

        // Drop down button for export
        $this->ExportOptions->UseButtonGroup = true;
        $this->ExportOptions->UseDropDownButton = true;
        if ($this->ExportOptions->UseButtonGroup && IsMobile()) {
            $this->ExportOptions->UseDropDownButton = true;
        }
        $this->ExportOptions->DropDownButtonPhrase = $Language->phrase("ButtonExport");

        // Add group option item
        $item = &$this->ExportOptions->addGroupOption();
        $item->Body = "";
        $item->Visible = false;
        if (!$Security->canExport()) { // Export not allowed
            $this->ExportOptions->hideAllOptions();
        }
    }

    // Set up search options
    protected function setupSearchOptions()
    {
        global $Language, $Security;
        $pageUrl = $this->pageUrl(false);
        $this->SearchOptions = new ListOptions(["TagClassName" => "ew-search-option"]);

        // Search button
        $item = &$this->SearchOptions->add("searchtoggle");
        $searchToggleClass = ($this->SearchWhere != "") ? " active" : " active";
        $item->Body = "<a class=\"btn btn-default ew-search-toggle" . $searchToggleClass . "\" role=\"button\" title=\"" . $Language->phrase("SearchPanel") . "\" data-caption=\"" . $Language->phrase("SearchPanel") . "\" data-ew-action=\"search-toggle\" data-form=\"forders2srch\" aria-pressed=\"" . ($searchToggleClass == " active" ? "true" : "false") . "\">" . $Language->phrase("SearchLink") . "</a>";
        $item->Visible = true;

        // Show all button
        $item = &$this->SearchOptions->add("showall");
        if ($this->UseCustomTemplate || !$this->UseAjaxActions) {
            $item->Body = "<a class=\"btn btn-default ew-show-all\" role=\"button\" title=\"" . $Language->phrase("ShowAll") . "\" data-caption=\"" . $Language->phrase("ShowAll") . "\" href=\"" . $pageUrl . "cmd=reset\">" . $Language->phrase("ShowAllBtn") . "</a>";
        } else {
            $item->Body = "<a class=\"btn btn-default ew-show-all\" role=\"button\" title=\"" . $Language->phrase("ShowAll") . "\" data-caption=\"" . $Language->phrase("ShowAll") . "\" data-ew-action=\"refresh\" data-url=\"" . $pageUrl . "cmd=reset\">" . $Language->phrase("ShowAllBtn") . "</a>";
        }
        $item->Visible = ($this->SearchWhere != $this->DefaultSearchWhere && $this->SearchWhere != "0=101");

        // Button group for search
        $this->SearchOptions->UseDropDownButton = false;
        $this->SearchOptions->UseButtonGroup = true;
        $this->SearchOptions->DropDownButtonPhrase = $Language->phrase("ButtonSearch");

        // Add group option item
        $item = &$this->SearchOptions->addGroupOption();
        $item->Body = "";
        $item->Visible = false;

        // Hide search options
        if ($this->isExport() || $this->CurrentAction && $this->CurrentAction != "search") {
            $this->SearchOptions->hideAllOptions();
        }
        if (!$Security->canSearch()) {
            $this->SearchOptions->hideAllOptions();
            $this->FilterOptions->hideAllOptions();
        }
    }

    // Check if any search fields
    public function hasSearchFields()
    {
        return true;
    }

    // Render search options
    protected function renderSearchOptions()
    {
        if (!$this->hasSearchFields() && $this->SearchOptions["searchtoggle"]) {
            $this->SearchOptions["searchtoggle"]->Visible = false;
        }
    }

    /**
    * Export data in HTML/CSV/Word/Excel/XML/Email/PDF format
    *
    * @param bool $return Return the data rather than output it
    * @return mixed
    */
    public function exportData($doc)
    {
        global $Language;
        $utf8 = SameText(Config("PROJECT_CHARSET"), "utf-8");

        // Load recordset
        $rs = null;
        $this->TotalRecords = $this->listRecordCount();

        // Export all
        if ($this->ExportAll) {
            if (Config("EXPORT_ALL_TIME_LIMIT") >= 0) {
                @set_time_limit(Config("EXPORT_ALL_TIME_LIMIT"));
            }
            $this->DisplayRecords = $this->TotalRecords;
            $this->StopRecord = $this->TotalRecords;
        } else { // Export one page only
            $this->setupStartRecord(); // Set up start record position
            // Set the last record to display
            if ($this->DisplayRecords <= 0) {
                $this->StopRecord = $this->TotalRecords;
            } else {
                $this->StopRecord = $this->StartRecord + $this->DisplayRecords - 1;
            }
        }
        $rs = $this->loadRecordset($this->StartRecord - 1, $this->DisplayRecords <= 0 ? $this->TotalRecords : $this->DisplayRecords);
        if (!$rs || !$doc) {
            RemoveHeader("Content-Type"); // Remove header
            RemoveHeader("Content-Disposition");
            $this->showMessage();
            return;
        }
        $this->StartRecord = 1;
        $this->StopRecord = $this->DisplayRecords <= 0 ? $this->TotalRecords : $this->DisplayRecords;

        // Call Page Exporting server event
        $doc->ExportCustom = !$this->pageExporting($doc);

        // Page header
        $header = $this->PageHeader;
        $this->pageDataRendering($header);
        $doc->Text .= $header;
        $this->exportDocument($doc, $rs, $this->StartRecord, $this->StopRecord, "");

        // Close recordset
        $rs->close();

        // Page footer
        $footer = $this->PageFooter;
        $this->pageDataRendered($footer);
        $doc->Text .= $footer;

        // Export header and footer
        $doc->exportHeaderAndFooter();

        // Call Page Exported server event
        $this->pageExported($doc);
    }

    // Set up Breadcrumb
    protected function setupBreadcrumb()
    {
        global $Breadcrumb, $Language;
        $Breadcrumb = new Breadcrumb("home");
        $url = CurrentUrl();
        $url = preg_replace('/\?cmd=reset(all){0,1}$/i', '', $url); // Remove cmd=reset(all)
        $Breadcrumb->add("list", $this->TableVar, $url, "", $this->TableVar, true);
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

    // Set up starting record parameters
    public function setupStartRecord()
    {
        if ($this->DisplayRecords == 0) {
            return;
        }
        $pageNo = Get(Config("TABLE_PAGE_NUMBER"));
        $startRec = Get(Config("TABLE_START_REC"));
        $infiniteScroll = ConvertToBool(Param("infinitescroll"));
        if ($pageNo !== null) { // Check for "pageno" parameter first
            $pageNo = ParseInteger($pageNo);
            if (is_numeric($pageNo)) {
                $this->StartRecord = ($pageNo - 1) * $this->DisplayRecords + 1;
                if ($this->StartRecord <= 0) {
                    $this->StartRecord = 1;
                } elseif ($this->StartRecord >= (int)(($this->TotalRecords - 1) / $this->DisplayRecords) * $this->DisplayRecords + 1) {
                    $this->StartRecord = (int)(($this->TotalRecords - 1) / $this->DisplayRecords) * $this->DisplayRecords + 1;
                }
            }
        } elseif ($startRec !== null && is_numeric($startRec)) { // Check for "start" parameter
            $this->StartRecord = $startRec;
        } elseif (!$infiniteScroll) {
            $this->StartRecord = $this->getStartRecordNumber();
        }

        // Check if correct start record counter
        if (!is_numeric($this->StartRecord) || intval($this->StartRecord) <= 0) { // Avoid invalid start record counter
            $this->StartRecord = 1; // Reset start record counter
        } elseif ($this->StartRecord > $this->TotalRecords) { // Avoid starting record > total records
            $this->StartRecord = (int)(($this->TotalRecords - 1) / $this->DisplayRecords) * $this->DisplayRecords + 1; // Point to last page first record
        } elseif (($this->StartRecord - 1) % $this->DisplayRecords != 0) {
            $this->StartRecord = (int)(($this->StartRecord - 1) / $this->DisplayRecords) * $this->DisplayRecords + 1; // Point to page boundary
        }
        if (!$infiniteScroll) {
            $this->setStartRecordNumber($this->StartRecord);
        }
    }

    // Get page count
    public function pageCount() {
        return ceil($this->TotalRecords / $this->DisplayRecords);
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

    // Row Custom Action event
    public function rowCustomAction($action, $row)
    {
        // Return false to abort
        return true;
    }

    // Page Exporting event
    // $doc = export object
    public function pageExporting(&$doc)
    {
        //$doc->Text = "my header"; // Export header
        //return false; // Return false to skip default export and use Row_Export event
        return true; // Return true to use default export and skip Row_Export event
    }

    // Row Export event
    // $doc = export document object
    public function rowExport($doc, $rs)
    {
        //$doc->Text .= "my content"; // Build HTML with field value: $rs["MyField"] or $this->MyField->ViewValue
    }

    // Page Exported event
    // $doc = export document object
    public function pageExported($doc)
    {
        //$doc->Text .= "my footer"; // Export footer
        //Log($doc->Text);
    }

    // Page Importing event
    public function pageImporting(&$builder, &$options)
    {
        //var_dump($options); // Show all options for importing
        //$builder = fn($workflow) => $workflow->addStep($myStep);
        //return false; // Return false to skip import
        return true;
    }

    // Row Import event
    public function rowImport(&$row, $cnt)
    {
        //Log($cnt); // Import record count
        //var_dump($row); // Import row
        //return false; // Return false to skip import
        return true;
    }

    // Page Imported event
    public function pageImported($obj, $results)
    {
        //var_dump($obj); // Workflow result object
        //var_dump($results); // Import results
    }
}
