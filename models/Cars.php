<?php

namespace PHPMaker2023\demo2023;

use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Table class for cars
 */
class Cars extends DbTable
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
    public $ModalSearch = true;
    public $ModalView = true;
    public $ModalAdd = true;
    public $ModalEdit = true;
    public $ModalUpdate = true;
    public $InlineDelete = true;
    public $ModalGridAdd = false;
    public $ModalGridEdit = false;
    public $ModalMultiEdit = false;

    // Fields
    public $ID;
    public $Trademark;
    public $Model;
    public $HP;
    public $Cylinders;
    public $TransmissionSpeeds;
    public $TransmissAutomatic;
    public $MPGCity;
    public $MPGHighway;
    public $Description;
    public $Price;
    public $Picture;
    public $Doors;
    public $Torque;

    // Page ID
    public $PageID = ""; // To be overridden by subclass

    // Constructor
    public function __construct()
    {
        parent::__construct();
        global $Language, $CurrentLanguage, $CurrentLocale;

        // Language object
        $Language = Container("language");
        $this->TableVar = "cars";
        $this->TableName = 'cars';
        $this->TableType = "TABLE";
        $this->ImportUseTransaction = $this->supportsTransaction() && Config("IMPORT_USE_TRANSACTION");
        $this->UseTransaction = $this->supportsTransaction() && Config("USE_TRANSACTION");

        // Update Table
        $this->UpdateTable = "cars";
        $this->Dbid = 'DB';
        $this->ExportAll = true;
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
        $this->DetailAdd = true; // Allow detail add
        $this->DetailEdit = true; // Allow detail edit
        $this->DetailView = false; // Allow detail view
        $this->ShowMultipleDetails = false; // Show multiple details
        $this->GridAddRowCount = 5;
        $this->AllowAddDeleteRow = true; // Allow add/delete row
        $this->UseAjaxActions = $this->UseAjaxActions || Config("USE_AJAX_ACTIONS");
        $this->UserIDAllowSecurity = Config("DEFAULT_USER_ID_ALLOW_SECURITY"); // Default User ID allowed permissions
        $this->BasicSearch = new BasicSearch($this);

        // ID
        $this->ID = new DbField(
            $this, // Table
            'x_ID', // Variable name
            'ID', // Name
            '`ID`', // Expression
            '`ID`', // Basic search expression
            3, // Type
            11, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`ID`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'NO' // Edit Tag
        );
        $this->ID->InputTextType = "text";
        $this->ID->IsAutoIncrement = true; // Autoincrement field
        $this->ID->IsPrimaryKey = true; // Primary key field
        $this->ID->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->ID->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['ID'] = &$this->ID;

        // Trademark
        $this->Trademark = new DbField(
            $this, // Table
            'x_Trademark', // Variable name
            'Trademark', // Name
            '`Trademark`', // Expression
            '`Trademark`', // Basic search expression
            3, // Type
            11, // Size
            -1, // Date/Time format
            false, // Is upload field
            'EV__Trademark', // Virtual expression
            true, // Is virtual
            true, // Force selection
            true, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'SELECT' // Edit Tag
        );
        $this->Trademark->InputTextType = "text";
        $this->Trademark->setSelectMultiple(false); // Select one
        $this->Trademark->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->Trademark->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->Trademark->Lookup = new Lookup('Trademark', 'trademarks', false, 'ID', ["Trademark","","",""], '', '', [], ["x_Model"], [], [], [], [], '', '', "`Trademark`");
        $this->Trademark->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->Trademark->SearchOperators = ["=", "<>", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['Trademark'] = &$this->Trademark;

        // Model
        $this->Model = new DbField(
            $this, // Table
            'x_Model', // Variable name
            'Model', // Name
            '`Model`', // Expression
            '`Model`', // Basic search expression
            3, // Type
            11, // Size
            -1, // Date/Time format
            false, // Is upload field
            'EV__Model', // Virtual expression
            true, // Is virtual
            true, // Force selection
            true, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'SELECT' // Edit Tag
        );
        $this->Model->InputTextType = "text";
        $this->Model->setSelectMultiple(false); // Select one
        $this->Model->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->Model->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->Model->Lookup = new Lookup('Model', 'models', true, 'ID', ["Model","","",""], '', '', ["x_Trademark"], [], ["Trademark"], ["x_Trademark"], [], [], '', '', "`Model`");
        $this->Model->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->Model->SearchOperators = ["=", "<>", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['Model'] = &$this->Model;

        // HP
        $this->HP = new DbField(
            $this, // Table
            'x_HP', // Variable name
            'HP', // Name
            '`HP`', // Expression
            '`HP`', // Basic search expression
            200, // Type
            255, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`HP`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->HP->InputTextType = "text";
        $this->HP->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['HP'] = &$this->HP;

        // Cylinders
        $this->Cylinders = new DbField(
            $this, // Table
            'x_Cylinders', // Variable name
            'Cylinders', // Name
            '`Cylinders`', // Expression
            '`Cylinders`', // Basic search expression
            3, // Type
            11, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`Cylinders`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->Cylinders->InputTextType = "text";
        $this->Cylinders->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->Cylinders->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['Cylinders'] = &$this->Cylinders;

        // Transmission Speeds
        $this->TransmissionSpeeds = new DbField(
            $this, // Table
            'x_TransmissionSpeeds', // Variable name
            'Transmission Speeds', // Name
            '`Transmission Speeds`', // Expression
            '`Transmission Speeds`', // Basic search expression
            200, // Type
            255, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`Transmission Speeds`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->TransmissionSpeeds->InputTextType = "text";
        $this->TransmissionSpeeds->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['Transmission Speeds'] = &$this->TransmissionSpeeds;

        // TransmissAutomatic
        $this->TransmissAutomatic = new DbField(
            $this, // Table
            'x_TransmissAutomatic', // Variable name
            'TransmissAutomatic', // Name
            '`TransmissAutomatic`', // Expression
            '`TransmissAutomatic`', // Basic search expression
            16, // Type
            1, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`TransmissAutomatic`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'CHECKBOX' // Edit Tag
        );
        $this->TransmissAutomatic->InputTextType = "text";
        $this->TransmissAutomatic->DataType = DATATYPE_BOOLEAN;
        $this->TransmissAutomatic->Lookup = new Lookup('TransmissAutomatic', 'cars', false, '', ["","","",""], '', '', [], [], [], [], [], [], '', '', "");
        $this->TransmissAutomatic->OptionCount = 2;
        $this->TransmissAutomatic->DefaultErrorMessage = $Language->phrase("IncorrectField");
        $this->TransmissAutomatic->SearchOperators = ["=", "<>", "IS NULL", "IS NOT NULL"];
        $this->Fields['TransmissAutomatic'] = &$this->TransmissAutomatic;

        // MPG City
        $this->MPGCity = new DbField(
            $this, // Table
            'x_MPGCity', // Variable name
            'MPG City', // Name
            '`MPG City`', // Expression
            '`MPG City`', // Basic search expression
            3, // Type
            11, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`MPG City`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->MPGCity->InputTextType = "text";
        $this->MPGCity->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->MPGCity->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['MPG City'] = &$this->MPGCity;

        // MPG Highway
        $this->MPGHighway = new DbField(
            $this, // Table
            'x_MPGHighway', // Variable name
            'MPG Highway', // Name
            '`MPG Highway`', // Expression
            '`MPG Highway`', // Basic search expression
            3, // Type
            11, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`MPG Highway`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->MPGHighway->InputTextType = "text";
        $this->MPGHighway->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->MPGHighway->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['MPG Highway'] = &$this->MPGHighway;

        // Description
        $this->Description = new DbField(
            $this, // Table
            'x_Description', // Variable name
            'Description', // Name
            '`Description`', // Expression
            '`Description`', // Basic search expression
            201, // Type
            -1, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`Description`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXTAREA' // Edit Tag
        );
        $this->Description->InputTextType = "text";
        $this->Description->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['Description'] = &$this->Description;

        // Price
        $this->Price = new DbField(
            $this, // Table
            'x_Price', // Variable name
            'Price', // Name
            '`Price`', // Expression
            '`Price`', // Basic search expression
            5, // Type
            22, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`Price`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->Price->InputTextType = "text";
        $this->Price->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->Price->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['Price'] = &$this->Price;

        // Picture
        $this->Picture = new DbField(
            $this, // Table
            'x_Picture', // Variable name
            'Picture', // Name
            '`Picture`', // Expression
            '`Picture`', // Basic search expression
            205, // Type
            0, // Size
            -1, // Date/Time format
            true, // Is upload field
            '`Picture`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'IMAGE', // View Tag
            'FILE' // Edit Tag
        );
        $this->Picture->InputTextType = "text";
        $this->Picture->Sortable = false; // Allow sort
        $this->Picture->ImageResize = true;
        $this->Picture->SearchOperators = ["=", "<>", "IS NULL", "IS NOT NULL"];
        $this->Fields['Picture'] = &$this->Picture;

        // Doors
        $this->Doors = new DbField(
            $this, // Table
            'x_Doors', // Variable name
            'Doors', // Name
            '`Doors`', // Expression
            '`Doors`', // Basic search expression
            3, // Type
            11, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`Doors`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->Doors->InputTextType = "text";
        $this->Doors->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->Doors->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['Doors'] = &$this->Doors;

        // Torque
        $this->Torque = new DbField(
            $this, // Table
            'x_Torque', // Variable name
            'Torque', // Name
            '`Torque`', // Expression
            '`Torque`', // Basic search expression
            200, // Type
            255, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`Torque`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->Torque->InputTextType = "text";
        $this->Torque->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['Torque'] = &$this->Torque;

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
            $sortFieldList = ($fld->VirtualExpression != "") ? $fld->VirtualExpression : $sortField;
            $orderBy = in_array($curSort, ["ASC", "DESC"]) ? $sortFieldList . " " . $curSort : "";
            $this->setSessionOrderByList($orderBy); // Save to Session
        }
    }

    // Update field sort
    public function updateFieldSort()
    {
        $orderBy = $this->useVirtualFields() ? $this->getSessionOrderByList() : $this->getSessionOrderBy(); // Get ORDER BY from Session
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

    // Session ORDER BY for List page
    public function getSessionOrderByList()
    {
        return Session(PROJECT_NAME . "_" . $this->TableVar . "_" . Config("TABLE_ORDER_BY_LIST"));
    }

    public function setSessionOrderByList($v)
    {
        $_SESSION[PROJECT_NAME . "_" . $this->TableVar . "_" . Config("TABLE_ORDER_BY_LIST")] = $v;
    }

    // Render X Axis for chart
    public function renderChartXAxis($chartVar, $chartRow)
    {
        return $chartRow;
    }

    // Table level SQL
    public function getSqlFrom() // From
    {
        return ($this->SqlFrom != "") ? $this->SqlFrom : "cars";
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

    public function getSqlSelectList() // Select for List page
    {
        if ($this->SqlSelectList) {
            return $this->SqlSelectList;
        }
        $from = "(SELECT *, (SELECT `Trademark` FROM trademarks TMP_LOOKUPTABLE WHERE TMP_LOOKUPTABLE.ID = cars.Trademark LIMIT 1) AS EV__Trademark, (SELECT DISTINCT `Model` FROM models TMP_LOOKUPTABLE WHERE TMP_LOOKUPTABLE.ID = cars.Model LIMIT 1) AS EV__Model FROM cars)";
        return $from . " TMP_TABLE";
    }

    public function sqlSelectList() // For backward compatibility
    {
        return $this->getSqlSelectList();
    }

    public function setSqlSelectList($v)
    {
        $this->SqlSelectList = $v;
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
        if ($this->useVirtualFields()) {
            $select = "*";
            $from = $this->getSqlSelectList();
            $sort = $this->UseSessionForListSql ? $this->getSessionOrderByList() : "";
        } else {
            $select = $this->getSqlSelect();
            $from = $this->getSqlFrom();
            $sort = $this->UseSessionForListSql ? $this->getSessionOrderBy() : "";
        }
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
        $sort = ($this->useVirtualFields()) ? $this->getSessionOrderByList() : $this->getSessionOrderBy();
        if ($orderBy != "" && $sort != "") {
            $orderBy .= ", " . $sort;
        } elseif ($sort != "") {
            $orderBy = $sort;
        }
        return $orderBy;
    }

    // Check if virtual fields is used in SQL
    protected function useVirtualFields()
    {
        $where = $this->UseSessionForListSql ? $this->getSessionWhere() : $this->CurrentFilter;
        $orderBy = $this->UseSessionForListSql ? $this->getSessionOrderByList() : "";
        if ($where != "") {
            $where = " " . str_replace(["(", ")"], ["", ""], $where) . " ";
        }
        if ($orderBy != "") {
            $orderBy = " " . str_replace(["(", ")"], ["", ""], $orderBy) . " ";
        }
        if (
            $this->Trademark->AdvancedSearch->SearchValue != "" ||
            $this->Trademark->AdvancedSearch->SearchValue2 != "" ||
            ContainsString($where, " " . $this->Trademark->VirtualExpression . " ")
        ) {
            return true;
        }
        if (ContainsString($orderBy, " " . $this->Trademark->VirtualExpression . " ")) {
            return true;
        }
        if (
            $this->Model->AdvancedSearch->SearchValue != "" ||
            $this->Model->AdvancedSearch->SearchValue2 != "" ||
            ContainsString($where, " " . $this->Model->VirtualExpression . " ")
        ) {
            return true;
        }
        if (ContainsString($orderBy, " " . $this->Model->VirtualExpression . " ")) {
            return true;
        }
        return false;
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
        if ($this->useVirtualFields()) {
            $sql = $this->buildSelectSql("*", $this->getSqlSelectList(), $this->getSqlWhere(), $groupBy, $having, "", $filter, "");
        } else {
            $sql = $this->buildSelectSql($select, $this->getSqlFrom(), $this->getSqlWhere(), $groupBy, $having, "", $filter, "");
        }
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
            $this->ID->setDbValue($conn->lastInsertId());
            $rs['ID'] = $this->ID->DbValue;
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
            if (!isset($rs['ID']) && !EmptyValue($this->ID->CurrentValue)) {
                $rs['ID'] = $this->ID->CurrentValue;
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
            if (array_key_exists('ID', $rs)) {
                AddFilter($where, QuotedName('ID', $this->Dbid) . '=' . QuotedValue($rs['ID'], $this->ID->DataType, $this->Dbid));
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
        $this->ID->DbValue = $row['ID'];
        $this->Trademark->DbValue = $row['Trademark'];
        $this->Model->DbValue = $row['Model'];
        $this->HP->DbValue = $row['HP'];
        $this->Cylinders->DbValue = $row['Cylinders'];
        $this->TransmissionSpeeds->DbValue = $row['Transmission Speeds'];
        $this->TransmissAutomatic->DbValue = $row['TransmissAutomatic'];
        $this->MPGCity->DbValue = $row['MPG City'];
        $this->MPGHighway->DbValue = $row['MPG Highway'];
        $this->Description->DbValue = $row['Description'];
        $this->Price->DbValue = $row['Price'];
        $this->Picture->Upload->DbValue = $row['Picture'];
        $this->Doors->DbValue = $row['Doors'];
        $this->Torque->DbValue = $row['Torque'];
    }

    // Delete uploaded files
    public function deleteUploadedFiles($row)
    {
        $this->loadDbValues($row);
    }

    // Record filter WHERE clause
    protected function sqlKeyFilter()
    {
        return "`ID` = @ID@";
    }

    // Get Key
    public function getKey($current = false)
    {
        $keys = [];
        $val = $current ? $this->ID->CurrentValue : $this->ID->OldValue;
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
                $this->ID->CurrentValue = $keys[0];
            } else {
                $this->ID->OldValue = $keys[0];
            }
        }
    }

    // Get record filter
    public function getRecordFilter($row = null, $current = false)
    {
        $keyFilter = $this->sqlKeyFilter();
        if (is_array($row)) {
            $val = array_key_exists('ID', $row) ? $row['ID'] : null;
        } else {
            $val = !EmptyValue($this->ID->OldValue) && !$current ? $this->ID->OldValue : $this->ID->CurrentValue;
        }
        if (!is_numeric($val)) {
            return "0=1"; // Invalid key
        }
        if ($val === null) {
            return "0=1"; // Invalid key
        } else {
            $keyFilter = str_replace("@ID@", AdjustSql($val, $this->Dbid), $keyFilter); // Replace key value
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
        return $_SESSION[$name] ?? GetUrl("carslist");
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
        if ($pageName == "carsview") {
            return $Language->phrase("View");
        } elseif ($pageName == "carsedit") {
            return $Language->phrase("Edit");
        } elseif ($pageName == "carsadd") {
            return $Language->phrase("Add");
        }
        return "";
    }

    // API page name
    public function getApiPageName($action)
    {
        switch (strtolower($action)) {
            case Config("API_VIEW_ACTION"):
                return "CarsView";
            case Config("API_ADD_ACTION"):
                return "CarsAdd";
            case Config("API_EDIT_ACTION"):
                return "CarsEdit";
            case Config("API_DELETE_ACTION"):
                return "CarsDelete";
            case Config("API_LIST_ACTION"):
                return "CarsList";
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
        return "carslist";
    }

    // View URL
    public function getViewUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("carsview", $parm);
        } else {
            $url = $this->keyUrl("carsview", Config("TABLE_SHOW_DETAIL") . "=");
        }
        return $this->addMasterUrl($url);
    }

    // Add URL
    public function getAddUrl($parm = "")
    {
        if ($parm != "") {
            $url = "carsadd?" . $parm;
        } else {
            $url = "carsadd";
        }
        return $this->addMasterUrl($url);
    }

    // Edit URL
    public function getEditUrl($parm = "")
    {
        $url = $this->keyUrl("carsedit", $parm);
        return $this->addMasterUrl($url);
    }

    // Inline edit URL
    public function getInlineEditUrl()
    {
        $url = $this->keyUrl("carslist", "action=edit");
        return $this->addMasterUrl($url);
    }

    // Copy URL
    public function getCopyUrl($parm = "")
    {
        $url = $this->keyUrl("carsadd", $parm);
        return $this->addMasterUrl($url);
    }

    // Inline copy URL
    public function getInlineCopyUrl()
    {
        $url = $this->keyUrl("carslist", "action=copy");
        return $this->addMasterUrl($url);
    }

    // Delete URL
    public function getDeleteUrl()
    {
        if ($this->UseAjaxActions && ConvertToBool(Param("infinitescroll")) && CurrentPageID() == "list") {
            return $this->keyUrl(GetApiUrl(Config("API_DELETE_ACTION") . "/" . $this->TableVar));
        } else {
            return $this->keyUrl("carsdelete");
        }
    }

    // Add master url
    public function addMasterUrl($url)
    {
        return $url;
    }

    public function keyToJson($htmlEncode = false)
    {
        $json = "";
        $json .= "\"ID\":" . JsonEncode($this->ID->CurrentValue, "number");
        $json = "{" . $json . "}";
        if ($htmlEncode) {
            $json = HtmlEncode($json);
        }
        return $json;
    }

    // Add key value to URL
    public function keyUrl($url, $parm = "")
    {
        if ($this->ID->CurrentValue !== null) {
            $url .= "/" . $this->encodeKeyValue($this->ID->CurrentValue);
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
            if (($keyValue = Param("ID") ?? Route("ID")) !== null) {
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
                $this->ID->CurrentValue = $key;
            } else {
                $this->ID->OldValue = $key;
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
        $this->ID->setDbValue($row['ID']);
        $this->Trademark->setDbValue($row['Trademark']);
        $this->Model->setDbValue($row['Model']);
        $this->HP->setDbValue($row['HP']);
        $this->Cylinders->setDbValue($row['Cylinders']);
        $this->TransmissionSpeeds->setDbValue($row['Transmission Speeds']);
        $this->TransmissAutomatic->setDbValue($row['TransmissAutomatic']);
        $this->MPGCity->setDbValue($row['MPG City']);
        $this->MPGHighway->setDbValue($row['MPG Highway']);
        $this->Description->setDbValue($row['Description']);
        $this->Price->setDbValue($row['Price']);
        $this->Picture->Upload->DbValue = $row['Picture'];
        $this->Doors->setDbValue($row['Doors']);
        $this->Torque->setDbValue($row['Torque']);
    }

    // Render list content
    public function renderListContent($filter)
    {
        global $Response;
        $listPage = "CarsList";
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

        // Transmission Speeds
        $this->TransmissionSpeeds->ViewValue = $this->TransmissionSpeeds->CurrentValue;

        // TransmissAutomatic
        if (ConvertToBool($this->TransmissAutomatic->CurrentValue)) {
            $this->TransmissAutomatic->ViewValue = $this->TransmissAutomatic->tagCaption(1) != "" ? $this->TransmissAutomatic->tagCaption(1) : "Yes";
        } else {
            $this->TransmissAutomatic->ViewValue = $this->TransmissAutomatic->tagCaption(2) != "" ? $this->TransmissAutomatic->tagCaption(2) : "No";
        }

        // MPG City
        $this->MPGCity->ViewValue = $this->MPGCity->CurrentValue;

        // MPG Highway
        $this->MPGHighway->ViewValue = $this->MPGHighway->CurrentValue;

        // Description
        $this->Description->ViewValue = $this->Description->CurrentValue;

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

        // Trademark
        $this->Trademark->HrefValue = "";
        $this->Trademark->TooltipValue = "";

        // Model
        $this->Model->HrefValue = "";
        if (!$this->isExport()) {
            $this->Model->TooltipValue = strval($this->Description->CurrentValue);
            $this->Model->TooltipWidth = 400;
            if ($this->Model->HrefValue == "") {
                $this->Model->HrefValue = "javascript:void(0);";
            }
            $this->Model->LinkAttrs->appendClass("ew-tooltip-link");
            $this->Model->LinkAttrs["data-tooltip-id"] = "tt_cars_x" . (($this->RowType != ROWTYPE_MASTER) ? @$this->RowCount : "") . "_Model";
            $this->Model->LinkAttrs["data-tooltip-width"] = $this->Model->TooltipWidth;
            $this->Model->LinkAttrs["data-bs-placement"] = IsRTL() ? "left" : "right";
        }

        // HP
        $this->HP->HrefValue = "";
        $this->HP->TooltipValue = "";

        // Cylinders
        $this->Cylinders->HrefValue = "";
        $this->Cylinders->TooltipValue = "";

        // Transmission Speeds
        $this->TransmissionSpeeds->HrefValue = "";
        $this->TransmissionSpeeds->TooltipValue = "";

        // TransmissAutomatic
        $this->TransmissAutomatic->HrefValue = "";
        $this->TransmissAutomatic->TooltipValue = "";

        // MPG City
        $this->MPGCity->HrefValue = "";
        $this->MPGCity->TooltipValue = "";

        // MPG Highway
        $this->MPGHighway->HrefValue = "";
        $this->MPGHighway->TooltipValue = "";

        // Description
        $this->Description->HrefValue = "";
        $this->Description->TooltipValue = "";

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
            $this->Picture->LinkAttrs["data-rel"] = "cars_x_Picture";
            $this->Picture->LinkAttrs->appendClass("ew-lightbox");
        }

        // Doors
        $this->Doors->HrefValue = "";
        $this->Doors->TooltipValue = "";

        // Torque
        $this->Torque->HrefValue = "";
        $this->Torque->TooltipValue = "";

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

        // ID
        $this->ID->setupEditAttributes();
        $this->ID->EditValue = $this->ID->CurrentValue;

        // Trademark
        $this->Trademark->setupEditAttributes();
        $this->Trademark->PlaceHolder = RemoveHtml($this->Trademark->caption());

        // Model
        $this->Model->setupEditAttributes();
        $this->Model->PlaceHolder = RemoveHtml($this->Model->caption());

        // HP
        $this->HP->setupEditAttributes();
        if (!$this->HP->Raw) {
            $this->HP->CurrentValue = HtmlDecode($this->HP->CurrentValue);
        }
        $this->HP->EditValue = $this->HP->CurrentValue;
        $this->HP->PlaceHolder = RemoveHtml($this->HP->caption());

        // Cylinders
        $this->Cylinders->setupEditAttributes();
        $this->Cylinders->EditValue = $this->Cylinders->CurrentValue;
        $this->Cylinders->PlaceHolder = RemoveHtml($this->Cylinders->caption());
        if (strval($this->Cylinders->EditValue) != "" && is_numeric($this->Cylinders->EditValue)) {
            $this->Cylinders->EditValue = $this->Cylinders->EditValue;
        }

        // Transmission Speeds
        $this->TransmissionSpeeds->setupEditAttributes();
        if (!$this->TransmissionSpeeds->Raw) {
            $this->TransmissionSpeeds->CurrentValue = HtmlDecode($this->TransmissionSpeeds->CurrentValue);
        }
        $this->TransmissionSpeeds->EditValue = $this->TransmissionSpeeds->CurrentValue;
        $this->TransmissionSpeeds->PlaceHolder = RemoveHtml($this->TransmissionSpeeds->caption());

        // TransmissAutomatic
        $this->TransmissAutomatic->EditValue = $this->TransmissAutomatic->options(false);
        $this->TransmissAutomatic->PlaceHolder = RemoveHtml($this->TransmissAutomatic->caption());

        // MPG City
        $this->MPGCity->setupEditAttributes();
        $this->MPGCity->EditValue = $this->MPGCity->CurrentValue;
        $this->MPGCity->PlaceHolder = RemoveHtml($this->MPGCity->caption());
        if (strval($this->MPGCity->EditValue) != "" && is_numeric($this->MPGCity->EditValue)) {
            $this->MPGCity->EditValue = $this->MPGCity->EditValue;
        }

        // MPG Highway
        $this->MPGHighway->setupEditAttributes();
        $this->MPGHighway->EditValue = $this->MPGHighway->CurrentValue;
        $this->MPGHighway->PlaceHolder = RemoveHtml($this->MPGHighway->caption());
        if (strval($this->MPGHighway->EditValue) != "" && is_numeric($this->MPGHighway->EditValue)) {
            $this->MPGHighway->EditValue = $this->MPGHighway->EditValue;
        }

        // Description
        $this->Description->setupEditAttributes();
        $this->Description->EditValue = $this->Description->CurrentValue;
        $this->Description->PlaceHolder = RemoveHtml($this->Description->caption());

        // Price
        $this->Price->setupEditAttributes();
        $this->Price->EditValue = $this->Price->CurrentValue;
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

        // Doors
        $this->Doors->setupEditAttributes();
        $this->Doors->EditValue = $this->Doors->CurrentValue;
        $this->Doors->PlaceHolder = RemoveHtml($this->Doors->caption());
        if (strval($this->Doors->EditValue) != "" && is_numeric($this->Doors->EditValue)) {
            $this->Doors->EditValue = FormatNumber($this->Doors->EditValue, null);
        }

        // Torque
        $this->Torque->setupEditAttributes();
        if (!$this->Torque->Raw) {
            $this->Torque->CurrentValue = HtmlDecode($this->Torque->CurrentValue);
        }
        $this->Torque->EditValue = $this->Torque->CurrentValue;
        $this->Torque->PlaceHolder = RemoveHtml($this->Torque->caption());

        // Call Row Rendered event
        $this->rowRendered();
    }

    // Aggregate list row values
    public function aggregateListRowValues()
    {
            $this->Trademark->Count++; // Increment count
    }

    // Aggregate list row (for rendering)
    public function aggregateListRow()
    {
            $this->Trademark->CurrentValue = $this->Trademark->Count;
            $this->Trademark->ViewValue = $this->Trademark->CurrentValue;
            $this->Trademark->HrefValue = ""; // Clear href value

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
                    $doc->exportCaption($this->ID);
                    $doc->exportCaption($this->Trademark);
                    $doc->exportCaption($this->Model);
                    $doc->exportCaption($this->HP);
                    $doc->exportCaption($this->Cylinders);
                    $doc->exportCaption($this->TransmissionSpeeds);
                    $doc->exportCaption($this->TransmissAutomatic);
                    $doc->exportCaption($this->MPGCity);
                    $doc->exportCaption($this->MPGHighway);
                    $doc->exportCaption($this->Description);
                    $doc->exportCaption($this->Price);
                    $doc->exportCaption($this->Picture);
                    $doc->exportCaption($this->Doors);
                    $doc->exportCaption($this->Torque);
                } else {
                    $doc->exportCaption($this->ID);
                    $doc->exportCaption($this->Trademark);
                    $doc->exportCaption($this->Model);
                    $doc->exportCaption($this->HP);
                    $doc->exportCaption($this->Cylinders);
                    $doc->exportCaption($this->Price);
                    $doc->exportCaption($this->Picture);
                    $doc->exportCaption($this->Doors);
                    $doc->exportCaption($this->Torque);
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
                $this->aggregateListRowValues(); // Aggregate row values

                // Render row
                $this->RowType = ROWTYPE_VIEW; // Render view
                $this->resetAttributes();
                $this->renderListRow();
                if (!$doc->ExportCustom) {
                    $doc->beginExportRow($rowCnt); // Allow CSS styles if enabled
                    if ($exportPageType == "view") {
                        $doc->exportField($this->ID);
                        $doc->exportField($this->Trademark);
                        $doc->exportField($this->Model);
                        $doc->exportField($this->HP);
                        $doc->exportField($this->Cylinders);
                        $doc->exportField($this->TransmissionSpeeds);
                        $doc->exportField($this->TransmissAutomatic);
                        $doc->exportField($this->MPGCity);
                        $doc->exportField($this->MPGHighway);
                        $doc->exportField($this->Description);
                        $doc->exportField($this->Price);
                        $doc->exportField($this->Picture);
                        $doc->exportField($this->Doors);
                        $doc->exportField($this->Torque);
                    } else {
                        $doc->exportField($this->ID);
                        $doc->exportField($this->Trademark);
                        $doc->exportField($this->Model);
                        $doc->exportField($this->HP);
                        $doc->exportField($this->Cylinders);
                        $doc->exportField($this->Price);
                        $doc->exportField($this->Picture);
                        $doc->exportField($this->Doors);
                        $doc->exportField($this->Torque);
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

        // Export aggregates (horizontal format only)
        if ($doc->Horizontal) {
            $this->RowType = ROWTYPE_AGGREGATE;
            $this->resetAttributes();
            $this->aggregateListRow();
            if (!$doc->ExportCustom) {
                $doc->beginExportRow(-1);
                $doc->exportAggregate($this->ID, '');
                $doc->exportAggregate($this->Trademark, 'COUNT');
                $doc->exportAggregate($this->Model, '');
                $doc->exportAggregate($this->HP, '');
                $doc->exportAggregate($this->Cylinders, '');
                $doc->exportAggregate($this->Price, '');
                $doc->exportAggregate($this->Picture, '');
                $doc->exportAggregate($this->Doors, '');
                $doc->exportAggregate($this->Torque, '');
                $doc->endExportRow();
            }
        }
        if (!$doc->ExportCustom) {
            $doc->exportTableFooter();
        }
    }

    // Get file data
    public function getFileData($fldparm, $key, $resize, $width = 0, $height = 0, $plugins = [])
    {
        global $DownloadFileName;
        $width = ($width > 0) ? $width : Config("THUMBNAIL_DEFAULT_WIDTH");
        $height = ($height > 0) ? $height : Config("THUMBNAIL_DEFAULT_HEIGHT");

        // Set up field name / file name field / file type field
        $fldName = "";
        $fileNameFld = "";
        $fileTypeFld = "";
        if ($fldparm == 'Picture') {
            $fldName = "Picture";
        } else {
            return false; // Incorrect field
        }

        // Set up key values
        $ar = explode(Config("COMPOSITE_KEY_SEPARATOR"), $key);
        if (count($ar) == 1) {
            $this->ID->CurrentValue = $ar[0];
        } else {
            return false; // Incorrect key
        }

        // Set up filter (WHERE Clause)
        $filter = $this->getRecordFilter();
        $this->CurrentFilter = $filter;
        $sql = $this->getCurrentSql();
        $conn = $this->getConnection();
        $dbtype = GetConnectionType($this->Dbid);
        if ($row = $conn->fetchAssociative($sql)) {
            $val = $row[$fldName];
            if (!EmptyValue($val)) {
                $fld = $this->Fields[$fldName];

                // Binary data
                if ($fld->DataType == DATATYPE_BLOB) {
                    if ($dbtype != "MYSQL") {
                        if (is_resource($val) && get_resource_type($val) == "stream") { // Byte array
                            $val = stream_get_contents($val);
                        }
                    }
                    if ($resize) {
                        ResizeBinary($val, $width, $height, $plugins);
                    }

                    // Write file type
                    if ($fileTypeFld != "" && !EmptyValue($row[$fileTypeFld])) {
                        AddHeader("Content-type", $row[$fileTypeFld]);
                    } else {
                        AddHeader("Content-type", ContentType($val));
                    }

                    // Write file name
                    $downloadPdf = !Config("EMBED_PDF") && Config("DOWNLOAD_PDF_FILE");
                    if ($fileNameFld != "" && !EmptyValue($row[$fileNameFld])) {
                        $fileName = $row[$fileNameFld];
                        $ext = strtolower($pathinfo["extension"] ?? "");
                        $isPdf = SameText($ext, "pdf");
                        if ($downloadPdf || !$isPdf) { // Skip header if not download PDF
                            AddHeader("Content-Disposition", "attachment; filename=\"" . $fileName . "\"");
                        }
                    } else {
                        $ext = ContentExtension($val);
                        $isPdf = SameText($ext, ".pdf");
                        if ($isPdf && $downloadPdf) { // Add header if download PDF
                            AddHeader("Content-Disposition", "attachment" . ($DownloadFileName ? "; filename=\"" . $DownloadFileName . "\"" : ""));
                        }
                    }

                    // Write file data
                    if (
                        StartsString("PK", $val) &&
                        ContainsString($val, "[Content_Types].xml") &&
                        ContainsString($val, "_rels") &&
                        ContainsString($val, "docProps")
                    ) { // Fix Office 2007 documents
                        if (!EndsString("\0\0\0", $val)) { // Not ends with 3 or 4 \0
                            $val .= "\0\0\0\0";
                        }
                    }

                    // Clear any debug message
                    if (ob_get_length()) {
                        ob_end_clean();
                    }

                    // Write binary data
                    Write($val);

                // Upload to folder
                } else {
                    if ($fld->UploadMultiple) {
                        $files = explode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $val);
                    } else {
                        $files = [$val];
                    }
                    $data = [];
                    $ar = [];
                    if ($fld->hasMethod("getUploadPath")) { // Check field level upload path
                        $fld->UploadPath = $fld->getUploadPath();
                    }
                    foreach ($files as $file) {
                        if (!EmptyValue($file)) {
                            if (Config("ENCRYPT_FILE_PATH")) {
                                $ar[$file] = FullUrl(GetApiUrl(Config("API_FILE_ACTION") .
                                    "/" . $this->TableVar . "/" . Encrypt($fld->physicalUploadPath() . $file)));
                            } else {
                                $ar[$file] = FullUrl($fld->hrefPath() . $file);
                            }
                        }
                    }
                    $data[$fld->Param] = $ar;
                    WriteJson($data);
                }
            }
            return true;
        }
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
    public function rowRendered() {
    	// Change the row color in List page by Bootstrap classes
    	if ($this->Trademark->ViewValue == "BMW") // List page only
    		$this->RowAttrs["class"] = "info";

    	// Change the table cell color
    	if ($this->PageID == "list" || $this->PageID == "view") { // List/View page only
    		if ($this->Cylinders->CurrentValue == 4) {
    			$this->Cylinders->CellAttrs["class"] = "bg-primary";
    		} elseif ($this->Cylinders->CurrentValue == 6) {
    			$this->Cylinders->CellAttrs["class"] = "bg-success";
    		} elseif ($this->Cylinders->CurrentValue == 8) {
    			$this->Cylinders->CellAttrs["class"] = "bg-danger";
    		}
    	}
    }

    // User ID Filtering event
    public function userIdFiltering(&$filter)
    {
        // Enter your code here
    }
}
