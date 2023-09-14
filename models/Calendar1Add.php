<?php

namespace PHPMaker2023\demo2023;

use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Page class
 */
class Calendar1Add extends Calendar1
{
    use MessagesTrait;

    // Page ID
    public $PageID = "add";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Page object name
    public $PageObjName = "Calendar1Add";

    // View file path
    public $View = null;

    // Title
    public $Title = null; // Title for <title> tag

    // Rendering View
    public $RenderingView = false;

    // CSS class/style
    public $ReportContainerClass = "ew-grid";
    public $CurrentPageName = "calendar1add";

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

    // Constructor
    public function __construct()
    {
        parent::__construct();
        global $Language, $DashboardReport, $DebugTimer, $UserTable;
        $this->TableVar = 'Calendar1';
        $this->TableName = 'Calendar1';

        // Table CSS class
        $this->TableClass = "table table-striped table-bordered table-hover table-sm ew-desktop-table ew-add-table";

        // Initialize
        $GLOBALS["Page"] = &$this;

        // Language object
        $Language = Container("language");

        // Table object (Calendar1)
        if (!isset($GLOBALS["Calendar1"]) || get_class($GLOBALS["Calendar1"]) == PROJECT_NAMESPACE . "Calendar1") {
            $GLOBALS["Calendar1"] = &$this;
        }

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'Calendar1');
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

