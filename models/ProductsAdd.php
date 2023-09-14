<?php

namespace PHPMaker2023\demo2023;

use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Page class
 */
class ProductsAdd extends Products
{
    use MessagesTrait;

    // Page ID
    public $PageID = "add";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Page object name
    public $PageObjName = "ProductsAdd";

    // View file path
    public $View = null;

    // Title
    public $Title = null; // Title for <title> tag

    // Rendering View
    public $RenderingView = false;

    // CSS class/style
    public $CurrentPageName = "productsadd";

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
        $this->ProductID->Visible = false;
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
        $this->TableVar = 'products';
        $this->TableName = 'products';

        // Table CSS class
        $this->TableClass = "table table-striped table-bordered table-hover table-sm ew-desktop-table ew-add-table";

        // Initialize
        $GLOBALS["Page"] = &$this;

        // Language object
        $Language = Container("language");

        // Table object (products)
        if (!isset($GLOBALS["products"]) || get_class($GLOBALS["products"]) == PROJECT_NAMESPACE . "products") {
            $GLOBALS["products"] = &$this;
        }

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
                    $result["view"] = $pageName == "productsview"; // If View page, no primary button
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

        // Set up lookup cache
        $this->setupLookupOptions($this->SupplierID);
        $this->setupLookupOptions($this->CategoryID);
        $this->setupLookupOptions($this->Discontinued);

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
            if (($keyValue = Get("ProductID") ?? Route("ProductID")) !== null) {
                $this->ProductID->setQueryStringValue($keyValue);
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

        // Set up master/detail parameters
        // NOTE: Must be after loadOldRecord to prevent master key values being overwritten
        $this->setupMasterParms();

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
                    $this->terminate("productslist"); // No matching record, return to list
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
                    if (GetPageName($returnUrl) == "productslist") {
                        $returnUrl = $this->addMasterUrl($returnUrl); // List page, return to List page with correct master key if necessary
                    } elseif (GetPageName($returnUrl) == "productsview") {
                        $returnUrl = $this->getViewUrl(); // View page, return to View page with keyurl directly
                    }

                    // Handle UseAjaxActions
                    if ($this->IsModal && $this->UseAjaxActions) {
                        $this->IsModal = false;
                        if (GetPageName($returnUrl) != "productslist") {
                            Container("flash")->addMessage("Return-Url", $returnUrl); // Save return URL
                            $returnUrl = "productslist"; // Return list page content
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
        $validate = !Config("SERVER_VALIDATE");

        // Check field name 'ProductName' first before field var 'x_ProductName'
        $val = $CurrentForm->hasValue("ProductName") ? $CurrentForm->getValue("ProductName") : $CurrentForm->getValue("x_ProductName");
        if (!$this->ProductName->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->ProductName->Visible = false; // Disable update for API request
            } else {
                $this->ProductName->setFormValue($val);
            }
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

        // Check field name 'CategoryID' first before field var 'x_CategoryID'
        $val = $CurrentForm->hasValue("CategoryID") ? $CurrentForm->getValue("CategoryID") : $CurrentForm->getValue("x_CategoryID");
        if (!$this->CategoryID->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->CategoryID->Visible = false; // Disable update for API request
            } else {
                $this->CategoryID->setFormValue($val);
            }
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

        // Check field name 'UnitPrice' first before field var 'x_UnitPrice'
        $val = $CurrentForm->hasValue("UnitPrice") ? $CurrentForm->getValue("UnitPrice") : $CurrentForm->getValue("x_UnitPrice");
        if (!$this->UnitPrice->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->UnitPrice->Visible = false; // Disable update for API request
            } else {
                $this->UnitPrice->setFormValue($val, true, $validate);
            }
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

        // Check field name 'UnitsOnOrder' first before field var 'x_UnitsOnOrder'
        $val = $CurrentForm->hasValue("UnitsOnOrder") ? $CurrentForm->getValue("UnitsOnOrder") : $CurrentForm->getValue("x_UnitsOnOrder");
        if (!$this->UnitsOnOrder->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->UnitsOnOrder->Visible = false; // Disable update for API request
            } else {
                $this->UnitsOnOrder->setFormValue($val, true, $validate);
            }
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

        // Check field name 'Discontinued' first before field var 'x_Discontinued'
        $val = $CurrentForm->hasValue("Discontinued") ? $CurrentForm->getValue("Discontinued") : $CurrentForm->getValue("x_Discontinued");
        if (!$this->Discontinued->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->Discontinued->Visible = false; // Disable update for API request
            } else {
                $this->Discontinued->setFormValue($val);
            }
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

        // Check field name 'ProductID' first before field var 'x_ProductID'
        $val = $CurrentForm->hasValue("ProductID") ? $CurrentForm->getValue("ProductID") : $CurrentForm->getValue("x_ProductID");
    }

    // Restore form values
    public function restoreFormValues()
    {
        global $CurrentForm;
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

        // Call Row_Rendering event
        $this->rowRendering();

        // Common render codes for all row types

        // ProductID
        $this->ProductID->RowCssClass = "row";

        // ProductName
        $this->ProductName->RowCssClass = "row";

        // SupplierID
        $this->SupplierID->RowCssClass = "row";

        // CategoryID
        $this->CategoryID->RowCssClass = "row";

        // QuantityPerUnit
        $this->QuantityPerUnit->RowCssClass = "row";

        // UnitPrice
        $this->UnitPrice->RowCssClass = "row";

        // UnitsInStock
        $this->UnitsInStock->RowCssClass = "row";

        // UnitsOnOrder
        $this->UnitsOnOrder->RowCssClass = "row";

        // ReorderLevel
        $this->ReorderLevel->RowCssClass = "row";

        // Discontinued
        $this->Discontinued->RowCssClass = "row";

        // EAN13
        $this->EAN13->RowCssClass = "row";

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
        } elseif ($this->RowType == ROWTYPE_ADD) {
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

    // Add record
    protected function addRow($rsold = null)
    {
        global $Language, $Security;

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

        // Write JSON response
        if (IsJsonResponse() && $addRow) {
            $row = $this->getRecordsFromRecordset([$rsnew], true);
            $table = $this->TableVar;
            WriteJson(["success" => true, "action" => Config("API_ADD_ACTION"), $table => $row]);
        }
        return $addRow;
    }

    // Set up master/detail based on QueryString
    protected function setupMasterParms()
    {
        $validMaster = false;
        $foreignKeys = [];
        // Get the keys for master table
        if (($master = Get(Config("TABLE_SHOW_MASTER"), Get(Config("TABLE_MASTER")))) !== null) {
            $masterTblVar = $master;
            if ($masterTblVar == "") {
                $validMaster = true;
                $this->DbMasterFilter = "";
                $this->DbDetailFilter = "";
            }
            if ($masterTblVar == "categories") {
                $validMaster = true;
                $masterTbl = Container("categories");
                if (($parm = Get("fk_CategoryID", Get("CategoryID"))) !== null) {
                    $masterTbl->CategoryID->setQueryStringValue($parm);
                    $this->CategoryID->QueryStringValue = $masterTbl->CategoryID->QueryStringValue; // DO NOT change, master/detail key data type can be different
                    $this->CategoryID->setSessionValue($this->CategoryID->QueryStringValue);
                    $foreignKeys["CategoryID"] = $this->CategoryID->QueryStringValue;
                    if (!is_numeric($masterTbl->CategoryID->QueryStringValue)) {
                        $validMaster = false;
                    }
                } else {
                    $validMaster = false;
                }
            }
        } elseif (($master = Post(Config("TABLE_SHOW_MASTER"), Post(Config("TABLE_MASTER")))) !== null) {
            $masterTblVar = $master;
            if ($masterTblVar == "") {
                    $validMaster = true;
                    $this->DbMasterFilter = "";
                    $this->DbDetailFilter = "";
            }
            if ($masterTblVar == "categories") {
                $validMaster = true;
                $masterTbl = Container("categories");
                if (($parm = Post("fk_CategoryID", Post("CategoryID"))) !== null) {
                    $masterTbl->CategoryID->setFormValue($parm);
                    $this->CategoryID->FormValue = $masterTbl->CategoryID->FormValue;
                    $this->CategoryID->setSessionValue($this->CategoryID->FormValue);
                    $foreignKeys["CategoryID"] = $this->CategoryID->FormValue;
                    if (!is_numeric($masterTbl->CategoryID->FormValue)) {
                        $validMaster = false;
                    }
                } else {
                    $validMaster = false;
                }
            }
        }
        if ($validMaster) {
            // Save current master table
            $this->setCurrentMasterTable($masterTblVar);

            // Reset start record counter (new master key)
            if (!$this->isAddOrEdit() && !$this->isGridUpdate()) {
                $this->StartRecord = 1;
                $this->setStartRecordNumber($this->StartRecord);
            }

            // Clear previous master key from Session
            if ($masterTblVar != "categories") {
                if (!array_key_exists("CategoryID", $foreignKeys)) { // Not current foreign key
                    $this->CategoryID->setSessionValue("");
                }
            }
        }
        $this->DbMasterFilter = $this->getMasterFilterFromSession(); // Get master filter from session
        $this->DbDetailFilter = $this->getDetailFilterFromSession(); // Get detail filter from session
    }

    // Set up Breadcrumb
    protected function setupBreadcrumb()
    {
        global $Breadcrumb, $Language;
        $Breadcrumb = new Breadcrumb("home");
        $url = CurrentUrl();
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("productslist"), "", $this->TableVar, true);
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
