<?php

namespace PHPMaker2023\demo2023;

use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Page class
 */
class Orders2Edit extends Orders2
{
    use MessagesTrait;

    // Page ID
    public $PageID = "edit";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Page object name
    public $PageObjName = "Orders2Edit";

    // View file path
    public $View = null;

    // Title
    public $Title = null; // Title for <title> tag

    // Rendering View
    public $RenderingView = false;

    // CSS class/style
    public $CurrentPageName = "orders2edit";

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
        $this->TableVar = 'orders2';
        $this->TableName = 'orders2';

        // Table CSS class
        $this->TableClass = "table table-striped table-bordered table-hover table-sm ew-desktop-table ew-edit-table";

        // Initialize
        $GLOBALS["Page"] = &$this;

        // Language object
        $Language = Container("language");

        // Table object (orders2)
        if (!isset($GLOBALS["orders2"]) || get_class($GLOBALS["orders2"]) == PROJECT_NAMESPACE . "orders2") {
            $GLOBALS["orders2"] = &$this;
        }

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
        $this->setupLookupOptions($this->CustomerID);
        $this->setupLookupOptions($this->EmployeeID);

        // Check modal
        if ($this->IsModal) {
            $SkipHeaderFooter = true;
        }
        $this->IsMobileOrModal = IsMobile() || $this->IsModal;
        $loaded = false;
        $postBack = false;

