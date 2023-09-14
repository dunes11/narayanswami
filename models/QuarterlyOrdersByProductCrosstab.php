<?php

namespace PHPMaker2023\demo2023;

use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Page class
 */
class QuarterlyOrdersByProductCrosstab extends QuarterlyOrdersByProduct
{
    use MessagesTrait;

    // Page ID
    public $PageID = "crosstab";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Page object name
    public $PageObjName = "QuarterlyOrdersByProductCrosstab";

    // View file path
    public $View = null;

    // Title
    public $Title = null; // Title for <title> tag

    // Rendering View
    public $RenderingView = false;

    // CSS class/style
    public $ReportContainerClass = "ew-grid";
    public $CurrentPageName = "quarterlyordersbyproduct";

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
        $this->TableVar = 'Quarterly_Orders_By_Product';
        $this->TableName = 'Quarterly Orders By Product';

        // CSS class name as context
        $this->ContextClass = CheckClassName($this->TableVar);
        AppendClass($this->ReportContainerClass, $this->ContextClass);

        // Initialize
        $GLOBALS["Page"] = &$this;

        // Language object
        $Language = Container("language");

        // Table object (Quarterly_Orders_By_Product)
        if (!isset($GLOBALS["Quarterly_Orders_By_Product"]) || get_class($GLOBALS["Quarterly_Orders_By_Product"]) == PROJECT_NAMESPACE . "Quarterly_Orders_By_Product") {
            $GLOBALS["Quarterly_Orders_By_Product"] = &$this;
        }

        // Page URL
        $pageUrl = $this->pageUrl(false);

