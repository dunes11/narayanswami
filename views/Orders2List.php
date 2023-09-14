<?php

namespace PHPMaker2023\demo2023;

// Page object
$Orders2List = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { orders2: currentTable } });
var currentPageID = ew.PAGE_ID = "list";
var currentForm;
var <?= $Page->FormName ?>;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("<?= $Page->FormName ?>")
        .setPageId("list")
        .setSubmitWithFetch(<?= $Page->UseAjaxActions ? "true" : "false" ?>)
        .setFormKeyCountName("<?= $Page->FormKeyCountName ?>")

        // Add fields
        .setFields([
            ["OrderID", [fields.OrderID.visible && fields.OrderID.required ? ew.Validators.required(fields.OrderID.caption) : null], fields.OrderID.isInvalid],
            ["CustomerID", [fields.CustomerID.visible && fields.CustomerID.required ? ew.Validators.required(fields.CustomerID.caption) : null], fields.CustomerID.isInvalid],
            ["EmployeeID", [fields.EmployeeID.visible && fields.EmployeeID.required ? ew.Validators.required(fields.EmployeeID.caption) : null], fields.EmployeeID.isInvalid],
            ["OrderDate", [fields.OrderDate.visible && fields.OrderDate.required ? ew.Validators.required(fields.OrderDate.caption) : null, ew.Validators.datetime(fields.OrderDate.clientFormatPattern)], fields.OrderDate.isInvalid],
            ["RequiredDate", [fields.RequiredDate.visible && fields.RequiredDate.required ? ew.Validators.required(fields.RequiredDate.caption) : null, ew.Validators.datetime(fields.RequiredDate.clientFormatPattern)], fields.RequiredDate.isInvalid],
            ["ShippedDate", [fields.ShippedDate.visible && fields.ShippedDate.required ? ew.Validators.required(fields.ShippedDate.caption) : null, ew.Validators.datetime(fields.ShippedDate.clientFormatPattern)], fields.ShippedDate.isInvalid],
            ["Freight", [fields.Freight.visible && fields.Freight.required ? ew.Validators.required(fields.Freight.caption) : null, ew.Validators.float], fields.Freight.isInvalid],
            ["ShipAddress", [fields.ShipAddress.visible && fields.ShipAddress.required ? ew.Validators.required(fields.ShipAddress.caption) : null], fields.ShipAddress.isInvalid],
            ["ShipCity", [fields.ShipCity.visible && fields.ShipCity.required ? ew.Validators.required(fields.ShipCity.caption) : null], fields.ShipCity.isInvalid],
            ["ShipCountry", [fields.ShipCountry.visible && fields.ShipCountry.required ? ew.Validators.required(fields.ShipCountry.caption) : null], fields.ShipCountry.isInvalid]
        ])

        // Check empty row
        .setEmptyRow(
            function (rowIndex) {
                let fobj = this.getForm(),
                    fields = [["CustomerID",false],["EmployeeID",false],["OrderDate",false],["RequiredDate",false],["ShippedDate",false],["Freight",false],["ShipAddress",false],["ShipCity",false],["ShipCountry",false]];
                if (fields.some(field => ew.valueChanged(fobj, rowIndex, ...field)))
                    return false;
                return true;
            }
        )

        // Form_CustomValidate
        .setCustomValidate(
            function (fobj) { // DO NOT CHANGE THIS LINE! (except for adding "async" keyword)!
                    // Your custom validation code here, return false if invalid.
                    return true;
                }
        )

        // Use JavaScript validation or not
        .setValidateRequired(ew.CLIENT_VALIDATE)

        // Dynamic selection lists
        .setLists({
            "CustomerID": <?= $Page->CustomerID->toClientList($Page) ?>,
            "EmployeeID": <?= $Page->EmployeeID->toClientList($Page) ?>,
        })
        .build();
    window[form.id] = form;
    currentForm = form;
    loadjs.done(form.id);
});
</script>
<script>
window.Tabulator || loadjs([
    ew.PATH_BASE + "js/tabulator.min.js?v=19.13.4",
    ew.PATH_BASE + "css/<?= CssFile("tabulator_bootstrap5.css", false) ?>?v=19.13.4"
], "import");
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<?php } ?>
<?php if (!$Page->isExport()) { ?>
<div class="btn-toolbar ew-toolbar">
<?php if ($Page->TotalRecords > 0 && $Page->ExportOptions->visible()) { ?>
<?php $Page->ExportOptions->render("body") ?>
<?php } ?>
<?php if ($Page->ImportOptions->visible()) { ?>
<?php $Page->ImportOptions->render("body") ?>
<?php } ?>
<?php if ($Page->SearchOptions->visible()) { ?>
<?php $Page->SearchOptions->render("body") ?>
<?php } ?>
<?php if ($Page->FilterOptions->visible()) { ?>
<?php $Page->FilterOptions->render("body") ?>
<?php } ?>
</div>
<?php } ?>
<?php if (!$Page->IsModal) { ?>
<form name="forders2srch" id="forders2srch" class="ew-form ew-ext-search-form" action="<?= CurrentPageUrl(false) ?>" novalidate autocomplete="on">
<div id="forders2srch_search_panel" class="mb-2 mb-sm-0 <?= $Page->SearchPanelClass ?>"><!-- .ew-search-panel -->
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { orders2: currentTable } });
var currentForm;
var forders2srch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery,
        fields = currentTable.fields;

    // Form object for search
    let form = new ew.FormBuilder()
        .setId("forders2srch")
        .setPageId("list")
