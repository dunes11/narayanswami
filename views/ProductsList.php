<?php

namespace PHPMaker2023\demo2023;

// Page object
$ProductsList = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { products: currentTable } });
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

        // Dynamic selection lists
        .setLists({
            "ProductName": <?= $Page->ProductName->toClientList($Page) ?>,
            "SupplierID": <?= $Page->SupplierID->toClientList($Page) ?>,
            "CategoryID": <?= $Page->CategoryID->toClientList($Page) ?>,
            "Discontinued": <?= $Page->Discontinued->toClientList($Page) ?>,
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
<?php if (!$Page->isExport() || Config("EXPORT_MASTER_RECORD") && $Page->isExport("print")) { ?>
<?php
if ($Page->DbMasterFilter != "" && $Page->getCurrentMasterTable() == "categories") {
    if ($Page->MasterRecordExists) {
        include_once "views/CategoriesMaster.php";
    }
}
?>
<?php } ?>
<?php if ($Page->ShowCurrentFilter) { ?>
<?php $Page->showFilterList() ?>
<?php } ?>
<?php if (!$Page->IsModal) { ?>
<form name="fproductssrch" id="fproductssrch" class="ew-form ew-ext-search-form" action="<?= CurrentPageUrl(false) ?>" novalidate autocomplete="on">
<div id="fproductssrch_search_panel" class="mb-2 mb-sm-0 <?= $Page->SearchPanelClass ?>"><!-- .ew-search-panel -->
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { products: currentTable } });
var currentForm;
var fproductssrch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery,
        fields = currentTable.fields;

    // Form object for search
    let form = new ew.FormBuilder()
        .setId("fproductssrch")
        .setPageId("list")
<?php if ($Page->UseAjaxActions) { ?>
        .setSubmitWithFetch(true)
<?php } ?>

        // Add fields
        .addFields([
            ["ProductID", [], fields.ProductID.isInvalid],
            ["ProductName", [], fields.ProductName.isInvalid],
            ["SupplierID", [], fields.SupplierID.isInvalid],
            ["CategoryID", [], fields.CategoryID.isInvalid],
            ["QuantityPerUnit", [], fields.QuantityPerUnit.isInvalid],
            ["UnitPrice", [], fields.UnitPrice.isInvalid],
            ["UnitsInStock", [], fields.UnitsInStock.isInvalid],
            ["UnitsOnOrder", [], fields.UnitsOnOrder.isInvalid],
            ["ReorderLevel", [], fields.ReorderLevel.isInvalid],
            ["Discontinued", [], fields.Discontinued.isInvalid],
            ["EAN13", [], fields.EAN13.isInvalid]
        ])
        // Validate form
        .setValidate(
            async function () {
                if (!this.validateRequired)
                    return true; // Ignore validation
                let fobj = this.getForm();

                // Validate fields
                if (!this.validateFields())
                    return false;

                // Call Form_CustomValidate event
                if (!(await this.customValidate?.(fobj) ?? true)) {
                    this.focus();
                    return false;
                }
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
            "ProductName": <?= $Page->ProductName->toClientList($Page) ?>,
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
<div class="row mb-0<?= ($Page->SearchFieldsPerRow > 0) ? " row-cols-sm-" . $Page->SearchFieldsPerRow : "" ?>">
<?php
// Render search row
$Page->RowType = ROWTYPE_SEARCH;
$Page->resetAttributes();
$Page->renderRow();
?>
<?php if ($Page->ProductName->Visible) { // ProductName ?>
<?php
if (!$Page->ProductName->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_ProductName" class="col-sm-auto d-sm-flex align-items-start mb-3 px-0 pe-sm-2<?= $Page->ProductName->UseFilter ? " ew-filter-field" : "" ?>">
        <select
            id="x_ProductName"
            name="x_ProductName[]"
            class="form-control ew-select<?= $Page->ProductName->isInvalidClass() ?>"
            data-select2-id="fproductssrch_x_ProductName"
            data-table="products"
            data-field="x_ProductName"
            data-caption="<?= HtmlEncode(RemoveHtml($Page->ProductName->caption())) ?>"
            data-filter="true"
            multiple
            size="1"
            data-value-separator="<?= $Page->ProductName->displayValueSeparatorAttribute() ?>"
            data-placeholder="<?= HtmlEncode($Page->ProductName->getPlaceHolder()) ?>"
            data-ew-action="update-options"
            <?= $Page->ProductName->editAttributes() ?>>
            <?= $Page->ProductName->selectOptionListHtml("x_ProductName", true) ?>
        </select>
        <div class="invalid-feedback"><?= $Page->ProductName->getErrorMessage(false) ?></div>
        <script>
        loadjs.ready("fproductssrch", function() {
            var options = {
                name: "x_ProductName",
                selectId: "fproductssrch_x_ProductName",
                ajax: { id: "x_ProductName", form: "fproductssrch", limit: ew.FILTER_PAGE_SIZE, data: { ajax: "filter" } }
            };
            options = Object.assign({}, ew.filterOptions, options, ew.vars.tables.products.fields.ProductName.filterOptions);
            ew.createFilter(options);
        });
        </script>
    </div><!-- /.col-sm-auto -->
<?php } ?>
</div><!-- /.row -->
<div class="row mb-0">
    <div class="col-sm-auto px-0 pe-sm-2">
        <div class="ew-basic-search input-group">
            <input type="search" name="<?= Config("TABLE_BASIC_SEARCH") ?>" id="<?= Config("TABLE_BASIC_SEARCH") ?>" class="form-control ew-basic-search-keyword" value="<?= HtmlEncode($Page->BasicSearch->getKeyword()) ?>" placeholder="<?= HtmlEncode($Language->phrase("Search")) ?>" aria-label="<?= HtmlEncode($Language->phrase("Search")) ?>">
            <input type="hidden" name="<?= Config("TABLE_BASIC_SEARCH_TYPE") ?>" id="<?= Config("TABLE_BASIC_SEARCH_TYPE") ?>" class="ew-basic-search-type" value="<?= HtmlEncode($Page->BasicSearch->getType()) ?>">
            <button type="button" data-bs-toggle="dropdown" class="btn btn-outline-secondary dropdown-toggle dropdown-toggle-split" aria-haspopup="true" aria-expanded="false">
                <span id="searchtype"><?= $Page->BasicSearch->getTypeNameShort() ?></span>
            </button>
            <div class="dropdown-menu dropdown-menu-end">
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "" ? " active" : "" ?>" form="fproductssrch" data-ew-action="search-type"><?= $Language->phrase("QuickSearchAuto") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "=" ? " active" : "" ?>" form="fproductssrch" data-ew-action="search-type" data-search-type="="><?= $Language->phrase("QuickSearchExact") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "AND" ? " active" : "" ?>" form="fproductssrch" data-ew-action="search-type" data-search-type="AND"><?= $Language->phrase("QuickSearchAll") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "OR" ? " active" : "" ?>" form="fproductssrch" data-ew-action="search-type" data-search-type="OR"><?= $Language->phrase("QuickSearchAny") ?></button>
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
<?php if ($Page->TotalRecords > 0 || $Page->CurrentAction) { ?>
<div class="card ew-card ew-grid<?= $Page->isAddOrEdit() ? " ew-grid-add-edit" : "" ?> <?= $Page->TableGridClass ?>">
<?php if (!$Page->isExport()) { ?>
<div class="card-header ew-grid-upper-panel">
<?php if (!$Page->isGridAdd() && !($Page->isGridEdit() && $Page->ModalGridEdit) && !$Page->isMultiEdit()) { ?>
<?= $Page->Pager->render() ?>
<?php } ?>
<div class="ew-list-other-options">
<?php $Page->OtherOptions->render("body") ?>
</div>
</div>
<?php } ?>
<form name="<?= $Page->FormName ?>" id="<?= $Page->FormName ?>" class="ew-form ew-list-form" action="<?= $Page->PageAction ?>" method="post" novalidate autocomplete="on">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="products">
<?php if ($Page->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<?php if ($Page->getCurrentMasterTable() == "categories" && $Page->CurrentAction) { ?>
<input type="hidden" name="<?= Config("TABLE_SHOW_MASTER") ?>" value="categories">
<input type="hidden" name="fk_CategoryID" value="<?= HtmlEncode($Page->CategoryID->getSessionValue()) ?>">
<?php } ?>
<div id="gmp_products" class="card-body ew-grid-middle-panel <?= $Page->TableContainerClass ?>" style="<?= $Page->TableContainerStyle ?>">
<?php if ($Page->TotalRecords > 0 || $Page->isGridEdit() || $Page->isMultiEdit()) { ?>
<table id="tbl_productslist" class="<?= $Page->TableClass ?>"><!-- .ew-table -->
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
<?php if ($Page->ProductID->Visible) { // ProductID ?>
        <th data-name="ProductID" class="<?= $Page->ProductID->headerCellClass() ?>"><div id="elh_products_ProductID" class="products_ProductID"><?= $Page->renderFieldHeader($Page->ProductID) ?></div></th>
<?php } ?>
<?php if ($Page->ProductName->Visible) { // ProductName ?>
        <th data-name="ProductName" class="<?= $Page->ProductName->headerCellClass() ?>"><div id="elh_products_ProductName" class="products_ProductName"><?= $Page->renderFieldHeader($Page->ProductName) ?></div></th>
<?php } ?>
<?php if ($Page->SupplierID->Visible) { // SupplierID ?>
        <th data-name="SupplierID" class="<?= $Page->SupplierID->headerCellClass() ?>"><div id="elh_products_SupplierID" class="products_SupplierID"><?= $Page->renderFieldHeader($Page->SupplierID) ?></div></th>
<?php } ?>
<?php if ($Page->CategoryID->Visible) { // CategoryID ?>
        <th data-name="CategoryID" class="<?= $Page->CategoryID->headerCellClass() ?>"><div id="elh_products_CategoryID" class="products_CategoryID"><?= $Page->renderFieldHeader($Page->CategoryID) ?></div></th>
<?php } ?>
<?php if ($Page->QuantityPerUnit->Visible) { // QuantityPerUnit ?>
        <th data-name="QuantityPerUnit" class="<?= $Page->QuantityPerUnit->headerCellClass() ?>"><div id="elh_products_QuantityPerUnit" class="products_QuantityPerUnit"><?= $Page->renderFieldHeader($Page->QuantityPerUnit) ?></div></th>
<?php } ?>
<?php if ($Page->UnitPrice->Visible) { // UnitPrice ?>
        <th data-name="UnitPrice" class="<?= $Page->UnitPrice->headerCellClass() ?>"><div id="elh_products_UnitPrice" class="products_UnitPrice"><?= $Page->renderFieldHeader($Page->UnitPrice) ?></div></th>
<?php } ?>
<?php if ($Page->UnitsInStock->Visible) { // UnitsInStock ?>
        <th data-name="UnitsInStock" class="<?= $Page->UnitsInStock->headerCellClass() ?>"><div id="elh_products_UnitsInStock" class="products_UnitsInStock"><?= $Page->renderFieldHeader($Page->UnitsInStock) ?></div></th>
<?php } ?>
<?php if ($Page->UnitsOnOrder->Visible) { // UnitsOnOrder ?>
        <th data-name="UnitsOnOrder" class="<?= $Page->UnitsOnOrder->headerCellClass() ?>"><div id="elh_products_UnitsOnOrder" class="products_UnitsOnOrder"><?= $Page->renderFieldHeader($Page->UnitsOnOrder) ?></div></th>
<?php } ?>
<?php if ($Page->ReorderLevel->Visible) { // ReorderLevel ?>
        <th data-name="ReorderLevel" class="<?= $Page->ReorderLevel->headerCellClass() ?>"><div id="elh_products_ReorderLevel" class="products_ReorderLevel"><?= $Page->renderFieldHeader($Page->ReorderLevel) ?></div></th>
<?php } ?>
<?php if ($Page->Discontinued->Visible) { // Discontinued ?>
        <th data-name="Discontinued" class="<?= $Page->Discontinued->headerCellClass() ?>"><div id="elh_products_Discontinued" class="products_Discontinued"><?= $Page->renderFieldHeader($Page->Discontinued) ?></div></th>
<?php } ?>
<?php if ($Page->EAN13->Visible) { // EAN13 ?>
        <th data-name="EAN13" class="<?= $Page->EAN13->headerCellClass() ?>"><div id="elh_products_EAN13" class="products_EAN13"><?= $Page->renderFieldHeader($Page->EAN13) ?></div></th>
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
?>
    <tr <?= $Page->rowAttributes() ?>>
<?php
// Render list options (body, left)
$Page->ListOptions->render("body", "left", $Page->RowCount);
?>
    <?php if ($Page->ProductID->Visible) { // ProductID ?>
        <td data-name="ProductID"<?= $Page->ProductID->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_products_ProductID" class="el_products_ProductID">
<span<?= $Page->ProductID->viewAttributes() ?>>
<?= $Page->ProductID->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->ProductName->Visible) { // ProductName ?>
        <td data-name="ProductName"<?= $Page->ProductName->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_products_ProductName" class="el_products_ProductName">
<span<?= $Page->ProductName->viewAttributes() ?>>
<?= $Page->ProductName->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->SupplierID->Visible) { // SupplierID ?>
        <td data-name="SupplierID"<?= $Page->SupplierID->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_products_SupplierID" class="el_products_SupplierID">
<span<?= $Page->SupplierID->viewAttributes() ?>>
<?= $Page->SupplierID->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->CategoryID->Visible) { // CategoryID ?>
        <td data-name="CategoryID"<?= $Page->CategoryID->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_products_CategoryID" class="el_products_CategoryID">
<span<?= $Page->CategoryID->viewAttributes() ?>>
<?= $Page->CategoryID->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->QuantityPerUnit->Visible) { // QuantityPerUnit ?>
        <td data-name="QuantityPerUnit"<?= $Page->QuantityPerUnit->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_products_QuantityPerUnit" class="el_products_QuantityPerUnit">
<span<?= $Page->QuantityPerUnit->viewAttributes() ?>>
<?= $Page->QuantityPerUnit->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->UnitPrice->Visible) { // UnitPrice ?>
        <td data-name="UnitPrice"<?= $Page->UnitPrice->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_products_UnitPrice" class="el_products_UnitPrice">
<span<?= $Page->UnitPrice->viewAttributes() ?>>
<?= $Page->UnitPrice->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->UnitsInStock->Visible) { // UnitsInStock ?>
        <td data-name="UnitsInStock"<?= $Page->UnitsInStock->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_products_UnitsInStock" class="el_products_UnitsInStock">
<span<?= $Page->UnitsInStock->viewAttributes() ?>>
<?= $Page->UnitsInStock->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->UnitsOnOrder->Visible) { // UnitsOnOrder ?>
        <td data-name="UnitsOnOrder"<?= $Page->UnitsOnOrder->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_products_UnitsOnOrder" class="el_products_UnitsOnOrder">
<span<?= $Page->UnitsOnOrder->viewAttributes() ?>>
<?= $Page->UnitsOnOrder->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->ReorderLevel->Visible) { // ReorderLevel ?>
        <td data-name="ReorderLevel"<?= $Page->ReorderLevel->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_products_ReorderLevel" class="el_products_ReorderLevel">
<span<?= $Page->ReorderLevel->viewAttributes() ?>>
<?= $Page->ReorderLevel->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->Discontinued->Visible) { // Discontinued ?>
        <td data-name="Discontinued"<?= $Page->Discontinued->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_products_Discontinued" class="el_products_Discontinued">
<span<?= $Page->Discontinued->viewAttributes() ?>>
<div class="form-check d-inline-block">
    <input type="checkbox" id="x_Discontinued_<?= $Page->RowCount ?>" class="form-check-input" value="<?= $Page->Discontinued->getViewValue() ?>" disabled<?php if (ConvertToBool($Page->Discontinued->CurrentValue)) { ?> checked<?php } ?>>
    <label class="form-check-label" for="x_Discontinued_<?= $Page->RowCount ?>"></label>
</div></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->EAN13->Visible) { // EAN13 ?>
        <td data-name="EAN13"<?= $Page->EAN13->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_products_EAN13" class="el_products_EAN13">
<span<?= $Page->EAN13->viewAttributes() ?>><?= PhpBarcode::barcode(true)->show($Page->EAN13->CurrentValue, 'EAN-13', 60) ?></span>
</span>
</td>
    <?php } ?>
<?php
// Render list options (body, right)
$Page->ListOptions->render("body", "right", $Page->RowCount);
?>
    </tr>
<?php
    }
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
    ew.addEventHandlers("products");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
