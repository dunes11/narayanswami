<?php

namespace PHPMaker2023\demo2023;

use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Page class
 */
class Calendar1Edit extends Calendar1
{
    use MessagesTrait;

    // Page ID
    public $PageID = "edit";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Page object name
    public $PageObjName = "Calendar1Edit";

    // View file path
    public $View = null;

    // Title
    public $Title = null; // Title for <title> tag

    // Rendering View
    public $RenderingView = false;

    // CSS class/style
    public $ReportContainerClass = "ew-grid";
    public $CurrentPageName = "calendar1edit";

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
        $this->TableClass = "table table-striped table-bordered table-hover table-sm ew-desktop-table ew-edit-table";

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

    // Properties
    public $FormClassName = "ew-form ew-edit-form overlay-wrapper";
    public $IsModal = false;
    public $IsMobileOrModal = false;
    public $DbMasterFilter;
    public $DbDetailFilter;
    public $HashValue; // Hash Value
    public $DisplayRecords = 1;
    public $StartRecord;
    public $StopRecord;
    public $TotalRecords = 0;
    public $RecordRange = 10;
    public $RecordCount;

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

        // Check modal
        if ($this->IsModal) {
            $SkipHeaderFooter = true;
        }
        $this->IsMobileOrModal = IsMobile() || $this->IsModal;

        // Load record by position
        $loadByPosition = false;
        $loaded = false;
        $postBack = false;

