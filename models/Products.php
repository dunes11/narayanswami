<?php

namespace PHPMaker2023\demo2023;

use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Table class for products
 */
class Products extends DbTable
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
    public $ProductID;
    public $ProductName;
    public $SupplierID;
    public $CategoryID;
    public $QuantityPerUnit;
    public $UnitPrice;
    public $UnitsInStock;
    public $UnitsOnOrder;
    public $ReorderLevel;
    public $Discontinued;
    public $EAN13;

    // Page ID
    public $PageID = ""; // To be overridden by subclass

    // Constructor
    public function __construct()
    {
        parent::__construct();
        global $Language, $CurrentLanguage, $CurrentLocale;

        // Language object
        $Language = Container("language");
        $this->TableVar = "products";
        $this->TableName = 'products';
        $this->TableType = "TABLE";
        $this->ImportUseTransaction = $this->supportsTransaction() && Config("IMPORT_USE_TRANSACTION");
        $this->UseTransaction = $this->supportsTransaction() && Config("USE_TRANSACTION");

        // Update Table
        $this->UpdateTable = "products";
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
        $this->ShowMultipleDetails = false; // Show multiple details
        $this->GridAddRowCount = 5;
        $this->AllowAddDeleteRow = true; // Allow add/delete row
        $this->UseAjaxActions = $this->UseAjaxActions || Config("USE_AJAX_ACTIONS");
        $this->UserIDAllowSecurity = Config("DEFAULT_USER_ID_ALLOW_SECURITY"); // Default User ID allowed permissions
        $this->BasicSearch = new BasicSearch($this);

        // ProductID
        $this->ProductID = new DbField(
            $this, // Table
            'x_ProductID', // Variable name
            'ProductID', // Name
            '`ProductID`', // Expression
            '`ProductID`', // Basic search expression
            3, // Type
            11, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`ProductID`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'NO' // Edit Tag
        );
        $this->ProductID->InputTextType = "text";
        $this->ProductID->IsAutoIncrement = true; // Autoincrement field
        $this->ProductID->IsPrimaryKey = true; // Primary key field
        $this->ProductID->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->ProductID->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['ProductID'] = &$this->ProductID;

        // ProductName
        $this->ProductName = new DbField(
            $this, // Table
            'x_ProductName', // Variable name
            'ProductName', // Name
            '`ProductName`', // Expression
            '`ProductName`', // Basic search expression
            200, // Type
            40, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`ProductName`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->ProductName->InputTextType = "text";
        $this->ProductName->Nullable = false; // NOT NULL field
        $this->ProductName->Required = true; // Required field
        $this->ProductName->UseFilter = true; // Table header filter
        $this->ProductName->Lookup = new Lookup('ProductName', 'products', true, 'ProductName', ["ProductName","","",""], '', '', [], [], [], [], [], [], '', '', "");
        $this->ProductName->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY"];
        $this->Fields['ProductName'] = &$this->ProductName;

        // SupplierID
        $this->SupplierID = new DbField(
            $this, // Table
            'x_SupplierID', // Variable name
            'SupplierID', // Name
            '`SupplierID`', // Expression
            '`SupplierID`', // Basic search expression
            3, // Type
            11, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`SupplierID`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'SELECT' // Edit Tag
        );
        $this->SupplierID->InputTextType = "text";
        $this->SupplierID->setSelectMultiple(false); // Select one
        $this->SupplierID->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->SupplierID->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->SupplierID->Lookup = new Lookup('SupplierID', 'suppliers', false, 'SupplierID', ["CompanyName","","",""], '', '', [], [], [], [], [], [], '', '', "`CompanyName`");
        $this->SupplierID->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->SupplierID->SearchOperators = ["=", "<>", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['SupplierID'] = &$this->SupplierID;

        // CategoryID
        $this->CategoryID = new DbField(
            $this, // Table
            'x_CategoryID', // Variable name
            'CategoryID', // Name
            '`CategoryID`', // Expression
            '`CategoryID`', // Basic search expression
            3, // Type
            11, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`CategoryID`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'SELECT' // Edit Tag
        );
        $this->CategoryID->InputTextType = "text";
        $this->CategoryID->IsForeignKey = true; // Foreign key field
        $this->CategoryID->setSelectMultiple(false); // Select one
        $this->CategoryID->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->CategoryID->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->CategoryID->Lookup = new Lookup('CategoryID', 'categories', false, 'CategoryID', ["CategoryName","","",""], '', '', [], [], [], [], [], [], '', '', "`CategoryName`");
        $this->CategoryID->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->CategoryID->SearchOperators = ["=", "<>", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['CategoryID'] = &$this->CategoryID;

        // QuantityPerUnit
        $this->QuantityPerUnit = new DbField(
            $this, // Table
            'x_QuantityPerUnit', // Variable name
            'QuantityPerUnit', // Name
            '`QuantityPerUnit`', // Expression
            '`QuantityPerUnit`', // Basic search expression
            200, // Type
            20, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`QuantityPerUnit`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->QuantityPerUnit->InputTextType = "text";
        $this->QuantityPerUnit->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['QuantityPerUnit'] = &$this->QuantityPerUnit;

        // UnitPrice
        $this->UnitPrice = new DbField(
            $this, // Table
            'x_UnitPrice', // Variable name
            'UnitPrice', // Name
            '`UnitPrice`', // Expression
            '`UnitPrice`', // Basic search expression
            5, // Type
            22, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`UnitPrice`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->UnitPrice->InputTextType = "text";
        $this->UnitPrice->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->UnitPrice->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['UnitPrice'] = &$this->UnitPrice;

        // UnitsInStock
        $this->UnitsInStock = new DbField(
            $this, // Table
            'x_UnitsInStock', // Variable name
            'UnitsInStock', // Name
            '`UnitsInStock`', // Expression
            '`UnitsInStock`', // Basic search expression
            2, // Type
            6, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`UnitsInStock`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->UnitsInStock->InputTextType = "text";
        $this->UnitsInStock->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->UnitsInStock->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['UnitsInStock'] = &$this->UnitsInStock;

        // UnitsOnOrder
        $this->UnitsOnOrder = new DbField(
            $this, // Table
            'x_UnitsOnOrder', // Variable name
            'UnitsOnOrder', // Name
            '`UnitsOnOrder`', // Expression
            '`UnitsOnOrder`', // Basic search expression
            2, // Type
            6, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`UnitsOnOrder`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->UnitsOnOrder->InputTextType = "text";
        $this->UnitsOnOrder->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->UnitsOnOrder->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['UnitsOnOrder'] = &$this->UnitsOnOrder;

        // ReorderLevel
        $this->ReorderLevel = new DbField(
            $this, // Table
            'x_ReorderLevel', // Variable name
            'ReorderLevel', // Name
            '`ReorderLevel`', // Expression
            '`ReorderLevel`', // Basic search expression
            2, // Type
            6, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`ReorderLevel`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->ReorderLevel->InputTextType = "text";
        $this->ReorderLevel->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->ReorderLevel->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['ReorderLevel'] = &$this->ReorderLevel;

        // Discontinued
        $this->Discontinued = new DbField(
            $this, // Table
            'x_Discontinued', // Variable name
            'Discontinued', // Name
            '`Discontinued`', // Expression
            '`Discontinued`', // Basic search expression
            16, // Type
            1, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`Discontinued`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'CHECKBOX' // Edit Tag
        );
        $this->Discontinued->addMethod("getDefault", fn() => "0");
        $this->Discontinued->InputTextType = "text";
        $this->Discontinued->Required = true; // Required field
        $this->Discontinued->DataType = DATATYPE_BOOLEAN;
        $this->Discontinued->Lookup = new Lookup('Discontinued', 'products', false, '', ["","","",""], '', '', [], [], [], [], [], [], '', '', "");
        $this->Discontinued->OptionCount = 2;
        $this->Discontinued->DefaultErrorMessage = $Language->phrase("IncorrectField");
        $this->Discontinued->SearchOperators = ["=", "<>", "IS NULL", "IS NOT NULL"];
        $this->Fields['Discontinued'] = &$this->Discontinued;

        // EAN13
        $this->EAN13 = new DbField(
            $this, // Table
            'x_EAN13', // Variable name
            'EAN13', // Name
            '`EAN13`', // Expression
            '`EAN13`', // Basic search expression
            200, // Type
            13, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`EAN13`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->EAN13->addMethod("getDefault", fn() => "0");
        $this->EAN13->InputTextType = "text";
        $this->EAN13->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['EAN13'] = &$this->EAN13;

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
        if ($this->getCurrentMasterTable() == "categories") {
            $masterTable = Container("categories");
            if ($this->CategoryID->getSessionValue() != "") {
                $masterFilter .= "" . GetKeyFilter($masterTable->CategoryID, $this->CategoryID->getSessionValue(), $masterTable->CategoryID->DataType, $masterTable->Dbid);
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
        if ($this->getCurrentMasterTable() == "categories") {
            $masterTable = Container("categories");
            if ($this->CategoryID->getSessionValue() != "") {
                $detailFilter .= "" . GetKeyFilter($this->CategoryID, $this->CategoryID->getSessionValue(), $masterTable->CategoryID->DataType, $this->Dbid);
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
            case "categories":
                $key = $keys["CategoryID"] ?? "";
                if (EmptyValue($key)) {
                    if ($masterTable->CategoryID->Required) { // Required field and empty value
                        return ""; // Return empty filter
                    }
                    $validKeys = false;
                } elseif (!$validKeys) { // Already has empty key
                    return ""; // Return empty filter
                }
                if ($validKeys) {
                    return GetKeyFilter($masterTable->CategoryID, $keys["CategoryID"], $this->CategoryID->DataType, $this->Dbid);
                }
                break;
        }
        return null; // All null values and no required fields
    }

    // Get detail filter
    public function getDetailFilter($masterTable)
    {
        switch ($masterTable->TableVar) {
            case "categories":
                return GetKeyFilter($this->CategoryID, $masterTable->CategoryID->DbValue, $masterTable->CategoryID->DataType, $masterTable->Dbid);
        }
        return "";
    }

    // Render X Axis for chart
    public function renderChartXAxis($chartVar, $chartRow)
    {
        return $chartRow;
    }

    // Table level SQL
    public function getSqlFrom() // From
    {
        return ($this->SqlFrom != "") ? $this->SqlFrom : "products";
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
            $this->ProductID->setDbValue($conn->lastInsertId());
            $rs['ProductID'] = $this->ProductID->DbValue;
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
            if (!isset($rs['ProductID']) && !EmptyValue($this->ProductID->CurrentValue)) {
                $rs['ProductID'] = $this->ProductID->CurrentValue;
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
            if (array_key_exists('ProductID', $rs)) {
                AddFilter($where, QuotedName('ProductID', $this->Dbid) . '=' . QuotedValue($rs['ProductID'], $this->ProductID->DataType, $this->Dbid));
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
        $this->ProductID->DbValue = $row['ProductID'];
        $this->ProductName->DbValue = $row['ProductName'];
        $this->SupplierID->DbValue = $row['SupplierID'];
        $this->CategoryID->DbValue = $row['CategoryID'];
        $this->QuantityPerUnit->DbValue = $row['QuantityPerUnit'];
        $this->UnitPrice->DbValue = $row['UnitPrice'];
        $this->UnitsInStock->DbValue = $row['UnitsInStock'];
        $this->UnitsOnOrder->DbValue = $row['UnitsOnOrder'];
        $this->ReorderLevel->DbValue = $row['ReorderLevel'];
        $this->Discontinued->DbValue = $row['Discontinued'];
        $this->EAN13->DbValue = $row['EAN13'];
    }

    // Delete uploaded files
    public function deleteUploadedFiles($row)
    {
        $this->loadDbValues($row);
    }

    // Record filter WHERE clause
    protected function sqlKeyFilter()
    {
        return "`ProductID` = @ProductID@";
    }

    // Get Key
    public function getKey($current = false)
    {
        $keys = [];
        $val = $current ? $this->ProductID->CurrentValue : $this->ProductID->OldValue;
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
                $this->ProductID->CurrentValue = $keys[0];
            } else {
                $this->ProductID->OldValue = $keys[0];
            }
        }
    }

    // Get record filter
    public function getRecordFilter($row = null, $current = false)
    {
        $keyFilter = $this->sqlKeyFilter();
        if (is_array($row)) {
            $val = array_key_exists('ProductID', $row) ? $row['ProductID'] : null;
        } else {
            $val = !EmptyValue($this->ProductID->OldValue) && !$current ? $this->ProductID->OldValue : $this->ProductID->CurrentValue;
        }
        if (!is_numeric($val)) {
            return "0=1"; // Invalid key
        }
        if ($val === null) {
            return "0=1"; // Invalid key
        } else {
            $keyFilter = str_replace("@ProductID@", AdjustSql($val, $this->Dbid), $keyFilter); // Replace key value
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
        return $_SESSION[$name] ?? GetUrl("productslist");
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
        if ($pageName == "productsview") {
            return $Language->phrase("View");
        } elseif ($pageName == "productsedit") {
            return $Language->phrase("Edit");
        } elseif ($pageName == "productsadd") {
            return $Language->phrase("Add");
        }
        return "";
    }

    // API page name
    public function getApiPageName($action)
    {
        switch (strtolower($action)) {
            case Config("API_VIEW_ACTION"):
                return "ProductsView";
            case Config("API_ADD_ACTION"):
                return "ProductsAdd";
            case Config("API_EDIT_ACTION"):
                return "ProductsEdit";
            case Config("API_DELETE_ACTION"):
                return "ProductsDelete";
            case Config("API_LIST_ACTION"):
                return "ProductsList";
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
        return "productslist";
    }

    // View URL
    public function getViewUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("productsview", $parm);
        } else {
            $url = $this->keyUrl("productsview", Config("TABLE_SHOW_DETAIL") . "=");
        }
        return $this->addMasterUrl($url);
    }

    // Add URL
    public function getAddUrl($parm = "")
    {
        if ($parm != "") {
            $url = "productsadd?" . $parm;
        } else {
            $url = "productsadd";
        }
        return $this->addMasterUrl($url);
    }

    // Edit URL
    public function getEditUrl($parm = "")
    {
        $url = $this->keyUrl("productsedit", $parm);
        return $this->addMasterUrl($url);
    }

    // Inline edit URL
    public function getInlineEditUrl()
    {
        $url = $this->keyUrl("productslist", "action=edit");
        return $this->addMasterUrl($url);
    }

    // Copy URL
    public function getCopyUrl($parm = "")
    {
        $url = $this->keyUrl("productsadd", $parm);
        return $this->addMasterUrl($url);
    }

    // Inline copy URL
    public function getInlineCopyUrl()
    {
        $url = $this->keyUrl("productslist", "action=copy");
        return $this->addMasterUrl($url);
    }

    // Delete URL
    public function getDeleteUrl()
    {
        if ($this->UseAjaxActions && ConvertToBool(Param("infinitescroll")) && CurrentPageID() == "list") {
            return $this->keyUrl(GetApiUrl(Config("API_DELETE_ACTION") . "/" . $this->TableVar));
        } else {
            return $this->keyUrl("productsdelete");
        }
    }

    // Add master url
    public function addMasterUrl($url)
    {
        if ($this->getCurrentMasterTable() == "categories" && !ContainsString($url, Config("TABLE_SHOW_MASTER") . "=")) {
            $url .= (ContainsString($url, "?") ? "&" : "?") . Config("TABLE_SHOW_MASTER") . "=" . $this->getCurrentMasterTable();
            $url .= "&" . GetForeignKeyUrl("fk_CategoryID", $this->CategoryID->getSessionValue()); // Use Session Value
        }
        return $url;
    }

    public function keyToJson($htmlEncode = false)
    {
        $json = "";
        $json .= "\"ProductID\":" . JsonEncode($this->ProductID->CurrentValue, "number");
        $json = "{" . $json . "}";
        if ($htmlEncode) {
            $json = HtmlEncode($json);
        }
        return $json;
    }

    // Add key value to URL
    public function keyUrl($url, $parm = "")
    {
        if ($this->ProductID->CurrentValue !== null) {
            $url .= "/" . $this->encodeKeyValue($this->ProductID->CurrentValue);
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
            if (($keyValue = Param("ProductID") ?? Route("ProductID")) !== null) {
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
                $this->ProductID->CurrentValue = $key;
            } else {
                $this->ProductID->OldValue = $key;
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

    // Render list content
    public function renderListContent($filter)
    {
        global $Response;
        $listPage = "ProductsList";
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

        // ProductID

        // ProductName

        // SupplierID

        // CategoryID

        // QuantityPerUnit

        // UnitPrice

        // UnitsInStock

        // UnitsOnOrder

        // ReorderLevel

        // Discontinued

        // EAN13

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

        // ProductID
        $this->ProductID->HrefValue = "";
        $this->ProductID->TooltipValue = "";

        // ProductName
        $this->ProductName->HrefValue = "";
        $this->ProductName->TooltipValue = "";

        // SupplierID
        $this->SupplierID->HrefValue = "";
        $this->SupplierID->TooltipValue = "";

        // CategoryID
        $this->CategoryID->HrefValue = "";
        $this->CategoryID->TooltipValue = "";

        // QuantityPerUnit
        $this->QuantityPerUnit->HrefValue = "";
        $this->QuantityPerUnit->TooltipValue = "";

        // UnitPrice
        $this->UnitPrice->HrefValue = "";
        $this->UnitPrice->TooltipValue = "";

        // UnitsInStock
        $this->UnitsInStock->HrefValue = "";
        $this->UnitsInStock->TooltipValue = "";

        // UnitsOnOrder
        $this->UnitsOnOrder->HrefValue = "";
        $this->UnitsOnOrder->TooltipValue = "";

        // ReorderLevel
        $this->ReorderLevel->HrefValue = "";
        $this->ReorderLevel->TooltipValue = "";

        // Discontinued
        $this->Discontinued->HrefValue = "";
        $this->Discontinued->TooltipValue = "";

        // EAN13
        $this->EAN13->HrefValue = "";
        $this->EAN13->ExportHrefValue = PhpBarcode::barcode(true)->getHrefValue($this->EAN13->CurrentValue, 'EAN-13', 60);
        $this->EAN13->TooltipValue = "";

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

        // ProductID
        $this->ProductID->setupEditAttributes();
        $this->ProductID->EditValue = $this->ProductID->CurrentValue;

        // ProductName
        $this->ProductName->setupEditAttributes();
        if (!$this->ProductName->Raw) {
            $this->ProductName->CurrentValue = HtmlDecode($this->ProductName->CurrentValue);
        }
        $this->ProductName->EditValue = $this->ProductName->CurrentValue;
        $this->ProductName->PlaceHolder = RemoveHtml($this->ProductName->caption());

        // SupplierID
        $this->SupplierID->setupEditAttributes();
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
            $this->CategoryID->PlaceHolder = RemoveHtml($this->CategoryID->caption());
        }

        // QuantityPerUnit
        $this->QuantityPerUnit->setupEditAttributes();
        if (!$this->QuantityPerUnit->Raw) {
            $this->QuantityPerUnit->CurrentValue = HtmlDecode($this->QuantityPerUnit->CurrentValue);
        }
        $this->QuantityPerUnit->EditValue = $this->QuantityPerUnit->CurrentValue;
        $this->QuantityPerUnit->PlaceHolder = RemoveHtml($this->QuantityPerUnit->caption());

        // UnitPrice
        $this->UnitPrice->setupEditAttributes();
        $this->UnitPrice->EditValue = $this->UnitPrice->CurrentValue;
        $this->UnitPrice->PlaceHolder = RemoveHtml($this->UnitPrice->caption());
        if (strval($this->UnitPrice->EditValue) != "" && is_numeric($this->UnitPrice->EditValue)) {
            $this->UnitPrice->EditValue = FormatNumber($this->UnitPrice->EditValue, null);
        }

        // UnitsInStock
        $this->UnitsInStock->setupEditAttributes();
        $this->UnitsInStock->EditValue = $this->UnitsInStock->CurrentValue;
        $this->UnitsInStock->PlaceHolder = RemoveHtml($this->UnitsInStock->caption());
        if (strval($this->UnitsInStock->EditValue) != "" && is_numeric($this->UnitsInStock->EditValue)) {
            $this->UnitsInStock->EditValue = $this->UnitsInStock->EditValue;
        }

        // UnitsOnOrder
        $this->UnitsOnOrder->setupEditAttributes();
        $this->UnitsOnOrder->EditValue = $this->UnitsOnOrder->CurrentValue;
        $this->UnitsOnOrder->PlaceHolder = RemoveHtml($this->UnitsOnOrder->caption());
        if (strval($this->UnitsOnOrder->EditValue) != "" && is_numeric($this->UnitsOnOrder->EditValue)) {
            $this->UnitsOnOrder->EditValue = $this->UnitsOnOrder->EditValue;
        }

        // ReorderLevel
        $this->ReorderLevel->setupEditAttributes();
        $this->ReorderLevel->EditValue = $this->ReorderLevel->CurrentValue;
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
        $this->EAN13->EditValue = $this->EAN13->CurrentValue;
        $this->EAN13->PlaceHolder = RemoveHtml($this->EAN13->caption());

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
                    $doc->exportCaption($this->ProductID);
                    $doc->exportCaption($this->ProductName);
                    $doc->exportCaption($this->SupplierID);
                    $doc->exportCaption($this->CategoryID);
                    $doc->exportCaption($this->QuantityPerUnit);
                    $doc->exportCaption($this->UnitPrice);
                    $doc->exportCaption($this->UnitsInStock);
                    $doc->exportCaption($this->UnitsOnOrder);
                    $doc->exportCaption($this->ReorderLevel);
                    $doc->exportCaption($this->Discontinued);
                    $doc->exportCaption($this->EAN13);
                } else {
                    $doc->exportCaption($this->ProductID);
                    $doc->exportCaption($this->ProductName);
                    $doc->exportCaption($this->SupplierID);
                    $doc->exportCaption($this->CategoryID);
                    $doc->exportCaption($this->QuantityPerUnit);
                    $doc->exportCaption($this->UnitPrice);
                    $doc->exportCaption($this->UnitsInStock);
                    $doc->exportCaption($this->UnitsOnOrder);
                    $doc->exportCaption($this->ReorderLevel);
                    $doc->exportCaption($this->Discontinued);
                    $doc->exportCaption($this->EAN13);
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
                        $doc->exportField($this->ProductID);
                        $doc->exportField($this->ProductName);
                        $doc->exportField($this->SupplierID);
                        $doc->exportField($this->CategoryID);
                        $doc->exportField($this->QuantityPerUnit);
                        $doc->exportField($this->UnitPrice);
                        $doc->exportField($this->UnitsInStock);
                        $doc->exportField($this->UnitsOnOrder);
                        $doc->exportField($this->ReorderLevel);
                        $doc->exportField($this->Discontinued);
                        $doc->exportField($this->EAN13);
                    } else {
                        $doc->exportField($this->ProductID);
                        $doc->exportField($this->ProductName);
                        $doc->exportField($this->SupplierID);
                        $doc->exportField($this->CategoryID);
                        $doc->exportField($this->QuantityPerUnit);
                        $doc->exportField($this->UnitPrice);
                        $doc->exportField($this->UnitsInStock);
                        $doc->exportField($this->UnitsOnOrder);
                        $doc->exportField($this->ReorderLevel);
                        $doc->exportField($this->Discontinued);
                        $doc->exportField($this->EAN13);
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