<?php if ($Page->UseAjaxActions) { ?>
        .setSubmitWithFetch(true)
<?php } ?>

        // Dynamic selection lists
        .setLists({
        })

        // Filters
        .setFilterList(<?= $Page->getFilterList() ?>)
        .build();
    window[form.id] = form;
    currentSearchForm = form;
    loadjs.done(form.id);
});
</script>
<input type="hidden" name="cmd" value="search">
<?php if ($Security->canSearch()) { ?>
<?php if (!$Page->isExport() && !($Page->CurrentAction && $Page->CurrentAction != "search") && $Page->hasSearchFields()) { ?>
<div class="ew-extended-search container-fluid ps-2">
<div class="row mb-0">
    <div class="col-sm-auto px-0 pe-sm-2">
        <div class="ew-basic-search input-group">
            <input type="search" name="<?= Config("TABLE_BASIC_SEARCH") ?>" id="<?= Config("TABLE_BASIC_SEARCH") ?>" class="form-control ew-basic-search-keyword" value="<?= HtmlEncode($Page->BasicSearch->getKeyword()) ?>" placeholder="<?= HtmlEncode($Language->phrase("Search")) ?>" aria-label="<?= HtmlEncode($Language->phrase("Search")) ?>">
            <input type="hidden" name="<?= Config("TABLE_BASIC_SEARCH_TYPE") ?>" id="<?= Config("TABLE_BASIC_SEARCH_TYPE") ?>" class="ew-basic-search-type" value="<?= HtmlEncode($Page->BasicSearch->getType()) ?>">
            <button type="button" data-bs-toggle="dropdown" class="btn btn-outline-secondary dropdown-toggle dropdown-toggle-split" aria-haspopup="true" aria-expanded="false">
                <span id="searchtype"><?= $Page->BasicSearch->getTypeNameShort() ?></span>
            </button>
            <div class="dropdown-menu dropdown-menu-end">
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "" ? " active" : "" ?>" form="forders2srch" data-ew-action="search-type"><?= $Language->phrase("QuickSearchAuto") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "=" ? " active" : "" ?>" form="forders2srch" data-ew-action="search-type" data-search-type="="><?= $Language->phrase("QuickSearchExact") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "AND" ? " active" : "" ?>" form="forders2srch" data-ew-action="search-type" data-search-type="AND"><?= $Language->phrase("QuickSearchAll") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "OR" ? " active" : "" ?>" form="forders2srch" data-ew-action="search-type" data-search-type="OR"><?= $Language->phrase("QuickSearchAny") ?></button>
            </div>
        </div>
    </div>
    <div class="col-sm-auto mb-3">
        <button class="btn btn-primary" name="btn-submit" id="btn-submit" type="submit"><?= $Language->phrase("SearchBtn") ?></button>
    </div>
</div>
</div><!-- /.ew-extended-search -->
<?php } ?>
<?php } ?>
</div><!-- /.ew-search-panel -->
</form>
<?php } ?>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<main class="list<?= ($Page->TotalRecords == 0 && !$Page->isAdd()) ? " ew-no-record" : "" ?>">
<div id="ew-list">
<?php if (!$Page->isExport() || $Page->isExport("print")) { ?>
<!-- Middle Container -->
<div id="ew-middle" class="<?= $Page->MiddleContentClass ?>">
<?php } ?>
<?php if (!$Page->isExport() || $Page->isExport("print")) { ?>
<!-- Content Container -->
<div id="ew-content" class="<?= $Page->ContainerClass ?>">
<?php } ?>
<?php if ($Page->TotalRecords > 0 || $Page->CurrentAction) { ?>
<div class="card ew-card ew-grid<?= $Page->isAddOrEdit() ? " ew-grid-add-edit" : "" ?> <?= $Page->TableGridClass ?>">
<form name="<?= $Page->FormName ?>" id="<?= $Page->FormName ?>" class="ew-form ew-list-form" action="<?= $Page->PageAction ?>" method="post" novalidate autocomplete="on">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="orders2">
<?php if ($Page->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div id="gmp_orders2" class="card-body ew-grid-middle-panel <?= $Page->TableContainerClass ?>" style="<?= $Page->TableContainerStyle ?>">
<?php if ($Page->TotalRecords > 0 || $Page->isAdd() || $Page->isCopy() || $Page->isGridEdit() || $Page->isMultiEdit()) { ?>
<table id="tbl_orders2list" class="<?= $Page->TableClass ?>"><!-- .ew-table -->
<thead>
    <tr class="ew-table-header">
<?php
// Header row
$Page->RowType = ROWTYPE_HEADER;

// Render list options
$Page->renderListOptions();

// Render list options (header, left)
$Page->ListOptions->render("header", "left");
?>
<?php if ($Page->OrderID->Visible) { // OrderID ?>
        <th data-name="OrderID" class="<?= $Page->OrderID->headerCellClass() ?>"><div id="elh_orders2_OrderID" class="orders2_OrderID"><?= $Page->renderFieldHeader($Page->OrderID) ?></div></th>
<?php } ?>
<?php if ($Page->CustomerID->Visible) { // CustomerID ?>
        <th data-name="CustomerID" class="<?= $Page->CustomerID->headerCellClass() ?>"><div id="elh_orders2_CustomerID" class="orders2_CustomerID"><?= $Page->renderFieldHeader($Page->CustomerID) ?></div></th>
<?php } ?>
<?php if ($Page->EmployeeID->Visible) { // EmployeeID ?>
        <th data-name="EmployeeID" class="<?= $Page->EmployeeID->headerCellClass() ?>"><div id="elh_orders2_EmployeeID" class="orders2_EmployeeID"><?= $Page->renderFieldHeader($Page->EmployeeID) ?></div></th>
<?php } ?>
<?php if ($Page->OrderDate->Visible) { // OrderDate ?>
        <th data-name="OrderDate" class="<?= $Page->OrderDate->headerCellClass() ?>"><div id="elh_orders2_OrderDate" class="orders2_OrderDate"><?= $Page->renderFieldHeader($Page->OrderDate) ?></div></th>
<?php } ?>
<?php if ($Page->RequiredDate->Visible) { // RequiredDate ?>
        <th data-name="RequiredDate" class="<?= $Page->RequiredDate->headerCellClass() ?>"><div id="elh_orders2_RequiredDate" class="orders2_RequiredDate"><?= $Page->renderFieldHeader($Page->RequiredDate) ?></div></th>
<?php } ?>
<?php if ($Page->ShippedDate->Visible) { // ShippedDate ?>
        <th data-name="ShippedDate" class="<?= $Page->ShippedDate->headerCellClass() ?>"><div id="elh_orders2_ShippedDate" class="orders2_ShippedDate"><?= $Page->renderFieldHeader($Page->ShippedDate) ?></div></th>
<?php } ?>
<?php if ($Page->Freight->Visible) { // Freight ?>
        <th data-name="Freight" class="<?= $Page->Freight->headerCellClass() ?>"><div id="elh_orders2_Freight" class="orders2_Freight"><?= $Page->renderFieldHeader($Page->Freight) ?></div></th>
<?php } ?>
<?php if ($Page->ShipAddress->Visible) { // ShipAddress ?>
        <th data-name="ShipAddress" class="<?= $Page->ShipAddress->headerCellClass() ?>"><div id="elh_orders2_ShipAddress" class="orders2_ShipAddress"><?= $Page->renderFieldHeader($Page->ShipAddress) ?></div></th>
<?php } ?>
<?php if ($Page->ShipCity->Visible) { // ShipCity ?>
        <th data-name="ShipCity" class="<?= $Page->ShipCity->headerCellClass() ?>"><div id="elh_orders2_ShipCity" class="orders2_ShipCity"><?= $Page->renderFieldHeader($Page->ShipCity) ?></div></th>
<?php } ?>
<?php if ($Page->ShipCountry->Visible) { // ShipCountry ?>
        <th data-name="ShipCountry" class="<?= $Page->ShipCountry->headerCellClass() ?>"><div id="elh_orders2_ShipCountry" class="orders2_ShipCountry"><?= $Page->renderFieldHeader($Page->ShipCountry) ?></div></th>
<?php } ?>
<?php
// Render list options (header, right)
$Page->ListOptions->render("header", "right");
?>
    </tr>
</thead>
<tbody data-page="<?= $Page->getPageNumber() ?>">
<?php
$Page->setupGrid();
while ($Page->RecordCount < $Page->StopRecord || $Page->RowIndex === '$rowindex$') {
    $Page->RecordCount++;
    if ($Page->RecordCount >= $Page->StartRecord) {
        $Page->setupRow();

        // Skip 1) delete row / empty row for confirm page, 2) hidden row
        if (
            $Page->RowAction != "delete" &&
            $Page->RowAction != "insertdelete" &&
            !($Page->RowAction == "insert" && $Page->isConfirm() && $Page->emptyRow()) &&
            $Page->RowAction != "hide"
        ) {
?>
    <tr <?= $Page->rowAttributes() ?>>
<?php
// Render list options (body, left)
$Page->ListOptions->render("body", "left", $Page->RowCount);
?>
    <?php if ($Page->OrderID->Visible) { // OrderID ?>
        <td data-name="OrderID"<?= $Page->OrderID->cellAttributes() ?>>
<?php if ($Page->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_orders2_OrderID" class="el_orders2_OrderID"></span>
<input type="hidden" data-table="orders2" data-field="x_OrderID" data-hidden="1" data-old name="o<?= $Page->RowIndex ?>_OrderID" id="o<?= $Page->RowIndex ?>_OrderID" value="<?= HtmlEncode($Page->OrderID->OldValue) ?>">
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_orders2_OrderID" class="el_orders2_OrderID">
<span<?= $Page->OrderID->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->OrderID->getDisplayValue($Page->OrderID->EditValue))) ?>"></span>
<input type="hidden" data-table="orders2" data-field="x_OrderID" data-hidden="1" name="x<?= $Page->RowIndex ?>_OrderID" id="x<?= $Page->RowIndex ?>_OrderID" value="<?= HtmlEncode($Page->OrderID->CurrentValue) ?>">
</span>
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_orders2_OrderID" class="el_orders2_OrderID">
<span<?= $Page->OrderID->viewAttributes() ?>>
<?= $Page->OrderID->getViewValue() ?></span>
</span>
<?php } ?>
</td>
    <?php } else { ?>
            <input type="hidden" data-table="orders2" data-field="x_OrderID" data-hidden="1" name="x<?= $Page->RowIndex ?>_OrderID" id="x<?= $Page->RowIndex ?>_OrderID" value="<?= HtmlEncode($Page->OrderID->CurrentValue) ?>">
    <?php } ?>
    <?php if ($Page->CustomerID->Visible) { // CustomerID ?>
        <td data-name="CustomerID"<?= $Page->CustomerID->cellAttributes() ?>>
<?php if ($Page->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_orders2_CustomerID" class="el_orders2_CustomerID">
    <select
        id="x<?= $Page->RowIndex ?>_CustomerID"
        name="x<?= $Page->RowIndex ?>_CustomerID"
        class="form-select ew-select<?= $Page->CustomerID->isInvalidClass() ?>"
        <?php if (!$Page->CustomerID->IsNativeSelect) { ?>
        data-select2-id="<?= $Page->FormName ?>_x<?= $Page->RowIndex ?>_CustomerID"
        <?php } ?>
        data-table="orders2"
        data-field="x_CustomerID"
        data-value-separator="<?= $Page->CustomerID->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->CustomerID->getPlaceHolder()) ?>"
        <?= $Page->CustomerID->editAttributes() ?>>
        <?= $Page->CustomerID->selectOptionListHtml("x{$Page->RowIndex}_CustomerID") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->CustomerID->getErrorMessage() ?></div>
<?= $Page->CustomerID->Lookup->getParamTag($Page, "p_x" . $Page->RowIndex . "_CustomerID") ?>
<?php if (!$Page->CustomerID->IsNativeSelect) { ?>
<script>
loadjs.ready("<?= $Page->FormName ?>", function() {
    var options = { name: "x<?= $Page->RowIndex ?>_CustomerID", selectId: "<?= $Page->FormName ?>_x<?= $Page->RowIndex ?>_CustomerID" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (<?= $Page->FormName ?>.lists.CustomerID?.lookupOptions.length) {
        options.data = { id: "x<?= $Page->RowIndex ?>_CustomerID", form: "<?= $Page->FormName ?>" };
    } else {
        options.ajax = { id: "x<?= $Page->RowIndex ?>_CustomerID", form: "<?= $Page->FormName ?>", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.orders2.fields.CustomerID.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
<input type="hidden" data-table="orders2" data-field="x_CustomerID" data-hidden="1" data-old name="o<?= $Page->RowIndex ?>_CustomerID" id="o<?= $Page->RowIndex ?>_CustomerID" value="<?= HtmlEncode($Page->CustomerID->OldValue) ?>">
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_orders2_CustomerID" class="el_orders2_CustomerID">
    <select
        id="x<?= $Page->RowIndex ?>_CustomerID"
        name="x<?= $Page->RowIndex ?>_CustomerID"
        class="form-select ew-select<?= $Page->CustomerID->isInvalidClass() ?>"
        <?php if (!$Page->CustomerID->IsNativeSelect) { ?>
        data-select2-id="<?= $Page->FormName ?>_x<?= $Page->RowIndex ?>_CustomerID"
        <?php } ?>
        data-table="orders2"
        data-field="x_CustomerID"
        data-value-separator="<?= $Page->CustomerID->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->CustomerID->getPlaceHolder()) ?>"
        <?= $Page->CustomerID->editAttributes() ?>>
        <?= $Page->CustomerID->selectOptionListHtml("x{$Page->RowIndex}_CustomerID") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->CustomerID->getErrorMessage() ?></div>
<?= $Page->CustomerID->Lookup->getParamTag($Page, "p_x" . $Page->RowIndex . "_CustomerID") ?>
<?php if (!$Page->CustomerID->IsNativeSelect) { ?>
<script>
loadjs.ready("<?= $Page->FormName ?>", function() {
    var options = { name: "x<?= $Page->RowIndex ?>_CustomerID", selectId: "<?= $Page->FormName ?>_x<?= $Page->RowIndex ?>_CustomerID" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (<?= $Page->FormName ?>.lists.CustomerID?.lookupOptions.length) {
        options.data = { id: "x<?= $Page->RowIndex ?>_CustomerID", form: "<?= $Page->FormName ?>" };
    } else {
        options.ajax = { id: "x<?= $Page->RowIndex ?>_CustomerID", form: "<?= $Page->FormName ?>", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.orders2.fields.CustomerID.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_orders2_CustomerID" class="el_orders2_CustomerID">
<span<?= $Page->CustomerID->viewAttributes() ?>>
<?= $Page->CustomerID->getViewValue() ?></span>
</span>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Page->EmployeeID->Visible) { // EmployeeID ?>
        <td data-name="EmployeeID"<?= $Page->EmployeeID->cellAttributes() ?>>
<?php if ($Page->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_orders2_EmployeeID" class="el_orders2_EmployeeID">
    <select
        id="x<?= $Page->RowIndex ?>_EmployeeID"
        name="x<?= $Page->RowIndex ?>_EmployeeID"
        class="form-select ew-select<?= $Page->EmployeeID->isInvalidClass() ?>"
        <?php if (!$Page->EmployeeID->IsNativeSelect) { ?>
        data-select2-id="<?= $Page->FormName ?>_x<?= $Page->RowIndex ?>_EmployeeID"
        <?php } ?>
        data-table="orders2"
        data-field="x_EmployeeID"
        data-value-separator="<?= $Page->EmployeeID->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->EmployeeID->getPlaceHolder()) ?>"
        <?= $Page->EmployeeID->editAttributes() ?>>
        <?= $Page->EmployeeID->selectOptionListHtml("x{$Page->RowIndex}_EmployeeID") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->EmployeeID->getErrorMessage() ?></div>
<?= $Page->EmployeeID->Lookup->getParamTag($Page, "p_x" . $Page->RowIndex . "_EmployeeID") ?>
<?php if (!$Page->EmployeeID->IsNativeSelect) { ?>
<script>
loadjs.ready("<?= $Page->FormName ?>", function() {
    var options = { name: "x<?= $Page->RowIndex ?>_EmployeeID", selectId: "<?= $Page->FormName ?>_x<?= $Page->RowIndex ?>_EmployeeID" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (<?= $Page->FormName ?>.lists.EmployeeID?.lookupOptions.length) {
        options.data = { id: "x<?= $Page->RowIndex ?>_EmployeeID", form: "<?= $Page->FormName ?>" };
    } else {
        options.ajax = { id: "x<?= $Page->RowIndex ?>_EmployeeID", form: "<?= $Page->FormName ?>", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.orders2.fields.EmployeeID.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
<input type="hidden" data-table="orders2" data-field="x_EmployeeID" data-hidden="1" data-old name="o<?= $Page->RowIndex ?>_EmployeeID" id="o<?= $Page->RowIndex ?>_EmployeeID" value="<?= HtmlEncode($Page->EmployeeID->OldValue) ?>">
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_orders2_EmployeeID" class="el_orders2_EmployeeID">
    <select
        id="x<?= $Page->RowIndex ?>_EmployeeID"
        name="x<?= $Page->RowIndex ?>_EmployeeID"
        class="form-select ew-select<?= $Page->EmployeeID->isInvalidClass() ?>"
        <?php if (!$Page->EmployeeID->IsNativeSelect) { ?>
        data-select2-id="<?= $Page->FormName ?>_x<?= $Page->RowIndex ?>_EmployeeID"
        <?php } ?>
        data-table="orders2"
        data-field="x_EmployeeID"
        data-value-separator="<?= $Page->EmployeeID->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->EmployeeID->getPlaceHolder()) ?>"
        <?= $Page->EmployeeID->editAttributes() ?>>
        <?= $Page->EmployeeID->selectOptionListHtml("x{$Page->RowIndex}_EmployeeID") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->EmployeeID->getErrorMessage() ?></div>
<?= $Page->EmployeeID->Lookup->getParamTag($Page, "p_x" . $Page->RowIndex . "_EmployeeID") ?>
<?php if (!$Page->EmployeeID->IsNativeSelect) { ?>
<script>
loadjs.ready("<?= $Page->FormName ?>", function() {
    var options = { name: "x<?= $Page->RowIndex ?>_EmployeeID", selectId: "<?= $Page->FormName ?>_x<?= $Page->RowIndex ?>_EmployeeID" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (<?= $Page->FormName ?>.lists.EmployeeID?.lookupOptions.length) {
        options.data = { id: "x<?= $Page->RowIndex ?>_EmployeeID", form: "<?= $Page->FormName ?>" };
    } else {
        options.ajax = { id: "x<?= $Page->RowIndex ?>_EmployeeID", form: "<?= $Page->FormName ?>", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.orders2.fields.EmployeeID.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_orders2_EmployeeID" class="el_orders2_EmployeeID">
<span<?= $Page->EmployeeID->viewAttributes() ?>>
<?= $Page->EmployeeID->getViewValue() ?></span>
</span>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Page->OrderDate->Visible) { // OrderDate ?>
        <td data-name="OrderDate"<?= $Page->OrderDate->cellAttributes() ?>>
<?php if ($Page->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_orders2_OrderDate" class="el_orders2_OrderDate">
<input type="<?= $Page->OrderDate->getInputTextType() ?>" name="x<?= $Page->RowIndex ?>_OrderDate" id="x<?= $Page->RowIndex ?>_OrderDate" data-table="orders2" data-field="x_OrderDate" value="<?= $Page->OrderDate->EditValue ?>" placeholder="<?= HtmlEncode($Page->OrderDate->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->OrderDate->formatPattern()) ?>"<?= $Page->OrderDate->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->OrderDate->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="orders2" data-field="x_OrderDate" data-hidden="1" data-old name="o<?= $Page->RowIndex ?>_OrderDate" id="o<?= $Page->RowIndex ?>_OrderDate" value="<?= HtmlEncode($Page->OrderDate->OldValue) ?>">
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_orders2_OrderDate" class="el_orders2_OrderDate">
<input type="<?= $Page->OrderDate->getInputTextType() ?>" name="x<?= $Page->RowIndex ?>_OrderDate" id="x<?= $Page->RowIndex ?>_OrderDate" data-table="orders2" data-field="x_OrderDate" value="<?= $Page->OrderDate->EditValue ?>" placeholder="<?= HtmlEncode($Page->OrderDate->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->OrderDate->formatPattern()) ?>"<?= $Page->OrderDate->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->OrderDate->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_orders2_OrderDate" class="el_orders2_OrderDate">
<span<?= $Page->OrderDate->viewAttributes() ?>>
<?= $Page->OrderDate->getViewValue() ?></span>
</span>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Page->RequiredDate->Visible) { // RequiredDate ?>
        <td data-name="RequiredDate"<?= $Page->RequiredDate->cellAttributes() ?>>
<?php if ($Page->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_orders2_RequiredDate" class="el_orders2_RequiredDate">
<input type="<?= $Page->RequiredDate->getInputTextType() ?>" name="x<?= $Page->RowIndex ?>_RequiredDate" id="x<?= $Page->RowIndex ?>_RequiredDate" data-table="orders2" data-field="x_RequiredDate" value="<?= $Page->RequiredDate->EditValue ?>" placeholder="<?= HtmlEncode($Page->RequiredDate->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->RequiredDate->formatPattern()) ?>"<?= $Page->RequiredDate->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->RequiredDate->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="orders2" data-field="x_RequiredDate" data-hidden="1" data-old name="o<?= $Page->RowIndex ?>_RequiredDate" id="o<?= $Page->RowIndex ?>_RequiredDate" value="<?= HtmlEncode($Page->RequiredDate->OldValue) ?>">
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_orders2_RequiredDate" class="el_orders2_RequiredDate">
<input type="<?= $Page->RequiredDate->getInputTextType() ?>" name="x<?= $Page->RowIndex ?>_RequiredDate" id="x<?= $Page->RowIndex ?>_RequiredDate" data-table="orders2" data-field="x_RequiredDate" value="<?= $Page->RequiredDate->EditValue ?>" placeholder="<?= HtmlEncode($Page->RequiredDate->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->RequiredDate->formatPattern()) ?>"<?= $Page->RequiredDate->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->RequiredDate->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_orders2_RequiredDate" class="el_orders2_RequiredDate">
<span<?= $Page->RequiredDate->viewAttributes() ?>>
<?= $Page->RequiredDate->getViewValue() ?></span>
</span>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Page->ShippedDate->Visible) { // ShippedDate ?>
        <td data-name="ShippedDate"<?= $Page->ShippedDate->cellAttributes() ?>>
<?php if ($Page->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_orders2_ShippedDate" class="el_orders2_ShippedDate">
<input type="<?= $Page->ShippedDate->getInputTextType() ?>" name="x<?= $Page->RowIndex ?>_ShippedDate" id="x<?= $Page->RowIndex ?>_ShippedDate" data-table="orders2" data-field="x_ShippedDate" value="<?= $Page->ShippedDate->EditValue ?>" placeholder="<?= HtmlEncode($Page->ShippedDate->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->ShippedDate->formatPattern()) ?>"<?= $Page->ShippedDate->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->ShippedDate->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="orders2" data-field="x_ShippedDate" data-hidden="1" data-old name="o<?= $Page->RowIndex ?>_ShippedDate" id="o<?= $Page->RowIndex ?>_ShippedDate" value="<?= HtmlEncode($Page->ShippedDate->OldValue) ?>">
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_orders2_ShippedDate" class="el_orders2_ShippedDate">
<input type="<?= $Page->ShippedDate->getInputTextType() ?>" name="x<?= $Page->RowIndex ?>_ShippedDate" id="x<?= $Page->RowIndex ?>_ShippedDate" data-table="orders2" data-field="x_ShippedDate" value="<?= $Page->ShippedDate->EditValue ?>" placeholder="<?= HtmlEncode($Page->ShippedDate->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->ShippedDate->formatPattern()) ?>"<?= $Page->ShippedDate->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->ShippedDate->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_orders2_ShippedDate" class="el_orders2_ShippedDate">
<span<?= $Page->ShippedDate->viewAttributes() ?>>
<?= $Page->ShippedDate->getViewValue() ?></span>
</span>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Page->Freight->Visible) { // Freight ?>
        <td data-name="Freight"<?= $Page->Freight->cellAttributes() ?>>
<?php if ($Page->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_orders2_Freight" class="el_orders2_Freight">
<input type="<?= $Page->Freight->getInputTextType() ?>" name="x<?= $Page->RowIndex ?>_Freight" id="x<?= $Page->RowIndex ?>_Freight" data-table="orders2" data-field="x_Freight" value="<?= $Page->Freight->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->Freight->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Freight->formatPattern()) ?>"<?= $Page->Freight->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->Freight->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="orders2" data-field="x_Freight" data-hidden="1" data-old name="o<?= $Page->RowIndex ?>_Freight" id="o<?= $Page->RowIndex ?>_Freight" value="<?= HtmlEncode($Page->Freight->OldValue) ?>">
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_orders2_Freight" class="el_orders2_Freight">
<input type="<?= $Page->Freight->getInputTextType() ?>" name="x<?= $Page->RowIndex ?>_Freight" id="x<?= $Page->RowIndex ?>_Freight" data-table="orders2" data-field="x_Freight" value="<?= $Page->Freight->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->Freight->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Freight->formatPattern()) ?>"<?= $Page->Freight->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->Freight->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_orders2_Freight" class="el_orders2_Freight">
<span<?= $Page->Freight->viewAttributes() ?>>
<?= $Page->Freight->getViewValue() ?></span>
</span>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Page->ShipAddress->Visible) { // ShipAddress ?>
        <td data-name="ShipAddress"<?= $Page->ShipAddress->cellAttributes() ?>>
<?php if ($Page->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_orders2_ShipAddress" class="el_orders2_ShipAddress">
<input type="<?= $Page->ShipAddress->getInputTextType() ?>" name="x<?= $Page->RowIndex ?>_ShipAddress" id="x<?= $Page->RowIndex ?>_ShipAddress" data-table="orders2" data-field="x_ShipAddress" value="<?= $Page->ShipAddress->EditValue ?>" size="30" maxlength="60" placeholder="<?= HtmlEncode($Page->ShipAddress->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->ShipAddress->formatPattern()) ?>"<?= $Page->ShipAddress->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->ShipAddress->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="orders2" data-field="x_ShipAddress" data-hidden="1" data-old name="o<?= $Page->RowIndex ?>_ShipAddress" id="o<?= $Page->RowIndex ?>_ShipAddress" value="<?= HtmlEncode($Page->ShipAddress->OldValue) ?>">
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_orders2_ShipAddress" class="el_orders2_ShipAddress">
<input type="<?= $Page->ShipAddress->getInputTextType() ?>" name="x<?= $Page->RowIndex ?>_ShipAddress" id="x<?= $Page->RowIndex ?>_ShipAddress" data-table="orders2" data-field="x_ShipAddress" value="<?= $Page->ShipAddress->EditValue ?>" size="30" maxlength="60" placeholder="<?= HtmlEncode($Page->ShipAddress->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->ShipAddress->formatPattern()) ?>"<?= $Page->ShipAddress->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->ShipAddress->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_orders2_ShipAddress" class="el_orders2_ShipAddress">
<span<?= $Page->ShipAddress->viewAttributes() ?>>
<?= $Page->ShipAddress->getViewValue() ?></span>
</span>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Page->ShipCity->Visible) { // ShipCity ?>
        <td data-name="ShipCity"<?= $Page->ShipCity->cellAttributes() ?>>
<?php if ($Page->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_orders2_ShipCity" class="el_orders2_ShipCity">
<input type="<?= $Page->ShipCity->getInputTextType() ?>" name="x<?= $Page->RowIndex ?>_ShipCity" id="x<?= $Page->RowIndex ?>_ShipCity" data-table="orders2" data-field="x_ShipCity" value="<?= $Page->ShipCity->EditValue ?>" size="30" maxlength="15" placeholder="<?= HtmlEncode($Page->ShipCity->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->ShipCity->formatPattern()) ?>"<?= $Page->ShipCity->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->ShipCity->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="orders2" data-field="x_ShipCity" data-hidden="1" data-old name="o<?= $Page->RowIndex ?>_ShipCity" id="o<?= $Page->RowIndex ?>_ShipCity" value="<?= HtmlEncode($Page->ShipCity->OldValue) ?>">
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_orders2_ShipCity" class="el_orders2_ShipCity">
<input type="<?= $Page->ShipCity->getInputTextType() ?>" name="x<?= $Page->RowIndex ?>_ShipCity" id="x<?= $Page->RowIndex ?>_ShipCity" data-table="orders2" data-field="x_ShipCity" value="<?= $Page->ShipCity->EditValue ?>" size="30" maxlength="15" placeholder="<?= HtmlEncode($Page->ShipCity->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->ShipCity->formatPattern()) ?>"<?= $Page->ShipCity->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->ShipCity->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_orders2_ShipCity" class="el_orders2_ShipCity">
<span<?= $Page->ShipCity->viewAttributes() ?>>
<?= $Page->ShipCity->getViewValue() ?></span>
</span>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Page->ShipCountry->Visible) { // ShipCountry ?>
        <td data-name="ShipCountry"<?= $Page->ShipCountry->cellAttributes() ?>>
<?php if ($Page->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_orders2_ShipCountry" class="el_orders2_ShipCountry">
<input type="<?= $Page->ShipCountry->getInputTextType() ?>" name="x<?= $Page->RowIndex ?>_ShipCountry" id="x<?= $Page->RowIndex ?>_ShipCountry" data-table="orders2" data-field="x_ShipCountry" value="<?= $Page->ShipCountry->EditValue ?>" size="30" maxlength="15" placeholder="<?= HtmlEncode($Page->ShipCountry->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->ShipCountry->formatPattern()) ?>"<?= $Page->ShipCountry->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->ShipCountry->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="orders2" data-field="x_ShipCountry" data-hidden="1" data-old name="o<?= $Page->RowIndex ?>_ShipCountry" id="o<?= $Page->RowIndex ?>_ShipCountry" value="<?= HtmlEncode($Page->ShipCountry->OldValue) ?>">
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_orders2_ShipCountry" class="el_orders2_ShipCountry">
<input type="<?= $Page->ShipCountry->getInputTextType() ?>" name="x<?= $Page->RowIndex ?>_ShipCountry" id="x<?= $Page->RowIndex ?>_ShipCountry" data-table="orders2" data-field="x_ShipCountry" value="<?= $Page->ShipCountry->EditValue ?>" size="30" maxlength="15" placeholder="<?= HtmlEncode($Page->ShipCountry->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->ShipCountry->formatPattern()) ?>"<?= $Page->ShipCountry->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->ShipCountry->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_orders2_ShipCountry" class="el_orders2_ShipCountry">
<span<?= $Page->ShipCountry->viewAttributes() ?>>
<?= $Page->ShipCountry->getViewValue() ?></span>
</span>
<?php } ?>
</td>
    <?php } ?>
<?php
// Render list options (body, right)
$Page->ListOptions->render("body", "right", $Page->RowCount);
?>
    </tr>
<?php if ($Page->RowType == ROWTYPE_ADD || $Page->RowType == ROWTYPE_EDIT) { ?>
<script data-rowindex="<?= $Page->RowIndex ?>">
loadjs.ready(["<?= $Page->FormName ?>","load"], () => <?= $Page->FormName ?>.updateLists(<?= $Page->RowIndex ?><?= $Page->isAdd() || $Page->isEdit() || $Page->isCopy() || $Page->RowIndex === '$rowindex$' ? ", true" : "" ?>));
</script>
<?php } ?>
<?php
    }
    } // End delete row checking
    if (
        $Page->Recordset &&
        !$Page->Recordset->EOF &&
        $Page->RowIndex !== '$rowindex$' &&
        (!$Page->isGridAdd() || $Page->CurrentMode == "copy") &&
        (!(($Page->isCopy() || $Page->isAdd()) && $Page->RowIndex == 0))
    ) {
        $Page->Recordset->moveNext();
    }
    // Reset for template row
    if ($Page->RowIndex === '$rowindex$') {
        $Page->RowIndex = 0;
    }
    // Reset inline add/copy row
    if (($Page->isCopy() || $Page->isAdd()) && $Page->RowIndex == 0) {
        $Page->RowIndex = 1;
    }
}
?>
</tbody>
</table><!-- /.ew-table -->
<?php } ?>
<?php if ($Page->isAdd() || $Page->isCopy()) { ?>
<input type="hidden" name="<?= $Page->FormKeyCountName ?>" id="<?= $Page->FormKeyCountName ?>" value="<?= $Page->KeyCount ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<?php } ?>
<?php if ($Page->isGridAdd()) { ?>
<input type="hidden" name="action" id="action" value="gridinsert">
<input type="hidden" name="<?= $Page->FormKeyCountName ?>" id="<?= $Page->FormKeyCountName ?>" value="<?= $Page->KeyCount ?>">
<?= $Page->MultiSelectKey ?>
<?php } ?>
<?php if ($Page->isEdit()) { ?>
<input type="hidden" name="<?= $Page->FormKeyCountName ?>" id="<?= $Page->FormKeyCountName ?>" value="<?= $Page->KeyCount ?>">
<?php } ?>
<?php if ($Page->isGridEdit() || $Page->isMultiEdit()) { ?>
<?php if ($Page->isGridEdit()) { ?>
<input type="hidden" name="action" id="action" value="gridupdate">
<?php } elseif ($Page->isMultiEdit()) { ?>
<input type="hidden" name="action" id="action" value="multiupdate">
<?php } ?>
<input type="hidden" name="<?= $Page->FormKeyCountName ?>" id="<?= $Page->FormKeyCountName ?>" value="<?= $Page->KeyCount ?>">
<?= $Page->MultiSelectKey ?>
<?php } ?>
</div><!-- /.ew-grid-middle-panel -->
<?php if (!$Page->CurrentAction && !$Page->UseAjaxActions) { ?>
<input type="hidden" name="action" id="action" value="">
<?php } ?>
</form><!-- /.ew-list-form -->
<?php
// Close recordset
if ($Page->Recordset) {
    $Page->Recordset->close();
}
?>
<?php if (!$Page->isExport()) { ?>
<div class="card-footer ew-grid-lower-panel">
<?php if (!$Page->isGridAdd() && !($Page->isGridEdit() && $Page->ModalGridEdit) && !$Page->isMultiEdit()) { ?>
<?= $Page->Pager->render() ?>
<?php } ?>
<div class="ew-list-other-options">
<?php $Page->OtherOptions->render("body", "bottom") ?>
</div>
</div>
<?php } ?>
</div><!-- /.ew-grid -->
<?php } else { ?>
<div class="ew-list-other-options">
<?php $Page->OtherOptions->render("body") ?>
</div>
<?php } ?>
<?php if (!$Page->isExport() || $Page->isExport("print")) { ?>
</div>
<!-- /#ew-content -->
<?php } ?>
<?php if (!$Page->isExport() || $Page->isExport("print")) { ?>
</div>
<!-- /#ew-middle -->
<?php } ?>
<?php if (!$Page->isExport() || $Page->isExport("print")) { ?>
<!-- Bottom Container -->
<div id="ew-bottom" class="<?= $Page->BottomContentClass ?>">
<?php } ?>
<?php
if (!$DashboardReport) {
    // Set up chart drilldown
    $Page->FreightByEmployees->DrillDownInPanel = $Page->DrillDownInPanel;
    echo $Page->FreightByEmployees->render("ew-chart-bottom");
}
?>
<?php if (!$Page->isExport() || $Page->isExport("print")) { ?>
</div>
<!-- /#ew-bottom -->
<?php } ?>
</div>
</main>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<?php if (!$Page->isExport()) { ?>
<script>
// Field event handlers
loadjs.ready("head", function() {
    ew.addEventHandlers("orders2");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