        // Close connection if not in dashboard
        if (!$DashboardReport) {
            CloseConnections();
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

            // Handle modal response (Assume return to modal for simplicity)
            if ($this->IsModal) { // Show as modal
                $result = ["url" => GetUrl($url), "modal" => "1"];
                $pageName = GetPageName($url);
                if ($pageName != $this->getListUrl()) { // Not List page => View page
                    $result["caption"] = $this->getModalCaption($pageName);
                    $result["view"] = $pageName == "calendar1view"; // If View page, no primary button
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
            $key .= @$ar['Id'];
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
            $this->Id->Visible = false;
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
        if ($lookup->LinkTable == $this->TableVar || property_exists($this, "ReportSourceTable") && $lookup->LinkTable == $this->ReportSourceTable) {
            $lookup->RenderViewFunc = "renderLookup"; // Set up view renderer
        }
        $lookup->RenderEditFunc = ""; // Set up edit renderer

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
    public $FormClassName = "ew-form ew-add-form";
    public $IsModal = false;
    public $IsMobileOrModal = false;
    public $DbMasterFilter = "";
    public $DbDetailFilter = "";
    public $StartRecord;
    public $Priv = 0;
    public $CopyRecord;

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

        // Global Page Loading event (in userfn*.php)
        Page_Loading();

        // Page Load event
        if (method_exists($this, "pageLoad")) {
            $this->pageLoad();
        }

        // Load default values for add
        $this->loadDefaultValues();

        // Check modal
        if ($this->IsModal) {
            $SkipHeaderFooter = true;
        }
        $this->IsMobileOrModal = IsMobile() || $this->IsModal;
        $postBack = false;

        // Set up current action
        if (IsApi()) {
            $this->CurrentAction = "insert"; // Add record directly
            $postBack = true;
        } elseif (Post("action", "") !== "") {
            $this->CurrentAction = Post("action"); // Get form action
            $this->setKey(Post($this->OldKeyName));
            $postBack = true;
        } else {
            // Load key values from QueryString
            if (($keyValue = Get("Id") ?? Route("Id")) !== null) {
                $this->Id->setQueryStringValue($keyValue);
            }
            $this->OldKey = $this->getKey(true); // Get from CurrentValue
            $this->CopyRecord = !EmptyValue($this->OldKey);
            if ($this->CopyRecord) {
                $this->CurrentAction = "copy"; // Copy record
                $this->setKey($this->OldKey); // Set up record key
            } else {
                $this->CurrentAction = "show"; // Display blank record
            }
        }

        // Load old record or default values
        $rsold = $this->loadOldRecord();

        // Load form values
        if ($postBack) {
            $this->loadFormValues(); // Load form values
        }

        // Validate form if post back
        if ($postBack) {
            if (!$this->validateForm()) {
                $this->EventCancelled = true; // Event cancelled
                $this->restoreFormValues(); // Restore form values
                if (IsApi()) {
                    $this->terminate();
                    return;
                } else {
                    $this->CurrentAction = "show"; // Form error, reset action
                }
            }
        }

        // Perform current action
        switch ($this->CurrentAction) {
            case "copy": // Copy an existing record
                if (!$rsold) { // Record not loaded
                    if ($this->getFailureMessage() == "") {
                        $this->setFailureMessage($Language->phrase("NoRecord")); // No record found
                    }
                    $this->terminate("calendar1list"); // No matching record, return to list
                    return;
                }
                break;
            case "insert": // Add new record
                $this->SendEmail = true; // Send email on add success
                if ($this->addRow($rsold)) { // Add successful
                    if ($this->getSuccessMessage() == "" && Post("addopt") != "1") { // Skip success message for addopt (done in JavaScript)
                        $this->setSuccessMessage($Language->phrase("AddSuccess")); // Set up success message
                    }
                    $returnUrl = $this->getReturnUrl();
                    if (GetPageName($returnUrl) == "calendar1list") {
                        $returnUrl = $this->addMasterUrl($returnUrl); // List page, return to List page with correct master key if necessary
                    } elseif (GetPageName($returnUrl) == "calendar1view") {
                        $returnUrl = $this->getViewUrl(); // View page, return to View page with keyurl directly
                    }

                    // Handle UseAjaxActions
                    if ($this->IsModal && $this->UseAjaxActions) {
                        $this->IsModal = false;
                        if (GetPageName($returnUrl) != "calendar1list") {
                            Container("flash")->addMessage("Return-Url", $returnUrl); // Save return URL
                            $returnUrl = "calendar1list"; // Return list page content
                        }
                    }
                    if (IsJsonResponse()) { // Return to caller
                        $this->terminate(true);
                        return;
                    } else {
                        $this->terminate($returnUrl);
                        return;
                    }
                } elseif (IsApi()) { // API request, return
                    $this->terminate();
                    return;
                } elseif ($this->IsModal && $this->UseAjaxActions) { // Return JSON error message
                    WriteJson([ "success" => false, "validation" => $this->getValidationErrors(), "error" => $this->getFailureMessage() ]);
                    $this->clearFailureMessage();
                    $this->terminate();
                    return;
                } else {
                    $this->EventCancelled = true; // Event cancelled
                    $this->restoreFormValues(); // Add failed, restore form values
                }
        }

        // Set up Breadcrumb
        $this->setupBreadcrumb();

        // Render row based on row type
        $this->RowType = ROWTYPE_ADD; // Render add type

        // Render row
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

    // Get upload files
    protected function getUploadFiles()
    {
        global $CurrentForm, $Language;
    }

    // Load default values
    protected function loadDefaultValues()
    {
        $this->Start->DefaultValue = Param("Start");
        if ($this->Start->DefaultValue !== null) {
            $this->Start->OldValue = $this->Start->DefaultValue;
        }
        $this->End->DefaultValue = Param("End");
        if ($this->End->DefaultValue !== null) {
            $this->End->OldValue = $this->End->DefaultValue;
        }
        $this->AllDay->DefaultValue = Param("AllDay");
        if ($this->AllDay->DefaultValue !== null) {
            $this->AllDay->OldValue = $this->AllDay->DefaultValue;
        }
    }

    // Load form values
    protected function loadFormValues()
    {
        // Load from form
        global $CurrentForm;
        $validate = !Config("SERVER_VALIDATE");

        // Check field name 'Title' first before field var 'x__Title'
        $val = $CurrentForm->hasValue("Title") ? $CurrentForm->getValue("Title") : $CurrentForm->getValue("x__Title");
        if (!$this->_Title->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->_Title->Visible = false; // Disable update for API request
            } else {
                $this->_Title->setFormValue($val);
            }
        }

        // Check field name 'Start' first before field var 'x_Start'
        $val = $CurrentForm->hasValue("Start") ? $CurrentForm->getValue("Start") : $CurrentForm->getValue("x_Start");
        if (!$this->Start->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->Start->Visible = false; // Disable update for API request
            } else {
                $this->Start->setFormValue($val, true, $validate);
            }
            $this->Start->CurrentValue = UnFormatDateTime($this->Start->CurrentValue, $this->Start->formatPattern());
        }

        // Check field name 'End' first before field var 'x_End'
        $val = $CurrentForm->hasValue("End") ? $CurrentForm->getValue("End") : $CurrentForm->getValue("x_End");
        if (!$this->End->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->End->Visible = false; // Disable update for API request
            } else {
                $this->End->setFormValue($val, true, $validate);
            }
            $this->End->CurrentValue = UnFormatDateTime($this->End->CurrentValue, $this->End->formatPattern());
        }

        // Check field name 'AllDay' first before field var 'x_AllDay'
        $val = $CurrentForm->hasValue("AllDay") ? $CurrentForm->getValue("AllDay") : $CurrentForm->getValue("x_AllDay");
        if (!$this->AllDay->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->AllDay->Visible = false; // Disable update for API request
            } else {
                $this->AllDay->setFormValue($val);
            }
        }