        // Initialize URLs

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'Quarterly Orders By Product');
        }

        // Start timer
        $DebugTimer = Container("timer");

        // Debug message
        LoadDebugMessage();

        // Open connection
        $GLOBALS["Conn"] ??= $this->getConnection();

        // User table object
        $UserTable = Container("usertable");

        // Export options
        $this->ExportOptions = new ListOptions(["TagClassName" => "ew-export-option"]);

        // Filter options
        $this->FilterOptions = new ListOptions(["TagClassName" => "ew-filter-option"]);
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
            SaveDebugMessage();
            Redirect(GetUrl($url));
        }
        return; // Return to controller
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

    // Options
    public $HideOptions = false;
    public $ExportOptions; // Export options
    public $SearchOptions; // Search options
    public $FilterOptions; // Filter options

    // Records
    public $GroupRecords = [];
    public $DetailRecords = [];
    public $DetailRecordCount = 0;

    // Paging variables
    public $RecordIndex = 0; // Record index
    public $RecordCount = 0; // Record count (start from 1 for each group)
    public $StartGroup = 0; // Start group
    public $StopGroup = 0; // Stop group
    public $TotalGroups = 0; // Total groups
    public $GroupCount = 0; // Group count
    public $DisplayGroups = 1; // Groups per page
    public $GroupRange = 10;
    public $PageSizes = ""; // Page sizes (comma separated)
    public $DefaultSearchWhere = ""; // Default search WHERE clause
    public $SearchWhere = "";
    public $SearchPanelClass = "ew-search-panel collapse show"; // Search Panel class
    public $SearchColumnCount = 0; // For extended search
    public $SearchFieldsPerRow = 1; // For extended search
    public $PageFirstGroupFilter = "";
    public $UserIDFilter = "";
    public $DrillDownList = "";
    public $DbMasterFilter = ""; // Master filter
    public $DbDetailFilter = ""; // Detail filter
    public $SearchCommand = false;
    public $ShowHeader = true;
    public $GroupColumnCount = 0;
    public $ColumnSpan;
    public $TopContentClass = "ew-top";
    public $MiddleContentClass = "ew-middle";
    public $BottomContentClass = "ew-bottom";

    /**
     * Page run
     *
     * @return void
     */
    public function run()
    {
        global $ExportType, $Language, $Security, $UserProfile,
            $Security, $DrillDownInPanel, $Breadcrumb,
            $DashboardReport;

        // Set up dashboard report
        $DashboardReport = $DashboardReport || ConvertToBool(Param(Config("PAGE_DASHBOARD"), false));
        if ($DashboardReport) {
            $this->UseAjaxActions = true;
        }

        // Use layout
        $this->UseLayout = $this->UseLayout && ConvertToBool(Param(Config("PAGE_LAYOUT"), true));

        // View
        $this->View = Get(Config("VIEW"));

        // Get export parameters
        $custom = "";
        if (Param("export") !== null) {
            $this->Export = Param("export");
            $custom = Param("custom", "");
        }
        $ExportType = $this->Export; // Get export parameter, used in header
        if ($ExportType != "") {
            global $SkipHeaderFooter;
            $SkipHeaderFooter = true;
        }
        $this->CurrentAction = Param("action"); // Set up current action

        // Setup export options
        $this->setupExportOptions();

        // Global Page Loading event (in userfn*.php)
        Page_Loading();

        // Page Load event
        if (method_exists($this, "pageLoad")) {
            $this->pageLoad();
        }

        // Setup other options
        $this->setupOtherOptions();

        // Set up table class
        if ($this->isExport("word") || $this->isExport("excel") || $this->isExport("pdf")) {
            $this->TableClass = "ew-table table-bordered table-sm";
        } else {
            PrependClass($this->TableClass, "table ew-table table-bordered table-sm");
        }

        // Set up report container class
        if (!$this->isExport("word") && !$this->isExport("excel")) {
            $this->ReportContainerClass .= " card ew-card";
        }

        // Set up Breadcrumb
        if (!$this->isExport() && !$DashboardReport) {
            $this->setupBreadcrumb();
        }

        // Get sort
        $this->Sort = $this->getSort();

        // Get chart sort
        $this->getChartSort();

        // Check if search command
        $this->SearchCommand = Get("cmd") == "search";

        // Load custom filters
        $this->pageFilterLoad();

        // Restore filter list
        $this->restoreFilterList();

        // Extended filter
        $extendedFilter = "";

        // Build extended filter
        $extendedFilter = $this->getExtendedFilter();
        AddFilter($this->SearchWhere, $extendedFilter);

        // Call Page Selecting event
        $this->pageSelecting($this->SearchWhere);

        // Load columns to array
        $this->getColumns();

        // Search options
        $this->setupSearchOptions();

        // Set up search panel class
        if ($this->SearchWhere != "") {
            AppendClass($this->SearchPanelClass, "show");
        }

        // Update filter
        AddFilter($this->Filter, $this->SearchWhere);

        // Get total group count
        $sql = $this->buildReportSql($this->getSqlSelectGroup(), $this->getSqlFrom(), $this->getSqlWhere(), $this->getSqlGroupBy(), "", "", $this->Filter, "");
        $this->TotalGroups = $this->getRecordCount($sql);
        if ($this->DisplayGroups <= 0 || $this->DrillDown) { // Display all groups
            $this->DisplayGroups = $this->TotalGroups;
        }
        $this->StartGroup = 1;

        // Set up start position if not export all
        if ($this->ExportAll && $this->isExport()) {
            $this->DisplayGroups = $this->TotalGroups;
        } else {
            $this->setupStartGroup();
        }

        // Set no record found message
        if ($this->TotalGroups == 0) {
            $this->ShowHeader = false;
            if ($Security->canList()) {
                if ($this->SearchWhere == "0=101") {
                    $this->setWarningMessage($Language->phrase("EnterSearchCriteria"));
                } else {
                    $this->setWarningMessage($Language->phrase("NoRecord"));
                }
            } else {
                $this->setWarningMessage(DeniedMessage());
            }
        }

        // Hide export options if export/dashboard report/hide options
        if ($this->isExport() || $DashboardReport || $this->HideOptions) {
            $this->ExportOptions->hideAllOptions();
        }

        // Hide search/filter options if export/drilldown/dashboard report/hide options
        if ($this->isExport() || $this->DrillDown || $DashboardReport || $this->HideOptions) {
            $this->SearchOptions->hideAllOptions();
            $this->FilterOptions->hideAllOptions();
        }

        // Get group records
        if ($this->TotalGroups > 0) {
            $grpSort = UpdateSortFields($this->getSqlOrderByGroup(), $this->Sort, 2); // Get grouping field only
            $sql = $this->buildReportSql($this->getSqlSelectGroup(), $this->getSqlFrom(), $this->getSqlWhere(), $this->getSqlGroupBy(), "", $this->getSqlOrderByGroup(), $this->Filter, $grpSort);
            $grpRs = $sql->setFirstResult(max($this->StartGroup - 1, 0))->setMaxResults($this->DisplayGroups)->execute();
            $this->GroupRecords = $grpRs->fetchAll(); // Get records of first groups
            $this->loadGroupRowValues();
            $this->GroupCount = 1;
        }

        // Init detail records
        $this->DetailRecords = [];

        // Set up column attributes
        $this->OrderDate->CssClass = "";
        $this->OrderDate->CellCssStyle = "";
        $this->setupFieldCount();

        // Set the last group to display if not export all
        if ($this->ExportAll && $this->isExport()) {
            $this->StopGroup = $this->TotalGroups;
        } else {
            $this->StopGroup = $this->StartGroup + $this->DisplayGroups - 1;
        }

        // Stop group <= total number of groups
        if (intval($this->StopGroup) > intval($this->TotalGroups)) {
            $this->StopGroup = $this->TotalGroups;
        }

        // Navigate
        $this->RecordCount = 0;
        $this->RecordIndex = 0;

        // Set up pager
        $this->Pager = new PrevNextPager($this, $this->StartGroup, $this->DisplayGroups, $this->TotalGroups, $this->PageSizes, $this->GroupRange, $this->AutoHidePager, $this->AutoHidePageSizeSelector);

        // Check if no records
        if ($this->TotalGroups == 0) {
            $this->ReportContainerClass .= " ew-no-record";
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

    // Load group row values
    public function loadGroupRowValues()
    {
        $cnt = count($this->GroupRecords); // Get record count
        if ($this->GroupCount < $cnt) {
            $this->CategoryName->setGroupValue(reset($this->GroupRecords[$this->GroupCount]));
        } else {
            $this->CategoryName->setGroupValue("");
        }
    }

    // Load row values
    public function loadRowValues($record)
    {
        $this->CategoryName->setDbValue($record['CategoryName']);
        $this->ProductName->setDbValue($record['ProductName']);
        $cntbase = 2;
        $cnt = count($this->SummaryFields);
        $record = array_values($record);
        for ($is = 0; $is < $cnt; $is++) {
            $smry = &$this->SummaryFields[$is];
            $cntval = count($smry->SummaryValues);
            for ($ix = 1; $ix < $cntval; $ix++) {
                if ($smry->SummaryType == "AVG") {
                    $smry->SummaryValues[$ix] = $record[$ix * 2 + $cntbase - 2];
                    $smry->SummaryValueCounts[$ix] = $record[$ix * 2 + $cntbase - 1];
                } else {
                    $smry->SummaryValues[$ix] = $record[$ix + $cntbase - 1];
                }
            }
            $cntbase += ($smry->SummaryType == "AVG") ? 2 * ($cntval - 1) : ($cntval - 1);
        }
    }

    // Get summary values from records
    public function getSummaryValues($records)
    {
        $colcnt = $this->ColumnCount;
        $cnt = count($this->SummaryFields);
        for ($is = 0; $is < $cnt; $is++) {
            $smry = &$this->SummaryFields[$is];
            $smry->SummaryGroupValues = InitArray($colcnt, null);
            $smry->SummaryGroupValueCounts = InitArray($colcnt, null);
        }
        foreach ($records as $record) {
            $record = array_values($record);
            $cntbase = 2;
            for ($is = 0; $is < $cnt; $is++) {
                $smry = &$this->SummaryFields[$is];
                $cntval = count($smry->SummaryValues);
                for ($ix = 1; $ix < $cntval; $ix++) {
                    if ($smry->SummaryType == "AVG") {
                        $thisval = $record[$ix * 2 + $cntbase - 2];
                        $thiscnt = $record[$ix * 2 + $cntbase - 1];
                    } else {
                        $thisval = $record[$ix + $cntbase - 1];
                    }
                    $smry->SummaryGroupValues[$ix - 1] = SummaryValue($smry->SummaryGroupValues[$ix - 1], $thisval, $smry->SummaryType);
                    if ($smry->SummaryType == "AVG") {
                        $smry->SummaryGroupValueCounts[$ix - 1] += $thiscnt;
                    }
                }
                $cntbase += ($smry->SummaryType == "AVG") ? 2 * ($cntval - 1) : ($cntval - 1);
            }
        }
    }

    // Render row
    public function renderRow()
    {
        global $Security, $Language;
        $conn = $this->getConnection();

        // Set up summary values
        if ($this->RowType != ROWTYPE_SEARCH) { // Skip for search row
            $colcnt = $this->ColumnCount + 1;
            $this->SummaryCellAttrs = InitArray($colcnt, null);
            $this->SummaryViewAttrs = InitArray($colcnt, null);
            $this->SummaryLinkAttrs = InitArray($colcnt, null);
            $this->SummaryCurrentValues = InitArray($colcnt, null);
            $this->SummaryViewValues = InitArray($colcnt, null);
            $cnt = count($this->SummaryFields);
            for ($is = 0; $is < $cnt; $is++) {
                $smry = &$this->SummaryFields[$is];
                $smry->SummaryViewAttrs = InitArray($colcnt, null);
                $smry->SummaryLinkAttrs = InitArray($colcnt, null);
                $smry->SummaryCurrentValues = InitArray($colcnt, null);
                $smry->SummaryViewValues = InitArray($colcnt, null);
                $smry->SummaryRowSummary = $smry->SummaryInitValue;
                $smry->SummaryRowCount = 0;
            }
        }
        if ($this->RowTotalType == ROWTOTAL_PAGE) {
            // Aggregate SQL (filter by group values)
            $firstGrpFld = &$this->CategoryName;
            $firstGrpFld->getDistinctValues($this->GroupRecords);
            $where = DetailFilterSql($firstGrpFld, $this->getSqlFirstGroupField(), $firstGrpFld->DistinctValues, $this->Dbid);
            if ($this->Filter != "") {
                $where = "($this->Filter) AND ($where)";
            }
            $qb = $this->buildReportSql($this->getSqlSelectAggregate()->addSelect($this->DistinctColumnFields), $this->getSqlFrom(), $this->getSqlWhere(), $this->getSqlGroupByAggregate(), "", "", $where, "");
            $rsagg = $qb->execute()->fetchNumeric();
        } else if ($this->RowTotalType == ROWTOTAL_GRAND) {
            // Aggregate SQL
            $qb = $this->buildReportSql($this->getSqlSelectAggregate()->addSelect($this->DistinctColumnFields), $this->getSqlFrom(), $this->getSqlWhere(), $this->getSqlGroupByAggregate(), "", "", $this->Filter, "");
            $rsagg = $qb->execute()->fetchNumeric();
        }
        if ($this->RowType != ROWTYPE_SEARCH) { // Skip for search row
            for ($i = 1; $i <= $this->ColumnCount; $i++) {
                if ($this->Columns[$i]->Visible) {
                    $cntbaseagg = 0;
                    $cnt = count($this->SummaryFields);
                    for ($is = 0; $is < $cnt; $is++) {
                        $smry = &$this->SummaryFields[$is];
                        if ($this->RowType == ROWTYPE_DETAIL) { // Detail row
                            $thisval = $smry->SummaryValues[$i];
                            if ($smry->SummaryType == "AVG") {
                                $thiscnt = $smry->SummaryValueCounts[$i];
                            }
                        } elseif ($this->RowTotalType == ROWTOTAL_GROUP) { // Group total
                            $thisval = $smry->SummaryGroupValues[$i - 1];
                            if ($smry->SummaryType == "AVG") {
                                $thiscnt = $smry->SummaryGroupValueCounts[$i - 1];
                            }
                        } elseif ($this->RowTotalType == ROWTOTAL_PAGE || $this->RowTotalType == ROWTOTAL_GRAND) { // Page Total / Grand total
                            if ($smry->SummaryType == "AVG") {
                                $thisval = $rsagg[$i * 2 + $cntbaseagg - 2] ?? 0;
                                $thiscnt = $rsagg[$i * 2 + $cntbaseagg - 1] ?? 0;
                                $cntbaseagg += $this->ColumnCount * 2;
                            } else {
                                $thisval = $rsagg[$i + $cntbaseagg - 1] ?? 0;
                                $cntbaseagg += $this->ColumnCount;
                            }
                        }
                        if ($smry->SummaryType == "AVG") {
                            $smry->SummaryCurrentValues[$i - 1] = ($thiscnt > 0) ? $thisval / $thiscnt : 0;
                        } else {
                            $smry->SummaryCurrentValues[$i - 1] = $thisval;
                        }
                        $smry->SummaryRowSummary = SummaryValue($smry->SummaryRowSummary, $thisval, $smry->SummaryType);
                        if ($smry->SummaryType == "AVG") {
                            $smry->SummaryRowCount += $thiscnt;
                        }
                    }
                }
            }
        }
        if ($this->RowType != ROWTYPE_SEARCH) { // Skip for search row
            $cnt = count($this->SummaryFields);
            for ($is = 0; $is < $cnt; $is++) {
                $smry = &$this->SummaryFields[$is];
                if ($smry->SummaryType == "AVG") {
                    $smry->SummaryCurrentValues[$this->ColumnCount] = ($smry->SummaryRowCount > 0) ? $smry->SummaryRowSummary / $smry->SummaryRowCount : 0;
                } else {
                    $smry->SummaryCurrentValues[$this->ColumnCount] = $smry->SummaryRowSummary;
                }
            }
        }

        // Call Row_Rendering event
        $this->rowRendering();
        if ($this->RowType == ROWTYPE_SEARCH) {
            // ProductName
            $this->ProductName->setupEditAttributes();
            if (!$this->ProductName->Raw) {
                $this->ProductName->AdvancedSearch->SearchValue = HtmlDecode($this->ProductName->AdvancedSearch->SearchValue);
            }
            $this->ProductName->EditValue = HtmlEncode($this->ProductName->AdvancedSearch->SearchValue);
            $this->ProductName->PlaceHolder = RemoveHtml($this->ProductName->caption());

            // OrderDate
            $this->OrderDate->setupEditAttributes();
            $this->OrderDate->EditValue = GetDropDownEditValue($this->OrderDate, $this->OrderDate->AdvancedSearch->SearchValue);
        } elseif ($this->RowType == ROWTYPE_TOTAL) {
            // CategoryName
            $this->CategoryName->GroupViewValue = $this->CategoryName->groupValue();
            $this->CategoryName->CellCssClass = ($this->RowGroupLevel == 1 ? "ew-rpt-grp-summary-1" : "ew-rpt-grp-field-1");

            // ProductName
            $this->ProductName->GroupViewValue = $this->ProductName->groupValue();
            $this->ProductName->CellCssClass = ($this->RowGroupLevel == 2 ? "ew-rpt-grp-summary-2" : "ew-rpt-grp-field-2");

            // Set up summary values
            $smry = &$this->SummaryFields[0];
            $scvcnt = count($smry->SummaryCurrentValues);
            for ($i = 0; $i < $scvcnt; $i++) {
                $smry->SummaryViewValues[$i] = FormatCurrency($smry->SummaryCurrentValues[$i], $this->Amount->formatPattern());
                $smry->SummaryViewAttrs[$i]["class"] = "";
                $this->SummaryCellAttrs[$i]["class"] = ($this->RowTotalType == ROWTOTAL_GROUP) ? "ew-rpt-grp-summary-" . $this->RowGroupLevel : "";
            }

            // CategoryName
            $this->CategoryName->HrefValue = "";

            // ProductName
            $this->ProductName->HrefValue = "";
        } else {
            // CategoryName
            $this->CategoryName->GroupViewValue = $this->CategoryName->groupValue();
            $this->CategoryName->CellCssClass = "ew-rpt-grp-field-1";
            if (!$this->CategoryName->LevelBreak) {
                $this->CategoryName->GroupViewValue = "";
            } else {
                $this->CategoryName->LevelBreak = false;
            }

            // ProductName
            $this->ProductName->GroupViewValue = $this->ProductName->groupValue();
            $this->ProductName->CellCssClass = "ew-rpt-grp-field-2";
            if (!$this->ProductName->LevelBreak) {
                $this->ProductName->GroupViewValue = "";
            } else {
                $this->ProductName->LevelBreak = false;
            }

            // Set up summary values
            $smry = &$this->SummaryFields[0];
            $scvcnt = count($smry->SummaryCurrentValues);
            for ($i = 0; $i < $scvcnt; $i++) {
                $smry->SummaryViewValues[$i] = FormatCurrency($smry->SummaryCurrentValues[$i], $this->Amount->formatPattern());
                $smry->SummaryViewAttrs[$i]["class"] = "";
                $this->SummaryCellAttrs[$i]["class"] = ($this->RecordCount % 2 != 1) ? "ew-table-alt-row" : "";
            }

            // CategoryName
            $this->CategoryName->HrefValue = "";
            $this->CategoryName->TooltipValue = "";

            // ProductName
            $this->ProductName->HrefValue = "";
            $this->ProductName->TooltipValue = "";
        }

        // Call Cell_Rendered event
        if ($this->RowType == ROWTYPE_TOTAL) {
            // CategoryName
            $this->CurrentIndex = 0; // Current index
            $currentValue = $this->CategoryName->groupValue();
            $viewValue = &$this->CategoryName->GroupViewValue;
            $viewAttrs = &$this->CategoryName->ViewAttrs;
            $cellAttrs = &$this->CategoryName->CellAttrs;
            $hrefValue = &$this->CategoryName->HrefValue;
            $linkAttrs = &$this->CategoryName->LinkAttrs;
            $this->cellRendered($this->CategoryName, $currentValue, $viewValue, $viewAttrs, $cellAttrs, $hrefValue, $linkAttrs);

            // ProductName
            $this->CurrentIndex = 1; // Current index
            $currentValue = $this->ProductName->groupValue();
            $viewValue = &$this->ProductName->GroupViewValue;
            $viewAttrs = &$this->ProductName->ViewAttrs;
            $cellAttrs = &$this->ProductName->CellAttrs;
            $hrefValue = &$this->ProductName->HrefValue;
            $linkAttrs = &$this->ProductName->LinkAttrs;
            $this->cellRendered($this->ProductName, $currentValue, $viewValue, $viewAttrs, $cellAttrs, $hrefValue, $linkAttrs);

            // Call Cell_Rendered for Summary fields
            $cnt = count($this->SummaryFields);
            for ($is = 0; $is < $cnt; $is++) {
                $smry = &$this->SummaryFields[$is];
                $scvcnt = count($smry->SummaryCurrentValues);
                for ($i = 0; $i < $scvcnt; $i++) {
                    $this->CurrentIndex = $i;
                    $currentValue = $smry->SummaryCurrentValues[$i];
                    $viewValue = &$smry->SummaryViewValues[$i];
                    $viewAttrs = &$smry->SummaryViewAttrs[$i];
                    $cellAttrs = &$this->SummaryCellAttrs[$i];
                    $hrefValue = "";
                    $linkAttrs = &$smry->SummaryLinkAttrs[$i];
                    $this->cellRendered($smry, $currentValue, $viewValue, $viewAttrs, $cellAttrs, $hrefValue, $linkAttrs);
                }
            }
        } elseif ($this->RowType == ROWTYPE_DETAIL) {
            // CategoryName
            $this->CurrentIndex = 0; // Group index
            $currentValue = $this->CategoryName->groupValue();
            $viewValue = &$this->CategoryName->GroupViewValue;
            $viewAttrs = &$this->CategoryName->ViewAttrs;
            $cellAttrs = &$this->CategoryName->CellAttrs;
            $hrefValue = &$this->CategoryName->HrefValue;
            $linkAttrs = &$this->CategoryName->LinkAttrs;
            $this->cellRendered($this->CategoryName, $currentValue, $viewValue, $viewAttrs, $cellAttrs, $hrefValue, $linkAttrs);

            // ProductName
            $this->CurrentIndex = 1; // Group index
            $currentValue = $this->ProductName->groupValue();
            $viewValue = &$this->ProductName->GroupViewValue;
            $viewAttrs = &$this->ProductName->ViewAttrs;
            $cellAttrs = &$this->ProductName->CellAttrs;
            $hrefValue = &$this->ProductName->HrefValue;
            $linkAttrs = &$this->ProductName->LinkAttrs;
            $this->cellRendered($this->ProductName, $currentValue, $viewValue, $viewAttrs, $cellAttrs, $hrefValue, $linkAttrs);
            $cnt = count($this->SummaryFields);
            for ($is = 0; $is < $cnt; $is++) {
                $smry = &$this->SummaryFields[$is];
                $scvcnt = count($smry->SummaryCurrentValues);
                for ($i = 0; $i < $scvcnt; $i++) {
                    $this->CurrentIndex = $i;
                    $currentValue = $smry->SummaryCurrentValues[$i];
                    $viewValue = &$smry->SummaryViewValues[$i];
                    $viewAttrs = &$smry->SummaryViewAttrs[$i];
                    $cellAttrs = &$this->SummaryCellAttrs[$i];
                    $hrefValue = "";
                    $linkAttrs = &$smry->SummaryLinkAttrs[$i];
                    $this->cellRendered($smry, $currentValue, $viewValue, $viewAttrs, $cellAttrs, $hrefValue, $linkAttrs);
                }
            }
        }

        // Call Row_Rendered event
        $this->rowRendered();
        $this->setupFieldCount();
    }

    // Setup field count
    protected function setupFieldCount()
    {
        $this->GroupColumnCount = 0;
        if ($this->CategoryName->Visible) {
            $this->GroupColumnCount += 1;
        }
        if ($this->ProductName->Visible) {
            $this->GroupColumnCount += 1;
        }
    }

    // Get column values
    protected function getColumns()
    {
        global $Language;

        // Load column values
        $filter = "";
        AddFilter($filter, $this->Filter);
        AddFilter($filter, $this->SearchWhere);
        $this->loadColumnValues($filter);

        // Get active columns
        $this->ColumnSpan = $this->ColumnCount;
        $this->ColumnSpan++; // Add summary column
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
            return '<button type="button" class="btn btn-default ew-export-link ew-excel" title="' . HtmlEncode($Language->phrase("ExportToExcel", true)) . '" data-caption="' . HtmlEncode($Language->phrase("ExportToExcel", true)) . '" data-ew-action="export" data-export="excel" data-custom="false" data-export-selected="false" data-url="' . $exportUrl . '">' . $Language->phrase("ExportToExcel") . '</button>';
        } elseif (SameText($type, "word")) {
            return '<button type="button" class="btn btn-default ew-export-link ew-word" title="' . HtmlEncode($Language->phrase("ExportToWord", true)) . '" data-caption="' . HtmlEncode($Language->phrase("ExportToWord", true)) . '" data-ew-action="export" data-export="word" data-custom="false" data-export-selected="false" data-url="' . $exportUrl . '">' . $Language->phrase("ExportToWord") . '</button>';
        } elseif (SameText($type, "pdf")) {
            return '<button type="button" class="btn btn-default ew-export-link ew-pdf" title="' . HtmlEncode($Language->phrase("ExportToPdf", true)) . '" data-caption="' . HtmlEncode($Language->phrase("ExportToPdf", true)) . '" data-ew-action="export" data-export="pdf" data-custom="false" data-export-selected="false" data-url="' . $exportUrl . '">' . $Language->phrase("ExportToPdf") . '</button>';
        } elseif (SameText($type, "html")) {
            return '<button type="button" class="btn btn-default ew-export-link ew-html" title="' . HtmlEncode($Language->phrase("ExportToHtml", true)) . '" data-caption="' . HtmlEncode($Language->phrase("ExportToHtml", true)) . '" data-ew-action="export" data-export="html" data-custom="false" data-export-selected="false" data-url="' . $exportUrl . '">' . $Language->phrase("ExportToHtml") . '</button>';
        } elseif (SameText($type, "email")) {
            return '<button type="button" class="btn btn-default ew-export-link ew-email" title="' . HtmlEncode($Language->phrase("ExportToEmail", true)) . '" data-caption="' . HtmlEncode($Language->phrase("ExportToEmail", true)) . '" data-ew-action="email" data-custom="false" data-export-selected="false" data-hdr="' . HtmlEncode($Language->phrase("ExportToEmail", true)) . '" data-url="' . $exportUrl . '">' . $Language->phrase("ExportToEmail") . '</button>';
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

        // Export to PDF
        $item = &$this->ExportOptions->add("pdf");
        $item->Body = $this->getExportTag("pdf");
        $item->Visible = true;

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

        // Hide options for export
        if ($this->isExport()) {
            $this->ExportOptions->hideAllOptions();
        }
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
        $item->Body = "<a class=\"btn btn-default ew-search-toggle" . $searchToggleClass . "\" role=\"button\" title=\"" . $Language->phrase("SearchPanel") . "\" data-caption=\"" . $Language->phrase("SearchPanel") . "\" data-ew-action=\"search-toggle\" data-form=\"fQuarterly_Orders_By_Productsrch\" aria-pressed=\"" . ($searchToggleClass == " active" ? "true" : "false") . "\">" . $Language->phrase("SearchLink") . "</a>";
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
        return $this->ProductName->Visible || $this->OrderDate->Visible;
    }

    // Render search options
    protected function renderSearchOptions()
    {
        if (!$this->hasSearchFields() && $this->SearchOptions["searchtoggle"]) {
            $this->SearchOptions["searchtoggle"]->Visible = false;
        }
    }

    // Set up Breadcrumb
    protected function setupBreadcrumb()
    {
        global $Breadcrumb, $Language;
        $Breadcrumb = new Breadcrumb("home");
        $url = CurrentUrl();
        $url = preg_replace('/\?cmd=reset(all){0,1}$/i', '', $url); // Remove cmd=reset(all)
        $Breadcrumb->add("crosstab", $this->TableVar, $url, "", $this->TableVar, true);
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
                case "x_OrderDate":
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

    // Get chart sort
    protected function getChartSort()
    {
        // Check for a resetsort command
        if (Get("cmd") !== null) {
            if (Get("cmd") == "resetsort") {
                $this->OrdersByCategory->setSort(0);
            }

        // Check for chartordertype parameter
        } elseif (Get("chartordertype") !== null && Route(1) !== null) {
            $chartorder = Route(1);
            $chartordertype = Get("chartordertype");
            if ($chartorder == "OrdersByCategory") {
                $this->OrdersByCategory->setSort($chartordertype);
            }
        }

        // Restore chart sort type from Session
        $this->OrdersByCategory->SortType = $this->OrdersByCategory->getSort();
    }

    // Set up other options
    protected function setupOtherOptions()
    {
        global $Language, $Security;

        // Filter button
        $item = &$this->FilterOptions->add("savecurrentfilter");
        $item->Body = "<a class=\"ew-save-filter\" data-form=\"fQuarterly_Orders_By_Productsrch\" data-ew-action=\"none\">" . $Language->phrase("SaveCurrentFilter") . "</a>";
        $item->Visible = true;
        $item = &$this->FilterOptions->add("deletefilter");
        $item->Body = "<a class=\"ew-delete-filter\" data-form=\"fQuarterly_Orders_By_Productsrch\" data-ew-action=\"none\">" . $Language->phrase("DeleteFilter") . "</a>";
        $item->Visible = true;
        $this->FilterOptions->UseDropDownButton = true;
        $this->FilterOptions->UseButtonGroup = !$this->FilterOptions->UseDropDownButton;
        $this->FilterOptions->DropDownButtonPhrase = $Language->phrase("Filters");

        // Add group option item
        $item = &$this->FilterOptions->addGroupOption();
        $item->Body = "";
        $item->Visible = false;
    }

    // Set up starting group
    protected function setupStartGroup()
    {
        // Exit if no groups
        if ($this->DisplayGroups == 0) {
            return;
        }
        $startGrp = Param(Config("TABLE_START_GROUP"));
        $pageNo = Param(Config("TABLE_PAGE_NUMBER"));

        // Check for a 'start' parameter
        if ($startGrp !== null) {
            $this->StartGroup = $startGrp;
            $this->setStartGroup($this->StartGroup);
        } elseif ($pageNo !== null) {
            $pageNo = ParseInteger($pageNo);
            if (is_numeric($pageNo)) {
                $this->StartGroup = ($pageNo - 1) * $this->DisplayGroups + 1;
                if ($this->StartGroup <= 0) {
                    $this->StartGroup = 1;
                } elseif ($this->StartGroup >= intval(($this->TotalGroups - 1) / $this->DisplayGroups) * $this->DisplayGroups + 1) {
                    $this->StartGroup = intval(($this->TotalGroups - 1) / $this->DisplayGroups) * $this->DisplayGroups + 1;
                }
                $this->setStartGroup($this->StartGroup);
            } else {
                $this->StartGroup = $this->getStartGroup();
            }
        } else {
            $this->StartGroup = $this->getStartGroup();
        }

        // Check if correct start group counter
        if (!is_numeric($this->StartGroup) || intval($this->StartGroup) <= 0) { // Avoid invalid start group counter
            $this->StartGroup = 1; // Reset start group counter
            $this->setStartGroup($this->StartGroup);
        } elseif (intval($this->StartGroup) > intval($this->TotalGroups)) { // Avoid starting group > total groups
            $this->StartGroup = intval(($this->TotalGroups - 1) / $this->DisplayGroups) * $this->DisplayGroups + 1; // Point to last page first group
            $this->setStartGroup($this->StartGroup);
        } elseif (($this->StartGroup - 1) % $this->DisplayGroups != 0) {
            $this->StartGroup = intval(($this->StartGroup - 1) / $this->DisplayGroups) * $this->DisplayGroups + 1; // Point to page boundary
            $this->setStartGroup($this->StartGroup);
        }
    }

    // Reset pager
    protected function resetPager()
    {
        // Reset start position (reset command)
        $this->StartGroup = 1;
        $this->setStartGroup($this->StartGroup);
    }

    // Get sort parameters based on sort links clicked
    protected function getSort()
    {
        if ($this->DrillDown) {
            return "CategoryName ASC";
        }
        $resetSort = Param("cmd") === "resetsort";
        $orderBy = Param("order", "");
        $orderType = Param("ordertype", "");

        // Check for a resetsort command
        if ($resetSort) {
            $this->setOrderBy("");
            $this->setStartGroup(1);
            $this->CategoryName->setSort("");
            $this->ProductName->setSort("");

        // Check for an Order parameter
        } elseif ($orderBy != "") {
            $this->CurrentOrder = $orderBy;
            $this->CurrentOrderType = $orderType;
            $this->updateSort($this->CategoryName); // CategoryName
            $this->updateSort($this->ProductName); // ProductName
            $sortSql = $this->sortSql();
            $this->setOrderBy($sortSql);
            $this->setStartGroup(1);
        }

        // Set up default sort
        if ($this->getOrderBy() == "") {
            $useDefaultSort = true;
            if ($useDefaultSort) {
                $this->setOrderBy("CategoryName ASC");
            }
        }
        return $this->getOrderBy();
    }

    // Return extended filter
    protected function getExtendedFilter()
    {
        $filter = "";
        if ($this->DrillDown) {
            return "";
        }
        $restoreSession = false;
        $restoreDefault = false;
        // Reset search command
        if (Get("cmd") == "reset") {
            // Set default values
            $this->ProductName->AdvancedSearch->unsetSession();
            $this->OrderDate->AdvancedSearch->unsetSession();
            $restoreDefault = true;
        } else {
            $restoreSession = !$this->SearchCommand;

            // Field ProductName
            if ($this->ProductName->AdvancedSearch->get()) {
            }

            // Field OrderDate
            $this->getDropDownValue($this->OrderDate);
            if (!$this->validateForm()) {
                return $filter;
            }
        }

        // Restore session
        if ($restoreSession) {
            $restoreDefault = true;
            if ($this->ProductName->AdvancedSearch->issetSession()) { // Field ProductName
                $this->ProductName->AdvancedSearch->load();
                $restoreDefault = false;
            }
            if ($this->OrderDate->AdvancedSearch->issetSession()) { // Field OrderDate
                $this->OrderDate->AdvancedSearch->load();
                $restoreDefault = false;
            }
        }

        // Restore default
        if ($restoreDefault) {
            $this->loadDefaultFilters();
        }

        // Call page filter validated event
        $this->pageFilterValidated();

        // Build SQL and save to session
        $this->buildExtendedFilter($this->ProductName, $filter, false, true); // Field ProductName
        $this->ProductName->AdvancedSearch->save();
        $this->buildDropDownFilter($this->OrderDate, $filter, false, true); // Field OrderDate
        $this->OrderDate->AdvancedSearch->save();

        // Field OrderDate
        LoadDropDownList($this->OrderDate->EditValue, $this->OrderDate->AdvancedSearch->SearchValue);
        return $filter;
    }

    // Build dropdown filter
    protected function buildDropDownFilter(&$fld, &$filterClause, $default = false, $saveFilter = false)
    {
        $fldVal = $default ? $fld->AdvancedSearch->SearchValueDefault : $fld->AdvancedSearch->SearchValue;
        $fldOpr = $default ? $fld->AdvancedSearch->SearchOperatorDefault : $fld->AdvancedSearch->SearchOperator;
        $fldVal2 = $default ? $fld->AdvancedSearch->SearchValue2Default : $fld->AdvancedSearch->SearchValue2;
        if (!EmptyValue($fld->DateFilter)) {
            $fldOpr = $fld->DateFilter;
            $fldVal2 = "";
        } elseif ($fld->UseFilter) {
            $fldOpr = "";
            $fldVal2 = "";
        }
        $sql = "";
        if (is_array($fldVal)) {
            foreach ($fldVal as $val) {
                $wrk = $this->getDropDownFilter($fld, $val, $fldOpr);
                if ($wrk != "") {
                    if ($sql != "") {
                        $sql .= " OR " . $wrk;
                    } else {
                        $sql = $wrk;
                    }
                }
            }
        } else {
            $sql = $this->getDropDownFilter($fld, $fldVal, $fldOpr, $fldVal2);
        }
        if ($sql != "") {
            $cond = SameText($this->SearchOption, "OR") ? "OR" : "AND";
            AddFilter($filterClause, $sql, $cond);
            if ($saveFilter) {
                $fld->CurrentFilter = $sql;
            }
        }
    }

    /**
     * Get dropdown filter
     *
     * @param ReportField $fld Report field object
     * @param string $fldVal Filter value
     * @param string $fldOpr Filter operator
     * @param string $fldVal2 Filter value 2
     * @return string WHERE clause
     */
    protected function getDropDownFilter($fld, $fldVal, $fldOpr, $fldVal2 = "")
    {
        $fldName = $fld->Name;
        $fldExpression = $fld->Expression;
        $fldDataType = $fld->DataType;
        $fldOpr = $fldOpr ?: "=";
        $fldVal = ConvertSearchValue($fldVal, $fldOpr, $fld);
        $wrk = "";
        if (SameString($fldVal, Config("NULL_VALUE"))) {
            $wrk = $fldExpression . " IS NULL";
        } elseif (SameString($fldVal, Config("NOT_NULL_VALUE"))) {
            $wrk = $fldExpression . " IS NOT NULL";
        } elseif (SameString($fldVal, Config("EMPTY_VALUE"))) {
            $wrk = $fldExpression . " = ''";
        } elseif (SameString($fldVal, Config("ALL_VALUE"))) {
            $wrk = "1 = 1";
        } else {
            if ($fld->GroupSql != "") { // Use grouping SQL for search if exists
                $fldExpression = str_replace("%s", $fldExpression, $fld->GroupSql);
            }
            if (StartsString("@@", $fldVal)) {
                $wrk = $this->getCustomFilter($fld, $fldVal, $this->Dbid);
            } elseif (($fld->isMultiSelect() || $fld->UseFilter) && IsMultiSearchOperator($fldOpr) && !EmptyValue($fldVal) && $fldVal != Config("INIT_VALUE")) {
                $wrk = GetMultiSearchSql($fld, $fldOpr, trim($fldVal), $this->Dbid);
            } elseif ($fldOpr == "BETWEEN" && !EmptyValue($fldVal) && $fldVal != Config("INIT_VALUE") && !EmptyValue($fldVal2) && $fldVal2 != Config("INIT_VALUE")) {
                $wrk = $fldExpression ." " . $fldOpr . " " . QuotedValue($fldVal, $fldDataType, $this->Dbid) . " AND " . QuotedValue($fldVal2, $fldDataType, $this->Dbid);
            } else {
                if ($fldVal != "" && $fldVal != Config("INIT_VALUE")) {
                    if ($fldDataType == DATATYPE_DATE && $fldOpr != "") {
                        $wrk = GetDateFilterSql($fld->Expression, $fldOpr, $fldVal, $fldDataType, $this->Dbid);
                    } else {
                        $wrk = SearchFilter($fldExpression, $fldOpr, $fldVal, $fldDataType, $this->Dbid);
                    }
                }
            }
        }

        // Call Page Filtering event
        if (!StartsString("@@", $fldVal)) {
            $this->pageFiltering($fld, $wrk, "dropdown", $fldOpr, $fldVal, "", "", $fldVal2);
        }
        return $wrk;
    }

    // Get custom filter
    protected function getCustomFilter(&$fld, $fldVal, $dbid = 0)
    {
        $wrk = "";
        if (is_array($fld->AdvancedFilters)) {
            foreach ($fld->AdvancedFilters as $filter) {
                if ($filter->ID == $fldVal && $filter->Enabled) {
                    $fldExpr = $fld->Expression;
                    $fn = $filter->FunctionName;
                    $wrkid = StartsString("@@", $filter->ID) ? substr($filter->ID, 2) : $filter->ID;
                    $fn = $fn != "" && !function_exists($fn) ? PROJECT_NAMESPACE . $fn : $fn;
                    if (function_exists($fn)) {
                        $wrk = $fn($fldExpr, $dbid);
                    } else {
                        $wrk = "";
                    }
                    $this->pageFiltering($fld, $wrk, "custom", $wrkid);
                    break;
                }
            }
        }
        return $wrk;
    }

    // Build extended filter
    protected function buildExtendedFilter(&$fld, &$filterClause, $default = false, $saveFilter = false)
    {
        $wrk = GetExtendedFilter($fld, $default, $this->Dbid);
        if (!$default) {
            $this->pageFiltering($fld, $wrk, "extended", $fld->AdvancedSearch->SearchOperator, $fld->AdvancedSearch->SearchValue, $fld->AdvancedSearch->SearchCondition, $fld->AdvancedSearch->SearchOperator2, $fld->AdvancedSearch->SearchValue2);
        }
        if ($wrk != "") {
            $cond = SameText($this->SearchOption, "OR") ? "OR" : "AND";
            AddFilter($filterClause, $wrk, $cond);
            if ($saveFilter) {
                $fld->CurrentFilter = $wrk;
            }
        }
    }

    // Get drop down value from querystring
    protected function getDropDownValue(&$fld)
    {
        if (IsPost()) {
            return false; // Skip post back
        }
        $res = false;
        $parm = $fld->Param;
        $opr = Get("z_$parm");
        if ($opr !== null) {
            $fld->AdvancedSearch->SearchOperator = $opr;
        }
        $val = Get("x_$parm");
        if ($val !== null) {
            if (is_array($val)) {
                $val = implode(Config("MULTIPLE_OPTION_SEPARATOR"), $val);
            }
            $fld->AdvancedSearch->setSearchValue($val);
            $res = true;
        }
        $val2 = Get("y_$parm");
        if ($val2 !== null) {
            if (is_array($val2)) {
                $val2 = implode(Config("MULTIPLE_OPTION_SEPARATOR"), $val2);
            }
            $fld->AdvancedSearch->setSearchValue2($val2);
            $res = true;
        }
        return $res;
    }

    // Dropdown filter exist
    protected function dropDownFilterExist(&$fld)
    {
        $wrk = "";
        $this->buildDropDownFilter($fld, $wrk);
        return ($wrk != "");
    }

    // Extended filter exist
    protected function extendedFilterExist(&$fld)
    {
        $extWrk = "";
        $this->buildExtendedFilter($fld, $extWrk);
        return ($extWrk != "");
    }

    // Validate form
    protected function validateForm()
    {
        global $Language;

        // Check if validation required
        if (!Config("SERVER_VALIDATE")) {
            return true;
        }

        // Return validate result
        $validateForm = !$this->hasInvalidFields();

        // Call Form_CustomValidate event
        $formCustomError = "";
        $validateForm = $validateForm && $this->formCustomValidate($formCustomError);
        if ($formCustomError != "") {
            $this->setFailureMessage($formCustomError);
        }
        return $validateForm;
    }

    // Load default value for filters
    protected function loadDefaultFilters()
    {
        // Field ProductName
        $this->ProductName->AdvancedSearch->loadDefault();

        // Field OrderDate
        $this->OrderDate->AdvancedSearch->loadDefault();
    }

    // Show list of filters
    public function showFilterList()
    {
        global $Language;

        // Initialize
        $filterList = "";
        $captionClass = $this->isExport("email") ? "ew-filter-caption-email" : "ew-filter-caption";
        $captionSuffix = $this->isExport("email") ? ": " : "";

        // Field ProductName
        $extWrk = "";
        $this->buildExtendedFilter($this->ProductName, $extWrk);
        $filter = "";
        if ($extWrk != "") {
            $filter .= "<span class=\"ew-filter-value\">$extWrk</span>";
        }
        if ($filter != "") {
            $filterList .= "<div><span class=\"" . $captionClass . "\">" . $this->ProductName->caption() . "</span>" . $captionSuffix . $filter . "</div>";
        }

        // Field OrderDate
        $extWrk = "";
        $this->buildDropDownFilter($this->OrderDate, $extWrk);
        $filter = "";
        if ($extWrk != "") {
            $filter .= "<span class=\"ew-filter-value\">$extWrk</span>";
        }
        if ($filter != "") {
            $filterList .= "<div><span class=\"" . $captionClass . "\">" . $this->OrderDate->caption() . "</span>" . $captionSuffix . $filter . "</div>";
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

    // Get list of filters
    public function getFilterList()
    {
        global $UserProfile;

        // Initialize
        $filterList = "";
        $savedFilterList = "";

        // Field ProductName
        $wrk = "";
        if ($this->ProductName->AdvancedSearch->SearchValue != "" || $this->ProductName->AdvancedSearch->SearchValue2 != "") {
            $wrk = "\"x_ProductName\":\"" . JsEncode($this->ProductName->AdvancedSearch->SearchValue) . "\"," .
                "\"z_ProductName\":\"" . JsEncode($this->ProductName->AdvancedSearch->SearchOperator) . "\"," .
                "\"v_ProductName\":\"" . JsEncode($this->ProductName->AdvancedSearch->SearchCondition) . "\"," .
                "\"y_ProductName\":\"" . JsEncode($this->ProductName->AdvancedSearch->SearchValue2) . "\"," .
                "\"w_ProductName\":\"" . JsEncode($this->ProductName->AdvancedSearch->SearchOperator2) . "\"";
        }
        if ($wrk != "") {
            if ($filterList != "") {
                $filterList .= ",";
            }
            $filterList .= $wrk;
        }

        // Field OrderDate
        $wrk = "";
        $wrk = ($this->OrderDate->AdvancedSearch->SearchValue != Config("INIT_VALUE")) ? $this->OrderDate->AdvancedSearch->SearchValue : "";
        if (is_array($wrk)) {
            $wrk = implode("||", $wrk);
        }
        if ($wrk != "") {
            $wrk = "\"x_OrderDate\":\"" . JsEncode($wrk) . "\"";
        }
        if ($wrk != "") {
            if ($filterList != "") {
                $filterList .= ",";
            }
            $filterList .= $wrk;
        }

        // Return filter list in json
        if ($filterList != "") {
            $filterList = "\"data\":{" . $filterList . "}";
        }
        if ($savedFilterList != "") {
            $filterList = Concat($filterList, "\"filters\":" . $savedFilterList, ",");
        }
        return ($filterList != "") ? "{" . $filterList . "}" : "null";
    }

    // Restore list of filters
    protected function restoreFilterList()
    {
        // Return if not reset filter
        if (Post("cmd", "") != "resetfilter") {
            return false;
        }
        $filter = json_decode(Post("filter", ""), true);
        return $this->setupFilterList($filter);
    }

    // Setup list of filters
    protected function setupFilterList($filter)
    {
        if (!is_array($filter)) {
            return false;
        }

        // Field ProductName
        if (!$this->ProductName->AdvancedSearch->get($filter)) {
            $this->ProductName->AdvancedSearch->loadDefault(); // Clear filter
        }
        $this->ProductName->AdvancedSearch->save();

        // Field OrderDate
        if (!$this->OrderDate->AdvancedSearch->get($filter)) {
            $this->OrderDate->AdvancedSearch->loadDefault(); // Clear filter
        }
        $this->OrderDate->AdvancedSearch->save();
        return true;
    }

    /**
     * Get drill down SQL
     *
     * @param ReportField $fld Source field object
     * @param string $target Target field name
     * @param int $rowtype Row type
     * 0 = detail
     * 1 = group
     * 2 = page
     * 3 = grand
     * @param int $parm Filter/Column index
     * -1 = use field filter value / current/old value
     * 0 = use grouping/column field value
     * > 0 = use column index
     * @return string Drill down SQL
     */
    public function getDrillDownSql($fld, $target, $rowtype, $parm = 0)
    {
        $sql = "";

        // Handle grand/page total
        if ($fld->Param == "CategoryName") { // First grouping field
            if ($rowtype == ROWTOTAL_GRAND) { // Grand total
                $sql = $fld->CurrentFilter;
                if ($sql == "") {
                    $sql = "1=1"; // Show all records
                }
            } elseif ($rowtype == ROWTOTAL_PAGE && $this->PageFirstGroupFilter != "") { // Page total
                $sql = str_replace($fld->Expression, "@" . $target, "(" . $this->PageFirstGroupFilter . ")");
            }
        }

        // Handle group/row/column field
        if ($parm >= 0 && $sql == "") {
            switch ($fld->Param) {
                case "CategoryName":
                    if ($fld->GroupSql != "") {
                        $sql = str_replace("%s", "@" . $target, $fld->GroupSql) . " = " . QuotedValue($fld->CurrentValue, DATATYPE_STRING, $this->Dbid);
                        AddFilter($sql, str_replace($fld->Expression, "@" . $target, $fld->CurrentFilter));
                    } else {
                        $sql = "@" . $target . " = " . QuotedValue($fld->CurrentValue, $fld->DataType, $this->Dbid);
                    }
                    break;
                case "OrderDate":
                    if (!EmptyValue($fld->AdvancedSearch->SearchValue) && !SameString($fld->AdvancedSearch->SearchValue, Config("INIT_VALUE"))) {
                        $sql = "YEAR(%s)";
                        $sql = str_replace("%s", "@" . $target, $sql) . " = " . QuotedValue($fld->AdvancedSearch->SearchValue, DATATYPE_NUMBER, $this->Dbid);
                    }
                    break;
            }
        }
        // Detail field
        if ($sql == "" && $rowtype == 0) {
            if ($fld->CurrentFilter != "") { // Use current filter
                $sql = str_replace($fld->Expression, "@" . $target, $fld->CurrentFilter);
            } elseif ($fld->CurrentValue != "") { // Use current value for detail row
                $sql = "@" . $target . "=" . QuotedValue($fld->CurrentValue, $fld->DataType, $this->Dbid);
            }
        }
        return $sql;
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

    // Page Selecting event
    public function pageSelecting(&$filter)
    {
        // Enter your code here
    }

    // Load Filters event
    public function pageFilterLoad()
    {
        // Enter your code here
        // Example: Register/Unregister Custom Extended Filter
        //RegisterFilter($this-><Field>, 'StartsWithA', 'Starts With A', 'GetStartsWithAFilter'); // With function, or
        //RegisterFilter($this-><Field>, 'StartsWithA', 'Starts With A'); // No function, use Page_Filtering event
        //UnregisterFilter($this-><Field>, 'StartsWithA');
    }

    // Page Filter Validated event
    public function pageFilterValidated()
    {
        // Example:
        //$this->MyField1->AdvancedSearch->SearchValue = "your search criteria"; // Search value
    }

    // Page Filtering event
    public function pageFiltering(&$fld, &$filter, $typ, $opr = "", $val = "", $cond = "", $opr2 = "", $val2 = "")
    {
        // Note: ALWAYS CHECK THE FILTER TYPE ($typ)! Example:
        //if ($typ == "dropdown" && $fld->Name == "MyField") // Dropdown filter
        //    $filter = "..."; // Modify the filter
        //if ($typ == "extended" && $fld->Name == "MyField") // Extended filter
        //    $filter = "..."; // Modify the filter
        //if ($typ == "custom" && $opr == "..." && $fld->Name == "MyField") // Custom filter, $opr is the custom filter ID
        //    $filter = "..."; // Modify the filter
    }

    // Cell Rendered event
    public function cellRendered(&$Field, $CurrentValue, &$ViewValue, &$ViewAttrs, &$CellAttrs, &$HrefValue, &$LinkAttrs)
    {
        //$ViewValue = "xxx";
        //$ViewAttrs["class"] = "xxx";
    }

    // Form Custom Validate event
    public function formCustomValidate(&$customError)
    {
        // Return error message in $customError
        return true;
    }
}
