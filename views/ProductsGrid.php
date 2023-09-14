<?php

namespace PHPMaker2023\demo2023;

// Set up and run Grid object
$Grid = Container("ProductsGrid");
$Grid->run();
?>
<?php if (!$Grid->isExport()) { ?>
<script>
var fproductsgrid;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let currentTable = <?= JsonEncode($Grid->toClientVar()) ?>;
    ew.deepAssign(ew.vars, { tables: { products: currentTable } });
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fproductsgrid")
        .setPageId("grid")
        .setFormKeyCountName("<?= $Grid->FormKeyCountName ?>")

        // Add fields
        .setFields([
            ["ProductID", [fields.ProductID.visible && fields.ProductID.required ? ew.Validators.required(fields.ProductID.caption) : null], fields.ProductID.isInvalid],
            ["ProductName", [fields.ProductName.visible && fields.ProductName.required ? ew.Validators.required(fields.ProductName.caption) : null], fields.ProductName.isInvalid],
            ["SupplierID", [fields.SupplierID.visible && fields.SupplierID.required ? ew.Validators.required(fields.SupplierID.caption) : null], fields.SupplierID.isInvalid],
            ["CategoryID", [fields.CategoryID.visible && fields.CategoryID.required ? ew.Validators.required(fields.CategoryID.caption) : null], fields.CategoryID.isInvalid],
            ["QuantityPerUnit", [fields.QuantityPerUnit.visible && fields.QuantityPerUnit.required ? ew.Validators.required(fields.QuantityPerUnit.caption) : null], fields.QuantityPerUnit.isInvalid],
            ["UnitPrice", [fields.UnitPrice.visible && fields.UnitPrice.required ? ew.Validators.required(fields.UnitPrice.caption) : null, ew.Validators.float], fields.UnitPrice.isInvalid],
            ["UnitsInStock", [fields.UnitsInStock.visible && fields.UnitsInStock.required ? ew.Validators.required(fields.UnitsInStock.caption) : null, ew.Validators.integer], fields.UnitsInStock.isInvalid],
            ["UnitsOnOrder", [fields.UnitsOnOrder.visible && fields.UnitsOnOrder.required ? ew.Validators.required(fields.UnitsOnOrder.caption) : null, ew.Validators.integer], fields.UnitsOnOrder.isInvalid],
            ["ReorderLevel", [fields.ReorderLevel.visible && fields.ReorderLevel.required ? ew.Validators.required(fields.ReorderLevel.caption) : null, ew.Validators.integer], fields.ReorderLevel.isInvalid],
            ["Discontinued", [fields.Discontinued.visible && fields.Discontinued.required ? ew.Validators.required(fields.Discontinued.caption) : null], fields.Discontinued.isInvalid],
            ["EAN13", [fields.EAN13.visible && fields.EAN13.required ? ew.Validators.required(fields.EAN13.caption) : null], fields.EAN13.isInvalid]
        ])

        // Check empty row
        .setEmptyRow(
            function (rowIndex) {
                let fobj = this.getForm(),
                    fields = [["ProductName",false],["SupplierID",false],["CategoryID",false],["QuantityPerUnit",false],["UnitPrice",false],["UnitsInStock",false],["UnitsOnOrder",false],["ReorderLevel",false],["Discontinued",true],["EAN13",false]];
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
            "SupplierID": <?= $Grid->SupplierID->toClientList($Grid) ?>,
            "CategoryID": <?= $Grid->CategoryID->toClientList($Grid) ?>,
            "Discontinued": <?= $Grid->Discontinued->toClientList($Grid) ?>,
        })
        .build();
    window[form.id] = form;
    loadjs.done(form.id);
});
</script>
<?php } ?>
<main class="list">
<div id="ew-list">
<?php if ($Grid->TotalRecords > 0 || $Grid->CurrentAction) { ?>
<div class="card ew-card ew-grid<?= $Grid->isAddOrEdit() ? " ew-grid-add-edit" : "" ?> <?= $Grid->TableGridClass ?>">
<?php if ($Grid->ShowOtherOptions) { ?>
<div class="card-header ew-grid-upper-panel">
<?php $Grid->OtherOptions->render("body") ?>
</div>
<?php } ?>
<div id="fproductsgrid" class="ew-form ew-list-form">
<div id="gmp_products" class="card-body ew-grid-middle-panel <?= $Grid->TableContainerClass ?>" style="<?= $Grid->TableContainerStyle ?>">
<table id="tbl_productsgrid" class="<?= $Grid->TableClass ?>"><!-- .ew-table -->
<thead>
    <tr class="ew-table-header">
<?php
// Header row
$Grid->RowType = ROWTYPE_HEADER;

// Render list options
$Grid->renderListOptions();

// Render list options (header, left)
$Grid->ListOptions->render("header", "left");
?>
<?php if ($Grid->ProductID->Visible) { // ProductID ?>
        <th data-name="ProductID" class="<?= $Grid->ProductID->headerCellClass() ?>"><div id="elh_products_ProductID" class="products_ProductID"><?= $Grid->renderFieldHeader($Grid->ProductID) ?></div></th>
<?php } ?>
<?php if ($Grid->ProductName->Visible) { // ProductName ?>
        <th data-name="ProductName" class="<?= $Grid->ProductName->headerCellClass() ?>"><div id="elh_products_ProductName" class="products_ProductName"><?= $Grid->renderFieldHeader($Grid->ProductName) ?></div></th>
<?php } ?>
<?php if ($Grid->SupplierID->Visible) { // SupplierID ?>
        <th data-name="SupplierID" class="<?= $Grid->SupplierID->headerCellClass() ?>"><div id="elh_products_SupplierID" class="products_SupplierID"><?= $Grid->renderFieldHeader($Grid->SupplierID) ?></div></th>
<?php } ?>
<?php if ($Grid->CategoryID->Visible) { // CategoryID ?>
        <th data-name="CategoryID" class="<?= $Grid->CategoryID->headerCellClass() ?>"><div id="elh_products_CategoryID" class="products_CategoryID"><?= $Grid->renderFieldHeader($Grid->CategoryID) ?></div></th>
<?php } ?>
<?php if ($Grid->QuantityPerUnit->Visible) { // QuantityPerUnit ?>
        <th data-name="QuantityPerUnit" class="<?= $Grid->QuantityPerUnit->headerCellClass() ?>"><div id="elh_products_QuantityPerUnit" class="products_QuantityPerUnit"><?= $Grid->renderFieldHeader($Grid->QuantityPerUnit) ?></div></th>
<?php } ?>
<?php if ($Grid->UnitPrice->Visible) { // UnitPrice ?>
        <th data-name="UnitPrice" class="<?= $Grid->UnitPrice->headerCellClass() ?>"><div id="elh_products_UnitPrice" class="products_UnitPrice"><?= $Grid->renderFieldHeader($Grid->UnitPrice) ?></div></th>
<?php } ?>
<?php if ($Grid->UnitsInStock->Visible) { // UnitsInStock ?>
        <th data-name="UnitsInStock" class="<?= $Grid->UnitsInStock->headerCellClass() ?>"><div id="elh_products_UnitsInStock" class="products_UnitsInStock"><?= $Grid->renderFieldHeader($Grid->UnitsInStock) ?></div></th>
<?php } ?>
<?php if ($Grid->UnitsOnOrder->Visible) { // UnitsOnOrder ?>
        <th data-name="UnitsOnOrder" class="<?= $Grid->UnitsOnOrder->headerCellClass() ?>"><div id="elh_products_UnitsOnOrder" class="products_UnitsOnOrder"><?= $Grid->renderFieldHeader($Grid->UnitsOnOrder) ?></div></th>
<?php } ?>
<?php if ($Grid->ReorderLevel->Visible) { // ReorderLevel ?>
        <th data-name="ReorderLevel" class="<?= $Grid->ReorderLevel->headerCellClass() ?>"><div id="elh_products_ReorderLevel" class="products_ReorderLevel"><?= $Grid->renderFieldHeader($Grid->ReorderLevel) ?></div></th>
<?php } ?>
<?php if ($Grid->Discontinued->Visible) { // Discontinued ?>
        <th data-name="Discontinued" class="<?= $Grid->Discontinued->headerCellClass() ?>"><div id="elh_products_Discontinued" class="products_Discontinued"><?= $Grid->renderFieldHeader($Grid->Discontinued) ?></div></th>
<?php } ?>
<?php if ($Grid->EAN13->Visible) { // EAN13 ?>
        <th data-name="EAN13" class="<?= $Grid->EAN13->headerCellClass() ?>"><div id="elh_products_EAN13" class="products_EAN13"><?= $Grid->renderFieldHeader($Grid->EAN13) ?></div></th>
<?php } ?>
<?php
// Render list options (header, right)
$Grid->ListOptions->render("header", "right");
?>
    </tr>
</thead>
<tbody data-page="<?= $Grid->getPageNumber() ?>">
<?php
$Grid->setupGrid();
while ($Grid->RecordCount < $Grid->StopRecord || $Grid->RowIndex === '$rowindex$') {
    $Grid->RecordCount++;
    if ($Grid->RecordCount >= $Grid->StartRecord) {
        $Grid->setupRow();

        // Skip 1) delete row / empty row for confirm page, 2) hidden row
        if (
            $Grid->RowAction != "delete" &&
            $Grid->RowAction != "insertdelete" &&
            !($Grid->RowAction == "insert" && $Grid->isConfirm() && $Grid->emptyRow()) &&
            $Grid->RowAction != "hide"
        ) {
?>
    <tr <?= $Grid->rowAttributes() ?>>
<?php
// Render list options (body, left)
$Grid->ListOptions->render("body", "left", $Grid->RowCount);
?>
    <?php if ($Grid->ProductID->Visible) { // ProductID ?>
        <td data-name="ProductID"<?= $Grid->ProductID->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_products_ProductID" class="el_products_ProductID"></span>
<input type="hidden" data-table="products" data-field="x_ProductID" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_ProductID" id="o<?= $Grid->RowIndex ?>_ProductID" value="<?= HtmlEncode($Grid->ProductID->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_products_ProductID" class="el_products_ProductID">
<span<?= $Grid->ProductID->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->ProductID->getDisplayValue($Grid->ProductID->EditValue))) ?>"></span>
<input type="hidden" data-table="products" data-field="x_ProductID" data-hidden="1" name="x<?= $Grid->RowIndex ?>_ProductID" id="x<?= $Grid->RowIndex ?>_ProductID" value="<?= HtmlEncode($Grid->ProductID->CurrentValue) ?>">
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_products_ProductID" class="el_products_ProductID">
<span<?= $Grid->ProductID->viewAttributes() ?>>
<?= $Grid->ProductID->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="products" data-field="x_ProductID" data-hidden="1" name="fproductsgrid$x<?= $Grid->RowIndex ?>_ProductID" id="fproductsgrid$x<?= $Grid->RowIndex ?>_ProductID" value="<?= HtmlEncode($Grid->ProductID->FormValue) ?>">
<input type="hidden" data-table="products" data-field="x_ProductID" data-hidden="1" data-old name="fproductsgrid$o<?= $Grid->RowIndex ?>_ProductID" id="fproductsgrid$o<?= $Grid->RowIndex ?>_ProductID" value="<?= HtmlEncode($Grid->ProductID->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } else { ?>
            <input type="hidden" data-table="products" data-field="x_ProductID" data-hidden="1" name="x<?= $Grid->RowIndex ?>_ProductID" id="x<?= $Grid->RowIndex ?>_ProductID" value="<?= HtmlEncode($Grid->ProductID->CurrentValue) ?>">
    <?php } ?>
    <?php if ($Grid->ProductName->Visible) { // ProductName ?>
        <td data-name="ProductName"<?= $Grid->ProductName->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_products_ProductName" class="el_products_ProductName">
<input type="<?= $Grid->ProductName->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_ProductName" id="x<?= $Grid->RowIndex ?>_ProductName" data-table="products" data-field="x_ProductName" value="<?= $Grid->ProductName->EditValue ?>" size="30" maxlength="40" placeholder="<?= HtmlEncode($Grid->ProductName->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->ProductName->formatPattern()) ?>"<?= $Grid->ProductName->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->ProductName->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="products" data-field="x_ProductName" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_ProductName" id="o<?= $Grid->RowIndex ?>_ProductName" value="<?= HtmlEncode($Grid->ProductName->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_products_ProductName" class="el_products_ProductName">
<input type="<?= $Grid->ProductName->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_ProductName" id="x<?= $Grid->RowIndex ?>_ProductName" data-table="products" data-field="x_ProductName" value="<?= $Grid->ProductName->EditValue ?>" size="30" maxlength="40" placeholder="<?= HtmlEncode($Grid->ProductName->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->ProductName->formatPattern()) ?>"<?= $Grid->ProductName->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->ProductName->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_products_ProductName" class="el_products_ProductName">
<span<?= $Grid->ProductName->viewAttributes() ?>>
<?= $Grid->ProductName->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="products" data-field="x_ProductName" data-hidden="1" name="fproductsgrid$x<?= $Grid->RowIndex ?>_ProductName" id="fproductsgrid$x<?= $Grid->RowIndex ?>_ProductName" value="<?= HtmlEncode($Grid->ProductName->FormValue) ?>">
<input type="hidden" data-table="products" data-field="x_ProductName" data-hidden="1" data-old name="fproductsgrid$o<?= $Grid->RowIndex ?>_ProductName" id="fproductsgrid$o<?= $Grid->RowIndex ?>_ProductName" value="<?= HtmlEncode($Grid->ProductName->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->SupplierID->Visible) { // SupplierID ?>
        <td data-name="SupplierID"<?= $Grid->SupplierID->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_products_SupplierID" class="el_products_SupplierID">
    <select
        id="x<?= $Grid->RowIndex ?>_SupplierID"
        name="x<?= $Grid->RowIndex ?>_SupplierID"
        class="form-control ew-select<?= $Grid->SupplierID->isInvalidClass() ?>"
        data-select2-id="fproductsgrid_x<?= $Grid->RowIndex ?>_SupplierID"
        data-table="products"
        data-field="x_SupplierID"
        data-caption="<?= HtmlEncode(RemoveHtml($Grid->SupplierID->caption())) ?>"
        data-modal-lookup="true"
        data-value-separator="<?= $Grid->SupplierID->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->SupplierID->getPlaceHolder()) ?>"
        <?= $Grid->SupplierID->editAttributes() ?>>
        <?= $Grid->SupplierID->selectOptionListHtml("x{$Grid->RowIndex}_SupplierID") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->SupplierID->getErrorMessage() ?></div>
<?= $Grid->SupplierID->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_SupplierID") ?>
<script>
loadjs.ready("fproductsgrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_SupplierID", selectId: "fproductsgrid_x<?= $Grid->RowIndex ?>_SupplierID" };
    if (fproductsgrid.lists.SupplierID?.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_SupplierID", form: "fproductsgrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_SupplierID", form: "fproductsgrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options = Object.assign({}, ew.modalLookupOptions, options, ew.vars.tables.products.fields.SupplierID.modalLookupOptions);
    ew.createModalLookup(options);
});
</script>
</span>
<input type="hidden" data-table="products" data-field="x_SupplierID" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_SupplierID" id="o<?= $Grid->RowIndex ?>_SupplierID" value="<?= HtmlEncode($Grid->SupplierID->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_products_SupplierID" class="el_products_SupplierID">
    <select
        id="x<?= $Grid->RowIndex ?>_SupplierID"
        name="x<?= $Grid->RowIndex ?>_SupplierID"
        class="form-control ew-select<?= $Grid->SupplierID->isInvalidClass() ?>"
        data-select2-id="fproductsgrid_x<?= $Grid->RowIndex ?>_SupplierID"
        data-table="products"
        data-field="x_SupplierID"
        data-caption="<?= HtmlEncode(RemoveHtml($Grid->SupplierID->caption())) ?>"
        data-modal-lookup="true"
        data-value-separator="<?= $Grid->SupplierID->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->SupplierID->getPlaceHolder()) ?>"
        <?= $Grid->SupplierID->editAttributes() ?>>
        <?= $Grid->SupplierID->selectOptionListHtml("x{$Grid->RowIndex}_SupplierID") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->SupplierID->getErrorMessage() ?></div>
<?= $Grid->SupplierID->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_SupplierID") ?>
<script>
loadjs.ready("fproductsgrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_SupplierID", selectId: "fproductsgrid_x<?= $Grid->RowIndex ?>_SupplierID" };
    if (fproductsgrid.lists.SupplierID?.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_SupplierID", form: "fproductsgrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_SupplierID", form: "fproductsgrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options = Object.assign({}, ew.modalLookupOptions, options, ew.vars.tables.products.fields.SupplierID.modalLookupOptions);
    ew.createModalLookup(options);
});
</script>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_products_SupplierID" class="el_products_SupplierID">
<span<?= $Grid->SupplierID->viewAttributes() ?>>
<?= $Grid->SupplierID->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="products" data-field="x_SupplierID" data-hidden="1" name="fproductsgrid$x<?= $Grid->RowIndex ?>_SupplierID" id="fproductsgrid$x<?= $Grid->RowIndex ?>_SupplierID" value="<?= HtmlEncode($Grid->SupplierID->FormValue) ?>">
<input type="hidden" data-table="products" data-field="x_SupplierID" data-hidden="1" data-old name="fproductsgrid$o<?= $Grid->RowIndex ?>_SupplierID" id="fproductsgrid$o<?= $Grid->RowIndex ?>_SupplierID" value="<?= HtmlEncode($Grid->SupplierID->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->CategoryID->Visible) { // CategoryID ?>
        <td data-name="CategoryID"<?= $Grid->CategoryID->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<?php if ($Grid->CategoryID->getSessionValue() != "") { ?>
<span<?= $Grid->CategoryID->viewAttributes() ?>>
<span class="form-control-plaintext"><?= $Grid->CategoryID->getDisplayValue($Grid->CategoryID->ViewValue) ?></span></span>
<input type="hidden" id="x<?= $Grid->RowIndex ?>_CategoryID" name="x<?= $Grid->RowIndex ?>_CategoryID" value="<?= HtmlEncode($Grid->CategoryID->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_products_CategoryID" class="el_products_CategoryID">
    <select
        id="x<?= $Grid->RowIndex ?>_CategoryID"
        name="x<?= $Grid->RowIndex ?>_CategoryID"
        class="form-select ew-select<?= $Grid->CategoryID->isInvalidClass() ?>"
        <?php if (!$Grid->CategoryID->IsNativeSelect) { ?>
        data-select2-id="fproductsgrid_x<?= $Grid->RowIndex ?>_CategoryID"
        <?php } ?>
        data-table="products"
        data-field="x_CategoryID"
        data-value-separator="<?= $Grid->CategoryID->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->CategoryID->getPlaceHolder()) ?>"
        <?= $Grid->CategoryID->editAttributes() ?>>
        <?= $Grid->CategoryID->selectOptionListHtml("x{$Grid->RowIndex}_CategoryID") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->CategoryID->getErrorMessage() ?></div>
<?= $Grid->CategoryID->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_CategoryID") ?>
<?php if (!$Grid->CategoryID->IsNativeSelect) { ?>
<script>
loadjs.ready("fproductsgrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_CategoryID", selectId: "fproductsgrid_x<?= $Grid->RowIndex ?>_CategoryID" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fproductsgrid.lists.CategoryID?.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_CategoryID", form: "fproductsgrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_CategoryID", form: "fproductsgrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.products.fields.CategoryID.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
<?php } ?>
<input type="hidden" data-table="products" data-field="x_CategoryID" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_CategoryID" id="o<?= $Grid->RowIndex ?>_CategoryID" value="<?= HtmlEncode($Grid->CategoryID->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<?php if ($Grid->CategoryID->getSessionValue() != "") { ?>
<span<?= $Grid->CategoryID->viewAttributes() ?>>
<span class="form-control-plaintext"><?= $Grid->CategoryID->getDisplayValue($Grid->CategoryID->ViewValue) ?></span></span>
<input type="hidden" id="x<?= $Grid->RowIndex ?>_CategoryID" name="x<?= $Grid->RowIndex ?>_CategoryID" value="<?= HtmlEncode($Grid->CategoryID->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_products_CategoryID" class="el_products_CategoryID">
    <select
        id="x<?= $Grid->RowIndex ?>_CategoryID"
        name="x<?= $Grid->RowIndex ?>_CategoryID"
        class="form-select ew-select<?= $Grid->CategoryID->isInvalidClass() ?>"
        <?php if (!$Grid->CategoryID->IsNativeSelect) { ?>
        data-select2-id="fproductsgrid_x<?= $Grid->RowIndex ?>_CategoryID"
        <?php } ?>
        data-table="products"
        data-field="x_CategoryID"
        data-value-separator="<?= $Grid->CategoryID->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->CategoryID->getPlaceHolder()) ?>"
        <?= $Grid->CategoryID->editAttributes() ?>>
        <?= $Grid->CategoryID->selectOptionListHtml("x{$Grid->RowIndex}_CategoryID") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->CategoryID->getErrorMessage() ?></div>
<?= $Grid->CategoryID->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_CategoryID") ?>
<?php if (!$Grid->CategoryID->IsNativeSelect) { ?>
<script>
loadjs.ready("fproductsgrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_CategoryID", selectId: "fproductsgrid_x<?= $Grid->RowIndex ?>_CategoryID" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fproductsgrid.lists.CategoryID?.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_CategoryID", form: "fproductsgrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_CategoryID", form: "fproductsgrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.products.fields.CategoryID.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
<?php } ?>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_products_CategoryID" class="el_products_CategoryID">
<span<?= $Grid->CategoryID->viewAttributes() ?>>
<?= $Grid->CategoryID->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="products" data-field="x_CategoryID" data-hidden="1" name="fproductsgrid$x<?= $Grid->RowIndex ?>_CategoryID" id="fproductsgrid$x<?= $Grid->RowIndex ?>_CategoryID" value="<?= HtmlEncode($Grid->CategoryID->FormValue) ?>">
<input type="hidden" data-table="products" data-field="x_CategoryID" data-hidden="1" data-old name="fproductsgrid$o<?= $Grid->RowIndex ?>_CategoryID" id="fproductsgrid$o<?= $Grid->RowIndex ?>_CategoryID" value="<?= HtmlEncode($Grid->CategoryID->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->QuantityPerUnit->Visible) { // QuantityPerUnit ?>
        <td data-name="QuantityPerUnit"<?= $Grid->QuantityPerUnit->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_products_QuantityPerUnit" class="el_products_QuantityPerUnit">
<input type="<?= $Grid->QuantityPerUnit->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_QuantityPerUnit" id="x<?= $Grid->RowIndex ?>_QuantityPerUnit" data-table="products" data-field="x_QuantityPerUnit" value="<?= $Grid->QuantityPerUnit->EditValue ?>" size="30" maxlength="20" placeholder="<?= HtmlEncode($Grid->QuantityPerUnit->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->QuantityPerUnit->formatPattern()) ?>"<?= $Grid->QuantityPerUnit->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->QuantityPerUnit->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="products" data-field="x_QuantityPerUnit" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_QuantityPerUnit" id="o<?= $Grid->RowIndex ?>_QuantityPerUnit" value="<?= HtmlEncode($Grid->QuantityPerUnit->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_products_QuantityPerUnit" class="el_products_QuantityPerUnit">
<input type="<?= $Grid->QuantityPerUnit->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_QuantityPerUnit" id="x<?= $Grid->RowIndex ?>_QuantityPerUnit" data-table="products" data-field="x_QuantityPerUnit" value="<?= $Grid->QuantityPerUnit->EditValue ?>" size="30" maxlength="20" placeholder="<?= HtmlEncode($Grid->QuantityPerUnit->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->QuantityPerUnit->formatPattern()) ?>"<?= $Grid->QuantityPerUnit->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->QuantityPerUnit->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_products_QuantityPerUnit" class="el_products_QuantityPerUnit">
<span<?= $Grid->QuantityPerUnit->viewAttributes() ?>>
<?= $Grid->QuantityPerUnit->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="products" data-field="x_QuantityPerUnit" data-hidden="1" name="fproductsgrid$x<?= $Grid->RowIndex ?>_QuantityPerUnit" id="fproductsgrid$x<?= $Grid->RowIndex ?>_QuantityPerUnit" value="<?= HtmlEncode($Grid->QuantityPerUnit->FormValue) ?>">
<input type="hidden" data-table="products" data-field="x_QuantityPerUnit" data-hidden="1" data-old name="fproductsgrid$o<?= $Grid->RowIndex ?>_QuantityPerUnit" id="fproductsgrid$o<?= $Grid->RowIndex ?>_QuantityPerUnit" value="<?= HtmlEncode($Grid->QuantityPerUnit->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->UnitPrice->Visible) { // UnitPrice ?>
        <td data-name="UnitPrice"<?= $Grid->UnitPrice->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_products_UnitPrice" class="el_products_UnitPrice">
<input type="<?= $Grid->UnitPrice->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_UnitPrice" id="x<?= $Grid->RowIndex ?>_UnitPrice" data-table="products" data-field="x_UnitPrice" value="<?= $Grid->UnitPrice->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->UnitPrice->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->UnitPrice->formatPattern()) ?>"<?= $Grid->UnitPrice->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->UnitPrice->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="products" data-field="x_UnitPrice" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_UnitPrice" id="o<?= $Grid->RowIndex ?>_UnitPrice" value="<?= HtmlEncode($Grid->UnitPrice->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_products_UnitPrice" class="el_products_UnitPrice">
<input type="<?= $Grid->UnitPrice->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_UnitPrice" id="x<?= $Grid->RowIndex ?>_UnitPrice" data-table="products" data-field="x_UnitPrice" value="<?= $Grid->UnitPrice->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->UnitPrice->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->UnitPrice->formatPattern()) ?>"<?= $Grid->UnitPrice->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->UnitPrice->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_products_UnitPrice" class="el_products_UnitPrice">
<span<?= $Grid->UnitPrice->viewAttributes() ?>>
<?= $Grid->UnitPrice->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="products" data-field="x_UnitPrice" data-hidden="1" name="fproductsgrid$x<?= $Grid->RowIndex ?>_UnitPrice" id="fproductsgrid$x<?= $Grid->RowIndex ?>_UnitPrice" value="<?= HtmlEncode($Grid->UnitPrice->FormValue) ?>">
<input type="hidden" data-table="products" data-field="x_UnitPrice" data-hidden="1" data-old name="fproductsgrid$o<?= $Grid->RowIndex ?>_UnitPrice" id="fproductsgrid$o<?= $Grid->RowIndex ?>_UnitPrice" value="<?= HtmlEncode($Grid->UnitPrice->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->UnitsInStock->Visible) { // UnitsInStock ?>
        <td data-name="UnitsInStock"<?= $Grid->UnitsInStock->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_products_UnitsInStock" class="el_products_UnitsInStock">
<input type="<?= $Grid->UnitsInStock->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_UnitsInStock" id="x<?= $Grid->RowIndex ?>_UnitsInStock" data-table="products" data-field="x_UnitsInStock" value="<?= $Grid->UnitsInStock->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->UnitsInStock->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->UnitsInStock->formatPattern()) ?>"<?= $Grid->UnitsInStock->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->UnitsInStock->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="products" data-field="x_UnitsInStock" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_UnitsInStock" id="o<?= $Grid->RowIndex ?>_UnitsInStock" value="<?= HtmlEncode($Grid->UnitsInStock->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_products_UnitsInStock" class="el_products_UnitsInStock">
<input type="<?= $Grid->UnitsInStock->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_UnitsInStock" id="x<?= $Grid->RowIndex ?>_UnitsInStock" data-table="products" data-field="x_UnitsInStock" value="<?= $Grid->UnitsInStock->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->UnitsInStock->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->UnitsInStock->formatPattern()) ?>"<?= $Grid->UnitsInStock->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->UnitsInStock->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_products_UnitsInStock" class="el_products_UnitsInStock">
<span<?= $Grid->UnitsInStock->viewAttributes() ?>>
<?= $Grid->UnitsInStock->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="products" data-field="x_UnitsInStock" data-hidden="1" name="fproductsgrid$x<?= $Grid->RowIndex ?>_UnitsInStock" id="fproductsgrid$x<?= $Grid->RowIndex ?>_UnitsInStock" value="<?= HtmlEncode($Grid->UnitsInStock->FormValue) ?>">
<input type="hidden" data-table="products" data-field="x_UnitsInStock" data-hidden="1" data-old name="fproductsgrid$o<?= $Grid->RowIndex ?>_UnitsInStock" id="fproductsgrid$o<?= $Grid->RowIndex ?>_UnitsInStock" value="<?= HtmlEncode($Grid->UnitsInStock->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->UnitsOnOrder->Visible) { // UnitsOnOrder ?>
        <td data-name="UnitsOnOrder"<?= $Grid->UnitsOnOrder->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_products_UnitsOnOrder" class="el_products_UnitsOnOrder">
<input type="<?= $Grid->UnitsOnOrder->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_UnitsOnOrder" id="x<?= $Grid->RowIndex ?>_UnitsOnOrder" data-table="products" data-field="x_UnitsOnOrder" value="<?= $Grid->UnitsOnOrder->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->UnitsOnOrder->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->UnitsOnOrder->formatPattern()) ?>"<?= $Grid->UnitsOnOrder->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->UnitsOnOrder->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="products" data-field="x_UnitsOnOrder" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_UnitsOnOrder" id="o<?= $Grid->RowIndex ?>_UnitsOnOrder" value="<?= HtmlEncode($Grid->UnitsOnOrder->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_products_UnitsOnOrder" class="el_products_UnitsOnOrder">
<input type="<?= $Grid->UnitsOnOrder->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_UnitsOnOrder" id="x<?= $Grid->RowIndex ?>_UnitsOnOrder" data-table="products" data-field="x_UnitsOnOrder" value="<?= $Grid->UnitsOnOrder->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->UnitsOnOrder->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->UnitsOnOrder->formatPattern()) ?>"<?= $Grid->UnitsOnOrder->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->UnitsOnOrder->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_products_UnitsOnOrder" class="el_products_UnitsOnOrder">
<span<?= $Grid->UnitsOnOrder->viewAttributes() ?>>
<?= $Grid->UnitsOnOrder->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="products" data-field="x_UnitsOnOrder" data-hidden="1" name="fproductsgrid$x<?= $Grid->RowIndex ?>_UnitsOnOrder" id="fproductsgrid$x<?= $Grid->RowIndex ?>_UnitsOnOrder" value="<?= HtmlEncode($Grid->UnitsOnOrder->FormValue) ?>">
<input type="hidden" data-table="products" data-field="x_UnitsOnOrder" data-hidden="1" data-old name="fproductsgrid$o<?= $Grid->RowIndex ?>_UnitsOnOrder" id="fproductsgrid$o<?= $Grid->RowIndex ?>_UnitsOnOrder" value="<?= HtmlEncode($Grid->UnitsOnOrder->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->ReorderLevel->Visible) { // ReorderLevel ?>
        <td data-name="ReorderLevel"<?= $Grid->ReorderLevel->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_products_ReorderLevel" class="el_products_ReorderLevel">
<input type="<?= $Grid->ReorderLevel->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_ReorderLevel" id="x<?= $Grid->RowIndex ?>_ReorderLevel" data-table="products" data-field="x_ReorderLevel" value="<?= $Grid->ReorderLevel->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->ReorderLevel->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->ReorderLevel->formatPattern()) ?>"<?= $Grid->ReorderLevel->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->ReorderLevel->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="products" data-field="x_ReorderLevel" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_ReorderLevel" id="o<?= $Grid->RowIndex ?>_ReorderLevel" value="<?= HtmlEncode($Grid->ReorderLevel->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_products_ReorderLevel" class="el_products_ReorderLevel">
<input type="<?= $Grid->ReorderLevel->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_ReorderLevel" id="x<?= $Grid->RowIndex ?>_ReorderLevel" data-table="products" data-field="x_ReorderLevel" value="<?= $Grid->ReorderLevel->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->ReorderLevel->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->ReorderLevel->formatPattern()) ?>"<?= $Grid->ReorderLevel->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->ReorderLevel->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_products_ReorderLevel" class="el_products_ReorderLevel">
<span<?= $Grid->ReorderLevel->viewAttributes() ?>>
<?= $Grid->ReorderLevel->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="products" data-field="x_ReorderLevel" data-hidden="1" name="fproductsgrid$x<?= $Grid->RowIndex ?>_ReorderLevel" id="fproductsgrid$x<?= $Grid->RowIndex ?>_ReorderLevel" value="<?= HtmlEncode($Grid->ReorderLevel->FormValue) ?>">
<input type="hidden" data-table="products" data-field="x_ReorderLevel" data-hidden="1" data-old name="fproductsgrid$o<?= $Grid->RowIndex ?>_ReorderLevel" id="fproductsgrid$o<?= $Grid->RowIndex ?>_ReorderLevel" value="<?= HtmlEncode($Grid->ReorderLevel->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->Discontinued->Visible) { // Discontinued ?>
        <td data-name="Discontinued"<?= $Grid->Discontinued->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_products_Discontinued" class="el_products_Discontinued">
<div class="form-check d-inline-block">
    <input type="checkbox" class="form-check-input<?= $Grid->Discontinued->isInvalidClass() ?>" data-table="products" data-field="x_Discontinued" data-boolean name="x<?= $Grid->RowIndex ?>_Discontinued" id="x<?= $Grid->RowIndex ?>_Discontinued" value="1"<?= ConvertToBool($Grid->Discontinued->CurrentValue) ? " checked" : "" ?><?= $Grid->Discontinued->editAttributes() ?>>
    <div class="invalid-feedback"><?= $Grid->Discontinued->getErrorMessage() ?></div>
</div>
</span>
<input type="hidden" data-table="products" data-field="x_Discontinued" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_Discontinued" id="o<?= $Grid->RowIndex ?>_Discontinued" value="<?= HtmlEncode($Grid->Discontinued->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_products_Discontinued" class="el_products_Discontinued">
<div class="form-check d-inline-block">
    <input type="checkbox" class="form-check-input<?= $Grid->Discontinued->isInvalidClass() ?>" data-table="products" data-field="x_Discontinued" data-boolean name="x<?= $Grid->RowIndex ?>_Discontinued" id="x<?= $Grid->RowIndex ?>_Discontinued" value="1"<?= ConvertToBool($Grid->Discontinued->CurrentValue) ? " checked" : "" ?><?= $Grid->Discontinued->editAttributes() ?>>
    <div class="invalid-feedback"><?= $Grid->Discontinued->getErrorMessage() ?></div>
</div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_products_Discontinued" class="el_products_Discontinued">
<span<?= $Grid->Discontinued->viewAttributes() ?>>
<div class="form-check d-inline-block">
    <input type="checkbox" id="x_Discontinued_<?= $Grid->RowCount ?>" class="form-check-input" value="<?= $Grid->Discontinued->getViewValue() ?>" disabled<?php if (ConvertToBool($Grid->Discontinued->CurrentValue)) { ?> checked<?php } ?>>
    <label class="form-check-label" for="x_Discontinued_<?= $Grid->RowCount ?>"></label>
</div></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="products" data-field="x_Discontinued" data-hidden="1" name="fproductsgrid$x<?= $Grid->RowIndex ?>_Discontinued" id="fproductsgrid$x<?= $Grid->RowIndex ?>_Discontinued" value="<?= HtmlEncode($Grid->Discontinued->FormValue) ?>">
<input type="hidden" data-table="products" data-field="x_Discontinued" data-hidden="1" data-old name="fproductsgrid$o<?= $Grid->RowIndex ?>_Discontinued" id="fproductsgrid$o<?= $Grid->RowIndex ?>_Discontinued" value="<?= HtmlEncode($Grid->Discontinued->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->EAN13->Visible) { // EAN13 ?>
        <td data-name="EAN13"<?= $Grid->EAN13->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_products_EAN13" class="el_products_EAN13">
<input type="<?= $Grid->EAN13->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_EAN13" id="x<?= $Grid->RowIndex ?>_EAN13" data-table="products" data-field="x_EAN13" value="<?= $Grid->EAN13->EditValue ?>" size="30" maxlength="13" placeholder="<?= HtmlEncode($Grid->EAN13->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->EAN13->formatPattern()) ?>"<?= $Grid->EAN13->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->EAN13->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="products" data-field="x_EAN13" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_EAN13" id="o<?= $Grid->RowIndex ?>_EAN13" value="<?= HtmlEncode($Grid->EAN13->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_products_EAN13" class="el_products_EAN13">
<input type="<?= $Grid->EAN13->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_EAN13" id="x<?= $Grid->RowIndex ?>_EAN13" data-table="products" data-field="x_EAN13" value="<?= $Grid->EAN13->EditValue ?>" size="30" maxlength="13" placeholder="<?= HtmlEncode($Grid->EAN13->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->EAN13->formatPattern()) ?>"<?= $Grid->EAN13->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->EAN13->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_products_EAN13" class="el_products_EAN13">
<span<?= $Grid->EAN13->viewAttributes() ?>><?= PhpBarcode::barcode(true)->show($Grid->EAN13->CurrentValue, 'EAN-13', 60) ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="products" data-field="x_EAN13" data-hidden="1" name="fproductsgrid$x<?= $Grid->RowIndex ?>_EAN13" id="fproductsgrid$x<?= $Grid->RowIndex ?>_EAN13" value="<?= HtmlEncode($Grid->EAN13->FormValue) ?>">
<input type="hidden" data-table="products" data-field="x_EAN13" data-hidden="1" data-old name="fproductsgrid$o<?= $Grid->RowIndex ?>_EAN13" id="fproductsgrid$o<?= $Grid->RowIndex ?>_EAN13" value="<?= HtmlEncode($Grid->EAN13->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
<?php
// Render list options (body, right)
$Grid->ListOptions->render("body", "right", $Grid->RowCount);
?>
    </tr>
<?php if ($Grid->RowType == ROWTYPE_ADD || $Grid->RowType == ROWTYPE_EDIT) { ?>
<script data-rowindex="<?= $Grid->RowIndex ?>">
loadjs.ready(["fproductsgrid","load"], () => fproductsgrid.updateLists(<?= $Grid->RowIndex ?><?= $Grid->isAdd() || $Grid->isEdit() || $Grid->isCopy() || $Grid->RowIndex === '$rowindex$' ? ", true" : "" ?>));
</script>
<?php } ?>
<?php
    }
    } // End delete row checking
    if (
        $Grid->Recordset &&
        !$Grid->Recordset->EOF &&
        $Grid->RowIndex !== '$rowindex$' &&
        (!$Grid->isGridAdd() || $Grid->CurrentMode == "copy") &&
        (!(($Grid->isCopy() || $Grid->isAdd()) && $Grid->RowIndex == 0))
    ) {
        $Grid->Recordset->moveNext();
    }
    // Reset for template row
    if ($Grid->RowIndex === '$rowindex$') {
        $Grid->RowIndex = 0;
    }
    // Reset inline add/copy row
    if (($Grid->isCopy() || $Grid->isAdd()) && $Grid->RowIndex == 0) {
        $Grid->RowIndex = 1;
    }
}
?>
</tbody>
</table><!-- /.ew-table -->
<?php if ($Grid->CurrentMode == "add" || $Grid->CurrentMode == "copy") { ?>
<input type="hidden" name="<?= $Grid->FormKeyCountName ?>" id="<?= $Grid->FormKeyCountName ?>" value="<?= $Grid->KeyCount ?>">
<?= $Grid->MultiSelectKey ?>
<?php } ?>
<?php if ($Grid->CurrentMode == "edit") { ?>
<input type="hidden" name="<?= $Grid->FormKeyCountName ?>" id="<?= $Grid->FormKeyCountName ?>" value="<?= $Grid->KeyCount ?>">
<?= $Grid->MultiSelectKey ?>
<?php } ?>
</div><!-- /.ew-grid-middle-panel -->
<?php if ($Grid->CurrentMode == "") { ?>
<input type="hidden" name="action" id="action" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fproductsgrid">
</div><!-- /.ew-list-form -->
<?php
// Close recordset
if ($Grid->Recordset) {
    $Grid->Recordset->close();
}
?>
<?php if ($Grid->ShowOtherOptions) { ?>
<div class="card-footer ew-grid-lower-panel">
<?php $Grid->OtherOptions->render("body", "bottom") ?>
</div>
<?php } ?>
</div><!-- /.ew-grid -->
<?php } else { ?>
<div class="ew-list-other-options">
<?php $Grid->OtherOptions->render("body") ?>
</div>
<?php } ?>
</div>
</main>
<?php if (!$Grid->isExport()) { ?>
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
