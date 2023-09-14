<?php

namespace PHPMaker2023\demo2023;

// Set up and run Grid object
$Grid = Container("OrderDetailsExtendedGrid");
$Grid->run();
?>
<?php if (!$Grid->isExport()) { ?>
<script>
var forder_details_extendedgrid;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let currentTable = <?= JsonEncode($Grid->toClientVar()) ?>;
    ew.deepAssign(ew.vars, { tables: { order_details_extended: currentTable } });
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("forder_details_extendedgrid")
        .setPageId("grid")
        .setFormKeyCountName("<?= $Grid->FormKeyCountName ?>")

        // Add fields
        .setFields([
            ["CompanyName", [fields.CompanyName.visible && fields.CompanyName.required ? ew.Validators.required(fields.CompanyName.caption) : null], fields.CompanyName.isInvalid],
            ["OrderID", [fields.OrderID.visible && fields.OrderID.required ? ew.Validators.required(fields.OrderID.caption) : null], fields.OrderID.isInvalid],
            ["ProductName", [fields.ProductName.visible && fields.ProductName.required ? ew.Validators.required(fields.ProductName.caption) : null], fields.ProductName.isInvalid],
            ["UnitPrice", [fields.UnitPrice.visible && fields.UnitPrice.required ? ew.Validators.required(fields.UnitPrice.caption) : null, ew.Validators.float], fields.UnitPrice.isInvalid],
            ["Quantity", [fields.Quantity.visible && fields.Quantity.required ? ew.Validators.required(fields.Quantity.caption) : null, ew.Validators.integer], fields.Quantity.isInvalid],
            ["Discount", [fields.Discount.visible && fields.Discount.required ? ew.Validators.required(fields.Discount.caption) : null, ew.Validators.float], fields.Discount.isInvalid],
            ["ExtendedPrice", [fields.ExtendedPrice.visible && fields.ExtendedPrice.required ? ew.Validators.required(fields.ExtendedPrice.caption) : null, ew.Validators.float], fields.ExtendedPrice.isInvalid]
        ])

        // Check empty row
        .setEmptyRow(
            function (rowIndex) {
                let fobj = this.getForm(),
                    fields = [["CompanyName",false],["ProductName",false],["UnitPrice",false],["Quantity",false],["Discount",false],["ExtendedPrice",false]];
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
<div id="forder_details_extendedgrid" class="ew-form ew-list-form">
<div id="gmp_order_details_extended" class="card-body ew-grid-middle-panel <?= $Grid->TableContainerClass ?>" style="<?= $Grid->TableContainerStyle ?>">
<table id="tbl_order_details_extendedgrid" class="<?= $Grid->TableClass ?>"><!-- .ew-table -->
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
<?php if ($Grid->CompanyName->Visible) { // CompanyName ?>
        <th data-name="CompanyName" class="<?= $Grid->CompanyName->headerCellClass() ?>"><div id="elh_order_details_extended_CompanyName" class="order_details_extended_CompanyName"><?= $Grid->renderFieldHeader($Grid->CompanyName) ?></div></th>
<?php } ?>
<?php if ($Grid->OrderID->Visible) { // OrderID ?>
        <th data-name="OrderID" class="<?= $Grid->OrderID->headerCellClass() ?>"><div id="elh_order_details_extended_OrderID" class="order_details_extended_OrderID"><?= $Grid->renderFieldHeader($Grid->OrderID) ?></div></th>
<?php } ?>
<?php if ($Grid->ProductName->Visible) { // ProductName ?>
        <th data-name="ProductName" class="<?= $Grid->ProductName->headerCellClass() ?>"><div id="elh_order_details_extended_ProductName" class="order_details_extended_ProductName"><?= $Grid->renderFieldHeader($Grid->ProductName) ?></div></th>
<?php } ?>
<?php if ($Grid->UnitPrice->Visible) { // UnitPrice ?>
        <th data-name="UnitPrice" class="<?= $Grid->UnitPrice->headerCellClass() ?>"><div id="elh_order_details_extended_UnitPrice" class="order_details_extended_UnitPrice"><?= $Grid->renderFieldHeader($Grid->UnitPrice) ?></div></th>
<?php } ?>
<?php if ($Grid->Quantity->Visible) { // Quantity ?>
        <th data-name="Quantity" class="<?= $Grid->Quantity->headerCellClass() ?>"><div id="elh_order_details_extended_Quantity" class="order_details_extended_Quantity"><?= $Grid->renderFieldHeader($Grid->Quantity) ?></div></th>
<?php } ?>
<?php if ($Grid->Discount->Visible) { // Discount ?>
        <th data-name="Discount" class="<?= $Grid->Discount->headerCellClass() ?>"><div id="elh_order_details_extended_Discount" class="order_details_extended_Discount"><?= $Grid->renderFieldHeader($Grid->Discount) ?></div></th>
<?php } ?>
<?php if ($Grid->ExtendedPrice->Visible) { // Extended Price ?>
        <th data-name="ExtendedPrice" class="<?= $Grid->ExtendedPrice->headerCellClass() ?>"><div id="elh_order_details_extended_ExtendedPrice" class="order_details_extended_ExtendedPrice"><?= $Grid->renderFieldHeader($Grid->ExtendedPrice) ?></div></th>
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
    <?php if ($Grid->CompanyName->Visible) { // CompanyName ?>
        <td data-name="CompanyName"<?= $Grid->CompanyName->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_order_details_extended_CompanyName" class="el_order_details_extended_CompanyName">
<input type="<?= $Grid->CompanyName->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_CompanyName" id="x<?= $Grid->RowIndex ?>_CompanyName" data-table="order_details_extended" data-field="x_CompanyName" value="<?= $Grid->CompanyName->EditValue ?>" size="30" maxlength="40" placeholder="<?= HtmlEncode($Grid->CompanyName->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->CompanyName->formatPattern()) ?>"<?= $Grid->CompanyName->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->CompanyName->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="order_details_extended" data-field="x_CompanyName" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_CompanyName" id="o<?= $Grid->RowIndex ?>_CompanyName" value="<?= HtmlEncode($Grid->CompanyName->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_order_details_extended_CompanyName" class="el_order_details_extended_CompanyName">
<input type="<?= $Grid->CompanyName->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_CompanyName" id="x<?= $Grid->RowIndex ?>_CompanyName" data-table="order_details_extended" data-field="x_CompanyName" value="<?= $Grid->CompanyName->EditValue ?>" size="30" maxlength="40" placeholder="<?= HtmlEncode($Grid->CompanyName->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->CompanyName->formatPattern()) ?>"<?= $Grid->CompanyName->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->CompanyName->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_order_details_extended_CompanyName" class="el_order_details_extended_CompanyName">
<span<?= $Grid->CompanyName->viewAttributes() ?>>
<?= $Grid->CompanyName->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="order_details_extended" data-field="x_CompanyName" data-hidden="1" name="forder_details_extendedgrid$x<?= $Grid->RowIndex ?>_CompanyName" id="forder_details_extendedgrid$x<?= $Grid->RowIndex ?>_CompanyName" value="<?= HtmlEncode($Grid->CompanyName->FormValue) ?>">
<input type="hidden" data-table="order_details_extended" data-field="x_CompanyName" data-hidden="1" data-old name="forder_details_extendedgrid$o<?= $Grid->RowIndex ?>_CompanyName" id="forder_details_extendedgrid$o<?= $Grid->RowIndex ?>_CompanyName" value="<?= HtmlEncode($Grid->CompanyName->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->OrderID->Visible) { // OrderID ?>
        <td data-name="OrderID"<?= $Grid->OrderID->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_order_details_extended_OrderID" class="el_order_details_extended_OrderID"></span>
<input type="hidden" data-table="order_details_extended" data-field="x_OrderID" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_OrderID" id="o<?= $Grid->RowIndex ?>_OrderID" value="<?= HtmlEncode($Grid->OrderID->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_order_details_extended_OrderID" class="el_order_details_extended_OrderID">
<span<?= $Grid->OrderID->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->OrderID->getDisplayValue($Grid->OrderID->EditValue))) ?>"></span>
<input type="hidden" data-table="order_details_extended" data-field="x_OrderID" data-hidden="1" name="x<?= $Grid->RowIndex ?>_OrderID" id="x<?= $Grid->RowIndex ?>_OrderID" value="<?= HtmlEncode($Grid->OrderID->CurrentValue) ?>">
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_order_details_extended_OrderID" class="el_order_details_extended_OrderID">
<span<?= $Grid->OrderID->viewAttributes() ?>>
<?= $Grid->OrderID->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="order_details_extended" data-field="x_OrderID" data-hidden="1" name="forder_details_extendedgrid$x<?= $Grid->RowIndex ?>_OrderID" id="forder_details_extendedgrid$x<?= $Grid->RowIndex ?>_OrderID" value="<?= HtmlEncode($Grid->OrderID->FormValue) ?>">
<input type="hidden" data-table="order_details_extended" data-field="x_OrderID" data-hidden="1" data-old name="forder_details_extendedgrid$o<?= $Grid->RowIndex ?>_OrderID" id="forder_details_extendedgrid$o<?= $Grid->RowIndex ?>_OrderID" value="<?= HtmlEncode($Grid->OrderID->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } else { ?>
            <input type="hidden" data-table="order_details_extended" data-field="x_OrderID" data-hidden="1" name="x<?= $Grid->RowIndex ?>_OrderID" id="x<?= $Grid->RowIndex ?>_OrderID" value="<?= HtmlEncode($Grid->OrderID->CurrentValue) ?>">
    <?php } ?>
    <?php if ($Grid->ProductName->Visible) { // ProductName ?>
        <td data-name="ProductName"<?= $Grid->ProductName->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_order_details_extended_ProductName" class="el_order_details_extended_ProductName">
<input type="<?= $Grid->ProductName->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_ProductName" id="x<?= $Grid->RowIndex ?>_ProductName" data-table="order_details_extended" data-field="x_ProductName" value="<?= $Grid->ProductName->EditValue ?>" size="30" maxlength="40" placeholder="<?= HtmlEncode($Grid->ProductName->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->ProductName->formatPattern()) ?>"<?= $Grid->ProductName->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->ProductName->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="order_details_extended" data-field="x_ProductName" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_ProductName" id="o<?= $Grid->RowIndex ?>_ProductName" value="<?= HtmlEncode($Grid->ProductName->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_order_details_extended_ProductName" class="el_order_details_extended_ProductName">
<input type="<?= $Grid->ProductName->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_ProductName" id="x<?= $Grid->RowIndex ?>_ProductName" data-table="order_details_extended" data-field="x_ProductName" value="<?= $Grid->ProductName->EditValue ?>" size="30" maxlength="40" placeholder="<?= HtmlEncode($Grid->ProductName->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->ProductName->formatPattern()) ?>"<?= $Grid->ProductName->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->ProductName->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_order_details_extended_ProductName" class="el_order_details_extended_ProductName">
<span<?= $Grid->ProductName->viewAttributes() ?>>
<?= $Grid->ProductName->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="order_details_extended" data-field="x_ProductName" data-hidden="1" name="forder_details_extendedgrid$x<?= $Grid->RowIndex ?>_ProductName" id="forder_details_extendedgrid$x<?= $Grid->RowIndex ?>_ProductName" value="<?= HtmlEncode($Grid->ProductName->FormValue) ?>">
<input type="hidden" data-table="order_details_extended" data-field="x_ProductName" data-hidden="1" data-old name="forder_details_extendedgrid$o<?= $Grid->RowIndex ?>_ProductName" id="forder_details_extendedgrid$o<?= $Grid->RowIndex ?>_ProductName" value="<?= HtmlEncode($Grid->ProductName->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->UnitPrice->Visible) { // UnitPrice ?>
        <td data-name="UnitPrice"<?= $Grid->UnitPrice->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_order_details_extended_UnitPrice" class="el_order_details_extended_UnitPrice">
<input type="<?= $Grid->UnitPrice->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_UnitPrice" id="x<?= $Grid->RowIndex ?>_UnitPrice" data-table="order_details_extended" data-field="x_UnitPrice" value="<?= $Grid->UnitPrice->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->UnitPrice->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->UnitPrice->formatPattern()) ?>"<?= $Grid->UnitPrice->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->UnitPrice->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="order_details_extended" data-field="x_UnitPrice" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_UnitPrice" id="o<?= $Grid->RowIndex ?>_UnitPrice" value="<?= HtmlEncode($Grid->UnitPrice->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_order_details_extended_UnitPrice" class="el_order_details_extended_UnitPrice">
<input type="<?= $Grid->UnitPrice->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_UnitPrice" id="x<?= $Grid->RowIndex ?>_UnitPrice" data-table="order_details_extended" data-field="x_UnitPrice" value="<?= $Grid->UnitPrice->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->UnitPrice->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->UnitPrice->formatPattern()) ?>"<?= $Grid->UnitPrice->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->UnitPrice->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_order_details_extended_UnitPrice" class="el_order_details_extended_UnitPrice">
<span<?= $Grid->UnitPrice->viewAttributes() ?>>
<?= $Grid->UnitPrice->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="order_details_extended" data-field="x_UnitPrice" data-hidden="1" name="forder_details_extendedgrid$x<?= $Grid->RowIndex ?>_UnitPrice" id="forder_details_extendedgrid$x<?= $Grid->RowIndex ?>_UnitPrice" value="<?= HtmlEncode($Grid->UnitPrice->FormValue) ?>">
<input type="hidden" data-table="order_details_extended" data-field="x_UnitPrice" data-hidden="1" data-old name="forder_details_extendedgrid$o<?= $Grid->RowIndex ?>_UnitPrice" id="forder_details_extendedgrid$o<?= $Grid->RowIndex ?>_UnitPrice" value="<?= HtmlEncode($Grid->UnitPrice->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->Quantity->Visible) { // Quantity ?>
        <td data-name="Quantity"<?= $Grid->Quantity->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_order_details_extended_Quantity" class="el_order_details_extended_Quantity">
<input type="<?= $Grid->Quantity->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_Quantity" id="x<?= $Grid->RowIndex ?>_Quantity" data-table="order_details_extended" data-field="x_Quantity" value="<?= $Grid->Quantity->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->Quantity->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->Quantity->formatPattern()) ?>"<?= $Grid->Quantity->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->Quantity->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="order_details_extended" data-field="x_Quantity" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_Quantity" id="o<?= $Grid->RowIndex ?>_Quantity" value="<?= HtmlEncode($Grid->Quantity->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_order_details_extended_Quantity" class="el_order_details_extended_Quantity">
<input type="<?= $Grid->Quantity->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_Quantity" id="x<?= $Grid->RowIndex ?>_Quantity" data-table="order_details_extended" data-field="x_Quantity" value="<?= $Grid->Quantity->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->Quantity->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->Quantity->formatPattern()) ?>"<?= $Grid->Quantity->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->Quantity->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_order_details_extended_Quantity" class="el_order_details_extended_Quantity">
<span<?= $Grid->Quantity->viewAttributes() ?>>
<?= $Grid->Quantity->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="order_details_extended" data-field="x_Quantity" data-hidden="1" name="forder_details_extendedgrid$x<?= $Grid->RowIndex ?>_Quantity" id="forder_details_extendedgrid$x<?= $Grid->RowIndex ?>_Quantity" value="<?= HtmlEncode($Grid->Quantity->FormValue) ?>">
<input type="hidden" data-table="order_details_extended" data-field="x_Quantity" data-hidden="1" data-old name="forder_details_extendedgrid$o<?= $Grid->RowIndex ?>_Quantity" id="forder_details_extendedgrid$o<?= $Grid->RowIndex ?>_Quantity" value="<?= HtmlEncode($Grid->Quantity->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->Discount->Visible) { // Discount ?>
        <td data-name="Discount"<?= $Grid->Discount->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_order_details_extended_Discount" class="el_order_details_extended_Discount">
<input type="<?= $Grid->Discount->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_Discount" id="x<?= $Grid->RowIndex ?>_Discount" data-table="order_details_extended" data-field="x_Discount" value="<?= $Grid->Discount->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->Discount->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->Discount->formatPattern()) ?>"<?= $Grid->Discount->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->Discount->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="order_details_extended" data-field="x_Discount" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_Discount" id="o<?= $Grid->RowIndex ?>_Discount" value="<?= HtmlEncode($Grid->Discount->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_order_details_extended_Discount" class="el_order_details_extended_Discount">
<input type="<?= $Grid->Discount->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_Discount" id="x<?= $Grid->RowIndex ?>_Discount" data-table="order_details_extended" data-field="x_Discount" value="<?= $Grid->Discount->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->Discount->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->Discount->formatPattern()) ?>"<?= $Grid->Discount->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->Discount->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_order_details_extended_Discount" class="el_order_details_extended_Discount">
<span<?= $Grid->Discount->viewAttributes() ?>>
<?= $Grid->Discount->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="order_details_extended" data-field="x_Discount" data-hidden="1" name="forder_details_extendedgrid$x<?= $Grid->RowIndex ?>_Discount" id="forder_details_extendedgrid$x<?= $Grid->RowIndex ?>_Discount" value="<?= HtmlEncode($Grid->Discount->FormValue) ?>">
<input type="hidden" data-table="order_details_extended" data-field="x_Discount" data-hidden="1" data-old name="forder_details_extendedgrid$o<?= $Grid->RowIndex ?>_Discount" id="forder_details_extendedgrid$o<?= $Grid->RowIndex ?>_Discount" value="<?= HtmlEncode($Grid->Discount->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->ExtendedPrice->Visible) { // Extended Price ?>
        <td data-name="ExtendedPrice"<?= $Grid->ExtendedPrice->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_order_details_extended_ExtendedPrice" class="el_order_details_extended_ExtendedPrice">
<input type="<?= $Grid->ExtendedPrice->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_ExtendedPrice" id="x<?= $Grid->RowIndex ?>_ExtendedPrice" data-table="order_details_extended" data-field="x_ExtendedPrice" value="<?= $Grid->ExtendedPrice->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->ExtendedPrice->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->ExtendedPrice->formatPattern()) ?>"<?= $Grid->ExtendedPrice->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->ExtendedPrice->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="order_details_extended" data-field="x_ExtendedPrice" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_ExtendedPrice" id="o<?= $Grid->RowIndex ?>_ExtendedPrice" value="<?= HtmlEncode($Grid->ExtendedPrice->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_order_details_extended_ExtendedPrice" class="el_order_details_extended_ExtendedPrice">
<input type="<?= $Grid->ExtendedPrice->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_ExtendedPrice" id="x<?= $Grid->RowIndex ?>_ExtendedPrice" data-table="order_details_extended" data-field="x_ExtendedPrice" value="<?= $Grid->ExtendedPrice->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->ExtendedPrice->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->ExtendedPrice->formatPattern()) ?>"<?= $Grid->ExtendedPrice->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->ExtendedPrice->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_order_details_extended_ExtendedPrice" class="el_order_details_extended_ExtendedPrice">
<span<?= $Grid->ExtendedPrice->viewAttributes() ?>>
<?= $Grid->ExtendedPrice->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="order_details_extended" data-field="x_ExtendedPrice" data-hidden="1" name="forder_details_extendedgrid$x<?= $Grid->RowIndex ?>_ExtendedPrice" id="forder_details_extendedgrid$x<?= $Grid->RowIndex ?>_ExtendedPrice" value="<?= HtmlEncode($Grid->ExtendedPrice->FormValue) ?>">
<input type="hidden" data-table="order_details_extended" data-field="x_ExtendedPrice" data-hidden="1" data-old name="forder_details_extendedgrid$o<?= $Grid->RowIndex ?>_ExtendedPrice" id="forder_details_extendedgrid$o<?= $Grid->RowIndex ?>_ExtendedPrice" value="<?= HtmlEncode($Grid->ExtendedPrice->OldValue) ?>">
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
loadjs.ready(["forder_details_extendedgrid","load"], () => forder_details_extendedgrid.updateLists(<?= $Grid->RowIndex ?><?= $Grid->isAdd() || $Grid->isEdit() || $Grid->isCopy() || $Grid->RowIndex === '$rowindex$' ? ", true" : "" ?>));
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
<?php
// Render aggregate row
$Grid->RowType = ROWTYPE_AGGREGATE;
$Grid->resetAttributes();
$Grid->renderRow();
?>
<?php if ($Grid->TotalRecords > 0 && $Grid->CurrentMode == "view") { ?>
<tfoot><!-- Table footer -->
    <tr class="ew-table-footer">
<?php
// Render list options
$Grid->renderListOptions();

// Render list options (footer, left)
$Grid->ListOptions->render("footer", "left");
?>
    <?php if ($Grid->CompanyName->Visible) { // CompanyName ?>
        <td data-name="CompanyName" class="<?= $Grid->CompanyName->footerCellClass() ?>"><span id="elf_order_details_extended_CompanyName" class="order_details_extended_CompanyName">
        </span></td>
    <?php } ?>
    <?php if ($Grid->OrderID->Visible) { // OrderID ?>
        <td data-name="OrderID" class="<?= $Grid->OrderID->footerCellClass() ?>"><span id="elf_order_details_extended_OrderID" class="order_details_extended_OrderID">
        </span></td>
    <?php } ?>
    <?php if ($Grid->ProductName->Visible) { // ProductName ?>
        <td data-name="ProductName" class="<?= $Grid->ProductName->footerCellClass() ?>"><span id="elf_order_details_extended_ProductName" class="order_details_extended_ProductName">
        </span></td>
    <?php } ?>
    <?php if ($Grid->UnitPrice->Visible) { // UnitPrice ?>
        <td data-name="UnitPrice" class="<?= $Grid->UnitPrice->footerCellClass() ?>"><span id="elf_order_details_extended_UnitPrice" class="order_details_extended_UnitPrice">
        </span></td>
    <?php } ?>
    <?php if ($Grid->Quantity->Visible) { // Quantity ?>
        <td data-name="Quantity" class="<?= $Grid->Quantity->footerCellClass() ?>"><span id="elf_order_details_extended_Quantity" class="order_details_extended_Quantity">
        <span class="ew-aggregate"><?= $Language->phrase("TOTAL") ?></span><span class="ew-aggregate-value">
        <?= $Grid->Quantity->ViewValue ?></span>
        </span></td>
    <?php } ?>
    <?php if ($Grid->Discount->Visible) { // Discount ?>
        <td data-name="Discount" class="<?= $Grid->Discount->footerCellClass() ?>"><span id="elf_order_details_extended_Discount" class="order_details_extended_Discount">
        </span></td>
    <?php } ?>
    <?php if ($Grid->ExtendedPrice->Visible) { // Extended Price ?>
        <td data-name="ExtendedPrice" class="<?= $Grid->ExtendedPrice->footerCellClass() ?>"><span id="elf_order_details_extended_ExtendedPrice" class="order_details_extended_ExtendedPrice">
        <span class="ew-aggregate"><?= $Language->phrase("TOTAL") ?></span><span class="ew-aggregate-value">
        <?= $Grid->ExtendedPrice->ViewValue ?></span>
        </span></td>
    <?php } ?>
<?php
// Render list options (footer, right)
$Grid->ListOptions->render("footer", "right");
?>
    </tr>
</tfoot>
<?php } ?>
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
<input type="hidden" name="detailpage" value="forder_details_extendedgrid">
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
    ew.addEventHandlers("order_details_extended");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
