<?php

namespace PHPMaker2023\demo2023;

use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Page class
 */
class CarsList extends Cars
{
    use MessagesTrait;

    // Page ID
    public $PageID = "list";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Page object name
    public $PageObjName = "CarsList";

    // View file path
    public $View = null;

    // Title
    public $Title = null; // Title for <title> tag

    // Rendering View
    public $RenderingView = false;

    // Grid form hidden field names
    public $FormName = "fcarslist";
    public $FormActionName = "";
    public $FormBlankRowName = "";
    public $FormKeyCountName = "";

    // CSS class/style
    public $CurrentPageName = "carslist";

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
        $this->ID->setVisibility();
        $this->Trademark->setVisibility();
        $this->Model->setVisibility();
        $this->HP->setVisibility();
        $this->Cylinders->setVisibility();
        $this->TransmissionSpeeds->Visible = false;
        $this->TransmissAutomatic->Visible = false;
        $this->MPGCity->Visible = false;
        $this->MPGHighway->Visible = false;
        $this->Description->Visible = false;
        $this->Price->setVisibility();
        $this->Picture->setVisibility();
        $this->Doors->setVisibility();
        $this->Torque->setVisibility();
    }

    // Constructor
    public function __construct()
    {
        parent::__construct();
        global $Language, $DashboardReport, $DebugTimer, $UserTable;
        $this->FormActionName = Config("FORM_ROW_ACTION_NAME");
        $this->FormBlankRowName = Config("FORM_BLANK_ROW_NAME");
        $this->FormKeyCountName = Config("FORM_KEY_COUNT_NAME");
        $this->TableVar = 'cars';
        $this->TableName = 'cars';

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

        // Table object (cars)
        if (!isset($GLOBALS["cars"]) || get_class($GLOBALS["cars"]) == PROJECT_NAMESPACE . "cars") {
            $GLOBALS["cars"] = &$this;
        }

        // Page URL
        $pageUrl = $this->pageUrl(false);

        // Initialize URLs
        $this->AddUrl = "carsadd";
        $this->InlineAddUrl = $pageUrl . "action=add";
        $this->GridAddUrl = $pageUrl . "action=gridadd";
        $this->GridEditUrl = $pageUrl . "action=gridedit";
        $this->MultiEditUrl = $pageUrl . "action=multiedit";
        $this->MultiDeleteUrl = "carsdelete";
        $this->MultiUpdateUrl = "carsupdate";

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'cars');
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
                    $result["view"] = $pageName == "carsview"; // If View page, no primary button
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
            $key .= @$ar['ID'];
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
            $this->ID->Visible = false;
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
    public $SearchFieldsPerRow = 2; // For extended search
    public $RecordCount = 0; // Record count
    public $InlineRowCount = 0;
    public $StartRowCount = 1;
    public $RowCount = 0;
    public $Attrs = []; // Row attributes and cell attributes
    public $RowIndex = 0; // Row index
    public $KeyCount = 0; // Key count
    public $MultiColumnGridClass = "row-cols-md-4";
    public $MultiColumnEditClass = "col-12 w-100";
    public $MultiColumnCardClass = "card h-100 ew-card";
    public $MultiColumnListOptionsPosition = "bottom-start";
    public $LayoutOptions;
    public $MultiColumnLayout = "cards";
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
        $this->setupLookupOptions($this->Trademark);
        $this->setupLookupOptions($this->Model);
        $this->setupLookupOptions($this->TransmissAutomatic);

        // Load default values for add
        $this->loadDefaultValues();

        // Update form name to avoid conflict
        if ($this->IsModal) {
            $this->FormName = "fcarsgrid";
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
                    $this->terminate("carslist");
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
                    $this->terminate("carslist");
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
        AddFilter($this->DefaultSearchWhere, $this->advancedSearchWhere(true));

        // Get basic search values
        $this->loadBasicSearchValues();

        // Get and validate search values for advanced search
        if (EmptyValue($this->UserAction)) { // Skip if user action
            $this->loadSearchValues();
        }

        // Process filter list
        if ($this->processFilterList()) {
            $this->terminate();
            return;
        }
        if ($CurrentForm) {
            $CurrentForm->resetIndex();
        }
        if (!$this->validateSearch()) {
            // Nothing to do
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

        // Get advanced search criteria
        if (!$this->hasInvalidFields()) {
            $srchAdvanced = $this->advancedSearchWhere();
        }

        // Get query builder criteria
        $query = $this->queryBuilderWhere();

        // Restore display records
        if ($this->Command != "json" && $this->getRecordsPerPage() != "") {
            $this->DisplayRecords = $this->getRecordsPerPage(); // Restore from Session
        } else {
            $this->DisplayRecords = 10; // Load default
            $this->setRecordsPerPage($this->DisplayRecords); // Save default to Session
        }

        // Load search default if no existing search criteria
        if (!$this->checkSearchParms() && !$query) {
            // Load basic search from default
            $this->BasicSearch->loadDefault();
            if ($this->BasicSearch->Keyword != "") {
                $srchBasic = $this->basicSearchWhere(); // Save to session
            }

            // Load advanced search from default
            if ($this->loadAdvancedSearchDefault()) {
                $srchAdvanced = $this->advancedSearchWhere(); // Save to session
            }
        }

        // Restore search settings from Session
        if (!$this->hasInvalidFields()) {
            $this->loadAdvancedSearch();
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

        // Handle inline add for custom template in table layout with no records
        if ($this->UseCustomTemplate && $this->MultiColumnLayout == "table" && $this->isAdd() && $this->TotalRecords == 0) {
            $this->MultiColumnLayout = "cards";
        }

        // No custom template for cards layout
        if ($this->MultiColumnLayout == "cards") {
            $this->UseCustomTemplate = false;
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
        $this->Price->FormValue = ""; // Clear form value
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
                                // Overwrite record, just reload hash value
                                if ($this->isGridOverwrite()) {
                                    $this->loadRowHash();
                                }
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
                    $key .= $this->ID->CurrentValue;

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
        if ($CurrentForm->hasValue("x_Trademark") && $CurrentForm->hasValue("o_Trademark") && $this->Trademark->CurrentValue != $this->Trademark->DefaultValue) {
            return false;
        }
        if ($CurrentForm->hasValue("x_Model") && $CurrentForm->hasValue("o_Model") && $this->Model->CurrentValue != $this->Model->DefaultValue) {
            return false;
        }
        if ($CurrentForm->hasValue("x_HP") && $CurrentForm->hasValue("o_HP") && $this->HP->CurrentValue != $this->HP->DefaultValue) {
            return false;
        }
        if ($CurrentForm->hasValue("x_Cylinders") && $CurrentForm->hasValue("o_Cylinders") && $this->Cylinders->CurrentValue != $this->Cylinders->DefaultValue) {
            return false;
        }
        if ($CurrentForm->hasValue("x_Price") && $CurrentForm->hasValue("o_Price") && $this->Price->CurrentValue != $this->Price->DefaultValue) {
            return false;
        }
        if (!EmptyValue($this->Picture->Upload->Value)) {
            return false;
        }
        if ($CurrentForm->hasValue("x_Doors") && $CurrentForm->hasValue("o_Doors") && $this->Doors->CurrentValue != $this->Doors->DefaultValue) {
            return false;
        }
        if ($CurrentForm->hasValue("x_Torque") && $CurrentForm->hasValue("o_Torque") && $this->Torque->CurrentValue != $this->Torque->DefaultValue) {
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
        $filterList = Concat($filterList, $this->ID->AdvancedSearch->toJson(), ","); // Field ID
        $filterList = Concat($filterList, $this->Trademark->AdvancedSearch->toJson(), ","); // Field Trademark
        $filterList = Concat($filterList, $this->Model->AdvancedSearch->toJson(), ","); // Field Model
        $filterList = Concat($filterList, $this->HP->AdvancedSearch->toJson(), ","); // Field HP
        $filterList = Concat($filterList, $this->Cylinders->AdvancedSearch->toJson(), ","); // Field Cylinders
        $filterList = Concat($filterList, $this->Description->AdvancedSearch->toJson(), ","); // Field Description
        $filterList = Concat($filterList, $this->Price->AdvancedSearch->toJson(), ","); // Field Price
        $filterList = Concat($filterList, $this->Doors->AdvancedSearch->toJson(), ","); // Field Doors
        $filterList = Concat($filterList, $this->Torque->AdvancedSearch->toJson(), ","); // Field Torque
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
            $UserProfile->setSearchFilters(CurrentUserName(), "fcarssrch", $filters);
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

        // Field ID
        $this->ID->AdvancedSearch->SearchValue = @$filter["x_ID"];
        $this->ID->AdvancedSearch->SearchOperator = @$filter["z_ID"];
        $this->ID->AdvancedSearch->SearchCondition = @$filter["v_ID"];
        $this->ID->AdvancedSearch->SearchValue2 = @$filter["y_ID"];
        $this->ID->AdvancedSearch->SearchOperator2 = @$filter["w_ID"];
        $this->ID->AdvancedSearch->save();

        // Field Trademark
        $this->Trademark->AdvancedSearch->SearchValue = @$filter["x_Trademark"];
        $this->Trademark->AdvancedSearch->SearchOperator = @$filter["z_Trademark"];
        $this->Trademark->AdvancedSearch->SearchCondition = @$filter["v_Trademark"];
        $this->Trademark->AdvancedSearch->SearchValue2 = @$filter["y_Trademark"];
        $this->Trademark->AdvancedSearch->SearchOperator2 = @$filter["w_Trademark"];
        $this->Trademark->AdvancedSearch->save();

        // Field Model
        $this->Model->AdvancedSearch->SearchValue = @$filter["x_Model"];
        $this->Model->AdvancedSearch->SearchOperator = @$filter["z_Model"];
        $this->Model->AdvancedSearch->SearchCondition = @$filter["v_Model"];
        $this->Model->AdvancedSearch->SearchValue2 = @$filter["y_Model"];
        $this->Model->AdvancedSearch->SearchOperator2 = @$filter["w_Model"];
        $this->Model->AdvancedSearch->save();

        // Field HP
        $this->HP->AdvancedSearch->SearchValue = @$filter["x_HP"];
        $this->HP->AdvancedSearch->SearchOperator = @$filter["z_HP"];
        $this->HP->AdvancedSearch->SearchCondition = @$filter["v_HP"];
        $this->HP->AdvancedSearch->SearchValue2 = @$filter["y_HP"];
        $this->HP->AdvancedSearch->SearchOperator2 = @$filter["w_HP"];
        $this->HP->AdvancedSearch->save();

        // Field Cylinders
        $this->Cylinders->AdvancedSearch->SearchValue = @$filter["x_Cylinders"];
        $this->Cylinders->AdvancedSearch->SearchOperator = @$filter["z_Cylinders"];
        $this->Cylinders->AdvancedSearch->SearchCondition = @$filter["v_Cylinders"];
        $this->Cylinders->AdvancedSearch->SearchValue2 = @$filter["y_Cylinders"];
        $this->Cylinders->AdvancedSearch->SearchOperator2 = @$filter["w_Cylinders"];
        $this->Cylinders->AdvancedSearch->save();

        // Field Description
        $this->Description->AdvancedSearch->SearchValue = @$filter["x_Description"];
        $this->Description->AdvancedSearch->SearchOperator = @$filter["z_Description"];
        $this->Description->AdvancedSearch->SearchCondition = @$filter["v_Description"];
        $this->Description->AdvancedSearch->SearchValue2 = @$filter["y_Description"];
        $this->Description->AdvancedSearch->SearchOperator2 = @$filter["w_Description"];
        $this->Description->AdvancedSearch->save();

        // Field Price
        $this->Price->AdvancedSearch->SearchValue = @$filter["x_Price"];
        $this->Price->AdvancedSearch->SearchOperator = @$filter["z_Price"];
        $this->Price->AdvancedSearch->SearchCondition = @$filter["v_Price"];
        $this->Price->AdvancedSearch->SearchValue2 = @$filter["y_Price"];
        $this->Price->AdvancedSearch->SearchOperator2 = @$filter["w_Price"];
        $this->Price->AdvancedSearch->save();

        // Field Doors
        $this->Doors->AdvancedSearch->SearchValue = @$filter["x_Doors"];
        $this->Doors->AdvancedSearch->SearchOperator = @$filter["z_Doors"];
        $this->Doors->AdvancedSearch->SearchCondition = @$filter["v_Doors"];
        $this->Doors->AdvancedSearch->SearchValue2 = @$filter["y_Doors"];
        $this->Doors->AdvancedSearch->SearchOperator2 = @$filter["w_Doors"];
        $this->Doors->AdvancedSearch->save();

        // Field Torque
        $this->Torque->AdvancedSearch->SearchValue = @$filter["x_Torque"];
        $this->Torque->AdvancedSearch->SearchOperator = @$filter["z_Torque"];
        $this->Torque->AdvancedSearch->SearchCondition = @$filter["v_Torque"];
        $this->Torque->AdvancedSearch->SearchValue2 = @$filter["y_Torque"];
        $this->Torque->AdvancedSearch->SearchOperator2 = @$filter["w_Torque"];
        $this->Torque->AdvancedSearch->save();
        $this->BasicSearch->setKeyword(@$filter[Config("TABLE_BASIC_SEARCH")]);
        $this->BasicSearch->setType(@$filter[Config("TABLE_BASIC_SEARCH_TYPE")]);
    }

    // Advanced search WHERE clause based on QueryString
    public function advancedSearchWhere($default = false)
    {
        global $Security;
        $where = "";
        if (!$Security->canSearch()) {
            return "";
        }
        $this->buildSearchSql($where, $this->ID, $default, false); // ID
        $this->buildSearchSql($where, $this->Trademark, $default, false); // Trademark
        $this->buildSearchSql($where, $this->Model, $default, false); // Model
        $this->buildSearchSql($where, $this->HP, $default, false); // HP
        $this->buildSearchSql($where, $this->Cylinders, $default, false); // Cylinders
        $this->buildSearchSql($where, $this->Description, $default, false); // Description
        $this->buildSearchSql($where, $this->Price, $default, false); // Price
        $this->buildSearchSql($where, $this->Doors, $default, false); // Doors
        $this->buildSearchSql($where, $this->Torque, $default, false); // Torque

        // Set up search command
        if (!$default && $where != "" && in_array($this->Command, ["", "reset", "resetall"])) {
            $this->Command = "search";
        }
        if (!$default && $this->Command == "search") {
            $this->ID->AdvancedSearch->save(); // ID
            $this->Trademark->AdvancedSearch->save(); // Trademark
            $this->Model->AdvancedSearch->save(); // Model
            $this->HP->AdvancedSearch->save(); // HP
            $this->Cylinders->AdvancedSearch->save(); // Cylinders
            $this->Description->AdvancedSearch->save(); // Description
            $this->Price->AdvancedSearch->save(); // Price
            $this->Doors->AdvancedSearch->save(); // Doors
            $this->Torque->AdvancedSearch->save(); // Torque

            // Clear rules for QueryBuilder
            $this->setSessionRules("");
        }
        return $where;
    }

    // Parse query builder rule function
    protected function parseRules($group, $fieldName = "") {
        $group["condition"] ??= "AND";
        if (!in_array($group["condition"], ["AND", "OR"])) {
            throw new \Exception("Unable to build SQL query with condition '" . $group["condition"] . "'");
        }
        if (!is_array($group["rules"] ?? null)) {
            return "";
        }
        $parts = [];
        foreach ($group["rules"] as $rule) {
            if (is_array($rule["rules"] ?? null) && count($rule["rules"]) > 0) {
                $parts[] = "(" . " " . $this->parseRules($rule, $fieldName) . " " . ")" . " ";
            } else {
                $field = $rule["field"];
                $fld = $this->fieldByParam($field);
                if (!$fld) {
                    throw new \Exception("Failed to find field '" . $field . "'");
                }
                if ($fieldName == "" || $fld->Name == $fieldName) { // Field name not specified or matched field name
                    $fldOpr = array_search($rule["operator"], Config("CLIENT_SEARCH_OPERATORS"));
                    $ope = Config("QUERY_BUILDER_OPERATORS")[$rule["operator"]] ?? null;
                    if (!$ope || !$fldOpr) {
                        throw new \Exception("Unknown SQL operation for operator '" . $rule["operator"] . "'");
                    }
                    if ($ope["nb_inputs"] > 0 && ($rule["value"] ?? false) || IsNullOrEmptyOperator($fldOpr)) {
                        $rule["value"] = !is_array($rule["value"]) ? [$rule["value"]] : $rule["value"];
                        $fldVal = $rule["value"][0];
                        if (is_array($fldVal)) {
                            $fldVal = implode(Config("MULTIPLE_OPTION_SEPARATOR"), $fldVal);
                        }
                        $useFilter = $fld->UseFilter; // Query builder does not use filter
                        try {
                            if ($fld->isMultiSelect()) {
                                $parts[] = $fldVal != "" ? GetMultiSearchSql($fld, $fldOpr, ConvertSearchValue($fldVal, $fldOpr, $fld), $this->Dbid) : "";
                            } else {
                                $fldVal2 = ContainsString($fldOpr, "BETWEEN") ? $rule["value"][1] : ""; // BETWEEN
                                if (is_array($fldVal2)) {
                                    $fldVal2 = implode(Config("MULTIPLE_OPTION_SEPARATOR"), $fldVal2);
                                }
                                $parts[] = GetSearchSql(
                                    $fld,
                                    ConvertSearchValue($fldVal, $fldOpr, $fld), // $fldVal
                                    $fldOpr,
                                    "", // $fldCond not used
                                    ConvertSearchValue($fldVal2, $fldOpr, $fld), // $fldVal2
                                    "", // $fldOpr2 not used
                                    $this->Dbid
                                );
                            }
                        } finally {
                            $fld->UseFilter = $useFilter;
                        }
                    }
                }
            }
        }
        $where = implode(" " . $group["condition"] . " ", array_filter($parts));
        if ($group["not"] ?? false) {
            $where = "NOT (" . $where . ")";
        }
        return $where;
    }

    // Quey builder WHERE clause
    public function queryBuilderWhere($fieldName = "")
    {
        global $Security;
        if (!$Security->canSearch()) {
            return "";
        }

        // Get rules by query builder
        $rules = Post("rules") ?? $this->getSessionRules();

        // Decode and parse rules
        $where = $rules ? $this->parseRules(json_decode($rules, true), $fieldName) : "";

        // Clear other search and save rules to session
        if ($where && $fieldName == "") { // Skip if get query for specific field
            $this->resetSearchParms();
            $this->setSessionRules($rules);
        }

        // Return query
        return $where;
    }

    // Build search SQL
    protected function buildSearchSql(&$where, $fld, $default, $multiValue)
    {
        $fldParm = $fld->Param;
        $fldVal = $default ? $fld->AdvancedSearch->SearchValueDefault : $fld->AdvancedSearch->SearchValue;
        $fldOpr = $default ? $fld->AdvancedSearch->SearchOperatorDefault : $fld->AdvancedSearch->SearchOperator;
        $fldCond = $default ? $fld->AdvancedSearch->SearchConditionDefault : $fld->AdvancedSearch->SearchCondition;
        $fldVal2 = $default ? $fld->AdvancedSearch->SearchValue2Default : $fld->AdvancedSearch->SearchValue2;
        $fldOpr2 = $default ? $fld->AdvancedSearch->SearchOperator2Default : $fld->AdvancedSearch->SearchOperator2;
        $fldVal = ConvertSearchValue($fldVal, $fldOpr, $fld);
        $fldVal2 = ConvertSearchValue($fldVal2, $fldOpr2, $fld);
        $fldOpr = ConvertSearchOperator($fldOpr, $fld, $fldVal);
        $fldOpr2 = ConvertSearchOperator($fldOpr2, $fld, $fldVal2);
        $wrk = "";
        if (is_array($fldVal)) {
            $fldVal = implode(Config("MULTIPLE_OPTION_SEPARATOR"), $fldVal);
        }
        if (is_array($fldVal2)) {
            $fldVal2 = implode(Config("MULTIPLE_OPTION_SEPARATOR"), $fldVal2);
        }
        if (Config("SEARCH_MULTI_VALUE_OPTION") == 1 && !$fld->UseFilter || !IsMultiSearchOperator($fldOpr)) {
            $multiValue = false;
        }
        if ($multiValue) {
            $wrk = $fldVal != "" ? GetMultiSearchSql($fld, $fldOpr, $fldVal, $this->Dbid) : ""; // Field value 1
            $wrk2 = $fldVal2 != "" ? GetMultiSearchSql($fld, $fldOpr2, $fldVal2, $this->Dbid) : ""; // Field value 2
            AddFilter($wrk, $wrk2, $fldCond);
        } else {
            $wrk = GetSearchSql($fld, $fldVal, $fldOpr, $fldCond, $fldVal2, $fldOpr2, $this->Dbid);
        }
        if ($this->SearchOption == "AUTO" && in_array($this->BasicSearch->getType(), ["AND", "OR"])) {
            $cond = $this->BasicSearch->getType();
        } else {
            $cond = SameText($this->SearchOption, "OR") ? "OR" : "AND";
        }
        AddFilter($where, $wrk, $cond);
    }

    // Show list of filters
    public function showFilterList()
    {
        global $Language;

        // Initialize
        $filterList = "";
        $captionClass = $this->isExport("email") ? "ew-filter-caption-email" : "ew-filter-caption";
        $captionSuffix = $this->isExport("email") ? ": " : "";

        // Field ID
        $filter = $this->queryBuilderWhere("ID");
        if (!$filter) {
            $this->buildSearchSql($filter, $this->ID, false, false);
        }
        if ($filter != "") {
            $filterList .= "<div><span class=\"" . $captionClass . "\">" . $this->ID->caption() . "</span>" . $captionSuffix . $filter . "</div>";
        }

        // Field Trademark
        $filter = $this->queryBuilderWhere("Trademark");
        if (!$filter) {
            $this->buildSearchSql($filter, $this->Trademark, false, false);
        }
        if ($filter != "") {
            $filterList .= "<div><span class=\"" . $captionClass . "\">" . $this->Trademark->caption() . "</span>" . $captionSuffix . $filter . "</div>";
        }

        // Field Model
        $filter = $this->queryBuilderWhere("Model");
        if (!$filter) {
            $this->buildSearchSql($filter, $this->Model, false, false);
        }
        if ($filter != "") {
            $filterList .= "<div><span class=\"" . $captionClass . "\">" . $this->Model->caption() . "</span>" . $captionSuffix . $filter . "</div>";
        }

        // Field HP
        $filter = $this->queryBuilderWhere("HP");
        if (!$filter) {
            $this->buildSearchSql($filter, $this->HP, false, false);
        }
        if ($filter != "") {
            $filterList .= "<div><span class=\"" . $captionClass . "\">" . $this->HP->caption() . "</span>" . $captionSuffix . $filter . "</div>";
        }

        // Field Cylinders
        $filter = $this->queryBuilderWhere("Cylinders");
        if (!$filter) {
            $this->buildSearchSql($filter, $this->Cylinders, false, false);
        }
        if ($filter != "") {
            $filterList .= "<div><span class=\"" . $captionClass . "\">" . $this->Cylinders->caption() . "</span>" . $captionSuffix . $filter . "</div>";
        }

        // Field Price
        $filter = $this->queryBuilderWhere("Price");
        if (!$filter) {
            $this->buildSearchSql($filter, $this->Price, false, false);
        }
        if ($filter != "") {
            $filterList .= "<div><span class=\"" . $captionClass . "\">" . $this->Price->caption() . "</span>" . $captionSuffix . $filter . "</div>";
        }

        // Field Doors
        $filter = $this->queryBuilderWhere("Doors");
        if (!$filter) {
            $this->buildSearchSql($filter, $this->Doors, false, false);
        }
        if ($filter != "") {
            $filterList .= "<div><span class=\"" . $captionClass . "\">" . $this->Doors->caption() . "</span>" . $captionSuffix . $filter . "</div>";
        }

        // Field Torque
        $filter = $this->queryBuilderWhere("Torque");
        if (!$filter) {
            $this->buildSearchSql($filter, $this->Torque, false, false);
        }
        if ($filter != "") {
            $filterList .= "<div><span class=\"" . $captionClass . "\">" . $this->Torque->caption() . "</span>" . $captionSuffix . $filter . "</div>";
        }
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
        $searchFlds[] = &$this->TransmissAutomatic;
        $searchFlds[] = &$this->Description;
        $searchFlds[] = &$this->Torque;
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
        if ($this->ID->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->Trademark->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->Model->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->HP->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->Cylinders->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->Description->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->Price->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->Doors->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->Torque->AdvancedSearch->issetSession()) {
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

        // Clear advanced search parameters
        $this->resetAdvancedSearchParms();

        // Clear queryBuilder
        $this->setSessionRules("");
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

    // Clear all advanced search parameters
    protected function resetAdvancedSearchParms()
    {
        $this->ID->AdvancedSearch->unsetSession();
        $this->Trademark->AdvancedSearch->unsetSession();
        $this->Model->AdvancedSearch->unsetSession();
        $this->HP->AdvancedSearch->unsetSession();
        $this->Cylinders->AdvancedSearch->unsetSession();
        $this->Description->AdvancedSearch->unsetSession();
        $this->Price->AdvancedSearch->unsetSession();
        $this->Doors->AdvancedSearch->unsetSession();
        $this->Torque->AdvancedSearch->unsetSession();
    }

    // Restore all search parameters
    protected function restoreSearchParms()
    {
        $this->RestoreSearch = true;

        // Restore basic search values
        $this->BasicSearch->load();

        // Restore advanced search values
        $this->ID->AdvancedSearch->load();
        $this->Trademark->AdvancedSearch->load();
        $this->Model->AdvancedSearch->load();
        $this->HP->AdvancedSearch->load();
        $this->Cylinders->AdvancedSearch->load();
        $this->Description->AdvancedSearch->load();
        $this->Price->AdvancedSearch->load();
        $this->Doors->AdvancedSearch->load();
        $this->Torque->AdvancedSearch->load();
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
            $defaultSortList = ""; // Set up default sort
            if ($this->getSessionOrderByList() == "" && $defaultSortList != "") {
                $this->setSessionOrderByList($defaultSortList);
            }
        }

        // Check for "order" parameter
        if (Get("order") !== null) {
            $this->CurrentOrder = Get("order");
            $this->CurrentOrderType = Get("ordertype", "");
            $this->updateSort($this->ID); // ID
            $this->updateSort($this->Trademark); // Trademark
            $this->updateSort($this->Model); // Model
            $this->updateSort($this->HP); // HP
            $this->updateSort($this->Cylinders); // Cylinders
            $this->updateSort($this->Price); // Price
            $this->updateSort($this->Doors); // Doors
            $this->updateSort($this->Torque); // Torque
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
                $this->setSessionOrderByList($orderBy);
                $this->ID->setSort("");
                $this->Trademark->setSort("");
                $this->Model->setSort("");
                $this->HP->setSort("");
                $this->Cylinders->setSort("");
                $this->TransmissionSpeeds->setSort("");
                $this->TransmissAutomatic->setSort("");
                $this->MPGCity->setSort("");
                $this->MPGHighway->setSort("");
                $this->Description->setSort("");
                $this->Price->setSort("");
                $this->Doors->setSort("");
                $this->Torque->setSort("");
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
        $this->MultiColumnLayout = Param(Config("PAGE_LAYOUT"));
        if (in_array($this->MultiColumnLayout, Config("PAGE_LAYOUTS"))) {
            $this->setSessionLayout($this->MultiColumnLayout);
        } else {
            $this->MultiColumnLayout = $this->getSessionLayout() ?? "cards";
        }

        // "griddelete"
        if ($this->AllowAddDeleteRow) {
            $item = &$this->ListOptions->add("griddelete");
            $item->CssClass = "text-nowrap";
            $item->OnLeft = $this->MultiColumnLayout == "cards" ? false : true;
            $item->Visible = false; // Default hidden
        }

        // Add group option item ("button")
        $item = &$this->ListOptions->addGroupOption();
        $item->Body = "";
        $item->OnLeft = $this->MultiColumnLayout == "cards" ? false : true;
        $item->Visible = false;

        // "view"
        $item = &$this->ListOptions->add("view");
        $item->CssClass = "text-nowrap";
        $item->Visible = $Security->canView();
        $item->OnLeft = $this->MultiColumnLayout == "cards" ? false : true;

        // "edit"
        $item = &$this->ListOptions->add("edit");
        $item->CssClass = "text-nowrap";
        $item->Visible = $Security->canEdit();
        $item->OnLeft = $this->MultiColumnLayout == "cards" ? false : true;

        // "copy"
        $item = &$this->ListOptions->add("copy");
        $item->CssClass = "text-nowrap";
        $item->Visible = $Security->canAdd();
        $item->OnLeft = $this->MultiColumnLayout == "cards" ? false : true;

        // "delete"
        $item = &$this->ListOptions->add("delete");
        $item->CssClass = "text-nowrap";
        $item->Visible = $Security->canDelete();
        $item->OnLeft = $this->MultiColumnLayout == "cards" ? false : true;

        // List actions
        $item = &$this->ListOptions->add("listactions");
        $item->CssClass = "text-nowrap";
        $item->OnLeft = $this->MultiColumnLayout == "cards" ? false : true;
        $item->Visible = false;
        $item->ShowInButtonGroup = false;
        $item->ShowInDropDown = false;

        // "checkbox"
        $item = &$this->ListOptions->add("checkbox");
        $item->Visible = $Security->canEdit();
        $item->OnLeft = $this->MultiColumnLayout == "cards" ? false : true;
        $item->Header = "<div class=\"form-check\"><input type=\"checkbox\" name=\"key\" id=\"key\" class=\"form-check-input\" data-ew-action=\"select-all-keys\"></div>";
        if ($item->OnLeft) {
            $item->moveTo(0);
        }
        $item->ShowInDropDown = false;
        $item->ShowInButtonGroup = false;

        // Drop down button for ListOptions
        $this->ListOptions->UseDropDownButton = true;
        if ($this->MultiColumnLayout == "cards") { // Multi column layout
            $this->ListOptions->DropDownButtonPhrase = $Language->phrase("ButtonListOptionsCard");
            $this->ListOptions->TagClassName = "ew-multi-column-list-option-card m-1";
        } else {
            $this->ListOptions->DropDownButtonPhrase = $Language->phrase("ButtonListOptions");
            $this->ListOptions->TagClassName = "ew-multi-column-list-option-table";
        }
        $this->ListOptions->UseButtonGroup = false;
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
        $pageUrl = $this->pageUrl(false);
        if ($this->CurrentMode == "view") {
            // "view"
            $opt = $this->ListOptions["view"];
            $viewcaption = HtmlTitle($Language->phrase("ViewLink"));
            if ($Security->canView()) {
                if ($this->ModalView && !IsMobile()) {
                    $opt->Body = "<a class=\"ew-row-link ew-view\" title=\"" . $viewcaption . "\" data-table=\"cars\" data-caption=\"" . $viewcaption . "\" data-ew-action=\"modal\" data-action=\"view\" data-ajax=\"" . ($this->UseAjaxActions ? "true" : "false") . "\" data-url=\"" . HtmlEncode(GetUrl($this->ViewUrl)) . "\" data-btn=\"null\">" . $Language->phrase("ViewLink") . "</a>";
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
                    $opt->Body = "<a class=\"ew-row-link ew-edit\" title=\"" . $editcaption . "\" data-table=\"cars\" data-caption=\"" . $editcaption . "\" data-ew-action=\"modal\" data-action=\"edit\" data-ajax=\"" . ($this->UseAjaxActions ? "true" : "false") . "\" data-url=\"" . HtmlEncode(GetUrl($this->EditUrl)) . "\" data-btn=\"SaveBtn\">" . $Language->phrase("EditLink") . "</a>";
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
                    $opt->Body = "<a class=\"ew-row-link ew-copy\" title=\"" . $copycaption . "\" data-table=\"cars\" data-caption=\"" . $copycaption . "\" data-ew-action=\"modal\" data-action=\"add\" data-ajax=\"" . ($this->UseAjaxActions ? "true" : "false") . "\" data-url=\"" . HtmlEncode(GetUrl($this->CopyUrl)) . "\" data-btn=\"AddBtn\">" . $Language->phrase("CopyLink") . "</a>";
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
                    $link = "<li><button type=\"button\" class=\"dropdown-item ew-action ew-list-action\" data-caption=\"" . HtmlTitle($caption) . "\" data-ew-action=\"submit\" form=\"fcarslist\" data-key=\"" . $this->keyToJson(true) . "\"" . $listaction->toDataAttrs() . ">" . $icon . " " . $listaction->Caption . "</button></li>";
                    if ($link != "") {
                        $links[] = $link;
                        if ($body == "") { // Setup first button
                            $body = "<button type=\"button\" class=\"btn btn-default ew-action ew-list-action\" title=\"" . HtmlTitle($caption) . "\" data-caption=\"" . HtmlTitle($caption) . "\" data-ew-action=\"submit\" form=\"fcarslist\" data-key=\"" . $this->keyToJson(true) . "\"" . $listaction->toDataAttrs() . ">" . $icon . " " . $listaction->Caption . "</button>";
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
        $opt->Body = "<div class=\"form-check\"><input type=\"checkbox\" id=\"key_m_" . $this->RowCount . "\" name=\"key_m[]\" class=\"form-check-input ew-multi-select\" value=\"" . HtmlEncode($this->ID->CurrentValue) . "\"></div>";
        if (($this->isGridEdit() || $this->isMultiEdit()) && is_numeric($this->RowIndex)) {
            $this->MultiSelectKey .= "<input type=\"hidden\" name=\"k" . $this->RowIndex . "_hash\" id=\"k" . $this->RowIndex . "_hash\" value=\"" . $this->HashValue . "\">";
        }
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
            $item->Body = "<a class=\"ew-add-edit ew-add\" title=\"" . $addcaption . "\" data-table=\"cars\" data-caption=\"" . $addcaption . "\" data-ew-action=\"modal\" data-action=\"add\" data-ajax=\"" . ($this->UseAjaxActions ? "true" : "false") . "\" data-url=\"" . HtmlEncode(GetUrl($this->AddUrl)) . "\" data-btn=\"AddBtn\">" . $Language->phrase("AddLink") . "</a>";
        } else {
            $item->Body = "<a class=\"ew-add-edit ew-add\" title=\"" . $addcaption . "\" data-caption=\"" . $addcaption . "\" href=\"" . HtmlEncode(GetUrl($this->AddUrl)) . "\">" . $Language->phrase("AddLink") . "</a>";
        }
        $item->Visible = $this->AddUrl != "" && $Security->canAdd();
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
            $Language->phrase("UpdateSelectedLink", true) . "\" data-table=\"cars\" data-caption=\"" .
            $Language->phrase("UpdateSelectedLink", true) . "\" form=\"fcarslist\" data-ew-action=\"" .
            ($this->ModalUpdate && !IsMobile() ? "modal" : "submit") . "\"" .
            ($this->ModalUpdate && !IsMobile() ? " data-action=\"update\"" : "") .
            ($this->UseAjaxActions ? " data-ajax=\"true\"" : "") .
            " data-url=\"" . GetUrl($this->MultiUpdateUrl) . "\">" . $Language->phrase("UpdateSelectedLink") . "</button>";
        $item->Visible = $Security->canEdit();

        // Add multi edit
        $item = &$option->add("multiedit");
        $item->Body = "<button type=\"button\" class=\"ew-action ew-multi-edit\" title=\"" .
            $Language->phrase("EditSelectedLink", true) . "\" data-table=\"cars\" data-caption=\"" .
            $Language->phrase("EditSelectedLink", true) . "\" form=\"fcarslist\" data-ew-action=\"" .
            ($this->ModalMultiEdit && !IsMobile() ? "modal" : "submit") . "\"" .
            ($this->ModalMultiEdit && !IsMobile() ? " data-action=\"multiedit\" data-btn=\"SaveBtn\"" : "") .
            ($this->UseAjaxActions ? " data-ajax=\"true\"" : "") .
            " data-url=\"" . GetUrl($this->MultiEditUrl) . "\">" . $Language->phrase("EditSelectedLink") . "</button>";
        $item->Visible = $this->MultiEditUrl != "" && $Security->canEdit();

        // Set up layout buttons/select all checkbox for multi column list page
        $this->LayoutOptions = new ListOptions(["TagClassName" => "ew-layout-option mb-3"]);
        $item = &$this->LayoutOptions->add("cards");
        $classname = $this->MultiColumnLayout == "cards" ? " disabled" : "";
        $item->Body = "<button type=\"button\" class=\"btn btn-default " . $classname . "\" title=\"" . $Language->phrase("MultiColumnCards", true) . "\" data-ew-action=\"layout\" data-layout=\"cards\" data-url=\"" . $this->pageUrl(false) . "\">" . $Language->phrase("MultiColumnCards") . "</button>";
        $item->Visible = true;
        $item = &$this->LayoutOptions->add("table");
        $classname = $this->MultiColumnLayout == "table" ? " disabled" : "";
        $item->Body = "<button type=\"button\" class=\"btn btn-default " . $classname . "\" title=\"" . $Language->phrase("MultiColumnTable", true) . "\" data-ew-action=\"layout\" data-layout=\"table\" data-url=\"" . $this->pageUrl(false) . "\">" . $Language->phrase("MultiColumnTable") . "</button>";
        $item->Visible = true;
        $item = &$this->LayoutOptions->add("checkbox");
        $item->Body = "<div class=\"btn-group\"><div class=\"form-check\"><input type=\"checkbox\" name=\"key\" id=\"key\" class=\"form-check-input\" data-ew-action=\"select-all-keys\" form=\"fcarslist\"><label class=\"form-check-label\" for=\"key\">" . $Language->phrase("SelectAll") . "</label></div></div>";
        $item->ShowInButtonGroup = true;
        $item->Visible = $Security->canEdit() && $this->MultiColumnLayout == "cards";
        $this->LayoutOptions->addGroupOption();
        $this->LayoutOptions->UseDropDownButton = false;
        $this->LayoutOptions->UseButtonGroup = true;
        if (IsExport("print") || $this->IsModal) {
            $this->LayoutOptions->hideAllOptions();
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
        $item->Body = "<a class=\"ew-save-filter\" data-form=\"fcarssrch\" data-ew-action=\"none\">" . $Language->phrase("SaveCurrentFilter") . "</a>";
        $item->Visible = true;
        $item = &$this->FilterOptions->add("deletefilter");
        $item->Body = "<a class=\"ew-delete-filter\" data-form=\"fcarssrch\" data-ew-action=\"none\">" . $Language->phrase("DeleteFilter") . "</a>";
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
                    $item->Body = '<button type="button" class="btn btn-default ew-action ew-list-action" title="' . HtmlEncode($caption) . '" data-caption="' . HtmlEncode($caption) . '" data-ew-action="submit" form="fcarslist"' . $listaction->toDataAttrs() . '>' . $icon . '</button>';
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
                if ($this->MultiColumnLayout == "table") { // Check if table layout
                    if ($this->AllowAddDeleteRow) {
                        // Add add blank row
                        $option = $options["addedit"];
                        $option->UseDropDownButton = false;
                        $item = &$option->add("addblankrow");
                        $item->Body = "<a type=\"button\" class=\"ew-add-edit ew-add-blank-row\" title=\"" . HtmlTitle($Language->phrase("AddBlankRow")) . "\" data-caption=\"" . HtmlTitle($Language->phrase("AddBlankRow")) . "\" data-ew-action=\"add-grid-row\">" . $Language->phrase("AddBlankRow") . "</a>";
                        $item->Visible = $Security->canAdd();
                    }
                }
                if (!($this->ModalGridAdd && !IsMobile())) {
                    $option = $options["action"];
                    $option->UseDropDownButton = false;
                    // Add grid insert
                    $item = &$option->add("gridinsert");
                    $item->Body = "<button class=\"ew-action ew-grid-insert\" title=\"" . HtmlTitle($Language->phrase("GridInsertLink")) . "\" data-caption=\"" . HtmlTitle($Language->phrase("GridInsertLink")) . "\" form=\"fcarslist\" formaction=\"" . GetUrl($this->pageName()) . "\">" . $Language->phrase("GridInsertLink") . "</button>";
                    // Add grid cancel
                    $item = &$option->add("gridcancel");
                    $cancelurl = $this->addMasterUrl($pageUrl . "action=cancel");
                    $item->Body = "<a type=\"button\" class=\"ew-action ew-grid-cancel\" title=\"" . HtmlTitle($Language->phrase("GridCancelLink")) . "\" data-caption=\"" . HtmlTitle($Language->phrase("GridCancelLink")) . "\" href=\"" . $cancelurl . "\">" . $Language->phrase("GridCancelLink") . "</a>";
                }
            }

            // Grid-Edit
            if ($this->isGridEdit()) {
                if ($this->MultiColumnLayout == "table") { // Check if table layout
                    if ($this->AllowAddDeleteRow) {
                        // Add add blank row
                        $option = $options["addedit"];
                        $option->UseDropDownButton = false;
                        $item = &$option->add("addblankrow");
                        $item->Body = "<button class=\"ew-add-edit ew-add-blank-row\" title=\"" . HtmlTitle($Language->phrase("AddBlankRow")) . "\" data-caption=\"" . HtmlTitle($Language->phrase("AddBlankRow")) . "\" data-ew-action=\"add-grid-row\">" . $Language->phrase("AddBlankRow") . "</button>";
                        $item->Visible = $Security->canAdd();
                    }
                }
                if (!($this->ModalGridEdit && !IsMobile())) {
                    $option = $options["action"];
                    $option->UseDropDownButton = false;
                    if ($this->UpdateConflict == "U") { // Record already updated by other user
                        $item = &$option->add("reload");
                        $item->Body = "<a class=\"ew-action ew-grid-reload\" title=\"" . HtmlTitle($Language->phrase("ReloadLink")) . "\" data-caption=\"" . HtmlTitle($Language->phrase("ReloadLink")) . "\" href=\"" . HtmlEncode(GetUrl($this->GridEditUrl)) . "\">" . $Language->phrase("ReloadLink") . "</a>";
                        $item = &$option->add("overwrite");
                        $item->Body = "<button class=\"ew-action ew-grid-overwrite\" title=\"" . HtmlTitle($Language->phrase("OverwriteLink")) . "\" data-caption=\"" . HtmlTitle($Language->phrase("OverwriteLink")) . "\" form=\"fcarslist\" formaction=\"" . GetUrl($this->pageName()) . "\">" . $Language->phrase("OverwriteLink") . "</button>";
                        $item = &$option->add("cancel");
                        $cancelurl = $this->addMasterUrl($pageUrl . "action=cancel");
                        $item->Body = "<a class=\"ew-action ew-grid-cancel\" title=\"" . HtmlTitle($Language->phrase("ConflictCancelLink")) . "\" data-caption=\"" . HtmlTitle($Language->phrase("ConflictCancelLink")) . "\" href=\"" . $cancelurl . "\">" . $Language->phrase("ConflictCancelLink") . "</a>";
                    } else {
                        $item = &$option->add("gridsave");
                        $item->Body = "<button class=\"ew-action ew-grid-save\" title=\"" . HtmlTitle($Language->phrase("GridSaveLink")) . "\" data-caption=\"" . HtmlTitle($Language->phrase("GridSaveLink")) . "\" form=\"fcarslist\" formaction=\"" . GetUrl($this->pageName()) . "\">" . $Language->phrase("GridSaveLink") . "</button>";
                        $item = &$option->add("gridcancel");
                        $cancelurl = $this->addMasterUrl($pageUrl . "action=cancel");
                        $item->Body = "<a class=\"ew-action ew-grid-cancel\" title=\"" . HtmlTitle($Language->phrase("GridCancelLink")) . "\" data-caption=\"" . HtmlTitle($Language->phrase("GridCancelLink")) . "\" href=\"" . $cancelurl . "\">" . $Language->phrase("GridCancelLink") . "</a>";
                    }
                }
            }

            // Multi-Edit
            if ($this->isMultiEdit()) {
                if (!$this->ModalMultiEdit) {
                    $option = $options["action"];
                    $option->UseDropDownButton = false;
                    if ($this->UpdateConflict == "U") { // Record already updated by other user
                        $item = &$option->add("reload");
                        $item->Body = "<a class=\"ew-action ew-grid-reload\" title=\"" . HtmlTitle($Language->phrase("ReloadLink")) . "\" data-caption=\"" . HtmlTitle($Language->phrase("ReloadLink")) . "\" href=\"" . HtmlEncode(GetUrl($this->MultiEditUrl)) . "\">" . $Language->phrase("ReloadLink") . "</a>";
                        $item = &$option->add("overwrite");
                        $item->Body = "<button class=\"ew-action ew-grid-overwrite\" title=\"" . HtmlTitle($Language->phrase("OverwriteLink")) . "\" data-caption=\"" . HtmlTitle($Language->phrase("OverwriteLink")) . "\" form=\"fcarslist\" formaction=\"" . GetUrl($this->pageName()) . "\">" . $Language->phrase("OverwriteLink") . "</button>";
                        $item = &$option->add("cancel");
                        $cancelurl = $this->addMasterUrl($pageUrl . "action=cancel");
                        $item->Body = "<a class=\"ew-action ew-grid-cancel\" title=\"" . HtmlTitle($Language->phrase("ConflictCancelLink")) . "\" data-caption=\"" . HtmlTitle($Language->phrase("ConflictCancelLink")) . "\" href=\"" . $cancelurl . "\">" . $Language->phrase("ConflictCancelLink") . "</a>";
                    } else {
                        $item = &$option->add("gridsave");
                        $item->Body = "<button class=\"ew-action ew-grid-save\" title=\"" . HtmlTitle($Language->phrase("GridSaveLink")) . "\" data-caption=\"" . HtmlTitle($Language->phrase("GridSaveLink")) . "\" form=\"fcarslist\" formaction=\"" . GetUrl($this->pageName()) . "\">" . $Language->phrase("GridSaveLink") . "</button>";
                        $item = &$option->add("gridcancel");
                        $cancelurl = $this->addMasterUrl($pageUrl . "action=cancel");
                        $item->Body = "<a class=\"ew-action ew-grid-cancel\" title=\"" . HtmlTitle($Language->phrase("GridCancelLink")) . "\" data-caption=\"" . HtmlTitle($Language->phrase("GridCancelLink")) . "\" href=\"" . $cancelurl . "\">" . $Language->phrase("GridCancelLink") . "</a>";
                    }
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

    // Get multi-column row CSS class
    public function getMultiColumnRowClass()
    {
        if ($this->isAddOrEdit() && ($this->RowType == ROWTYPE_ADD || $this->RowType == ROWTYPE_EDIT)) {
            return "row"; // Full row
        }
        return "row row-cols-1 " . $this->MultiColumnGridClass . " g-4 ew-multi-column-row";
    }

    // Get multi-column col-* CSS class
    public function getMultiColumnColClass()
    {
        if ($this->isAddOrEdit() && ($this->RowType == ROWTYPE_ADD || $this->RowType == ROWTYPE_EDIT)) {
            return $this->MultiColumnEditClass; // Full row
        }
        return "col";
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
        if (($this->isGridAdd() || $this->isGridEdit()) && $this->MultiColumnLayout == "table") { // Render template row first
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
                $this->RowAttrs->merge(["data-rowindex" => $this->RowIndex, "id" => "r0_cars", "data-rowtype" => ROWTYPE_ADD]);
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
        if ($this->isGridEdit()) { // Grid edit
            if ($this->EventCancelled) {
                $this->restoreCurrentRowFormValues($this->RowIndex); // Restore form values
            }
            if ($this->RowAction == "insert") {
                $this->RowType = ROWTYPE_ADD; // Render add
            } else {
                $this->RowType = ROWTYPE_EDIT; // Render edit
            }
            if (!$this->EventCancelled) {
                $this->HashValue = $this->getRowHash($this->Recordset); // Get hash value for record
            }
        }
        if ($this->isMultiEdit()) { // Multi edit
            if ($this->EventCancelled) {
                $this->restoreCurrentRowFormValues($this->RowIndex); // Restore form values
            }
            $this->RowType = ROWTYPE_EDIT; // Render edit
            if (!$this->EventCancelled) {
                $this->HashValue = $this->getRowHash($this->Recordset); // Get hash value for record
            }
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
            "id" => "r" . $this->RowCount . "_cars",
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
        $this->Picture->Upload->Index = $CurrentForm->Index;
        $this->Picture->Upload->uploadFile();
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

    // Load search values for validation
    protected function loadSearchValues()
    {
        // Load search values
        $hasValue = false;

        // Load query builder rules
        $rules = Post("rules");
        if ($rules && $this->Command == "") {
            $this->QueryRules = $rules;
            $this->Command = "search";
        }

        // ID
        if ($this->ID->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->ID->AdvancedSearch->SearchValue != "" || $this->ID->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // Trademark
        if ($this->Trademark->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->Trademark->AdvancedSearch->SearchValue != "" || $this->Trademark->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // Model
        if ($this->Model->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->Model->AdvancedSearch->SearchValue != "" || $this->Model->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // HP
        if ($this->HP->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->HP->AdvancedSearch->SearchValue != "" || $this->HP->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // Cylinders
        if ($this->Cylinders->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->Cylinders->AdvancedSearch->SearchValue != "" || $this->Cylinders->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // Description
        if ($this->Description->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->Description->AdvancedSearch->SearchValue != "" || $this->Description->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // Price
        if ($this->Price->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->Price->AdvancedSearch->SearchValue != "" || $this->Price->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // Doors
        if ($this->Doors->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->Doors->AdvancedSearch->SearchValue != "" || $this->Doors->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // Torque
        if ($this->Torque->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->Torque->AdvancedSearch->SearchValue != "" || $this->Torque->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }
        return $hasValue;
    }

    // Load form values
    protected function loadFormValues()
    {
        // Load from form
        global $CurrentForm;
        $validate = !Config("SERVER_VALIDATE");

        // Check field name 'ID' first before field var 'x_ID'
        $val = $CurrentForm->hasValue("ID") ? $CurrentForm->getValue("ID") : $CurrentForm->getValue("x_ID");
        if (!$this->ID->IsDetailKey && !$this->isGridAdd() && !$this->isAdd()) {
            $this->ID->setFormValue($val);
        }

        // Check field name 'Trademark' first before field var 'x_Trademark'
        $val = $CurrentForm->hasValue("Trademark") ? $CurrentForm->getValue("Trademark") : $CurrentForm->getValue("x_Trademark");
        if (!$this->Trademark->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->Trademark->Visible = false; // Disable update for API request
            } else {
                $this->Trademark->setFormValue($val);
            }
        }
        if ($CurrentForm->hasValue("o_Trademark")) {
            $this->Trademark->setOldValue($CurrentForm->getValue("o_Trademark"));
        }

        // Check field name 'Model' first before field var 'x_Model'
        $val = $CurrentForm->hasValue("Model") ? $CurrentForm->getValue("Model") : $CurrentForm->getValue("x_Model");
        if (!$this->Model->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->Model->Visible = false; // Disable update for API request
            } else {
                $this->Model->setFormValue($val);
            }
        }
        if ($CurrentForm->hasValue("o_Model")) {
            $this->Model->setOldValue($CurrentForm->getValue("o_Model"));
        }

        // Check field name 'HP' first before field var 'x_HP'
        $val = $CurrentForm->hasValue("HP") ? $CurrentForm->getValue("HP") : $CurrentForm->getValue("x_HP");
        if (!$this->HP->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->HP->Visible = false; // Disable update for API request
            } else {
                $this->HP->setFormValue($val);
            }
        }
        if ($CurrentForm->hasValue("o_HP")) {
            $this->HP->setOldValue($CurrentForm->getValue("o_HP"));
        }

        // Check field name 'Cylinders' first before field var 'x_Cylinders'
        $val = $CurrentForm->hasValue("Cylinders") ? $CurrentForm->getValue("Cylinders") : $CurrentForm->getValue("x_Cylinders");
        if (!$this->Cylinders->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->Cylinders->Visible = false; // Disable update for API request
            } else {
                $this->Cylinders->setFormValue($val, true, $validate);
            }
        }
        if ($CurrentForm->hasValue("o_Cylinders")) {
            $this->Cylinders->setOldValue($CurrentForm->getValue("o_Cylinders"));
        }

        // Check field name 'Price' first before field var 'x_Price'
        $val = $CurrentForm->hasValue("Price") ? $CurrentForm->getValue("Price") : $CurrentForm->getValue("x_Price");
        if (!$this->Price->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->Price->Visible = false; // Disable update for API request
            } else {
                $this->Price->setFormValue($val, true, $validate);
            }
        }
        if ($CurrentForm->hasValue("o_Price")) {
            $this->Price->setOldValue($CurrentForm->getValue("o_Price"));
        }

        // Check field name 'Doors' first before field var 'x_Doors'
        $val = $CurrentForm->hasValue("Doors") ? $CurrentForm->getValue("Doors") : $CurrentForm->getValue("x_Doors");
        if (!$this->Doors->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->Doors->Visible = false; // Disable update for API request
            } else {
                $this->Doors->setFormValue($val, true, $validate);
            }
        }
        if ($CurrentForm->hasValue("o_Doors")) {
            $this->Doors->setOldValue($CurrentForm->getValue("o_Doors"));
        }

        // Check field name 'Torque' first before field var 'x_Torque'
        $val = $CurrentForm->hasValue("Torque") ? $CurrentForm->getValue("Torque") : $CurrentForm->getValue("x_Torque");
        if (!$this->Torque->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->Torque->Visible = false; // Disable update for API request
            } else {
                $this->Torque->setFormValue($val);
            }
        }
        if ($CurrentForm->hasValue("o_Torque")) {
            $this->Torque->setOldValue($CurrentForm->getValue("o_Torque"));
        }
        $this->getUploadFiles(); // Get upload files
        if (!$this->isOverwrite()) {
            $this->HashValue = $CurrentForm->getValue("k_hash");
        }
    }

    // Restore form values
    public function restoreFormValues()
    {
        global $CurrentForm;
        if (!$this->isGridAdd() && !$this->isAdd()) {
            $this->ID->CurrentValue = $this->ID->FormValue;
        }
        $this->Trademark->CurrentValue = $this->Trademark->FormValue;
        $this->Model->CurrentValue = $this->Model->FormValue;
        $this->HP->CurrentValue = $this->HP->FormValue;
        $this->Cylinders->CurrentValue = $this->Cylinders->FormValue;
        $this->Price->CurrentValue = $this->Price->FormValue;
        $this->Doors->CurrentValue = $this->Doors->FormValue;
        $this->Torque->CurrentValue = $this->Torque->FormValue;
        if (!$this->isOverwrite()) {
            $this->HashValue = $CurrentForm->getValue("k_hash");
        }
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
        $this->ID->setDbValue($row['ID']);
        $this->Trademark->setDbValue($row['Trademark']);
        if (array_key_exists('EV__Trademark', $row)) {
            $this->Trademark->VirtualValue = $row['EV__Trademark']; // Set up virtual field value
        } else {
            $this->Trademark->VirtualValue = ""; // Clear value
        }
        $this->Model->setDbValue($row['Model']);
        if (array_key_exists('EV__Model', $row)) {
            $this->Model->VirtualValue = $row['EV__Model']; // Set up virtual field value
        } else {
            $this->Model->VirtualValue = ""; // Clear value
        }
        $this->HP->setDbValue($row['HP']);
        $this->Cylinders->setDbValue($row['Cylinders']);
        $this->TransmissionSpeeds->setDbValue($row['Transmission Speeds']);
        $this->TransmissAutomatic->setDbValue($row['TransmissAutomatic']);
        $this->MPGCity->setDbValue($row['MPG City']);
        $this->MPGHighway->setDbValue($row['MPG Highway']);
        $this->Description->setDbValue($row['Description']);
        $this->Price->setDbValue($row['Price']);
        $this->Picture->Upload->DbValue = $row['Picture'];
        if (is_resource($this->Picture->Upload->DbValue) && get_resource_type($this->Picture->Upload->DbValue) == "stream") { // Byte array
            $this->Picture->Upload->DbValue = stream_get_contents($this->Picture->Upload->DbValue);
        }
        $this->Doors->setDbValue($row['Doors']);
        $this->Torque->setDbValue($row['Torque']);
    }

    // Return a row with default values
    protected function newRow()
    {
        $row = [];
        $row['ID'] = $this->ID->DefaultValue;
        $row['Trademark'] = $this->Trademark->DefaultValue;
        $row['Model'] = $this->Model->DefaultValue;
        $row['HP'] = $this->HP->DefaultValue;
        $row['Cylinders'] = $this->Cylinders->DefaultValue;
        $row['Transmission Speeds'] = $this->TransmissionSpeeds->DefaultValue;
        $row['TransmissAutomatic'] = $this->TransmissAutomatic->DefaultValue;
        $row['MPG City'] = $this->MPGCity->DefaultValue;
        $row['MPG Highway'] = $this->MPGHighway->DefaultValue;
        $row['Description'] = $this->Description->DefaultValue;
        $row['Price'] = $this->Price->DefaultValue;
        $row['Picture'] = $this->Picture->DefaultValue;
        $row['Doors'] = $this->Doors->DefaultValue;
        $row['Torque'] = $this->Torque->DefaultValue;
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

        // ID

        // Trademark

        // Model

        // HP

        // Cylinders

        // Transmission Speeds

        // TransmissAutomatic

        // MPG City

        // MPG Highway

        // Description

        // Price

        // Picture

        // Doors

        // Torque

        // Accumulate aggregate value
        if ($this->RowType != ROWTYPE_AGGREGATEINIT && $this->RowType != ROWTYPE_AGGREGATE && $this->RowType != ROWTYPE_PREVIEW_FIELD) {
            $this->Trademark->Count++; // Increment count
        }

        // View row
        if ($this->RowType == ROWTYPE_VIEW) {
            // ID
            $this->ID->ViewValue = $this->ID->CurrentValue;

            // Trademark
            if ($this->Trademark->VirtualValue != "") {
                $this->Trademark->ViewValue = $this->Trademark->VirtualValue;
            } else {
                $curVal = strval($this->Trademark->CurrentValue);
                if ($curVal != "") {
                    $this->Trademark->ViewValue = $this->Trademark->lookupCacheOption($curVal);
                    if ($this->Trademark->ViewValue === null) { // Lookup from database
                        $filterWrk = SearchFilter("`ID`", "=", $curVal, DATATYPE_NUMBER, "");
                        $sqlWrk = $this->Trademark->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                        $conn = Conn();
                        $config = $conn->getConfiguration();
                        $config->setResultCacheImpl($this->Cache);
                        $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                        $ari = count($rswrk);
                        if ($ari > 0) { // Lookup values found
                            $arwrk = $this->Trademark->Lookup->renderViewRow($rswrk[0]);
                            $this->Trademark->ViewValue = $this->Trademark->displayValue($arwrk);
                        } else {
                            $this->Trademark->ViewValue = $this->Trademark->CurrentValue;
                        }
                    }
                } else {
                    $this->Trademark->ViewValue = null;
                }
            }

            // Model
            if ($this->Model->VirtualValue != "") {
                $this->Model->ViewValue = $this->Model->VirtualValue;
            } else {
                $curVal = strval($this->Model->CurrentValue);
                if ($curVal != "") {
                    $this->Model->ViewValue = $this->Model->lookupCacheOption($curVal);
                    if ($this->Model->ViewValue === null) { // Lookup from database
                        $filterWrk = SearchFilter("`ID`", "=", $curVal, DATATYPE_NUMBER, "");
                        $sqlWrk = $this->Model->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                        $conn = Conn();
                        $config = $conn->getConfiguration();
                        $config->setResultCacheImpl($this->Cache);
                        $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                        $ari = count($rswrk);
                        if ($ari > 0) { // Lookup values found
                            $arwrk = $this->Model->Lookup->renderViewRow($rswrk[0]);
                            $this->Model->ViewValue = $this->Model->displayValue($arwrk);
                        } else {
                            $this->Model->ViewValue = $this->Model->CurrentValue;
                        }
                    }
                } else {
                    $this->Model->ViewValue = null;
                }
            }

            // HP
            $this->HP->ViewValue = $this->HP->CurrentValue;

            // Cylinders
            $this->Cylinders->ViewValue = $this->Cylinders->CurrentValue;

            // Price
            $this->Price->ViewValue = $this->Price->CurrentValue;
            $this->Price->ViewValue = FormatCurrency($this->Price->ViewValue, $this->Price->formatPattern());

            // Picture
            if (!EmptyValue($this->Picture->Upload->DbValue)) {
                $this->Picture->ImageWidth = 200;
                $this->Picture->ImageHeight = 0;
                $this->Picture->ImageAlt = $this->Picture->alt();
                $this->Picture->ImageCssClass = "ew-image";
                $this->Picture->ViewValue = $this->ID->CurrentValue;
                $this->Picture->IsBlobImage = IsImageFile(ContentExtension($this->Picture->Upload->DbValue));
            } else {
                $this->Picture->ViewValue = "";
            }

            // Doors
            $this->Doors->ViewValue = $this->Doors->CurrentValue;
            $this->Doors->ViewValue = FormatNumber($this->Doors->ViewValue, $this->Doors->formatPattern());

            // Torque
            $this->Torque->ViewValue = $this->Torque->CurrentValue;

            // ID
            $this->ID->HrefValue = "";
            $this->ID->TooltipValue = "";
            if (!$this->isExport()) {
                $this->ID->ViewValue = $this->highlightValue($this->ID);
            }

            // Trademark
            $this->Trademark->HrefValue = "";
            $this->Trademark->TooltipValue = "";
            if (!$this->isExport()) {
                $this->Trademark->ViewValue = $this->highlightValue($this->Trademark);
            }

            // Model
            $this->Model->HrefValue = "";
            if (!$this->isExport()) {
                $this->Model->TooltipValue = strval($this->Description->CurrentValue);
                $this->Model->TooltipWidth = 400;
                if ($this->Model->HrefValue == "") {
                    $this->Model->HrefValue = "javascript:void(0);";
                }
                $this->Model->LinkAttrs->appendClass("ew-tooltip-link");
                $this->Model->LinkAttrs["data-tooltip-id"] = "tt_cars_x" . $this->RowCount . "_Model";
                $this->Model->LinkAttrs["data-tooltip-width"] = $this->Model->TooltipWidth;
                $this->Model->LinkAttrs["data-bs-placement"] = IsRTL() ? "left" : "right";
            }
            if (!$this->isExport()) {
                $this->Model->ViewValue = $this->highlightValue($this->Model);
            }

            // HP
            $this->HP->HrefValue = "";
            $this->HP->TooltipValue = "";
            if (!$this->isExport()) {
                $this->HP->ViewValue = $this->highlightValue($this->HP);
            }

            // Cylinders
            $this->Cylinders->HrefValue = "";
            $this->Cylinders->TooltipValue = "";
            if (!$this->isExport()) {
                $this->Cylinders->ViewValue = $this->highlightValue($this->Cylinders);
            }

            // Price
            $this->Price->HrefValue = "";
            $this->Price->TooltipValue = "";

            // Picture
            if (!empty($this->Picture->Upload->DbValue)) {
                $this->Picture->HrefValue = GetFileUploadUrl($this->Picture, $this->ID->CurrentValue);
                $this->Picture->LinkAttrs["target"] = "_blank";
                if ($this->Picture->IsBlobImage && empty($this->Picture->LinkAttrs["target"])) {
                    $this->Picture->LinkAttrs["target"] = "_blank";
                }
                if ($this->isExport()) {
                    $this->Picture->HrefValue = FullUrl($this->Picture->HrefValue, "href");
                }
            } else {
                $this->Picture->HrefValue = "";
            }
            $this->Picture->ExportHrefValue = GetFileUploadUrl($this->Picture, $this->ID->CurrentValue);
            $this->Picture->TooltipValue = "";
            if ($this->Picture->UseColorbox) {
                if (EmptyValue($this->Picture->TooltipValue)) {
                    $this->Picture->LinkAttrs["title"] = $Language->phrase("ViewImageGallery");
                }
                $this->Picture->LinkAttrs["data-rel"] = "cars_x" . $this->RowCount . "_Picture";
                $this->Picture->LinkAttrs->appendClass("ew-lightbox");
            }

            // Doors
            $this->Doors->HrefValue = "";
            $this->Doors->TooltipValue = "";

            // Torque
            $this->Torque->HrefValue = "";
            $this->Torque->TooltipValue = "";
            if (!$this->isExport()) {
                $this->Torque->ViewValue = $this->highlightValue($this->Torque);
            }
        } elseif ($this->RowType == ROWTYPE_ADD) {
            // ID

            // Trademark
            $this->Trademark->setupEditAttributes();
            $curVal = trim(strval($this->Trademark->CurrentValue));
            if ($curVal != "") {
                $this->Trademark->ViewValue = $this->Trademark->lookupCacheOption($curVal);
            } else {
                $this->Trademark->ViewValue = $this->Trademark->Lookup !== null && is_array($this->Trademark->lookupOptions()) && count($this->Trademark->lookupOptions()) > 0 ? $curVal : null;
            }
            if ($this->Trademark->ViewValue !== null) { // Load from cache
                $this->Trademark->EditValue = array_values($this->Trademark->lookupOptions());
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = SearchFilter("`ID`", "=", $this->Trademark->CurrentValue, DATATYPE_NUMBER, "");
                }
                $sqlWrk = $this->Trademark->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCacheImpl($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->Trademark->EditValue = $arwrk;
            }
            $this->Trademark->PlaceHolder = RemoveHtml($this->Trademark->caption());

            // Model
            $this->Model->setupEditAttributes();
            $curVal = trim(strval($this->Model->CurrentValue));
            if ($curVal != "") {
                $this->Model->ViewValue = $this->Model->lookupCacheOption($curVal);
            } else {
                $this->Model->ViewValue = $this->Model->Lookup !== null && is_array($this->Model->lookupOptions()) && count($this->Model->lookupOptions()) > 0 ? $curVal : null;
            }
            if ($this->Model->ViewValue !== null) { // Load from cache
                $this->Model->EditValue = array_values($this->Model->lookupOptions());
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = SearchFilter("`ID`", "=", $this->Model->CurrentValue, DATATYPE_NUMBER, "");
                }
                $sqlWrk = $this->Model->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCacheImpl($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->Model->EditValue = $arwrk;
            }
            $this->Model->PlaceHolder = RemoveHtml($this->Model->caption());

            // HP
            $this->HP->setupEditAttributes();
            if (!$this->HP->Raw) {
                $this->HP->CurrentValue = HtmlDecode($this->HP->CurrentValue);
            }
            $this->HP->EditValue = HtmlEncode($this->HP->CurrentValue);
            $this->HP->PlaceHolder = RemoveHtml($this->HP->caption());

            // Cylinders
            $this->Cylinders->setupEditAttributes();
            $this->Cylinders->EditValue = HtmlEncode($this->Cylinders->CurrentValue);
            $this->Cylinders->PlaceHolder = RemoveHtml($this->Cylinders->caption());
            if (strval($this->Cylinders->EditValue) != "" && is_numeric($this->Cylinders->EditValue)) {
                $this->Cylinders->EditValue = $this->Cylinders->EditValue;
            }

            // Price
            $this->Price->setupEditAttributes();
            $this->Price->EditValue = HtmlEncode($this->Price->CurrentValue);
            $this->Price->PlaceHolder = RemoveHtml($this->Price->caption());
            if (strval($this->Price->EditValue) != "" && is_numeric($this->Price->EditValue)) {
                $this->Price->EditValue = FormatNumber($this->Price->EditValue, null);
            }

            // Picture
            $this->Picture->setupEditAttributes();
            if (!EmptyValue($this->Picture->Upload->DbValue)) {
                $this->Picture->ImageWidth = 200;
                $this->Picture->ImageHeight = 0;
                $this->Picture->ImageAlt = $this->Picture->alt();
                $this->Picture->ImageCssClass = "ew-image";
                $this->Picture->EditValue = $this->ID->CurrentValue;
                $this->Picture->IsBlobImage = IsImageFile(ContentExtension($this->Picture->Upload->DbValue));
            } else {
                $this->Picture->EditValue = "";
            }
            if (is_numeric($this->RowIndex)) {
                RenderUploadField($this->Picture, $this->RowIndex);
            }

            // Doors
            $this->Doors->setupEditAttributes();
            $this->Doors->EditValue = HtmlEncode($this->Doors->CurrentValue);
            $this->Doors->PlaceHolder = RemoveHtml($this->Doors->caption());
            if (strval($this->Doors->EditValue) != "" && is_numeric($this->Doors->EditValue)) {
                $this->Doors->EditValue = FormatNumber($this->Doors->EditValue, null);
            }

            // Torque
            $this->Torque->setupEditAttributes();
            if (!$this->Torque->Raw) {
                $this->Torque->CurrentValue = HtmlDecode($this->Torque->CurrentValue);
            }
            $this->Torque->EditValue = HtmlEncode($this->Torque->CurrentValue);
            $this->Torque->PlaceHolder = RemoveHtml($this->Torque->caption());

            // Add refer script

            // ID
            $this->ID->HrefValue = "";

            // Trademark
            $this->Trademark->HrefValue = "";

            // Model
            $this->Model->HrefValue = "";

            // HP
            $this->HP->HrefValue = "";

            // Cylinders
            $this->Cylinders->HrefValue = "";

            // Price
            $this->Price->HrefValue = "";

            // Picture
            if (!empty($this->Picture->Upload->DbValue)) {
                $this->Picture->HrefValue = GetFileUploadUrl($this->Picture, $this->ID->CurrentValue);
                $this->Picture->LinkAttrs["target"] = "_blank";
                if ($this->Picture->IsBlobImage && empty($this->Picture->LinkAttrs["target"])) {
                    $this->Picture->LinkAttrs["target"] = "_blank";
                }
                if ($this->isExport()) {
                    $this->Picture->HrefValue = FullUrl($this->Picture->HrefValue, "href");
                }
            } else {
                $this->Picture->HrefValue = "";
            }
            $this->Picture->ExportHrefValue = GetFileUploadUrl($this->Picture, $this->ID->CurrentValue);

            // Doors
            $this->Doors->HrefValue = "";

            // Torque
            $this->Torque->HrefValue = "";
        } elseif ($this->RowType == ROWTYPE_EDIT) {
            // ID
            $this->ID->setupEditAttributes();
            $this->ID->EditValue = $this->ID->CurrentValue;

            // Trademark
            $this->Trademark->setupEditAttributes();
            $curVal = trim(strval($this->Trademark->CurrentValue));
            if ($curVal != "") {
                $this->Trademark->ViewValue = $this->Trademark->lookupCacheOption($curVal);
            } else {
                $this->Trademark->ViewValue = $this->Trademark->Lookup !== null && is_array($this->Trademark->lookupOptions()) && count($this->Trademark->lookupOptions()) > 0 ? $curVal : null;
            }
            if ($this->Trademark->ViewValue !== null) { // Load from cache
                $this->Trademark->EditValue = array_values($this->Trademark->lookupOptions());
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = SearchFilter("`ID`", "=", $this->Trademark->CurrentValue, DATATYPE_NUMBER, "");
                }
                $sqlWrk = $this->Trademark->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCacheImpl($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->Trademark->EditValue = $arwrk;
            }
            $this->Trademark->PlaceHolder = RemoveHtml($this->Trademark->caption());

            // Model
            $this->Model->setupEditAttributes();
            $curVal = trim(strval($this->Model->CurrentValue));
            if ($curVal != "") {
                $this->Model->ViewValue = $this->Model->lookupCacheOption($curVal);
            } else {
                $this->Model->ViewValue = $this->Model->Lookup !== null && is_array($this->Model->lookupOptions()) && count($this->Model->lookupOptions()) > 0 ? $curVal : null;
            }
            if ($this->Model->ViewValue !== null) { // Load from cache
                $this->Model->EditValue = array_values($this->Model->lookupOptions());
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = SearchFilter("`ID`", "=", $this->Model->CurrentValue, DATATYPE_NUMBER, "");
                }
                $sqlWrk = $this->Model->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCacheImpl($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->Model->EditValue = $arwrk;
            }
            $this->Model->PlaceHolder = RemoveHtml($this->Model->caption());

            // HP
            $this->HP->setupEditAttributes();
            if (!$this->HP->Raw) {
                $this->HP->CurrentValue = HtmlDecode($this->HP->CurrentValue);
            }
            $this->HP->EditValue = HtmlEncode($this->HP->CurrentValue);
            $this->HP->PlaceHolder = RemoveHtml($this->HP->caption());

            // Cylinders
            $this->Cylinders->setupEditAttributes();
            $this->Cylinders->EditValue = HtmlEncode($this->Cylinders->CurrentValue);
            $this->Cylinders->PlaceHolder = RemoveHtml($this->Cylinders->caption());
            if (strval($this->Cylinders->EditValue) != "" && is_numeric($this->Cylinders->EditValue)) {
                $this->Cylinders->EditValue = $this->Cylinders->EditValue;
            }

            // Price
            $this->Price->setupEditAttributes();
            $this->Price->EditValue = HtmlEncode($this->Price->CurrentValue);
            $this->Price->PlaceHolder = RemoveHtml($this->Price->caption());
            if (strval($this->Price->EditValue) != "" && is_numeric($this->Price->EditValue)) {
                $this->Price->EditValue = FormatNumber($this->Price->EditValue, null);
            }

            // Picture
            $this->Picture->setupEditAttributes();
            if (!EmptyValue($this->Picture->Upload->DbValue)) {
                $this->Picture->ImageWidth = 200;
                $this->Picture->ImageHeight = 0;
                $this->Picture->ImageAlt = $this->Picture->alt();
                $this->Picture->ImageCssClass = "ew-image";
                $this->Picture->EditValue = $this->ID->CurrentValue;
                $this->Picture->IsBlobImage = IsImageFile(ContentExtension($this->Picture->Upload->DbValue));
            } else {
                $this->Picture->EditValue = "";
            }
            if (is_numeric($this->RowIndex)) {
                RenderUploadField($this->Picture, $this->RowIndex);
            }

            // Doors
            $this->Doors->setupEditAttributes();
            $this->Doors->EditValue = HtmlEncode($this->Doors->CurrentValue);
            $this->Doors->PlaceHolder = RemoveHtml($this->Doors->caption());
            if (strval($this->Doors->EditValue) != "" && is_numeric($this->Doors->EditValue)) {
                $this->Doors->EditValue = FormatNumber($this->Doors->EditValue, null);
            }

            // Torque
            $this->Torque->setupEditAttributes();
            if (!$this->Torque->Raw) {
                $this->Torque->CurrentValue = HtmlDecode($this->Torque->CurrentValue);
            }
            $this->Torque->EditValue = HtmlEncode($this->Torque->CurrentValue);
            $this->Torque->PlaceHolder = RemoveHtml($this->Torque->caption());

            // Edit refer script

            // ID
            $this->ID->HrefValue = "";

            // Trademark
            $this->Trademark->HrefValue = "";

            // Model
            $this->Model->HrefValue = "";

            // HP
            $this->HP->HrefValue = "";

            // Cylinders
            $this->Cylinders->HrefValue = "";

            // Price
            $this->Price->HrefValue = "";

            // Picture
            if (!empty($this->Picture->Upload->DbValue)) {
                $this->Picture->HrefValue = GetFileUploadUrl($this->Picture, $this->ID->CurrentValue);
                $this->Picture->LinkAttrs["target"] = "_blank";
                if ($this->Picture->IsBlobImage && empty($this->Picture->LinkAttrs["target"])) {
                    $this->Picture->LinkAttrs["target"] = "_blank";
                }
                if ($this->isExport()) {
                    $this->Picture->HrefValue = FullUrl($this->Picture->HrefValue, "href");
                }
            } else {
                $this->Picture->HrefValue = "";
            }
            $this->Picture->ExportHrefValue = GetFileUploadUrl($this->Picture, $this->ID->CurrentValue);

            // Doors
            $this->Doors->HrefValue = "";

            // Torque
            $this->Torque->HrefValue = "";
        } elseif ($this->RowType == ROWTYPE_SEARCH) {
            // ID
            $this->ID->setupEditAttributes();
            $this->ID->EditValue = HtmlEncode($this->ID->AdvancedSearch->SearchValue);
            $this->ID->PlaceHolder = RemoveHtml($this->ID->caption());

            // Trademark
            $this->Trademark->setupEditAttributes();
            $this->Trademark->EditValue = HtmlEncode($this->Trademark->AdvancedSearch->SearchValue);
            $this->Trademark->PlaceHolder = RemoveHtml($this->Trademark->caption());

            // Model
            $this->Model->setupEditAttributes();
            $this->Model->EditValue = HtmlEncode($this->Model->AdvancedSearch->SearchValue);
            $this->Model->PlaceHolder = RemoveHtml($this->Model->caption());

            // HP
            $this->HP->setupEditAttributes();
            if (!$this->HP->Raw) {
                $this->HP->AdvancedSearch->SearchValue = HtmlDecode($this->HP->AdvancedSearch->SearchValue);
            }
            $this->HP->EditValue = HtmlEncode($this->HP->AdvancedSearch->SearchValue);
            $this->HP->PlaceHolder = RemoveHtml($this->HP->caption());

            // Cylinders
            $this->Cylinders->setupEditAttributes();
            $this->Cylinders->EditValue = HtmlEncode($this->Cylinders->AdvancedSearch->SearchValue);
            $this->Cylinders->PlaceHolder = RemoveHtml($this->Cylinders->caption());

            // Price
            $this->Price->setupEditAttributes();
            $this->Price->EditValue = HtmlEncode($this->Price->AdvancedSearch->SearchValue);
            $this->Price->PlaceHolder = RemoveHtml($this->Price->caption());

            // Picture
            $this->Picture->setupEditAttributes();
            if (!EmptyValue($this->Picture->Upload->DbValue)) {
                $this->Picture->ImageWidth = 200;
                $this->Picture->ImageHeight = 0;
                $this->Picture->ImageAlt = $this->Picture->alt();
                $this->Picture->ImageCssClass = "ew-image";
                $this->Picture->EditValue = $this->ID->CurrentValue;
                $this->Picture->IsBlobImage = IsImageFile(ContentExtension($this->Picture->Upload->DbValue));
            } else {
                $this->Picture->EditValue = "";
            }

            // Doors
            $this->Doors->setupEditAttributes();
            $this->Doors->EditValue = HtmlEncode($this->Doors->AdvancedSearch->SearchValue);
            $this->Doors->PlaceHolder = RemoveHtml($this->Doors->caption());

            // Torque
            $this->Torque->setupEditAttributes();
            if (!$this->Torque->Raw) {
                $this->Torque->AdvancedSearch->SearchValue = HtmlDecode($this->Torque->AdvancedSearch->SearchValue);
            }
            $this->Torque->EditValue = HtmlEncode($this->Torque->AdvancedSearch->SearchValue);
            $this->Torque->PlaceHolder = RemoveHtml($this->Torque->caption());
        } elseif ($this->RowType == ROWTYPE_AGGREGATEINIT) { // Initialize aggregate row
                $this->Trademark->Count = 0; // Initialize count
        } elseif ($this->RowType == ROWTYPE_AGGREGATE) { // Aggregate row
            $this->Trademark->CurrentValue = $this->Trademark->Count;
            $this->Trademark->ViewValue = $this->Trademark->CurrentValue;
            $this->Trademark->HrefValue = ""; // Clear href value
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

    // Validate form
    protected function validateForm()
    {
        global $Language, $Security;

        // Check if validation required
        if (!Config("SERVER_VALIDATE")) {
            return true;
        }
        $validateForm = true;
        if ($this->ID->Visible && $this->ID->Required) {
            if (!$this->ID->IsDetailKey && EmptyValue($this->ID->FormValue)) {
                $this->ID->addErrorMessage(str_replace("%s", $this->ID->caption(), $this->ID->RequiredErrorMessage));
            }
        }
        if ($this->Trademark->Visible && $this->Trademark->Required) {
            if (!$this->Trademark->IsDetailKey && EmptyValue($this->Trademark->FormValue)) {
                $this->Trademark->addErrorMessage(str_replace("%s", $this->Trademark->caption(), $this->Trademark->RequiredErrorMessage));
            }
        }
        if ($this->Model->Visible && $this->Model->Required) {
            if (!$this->Model->IsDetailKey && EmptyValue($this->Model->FormValue)) {
                $this->Model->addErrorMessage(str_replace("%s", $this->Model->caption(), $this->Model->RequiredErrorMessage));
            }
        }
        if ($this->HP->Visible && $this->HP->Required) {
            if (!$this->HP->IsDetailKey && EmptyValue($this->HP->FormValue)) {
                $this->HP->addErrorMessage(str_replace("%s", $this->HP->caption(), $this->HP->RequiredErrorMessage));
            }
        }
        if ($this->Cylinders->Visible && $this->Cylinders->Required) {
            if (!$this->Cylinders->IsDetailKey && EmptyValue($this->Cylinders->FormValue)) {
                $this->Cylinders->addErrorMessage(str_replace("%s", $this->Cylinders->caption(), $this->Cylinders->RequiredErrorMessage));
            }
        }
        if (!CheckInteger($this->Cylinders->FormValue)) {
            $this->Cylinders->addErrorMessage($this->Cylinders->getErrorMessage(false));
        }
        if ($this->Price->Visible && $this->Price->Required) {
            if (!$this->Price->IsDetailKey && EmptyValue($this->Price->FormValue)) {
                $this->Price->addErrorMessage(str_replace("%s", $this->Price->caption(), $this->Price->RequiredErrorMessage));
            }
        }
        if (!CheckNumber($this->Price->FormValue)) {
            $this->Price->addErrorMessage($this->Price->getErrorMessage(false));
        }
        if ($this->Picture->Visible && $this->Picture->Required) {
            if ($this->Picture->Upload->FileName == "" && !$this->Picture->Upload->KeepFile) {
                $this->Picture->addErrorMessage(str_replace("%s", $this->Picture->caption(), $this->Picture->RequiredErrorMessage));
            }
        }
        if ($this->Doors->Visible && $this->Doors->Required) {
            if (!$this->Doors->IsDetailKey && EmptyValue($this->Doors->FormValue)) {
                $this->Doors->addErrorMessage(str_replace("%s", $this->Doors->caption(), $this->Doors->RequiredErrorMessage));
            }
        }
        if (!CheckInteger($this->Doors->FormValue)) {
            $this->Doors->addErrorMessage($this->Doors->getErrorMessage(false));
        }
        if ($this->Torque->Visible && $this->Torque->Required) {
            if (!$this->Torque->IsDetailKey && EmptyValue($this->Torque->FormValue)) {
                $this->Torque->addErrorMessage(str_replace("%s", $this->Torque->caption(), $this->Torque->RequiredErrorMessage));
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
            $thisKey .= $row['ID'];

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

        // Trademark
        $this->Trademark->setDbValueDef($rsnew, $this->Trademark->CurrentValue, $this->Trademark->ReadOnly);

        // Model
        $this->Model->setDbValueDef($rsnew, $this->Model->CurrentValue, $this->Model->ReadOnly);

        // HP
        $this->HP->setDbValueDef($rsnew, $this->HP->CurrentValue, $this->HP->ReadOnly);

        // Cylinders
        $this->Cylinders->setDbValueDef($rsnew, $this->Cylinders->CurrentValue, $this->Cylinders->ReadOnly);

        // Price
        $this->Price->setDbValueDef($rsnew, $this->Price->CurrentValue, $this->Price->ReadOnly);

        // Picture
        if ($this->Picture->Visible && !$this->Picture->ReadOnly && !$this->Picture->Upload->KeepFile) {
            if ($this->Picture->Upload->Value === null) {
                $rsnew['Picture'] = null;
            } else {
                $this->Picture->Upload->Resize(100, 0);
                $this->Picture->ImageWidth = 100; // Resize width
                $this->Picture->ImageHeight = 0; // Resize height
                $rsnew['Picture'] = $this->Picture->Upload->Value;
            }
        }

        // Doors
        $this->Doors->setDbValueDef($rsnew, $this->Doors->CurrentValue, $this->Doors->ReadOnly);

        // Torque
        $this->Torque->setDbValueDef($rsnew, $this->Torque->CurrentValue, $this->Torque->ReadOnly);

        // Update current values
        $this->setCurrentValues($rsnew);

        // Check hash value
        $rowHasConflict = (!IsApi() && $this->getRowHash($rsold) != $this->HashValue);

        // Call Row Update Conflict event
        if ($rowHasConflict) {
            $rowHasConflict = $this->rowUpdateConflict($rsold, $rsnew);
        }
        if ($rowHasConflict) {
            $this->setFailureMessage($Language->phrase("RecordChangedByOtherUser"));
            $this->UpdateConflict = "U";
            return false; // Update Failed
        }

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
        $hash .= GetFieldHash($row['Trademark']); // Trademark
        $hash .= GetFieldHash($row['Model']); // Model
        $hash .= GetFieldHash($row['HP']); // HP
        $hash .= GetFieldHash($row['Cylinders']); // Cylinders
        $hash .= GetFieldHash($row['Price']); // Price
        $hash .= GetFieldHash($row['Picture']); // Picture
        $hash .= GetFieldHash($row['Doors']); // Doors
        $hash .= GetFieldHash($row['Torque']); // Torque
        return md5($hash);
    }

    // Add record
    protected function addRow($rsold = null)
    {
        global $Language, $Security;

        // Set new row
        $rsnew = [];

        // Trademark
        $this->Trademark->setDbValueDef($rsnew, $this->Trademark->CurrentValue, strval($this->Trademark->CurrentValue) == "");

        // Model
        $this->Model->setDbValueDef($rsnew, $this->Model->CurrentValue, false);

        // HP
        $this->HP->setDbValueDef($rsnew, $this->HP->CurrentValue, false);

        // Cylinders
        $this->Cylinders->setDbValueDef($rsnew, $this->Cylinders->CurrentValue, false);

        // Price
        $this->Price->setDbValueDef($rsnew, $this->Price->CurrentValue, false);

        // Picture
        if ($this->Picture->Visible && !$this->Picture->Upload->KeepFile) {
            if ($this->Picture->Upload->Value === null) {
                $rsnew['Picture'] = null;
            } else {
                $this->Picture->Upload->Resize(100, 0);
                $this->Picture->ImageWidth = 100; // Resize width
                $this->Picture->ImageHeight = 0; // Resize height
                $rsnew['Picture'] = $this->Picture->Upload->Value;
            }
        }

        // Doors
        $this->Doors->setDbValueDef($rsnew, $this->Doors->CurrentValue, false);

        // Torque
        $this->Torque->setDbValueDef($rsnew, $this->Torque->CurrentValue, false);

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

    // Load advanced search
    public function loadAdvancedSearch()
    {
        $this->ID->AdvancedSearch->load();
        $this->Trademark->AdvancedSearch->load();
        $this->Model->AdvancedSearch->load();
        $this->HP->AdvancedSearch->load();
        $this->Cylinders->AdvancedSearch->load();
        $this->Description->AdvancedSearch->load();
        $this->Price->AdvancedSearch->load();
        $this->Doors->AdvancedSearch->load();
        $this->Torque->AdvancedSearch->load();
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
                return "<button type=\"button\" class=\"btn btn-default ew-export-link ew-excel\" title=\"" . HtmlEncode($Language->phrase("ExportToExcel", true)) . "\" data-caption=\"" . HtmlEncode($Language->phrase("ExportToExcel", true)) . "\" form=\"fcarslist\" data-url=\"$exportUrl\" data-ew-action=\"export\" data-export=\"excel\" data-custom=\"true\" data-export-selected=\"false\">" . $Language->phrase("ExportToExcel") . "</button>";
            } else {
                return "<a href=\"$exportUrl\" class=\"btn btn-default ew-export-link ew-excel\" title=\"" . HtmlEncode($Language->phrase("ExportToExcel", true)) . "\" data-caption=\"" . HtmlEncode($Language->phrase("ExportToExcel", true)) . "\">" . $Language->phrase("ExportToExcel") . "</a>";
            }
        } elseif (SameText($type, "word")) {
            if ($custom) {
                return "<button type=\"button\" class=\"btn btn-default ew-export-link ew-word\" title=\"" . HtmlEncode($Language->phrase("ExportToWord", true)) . "\" data-caption=\"" . HtmlEncode($Language->phrase("ExportToWord", true)) . "\" form=\"fcarslist\" data-url=\"$exportUrl\" data-ew-action=\"export\" data-export=\"word\" data-custom=\"true\" data-export-selected=\"false\">" . $Language->phrase("ExportToWord") . "</button>";
            } else {
                return "<a href=\"$exportUrl\" class=\"btn btn-default ew-export-link ew-word\" title=\"" . HtmlEncode($Language->phrase("ExportToWord", true)) . "\" data-caption=\"" . HtmlEncode($Language->phrase("ExportToWord", true)) . "\">" . $Language->phrase("ExportToWord") . "</a>";
            }
        } elseif (SameText($type, "pdf")) {
            if ($custom) {
                return "<button type=\"button\" class=\"btn btn-default ew-export-link ew-pdf\" title=\"" . HtmlEncode($Language->phrase("ExportToPdf", true)) . "\" data-caption=\"" . HtmlEncode($Language->phrase("ExportToPdf", true)) . "\" form=\"fcarslist\" data-url=\"$exportUrl\" data-ew-action=\"export\" data-export=\"pdf\" data-custom=\"true\" data-export-selected=\"false\">" . $Language->phrase("ExportToPdf") . "</button>";
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
            return '<button type="button" class="btn btn-default ew-export-link ew-email" title="' . $Language->phrase("ExportToEmail", true) . '" data-caption="' . $Language->phrase("ExportToEmail", true) . '" form="fcarslist" data-ew-action="email" data-custom="false" data-hdr="' . $Language->phrase("ExportToEmail", true) . '" data-exported-selected="false"' . $url . '>' . $Language->phrase("ExportToEmail") . '</button>';
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
        $item->Body = "<a class=\"btn btn-default ew-search-toggle" . $searchToggleClass . "\" role=\"button\" title=\"" . $Language->phrase("SearchPanel") . "\" data-caption=\"" . $Language->phrase("SearchPanel") . "\" data-ew-action=\"search-toggle\" data-form=\"fcarssrch\" aria-pressed=\"" . ($searchToggleClass == " active" ? "true" : "false") . "\">" . $Language->phrase("SearchLink") . "</a>";
        $item->Visible = true;

        // Show all button
        $item = &$this->SearchOptions->add("showall");
        if ($this->UseCustomTemplate || !$this->UseAjaxActions) {
            $item->Body = "<a class=\"btn btn-default ew-show-all\" role=\"button\" title=\"" . $Language->phrase("ShowAll") . "\" data-caption=\"" . $Language->phrase("ShowAll") . "\" href=\"" . $pageUrl . "cmd=reset\">" . $Language->phrase("ShowAllBtn") . "</a>";
        } else {
            $item->Body = "<a class=\"btn btn-default ew-show-all\" role=\"button\" title=\"" . $Language->phrase("ShowAll") . "\" data-caption=\"" . $Language->phrase("ShowAll") . "\" data-ew-action=\"refresh\" data-url=\"" . $pageUrl . "cmd=reset\">" . $Language->phrase("ShowAllBtn") . "</a>";
        }
        $item->Visible = ($this->SearchWhere != $this->DefaultSearchWhere && $this->SearchWhere != "0=101");

        // Advanced search button
        $item = &$this->SearchOptions->add("advancedsearch");
        if ($this->ModalSearch && !IsMobile()) {
            $item->Body = "<a class=\"btn btn-default ew-advanced-search\" title=\"" . $Language->phrase("AdvancedSearch", true) . "\" data-table=\"cars\" data-caption=\"" . $Language->phrase("AdvancedSearch", true) . "\" data-ew-action=\"modal\" data-url=\"carssearch\" data-btn=\"SearchBtn\">" . $Language->phrase("AdvancedSearch", false) . "</a>";
        } else {
            $item->Body = "<a class=\"btn btn-default ew-advanced-search\" title=\"" . $Language->phrase("AdvancedSearch", true) . "\" data-caption=\"" . $Language->phrase("AdvancedSearch", true) . "\" href=\"carssearch\">" . $Language->phrase("AdvancedSearch", false) . "</a>";
        }
        $item->Visible = true;

        // Query builder button
        $item = &$this->SearchOptions->add("querybuilder");
        if ($this->ModalSearch && !IsMobile()) {
            $item->Body = "<a class=\"btn btn-default ew-query-builder\" title=\"" . $Language->phrase("QueryBuilder", true) . "\" data-table=\"cars\" data-caption=\"" . $Language->phrase("QueryBuilder", true) . "\" data-ew-action=\"modal\" data-url=\"carsquery\" data-btn=\"SearchBtn\">" . $Language->phrase("QueryBuilder", false) . "</a>";
        } else {
            $item->Body = "<a class=\"btn btn-default ew-query-builder\" title=\"" . $Language->phrase("QueryBuilder", true) . "\" data-caption=\"" . $Language->phrase("QueryBuilder", true) . "\" href=\"carsquery\">" . $Language->phrase("QueryBuilder", false) . "</a>";
        }
        $item->Visible = true;

        // Search highlight button
        $item = &$this->SearchOptions->add("searchhighlight");
        $item->Body = "<a class=\"btn btn-default ew-highlight active\" role=\"button\" title=\"" . $Language->phrase("Highlight") . "\" data-caption=\"" . $Language->phrase("Highlight") . "\" data-ew-action=\"highlight\" data-form=\"fcarssrch\" data-name=\"" . $this->highlightName() . "\">" . $Language->phrase("HighlightBtn") . "</a>";
        $item->Visible = ($this->SearchWhere != "" && $this->TotalRecords > 0);

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
                case "x_Trademark":
                    break;
                case "x_Model":
                    break;
                case "x_TransmissAutomatic":
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