        // Check field name 'Description' first before field var 'x_Description'
        $val = $CurrentForm->hasValue("Description") ? $CurrentForm->getValue("Description") : $CurrentForm->getValue("x_Description");
        if (!$this->Description->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->Description->Visible = false; // Disable update for API request
            } else {
                $this->Description->setFormValue($val);
            }
        }

        // Check field name 'GroupId' first before field var 'x_GroupId'
        $val = $CurrentForm->hasValue("GroupId") ? $CurrentForm->getValue("GroupId") : $CurrentForm->getValue("x_GroupId");
        if (!$this->GroupId->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->GroupId->Visible = false; // Disable update for API request
            } else {
                $this->GroupId->setFormValue($val);
            }
        }

        // Check field name 'Url' first before field var 'x_Url'
        $val = $CurrentForm->hasValue("Url") ? $CurrentForm->getValue("Url") : $CurrentForm->getValue("x_Url");
        if (!$this->Url->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->Url->Visible = false; // Disable update for API request
            } else {
                $this->Url->setFormValue($val);
            }
        }

        // Check field name 'ClassNames' first before field var 'x_ClassNames'
        $val = $CurrentForm->hasValue("ClassNames") ? $CurrentForm->getValue("ClassNames") : $CurrentForm->getValue("x_ClassNames");
        if (!$this->ClassNames->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->ClassNames->Visible = false; // Disable update for API request
            } else {
                $this->ClassNames->setFormValue($val);
            }
        }

        // Check field name 'Display' first before field var 'x_Display'
        $val = $CurrentForm->hasValue("Display") ? $CurrentForm->getValue("Display") : $CurrentForm->getValue("x_Display");
        if (!$this->Display->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->Display->Visible = false; // Disable update for API request
            } else {
                $this->Display->setFormValue($val);
            }
        }

        // Check field name 'BackgroundColor' first before field var 'x_BackgroundColor'
        $val = $CurrentForm->hasValue("BackgroundColor") ? $CurrentForm->getValue("BackgroundColor") : $CurrentForm->getValue("x_BackgroundColor");
        if (!$this->BackgroundColor->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->BackgroundColor->Visible = false; // Disable update for API request
            } else {
                $this->BackgroundColor->setFormValue($val);
            }
        }

        // Check field name 'Id' first before field var 'x_Id'
        $val = $CurrentForm->hasValue("Id") ? $CurrentForm->getValue("Id") : $CurrentForm->getValue("x_Id");
    }

    // Restore form values
    public function restoreFormValues()
    {
        global $CurrentForm;
        $this->_Title->CurrentValue = $this->_Title->FormValue;
        $this->Start->CurrentValue = $this->Start->FormValue;
        $this->Start->CurrentValue = UnFormatDateTime($this->Start->CurrentValue, $this->Start->formatPattern());
        $this->End->CurrentValue = $this->End->FormValue;
        $this->End->CurrentValue = UnFormatDateTime($this->End->CurrentValue, $this->End->formatPattern());
        $this->AllDay->CurrentValue = $this->AllDay->FormValue;
        $this->Description->CurrentValue = $this->Description->FormValue;
        $this->GroupId->CurrentValue = $this->GroupId->FormValue;
        $this->Url->CurrentValue = $this->Url->FormValue;
        $this->ClassNames->CurrentValue = $this->ClassNames->FormValue;
        $this->Display->CurrentValue = $this->Display->FormValue;
        $this->BackgroundColor->CurrentValue = $this->BackgroundColor->FormValue;
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
        $this->Id->setDbValue($row['Id']);
        $this->_Title->setDbValue($row['Title']);
        $this->Start->setDbValue($row['Start']);
        $this->End->setDbValue($row['End']);
        $this->AllDay->setDbValue($row['AllDay']);
        $this->Description->setDbValue($row['Description']);
        $this->GroupId->setDbValue($row['GroupId']);
        $this->Url->setDbValue($row['Url']);
        $this->ClassNames->setDbValue($row['ClassNames']);
        $this->Display->setDbValue($row['Display']);
        $this->BackgroundColor->setDbValue($row['BackgroundColor']);
    }

    // Return a row with default values
    protected function newRow()
    {
        $row = [];
        $row['Id'] = $this->Id->DefaultValue;
        $row['Title'] = $this->_Title->DefaultValue;
        $row['Start'] = $this->Start->DefaultValue;
        $row['End'] = $this->End->DefaultValue;
        $row['AllDay'] = $this->AllDay->DefaultValue;
        $row['Description'] = $this->Description->DefaultValue;
        $row['GroupId'] = $this->GroupId->DefaultValue;
        $row['Url'] = $this->Url->DefaultValue;
        $row['ClassNames'] = $this->ClassNames->DefaultValue;
        $row['Display'] = $this->Display->DefaultValue;
        $row['BackgroundColor'] = $this->BackgroundColor->DefaultValue;
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

        // Call Row_Rendering event
        $this->rowRendering();

        // Common render codes for all row types

        // Id
        $this->Id->RowCssClass = "row";

        // Title
        $this->_Title->RowCssClass = "row";

        // Start
        $this->Start->RowCssClass = "row";

        // End
        $this->End->RowCssClass = "row";

        // AllDay
        $this->AllDay->RowCssClass = "row";

        // Description
        $this->Description->RowCssClass = "row";

        // GroupId
        $this->GroupId->RowCssClass = "row";

        // Url
        $this->Url->RowCssClass = "row";

        // ClassNames
        $this->ClassNames->RowCssClass = "row";

        // Display
        $this->Display->RowCssClass = "row";

        // BackgroundColor
        $this->BackgroundColor->RowCssClass = "row";

        // View row
        if ($this->RowType == ROWTYPE_VIEW) {
            // Id
            $this->Id->ViewValue = $this->Id->CurrentValue;

            // Title
            $this->_Title->ViewValue = $this->_Title->CurrentValue;

            // Start
            $this->Start->ViewValue = $this->Start->CurrentValue;
            $this->Start->ViewValue = FormatDateTime($this->Start->ViewValue, $this->Start->formatPattern());

            // End
            $this->End->ViewValue = $this->End->CurrentValue;
            $this->End->ViewValue = FormatDateTime($this->End->ViewValue, $this->End->formatPattern());

            // AllDay
            if (ConvertToBool($this->AllDay->CurrentValue)) {
                $this->AllDay->ViewValue = $this->AllDay->tagCaption(1) != "" ? $this->AllDay->tagCaption(1) : "Yes";
            } else {
                $this->AllDay->ViewValue = $this->AllDay->tagCaption(2) != "" ? $this->AllDay->tagCaption(2) : "No";
            }

            // Description
            $this->Description->ViewValue = $this->Description->CurrentValue;

            // GroupId
            $this->GroupId->ViewValue = $this->GroupId->CurrentValue;

            // Url
            $this->Url->ViewValue = $this->Url->CurrentValue;

            // ClassNames
            $this->ClassNames->ViewValue = $this->ClassNames->CurrentValue;

            // Display
            if (strval($this->Display->CurrentValue) != "") {
                $this->Display->ViewValue = $this->Display->optionCaption($this->Display->CurrentValue);
            } else {
                $this->Display->ViewValue = null;
            }

            // BackgroundColor
            $this->BackgroundColor->ViewValue = $this->BackgroundColor->CurrentValue;

            // Title
            $this->_Title->HrefValue = "";

            // Start
            $this->Start->HrefValue = "";

            // End
            $this->End->HrefValue = "";

            // AllDay
            $this->AllDay->HrefValue = "";

            // Description
            $this->Description->HrefValue = "";

            // GroupId
            $this->GroupId->HrefValue = "";

            // Url
            $this->Url->HrefValue = "";

            // ClassNames
            $this->ClassNames->HrefValue = "";

            // Display
            $this->Display->HrefValue = "";

            // BackgroundColor
            $this->BackgroundColor->HrefValue = "";
        } elseif ($this->RowType == ROWTYPE_ADD) {
            // Title
            $this->_Title->setupEditAttributes();
            if (!$this->_Title->Raw) {
                $this->_Title->CurrentValue = HtmlDecode($this->_Title->CurrentValue);
            }
            $this->_Title->EditValue = HtmlEncode($this->_Title->CurrentValue);
            $this->_Title->PlaceHolder = RemoveHtml($this->_Title->caption());

            // Start
            $this->Start->setupEditAttributes();
            $this->Start->EditValue = HtmlEncode(FormatDateTime($this->Start->CurrentValue, $this->Start->formatPattern()));
            $this->Start->PlaceHolder = RemoveHtml($this->Start->caption());

            // End
            $this->End->setupEditAttributes();
            $this->End->EditValue = HtmlEncode(FormatDateTime($this->End->CurrentValue, $this->End->formatPattern()));
            $this->End->PlaceHolder = RemoveHtml($this->End->caption());

            // AllDay
            $this->AllDay->EditValue = $this->AllDay->options(false);
            $this->AllDay->PlaceHolder = RemoveHtml($this->AllDay->caption());

            // Description
            $this->Description->setupEditAttributes();
            $this->Description->EditValue = HtmlEncode($this->Description->CurrentValue);
            $this->Description->PlaceHolder = RemoveHtml($this->Description->caption());

            // GroupId
            $this->GroupId->setupEditAttributes();
            if (!$this->GroupId->Raw) {
                $this->GroupId->CurrentValue = HtmlDecode($this->GroupId->CurrentValue);
            }
            $this->GroupId->EditValue = HtmlEncode($this->GroupId->CurrentValue);
            $this->GroupId->PlaceHolder = RemoveHtml($this->GroupId->caption());

            // Url
            $this->Url->setupEditAttributes();
            if (!$this->Url->Raw) {
                $this->Url->CurrentValue = HtmlDecode($this->Url->CurrentValue);
            }
            $this->Url->EditValue = HtmlEncode($this->Url->CurrentValue);
            $this->Url->PlaceHolder = RemoveHtml($this->Url->caption());

            // ClassNames
            $this->ClassNames->setupEditAttributes();
            if (!$this->ClassNames->Raw) {
                $this->ClassNames->CurrentValue = HtmlDecode($this->ClassNames->CurrentValue);
            }
            $this->ClassNames->EditValue = HtmlEncode($this->ClassNames->CurrentValue);
            $this->ClassNames->PlaceHolder = RemoveHtml($this->ClassNames->caption());

            // Display
            $this->Display->setupEditAttributes();
            $this->Display->EditValue = $this->Display->options(true);
            $this->Display->PlaceHolder = RemoveHtml($this->Display->caption());

            // BackgroundColor
            $this->BackgroundColor->setupEditAttributes();
            if (!$this->BackgroundColor->Raw) {
                $this->BackgroundColor->CurrentValue = HtmlDecode($this->BackgroundColor->CurrentValue);
            }
            $this->BackgroundColor->EditValue = HtmlEncode($this->BackgroundColor->CurrentValue);
            $this->BackgroundColor->PlaceHolder = RemoveHtml($this->BackgroundColor->caption());

            // Add refer script

            // Title
            $this->_Title->HrefValue = "";

            // Start
            $this->Start->HrefValue = "";

            // End
            $this->End->HrefValue = "";

            // AllDay
            $this->AllDay->HrefValue = "";

            // Description
            $this->Description->HrefValue = "";

            // GroupId
            $this->GroupId->HrefValue = "";

            // Url
            $this->Url->HrefValue = "";

            // ClassNames
            $this->ClassNames->HrefValue = "";

            // Display
            $this->Display->HrefValue = "";

            // BackgroundColor
            $this->BackgroundColor->HrefValue = "";
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
        if ($this->_Title->Visible && $this->_Title->Required) {
            if (!$this->_Title->IsDetailKey && EmptyValue($this->_Title->FormValue)) {
                $this->_Title->addErrorMessage(str_replace("%s", $this->_Title->caption(), $this->_Title->RequiredErrorMessage));
            }
        }
        if ($this->Start->Visible && $this->Start->Required) {
            if (!$this->Start->IsDetailKey && EmptyValue($this->Start->FormValue)) {
                $this->Start->addErrorMessage(str_replace("%s", $this->Start->caption(), $this->Start->RequiredErrorMessage));
            }
        }
        if (!CheckDate($this->Start->FormValue, $this->Start->formatPattern())) {
            $this->Start->addErrorMessage($this->Start->getErrorMessage(false));
        }
        if ($this->End->Visible && $this->End->Required) {
            if (!$this->End->IsDetailKey && EmptyValue($this->End->FormValue)) {
                $this->End->addErrorMessage(str_replace("%s", $this->End->caption(), $this->End->RequiredErrorMessage));
            }
        }
        if (!CheckDate($this->End->FormValue, $this->End->formatPattern())) {
            $this->End->addErrorMessage($this->End->getErrorMessage(false));
        }
        if ($this->AllDay->Visible && $this->AllDay->Required) {
            if ($this->AllDay->FormValue == "") {
                $this->AllDay->addErrorMessage(str_replace("%s", $this->AllDay->caption(), $this->AllDay->RequiredErrorMessage));
            }
        }
        if ($this->Description->Visible && $this->Description->Required) {
            if (!$this->Description->IsDetailKey && EmptyValue($this->Description->FormValue)) {
                $this->Description->addErrorMessage(str_replace("%s", $this->Description->caption(), $this->Description->RequiredErrorMessage));
            }
        }
        if ($this->GroupId->Visible && $this->GroupId->Required) {
            if (!$this->GroupId->IsDetailKey && EmptyValue($this->GroupId->FormValue)) {
                $this->GroupId->addErrorMessage(str_replace("%s", $this->GroupId->caption(), $this->GroupId->RequiredErrorMessage));
            }
        }
        if ($this->Url->Visible && $this->Url->Required) {
            if (!$this->Url->IsDetailKey && EmptyValue($this->Url->FormValue)) {
                $this->Url->addErrorMessage(str_replace("%s", $this->Url->caption(), $this->Url->RequiredErrorMessage));
            }
        }
        if ($this->ClassNames->Visible && $this->ClassNames->Required) {
            if (!$this->ClassNames->IsDetailKey && EmptyValue($this->ClassNames->FormValue)) {
                $this->ClassNames->addErrorMessage(str_replace("%s", $this->ClassNames->caption(), $this->ClassNames->RequiredErrorMessage));
            }
        }
        if ($this->Display->Visible && $this->Display->Required) {
            if (!$this->Display->IsDetailKey && EmptyValue($this->Display->FormValue)) {
                $this->Display->addErrorMessage(str_replace("%s", $this->Display->caption(), $this->Display->RequiredErrorMessage));
            }
        }
        if ($this->BackgroundColor->Visible && $this->BackgroundColor->Required) {
            if (!$this->BackgroundColor->IsDetailKey && EmptyValue($this->BackgroundColor->FormValue)) {
                $this->BackgroundColor->addErrorMessage(str_replace("%s", $this->BackgroundColor->caption(), $this->BackgroundColor->RequiredErrorMessage));
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

    // Add record
    protected function addRow($rsold = null)
    {
        global $Language, $Security;

        // Set new row
        $rsnew = [];

        // Title
        $this->_Title->setDbValueDef($rsnew, $this->_Title->CurrentValue, false);

        // Start
        $this->Start->setDbValueDef($rsnew, UnFormatDateTime($this->Start->CurrentValue, $this->Start->formatPattern()), false);

        // End
        $this->End->setDbValueDef($rsnew, UnFormatDateTime($this->End->CurrentValue, $this->End->formatPattern()), false);

        // AllDay
        $tmpBool = $this->AllDay->CurrentValue;
        if ($tmpBool != "1" && $tmpBool != "0") {
            $tmpBool = !empty($tmpBool) ? "1" : "0";
        }
        $this->AllDay->setDbValueDef($rsnew, $tmpBool, false);

        // Description
        $this->Description->setDbValueDef($rsnew, $this->Description->CurrentValue, false);

        // GroupId
        $this->GroupId->setDbValueDef($rsnew, $this->GroupId->CurrentValue, false);

        // Url
        $this->Url->setDbValueDef($rsnew, $this->Url->CurrentValue, false);

        // ClassNames
        $this->ClassNames->setDbValueDef($rsnew, $this->ClassNames->CurrentValue, false);

        // Display
        $this->Display->setDbValueDef($rsnew, $this->Display->CurrentValue, false);

        // BackgroundColor
        $this->BackgroundColor->setDbValueDef($rsnew, $this->BackgroundColor->CurrentValue, false);

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

        // Write JSON response
        if (IsJsonResponse() && $addRow) {
            $row = $this->getRecordsFromRecordset([$rsnew], true);
            $table = $this->UpdateTable;
            WriteJson(["success" => true, "action" => Config("API_ADD_ACTION"), $table => $row]);
        }
        return $addRow;
    }

    // Set up Breadcrumb
    protected function setupBreadcrumb()
    {
        global $Breadcrumb, $Language;
        $Breadcrumb = new Breadcrumb("home");
        $url = CurrentUrl();
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("calendar1list"), "", $this->TableVar, true);
        $pageId = ($this->isCopy()) ? "Copy" : "Add";
        $Breadcrumb->add("add", $pageId, $url);
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
                case "x_AllDay":
                    break;
                case "x_ClassNames":
                    break;
                case "x_Display":
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
