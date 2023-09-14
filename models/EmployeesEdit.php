<?php

namespace PHPMaker2023\demo2023;

use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Page class
 */
class EmployeesEdit extends Employees
{
    use MessagesTrait;

    // Page ID
    public $PageID = "edit";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Page object name
    public $PageObjName = "EmployeesEdit";

    // View file path
    public $View = null;

    // Title
    public $Title = null; // Title for <title> tag

    // Rendering View
    public $RenderingView = false;

    // CSS class/style
    public $CurrentPageName = "employeesedit";

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
        $this->TableClass = "table table-striped table-bordered table-hover table-sm ew-desktop-table ew-edit-table";

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
            if (($keyValue = Get("EmployeeID") ?? Key(0) ?? Route(2)) !== null) {
                $this->EmployeeID->setQueryStringValue($keyValue);
                $this->EmployeeID->setOldValue($this->EmployeeID->QueryStringValue);
            } elseif (Post("EmployeeID") !== null) {
                $this->EmployeeID->setFormValue(Post("EmployeeID"));
                $this->EmployeeID->setOldValue($this->EmployeeID->FormValue);
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
                if (($keyValue = Get("EmployeeID") ?? Route("EmployeeID")) !== null) {
                    $this->EmployeeID->setQueryStringValue($keyValue);
                    $loadByQuery = true;
                } else {
                    $this->EmployeeID->CurrentValue = null;
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
                        $this->terminate("employeeslist"); // Return to list page
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
                        if ($this->EmployeeID->CurrentValue != null) {
                            while (!$rs->EOF) {
                                if (SameString($this->EmployeeID->CurrentValue, $rs->fields['EmployeeID'])) {
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
                        $this->terminate("employeeslist"); // Return to list page
                        return;
                    } else {
                    }
                } else { // Modal edit page
                    if (!$loaded) { // Load record based on key
                        if ($this->getFailureMessage() == "") {
                            $this->setFailureMessage($Language->phrase("NoRecord")); // No record found
                        }
                        $this->terminate("employeeslist"); // No matching record, return to list
                        return;
                    }
                } // End modal checking
                break;
            case "update": // Update
                $returnUrl = $this->getReturnUrl();
                if (GetPageName($returnUrl) == "employeeslist") {
                    $returnUrl = $this->addMasterUrl($returnUrl); // List page, return to List page with correct master key if necessary
                }
                $this->SendEmail = true; // Send email on update success
                if ($this->editRow()) {
                    // Handle UseAjaxActions with return page
                    if ($this->IsModal && $this->UseAjaxActions) {
                        $this->IsModal = false;
                        if (GetPageName($returnUrl) != "employeeslist") {
                            Container("flash")->addMessage("Return-Url", $returnUrl); // Save return URL
                            $returnUrl = "employeeslist"; // Return list page content
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
        $this->Photo->Upload->Index = $CurrentForm->Index;
        $this->Photo->Upload->uploadFile();
        $this->Photo->CurrentValue = $this->Photo->Upload->FileName;
    }

    // Load form values
    protected function loadFormValues()
    {
        // Load from form
        global $CurrentForm;
        $validate = !Config("SERVER_VALIDATE");

        // Check field name 'EmployeeID' first before field var 'x_EmployeeID'
        $val = $CurrentForm->hasValue("EmployeeID") ? $CurrentForm->getValue("EmployeeID") : $CurrentForm->getValue("x_EmployeeID");
        if (!$this->EmployeeID->IsDetailKey) {
            $this->EmployeeID->setFormValue($val);
        }

        // Check field name 'Username' first before field var 'x__Username'
        $val = $CurrentForm->hasValue("Username") ? $CurrentForm->getValue("Username") : $CurrentForm->getValue("x__Username");
        if (!$this->_Username->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->_Username->Visible = false; // Disable update for API request
            } else {
                $this->_Username->setFormValue($val);
            }
        }

        // Check field name 'LastName' first before field var 'x_LastName'
        $val = $CurrentForm->hasValue("LastName") ? $CurrentForm->getValue("LastName") : $CurrentForm->getValue("x_LastName");
        if (!$this->LastName->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->LastName->Visible = false; // Disable update for API request
            } else {
                $this->LastName->setFormValue($val);
            }
        }

        // Check field name 'FirstName' first before field var 'x_FirstName'
        $val = $CurrentForm->hasValue("FirstName") ? $CurrentForm->getValue("FirstName") : $CurrentForm->getValue("x_FirstName");
        if (!$this->FirstName->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->FirstName->Visible = false; // Disable update for API request
            } else {
                $this->FirstName->setFormValue($val);
            }
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

        // Check field name 'TitleOfCourtesy' first before field var 'x_TitleOfCourtesy'
        $val = $CurrentForm->hasValue("TitleOfCourtesy") ? $CurrentForm->getValue("TitleOfCourtesy") : $CurrentForm->getValue("x_TitleOfCourtesy");
        if (!$this->TitleOfCourtesy->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->TitleOfCourtesy->Visible = false; // Disable update for API request
            } else {
                $this->TitleOfCourtesy->setFormValue($val);
            }
        }

        // Check field name 'BirthDate' first before field var 'x_BirthDate'
        $val = $CurrentForm->hasValue("BirthDate") ? $CurrentForm->getValue("BirthDate") : $CurrentForm->getValue("x_BirthDate");
        if (!$this->BirthDate->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->BirthDate->Visible = false; // Disable update for API request
            } else {
                $this->BirthDate->setFormValue($val, true, $validate);
            }
            $this->BirthDate->CurrentValue = UnFormatDateTime($this->BirthDate->CurrentValue, $this->BirthDate->formatPattern());
        }

        // Check field name 'HireDate' first before field var 'x_HireDate'
        $val = $CurrentForm->hasValue("HireDate") ? $CurrentForm->getValue("HireDate") : $CurrentForm->getValue("x_HireDate");
        if (!$this->HireDate->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->HireDate->Visible = false; // Disable update for API request
            } else {
                $this->HireDate->setFormValue($val, true, $validate);
            }
            $this->HireDate->CurrentValue = UnFormatDateTime($this->HireDate->CurrentValue, $this->HireDate->formatPattern());
        }

        // Check field name 'Address' first before field var 'x_Address'
        $val = $CurrentForm->hasValue("Address") ? $CurrentForm->getValue("Address") : $CurrentForm->getValue("x_Address");
        if (!$this->Address->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->Address->Visible = false; // Disable update for API request
            } else {
                $this->Address->setFormValue($val);
            }
        }

