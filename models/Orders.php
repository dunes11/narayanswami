<?php

namespace PHPMaker2023\demo2023;

use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Table class for orders
 */
class Orders extends DbTable
{
    protected $SqlFrom = "";
    protected $SqlSelect = null;
    protected $SqlSelectList = null;
    protected $SqlWhere = "";
    protected $SqlGroupBy = "";
    protected $SqlHaving = "";
    protected $SqlOrderBy = "";
    public $DbErrorMessage = "";
    public $UseSessionForListSql = true;

    // Column CSS classes
    public $LeftColumnClass = "col-sm-2 col-form-label ew-label";
    public $RightColumnClass = "col-sm-10";
    public $OffsetColumnClass = "col-sm-10 offset-sm-2";
    public $TableLeftColumnClass = "w-col-2";

    // Ajax / Modal
    public $UseAjaxActions = false;
    public $ModalSearch = false;
    public $ModalView = false;
    public $ModalAdd = false;
    public $ModalEdit = false;
    public $ModalUpdate = false;
    public $InlineDelete = true;
    public $ModalGridAdd = false;
    public $ModalGridEdit = false;
    public $ModalMultiEdit = false;

    // Fields
    public $OrderID;
    public $CustomerID;
    public $EmployeeID;
    public $OrderDate;
    public $RequiredDate;
    public $ShippedDate;
    public $ShipVia;
    public $Freight;
    public $ShipName;
    public $ShipAddress;
    public $ShipCity;
    public $ShipRegion;
    public $ShipPostalCode;
    public $ShipCountry;

    // Page ID
    public $PageID = ""; // To be overridden by subclass

    // Constructor
    public function __construct()
    {
        parent::__construct();
        global $Language, $CurrentLanguage, $CurrentLocale;

        // Language object
        $Language = Container("language");
        $this->TableVar = "orders";
        $this->TableName = 'orders';
        $this->TableType = "TABLE";
        $this->ImportUseTransaction = $this->supportsTransaction() && Config("IMPORT_USE_TRANSACTION");
        $this->UseTransaction = $this->supportsTransaction() && Config("USE_TRANSACTION");

        // Update Table
        $this->UpdateTable = "orders";
        $this->Dbid = 'DB';
        $this->ExportAll = false;
        $this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)

        // PDF
        $this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
        $this->ExportPageSize = "a4"; // Page size (PDF only)

        // PhpSpreadsheet
        $this->ExportExcelPageOrientation = null; // Page orientation (PhpSpreadsheet only)
        $this->ExportExcelPageSize = null; // Page size (PhpSpreadsheet only)

        // PHPWord
        $this->ExportWordPageOrientation = ""; // Page orientation (PHPWord only)
        $this->ExportWordPageSize = ""; // Page orientation (PHPWord only)
        $this->ExportWordColumnWidth = null; // Cell width (PHPWord only)
        $this->DetailAdd = false; // Allow detail add
        $this->DetailEdit = false; // Allow detail edit
        $this->DetailView = true; // Allow detail view
        $this->ShowMultipleDetails = true; // Show multiple details
        $this->GridAddRowCount = 5;
        $this->AllowAddDeleteRow = true; // Allow add/delete row
        $this->UseAjaxActions = $this->UseAjaxActions || Config("USE_AJAX_ACTIONS");
        $this->BasicSearch = new BasicSearch($this);

        // OrderID
        $this->OrderID = new DbField(
            $this, // Table
            'x_OrderID', // Variable name
            'OrderID', // Name
            '`OrderID`', // Expression
            '`OrderID`', // Basic search expression
            3, // Type
            11, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`OrderID`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'NO' // Edit Tag
        );
        $this->OrderID->InputTextType = "text";
        $this->OrderID->IsAutoIncrement = true; // Autoincrement field
        $this->OrderID->IsPrimaryKey = true; // Primary key field
        $this->OrderID->IsForeignKey = true; // Foreign key field
        $this->OrderID->Sortable = false; // Allow sort
        $this->OrderID->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->OrderID->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['OrderID'] = &$this->OrderID;

        // CustomerID
        $this->CustomerID = new DbField(
            $this, // Table
            'x_CustomerID', // Variable name
            'CustomerID', // Name
            '`CustomerID`', // Expression
            '`CustomerID`', // Basic search expression
            200, // Type
            5, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`CustomerID`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'SELECT' // Edit Tag
        );
        $this->CustomerID->InputTextType = "text";
        $this->CustomerID->IsForeignKey = true; // Foreign key field
        $this->CustomerID->Sortable = false; // Allow sort
        $this->CustomerID->setSelectMultiple(false); // Select one
        $this->CustomerID->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->CustomerID->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->CustomerID->Lookup = new Lookup('CustomerID', 'customers', false, 'CustomerID', ["CompanyName","CustomerID","",""], '', '', [], [], [], [], [], [], '', '<div>{{:df}} <em>({{:df2}})</em></div>', "CONCAT(COALESCE(`CompanyName`, ''),'" . ValueSeparator(1, $this->CustomerID) . "',COALESCE(`CustomerID`,''))");
        $this->CustomerID->SearchOperators = ["=", "<>", "IS NULL", "IS NOT NULL"];
        $this->Fields['CustomerID'] = &$this->CustomerID;

