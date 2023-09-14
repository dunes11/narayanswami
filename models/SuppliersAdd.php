<?php

namespace PHPMaker2023\demo2023;

use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Page class
 */
class SuppliersAdd extends Suppliers
{
    use MessagesTrait;

    // Page ID
    public $PageID = "add";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Page object name
    public $PageObjName = "SuppliersAdd";

    // View file path
    public $View = null;

    // Title
    public $Title = null; // Title for <title> tag

    // Rendering View
    public $RenderingView = false;

    // CSS class/style
    public $CurrentPageName = "suppliersadd";

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
        $this->SupplierID->Visible = false;
        $this->CompanyName->setVisibility();
        $this->ContactName->setVisibility();
        $this->ContactTitle->setVisibility();
        $this->Address->setVisibility();
        $this->City->setVisibility();
        $this->Region->setVisibility();
        $this->PostalCode->setVisibility();
        $this->Country->setVisibility();
        $this->Phone->setVisibility();
        $this->Fax->setVisibility();
        $this->HomePage->setVisibility();
    }

    // Constructor
    public function __construct()
    {
        parent::__construct();
        global $Language, $DashboardReport, $DebugTimer, $UserTable;
        $this->TableVar = 'suppliers';
        $this->TableName = 'suppliers';

        // Table CSS class
        $this->TableClass = "table table-striped table-bordered table-hover table-sm ew-desktop-table ew-add-table";

        // Initialize
        $GLOBALS["Page"] = &$this;

        // Language object
        $Language = Container("language");

        // Table object (suppliers)
        if (!isset($GLOBALS["suppliers"]) || get_class($GLOBALS["suppliers"]) == PROJECT_NAMESPACE . "suppliers") {
            $GLOBALS["suppliers"] = &$this;
        }

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'suppliers');
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
                    $result["view"] = $pageName == "suppliersview"; // If View page, no primary button
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
            $key .= @$ar['SupplierID'];
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
            $this->SupplierID->Visible = false;
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
            if (($keyValue = Get("SupplierID") ?? Route("SupplierID")) !== null) {
                $this->SupplierID->setQueryStringValue($keyValue);
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
                    $this->terminate("supplierslist"); // No matching record, return to list
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
                    if (GetPageName($returnUrl) == "supplierslist") {
                        $returnUrl = $this->addMasterUrl($returnUrl); // List page, return to List page with correct master key if necessary
                    } elseif (GetPageName($returnUrl) == "suppliersview") {
                        $returnUrl = $this->getViewUrl(); // View page, return to View page with keyurl directly
                    }

                    // Handle UseAjaxActions
                    if ($this->IsModal && $this->UseAjaxActions) {
                        $this->IsModal = false;
                        if (GetPageName($returnUrl) != "supplierslist") {
                            Container("flash")->addMessage("Return-Url", $returnUrl); // Save return URL
                            $returnUrl = "supplierslist"; // Return list page content
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
    }

    // Load form values
    protected function loadFormValues()
    {
        // Load from form
        global $CurrentForm;
        $validate = !Config("SERVER_VALIDATE");

        // Check field name 'CompanyName' first before field var 'x_CompanyName'
        $val = $CurrentForm->hasValue("CompanyName") ? $CurrentForm->getValue("CompanyName") : $CurrentForm->getValue("x_CompanyName");
        if (!$this->CompanyName->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->CompanyName->Visible = false; // Disable update for API request
            } else {
                $this->CompanyName->setFormValue($val);
            }
        }

        // Check field name 'ContactName' first before field var 'x_ContactName'
        $val = $CurrentForm->hasValue("ContactName") ? $CurrentForm->getValue("ContactName") : $CurrentForm->getValue("x_ContactName");
        if (!$this->ContactName->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->ContactName->Visible = false; // Disable update for API request
            } else {
                $this->ContactName->setFormValue($val);
            }
        }

        // Check field name 'ContactTitle' first before field var 'x_ContactTitle'
        $val = $CurrentForm->hasValue("ContactTitle") ? $CurrentForm->getValue("ContactTitle") : $CurrentForm->getValue("x_ContactTitle");
        if (!$this->ContactTitle->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->ContactTitle->Visible = false; // Disable update for API request
            } else {
                $this->ContactTitle->setFormValue($val);
            }
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

        // Check field name 'Phone' first before field var 'x_Phone'
        $val = $CurrentForm->hasValue("Phone") ? $CurrentForm->getValue("Phone") : $CurrentForm->getValue("x_Phone");
        if (!$this->Phone->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->Phone->Visible = false; // Disable update for API request
            } else {
                $this->Phone->setFormValue($val);
            }
        }

        // Check field name 'Fax' first before field var 'x_Fax'
        $val = $CurrentForm->hasValue("Fax") ? $CurrentForm->getValue("Fax") : $CurrentForm->getValue("x_Fax");
        if (!$this->Fax->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->Fax->Visible = false; // Disable update for API request
            } else {
                $this->Fax->setFormValue($val);
            }
        }

        // Check field name 'HomePage' first before field var 'x_HomePage'
        $val = $CurrentForm->hasValue("HomePage") ? $CurrentForm->getValue("HomePage") : $CurrentForm->getValue("x_HomePage");
        if (!$this->HomePage->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->HomePage->Visible = false; // Disable update for API request
            } else {
                $this->HomePage->setFormValue($val);
            }
        }

        // Check field name 'SupplierID' first before field var 'x_SupplierID'
        $val = $CurrentForm->hasValue("SupplierID") ? $CurrentForm->getValue("SupplierID") : $CurrentForm->getValue("x_SupplierID");
    }

    // Restore form values
    public function restoreFormValues()
    {
        global $CurrentForm;
        $this->CompanyName->CurrentValue = $this->CompanyName->FormValue;
        $this->ContactName->CurrentValue = $this->ContactName->FormValue;
        $this->ContactTitle->CurrentValue = $this->ContactTitle->FormValue;
        $this->Address->CurrentValue = $this->Address->FormValue;
        $this->City->CurrentValue = $this->City->FormValue;
        $this->Region->CurrentValue = $this->Region->FormValue;
        $this->PostalCode->CurrentValue = $this->PostalCode->FormValue;
        $this->Country->CurrentValue = $this->Country->FormValue;
        $this->Phone->CurrentValue = $this->Phone->FormValue;
        $this->Fax->CurrentValue = $this->Fax->FormValue;
        $this->HomePage->CurrentValue = $this->HomePage->FormValue;
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
        $this->SupplierID->setDbValue($row['SupplierID']);
        $this->CompanyName->setDbValue($row['CompanyName']);
        $this->ContactName->setDbValue($row['ContactName']);
        $this->ContactTitle->setDbValue($row['ContactTitle']);
        $this->Address->setDbValue($row['Address']);
        $this->City->setDbValue($row['City']);
        $this->Region->setDbValue($row['Region']);
        $this->PostalCode->setDbValue($row['PostalCode']);
        $this->Country->setDbValue($row['Country']);
        $this->Phone->setDbValue($row['Phone']);
        $this->Fax->setDbValue($row['Fax']);
        $this->HomePage->setDbValue($row['HomePage']);
    }

    // Return a row with default values
    protected function newRow()
    {
        $row = [];
        $row['SupplierID'] = $this->SupplierID->DefaultValue;
        $row['CompanyName'] = $this->CompanyName->DefaultValue;
        $row['ContactName'] = $this->ContactName->DefaultValue;
        $row['ContactTitle'] = $this->ContactTitle->DefaultValue;
        $row['Address'] = $this->Address->DefaultValue;
        $row['City'] = $this->City->DefaultValue;
        $row['Region'] = $this->Region->DefaultValue;
        $row['PostalCode'] = $this->PostalCode->DefaultValue;
        $row['Country'] = $this->Country->DefaultValue;
        $row['Phone'] = $this->Phone->DefaultValue;
        $row['Fax'] = $this->Fax->DefaultValue;
        $row['HomePage'] = $this->HomePage->DefaultValue;
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

        // SupplierID
        $this->SupplierID->RowCssClass = "row";

        // CompanyName
        $this->CompanyName->RowCssClass = "row";

        // ContactName
        $this->ContactName->RowCssClass = "row";

        // ContactTitle
        $this->ContactTitle->RowCssClass = "row";

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

        // Phone
        $this->Phone->RowCssClass = "row";

        // Fax
        $this->Fax->RowCssClass = "row";

        // HomePage
        $this->HomePage->RowCssClass = "row";

        // View row
        if ($this->RowType == ROWTYPE_VIEW) {
            // SupplierID
            $this->SupplierID->ViewValue = $this->SupplierID->CurrentValue;

            // CompanyName
            $this->CompanyName->ViewValue = $this->CompanyName->CurrentValue;

            // ContactName
            $this->ContactName->ViewValue = $this->ContactName->CurrentValue;

            // ContactTitle
            $this->ContactTitle->ViewValue = $this->ContactTitle->CurrentValue;

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

            // Phone
            $this->Phone->ViewValue = $this->Phone->CurrentValue;

            // Fax
            $this->Fax->ViewValue = $this->Fax->CurrentValue;

            // HomePage
            $this->HomePage->ViewValue = $this->HomePage->CurrentValue;
            if ($this->HomePage->ViewValue != null) {
                $this->HomePage->ViewValue = str_replace(["\r\n", "\n", "\r"], "<br>", $this->HomePage->ViewValue);
            }

            // CompanyName
            $this->CompanyName->HrefValue = "";

            // ContactName
            $this->ContactName->HrefValue = "";

            // ContactTitle
            $this->ContactTitle->HrefValue = "";

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

            // Phone
            $this->Phone->HrefValue = "";

            // Fax
            $this->Fax->HrefValue = "";

            // HomePage
            $this->HomePage->HrefValue = "";
        } elseif ($this->RowType == ROWTYPE_ADD) {
            // CompanyName
            $this->CompanyName->setupEditAttributes();
            if (!$this->CompanyName->Raw) {
                $this->CompanyName->CurrentValue = HtmlDecode($this->CompanyName->CurrentValue);
            }
            $this->CompanyName->EditValue = HtmlEncode($this->CompanyName->CurrentValue);
            $this->CompanyName->PlaceHolder = RemoveHtml($this->CompanyName->caption());

            // ContactName
            $this->ContactName->setupEditAttributes();
            if (!$this->ContactName->Raw) {
                $this->ContactName->CurrentValue = HtmlDecode($this->ContactName->CurrentValue);
            }
            $this->ContactName->EditValue = HtmlEncode($this->ContactName->CurrentValue);
            $this->ContactName->PlaceHolder = RemoveHtml($this->ContactName->caption());

            // ContactTitle
            $this->ContactTitle->setupEditAttributes();
            if (!$this->ContactTitle->Raw) {
                $this->ContactTitle->CurrentValue = HtmlDecode($this->ContactTitle->CurrentValue);
            }
            $this->ContactTitle->EditValue = HtmlEncode($this->ContactTitle->CurrentValue);
            $this->ContactTitle->PlaceHolder = RemoveHtml($this->ContactTitle->caption());

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

            // Phone
            $this->Phone->setupEditAttributes();
            if (!$this->Phone->Raw) {
                $this->Phone->CurrentValue = HtmlDecode($this->Phone->CurrentValue);
            }
            $this->Phone->EditValue = HtmlEncode($this->Phone->CurrentValue);
            $this->Phone->PlaceHolder = RemoveHtml($this->Phone->caption());

            // Fax
            $this->Fax->setupEditAttributes();
            if (!$this->Fax->Raw) {
                $this->Fax->CurrentValue = HtmlDecode($this->Fax->CurrentValue);
            }
            $this->Fax->EditValue = HtmlEncode($this->Fax->CurrentValue);
            $this->Fax->PlaceHolder = RemoveHtml($this->Fax->caption());

            // HomePage
            $this->HomePage->setupEditAttributes();
            $this->HomePage->EditValue = HtmlEncode($this->HomePage->CurrentValue);
            $this->HomePage->PlaceHolder = RemoveHtml($this->HomePage->caption());

            // Add refer script

            // CompanyName
            $this->CompanyName->HrefValue = "";

            // ContactName
            $this->ContactName->HrefValue = "";

            // ContactTitle
            $this->ContactTitle->HrefValue = "";

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

            // Phone
            $this->Phone->HrefValue = "";

            // Fax
            $this->Fax->HrefValue = "";

            // HomePage
            $this->HomePage->HrefValue = "";
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
        if ($this->CompanyName->Visible && $this->CompanyName->Required) {
            if (!$this->CompanyName->IsDetailKey && EmptyValue($this->CompanyName->FormValue)) {
                $this->CompanyName->addErrorMessage(str_replace("%s", $this->CompanyName->caption(), $this->CompanyName->RequiredErrorMessage));
            }
        }
        if ($this->ContactName->Visible && $this->ContactName->Required) {
            if (!$this->ContactName->IsDetailKey && EmptyValue($this->ContactName->FormValue)) {
                $this->ContactName->addErrorMessage(str_replace("%s", $this->ContactName->caption(), $this->ContactName->RequiredErrorMessage));
            }
        }
        if ($this->ContactTitle->Visible && $this->ContactTitle->Required) {
            if (!$this->ContactTitle->IsDetailKey && EmptyValue($this->ContactTitle->FormValue)) {
                $this->ContactTitle->addErrorMessage(str_replace("%s", $this->ContactTitle->caption(), $this->ContactTitle->RequiredErrorMessage));
            }
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
        if ($this->Phone->Visible && $this->Phone->Required) {
            if (!$this->Phone->IsDetailKey && EmptyValue($this->Phone->FormValue)) {
                $this->Phone->addErrorMessage(str_replace("%s", $this->Phone->caption(), $this->Phone->RequiredErrorMessage));
            }
        }
        if ($this->Fax->Visible && $this->Fax->Required) {
            if (!$this->Fax->IsDetailKey && EmptyValue($this->Fax->FormValue)) {
                $this->Fax->addErrorMessage(str_replace("%s", $this->Fax->caption(), $this->Fax->RequiredErrorMessage));
            }
        }
        if ($this->HomePage->Visible && $this->HomePage->Required) {
            if (!$this->HomePage->IsDetailKey && EmptyValue($this->HomePage->FormValue)) {
                $this->HomePage->addErrorMessage(str_replace("%s", $this->HomePage->caption(), $this->HomePage->RequiredErrorMessage));
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

        // CompanyName
        $this->CompanyName->setDbValueDef($rsnew, $this->CompanyName->CurrentValue, false);

        // ContactName
        $this->ContactName->setDbValueDef($rsnew, $this->ContactName->CurrentValue, false);

        // ContactTitle
        $this->ContactTitle->setDbValueDef($rsnew, $this->ContactTitle->CurrentValue, false);

        // Address
        $this->Address->setDbValueDef($rsnew, $this->Address->CurrentValue, false);

        // City
        $this->City->setDbValueDef($rsnew, $this->City->CurrentValue, false);

        // Region
        $this->Region->setDbValueDef($rsnew, $this->Region->CurrentValue, false);

        // PostalCode
        $this->PostalCode->setDbValueDef($rsnew, $this->PostalCode->CurrentValue, false);

        // Country
        $this->Country->setDbValueDef($rsnew, $this->Country->CurrentValue, false);

        // Phone
        $this->Phone->setDbValueDef($rsnew, $this->Phone->CurrentValue, false);

        // Fax
        $this->Fax->setDbValueDef($rsnew, $this->Fax->CurrentValue, false);

        // HomePage
        $this->HomePage->setDbValueDef($rsnew, $this->HomePage->CurrentValue, false);

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
            $table = $this->TableVar;
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
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("supplierslist"), "", $this->TableVar, true);
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