        // Set up current action and primary key
        if (IsApi()) {
            // Load key values
            $loaded = true;
            if (($keyValue = Get("OrderID") ?? Key(0) ?? Route(2)) !== null) {
                $this->OrderID->setQueryStringValue($keyValue);
                $this->OrderID->setOldValue($this->OrderID->QueryStringValue);
            } elseif (Post("OrderID") !== null) {
                $this->OrderID->setFormValue(Post("OrderID"));
                $this->OrderID->setOldValue($this->OrderID->FormValue);
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
                if (($keyValue = Get("OrderID") ?? Route("OrderID")) !== null) {
                    $this->OrderID->setQueryStringValue($keyValue);
                    $loadByQuery = true;
                } else {
                    $this->OrderID->CurrentValue = null;
                }
            }

            // Load recordset
            if ($this->isShow()) {
                    // Load current record
                    $loaded = $this->loadRow();
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
                    if (!$loaded) { // Load record based on key
                        if ($this->getFailureMessage() == "") {
                            $this->setFailureMessage($Language->phrase("NoRecord")); // No record found
                        }
                        $this->terminate("orders2list"); // No matching record, return to list
                        return;
                    }
                break;
            case "update": // Update
                $returnUrl = $this->getReturnUrl();
                if (GetPageName($returnUrl) == "orders2list") {
                    $returnUrl = $this->addMasterUrl($returnUrl); // List page, return to List page with correct master key if necessary
                }
                $this->SendEmail = true; // Send email on update success
                if ($this->editRow()) {
                    // Handle UseAjaxActions with return page
                    if ($this->IsModal && $this->UseAjaxActions) {
                        $this->IsModal = false;
                        if (GetPageName($returnUrl) != "orders2list") {
                            Container("flash")->addMessage("Return-Url", $returnUrl); // Save return URL
                            $returnUrl = "orders2list"; // Return list page content
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

        // Check field name 'OrderID' first before field var 'x_OrderID'
        $val = $CurrentForm->hasValue("OrderID") ? $CurrentForm->getValue("OrderID") : $CurrentForm->getValue("x_OrderID");
        if (!$this->OrderID->IsDetailKey) {
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

        // Check field name 'EmployeeID' first before field var 'x_EmployeeID'
        $val = $CurrentForm->hasValue("EmployeeID") ? $CurrentForm->getValue("EmployeeID") : $CurrentForm->getValue("x_EmployeeID");
        if (!$this->EmployeeID->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->EmployeeID->Visible = false; // Disable update for API request
            } else {
                $this->EmployeeID->setFormValue($val);
            }
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

        // Check field name 'ShipVia' first before field var 'x_ShipVia'
        $val = $CurrentForm->hasValue("ShipVia") ? $CurrentForm->getValue("ShipVia") : $CurrentForm->getValue("x_ShipVia");
        if (!$this->ShipVia->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->ShipVia->Visible = false; // Disable update for API request
            } else {
                $this->ShipVia->setFormValue($val, true, $validate);
            }
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

        // Check field name 'ShipName' first before field var 'x_ShipName'
        $val = $CurrentForm->hasValue("ShipName") ? $CurrentForm->getValue("ShipName") : $CurrentForm->getValue("x_ShipName");
        if (!$this->ShipName->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->ShipName->Visible = false; // Disable update for API request
            } else {
                $this->ShipName->setFormValue($val);
            }
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

        // Check field name 'ShipCity' first before field var 'x_ShipCity'
        $val = $CurrentForm->hasValue("ShipCity") ? $CurrentForm->getValue("ShipCity") : $CurrentForm->getValue("x_ShipCity");
        if (!$this->ShipCity->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->ShipCity->Visible = false; // Disable update for API request
            } else {
                $this->ShipCity->setFormValue($val);
            }
        }

        // Check field name 'ShipRegion' first before field var 'x_ShipRegion'
        $val = $CurrentForm->hasValue("ShipRegion") ? $CurrentForm->getValue("ShipRegion") : $CurrentForm->getValue("x_ShipRegion");
        if (!$this->ShipRegion->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->ShipRegion->Visible = false; // Disable update for API request
            } else {
                $this->ShipRegion->setFormValue($val);
            }
        }

        // Check field name 'ShipPostalCode' first before field var 'x_ShipPostalCode'
        $val = $CurrentForm->hasValue("ShipPostalCode") ? $CurrentForm->getValue("ShipPostalCode") : $CurrentForm->getValue("x_ShipPostalCode");
        if (!$this->ShipPostalCode->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->ShipPostalCode->Visible = false; // Disable update for API request
            } else {
                $this->ShipPostalCode->setFormValue($val);
            }
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
    }

    // Restore form values
    public function restoreFormValues()
    {
        global $CurrentForm;
        $this->OrderID->CurrentValue = $this->OrderID->FormValue;
        $this->CustomerID->CurrentValue = $this->CustomerID->FormValue;
        $this->EmployeeID->CurrentValue = $this->EmployeeID->FormValue;
        $this->OrderDate->CurrentValue = $this->OrderDate->FormValue;
        $this->OrderDate->CurrentValue = UnFormatDateTime($this->OrderDate->CurrentValue, $this->OrderDate->formatPattern());
        $this->RequiredDate->CurrentValue = $this->RequiredDate->FormValue;
        $this->RequiredDate->CurrentValue = UnFormatDateTime($this->RequiredDate->CurrentValue, $this->RequiredDate->formatPattern());
        $this->ShippedDate->CurrentValue = $this->ShippedDate->FormValue;
        $this->ShippedDate->CurrentValue = UnFormatDateTime($this->ShippedDate->CurrentValue, $this->ShippedDate->formatPattern());
        $this->ShipVia->CurrentValue = $this->ShipVia->FormValue;
        $this->Freight->CurrentValue = $this->Freight->FormValue;
        $this->ShipName->CurrentValue = $this->ShipName->FormValue;
        $this->ShipAddress->CurrentValue = $this->ShipAddress->FormValue;
        $this->ShipCity->CurrentValue = $this->ShipCity->FormValue;
        $this->ShipRegion->CurrentValue = $this->ShipRegion->FormValue;
        $this->ShipPostalCode->CurrentValue = $this->ShipPostalCode->FormValue;
        $this->ShipCountry->CurrentValue = $this->ShipCountry->FormValue;
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

            // ShipVia
            $this->ShipVia->HrefValue = "";

            // Freight
            $this->Freight->HrefValue = "";

            // ShipName
            $this->ShipName->HrefValue = "";

            // ShipAddress
            $this->ShipAddress->HrefValue = "";

            // ShipCity
            $this->ShipCity->HrefValue = "";

            // ShipRegion
            $this->ShipRegion->HrefValue = "";

            // ShipPostalCode
            $this->ShipPostalCode->HrefValue = "";

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

            // ShipVia
            $this->ShipVia->setupEditAttributes();
            $this->ShipVia->EditValue = HtmlEncode($this->ShipVia->CurrentValue);
            $this->ShipVia->PlaceHolder = RemoveHtml($this->ShipVia->caption());
            if (strval($this->ShipVia->EditValue) != "" && is_numeric($this->ShipVia->EditValue)) {
                $this->ShipVia->EditValue = $this->ShipVia->EditValue;
            }

            // Freight
            $this->Freight->setupEditAttributes();
            $this->Freight->EditValue = HtmlEncode($this->Freight->CurrentValue);
            $this->Freight->PlaceHolder = RemoveHtml($this->Freight->caption());
            if (strval($this->Freight->EditValue) != "" && is_numeric($this->Freight->EditValue)) {
                $this->Freight->EditValue = FormatNumber($this->Freight->EditValue, null);
            }

            // ShipName
            $this->ShipName->setupEditAttributes();
            if (!$this->ShipName->Raw) {
                $this->ShipName->CurrentValue = HtmlDecode($this->ShipName->CurrentValue);
            }
            $this->ShipName->EditValue = HtmlEncode($this->ShipName->CurrentValue);
            $this->ShipName->PlaceHolder = RemoveHtml($this->ShipName->caption());

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

            // ShipRegion
            $this->ShipRegion->setupEditAttributes();
            if (!$this->ShipRegion->Raw) {
                $this->ShipRegion->CurrentValue = HtmlDecode($this->ShipRegion->CurrentValue);
            }
            $this->ShipRegion->EditValue = HtmlEncode($this->ShipRegion->CurrentValue);
            $this->ShipRegion->PlaceHolder = RemoveHtml($this->ShipRegion->caption());

            // ShipPostalCode
            $this->ShipPostalCode->setupEditAttributes();
            if (!$this->ShipPostalCode->Raw) {
                $this->ShipPostalCode->CurrentValue = HtmlDecode($this->ShipPostalCode->CurrentValue);
            }
            $this->ShipPostalCode->EditValue = HtmlEncode($this->ShipPostalCode->CurrentValue);
            $this->ShipPostalCode->PlaceHolder = RemoveHtml($this->ShipPostalCode->caption());

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

            // ShipVia
            $this->ShipVia->HrefValue = "";

            // Freight
            $this->Freight->HrefValue = "";

            // ShipName
            $this->ShipName->HrefValue = "";

            // ShipAddress
            $this->ShipAddress->HrefValue = "";

            // ShipCity
            $this->ShipCity->HrefValue = "";

            // ShipRegion
            $this->ShipRegion->HrefValue = "";

            // ShipPostalCode
            $this->ShipPostalCode->HrefValue = "";

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
        if ($this->ShipVia->Visible && $this->ShipVia->Required) {
            if (!$this->ShipVia->IsDetailKey && EmptyValue($this->ShipVia->FormValue)) {
                $this->ShipVia->addErrorMessage(str_replace("%s", $this->ShipVia->caption(), $this->ShipVia->RequiredErrorMessage));
            }
        }
        if (!CheckInteger($this->ShipVia->FormValue)) {
            $this->ShipVia->addErrorMessage($this->ShipVia->getErrorMessage(false));
        }
        if ($this->Freight->Visible && $this->Freight->Required) {
            if (!$this->Freight->IsDetailKey && EmptyValue($this->Freight->FormValue)) {
                $this->Freight->addErrorMessage(str_replace("%s", $this->Freight->caption(), $this->Freight->RequiredErrorMessage));
            }
        }
        if (!CheckNumber($this->Freight->FormValue)) {
            $this->Freight->addErrorMessage($this->Freight->getErrorMessage(false));
        }
        if ($this->ShipName->Visible && $this->ShipName->Required) {
            if (!$this->ShipName->IsDetailKey && EmptyValue($this->ShipName->FormValue)) {
                $this->ShipName->addErrorMessage(str_replace("%s", $this->ShipName->caption(), $this->ShipName->RequiredErrorMessage));
            }
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
        if ($this->ShipRegion->Visible && $this->ShipRegion->Required) {
            if (!$this->ShipRegion->IsDetailKey && EmptyValue($this->ShipRegion->FormValue)) {
                $this->ShipRegion->addErrorMessage(str_replace("%s", $this->ShipRegion->caption(), $this->ShipRegion->RequiredErrorMessage));
            }
        }
        if ($this->ShipPostalCode->Visible && $this->ShipPostalCode->Required) {
            if (!$this->ShipPostalCode->IsDetailKey && EmptyValue($this->ShipPostalCode->FormValue)) {
                $this->ShipPostalCode->addErrorMessage(str_replace("%s", $this->ShipPostalCode->caption(), $this->ShipPostalCode->RequiredErrorMessage));
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

        // ShipVia
        $this->ShipVia->setDbValueDef($rsnew, $this->ShipVia->CurrentValue, $this->ShipVia->ReadOnly);

        // Freight
        $this->Freight->setDbValueDef($rsnew, $this->Freight->CurrentValue, $this->Freight->ReadOnly);

        // ShipName
        $this->ShipName->setDbValueDef($rsnew, $this->ShipName->CurrentValue, $this->ShipName->ReadOnly);

        // ShipAddress
        $this->ShipAddress->setDbValueDef($rsnew, $this->ShipAddress->CurrentValue, $this->ShipAddress->ReadOnly);

        // ShipCity
        $this->ShipCity->setDbValueDef($rsnew, $this->ShipCity->CurrentValue, $this->ShipCity->ReadOnly);

        // ShipRegion
        $this->ShipRegion->setDbValueDef($rsnew, $this->ShipRegion->CurrentValue, $this->ShipRegion->ReadOnly);

        // ShipPostalCode
        $this->ShipPostalCode->setDbValueDef($rsnew, $this->ShipPostalCode->CurrentValue, $this->ShipPostalCode->ReadOnly);

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

        // Write JSON response
        if (IsJsonResponse() && $editRow) {
            $row = $this->getRecordsFromRecordset([$rsnew], true);
            $table = $this->TableVar;
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
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("orders2list"), "", $this->TableVar, true);
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
