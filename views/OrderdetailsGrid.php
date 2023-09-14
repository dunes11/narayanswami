<?php

namespace PHPMaker2023\demo2023;

// Set up and run Grid object
$Grid = Container("OrderdetailsGrid");
$Grid->run();
?>
<?php if (!$Grid->isExport()) { ?>
<script>
var forderdetailsgrid;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let currentTable = <?= JsonEncode($Grid->toClientVar()) ?>;
    ew.deepAssign(ew.vars, { tables: { orderdetails: currentTable } });
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("forderdetailsgrid")
        .setPageId("grid")
        .setFormKeyCountName("<?= $Grid->FormKeyCountName ?>")

        // Add fields
        .setFields([
            ["OrderID", [fields.OrderID.visible && fields.OrderID.required ? ew.Validators.required(fields.OrderID.caption) : null, ew.Validators.integer], fields.OrderID.isInvalid],
            ["ProductID", [fields.ProductID.visible && fields.ProductID.required ? ew.Validators.required(fields.ProductID.caption) : null], fields.ProductID.isInvalid],
            ["UnitPrice", [fields.UnitPrice.visible && fields.UnitPrice.required ? ew.Validators.required(fields.UnitPrice.caption) : null, ew.Validators.float], fields.UnitPrice.isInvalid],
            ["Quantity", [fields.Quantity.visible && fields.Quantity.required ? ew.Validators.required(fields.Quantity.caption) : null, ew.Validators.integer], fields.Quantity.isInvalid],
            ["Discount", [fields.Discount.visible && fields.Discount.required ? ew.Validators.required(fields.Discount.caption) : null, ew.Validators.float], fields.Discount.isInvalid],
            ["SubTotal", [fields.SubTotal.visible && fields.SubTotal.required ? ew.Validators.required(fields.SubTotal.caption) : null, ew.Validators.float], fields.SubTotal.isInvalid]
        ])

        // Check empty row
        .setEmptyRow(
            function (rowIndex) {
                let fobj = this.getForm(),
                    fields = [["OrderID",false],["ProductID",false],["UnitPrice",false],["Quantity",false],["Discount",false],["SubTotal",false]];
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
            "ProductID": <?= $Grid->ProductID->toClientList($Grid) ?>,
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
<div id="forderdetailsgrid" class="ew-form ew-list-form">
<div id="gmp_orderdetails" class="card-body ew-grid-middle-panel <?= $Grid->TableContainerClass ?>" style="<?= $Grid->TableContainerStyle ?>">
<table id="tbl_orderdetailsgrid" class="<?= $Grid->TableClass ?>"><!-- .ew-table -->
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
<?php if ($Grid->OrderID->Visible) { // OrderID ?>
        <th data-name="OrderID" class="<?= $Grid->OrderID->headerCellClass() ?>"><div id="elh_orderdetails_OrderID" class="orderdetails_OrderID"><?= $Grid->renderFieldHeader($Grid->OrderID) ?></div></th>
<?php } ?>
<?php if ($Grid->ProductID->Visible) { // ProductID ?>
        <th data-name="ProductID" class="<?= $Grid->ProductID->headerCellClass() ?>"><div id="elh_orderdetails_ProductID" class="orderdetails_ProductID"><?= $Grid->renderFieldHeader($Grid->ProductID) ?></div></th>
<?php } ?>
<?php if ($Grid->UnitPrice->Visible) { // UnitPrice ?>
        <th data-name="UnitPrice" class="<?= $Grid->UnitPrice->headerCellClass() ?>"><div id="elh_orderdetails_UnitPrice" class="orderdetails_UnitPrice"><?= $Grid->renderFieldHeader($Grid->UnitPrice) ?></div></th>
<?php } ?>
<?php if ($Grid->Quantity->Visible) { // Quantity ?>
        <th data-name="Quantity" class="<?= $Grid->Quantity->headerCellClass() ?>"><div id="elh_orderdetails_Quantity" class="orderdetails_Quantity"><?= $Grid->renderFieldHeader($Grid->Quantity) ?></div></th>
<?php } ?>
<?php if ($Grid->Discount->Visible) { // Discount ?>
        <th data-name="Discount" class="<?= $Grid->Discount->headerCellClass() ?>"><div id="elh_orderdetails_Discount" class="orderdetails_Discount"><?= $Grid->renderFieldHeader($Grid->Discount) ?></div></th>
<?php } ?>
<?php if ($Grid->SubTotal->Visible) { // SubTotal ?>
        <th data-name="SubTotal" class="<?= $Grid->SubTotal->headerCellClass() ?>"><div id="elh_orderdetails_SubTotal" class="orderdetails_SubTotal"><?= $Grid->renderFieldHeader($Grid->SubTotal) ?></div></th>
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
    <?php if ($Grid->OrderID->Visible) { // OrderID ?>
        <td data-name="OrderID"<?= $Grid->OrderID->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<?php if ($Grid->OrderID->getSessionValue() != "") { ?>
<span<?= $Grid->OrderID->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->OrderID->getDisplayValue($Grid->OrderID->ViewValue))) ?>"></span>
<input type="hidden" id="x<?= $Grid->RowIndex ?>_OrderID" name="x<?= $Grid->RowIndex ?>_OrderID" value="<?= HtmlEncode($Grid->OrderID->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_orderdetails_OrderID" class="el_orderdetails_OrderID">
<input type="<?= $Grid->OrderID->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_OrderID" id="x<?= $Grid->RowIndex ?>_OrderID" data-table="orderdetails" data-field="x_OrderID" value="<?= $Grid->OrderID->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->OrderID->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->OrderID->formatPattern()) ?>"<?= $Grid->OrderID->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->OrderID->getErrorMessage() ?></div>
</span>
<?php } ?>
<input type="hidden" data-table="orderdetails" data-field="x_OrderID" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_OrderID" id="o<?= $Grid->RowIndex ?>_OrderID" value="<?= HtmlEncode($Grid->OrderID->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_orderdetails_OrderID" class="el_orderdetails_OrderID">
<?php if ($Grid->OrderID->getSessionValue() != "") { ?>
<span<?= $Grid->OrderID->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->OrderID->getDisplayValue($Grid->OrderID->EditValue))) ?>"></span>
<input type="hidden" id="x<?= $Grid->RowIndex ?>_OrderID" name="x<?= $Grid->RowIndex ?>_OrderID" value="<?= HtmlEncode($Grid->OrderID->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<input type="<?= $Grid->OrderID->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_OrderID" id="x<?= $Grid->RowIndex ?>_OrderID" data-table="orderdetails" data-field="x_OrderID" value="<?= $Grid->OrderID->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->OrderID->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->OrderID->formatPattern()) ?>"<?= $Grid->OrderID->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->OrderID->getErrorMessage() ?></div>
<?php } ?>
<input type="hidden" data-table="orderdetails" data-field="x_OrderID" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_OrderID" id="o<?= $Grid->RowIndex ?>_OrderID" value="<?= HtmlEncode($Grid->OrderID->OldValue ?? $Grid->OrderID->CurrentValue) ?>">
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_orderdetails_OrderID" class="el_orderdetails_OrderID">
<span<?= $Grid->OrderID->viewAttributes() ?>>
<?= $Grid->OrderID->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="orderdetails" data-field="x_OrderID" data-hidden="1" name="forderdetailsgrid$x<?= $Grid->RowIndex ?>_OrderID" id="forderdetailsgrid$x<?= $Grid->RowIndex ?>_OrderID" value="<?= HtmlEncode($Grid->OrderID->FormValue) ?>">
<input type="hidden" data-table="orderdetails" data-field="x_OrderID" data-hidden="1" data-old name="forderdetailsgrid$o<?= $Grid->RowIndex ?>_OrderID" id="forderdetailsgrid$o<?= $Grid->RowIndex ?>_OrderID" value="<?= HtmlEncode($Grid->OrderID->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } else { ?>
            <input type="hidden" data-table="orderdetails" data-field="x_OrderID" data-hidden="1" name="x<?= $Grid->RowIndex ?>_OrderID" id="x<?= $Grid->RowIndex ?>_OrderID" value="<?= HtmlEncode($Grid->OrderID->CurrentValue) ?>">
    <?php } ?>
    <?php if ($Grid->ProductID->Visible) { // ProductID ?>
        <td data-name="ProductID"<?= $Grid->ProductID->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_orderdetails_ProductID" class="el_orderdetails_ProductID">
    <select
        id="x<?= $Grid->RowIndex ?>_ProductID"
        name="x<?= $Grid->RowIndex ?>_ProductID"
        class="form-control ew-select<?= $Grid->ProductID->isInvalidClass() ?>"
        data-select2-id="forderdetailsgrid_x<?= $Grid->RowIndex ?>_ProductID"
        data-table="orderdetails"
        data-field="x_ProductID"
        data-caption="<?= HtmlEncode(RemoveHtml($Grid->ProductID->caption())) ?>"
        data-modal-lookup="true"
        data-value-separator="<?= $Grid->ProductID->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->ProductID->getPlaceHolder()) ?>"
        data-ew-action="autofill"
        <?= $Grid->ProductID->editAttributes() ?>>
        <?= $Grid->ProductID->selectOptionListHtml("x{$Grid->RowIndex}_ProductID") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->ProductID->getErrorMessage() ?></div>
<?= $Grid->ProductID->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_ProductID") ?>
<script>
loadjs.ready("forderdetailsgrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_ProductID", selectId: "forderdetailsgrid_x<?= $Grid->RowIndex ?>_ProductID" };
    if (forderdetailsgrid.lists.ProductID?.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_ProductID", form: "forderdetailsgrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_ProductID", form: "forderdetailsgrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options = Object.assign({}, ew.modalLookupOptions, options, ew.vars.tables.orderdetails.fields.ProductID.modalLookupOptions);
    ew.createModalLookup(options);
});
</script>
</span>
<input type="hidden" data-table="orderdetails" data-field="x_ProductID" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_ProductID" id="o<?= $Grid->RowIndex ?>_ProductID" value="<?= HtmlEncode($Grid->ProductID->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_orderdetails_ProductID" class="el_orderdetails_ProductID">
    <select
        id="x<?= $Grid->RowIndex ?>_ProductID"
        name="x<?= $Grid->RowIndex ?>_ProductID"
        class="form-control ew-select<?= $Grid->ProductID->isInvalidClass() ?>"
        data-select2-id="forderdetailsgrid_x<?= $Grid->RowIndex ?>_ProductID"
        data-table="orderdetails"
        data-field="x_ProductID"
        data-caption="<?= HtmlEncode(RemoveHtml($Grid->ProductID->caption())) ?>"
        data-modal-lookup="true"
        data-value-separator="<?= $Grid->ProductID->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->ProductID->getPlaceHolder()) ?>"
        data-ew-action="autofill"
        <?= $Grid->ProductID->editAttributes() ?>>
        <?= $Grid->ProductID->selectOptionListHtml("x{$Grid->RowIndex}_ProductID") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->ProductID->getErrorMessage() ?></div>
<?= $Grid->ProductID->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_ProductID") ?>
<script>
loadjs.ready("forderdetailsgrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_ProductID", selectId: "forderdetailsgrid_x<?= $Grid->RowIndex ?>_ProductID" };
    if (forderdetailsgrid.lists.ProductID?.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_ProductID", form: "forderdetailsgrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_ProductID", form: "forderdetailsgrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options = Object.assign({}, ew.modalLookupOptions, options, ew.vars.tables.orderdetails.fields.ProductID.modalLookupOptions);
    ew.createModalLookup(options);
});
</script>
<input type="hidden" data-table="orderdetails" data-field="x_ProductID" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_ProductID" id="o<?= $Grid->RowIndex ?>_ProductID" value="<?= HtmlEncode($Grid->ProductID->OldValue ?? $Grid->ProductID->CurrentValue) ?>">
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_orderdetails_ProductID" class="el_orderdetails_ProductID">
<span<?= $Grid->ProductID->viewAttributes() ?>>
<?= $Grid->ProductID->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="orderdetails" data-field="x_ProductID" data-hidden="1" name="forderdetailsgrid$x<?= $Grid->RowIndex ?>_ProductID" id="forderdetailsgrid$x<?= $Grid->RowIndex ?>_ProductID" value="<?= HtmlEncode($Grid->ProductID->FormValue) ?>">
<input type="hidden" data-table="orderdetails" data-field="x_ProductID" data-hidden="1" data-old name="forderdetailsgrid$o<?= $Grid->RowIndex ?>_ProductID" id="forderdetailsgrid$o<?= $Grid->RowIndex ?>_ProductID" value="<?= HtmlEncode($Grid->ProductID->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } else { ?>
            <input type="hidden" data-table="orderdetails" data-field="x_ProductID" data-hidden="1" name="x<?= $Grid->RowIndex ?>_ProductID" id="x<?= $Grid->RowIndex ?>_ProductID" value="<?= HtmlEncode($Grid->ProductID->CurrentValue) ?>">
    <?php } ?>
    <?php if ($Grid->UnitPrice->Visible) { // UnitPrice ?>
        <td data-name="UnitPrice"<?= $Grid->UnitPrice->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_orderdetails_UnitPrice" class="el_orderdetails_UnitPrice">
<input type="<?= $Grid->UnitPrice->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_UnitPrice" id="x<?= $Grid->RowIndex ?>_UnitPrice" data-table="orderdetails" data-field="x_UnitPrice" value="<?= $Grid->UnitPrice->EditValue ?>" size="20" placeholder="<?= HtmlEncode($Grid->UnitPrice->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->UnitPrice->formatPattern()) ?>"<?= $Grid->UnitPrice->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->UnitPrice->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="orderdetails" data-field="x_UnitPrice" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_UnitPrice" id="o<?= $Grid->RowIndex ?>_UnitPrice" value="<?= HtmlEncode($Grid->UnitPrice->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_orderdetails_UnitPrice" class="el_orderdetails_UnitPrice">
<input type="<?= $Grid->UnitPrice->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_UnitPrice" id="x<?= $Grid->RowIndex ?>_UnitPrice" data-table="orderdetails" data-field="x_UnitPrice" value="<?= $Grid->UnitPrice->EditValue ?>" size="20" placeholder="<?= HtmlEncode($Grid->UnitPrice->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->UnitPrice->formatPattern()) ?>"<?= $Grid->UnitPrice->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->UnitPrice->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_orderdetails_UnitPrice" class="el_orderdetails_UnitPrice">
<span<?= $Grid->UnitPrice->viewAttributes() ?>>
<?= $Grid->UnitPrice->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="orderdetails" data-field="x_UnitPrice" data-hidden="1" name="forderdetailsgrid$x<?= $Grid->RowIndex ?>_UnitPrice" id="forderdetailsgrid$x<?= $Grid->RowIndex ?>_UnitPrice" value="<?= HtmlEncode($Grid->UnitPrice->FormValue) ?>">
<input type="hidden" data-table="orderdetails" data-field="x_UnitPrice" data-hidden="1" data-old name="forderdetailsgrid$o<?= $Grid->RowIndex ?>_UnitPrice" id="forderdetailsgrid$o<?= $Grid->RowIndex ?>_UnitPrice" value="<?= HtmlEncode($Grid->UnitPrice->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->Quantity->Visible) { // Quantity ?>
        <td data-name="Quantity"<?= $Grid->Quantity->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_orderdetails_Quantity" class="el_orderdetails_Quantity">
<input type="<?= $Grid->Quantity->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_Quantity" id="x<?= $Grid->RowIndex ?>_Quantity" data-table="orderdetails" data-field="x_Quantity" value="<?= $Grid->Quantity->EditValue ?>" size="20" placeholder="<?= HtmlEncode($Grid->Quantity->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->Quantity->formatPattern()) ?>"<?= $Grid->Quantity->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->Quantity->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="orderdetails" data-field="x_Quantity" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_Quantity" id="o<?= $Grid->RowIndex ?>_Quantity" value="<?= HtmlEncode($Grid->Quantity->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_orderdetails_Quantity" class="el_orderdetails_Quantity">
<input type="<?= $Grid->Quantity->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_Quantity" id="x<?= $Grid->RowIndex ?>_Quantity" data-table="orderdetails" data-field="x_Quantity" value="<?= $Grid->Quantity->EditValue ?>" size="20" placeholder="<?= HtmlEncode($Grid->Quantity->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->Quantity->formatPattern()) ?>"<?= $Grid->Quantity->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->Quantity->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_orderdetails_Quantity" class="el_orderdetails_Quantity">
<span<?= $Grid->Quantity->viewAttributes() ?>>
<?= $Grid->Quantity->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="orderdetails" data-field="x_Quantity" data-hidden="1" name="forderdetailsgrid$x<?= $Grid->RowIndex ?>_Quantity" id="forderdetailsgrid$x<?= $Grid->RowIndex ?>_Quantity" value="<?= HtmlEncode($Grid->Quantity->FormValue) ?>">
<input type="hidden" data-table="orderdetails" data-field="x_Quantity" data-hidden="1" data-old name="forderdetailsgrid$o<?= $Grid->RowIndex ?>_Quantity" id="forderdetailsgrid$o<?= $Grid->RowIndex ?>_Quantity" value="<?= HtmlEncode($Grid->Quantity->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->Discount->Visible) { // Discount ?>
        <td data-name="Discount"<?= $Grid->Discount->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_orderdetails_Discount" class="el_orderdetails_Discount">
<input type="<?= $Grid->Discount->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_Discount" id="x<?= $Grid->RowIndex ?>_Discount" data-table="orderdetails" data-field="x_Discount" value="<?= $Grid->Discount->EditValue ?>" size="20" placeholder="<?= HtmlEncode($Grid->Discount->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->Discount->formatPattern()) ?>"<?= $Grid->Discount->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->Discount->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="orderdetails" data-field="x_Discount" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_Discount" id="o<?= $Grid->RowIndex ?>_Discount" value="<?= HtmlEncode($Grid->Discount->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_orderdetails_Discount" class="el_orderdetails_Discount">
<input type="<?= $Grid->Discount->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_Discount" id="x<?= $Grid->RowIndex ?>_Discount" data-table="orderdetails" data-field="x_Discount" value="<?= $Grid->Discount->EditValue ?>" size="20" placeholder="<?= HtmlEncode($Grid->Discount->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->Discount->formatPattern()) ?>"<?= $Grid->Discount->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->Discount->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_orderdetails_Discount" class="el_orderdetails_Discount">
<span<?= $Grid->Discount->viewAttributes() ?>>
<?= $Grid->Discount->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="orderdetails" data-field="x_Discount" data-hidden="1" name="forderdetailsgrid$x<?= $Grid->RowIndex ?>_Discount" id="forderdetailsgrid$x<?= $Grid->RowIndex ?>_Discount" value="<?= HtmlEncode($Grid->Discount->FormValue) ?>">
<input type="hidden" data-table="orderdetails" data-field="x_Discount" data-hidden="1" data-old name="forderdetailsgrid$o<?= $Grid->RowIndex ?>_Discount" id="forderdetailsgrid$o<?= $Grid->RowIndex ?>_Discount" value="<?= HtmlEncode($Grid->Discount->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->SubTotal->Visible) { // SubTotal ?>
        <td data-name="SubTotal"<?= $Grid->SubTotal->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_orderdetails_SubTotal" class="el_orderdetails_SubTotal">
<input type="<?= $Grid->SubTotal->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_SubTotal" id="x<?= $Grid->RowIndex ?>_SubTotal" data-table="orderdetails" data-field="x_SubTotal" value="<?= $Grid->SubTotal->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->SubTotal->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->SubTotal->formatPattern()) ?>"<?= $Grid->SubTotal->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->SubTotal->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="orderdetails" data-field="x_SubTotal" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_SubTotal" id="o<?= $Grid->RowIndex ?>_SubTotal" value="<?= HtmlEncode($Grid->SubTotal->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_orderdetails_SubTotal" class="el_orderdetails_SubTotal">
<input type="<?= $Grid->SubTotal->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_SubTotal" id="x<?= $Grid->RowIndex ?>_SubTotal" data-table="orderdetails" data-field="x_SubTotal" value="<?= $Grid->SubTotal->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->SubTotal->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->SubTotal->formatPattern()) ?>"<?= $Grid->SubTotal->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->SubTotal->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_orderdetails_SubTotal" class="el_orderdetails_SubTotal">
<span<?= $Grid->SubTotal->viewAttributes() ?>>
<?= $Grid->SubTotal->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="orderdetails" data-field="x_SubTotal" data-hidden="1" name="forderdetailsgrid$x<?= $Grid->RowIndex ?>_SubTotal" id="forderdetailsgrid$x<?= $Grid->RowIndex ?>_SubTotal" value="<?= HtmlEncode($Grid->SubTotal->FormValue) ?>">
<input type="hidden" data-table="orderdetails" data-field="x_SubTotal" data-hidden="1" data-old name="forderdetailsgrid$o<?= $Grid->RowIndex ?>_SubTotal" id="forderdetailsgrid$o<?= $Grid->RowIndex ?>_SubTotal" value="<?= HtmlEncode($Grid->SubTotal->OldValue) ?>">
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
loadjs.ready(["forderdetailsgrid","load"], () => forderdetailsgrid.updateLists(<?= $Grid->RowIndex ?><?= $Grid->isAdd() || $Grid->isEdit() || $Grid->isCopy() || $Grid->RowIndex === '$rowindex$' ? ", true" : "" ?>));
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
<input type="hidden" name="detailpage" value="forderdetailsgrid">
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
    ew.addEventHandlers("orderdetails");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