        // EmployeeID
        $this->EmployeeID = new DbField(
            $this, // Table
            'x_EmployeeID', // Variable name
            'EmployeeID', // Name
            '`EmployeeID`', // Expression
            '`EmployeeID`', // Basic search expression
            3, // Type
            11, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`EmployeeID`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'SELECT' // Edit Tag
        );
        $this->EmployeeID->InputTextType = "text";
        $this->EmployeeID->Sortable = false; // Allow sort
        $this->EmployeeID->setSelectMultiple(false); // Select one
        $this->EmployeeID->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->EmployeeID->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->EmployeeID->Lookup = new Lookup('EmployeeID', 'employees', false, 'EmployeeID', ["LastName","FirstName","",""], '', '', [], [], [], [], [], [], '', '', "CONCAT(COALESCE(`LastName`, ''),'" . ValueSeparator(1, $this->EmployeeID) . "',COALESCE(`FirstName`,''))");
        $this->EmployeeID->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->EmployeeID->SearchOperators = ["=", "<>", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['EmployeeID'] = &$this->EmployeeID;

        // OrderDate
        $this->OrderDate = new DbField(
            $this, // Table
            'x_OrderDate', // Variable name
            'OrderDate', // Name
            '`OrderDate`', // Expression
            CastDateFieldForLike("`OrderDate`", 0, "DB"), // Basic search expression
            135, // Type
            19, // Size
            0, // Date/Time format
            false, // Is upload field
            '`OrderDate`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->OrderDate->InputTextType = "text";
        $this->OrderDate->DefaultErrorMessage = str_replace("%s", $GLOBALS["DATE_FORMAT"], $Language->phrase("IncorrectDate"));
        $this->OrderDate->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['OrderDate'] = &$this->OrderDate;

        // RequiredDate
        $this->RequiredDate = new DbField(
            $this, // Table
            'x_RequiredDate', // Variable name
            'RequiredDate', // Name
            '`RequiredDate`', // Expression
            CastDateFieldForLike("`RequiredDate`", 0, "DB"), // Basic search expression
            135, // Type
            19, // Size
            0, // Date/Time format
            false, // Is upload field
            '`RequiredDate`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->RequiredDate->InputTextType = "text";
        $this->RequiredDate->Sortable = false; // Allow sort
        $this->RequiredDate->DefaultErrorMessage = str_replace("%s", $GLOBALS["DATE_FORMAT"], $Language->phrase("IncorrectDate"));
        $this->RequiredDate->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['RequiredDate'] = &$this->RequiredDate;

        // ShippedDate
        $this->ShippedDate = new DbField(
            $this, // Table
            'x_ShippedDate', // Variable name
            'ShippedDate', // Name
            '`ShippedDate`', // Expression
            CastDateFieldForLike("`ShippedDate`", 0, "DB"), // Basic search expression
            135, // Type
            19, // Size
            0, // Date/Time format
            false, // Is upload field
            '`ShippedDate`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->ShippedDate->InputTextType = "text";
        $this->ShippedDate->Sortable = false; // Allow sort
        $this->ShippedDate->DefaultErrorMessage = str_replace("%s", $GLOBALS["DATE_FORMAT"], $Language->phrase("IncorrectDate"));
        $this->ShippedDate->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['ShippedDate'] = &$this->ShippedDate;

        // ShipVia
        $this->ShipVia = new DbField(
            $this, // Table
            'x_ShipVia', // Variable name
            'ShipVia', // Name
            '`ShipVia`', // Expression
            '`ShipVia`', // Basic search expression
            3, // Type
            11, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`ShipVia`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->ShipVia->InputTextType = "text";
        $this->ShipVia->Sortable = false; // Allow sort
        $this->ShipVia->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->ShipVia->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['ShipVia'] = &$this->ShipVia;

        // Freight
        $this->Freight = new DbField(
            $this, // Table
            'x_Freight', // Variable name
            'Freight', // Name
            '`Freight`', // Expression
            '`Freight`', // Basic search expression
            5, // Type
            22, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`Freight`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->Freight->InputTextType = "text";
        $this->Freight->Sortable = false; // Allow sort
        $this->Freight->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->Freight->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['Freight'] = &$this->Freight;

        // ShipName
        $this->ShipName = new DbField(
            $this, // Table
            'x_ShipName', // Variable name
            'ShipName', // Name
            '`ShipName`', // Expression
            '`ShipName`', // Basic search expression
            200, // Type
            40, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`ShipName`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->ShipName->InputTextType = "text";
        $this->ShipName->Sortable = false; // Allow sort
        $this->ShipName->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['ShipName'] = &$this->ShipName;

        // ShipAddress
        $this->ShipAddress = new DbField(
            $this, // Table
            'x_ShipAddress', // Variable name
            'ShipAddress', // Name
            '`ShipAddress`', // Expression
            '`ShipAddress`', // Basic search expression
            200, // Type
            60, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`ShipAddress`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->ShipAddress->InputTextType = "text";
        $this->ShipAddress->Sortable = false; // Allow sort
        $this->ShipAddress->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['ShipAddress'] = &$this->ShipAddress;

        // ShipCity
        $this->ShipCity = new DbField(
            $this, // Table
            'x_ShipCity', // Variable name
            'ShipCity', // Name
            '`ShipCity`', // Expression
            '`ShipCity`', // Basic search expression
            200, // Type
            15, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`ShipCity`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->ShipCity->InputTextType = "text";
        $this->ShipCity->Sortable = false; // Allow sort
        $this->ShipCity->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['ShipCity'] = &$this->ShipCity;

        // ShipRegion
        $this->ShipRegion = new DbField(
            $this, // Table
            'x_ShipRegion', // Variable name
            'ShipRegion', // Name
            '`ShipRegion`', // Expression
            '`ShipRegion`', // Basic search expression
            200, // Type
            15, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`ShipRegion`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->ShipRegion->InputTextType = "text";
        $this->ShipRegion->Sortable = false; // Allow sort
        $this->ShipRegion->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['ShipRegion'] = &$this->ShipRegion;

        // ShipPostalCode
        $this->ShipPostalCode = new DbField(
            $this, // Table
            'x_ShipPostalCode', // Variable name
            'ShipPostalCode', // Name
            '`ShipPostalCode`', // Expression
            '`ShipPostalCode`', // Basic search expression
            200, // Type
            10, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`ShipPostalCode`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->ShipPostalCode->InputTextType = "text";
        $this->ShipPostalCode->Sortable = false; // Allow sort
        $this->ShipPostalCode->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['ShipPostalCode'] = &$this->ShipPostalCode;

        // ShipCountry
        $this->ShipCountry = new DbField(
            $this, // Table
            'x_ShipCountry', // Variable name
            'ShipCountry', // Name
            '`ShipCountry`', // Expression
            '`ShipCountry`', // Basic search expression
            200, // Type
            15, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`ShipCountry`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->ShipCountry->InputTextType = "text";
        $this->ShipCountry->Sortable = false; // Allow sort
        $this->ShipCountry->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['ShipCountry'] = &$this->ShipCountry;

        // Add Doctrine Cache
        $this->Cache = new ArrayCache();
        $this->CacheProfile = new \Doctrine\DBAL\Cache\QueryCacheProfile(0, $this->TableVar);

        // Call Table Load event
        $this->tableLoad();
    }

    // Field Visibility
    public function getFieldVisibility($fldParm)
    {
        global $Security;
        return $this->$fldParm->Visible; // Returns original value
    }

    // Set left column class (must be predefined col-*-* classes of Bootstrap grid system)
    public function setLeftColumnClass($class)
    {
        if (preg_match('/^col\-(\w+)\-(\d+)$/', $class, $match)) {
            $this->LeftColumnClass = $class . " col-form-label ew-label";
            $this->RightColumnClass = "col-" . $match[1] . "-" . strval(12 - (int)$match[2]);
            $this->OffsetColumnClass = $this->RightColumnClass . " " . str_replace("col-", "offset-", $class);
            $this->TableLeftColumnClass = preg_replace('/^col-\w+-(\d+)$/', "w-col-$1", $class); // Change to w-col-*
        }
    }

    // Single column sort
    public function updateSort(&$fld)
    {
        if ($this->CurrentOrder == $fld->Name) {
            $sortField = $fld->Expression;
            $lastSort = $fld->getSort();
            if (in_array($this->CurrentOrderType, ["ASC", "DESC", "NO"])) {
                $curSort = $this->CurrentOrderType;
            } else {
                $curSort = $lastSort;
            }
            $orderBy = in_array($curSort, ["ASC", "DESC"]) ? $sortField . " " . $curSort : "";
            $this->setSessionOrderBy($orderBy); // Save to Session
        }
    }

    // Update field sort
    public function updateFieldSort()
    {
        $orderBy = $this->getSessionOrderBy(); // Get ORDER BY from Session
        $flds = GetSortFields($orderBy);
        foreach ($this->Fields as $field) {
            $fldSort = "";
            foreach ($flds as $fld) {
                if ($fld[0] == $field->Expression || $fld[0] == $field->VirtualExpression) {
                    $fldSort = $fld[1];
                }
            }
            $field->setSort($fldSort);
        }
    }

    // Current master table name
    public function getCurrentMasterTable()
    {
        return Session(PROJECT_NAME . "_" . $this->TableVar . "_" . Config("TABLE_MASTER_TABLE"));
    }

    public function setCurrentMasterTable($v)
    {
        $_SESSION[PROJECT_NAME . "_" . $this->TableVar . "_" . Config("TABLE_MASTER_TABLE")] = $v;
    }

    // Get master WHERE clause from session values
    public function getMasterFilterFromSession()
    {
        // Master filter
        $masterFilter = "";
        if ($this->getCurrentMasterTable() == "customers") {
            $masterTable = Container("customers");
            if ($this->CustomerID->getSessionValue() != "") {
                $masterFilter .= "" . GetKeyFilter($masterTable->CustomerID, $this->CustomerID->getSessionValue(), $masterTable->CustomerID->DataType, $masterTable->Dbid);
            } else {
                return "";
            }
        }
        return $masterFilter;
    }

    // Get detail WHERE clause from session values
    public function getDetailFilterFromSession()
    {
        // Detail filter
        $detailFilter = "";
        if ($this->getCurrentMasterTable() == "customers") {
            $masterTable = Container("customers");
            if ($this->CustomerID->getSessionValue() != "") {
                $detailFilter .= "" . GetKeyFilter($this->CustomerID, $this->CustomerID->getSessionValue(), $masterTable->CustomerID->DataType, $this->Dbid);
            } else {
                return "";
            }
        }
        return $detailFilter;
    }

    /**
     * Get master filter
     *
     * @param object $masterTable Master Table
     * @param array $keys Detail Keys
     * @return mixed NULL is returned if all keys are empty, Empty string is returned if some keys are empty and is required
     */
    public function getMasterFilter($masterTable, $keys)
    {
        $validKeys = true;
        switch ($masterTable->TableVar) {
            case "customers":
                $key = $keys["CustomerID"] ?? "";
                if (EmptyValue($key)) {
                    if ($masterTable->CustomerID->Required) { // Required field and empty value
                        return ""; // Return empty filter
                    }
                    $validKeys = false;
                } elseif (!$validKeys) { // Already has empty key
                    return ""; // Return empty filter
                }
                if ($validKeys) {
                    return GetKeyFilter($masterTable->CustomerID, $keys["CustomerID"], $this->CustomerID->DataType, $this->Dbid);
                }
                break;
        }
        return null; // All null values and no required fields
    }

    // Get detail filter
    public function getDetailFilter($masterTable)
    {
        switch ($masterTable->TableVar) {
            case "customers":
                return GetKeyFilter($this->CustomerID, $masterTable->CustomerID->DbValue, $masterTable->CustomerID->DataType, $masterTable->Dbid);
        }
        return "";
    }

    // Current detail table name
    public function getCurrentDetailTable()
    {
        return Session(PROJECT_NAME . "_" . $this->TableVar . "_" . Config("TABLE_DETAIL_TABLE")) ?? "";
    }

    public function setCurrentDetailTable($v)
    {
        $_SESSION[PROJECT_NAME . "_" . $this->TableVar . "_" . Config("TABLE_DETAIL_TABLE")] = $v;
    }

    // Get detail url
    public function getDetailUrl()
    {
        // Detail url
        $detailUrl = "";
        if ($this->getCurrentDetailTable() == "orderdetails") {
            $detailUrl = Container("orderdetails")->getListUrl() . "?" . Config("TABLE_SHOW_MASTER") . "=" . $this->TableVar;
            $detailUrl .= "&" . GetForeignKeyUrl("fk_OrderID", $this->OrderID->CurrentValue);
        }
        if ($this->getCurrentDetailTable() == "order_details_extended") {
            $detailUrl = Container("order_details_extended")->getListUrl() . "?" . Config("TABLE_SHOW_MASTER") . "=" . $this->TableVar;
            $detailUrl .= "&" . GetForeignKeyUrl("fk_OrderID", $this->OrderID->CurrentValue);
        }
        if ($detailUrl == "") {
            $detailUrl = "orderslist";
        }
        return $detailUrl;
    }

    // Render X Axis for chart
    public function renderChartXAxis($chartVar, $chartRow)
    {
        return $chartRow;
    }

    // Table level SQL
    public function getSqlFrom() // From
    {
        return ($this->SqlFrom != "") ? $this->SqlFrom : "orders";
    }

    public function sqlFrom() // For backward compatibility
    {
        return $this->getSqlFrom();
    }

    public function setSqlFrom($v)
    {
        $this->SqlFrom = $v;
    }

    public function getSqlSelect() // Select
    {
        return $this->SqlSelect ?? $this->getQueryBuilder()->select("*");
    }

    public function sqlSelect() // For backward compatibility
    {
        return $this->getSqlSelect();
    }

    public function setSqlSelect($v)
    {
        $this->SqlSelect = $v;
    }

    public function getSqlWhere() // Where
    {
        $where = ($this->SqlWhere != "") ? $this->SqlWhere : "";
        $this->DefaultFilter = "";
        AddFilter($where, $this->DefaultFilter);
        return $where;
    }

    public function sqlWhere() // For backward compatibility
    {
        return $this->getSqlWhere();
    }

    public function setSqlWhere($v)
    {
        $this->SqlWhere = $v;
    }

    public function getSqlGroupBy() // Group By
    {
        return ($this->SqlGroupBy != "") ? $this->SqlGroupBy : "";
    }

    public function sqlGroupBy() // For backward compatibility
    {
        return $this->getSqlGroupBy();
    }

    public function setSqlGroupBy($v)
    {
        $this->SqlGroupBy = $v;
    }

    public function getSqlHaving() // Having
    {
        return ($this->SqlHaving != "") ? $this->SqlHaving : "";
    }

    public function sqlHaving() // For backward compatibility
    {
        return $this->getSqlHaving();
    }

    public function setSqlHaving($v)
    {
        $this->SqlHaving = $v;
    }

    public function getSqlOrderBy() // Order By
    {
        return ($this->SqlOrderBy != "") ? $this->SqlOrderBy : "";
    }

    public function sqlOrderBy() // For backward compatibility
    {
        return $this->getSqlOrderBy();
    }

    public function setSqlOrderBy($v)
    {
        $this->SqlOrderBy = $v;
    }

    // Apply User ID filters
    public function applyUserIDFilters($filter, $id = "")
    {
        global $Security;
        // Add User ID filter
        if ($Security->currentUserID() != "" && !$Security->isAdmin()) { // Non system admin
            $filter = $this->addUserIDFilter($filter, $id);
        }
        return $filter;
    }

    // Check if User ID security allows view all
    public function userIDAllow($id = "")
    {
        $allow = $this->UserIDAllowSecurity;
        switch ($id) {
            case "add":
            case "copy":
            case "gridadd":
            case "register":
            case "addopt":
                return (($allow & 1) == 1);
            case "edit":
            case "gridedit":
            case "update":
            case "changepassword":
            case "resetpassword":
                return (($allow & 4) == 4);
            case "delete":
                return (($allow & 2) == 2);
            case "view":
                return (($allow & 32) == 32);
            case "search":
                return (($allow & 64) == 64);
            case "lookup":
                return (($allow & 256) == 256);
            default:
                return (($allow & 8) == 8);
        }
    }

    /**
     * Get record count
     *
     * @param string|QueryBuilder $sql SQL or QueryBuilder
     * @param mixed $c Connection
     * @return int
     */
    public function getRecordCount($sql, $c = null)
    {
        $cnt = -1;
        $rs = null;
        if ($sql instanceof QueryBuilder) { // Query builder
            $sqlwrk = clone $sql;
            $sqlwrk = $sqlwrk->resetQueryPart("orderBy")->getSQL();
        } else {
            $sqlwrk = $sql;
        }
        $pattern = '/^SELECT\s([\s\S]+)\sFROM\s/i';
        // Skip Custom View / SubQuery / SELECT DISTINCT / ORDER BY
        if (
            ($this->TableType == 'TABLE' || $this->TableType == 'VIEW' || $this->TableType == 'LINKTABLE') &&
            preg_match($pattern, $sqlwrk) && !preg_match('/\(\s*(SELECT[^)]+)\)/i', $sqlwrk) &&
            !preg_match('/^\s*select\s+distinct\s+/i', $sqlwrk) && !preg_match('/\s+order\s+by\s+/i', $sqlwrk)
        ) {
            $sqlwrk = "SELECT COUNT(*) FROM " . preg_replace($pattern, "", $sqlwrk);
        } else {
            $sqlwrk = "SELECT COUNT(*) FROM (" . $sqlwrk . ") COUNT_TABLE";
        }
        $conn = $c ?? $this->getConnection();
        $cnt = $conn->fetchOne($sqlwrk);
        if ($cnt !== false) {
            return (int)$cnt;
        }

        // Unable to get count by SELECT COUNT(*), execute the SQL to get record count directly
        return ExecuteRecordCount($sql, $conn);
    }

    // Get SQL
    public function getSql($where, $orderBy = "")
    {
        return $this->getSqlAsQueryBuilder($where, $orderBy)->getSQL();
    }

    // Get QueryBuilder
    public function getSqlAsQueryBuilder($where, $orderBy = "")
    {
        return $this->buildSelectSql(
            $this->getSqlSelect(),
            $this->getSqlFrom(),
            $this->getSqlWhere(),
            $this->getSqlGroupBy(),
            $this->getSqlHaving(),
            $this->getSqlOrderBy(),
            $where,
            $orderBy
        );
    }

    // Table SQL
    public function getCurrentSql()
    {
        $filter = $this->CurrentFilter;
        $filter = $this->applyUserIDFilters($filter);
        $sort = $this->getSessionOrderBy();
        return $this->getSql($filter, $sort);
    }

    /**
     * Table SQL with List page filter
     *
     * @return QueryBuilder
     */
    public function getListSql()
    {
        $filter = $this->UseSessionForListSql ? $this->getSessionWhere() : "";
        AddFilter($filter, $this->CurrentFilter);
        $filter = $this->applyUserIDFilters($filter);
        $this->recordsetSelecting($filter);
        $select = $this->getSqlSelect();
        $from = $this->getSqlFrom();
        $sort = $this->UseSessionForListSql ? $this->getSessionOrderBy() : "";
        $this->Sort = $sort;
        return $this->buildSelectSql(
            $select,
            $from,
            $this->getSqlWhere(),
            $this->getSqlGroupBy(),
            $this->getSqlHaving(),
            $this->getSqlOrderBy(),
            $filter,
            $sort
        );
    }

    // Get ORDER BY clause
    public function getOrderBy()
    {
        $orderBy = $this->getSqlOrderBy();
        $sort = $this->getSessionOrderBy();
        if ($orderBy != "" && $sort != "") {
            $orderBy .= ", " . $sort;
        } elseif ($sort != "") {
            $orderBy = $sort;
        }
        return $orderBy;
    }

    // Get record count based on filter (for detail record count in master table pages)
    public function loadRecordCount($filter)
    {
        $origFilter = $this->CurrentFilter;
        $this->CurrentFilter = $filter;
        $this->recordsetSelecting($this->CurrentFilter);
        $select = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlSelect() : $this->getQueryBuilder()->select("*");
        $groupBy = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlGroupBy() : "";
        $having = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlHaving() : "";
        $sql = $this->buildSelectSql($select, $this->getSqlFrom(), $this->getSqlWhere(), $groupBy, $having, "", $this->CurrentFilter, "");
        $cnt = $this->getRecordCount($sql);
        $this->CurrentFilter = $origFilter;
        return $cnt;
    }

    // Get record count (for current List page)
    public function listRecordCount()
    {
        $filter = $this->getSessionWhere();
        AddFilter($filter, $this->CurrentFilter);
        $filter = $this->applyUserIDFilters($filter);
        $this->recordsetSelecting($filter);
        $select = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlSelect() : $this->getQueryBuilder()->select("*");
        $groupBy = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlGroupBy() : "";
        $having = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlHaving() : "";
        $sql = $this->buildSelectSql($select, $this->getSqlFrom(), $this->getSqlWhere(), $groupBy, $having, "", $filter, "");
        $cnt = $this->getRecordCount($sql);
        return $cnt;
    }

    /**
     * INSERT statement
     *
     * @param mixed $rs
     * @return QueryBuilder
     */
    public function insertSql(&$rs)
    {
        $queryBuilder = $this->getQueryBuilder();
        $queryBuilder->insert($this->UpdateTable);
        foreach ($rs as $name => $value) {
            if (!isset($this->Fields[$name]) || $this->Fields[$name]->IsCustom) {
                continue;
            }
            $type = GetParameterType($this->Fields[$name], $value, $this->Dbid);
            $queryBuilder->setValue($this->Fields[$name]->Expression, $queryBuilder->createPositionalParameter($value, $type));
        }
        return $queryBuilder;
    }

    // Insert
    public function insert(&$rs)
    {
        $conn = $this->getConnection();
        try {
            $success = $this->insertSql($rs)->execute();
            $this->DbErrorMessage = "";
        } catch (\Exception $e) {
            $success = false;
            $this->DbErrorMessage = $e->getMessage();
        }
        if ($success) {
            // Get insert id if necessary
            $this->OrderID->setDbValue($conn->lastInsertId());
            $rs['OrderID'] = $this->OrderID->DbValue;
        }
        return $success;
    }

    /**
     * UPDATE statement
     *
     * @param array $rs Data to be updated
     * @param string|array $where WHERE clause
     * @param string $curfilter Filter
     * @return QueryBuilder
     */
    public function updateSql(&$rs, $where = "", $curfilter = true)
    {
        $queryBuilder = $this->getQueryBuilder();
        $queryBuilder->update($this->UpdateTable);
        foreach ($rs as $name => $value) {
            if (!isset($this->Fields[$name]) || $this->Fields[$name]->IsCustom || $this->Fields[$name]->IsAutoIncrement) {
                continue;
            }
            $type = GetParameterType($this->Fields[$name], $value, $this->Dbid);
            $queryBuilder->set($this->Fields[$name]->Expression, $queryBuilder->createPositionalParameter($value, $type));
        }
        $filter = ($curfilter) ? $this->CurrentFilter : "";
        if (is_array($where)) {
            $where = $this->arrayToFilter($where);
        }
        AddFilter($filter, $where);
        if ($filter != "") {
            $queryBuilder->where($filter);
        }
        return $queryBuilder;
    }

    // Update
    public function update(&$rs, $where = "", $rsold = null, $curfilter = true)
    {
        // Cascade Update detail table 'orderdetails'
        $cascadeUpdate = false;
        $rscascade = [];
        if ($rsold && (isset($rs['OrderID']) && $rsold['OrderID'] != $rs['OrderID'])) { // Update detail field 'OrderID'
            $cascadeUpdate = true;
            $rscascade['OrderID'] = $rs['OrderID'];
        }
        if ($cascadeUpdate) {
            $rswrk = Container("orderdetails")->loadRs("`OrderID` = " . QuotedValue($rsold['OrderID'], DATATYPE_NUMBER, 'DB'))->fetchAllAssociative();
            foreach ($rswrk as $rsdtlold) {
                $rskey = [];
                $fldname = 'OrderID';
                $rskey[$fldname] = $rsdtlold[$fldname];
                $fldname = 'ProductID';
                $rskey[$fldname] = $rsdtlold[$fldname];
                $rsdtlnew = array_merge($rsdtlold, $rscascade);
                // Call Row_Updating event
                $success = Container("orderdetails")->rowUpdating($rsdtlold, $rsdtlnew);
                if ($success) {
                    $success = Container("orderdetails")->update($rscascade, $rskey, $rsdtlold);
                }
                if (!$success) {
                    return false;
                }
                // Call Row_Updated event
                Container("orderdetails")->rowUpdated($rsdtlold, $rsdtlnew);
            }
        }

        // If no field is updated, execute may return 0. Treat as success
        try {
            $success = $this->updateSql($rs, $where, $curfilter)->execute();
            $success = ($success > 0) ? $success : true;
            $this->DbErrorMessage = "";
        } catch (\Exception $e) {
            $success = false;
            $this->DbErrorMessage = $e->getMessage();
        }

        // Return auto increment field
        if ($success) {
            if (!isset($rs['OrderID']) && !EmptyValue($this->OrderID->CurrentValue)) {
                $rs['OrderID'] = $this->OrderID->CurrentValue;
            }
        }
        return $success;
    }

    /**
     * DELETE statement
     *
     * @param array $rs Key values
     * @param string|array $where WHERE clause
     * @param string $curfilter Filter
     * @return QueryBuilder
     */
    public function deleteSql(&$rs, $where = "", $curfilter = true)
    {
        $queryBuilder = $this->getQueryBuilder();
        $queryBuilder->delete($this->UpdateTable);
        if (is_array($where)) {
            $where = $this->arrayToFilter($where);
        }
        if ($rs) {
            if (array_key_exists('OrderID', $rs)) {
                AddFilter($where, QuotedName('OrderID', $this->Dbid) . '=' . QuotedValue($rs['OrderID'], $this->OrderID->DataType, $this->Dbid));
            }
        }
        $filter = ($curfilter) ? $this->CurrentFilter : "";
        AddFilter($filter, $where);
        return $queryBuilder->where($filter != "" ? $filter : "0=1");
    }

    // Delete
    public function delete(&$rs, $where = "", $curfilter = false)
    {
        $success = true;

        // Cascade delete detail table 'orderdetails'
        $dtlrows = Container("orderdetails")->loadRs("`OrderID` = " . QuotedValue($rs['OrderID'], DATATYPE_NUMBER, "DB"))->fetchAllAssociative();
        // Call Row Deleting event
        foreach ($dtlrows as $dtlrow) {
            $success = Container("orderdetails")->rowDeleting($dtlrow);
            if (!$success) {
                break;
            }
        }
        if ($success) {
            foreach ($dtlrows as $dtlrow) {
                $success = Container("orderdetails")->delete($dtlrow); // Delete
                if (!$success) {
                    break;
                }
            }
        }
        // Call Row Deleted event
        if ($success) {
            foreach ($dtlrows as $dtlrow) {
                Container("orderdetails")->rowDeleted($dtlrow);
            }
        }
        if ($success) {
            try {
                $success = $this->deleteSql($rs, $where, $curfilter)->execute();
                $this->DbErrorMessage = "";
            } catch (\Exception $e) {
                $success = false;
                $this->DbErrorMessage = $e->getMessage();
            }
        }
        return $success;
    }

    // Load DbValue from recordset or array
    protected function loadDbValues($row)
    {
        if (!is_array($row)) {
            return;
        }
        $this->OrderID->DbValue = $row['OrderID'];
        $this->CustomerID->DbValue = $row['CustomerID'];
        $this->EmployeeID->DbValue = $row['EmployeeID'];
        $this->OrderDate->DbValue = $row['OrderDate'];
        $this->RequiredDate->DbValue = $row['RequiredDate'];
        $this->ShippedDate->DbValue = $row['ShippedDate'];
        $this->ShipVia->DbValue = $row['ShipVia'];
        $this->Freight->DbValue = $row['Freight'];
        $this->ShipName->DbValue = $row['ShipName'];
        $this->ShipAddress->DbValue = $row['ShipAddress'];
        $this->ShipCity->DbValue = $row['ShipCity'];
        $this->ShipRegion->DbValue = $row['ShipRegion'];
        $this->ShipPostalCode->DbValue = $row['ShipPostalCode'];
        $this->ShipCountry->DbValue = $row['ShipCountry'];
    }

    // Delete uploaded files
    public function deleteUploadedFiles($row)
    {
        $this->loadDbValues($row);
    }

    // Record filter WHERE clause
    protected function sqlKeyFilter()
    {
        return "`OrderID` = @OrderID@";
    }

    // Get Key
    public function getKey($current = false)
    {
        $keys = [];
        $val = $current ? $this->OrderID->CurrentValue : $this->OrderID->OldValue;
        if (EmptyValue($val)) {
            return "";
        } else {
            $keys[] = $val;
        }
        return implode(Config("COMPOSITE_KEY_SEPARATOR"), $keys);
    }

    // Set Key
    public function setKey($key, $current = false)
    {
        $this->OldKey = strval($key);
        $keys = explode(Config("COMPOSITE_KEY_SEPARATOR"), $this->OldKey);
        if (count($keys) == 1) {
            if ($current) {
                $this->OrderID->CurrentValue = $keys[0];
            } else {
                $this->OrderID->OldValue = $keys[0];
            }
        }
    }

    // Get record filter
    public function getRecordFilter($row = null, $current = false)
    {
        $keyFilter = $this->sqlKeyFilter();
        if (is_array($row)) {
            $val = array_key_exists('OrderID', $row) ? $row['OrderID'] : null;
        } else {
            $val = !EmptyValue($this->OrderID->OldValue) && !$current ? $this->OrderID->OldValue : $this->OrderID->CurrentValue;
        }
        if (!is_numeric($val)) {
            return "0=1"; // Invalid key
        }
        if ($val === null) {
            return "0=1"; // Invalid key
        } else {
            $keyFilter = str_replace("@OrderID@", AdjustSql($val, $this->Dbid), $keyFilter); // Replace key value
        }
        return $keyFilter;
    }

    // Return page URL
    public function getReturnUrl()
    {
        $referUrl = ReferUrl();
        $referPageName = ReferPageName();
        $name = PROJECT_NAME . "_" . $this->TableVar . "_" . Config("TABLE_RETURN_URL");
        // Get referer URL automatically
        if ($referUrl != "" && $referPageName != CurrentPageName() && $referPageName != "login") { // Referer not same page or login page
            $_SESSION[$name] = $referUrl; // Save to Session
        }
        return $_SESSION[$name] ?? GetUrl("orderslist");
    }

    // Set return page URL
    public function setReturnUrl($v)
    {
        $_SESSION[PROJECT_NAME . "_" . $this->TableVar . "_" . Config("TABLE_RETURN_URL")] = $v;
    }

    // Get modal caption
    public function getModalCaption($pageName)
    {
        global $Language;
        if ($pageName == "ordersview") {
            return $Language->phrase("View");
        } elseif ($pageName == "ordersedit") {
            return $Language->phrase("Edit");
        } elseif ($pageName == "ordersadd") {
            return $Language->phrase("Add");
        }
        return "";
    }

    // API page name
    public function getApiPageName($action)
    {
        switch (strtolower($action)) {
            case Config("API_VIEW_ACTION"):
                return "OrdersView";
            case Config("API_ADD_ACTION"):
                return "OrdersAdd";
            case Config("API_EDIT_ACTION"):
                return "OrdersEdit";
            case Config("API_DELETE_ACTION"):
                return "OrdersDelete";
            case Config("API_LIST_ACTION"):
                return "OrdersList";
            default:
                return "";
        }
    }

    // Current URL
    public function getCurrentUrl($parm = "")
    {
        $url = CurrentPageUrl(false);
        if ($parm != "") {
            $url = $this->keyUrl($url, $parm);
        } else {
            $url = $this->keyUrl($url, Config("TABLE_SHOW_DETAIL") . "=");
        }
        return $this->addMasterUrl($url);
    }

    // List URL
    public function getListUrl()
    {
        return "orderslist";
    }

    // View URL
    public function getViewUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("ordersview", $parm);
        } else {
            $url = $this->keyUrl("ordersview", Config("TABLE_SHOW_DETAIL") . "=");
        }
        return $this->addMasterUrl($url);
    }

    // Add URL
    public function getAddUrl($parm = "")
    {
        if ($parm != "") {
            $url = "ordersadd?" . $parm;
        } else {
            $url = "ordersadd";
        }
        return $this->addMasterUrl($url);
    }

    // Edit URL
    public function getEditUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("ordersedit", $parm);
        } else {
            $url = $this->keyUrl("ordersedit", Config("TABLE_SHOW_DETAIL") . "=");
        }
        return $this->addMasterUrl($url);
    }

    // Inline edit URL
    public function getInlineEditUrl()
    {
        $url = $this->keyUrl("orderslist", "action=edit");
        return $this->addMasterUrl($url);
    }

    // Copy URL
    public function getCopyUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("ordersadd", $parm);
        } else {
            $url = $this->keyUrl("ordersadd", Config("TABLE_SHOW_DETAIL") . "=");
        }
        return $this->addMasterUrl($url);
    }

    // Inline copy URL
    public function getInlineCopyUrl()
    {
        $url = $this->keyUrl("orderslist", "action=copy");
        return $this->addMasterUrl($url);
    }

    // Delete URL
    public function getDeleteUrl()
    {
        if ($this->UseAjaxActions && ConvertToBool(Param("infinitescroll")) && CurrentPageID() == "list") {
            return $this->keyUrl(GetApiUrl(Config("API_DELETE_ACTION") . "/" . $this->TableVar));
        } else {
            return $this->keyUrl("ordersdelete");
        }
    }

    // Add master url
    public function addMasterUrl($url)
    {
        if ($this->getCurrentMasterTable() == "customers" && !ContainsString($url, Config("TABLE_SHOW_MASTER") . "=")) {
            $url .= (ContainsString($url, "?") ? "&" : "?") . Config("TABLE_SHOW_MASTER") . "=" . $this->getCurrentMasterTable();
            $url .= "&" . GetForeignKeyUrl("fk_CustomerID", $this->CustomerID->getSessionValue()); // Use Session Value
        }
        return $url;
    }

    public function keyToJson($htmlEncode = false)
    {
        $json = "";
        $json .= "\"OrderID\":" . JsonEncode($this->OrderID->CurrentValue, "number");
        $json = "{" . $json . "}";
        if ($htmlEncode) {
            $json = HtmlEncode($json);
        }
        return $json;
    }

    // Add key value to URL
    public function keyUrl($url, $parm = "")
    {
        if ($this->OrderID->CurrentValue !== null) {
            $url .= "/" . $this->encodeKeyValue($this->OrderID->CurrentValue);
        } else {
            return "javascript:ew.alert(ew.language.phrase('InvalidRecord'));";
        }
        if ($parm != "") {
            $url .= "?" . $parm;
        }
        return $url;
    }

    // Render sort
    public function renderFieldHeader($fld)
    {
        global $Security, $Language, $Page;
        $sortUrl = "";
        $attrs = "";
        if ($fld->Sortable) {
            $sortUrl = $this->sortUrl($fld);
            $attrs = ' role="button" data-ew-action="sort" data-ajax="' . ($this->UseAjaxActions ? "true" : "false") . '" data-sort-url="' . $sortUrl . '" data-sort-type="1"';
            if ($this->ContextClass) { // Add context
                $attrs .= ' data-context="' . HtmlEncode($this->ContextClass) . '"';
            }
        }
        $html = '<div class="ew-table-header-caption"' . $attrs . '>' . $fld->caption() . '</div>';
        if ($sortUrl) {
            $html .= '<div class="ew-table-header-sort">' . $fld->getSortIcon() . '</div>';
        }
        if ($fld->UseFilter && $Security->canSearch()) {
            $html .= '<div class="ew-filter-dropdown-btn" data-ew-action="filter" data-table="' . $fld->TableVar . '" data-field="' . $fld->FieldVar .
                '"><div class="ew-table-header-filter" role="button" aria-haspopup="true">' . $Language->phrase("Filter") . '</div></div>';
        }
        $html = '<div class="ew-table-header-btn">' . $html . '</div>';
        if ($this->UseCustomTemplate) {
            $scriptId = str_replace("{id}", $fld->TableVar . "_" . $fld->Param, "tpc_{id}");
            $html = '<template id="' . $scriptId . '">' . $html . '</template>';
        }
        return $html;
    }

    // Sort URL
    public function sortUrl($fld)
    {
        global $DashboardReport;
        if (
            $this->CurrentAction || $this->isExport() ||
            in_array($fld->Type, [128, 204, 205])
        ) { // Unsortable data type
                return "";
        } elseif ($fld->Sortable) {
            $urlParm = "order=" . urlencode($fld->Name) . "&amp;ordertype=" . $fld->getNextSort();
            if ($DashboardReport) {
                $urlParm .= "&amp;dashboard=true";
            }
            return $this->addMasterUrl($this->CurrentPageName . "?" . $urlParm);
        } else {
            return "";
        }
    }

    // Get record keys from Post/Get/Session
    public function getRecordKeys()
    {
        $arKeys = [];
        $arKey = [];
        if (Param("key_m") !== null) {
            $arKeys = Param("key_m");
            $cnt = count($arKeys);
        } else {
            if (($keyValue = Param("OrderID") ?? Route("OrderID")) !== null) {
                $arKeys[] = $keyValue;
            } elseif (IsApi() && (($keyValue = Key(0) ?? Route(2)) !== null)) {
                $arKeys[] = $keyValue;
            } else {
                $arKeys = null; // Do not setup
            }

            //return $arKeys; // Do not return yet, so the values will also be checked by the following code
        }
        // Check keys
        $ar = [];
        if (is_array($arKeys)) {
            foreach ($arKeys as $key) {
                if (!is_numeric($key)) {
                    continue;
                }
                $ar[] = $key;
            }
        }
        return $ar;
    }

    // Get filter from records
    public function getFilterFromRecords($rows)
    {
        $keyFilter = "";
        foreach ($rows as $row) {
            if ($keyFilter != "") {
                $keyFilter .= " OR ";
            }
            $keyFilter .= "(" . $this->getRecordFilter($row) . ")";
        }
        return $keyFilter;
    }

    // Get filter from record keys
    public function getFilterFromRecordKeys($setCurrent = true)
    {
        $arKeys = $this->getRecordKeys();
        $keyFilter = "";
        foreach ($arKeys as $key) {
            if ($keyFilter != "") {
                $keyFilter .= " OR ";
            }
            if ($setCurrent) {
                $this->OrderID->CurrentValue = $key;
            } else {
                $this->OrderID->OldValue = $key;
            }
            $keyFilter .= "(" . $this->getRecordFilter() . ")";
        }
        return $keyFilter;
    }

    // Load recordset based on filter / sort
    public function loadRs($filter, $sort = "")
    {
        $sql = $this->getSql($filter, $sort); // Set up filter (WHERE Clause) / sort (ORDER BY Clause)
        $conn = $this->getConnection();
        return $conn->executeQuery($sql);
    }

    // Load row values from record
    public function loadListRowValues(&$rs)
    {
        if (is_array($rs)) {
            $row = $rs;
        } elseif ($rs && property_exists($rs, "fields")) { // Recordset
            $row = $rs->fields;
        } else {
            return;
        }
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

    // Render list content
    public function renderListContent($filter)
    {
        global $Response;
        $listPage = "OrdersList";
        $listClass = PROJECT_NAMESPACE . $listPage;
        $page = new $listClass();
        $page->loadRecordsetFromFilter($filter);
        $view = Container("view");
        $template = $listPage . ".php"; // View
        $GLOBALS["Title"] ??= $page->Title; // Title
        try {
            $Response = $view->render($Response, $template, $GLOBALS);
        } finally {
            $page->terminate(); // Terminate page and clean up
        }
    }

    // Render list row values
    public function renderListRow()
    {
        global $Security, $CurrentLanguage, $Language;

        // Call Row Rendering event
        $this->rowRendering();

        // Common render codes

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
        $this->Freight->ViewValue = FormatNumber($this->Freight->ViewValue, $this->Freight->formatPattern());

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

        // ShipVia
        $this->ShipVia->HrefValue = "";
        $this->ShipVia->TooltipValue = "";

        // Freight
        $this->Freight->HrefValue = "";
        $this->Freight->TooltipValue = "";

        // ShipName
        $this->ShipName->HrefValue = "";
        $this->ShipName->TooltipValue = "";

        // ShipAddress
        $this->ShipAddress->HrefValue = "";
        $this->ShipAddress->TooltipValue = "";

        // ShipCity
        $this->ShipCity->HrefValue = "";
        $this->ShipCity->TooltipValue = "";

        // ShipRegion
        $this->ShipRegion->HrefValue = "";
        $this->ShipRegion->TooltipValue = "";

        // ShipPostalCode
        $this->ShipPostalCode->HrefValue = "";
        $this->ShipPostalCode->TooltipValue = "";

        // ShipCountry
        $this->ShipCountry->HrefValue = "";
        $this->ShipCountry->TooltipValue = "";

        // Call Row Rendered event
        $this->rowRendered();

        // Save data for Custom Template
        $this->Rows[] = $this->customTemplateFieldValues();
    }

    // Render edit row values
    public function renderEditRow()
    {
        global $Security, $CurrentLanguage, $Language;

        // Call Row Rendering event
        $this->rowRendering();

        // OrderID
        $this->OrderID->setupEditAttributes();
        $this->OrderID->EditValue = $this->OrderID->CurrentValue;

        // CustomerID
        $this->CustomerID->setupEditAttributes();
        if ($this->CustomerID->getSessionValue() != "") {
            $this->CustomerID->CurrentValue = GetForeignKeyValue($this->CustomerID->getSessionValue());
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
        } else {
            $this->CustomerID->PlaceHolder = RemoveHtml($this->CustomerID->caption());
        }

        // EmployeeID
        $this->EmployeeID->setupEditAttributes();
        if (!$Security->isAdmin() && $Security->isLoggedIn() && !$this->userIDAllow("info")) { // Non system admin
            $this->EmployeeID->CurrentValue = EmptyValue($this->EmployeeID->CurrentValue) ? CurrentUserID() : $this->EmployeeID->CurrentValue;
        } else {
            $this->EmployeeID->PlaceHolder = RemoveHtml($this->EmployeeID->caption());
        }

        // OrderDate
        $this->OrderDate->setupEditAttributes();
        $this->OrderDate->EditValue = FormatDateTime($this->OrderDate->CurrentValue, $this->OrderDate->formatPattern());
        $this->OrderDate->PlaceHolder = RemoveHtml($this->OrderDate->caption());

        // RequiredDate
        $this->RequiredDate->setupEditAttributes();
        $this->RequiredDate->EditValue = FormatDateTime($this->RequiredDate->CurrentValue, $this->RequiredDate->formatPattern());
        $this->RequiredDate->PlaceHolder = RemoveHtml($this->RequiredDate->caption());

        // ShippedDate
        $this->ShippedDate->setupEditAttributes();
        $this->ShippedDate->EditValue = FormatDateTime($this->ShippedDate->CurrentValue, $this->ShippedDate->formatPattern());
        $this->ShippedDate->PlaceHolder = RemoveHtml($this->ShippedDate->caption());

        // ShipVia
        $this->ShipVia->setupEditAttributes();
        $this->ShipVia->EditValue = $this->ShipVia->CurrentValue;
        $this->ShipVia->PlaceHolder = RemoveHtml($this->ShipVia->caption());
        if (strval($this->ShipVia->EditValue) != "" && is_numeric($this->ShipVia->EditValue)) {
            $this->ShipVia->EditValue = $this->ShipVia->EditValue;
        }

        // Freight
        $this->Freight->setupEditAttributes();
        $this->Freight->EditValue = $this->Freight->CurrentValue;
        $this->Freight->PlaceHolder = RemoveHtml($this->Freight->caption());
        if (strval($this->Freight->EditValue) != "" && is_numeric($this->Freight->EditValue)) {
            $this->Freight->EditValue = FormatNumber($this->Freight->EditValue, null);
        }

        // ShipName
        $this->ShipName->setupEditAttributes();
        if (!$this->ShipName->Raw) {
            $this->ShipName->CurrentValue = HtmlDecode($this->ShipName->CurrentValue);
        }
        $this->ShipName->EditValue = $this->ShipName->CurrentValue;
        $this->ShipName->PlaceHolder = RemoveHtml($this->ShipName->caption());

        // ShipAddress
        $this->ShipAddress->setupEditAttributes();
        if (!$this->ShipAddress->Raw) {
            $this->ShipAddress->CurrentValue = HtmlDecode($this->ShipAddress->CurrentValue);
        }
        $this->ShipAddress->EditValue = $this->ShipAddress->CurrentValue;
        $this->ShipAddress->PlaceHolder = RemoveHtml($this->ShipAddress->caption());

        // ShipCity
        $this->ShipCity->setupEditAttributes();
        if (!$this->ShipCity->Raw) {
            $this->ShipCity->CurrentValue = HtmlDecode($this->ShipCity->CurrentValue);
        }
        $this->ShipCity->EditValue = $this->ShipCity->CurrentValue;
        $this->ShipCity->PlaceHolder = RemoveHtml($this->ShipCity->caption());

        // ShipRegion
        $this->ShipRegion->setupEditAttributes();
        if (!$this->ShipRegion->Raw) {
            $this->ShipRegion->CurrentValue = HtmlDecode($this->ShipRegion->CurrentValue);
        }
        $this->ShipRegion->EditValue = $this->ShipRegion->CurrentValue;
        $this->ShipRegion->PlaceHolder = RemoveHtml($this->ShipRegion->caption());

        // ShipPostalCode
        $this->ShipPostalCode->setupEditAttributes();
        if (!$this->ShipPostalCode->Raw) {
            $this->ShipPostalCode->CurrentValue = HtmlDecode($this->ShipPostalCode->CurrentValue);
        }
        $this->ShipPostalCode->EditValue = $this->ShipPostalCode->CurrentValue;
        $this->ShipPostalCode->PlaceHolder = RemoveHtml($this->ShipPostalCode->caption());

        // ShipCountry
        $this->ShipCountry->setupEditAttributes();
        if (!$this->ShipCountry->Raw) {
            $this->ShipCountry->CurrentValue = HtmlDecode($this->ShipCountry->CurrentValue);
        }
        $this->ShipCountry->EditValue = $this->ShipCountry->CurrentValue;
        $this->ShipCountry->PlaceHolder = RemoveHtml($this->ShipCountry->caption());

        // Call Row Rendered event
        $this->rowRendered();
    }

    // Aggregate list row values
    public function aggregateListRowValues()
    {
    }

    // Aggregate list row (for rendering)
    public function aggregateListRow()
    {
        // Call Row Rendered event
        $this->rowRendered();
    }

    // Export data in HTML/CSV/Word/Excel/Email/PDF format
    public function exportDocument($doc, $recordset, $startRec = 1, $stopRec = 1, $exportPageType = "")
    {
        if (!$recordset || !$doc) {
            return;
        }
        if (!$doc->ExportCustom) {
            // Write header
            $doc->exportTableHeader();
            if ($doc->Horizontal) { // Horizontal format, write header
                $doc->beginExportRow();
                if ($exportPageType == "view") {
                    $doc->exportCaption($this->OrderID);
                    $doc->exportCaption($this->CustomerID);
                    $doc->exportCaption($this->EmployeeID);
                    $doc->exportCaption($this->OrderDate);
                    $doc->exportCaption($this->RequiredDate);
                    $doc->exportCaption($this->ShippedDate);
                    $doc->exportCaption($this->ShipVia);
                    $doc->exportCaption($this->Freight);
                    $doc->exportCaption($this->ShipName);
                    $doc->exportCaption($this->ShipAddress);
                    $doc->exportCaption($this->ShipCity);
                    $doc->exportCaption($this->ShipRegion);
                    $doc->exportCaption($this->ShipPostalCode);
                    $doc->exportCaption($this->ShipCountry);
                } else {
                    $doc->exportCaption($this->OrderID);
                    $doc->exportCaption($this->CustomerID);
                    $doc->exportCaption($this->EmployeeID);
                    $doc->exportCaption($this->OrderDate);
                }
                $doc->endExportRow();
            }
        }

        // Move to first record
        $recCnt = $startRec - 1;
        $stopRec = ($stopRec > 0) ? $stopRec : PHP_INT_MAX;
        while (!$recordset->EOF && $recCnt < $stopRec) {
            $row = $recordset->fields;
            $recCnt++;
            if ($recCnt >= $startRec) {
                $rowCnt = $recCnt - $startRec + 1;

                // Page break
                if ($this->ExportPageBreakCount > 0) {
                    if ($rowCnt > 1 && ($rowCnt - 1) % $this->ExportPageBreakCount == 0) {
                        $doc->exportPageBreak();
                    }
                }
                $this->loadListRowValues($row);

                // Render row
                $this->RowType = ROWTYPE_VIEW; // Render view
                $this->resetAttributes();
                $this->renderListRow();
                if (!$doc->ExportCustom) {
                    $doc->beginExportRow($rowCnt); // Allow CSS styles if enabled
                    if ($exportPageType == "view") {
                        $doc->exportField($this->OrderID);
                        $doc->exportField($this->CustomerID);
                        $doc->exportField($this->EmployeeID);
                        $doc->exportField($this->OrderDate);
                        $doc->exportField($this->RequiredDate);
                        $doc->exportField($this->ShippedDate);
                        $doc->exportField($this->ShipVia);
                        $doc->exportField($this->Freight);
                        $doc->exportField($this->ShipName);
                        $doc->exportField($this->ShipAddress);
                        $doc->exportField($this->ShipCity);
                        $doc->exportField($this->ShipRegion);
                        $doc->exportField($this->ShipPostalCode);
                        $doc->exportField($this->ShipCountry);
                    } else {
                        $doc->exportField($this->OrderID);
                        $doc->exportField($this->CustomerID);
                        $doc->exportField($this->EmployeeID);
                        $doc->exportField($this->OrderDate);
                    }
                    $doc->endExportRow($rowCnt);
                }
            }

            // Call Row Export server event
            if ($doc->ExportCustom) {
                $this->rowExport($doc, $row);
            }
            $recordset->moveNext();
        }
        if (!$doc->ExportCustom) {
            $doc->exportTableFooter();
        }
    }

    // Add User ID filter
    public function addUserIDFilter($filter = "", $id = "")
    {
        global $Security;
        $filterWrk = "";
        if ($id == "")
            $id = (CurrentPageID() == "list") ? $this->CurrentAction : CurrentPageID();
        if (!$this->userIDAllow($id) && !$Security->isAdmin()) {
            $filterWrk = $Security->userIdList();
            if ($filterWrk != "") {
                $filterWrk = '`EmployeeID` IN (' . $filterWrk . ')';
            }
        }

        // Call User ID Filtering event
        $this->userIdFiltering($filterWrk);
        AddFilter($filter, $filterWrk);
        return $filter;
    }

    // User ID subquery
    public function getUserIDSubquery(&$fld, &$masterfld)
    {
        global $UserTable;
        $wrk = "";
        $sql = "SELECT " . $masterfld->Expression . " FROM orders";
        $filter = $this->addUserIDFilter("");
        if ($filter != "") {
            $sql .= " WHERE " . $filter;
        }

        // List all values
        $conn = Conn($UserTable->Dbid);
        $config = $conn->getConfiguration();
        $config->setResultCacheImpl($this->Cache);
        if ($rs = $conn->executeCacheQuery($sql, [], [], $this->CacheProfile)->fetchAllNumeric()) {
            foreach ($rs as $row) {
                if ($wrk != "") {
                    $wrk .= ",";
                }
                $wrk .= QuotedValue($row[0], $masterfld->DataType, Config("USER_TABLE_DBID"));
            }
        }
        if ($wrk != "") {
            $wrk = $fld->Expression . " IN (" . $wrk . ")";
        } else { // No User ID value found
            $wrk = "0=1";
        }
        return $wrk;
    }

    // Get file data
    public function getFileData($fldparm, $key, $resize, $width = 0, $height = 0, $plugins = [])
    {
        global $DownloadFileName;

        // No binary fields
        return false;
    }

    // Table level events

    // Table Load event
    public function tableLoad()
    {
        // Enter your code here
    }

    // Recordset Selecting event
    public function recordsetSelecting(&$filter)
    {
        // Enter your code here
    }

    // Recordset Selected event
    public function recordsetSelected(&$rs)
    {
        //Log("Recordset Selected");
    }

    // Recordset Search Validated event
    public function recordsetSearchValidated()
    {
        // Example:
        //$this->MyField1->AdvancedSearch->SearchValue = "your search criteria"; // Search value
    }

    // Recordset Searching event
    public function recordsetSearching(&$filter)
    {
        // Enter your code here
    }

    // Row_Selecting event
    public function rowSelecting(&$filter)
    {
        // Enter your code here
    }

    // Row Selected event
    public function rowSelected(&$rs)
    {
        //Log("Row Selected");
    }

    // Row Inserting event
    public function rowInserting($rsold, &$rsnew)
    {
        // Enter your code here
        // To cancel, set return value to false
        return true;
    }

    // Row Inserted event
    public function rowInserted($rsold, &$rsnew)
    {
        //Log("Row Inserted");
    }

    // Row Updating event
    public function rowUpdating($rsold, &$rsnew)
    {
        // Enter your code here
        // To cancel, set return value to false
        return true;
    }

    // Row Updated event
    public function rowUpdated($rsold, &$rsnew)
    {
        //Log("Row Updated");
    }

    // Row Update Conflict event
    public function rowUpdateConflict($rsold, &$rsnew)
    {
        // Enter your code here
        // To ignore conflict, set return value to false
        return true;
    }

    // Grid Inserting event
    public function gridInserting()
    {
        // Enter your code here
        // To reject grid insert, set return value to false
        return true;
    }

    // Grid Inserted event
    public function gridInserted($rsnew)
    {
        //Log("Grid Inserted");
    }

    // Grid Updating event
    public function gridUpdating($rsold)
    {
        // Enter your code here
        // To reject grid update, set return value to false
        return true;
    }

    // Grid Updated event
    public function gridUpdated($rsold, $rsnew)
    {
        //Log("Grid Updated");
    }

    // Row Deleting event
    public function rowDeleting(&$rs)
    {
        // Enter your code here
        // To cancel, set return value to False
        return true;
    }

    // Row Deleted event
    public function rowDeleted(&$rs)
    {
        //Log("Row Deleted");
    }

    // Email Sending event
    public function emailSending($email, &$args)
    {
        //var_dump($email, $args); exit();
        return true;
    }

    // Lookup Selecting event
    public function lookupSelecting($fld, &$filter)
    {
        //var_dump($fld->Name, $fld->Lookup, $filter); // Uncomment to view the filter
        // Enter your code here
    }

    // Row Rendering event
    public function rowRendering()
    {
        // Enter your code here
    }

    // Row Rendered event
    public function rowRendered()
    {
        // To view properties of field class, use:
        //var_dump($this-><FieldName>);
    }

    // User ID Filtering event
    public function userIdFiltering(&$filter)
    {
        // Enter your code here
    }
}