        // Check field name 'City' first before field var 'x_City'
        $val = $CurrentForm->hasValue("City") ? $CurrentForm->getValue("City") : $CurrentForm->getValue("x_City");
        if (!$this->City->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->City->Visible = false; // Disable update for API request
            } else {
                $this->City->setFormValue($val);
            }
        }

        // Check field name 'Region' first before field var 'x_Region'
        $val = $CurrentForm->hasValue("Region") ? $CurrentForm->getValue("Region") : $CurrentForm->getValue("x_Region");
        if (!$this->Region->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->Region->Visible = false; // Disable update for API request
            } else {
                $this->Region->setFormValue($val);
            }
        }

        // Check field name 'PostalCode' first before field var 'x_PostalCode'
        $val = $CurrentForm->hasValue("PostalCode") ? $CurrentForm->getValue("PostalCode") : $CurrentForm->getValue("x_PostalCode");
        if (!$this->PostalCode->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->PostalCode->Visible = false; // Disable update for API request
            } else {
                $this->PostalCode->setFormValue($val);
            }
        }

        // Check field name 'Country' first before field var 'x_Country'
        $val = $CurrentForm->hasValue("Country") ? $CurrentForm->getValue("Country") : $CurrentForm->getValue("x_Country");
        if (!$this->Country->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->Country->Visible = false; // Disable update for API request
            } else {
                $this->Country->setFormValue($val);
            }
        }

        // Check field name 'HomePhone' first before field var 'x_HomePhone'
        $val = $CurrentForm->hasValue("HomePhone") ? $CurrentForm->getValue("HomePhone") : $CurrentForm->getValue("x_HomePhone");
        if (!$this->HomePhone->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->HomePhone->Visible = false; // Disable update for API request
            } else {
                $this->HomePhone->setFormValue($val);
            }
        }

        // Check field name 'Extension' first before field var 'x_Extension'
        $val = $CurrentForm->hasValue("Extension") ? $CurrentForm->getValue("Extension") : $CurrentForm->getValue("x_Extension");
        if (!$this->Extension->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->Extension->Visible = false; // Disable update for API request
            } else {
                $this->Extension->setFormValue($val);
            }
        }

        // Check field name 'Notes' first before field var 'x_Notes'
        $val = $CurrentForm->hasValue("Notes") ? $CurrentForm->getValue("Notes") : $CurrentForm->getValue("x_Notes");
        if (!$this->Notes->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->Notes->Visible = false; // Disable update for API request
            } else {
                $this->Notes->setFormValue($val);
            }
        }

        // Check field name 'ReportsTo' first before field var 'x_ReportsTo'
        $val = $CurrentForm->hasValue("ReportsTo") ? $CurrentForm->getValue("ReportsTo") : $CurrentForm->getValue("x_ReportsTo");
        if (!$this->ReportsTo->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->ReportsTo->Visible = false; // Disable update for API request
            } else {
                $this->ReportsTo->setFormValue($val);
            }
        }

        // Check field name 'Password' first before field var 'x__Password'
        $val = $CurrentForm->hasValue("Password") ? $CurrentForm->getValue("Password") : $CurrentForm->getValue("x__Password");
        if (!$this->_Password->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->_Password->Visible = false; // Disable update for API request
            } else {
                $this->_Password->setFormValue($val);
            }
        }

        // Check field name 'UserLevel' first before field var 'x__UserLevel'
        $val = $CurrentForm->hasValue("UserLevel") ? $CurrentForm->getValue("UserLevel") : $CurrentForm->getValue("x__UserLevel");
        if (!$this->_UserLevel->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->_UserLevel->Visible = false; // Disable update for API request
            } else {
                $this->_UserLevel->setFormValue($val);
            }
        }

        // Check field name 'Email' first before field var 'x__Email'
        $val = $CurrentForm->hasValue("Email") ? $CurrentForm->getValue("Email") : $CurrentForm->getValue("x__Email");
        if (!$this->_Email->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->_Email->Visible = false; // Disable update for API request
            } else {
                $this->_Email->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'Activated' first before field var 'x_Activated'
        $val = $CurrentForm->hasValue("Activated") ? $CurrentForm->getValue("Activated") : $CurrentForm->getValue("x_Activated");
        if (!$this->Activated->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->Activated->Visible = false; // Disable update for API request
            } else {
                $this->Activated->setFormValue($val);
            }
        }

        // Check field name 'Profile' first before field var 'x__Profile'
        $val = $CurrentForm->hasValue("Profile") ? $CurrentForm->getValue("Profile") : $CurrentForm->getValue("x__Profile");
        if (!$this->_Profile->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->_Profile->Visible = false; // Disable update for API request
            } else {
                $this->_Profile->setFormValue($val);
            }
        }
		$this->Photo->OldUploadPath = $this->Photo->getUploadPath(); // PHP
		$this->Photo->UploadPath = $this->Photo->OldUploadPath;
        $this->getUploadFiles(); // Get upload files
    }

    // Restore form values
    public function restoreFormValues()
    {
        global $CurrentForm;
        $this->EmployeeID->CurrentValue = $this->EmployeeID->FormValue;
        $this->_Username->CurrentValue = $this->_Username->FormValue;
        $this->LastName->CurrentValue = $this->LastName->FormValue;
        $this->FirstName->CurrentValue = $this->FirstName->FormValue;
        $this->_Title->CurrentValue = $this->_Title->FormValue;
        $this->TitleOfCourtesy->CurrentValue = $this->TitleOfCourtesy->FormValue;
        $this->BirthDate->CurrentValue = $this->BirthDate->FormValue;
        $this->BirthDate->CurrentValue = UnFormatDateTime($this->BirthDate->CurrentValue, $this->BirthDate->formatPattern());
        $this->HireDate->CurrentValue = $this->HireDate->FormValue;
        $this->HireDate->CurrentValue = UnFormatDateTime($this->HireDate->CurrentValue, $this->HireDate->formatPattern());
        $this->Address->CurrentValue = $this->Address->FormValue;
        $this->City->CurrentValue = $this->City->FormValue;
        $this->Region->CurrentValue = $this->Region->FormValue;
        $this->PostalCode->CurrentValue = $this->PostalCode->FormValue;
        $this->Country->CurrentValue = $this->Country->FormValue;
        $this->HomePhone->CurrentValue = $this->HomePhone->FormValue;
        $this->Extension->CurrentValue = $this->Extension->FormValue;
        $this->Notes->CurrentValue = $this->Notes->FormValue;
        $this->ReportsTo->CurrentValue = $this->ReportsTo->FormValue;
        $this->_Password->CurrentValue = $this->_Password->FormValue;
        $this->_UserLevel->CurrentValue = $this->_UserLevel->FormValue;
        $this->_Email->CurrentValue = $this->_Email->FormValue;
        $this->Activated->CurrentValue = $this->Activated->FormValue;
        $this->_Profile->CurrentValue = $this->_Profile->FormValue;
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

        // Check if valid User ID
        if ($res) {
            $res = $this->showOptionLink("edit");
            if (!$res) {
                $userIdMsg = DeniedMessage();
                $this->setFailureMessage($userIdMsg);
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
        $this->EmployeeID->setDbValue($row['EmployeeID']);
        $this->_Username->setDbValue($row['Username']);
        $this->LastName->setDbValue($row['LastName']);
        $this->FirstName->setDbValue($row['FirstName']);
        $this->_Title->setDbValue($row['Title']);
        $this->TitleOfCourtesy->setDbValue($row['TitleOfCourtesy']);
        $this->BirthDate->setDbValue($row['BirthDate']);
        $this->HireDate->setDbValue($row['HireDate']);
        $this->Address->setDbValue($row['Address']);
        $this->City->setDbValue($row['City']);
        $this->Region->setDbValue($row['Region']);
        $this->PostalCode->setDbValue($row['PostalCode']);
        $this->Country->setDbValue($row['Country']);
        $this->HomePhone->setDbValue($row['HomePhone']);
        $this->Extension->setDbValue($row['Extension']);
        $this->Photo->Upload->DbValue = $row['Photo'];
        $this->Photo->setDbValue($this->Photo->Upload->DbValue);
        $this->Notes->setDbValue($row['Notes']);
        $this->ReportsTo->setDbValue($row['ReportsTo']);
        $this->_Password->setDbValue($row['Password']);
        $this->_UserLevel->setDbValue($row['UserLevel']);
        $this->_Email->setDbValue($row['Email']);
        $this->Activated->setDbValue($row['Activated']);
        $this->_Profile->setDbValue($row['Profile']);
    }

    // Return a row with default values
    protected function newRow()
    {
        $row = [];
        $row['EmployeeID'] = $this->EmployeeID->DefaultValue;
        $row['Username'] = $this->_Username->DefaultValue;
        $row['LastName'] = $this->LastName->DefaultValue;
        $row['FirstName'] = $this->FirstName->DefaultValue;
        $row['Title'] = $this->_Title->DefaultValue;
        $row['TitleOfCourtesy'] = $this->TitleOfCourtesy->DefaultValue;
        $row['BirthDate'] = $this->BirthDate->DefaultValue;
        $row['HireDate'] = $this->HireDate->DefaultValue;
        $row['Address'] = $this->Address->DefaultValue;
        $row['City'] = $this->City->DefaultValue;
        $row['Region'] = $this->Region->DefaultValue;
        $row['PostalCode'] = $this->PostalCode->DefaultValue;
        $row['Country'] = $this->Country->DefaultValue;
        $row['HomePhone'] = $this->HomePhone->DefaultValue;
        $row['Extension'] = $this->Extension->DefaultValue;
        $row['Photo'] = $this->Photo->DefaultValue;
        $row['Notes'] = $this->Notes->DefaultValue;
        $row['ReportsTo'] = $this->ReportsTo->DefaultValue;
        $row['Password'] = $this->_Password->DefaultValue;
        $row['UserLevel'] = $this->_UserLevel->DefaultValue;
        $row['Email'] = $this->_Email->DefaultValue;
        $row['Activated'] = $this->Activated->DefaultValue;
        $row['Profile'] = $this->_Profile->DefaultValue;
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

            // Username
            $this->_Username->HrefValue = "";

            // LastName
            $this->LastName->HrefValue = "";

            // FirstName
            $this->FirstName->HrefValue = "";

            // Title
            $this->_Title->HrefValue = "";

            // TitleOfCourtesy
            $this->TitleOfCourtesy->HrefValue = "";

            // BirthDate
            $this->BirthDate->HrefValue = "";

            // HireDate
            $this->HireDate->HrefValue = "";

            // Address
            $this->Address->HrefValue = "";

            // City
            $this->City->HrefValue = "";

            // Region
            $this->Region->HrefValue = "";

            // PostalCode
            $this->PostalCode->HrefValue = "";

            // Country
            $this->Country->HrefValue = "";

            // HomePhone
            $this->HomePhone->HrefValue = "";

            // Extension
            $this->Extension->HrefValue = "";

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

            // Notes
            $this->Notes->HrefValue = "";

            // ReportsTo
            $this->ReportsTo->HrefValue = "";

            // Password
            $this->_Password->HrefValue = "";

            // UserLevel
            $this->_UserLevel->HrefValue = "";

            // Email
            $this->_Email->HrefValue = "";

            // Activated
            $this->Activated->HrefValue = "";

            // Profile
            $this->_Profile->HrefValue = "";
        } elseif ($this->RowType == ROWTYPE_EDIT) {
            // EmployeeID
            $this->EmployeeID->setupEditAttributes();
            $this->EmployeeID->EditValue = $this->EmployeeID->CurrentValue;

            // Username
            $this->_Username->setupEditAttributes();
            if (!$this->_Username->Raw) {
                $this->_Username->CurrentValue = HtmlDecode($this->_Username->CurrentValue);
            }
            $this->_Username->EditValue = HtmlEncode($this->_Username->CurrentValue);
            $this->_Username->PlaceHolder = RemoveHtml($this->_Username->caption());

            // LastName
            $this->LastName->setupEditAttributes();
            if (!$this->LastName->Raw) {
                $this->LastName->CurrentValue = HtmlDecode($this->LastName->CurrentValue);
            }
            $this->LastName->EditValue = HtmlEncode($this->LastName->CurrentValue);
            $this->LastName->PlaceHolder = RemoveHtml($this->LastName->caption());

            // FirstName
            $this->FirstName->setupEditAttributes();
            if (!$this->FirstName->Raw) {
                $this->FirstName->CurrentValue = HtmlDecode($this->FirstName->CurrentValue);
            }
            $this->FirstName->EditValue = HtmlEncode($this->FirstName->CurrentValue);
            $this->FirstName->PlaceHolder = RemoveHtml($this->FirstName->caption());

            // Title
            $this->_Title->setupEditAttributes();
            if (!$this->_Title->Raw) {
                $this->_Title->CurrentValue = HtmlDecode($this->_Title->CurrentValue);
            }
            $this->_Title->EditValue = HtmlEncode($this->_Title->CurrentValue);
            $this->_Title->PlaceHolder = RemoveHtml($this->_Title->caption());

            // TitleOfCourtesy
            $this->TitleOfCourtesy->setupEditAttributes();
            $this->TitleOfCourtesy->EditValue = $this->TitleOfCourtesy->options(true);
            $this->TitleOfCourtesy->PlaceHolder = RemoveHtml($this->TitleOfCourtesy->caption());

            // BirthDate
            $this->BirthDate->setupEditAttributes();
            $this->BirthDate->EditValue = HtmlEncode(FormatDateTime($this->BirthDate->CurrentValue, $this->BirthDate->formatPattern()));
            $this->BirthDate->PlaceHolder = RemoveHtml($this->BirthDate->caption());

            // HireDate
            $this->HireDate->setupEditAttributes();
            $this->HireDate->EditValue = HtmlEncode(FormatDateTime($this->HireDate->CurrentValue, $this->HireDate->formatPattern()));
            $this->HireDate->PlaceHolder = RemoveHtml($this->HireDate->caption());

            // Address
            $this->Address->setupEditAttributes();
            if (!$this->Address->Raw) {
                $this->Address->CurrentValue = HtmlDecode($this->Address->CurrentValue);
            }
            $this->Address->EditValue = HtmlEncode($this->Address->CurrentValue);
            $this->Address->PlaceHolder = RemoveHtml($this->Address->caption());

            // City
            $this->City->setupEditAttributes();
            if (!$this->City->Raw) {
                $this->City->CurrentValue = HtmlDecode($this->City->CurrentValue);
            }
            $this->City->EditValue = HtmlEncode($this->City->CurrentValue);
            $this->City->PlaceHolder = RemoveHtml($this->City->caption());

            // Region
            $this->Region->setupEditAttributes();
            if (!$this->Region->Raw) {
                $this->Region->CurrentValue = HtmlDecode($this->Region->CurrentValue);
            }
            $this->Region->EditValue = HtmlEncode($this->Region->CurrentValue);
            $this->Region->PlaceHolder = RemoveHtml($this->Region->caption());

            // PostalCode
            $this->PostalCode->setupEditAttributes();
            if (!$this->PostalCode->Raw) {
                $this->PostalCode->CurrentValue = HtmlDecode($this->PostalCode->CurrentValue);
            }
            $this->PostalCode->EditValue = HtmlEncode($this->PostalCode->CurrentValue);
            $this->PostalCode->PlaceHolder = RemoveHtml($this->PostalCode->caption());

            // Country
            $this->Country->setupEditAttributes();
            if (!$this->Country->Raw) {
                $this->Country->CurrentValue = HtmlDecode($this->Country->CurrentValue);
            }
            $this->Country->EditValue = HtmlEncode($this->Country->CurrentValue);
            $this->Country->PlaceHolder = RemoveHtml($this->Country->caption());

            // HomePhone
            $this->HomePhone->setupEditAttributes();
            if (!$this->HomePhone->Raw) {
                $this->HomePhone->CurrentValue = HtmlDecode($this->HomePhone->CurrentValue);
            }
            $this->HomePhone->EditValue = HtmlEncode($this->HomePhone->CurrentValue);
            $this->HomePhone->PlaceHolder = RemoveHtml($this->HomePhone->caption());

            // Extension
            $this->Extension->setupEditAttributes();
            if (!$this->Extension->Raw) {
                $this->Extension->CurrentValue = HtmlDecode($this->Extension->CurrentValue);
            }
            $this->Extension->EditValue = HtmlEncode($this->Extension->CurrentValue);
            $this->Extension->PlaceHolder = RemoveHtml($this->Extension->caption());

            // Photo
            $this->Photo->setupEditAttributes();
            $this->Photo->UploadPath = $this->Photo->getUploadPath(); // PHP
            if (!EmptyValue($this->Photo->Upload->DbValue)) {
                $this->Photo->ImageWidth = 120;
                $this->Photo->ImageHeight = 0;
                $this->Photo->ImageAlt = $this->Photo->alt();
                $this->Photo->ImageCssClass = "ew-image";
                $this->Photo->EditValue = $this->Photo->Upload->DbValue;
            } else {
                $this->Photo->EditValue = "";
            }
            if (!EmptyValue($this->Photo->CurrentValue)) {
                $this->Photo->Upload->FileName = $this->Photo->CurrentValue;
            }
            if ($this->isShow()) {
                RenderUploadField($this->Photo);
            }

            // Notes
            $this->Notes->setupEditAttributes();
            $this->Notes->EditValue = HtmlEncode($this->Notes->CurrentValue);
            $this->Notes->PlaceHolder = RemoveHtml($this->Notes->caption());

            // ReportsTo
            $this->ReportsTo->setupEditAttributes();
            if (!$Security->isAdmin() && $Security->isLoggedIn()) { // Non system admin
                if (SameString($this->EmployeeID->CurrentValue, CurrentUserID())) {
                    $curVal = strval($this->ReportsTo->CurrentValue);
                    if ($curVal != "") {
                        $this->ReportsTo->EditValue = $this->ReportsTo->lookupCacheOption($curVal);
                        if ($this->ReportsTo->EditValue === null) { // Lookup from database
                            $filterWrk = SearchFilter("`EmployeeID`", "=", $curVal, DATATYPE_NUMBER, "");
                            $sqlWrk = $this->ReportsTo->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                            $conn = Conn();
                            $config = $conn->getConfiguration();
                            $config->setResultCacheImpl($this->Cache);
                            $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                            $ari = count($rswrk);
                            if ($ari > 0) { // Lookup values found
                                $arwrk = $this->ReportsTo->Lookup->renderViewRow($rswrk[0]);
                                $this->ReportsTo->EditValue = $this->ReportsTo->displayValue($arwrk);
                            } else {
                                $this->ReportsTo->EditValue = $this->ReportsTo->CurrentValue;
                            }
                        }
                    } else {
                        $this->ReportsTo->EditValue = null;
                    }
                } else {
                    if (trim(strval($this->ReportsTo->CurrentValue)) == "") {
                        $filterWrk = "0=1";
                    } else {
                        $filterWrk = SearchFilter("`EmployeeID`", "=", $this->ReportsTo->CurrentValue, DATATYPE_NUMBER, "");
                    }
                    AddFilter($filterWrk, Container("employees")->addParentUserIDFilter($this->EmployeeID->CurrentValue));
                    $sqlWrk = $this->ReportsTo->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                    $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll();
                    $arwrk = $rswrk;
                    $this->ReportsTo->EditValue = $arwrk;
                }
            } else {
                $curVal = trim(strval($this->ReportsTo->CurrentValue));
                if ($curVal != "") {
                    $this->ReportsTo->ViewValue = $this->ReportsTo->lookupCacheOption($curVal);
                } else {
                    $this->ReportsTo->ViewValue = $this->ReportsTo->Lookup !== null && is_array($this->ReportsTo->lookupOptions()) && count($this->ReportsTo->lookupOptions()) > 0 ? $curVal : null;
                }
                if ($this->ReportsTo->ViewValue !== null) { // Load from cache
                    $this->ReportsTo->EditValue = array_values($this->ReportsTo->lookupOptions());
                } else { // Lookup from database
                    if ($curVal == "") {
                        $filterWrk = "0=1";
                    } else {
                        $filterWrk = SearchFilter("`EmployeeID`", "=", $this->ReportsTo->CurrentValue, DATATYPE_NUMBER, "");
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
            }

            // Password
            $this->_Password->setupEditAttributes(["class" => "ew-password-strength"]);
            $this->_Password->EditValue = $Language->phrase("PasswordMask"); // Show as masked password
            $this->_Password->PlaceHolder = RemoveHtml($this->_Password->caption());

            // UserLevel
            $this->_UserLevel->setupEditAttributes();
            if (!$Security->canAdmin()) { // System admin
                $this->_UserLevel->EditValue = $Language->phrase("PasswordMask");
            } else {
                $curVal = trim(strval($this->_UserLevel->CurrentValue));
                if ($curVal != "") {
                    $this->_UserLevel->ViewValue = $this->_UserLevel->lookupCacheOption($curVal);
                } else {
                    $this->_UserLevel->ViewValue = $this->_UserLevel->Lookup !== null && is_array($this->_UserLevel->lookupOptions()) && count($this->_UserLevel->lookupOptions()) > 0 ? $curVal : null;
                }
                if ($this->_UserLevel->ViewValue !== null) { // Load from cache
                    $this->_UserLevel->EditValue = array_values($this->_UserLevel->lookupOptions());
                } else { // Lookup from database
                    if ($curVal == "") {
                        $filterWrk = "0=1";
                    } else {
                        $filterWrk = SearchFilter("`userlevelid`", "=", $this->_UserLevel->CurrentValue, DATATYPE_NUMBER, "");
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
                $this->_Email->CurrentValue = HtmlDecode($this->_Email->CurrentValue);
            }
            $this->_Email->EditValue = HtmlEncode($this->_Email->CurrentValue);
            $this->_Email->PlaceHolder = RemoveHtml($this->_Email->caption());

            // Activated
            $this->Activated->EditValue = $this->Activated->options(false);
            $this->Activated->PlaceHolder = RemoveHtml($this->Activated->caption());

            // Profile
            $this->_Profile->setupEditAttributes();
            $this->_Profile->EditValue = HtmlEncode($this->_Profile->CurrentValue);
            $this->_Profile->PlaceHolder = RemoveHtml($this->_Profile->caption());

            // Edit refer script

            // EmployeeID
            $this->EmployeeID->HrefValue = "";

            // Username
            $this->_Username->HrefValue = "";

            // LastName
            $this->LastName->HrefValue = "";

            // FirstName
            $this->FirstName->HrefValue = "";

            // Title
            $this->_Title->HrefValue = "";

            // TitleOfCourtesy
            $this->TitleOfCourtesy->HrefValue = "";

            // BirthDate
            $this->BirthDate->HrefValue = "";

            // HireDate
            $this->HireDate->HrefValue = "";

            // Address
            $this->Address->HrefValue = "";

            // City
            $this->City->HrefValue = "";

            // Region
            $this->Region->HrefValue = "";

            // PostalCode
            $this->PostalCode->HrefValue = "";

            // Country
            $this->Country->HrefValue = "";

            // HomePhone
            $this->HomePhone->HrefValue = "";

            // Extension
            $this->Extension->HrefValue = "";

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

            // Notes
            $this->Notes->HrefValue = "";

            // ReportsTo
            $this->ReportsTo->HrefValue = "";

            // Password
            $this->_Password->HrefValue = "";

            // UserLevel
            $this->_UserLevel->HrefValue = "";

            // Email
            $this->_Email->HrefValue = "";

            // Activated
            $this->Activated->HrefValue = "";

            // Profile
            $this->_Profile->HrefValue = "";
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
        if ($this->EmployeeID->Visible && $this->EmployeeID->Required) {
            if (!$this->EmployeeID->IsDetailKey && EmptyValue($this->EmployeeID->FormValue)) {
                $this->EmployeeID->addErrorMessage(str_replace("%s", $this->EmployeeID->caption(), $this->EmployeeID->RequiredErrorMessage));
            }
        }
        if ($this->_Username->Visible && $this->_Username->Required) {
            if (!$this->_Username->IsDetailKey && EmptyValue($this->_Username->FormValue)) {
                $this->_Username->addErrorMessage(str_replace("%s", $this->_Username->caption(), $this->_Username->RequiredErrorMessage));
            }
        }
        if (!$this->_Username->Raw && Config("REMOVE_XSS") && CheckUsername($this->_Username->FormValue)) {
            $this->_Username->addErrorMessage($Language->phrase("InvalidUsernameChars"));
        }
        if ($this->LastName->Visible && $this->LastName->Required) {
            if (!$this->LastName->IsDetailKey && EmptyValue($this->LastName->FormValue)) {
                $this->LastName->addErrorMessage(str_replace("%s", $this->LastName->caption(), $this->LastName->RequiredErrorMessage));
            }
        }
        if ($this->FirstName->Visible && $this->FirstName->Required) {
            if (!$this->FirstName->IsDetailKey && EmptyValue($this->FirstName->FormValue)) {
                $this->FirstName->addErrorMessage(str_replace("%s", $this->FirstName->caption(), $this->FirstName->RequiredErrorMessage));
            }
        }
        if ($this->_Title->Visible && $this->_Title->Required) {
            if (!$this->_Title->IsDetailKey && EmptyValue($this->_Title->FormValue)) {
                $this->_Title->addErrorMessage(str_replace("%s", $this->_Title->caption(), $this->_Title->RequiredErrorMessage));
            }
        }
        if ($this->TitleOfCourtesy->Visible && $this->TitleOfCourtesy->Required) {
            if (!$this->TitleOfCourtesy->IsDetailKey && EmptyValue($this->TitleOfCourtesy->FormValue)) {
                $this->TitleOfCourtesy->addErrorMessage(str_replace("%s", $this->TitleOfCourtesy->caption(), $this->TitleOfCourtesy->RequiredErrorMessage));
            }
        }
        if ($this->BirthDate->Visible && $this->BirthDate->Required) {
            if (!$this->BirthDate->IsDetailKey && EmptyValue($this->BirthDate->FormValue)) {
                $this->BirthDate->addErrorMessage(str_replace("%s", $this->BirthDate->caption(), $this->BirthDate->RequiredErrorMessage));
            }
        }
        if (!CheckDate($this->BirthDate->FormValue, $this->BirthDate->formatPattern())) {
            $this->BirthDate->addErrorMessage($this->BirthDate->getErrorMessage(false));
        }
        if ($this->HireDate->Visible && $this->HireDate->Required) {
            if (!$this->HireDate->IsDetailKey && EmptyValue($this->HireDate->FormValue)) {
                $this->HireDate->addErrorMessage(str_replace("%s", $this->HireDate->caption(), $this->HireDate->RequiredErrorMessage));
            }
        }
        if (!CheckDate($this->HireDate->FormValue, $this->HireDate->formatPattern())) {
            $this->HireDate->addErrorMessage($this->HireDate->getErrorMessage(false));
        }
        if ($this->Address->Visible && $this->Address->Required) {
            if (!$this->Address->IsDetailKey && EmptyValue($this->Address->FormValue)) {
                $this->Address->addErrorMessage(str_replace("%s", $this->Address->caption(), $this->Address->RequiredErrorMessage));
            }
        }
        if ($this->City->Visible && $this->City->Required) {
            if (!$this->City->IsDetailKey && EmptyValue($this->City->FormValue)) {
                $this->City->addErrorMessage(str_replace("%s", $this->City->caption(), $this->City->RequiredErrorMessage));
            }
        }
        if ($this->Region->Visible && $this->Region->Required) {
            if (!$this->Region->IsDetailKey && EmptyValue($this->Region->FormValue)) {
                $this->Region->addErrorMessage(str_replace("%s", $this->Region->caption(), $this->Region->RequiredErrorMessage));
            }
        }
        if ($this->PostalCode->Visible && $this->PostalCode->Required) {
            if (!$this->PostalCode->IsDetailKey && EmptyValue($this->PostalCode->FormValue)) {
                $this->PostalCode->addErrorMessage(str_replace("%s", $this->PostalCode->caption(), $this->PostalCode->RequiredErrorMessage));
            }
        }
        if ($this->Country->Visible && $this->Country->Required) {
            if (!$this->Country->IsDetailKey && EmptyValue($this->Country->FormValue)) {
                $this->Country->addErrorMessage(str_replace("%s", $this->Country->caption(), $this->Country->RequiredErrorMessage));
            }
        }
        if ($this->HomePhone->Visible && $this->HomePhone->Required) {
            if (!$this->HomePhone->IsDetailKey && EmptyValue($this->HomePhone->FormValue)) {
                $this->HomePhone->addErrorMessage(str_replace("%s", $this->HomePhone->caption(), $this->HomePhone->RequiredErrorMessage));
            }
        }
        if ($this->Extension->Visible && $this->Extension->Required) {
            if (!$this->Extension->IsDetailKey && EmptyValue($this->Extension->FormValue)) {
                $this->Extension->addErrorMessage(str_replace("%s", $this->Extension->caption(), $this->Extension->RequiredErrorMessage));
            }
        }
        if ($this->Photo->Visible && $this->Photo->Required) {
            if ($this->Photo->Upload->FileName == "" && !$this->Photo->Upload->KeepFile) {
                $this->Photo->addErrorMessage(str_replace("%s", $this->Photo->caption(), $this->Photo->RequiredErrorMessage));
            }
        }
        if ($this->Notes->Visible && $this->Notes->Required) {
            if (!$this->Notes->IsDetailKey && EmptyValue($this->Notes->FormValue)) {
                $this->Notes->addErrorMessage(str_replace("%s", $this->Notes->caption(), $this->Notes->RequiredErrorMessage));
            }
        }
        if ($this->ReportsTo->Visible && $this->ReportsTo->Required) {
            if (!$this->ReportsTo->IsDetailKey && EmptyValue($this->ReportsTo->FormValue)) {
                $this->ReportsTo->addErrorMessage(str_replace("%s", $this->ReportsTo->caption(), $this->ReportsTo->RequiredErrorMessage));
            }
        }
        if ($this->_Password->Visible && $this->_Password->Required) {
            if (!$this->_Password->IsDetailKey && EmptyValue($this->_Password->FormValue)) {
                $this->_Password->addErrorMessage(str_replace("%s", $this->_Password->caption(), $this->_Password->RequiredErrorMessage));
            }
        }
        if (!$this->_Password->Raw && Config("REMOVE_XSS") && CheckPassword($this->_Password->FormValue)) {
            $this->_Password->addErrorMessage($Language->phrase("InvalidPasswordChars"));
        }
        if ($this->_UserLevel->Visible && $this->_UserLevel->Required) {
            if ($Security->canAdmin() && !$this->_UserLevel->IsDetailKey && EmptyValue($this->_UserLevel->FormValue)) {
                $this->_UserLevel->addErrorMessage(str_replace("%s", $this->_UserLevel->caption(), $this->_UserLevel->RequiredErrorMessage));
            }
        }
        if ($this->_Email->Visible && $this->_Email->Required) {
            if (!$this->_Email->IsDetailKey && EmptyValue($this->_Email->FormValue)) {
                $this->_Email->addErrorMessage(str_replace("%s", $this->_Email->caption(), $this->_Email->RequiredErrorMessage));
            }
        }
        if (!CheckEmail($this->_Email->FormValue)) {
            $this->_Email->addErrorMessage($this->_Email->getErrorMessage(false));
        }
        if ($this->Activated->Visible && $this->Activated->Required) {
            if ($this->Activated->FormValue == "") {
                $this->Activated->addErrorMessage(str_replace("%s", $this->Activated->caption(), $this->Activated->RequiredErrorMessage));
            }
        }
        if ($this->_Profile->Visible && $this->_Profile->Required) {
            if (!$this->_Profile->IsDetailKey && EmptyValue($this->_Profile->FormValue)) {
                $this->_Profile->addErrorMessage(str_replace("%s", $this->_Profile->caption(), $this->_Profile->RequiredErrorMessage));
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
            $this->Photo->OldUploadPath = $this->Photo->getUploadPath(); // PHP
            $this->Photo->UploadPath = $this->Photo->OldUploadPath;
        }

        // Set new row
        $rsnew = [];

        // Username
        $this->_Username->setDbValueDef($rsnew, $this->_Username->CurrentValue, $this->_Username->ReadOnly);

        // LastName
        $this->LastName->setDbValueDef($rsnew, $this->LastName->CurrentValue, $this->LastName->ReadOnly);

        // FirstName
        $this->FirstName->setDbValueDef($rsnew, $this->FirstName->CurrentValue, $this->FirstName->ReadOnly);

        // Title
        $this->_Title->setDbValueDef($rsnew, $this->_Title->CurrentValue, $this->_Title->ReadOnly);

        // TitleOfCourtesy
        $this->TitleOfCourtesy->setDbValueDef($rsnew, $this->TitleOfCourtesy->CurrentValue, $this->TitleOfCourtesy->ReadOnly);

        // BirthDate
        $this->BirthDate->setDbValueDef($rsnew, UnFormatDateTime($this->BirthDate->CurrentValue, $this->BirthDate->formatPattern()), $this->BirthDate->ReadOnly);

        // HireDate
        $this->HireDate->setDbValueDef($rsnew, UnFormatDateTime($this->HireDate->CurrentValue, $this->HireDate->formatPattern()), $this->HireDate->ReadOnly);

        // Address
        $this->Address->setDbValueDef($rsnew, $this->Address->CurrentValue, $this->Address->ReadOnly);

        // City
        $this->City->setDbValueDef($rsnew, $this->City->CurrentValue, $this->City->ReadOnly);

        // Region
        $this->Region->setDbValueDef($rsnew, $this->Region->CurrentValue, $this->Region->ReadOnly);

        // PostalCode
        $this->PostalCode->setDbValueDef($rsnew, $this->PostalCode->CurrentValue, $this->PostalCode->ReadOnly);

        // Country
        $this->Country->setDbValueDef($rsnew, $this->Country->CurrentValue, $this->Country->ReadOnly);

        // HomePhone
        $this->HomePhone->setDbValueDef($rsnew, $this->HomePhone->CurrentValue, $this->HomePhone->ReadOnly);

        // Extension
        $this->Extension->setDbValueDef($rsnew, $this->Extension->CurrentValue, $this->Extension->ReadOnly);

        // Photo
        if ($this->Photo->Visible && !$this->Photo->ReadOnly && !$this->Photo->Upload->KeepFile) {
            $this->Photo->Upload->DbValue = $rsold['Photo']; // Get original value
            if ($this->Photo->Upload->FileName == "") {
                $rsnew['Photo'] = null;
            } else {
                $rsnew['Photo'] = $this->Photo->Upload->FileName;
            }
            $this->Photo->ImageWidth = 160; // Resize width
            $this->Photo->ImageHeight = 0; // Resize height
        }

        // Notes
        $this->Notes->setDbValueDef($rsnew, $this->Notes->CurrentValue, $this->Notes->ReadOnly);

        // ReportsTo
        $this->ReportsTo->setDbValueDef($rsnew, $this->ReportsTo->CurrentValue, $this->ReportsTo->ReadOnly);

        // Password
        if (!IsMaskedPassword($this->_Password->CurrentValue)) {
            $this->_Password->setDbValueDef($rsnew, $this->_Password->CurrentValue, $this->_Password->ReadOnly || Config("ENCRYPTED_PASSWORD") && $rsold['Password'] == $this->_Password->CurrentValue);
        }

        // UserLevel
        if ($Security->canAdmin()) { // System admin
            $this->_UserLevel->setDbValueDef($rsnew, $this->_UserLevel->CurrentValue, $this->_UserLevel->ReadOnly);
        }

        // Email
        $this->_Email->setDbValueDef($rsnew, $this->_Email->CurrentValue, $this->_Email->ReadOnly);

        // Activated
        $tmpBool = $this->Activated->CurrentValue;
        if ($tmpBool != "Y" && $tmpBool != "N") {
            $tmpBool = !empty($tmpBool) ? "Y" : "N";
        }
        $this->Activated->setDbValueDef($rsnew, $tmpBool, $this->Activated->ReadOnly);

        // Profile
        $this->_Profile->setDbValueDef($rsnew, $this->_Profile->CurrentValue, $this->_Profile->ReadOnly);

        // Update current values
        $this->setCurrentValues($rsnew);
        if ($this->Photo->Visible && !$this->Photo->Upload->KeepFile) {
            $this->Photo->UploadPath = $this->Photo->getUploadPath(); // PHP
            $oldFiles = EmptyValue($this->Photo->Upload->DbValue) ? [] : explode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $this->Photo->htmlDecode(strval($this->Photo->Upload->DbValue)));
            if (!EmptyValue($this->Photo->Upload->FileName)) {
                $newFiles = explode(Config("MULTIPLE_UPLOAD_SEPARATOR"), strval($this->Photo->Upload->FileName));
                $NewFileCount = count($newFiles);
                for ($i = 0; $i < $NewFileCount; $i++) {
                    if ($newFiles[$i] != "") {
                        $file = $newFiles[$i];
                        $tempPath = UploadTempPath($this->Photo, $this->Photo->Upload->Index);
                        if (file_exists($tempPath . $file)) {
                            if (Config("DELETE_UPLOADED_FILES")) {
                                $oldFileFound = false;
                                $oldFileCount = count($oldFiles);
                                for ($j = 0; $j < $oldFileCount; $j++) {
                                    $oldFile = $oldFiles[$j];
                                    if ($oldFile == $file) { // Old file found, no need to delete anymore
                                        array_splice($oldFiles, $j, 1);
                                        $oldFileFound = true;
                                        break;
                                    }
                                }
                                if ($oldFileFound) { // No need to check if file exists further
                                    continue;
                                }
                            }
                            $file1 = UniqueFilename($this->Photo->physicalUploadPath(), $file); // Get new file name
                            if ($file1 != $file) { // Rename temp file
                                while (file_exists($tempPath . $file1) || file_exists($this->Photo->physicalUploadPath() . $file1)) { // Make sure no file name clash
                                    $file1 = UniqueFilename([$this->Photo->physicalUploadPath(), $tempPath], $file1, true); // Use indexed name
                                }
                                rename($tempPath . $file, $tempPath . $file1);
                                $newFiles[$i] = $file1;
                            }
                        }
                    }
                }
                $this->Photo->Upload->DbValue = empty($oldFiles) ? "" : implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $oldFiles);
                $this->Photo->Upload->FileName = implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $newFiles);
                $this->Photo->setDbValueDef($rsnew, $this->Photo->Upload->FileName, $this->Photo->ReadOnly);
            }
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
                if ($this->Photo->Visible && !$this->Photo->Upload->KeepFile) {
                    $oldFiles = EmptyValue($this->Photo->Upload->DbValue) ? [] : explode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $this->Photo->htmlDecode(strval($this->Photo->Upload->DbValue)));
                    if (!EmptyValue($this->Photo->Upload->FileName)) {
                        $newFiles = explode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $this->Photo->Upload->FileName);
                        $newFiles2 = explode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $this->Photo->htmlDecode($rsnew['Photo']));
                        $newFileCount = count($newFiles);
                        for ($i = 0; $i < $newFileCount; $i++) {
                            if ($newFiles[$i] != "") {
                                $file = UploadTempPath($this->Photo, $this->Photo->Upload->Index) . $newFiles[$i];
                                if (file_exists($file)) {
                                    if (@$newFiles2[$i] != "") { // Use correct file name
                                        $newFiles[$i] = $newFiles2[$i];
                                    }
                                    if (!$this->Photo->Upload->ResizeAndSaveToFile($this->Photo->ImageWidth, $this->Photo->ImageHeight, 100, $newFiles[$i], true, $i)) {
                                        $this->setFailureMessage($Language->phrase("UploadError7"));
                                        return false;
                                    }
                                }
                            }
                        }
                    } else {
                        $newFiles = [];
                    }
                    if (Config("DELETE_UPLOADED_FILES")) {
                        foreach ($oldFiles as $oldFile) {
                            if ($oldFile != "" && !in_array($oldFile, $newFiles)) {
                                @unlink($this->Photo->oldPhysicalUploadPath() . $oldFile);
                            }
                        }
                    }
                }
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
            $table = $this->TableVar;
            WriteJson(["success" => true, "action" => Config("API_EDIT_ACTION"), $table => $row]);
        }
        return $editRow;
    }

    // Show link optionally based on User ID
    protected function showOptionLink($id = "")
    {
        global $Security;
        if ($Security->isLoggedIn() && !$Security->isAdmin() && !$this->userIDAllow($id)) {
            return $Security->isValidUserID($this->EmployeeID->CurrentValue);
        }
        return true;
    }

    // Set up Breadcrumb
    protected function setupBreadcrumb()
    {
        global $Breadcrumb, $Language;
        $Breadcrumb = new Breadcrumb("home");
        $url = CurrentUrl();
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("employeeslist"), "", $this->TableVar, true);
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