        // Set up current action and primary key
        if (IsApi()) {
            // Load key values
            $loaded = true;
            if (($keyValue = Get("Id") ?? Key(0) ?? Route(2)) !== null) {
                $this->Id->setQueryStringValue($keyValue);
                $this->Id->setOldValue($this->Id->QueryStringValue);
            } elseif (Post("Id") !== null) {
                $this->Id->setFormValue(Post("Id"));
                $this->Id->setOldValue($this->Id->FormValue);
            } else {
                $loaded = false; // Unable to load key
            }

            // Load record
            if ($loaded) {
                $loaded = $this->loadRow();
            }
            if (!$loaded) {
                $this->setFailureMessage($Language->phrase("NoRecord")); // Set no record message
                $this->terminate();
                return;
            }
            $this->CurrentAction = "update"; // Update record directly
            $this->OldKey = $this->getKey(true); // Get from CurrentValue
            $postBack = true;
        } else {
            if (Post("action", "") !== "") {
                $this->CurrentAction = Post("action"); // Get action code
                if (!$this->isShow()) { // Not reload record, handle as postback
                    $postBack = true;
                }

                // Get key from Form
                $this->setKey(Post($this->OldKeyName), $this->isShow());
            } else {
                $this->CurrentAction = "show"; // Default action is display

                // Load key from QueryString
                $loadByQuery = false;
                if (($keyValue = Get("Id") ?? Route("Id")) !== null) {
                    $this->Id->setQueryStringValue($keyValue);
                    $loadByQuery = true;
                } else {
                    $this->Id->CurrentValue = null;
                }
                if (!$loadByQuery || Get(Config("TABLE_START_REC")) !== null || Get(Config("TABLE_PAGE_NUMBER")) !== null) {
                    $loadByPosition = true;
                }
            }

            // Load recordset
            if ($this->isShow()) {
                if (!$this->IsModal) { // Normal edit page
                    $this->StartRecord = 1; // Initialize start position
                    if ($rs = $this->loadRecordset()) { // Load records
                        $this->TotalRecords = $rs->recordCount(); // Get record count
                    }
                    if ($this->TotalRecords <= 0) { // No record found
                        if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "") {
                            $this->setFailureMessage($Language->phrase("NoRecord")); // Set no record message
                        }
                        $this->terminate("calendar1list"); // Return to list page
                        return;
                    } elseif ($loadByPosition) { // Load record by position
                        $this->setupStartRecord(); // Set up start record position
                        // Point to current record
                        if ($this->StartRecord <= $this->TotalRecords) {
                            $rs->move($this->StartRecord - 1);
                            // Redirect to correct record
                            $this->loadRowValues($rs);
                            $url = $this->getCurrentUrl();
                            $this->terminate($url);
                            return;
                        }
                    } else { // Match key values
                        if ($this->Id->CurrentValue != null) {
                            while (!$rs->EOF) {
                                if (SameString($this->Id->CurrentValue, $rs->fields['Id'])) {
                                    $this->setStartRecordNumber($this->StartRecord); // Save record position
                                    $loaded = true;
                                    break;
                                } else {
                                    $this->StartRecord++;
                                    $rs->moveNext();
                                }
                            }
                        }
                    }

                    // Load current row values
                    if ($loaded) {
                        $this->loadRowValues($rs);
                    }
                } else {
                    // Load current record
                    $loaded = $this->loadRow();
                } // End modal checking
                $this->OldKey = $loaded ? $this->getKey(true) : ""; // Get from CurrentValue
            }
        }

        // Process form if post back
        if ($postBack) {
            $this->loadFormValues(); // Get form values
        }

        // Validate form if post back
        if ($postBack) {
            if (!$this->validateForm()) {
                $this->EventCancelled = true; // Event cancelled
                $this->restoreFormValues();
                if (IsApi()) {
                    $this->terminate();
                    return;
                } else {
                    $this->CurrentAction = ""; // Form error, reset action
                }
            }
        }

        // Perform current action
        switch ($this->CurrentAction) {
            case "show": // Get a record to display
                if (!$this->IsModal) { // Normal edit page
                    if (!$loaded) {
                        if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "") {
                            $this->setFailureMessage($Language->phrase("NoRecord")); // Set no record message
                        }
                        $this->terminate("calendar1list"); // Return to list page
                        return;
                    } else {
                    }
                } else { // Modal edit page
                    if (!$loaded) { // Load record based on key
                        if ($this->getFailureMessage() == "") {
                            $this->setFailureMessage($Language->phrase("NoRecord")); // No record found
                        }
                        $this->terminate("calendar1list"); // No matching record, return to list
                        return;
                    }
                } // End modal checking
                break;
            case "update": // Update
                $returnUrl = $this->getReturnUrl();
                if (GetPageName($returnUrl) == "calendar1list") {
                    $returnUrl = $this->addMasterUrl($returnUrl); // List page, return to List page with correct master key if necessary
                }
                $this->SendEmail = true; // Send email on update success
                if ($this->editRow()) {
                    // Handle UseAjaxActions with return page
                    if ($this->IsModal && $this->UseAjaxActions) {
                        $this->IsModal = false;
                        if (GetPageName($returnUrl) != "calendar1list") {
                            Container("flash")->addMessage("Return-Url", $returnUrl); // Save return URL
                            $returnUrl = "calendar1list"; // Return list page content
                        }
                    }
                    if ($this->getSuccessMessage() == "") {
                        $this->setSuccessMessage($Language->phrase("UpdateSuccess")); // Update success
                    }
                    if (IsJsonResponse()) {
                        $this->terminate(true);
                        return;
                    } else {
                        $this->terminate($returnUrl); // Return to caller
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
                } elseif ($this->getFailureMessage() == $Language->phrase("NoRecord")) {
                    $this->terminate($returnUrl); // Return to caller
                    return;
                } else {
                    $this->EventCancelled = true; // Event cancelled
                    $this->restoreFormValues(); // Restore form values if update failed
                }
        }

        // Set up Breadcrumb
        $this->setupBreadcrumb();

        // Render the record
        $this->RowType = ROWTYPE_EDIT; // Render as Edit
        $this->resetAttributes();
        $this->renderRow();
        if (!$this->IsModal) { // Normal view page
            $this->Pager = new PrevNextPager($this, $this->StartRecord, $this->DisplayRecords, $this->TotalRecords, "", $this->RecordRange, $this->AutoHidePager, false, false);
            $this->Pager->PageNumberName = Config("TABLE_PAGE_NUMBER");
            $this->Pager->PagePhraseId = "Record"; // Show as record
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

    // Get upload files
    protected function getUploadFiles()
    {
        global $CurrentForm, $Language;
    }

    // Load form values
    protected function loadFormValues()
    {
        // Load from form
        global $CurrentForm;
        $validate = !Config("SERVER_VALIDATE");

        // Check field name 'Id' first before field var 'x_Id'
        $val = $CurrentForm->hasValue("Id") ? $CurrentForm->getValue("Id") : $CurrentForm->getValue("x_Id");
        if (!$this->Id->IsDetailKey) {
            $this->Id->setFormValue($val);
        }

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
    }

    // Restore form values
    public function restoreFormValues()
    {
        global $CurrentForm;
        $this->Id->CurrentValue = $this->Id->FormValue;
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

            // Id
            $this->Id->HrefValue = "";

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
        } elseif ($this->RowType == ROWTYPE_EDIT) {
            // Id
            $this->Id->setupEditAttributes();
            $this->Id->EditValue = $this->Id->CurrentValue;

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

            // Edit refer script

            // Id
            $this->Id->HrefValue = "";

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
        if ($this->Id->Visible && $this->Id->Required) {
            if (!$this->Id->IsDetailKey && EmptyValue($this->Id->FormValue)) {
                $this->Id->addErrorMessage(str_replace("%s", $this->Id->caption(), $this->Id->RequiredErrorMessage));
            }
        }
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

        // Title
        $this->_Title->setDbValueDef($rsnew, $this->_Title->CurrentValue, $this->_Title->ReadOnly);

        // Start
        $this->Start->setDbValueDef($rsnew, UnFormatDateTime($this->Start->CurrentValue, $this->Start->formatPattern()), $this->Start->ReadOnly);

        // End
        $this->End->setDbValueDef($rsnew, UnFormatDateTime($this->End->CurrentValue, $this->End->formatPattern()), $this->End->ReadOnly);

        // AllDay
        $tmpBool = $this->AllDay->CurrentValue;
        if ($tmpBool != "1" && $tmpBool != "0") {
            $tmpBool = !empty($tmpBool) ? "1" : "0";
        }
        $this->AllDay->setDbValueDef($rsnew, $tmpBool, $this->AllDay->ReadOnly);

        // Description
        $this->Description->setDbValueDef($rsnew, $this->Description->CurrentValue, $this->Description->ReadOnly);

        // GroupId
        $this->GroupId->setDbValueDef($rsnew, $this->GroupId->CurrentValue, $this->GroupId->ReadOnly);

        // Url
        $this->Url->setDbValueDef($rsnew, $this->Url->CurrentValue, $this->Url->ReadOnly);

        // ClassNames
        $this->ClassNames->setDbValueDef($rsnew, $this->ClassNames->CurrentValue, $this->ClassNames->ReadOnly);

        // Display
        $this->Display->setDbValueDef($rsnew, $this->Display->CurrentValue, $this->Display->ReadOnly);

        // BackgroundColor
        $this->BackgroundColor->setDbValueDef($rsnew, $this->BackgroundColor->CurrentValue, $this->BackgroundColor->ReadOnly);

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

        // Write JSON response
        if (IsJsonResponse() && $editRow) {
            $row = $this->getRecordsFromRecordset([$rsnew], true);
            $table = $this->UpdateTable;
            $row['id'] = $row['Id']; // Get event id
            WriteJson(["success" => true, "action" => Config("API_EDIT_ACTION"), $table => $row]);
        }
        return $editRow;
    }

    // Set up Breadcrumb
    protected function setupBreadcrumb()
    {
        global $Breadcrumb, $Language;
        $Breadcrumb = new Breadcrumb("home");
        $url = CurrentUrl();
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("calendar1list"), "", $this->TableVar, true);
        $pageId = "edit";
        $Breadcrumb->add("edit", $pageId, $url);
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

    // Set up starting record parameters
    public function setupStartRecord()
    {
        if ($this->DisplayRecords == 0) {
            return;
        }
        $pageNo = Get(Config("TABLE_PAGE_NUMBER"));
        $startRec = Get(Config("TABLE_START_REC"));
        $infiniteScroll = false;
        $recordNo = $pageNo ?? $startRec; // Record number = page number or start record
        if ($recordNo !== null && is_numeric($recordNo)) {
            $this->StartRecord = $recordNo;
        } else {
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
}
