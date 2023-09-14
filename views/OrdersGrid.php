<?php

namespace PHPMaker2023\demo2023;

// Set up and run Grid object
$Grid = Container("OrdersGrid");
$Grid->run();
?>
<?php if (!$Grid->isExport()) { ?>
<script>
var fordersgrid;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let currentTable = <?= JsonEncode($Grid->toClientVar()) ?>;
    ew.deepAssign(ew.vars, { tables: { orders: currentTable } });
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fordersgrid")
        .setPageId("grid")
        .setFormKeyCountName("<?= $Grid->FormKeyCountName ?>")

        // Add fields
        .setFields([
            ["OrderID", [fields.OrderID.visible && fields.OrderID.required ? ew.Validators.required(fields.OrderID.caption) : null], fields.OrderID.isInvalid],
            ["CustomerID", [fields.CustomerID.visible && fields.CustomerID.required ? ew.Validators.required(fields.CustomerID.caption) : null], fields.CustomerID.isInvalid],
            ["EmployeeID", [fields.EmployeeID.visible && fields.EmployeeID.required ? ew.Validators.required(fields.EmployeeID.caption) : null], fields.EmployeeID.isInvalid],
            ["OrderDate", [fields.OrderDate.visible && fields.OrderDate.required ? ew.Validators.required(fields.OrderDate.caption) : null, ew.Validators.datetime(fields.OrderDate.clientFormatPattern)], fields.OrderDate.isInvalid]
        ])

        // Check empty row
        .setEmptyRow(
            function (rowIndex) {
                let fobj = this.getForm(),
                    fields = [["CustomerID",false],["EmployeeID",false],["OrderDate",false]];
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
            "CustomerID": <?= $Grid->CustomerID->toClientList($Grid) ?>,
            "EmployeeID": <?= $Grid->EmployeeID->toClientList($Grid) ?>,
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
<div id="fordersgrid" class="ew-form ew-list-form">
<div id="gmp_orders" class="card-body ew-grid-middle-panel <?= $Grid->TableContainerClass ?>" style="<?= $Grid->TableContainerStyle ?>">
<table id="tbl_ordersgrid" class="<?= $Grid->TableClass ?>"><!-- .ew-table -->
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
        <th data-name="OrderID" class="<?= $Grid->OrderID->headerCellClass() ?>"><div id="elh_orders_OrderID" class="orders_OrderID"><?= $Grid->renderFieldHeader($Grid->OrderID) ?></div></th>
<?php } ?>
<?php if ($Grid->CustomerID->Visible) { // CustomerID ?>
        <th data-name="CustomerID" class="<?= $Grid->CustomerID->headerCellClass() ?>"><div id="elh_orders_CustomerID" class="orders_CustomerID"><?= $Grid->renderFieldHeader($Grid->CustomerID) ?></div></th>
<?php } ?>
<?php if ($Grid->EmployeeID->Visible) { // EmployeeID ?>
        <th data-name="EmployeeID" class="<?= $Grid->EmployeeID->headerCellClass() ?>"><div id="elh_orders_EmployeeID" class="orders_EmployeeID"><?= $Grid->renderFieldHeader($Grid->EmployeeID) ?></div></th>
<?php } ?>
<?php if ($Grid->OrderDate->Visible) { // OrderDate ?>
        <th data-name="OrderDate" class="<?= $Grid->OrderDate->headerCellClass() ?>"><div id="elh_orders_OrderDate" class="orders_OrderDate"><?= $Grid->renderFieldHeader($Grid->OrderDate) ?></div></th>
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
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_orders_OrderID" class="el_orders_OrderID"></span>
<input type="hidden" data-table="orders" data-field="x_OrderID" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_OrderID" id="o<?= $Grid->RowIndex ?>_OrderID" value="<?= HtmlEncode($Grid->OrderID->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_orders_OrderID" class="el_orders_OrderID">
<span<?= $Grid->OrderID->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->OrderID->getDisplayValue($Grid->OrderID->EditValue))) ?>"></span>
<input type="hidden" data-table="orders" data-field="x_OrderID" data-hidden="1" name="x<?= $Grid->RowIndex ?>_OrderID" id="x<?= $Grid->RowIndex ?>_OrderID" value="<?= HtmlEncode($Grid->OrderID->CurrentValue) ?>">
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_orders_OrderID" class="el_orders_OrderID">
<span<?= $Grid->OrderID->viewAttributes() ?>>
<?= $Grid->OrderID->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="orders" data-field="x_OrderID" data-hidden="1" name="fordersgrid$x<?= $Grid->RowIndex ?>_OrderID" id="fordersgrid$x<?= $Grid->RowIndex ?>_OrderID" value="<?= HtmlEncode($Grid->OrderID->FormValue) ?>">
<input type="hidden" data-table="orders" data-field="x_OrderID" data-hidden="1" data-old name="fordersgrid$o<?= $Grid->RowIndex ?>_OrderID" id="fordersgrid$o<?= $Grid->RowIndex ?>_OrderID" value="<?= HtmlEncode($Grid->OrderID->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } else { ?>
            <input type="hidden" data-table="orders" data-field="x_OrderID" data-hidden="1" name="x<?= $Grid->RowIndex ?>_OrderID" id="x<?= $Grid->RowIndex ?>_OrderID" value="<?= HtmlEncode($Grid->OrderID->CurrentValue) ?>">
    <?php } ?>
    <?php if ($Grid->CustomerID->Visible) { // CustomerID ?>
        <td data-name="CustomerID"<?= $Grid->CustomerID->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<?php if ($Grid->CustomerID->getSessionValue() != "") { ?>
<span<?= $Grid->CustomerID->viewAttributes() ?>>
<span class="form-control-plaintext"><?= $Grid->CustomerID->getDisplayValue($Grid->CustomerID->ViewValue) ?></span></span>
<input type="hidden" id="x<?= $Grid->RowIndex ?>_CustomerID" name="x<?= $Grid->RowIndex ?>_CustomerID" value="<?= HtmlEncode($Grid->CustomerID->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_orders_CustomerID" class="el_orders_CustomerID">
    <select
        id="x<?= $Grid->RowIndex ?>_CustomerID"
        name="x<?= $Grid->RowIndex ?>_CustomerID"
        class="form-control ew-select<?= $Grid->CustomerID->isInvalidClass() ?>"
        data-select2-id="fordersgrid_x<?= $Grid->RowIndex ?>_CustomerID"
        data-table="orders"
        data-field="x_CustomerID"
        data-caption="<?= HtmlEncode(RemoveHtml($Grid->CustomerID->caption())) ?>"
        data-modal-lookup="true"
        data-value-separator="<?= $Grid->CustomerID->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->CustomerID->getPlaceHolder()) ?>"
        <?= $Grid->CustomerID->editAttributes() ?>>
        <?= $Grid->CustomerID->selectOptionListHtml("x{$Grid->RowIndex}_CustomerID") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->CustomerID->getErrorMessage() ?></div>
<?= $Grid->CustomerID->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_CustomerID") ?>
<script>
loadjs.ready("fordersgrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_CustomerID", selectId: "fordersgrid_x<?= $Grid->RowIndex ?>_CustomerID" };
    if (fordersgrid.lists.CustomerID?.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_CustomerID", form: "fordersgrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_CustomerID", form: "fordersgrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options = Object.assign({}, ew.modalLookupOptions, options, ew.vars.tables.orders.fields.CustomerID.modalLookupOptions);
    ew.createModalLookup(options);
});
</script>
</span>
<?php } ?>
<input type="hidden" data-table="orders" data-field="x_CustomerID" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_CustomerID" id="o<?= $Grid->RowIndex ?>_CustomerID" value="<?= HtmlEncode($Grid->CustomerID->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<?php if ($Grid->CustomerID->getSessionValue() != "") { ?>
<span<?= $Grid->CustomerID->viewAttributes() ?>>
<span class="form-control-plaintext"><?= $Grid->CustomerID->getDisplayValue($Grid->CustomerID->ViewValue) ?></span></span>
<input type="hidden" id="x<?= $Grid->RowIndex ?>_CustomerID" name="x<?= $Grid->RowIndex ?>_CustomerID" value="<?= HtmlEncode($Grid->CustomerID->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_orders_CustomerID" class="el_orders_CustomerID">
    <select
        id="x<?= $Grid->RowIndex ?>_CustomerID"
        name="x<?= $Grid->RowIndex ?>_CustomerID"
        class="form-control ew-select<?= $Grid->CustomerID->isInvalidClass() ?>"
        data-select2-id="fordersgrid_x<?= $Grid->RowIndex ?>_CustomerID"
        data-table="orders"
        data-field="x_CustomerID"
        data-caption="<?= HtmlEncode(RemoveHtml($Grid->CustomerID->caption())) ?>"
        data-modal-lookup="true"
        data-value-separator="<?= $Grid->CustomerID->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->CustomerID->getPlaceHolder()) ?>"
        <?= $Grid->CustomerID->editAttributes() ?>>
        <?= $Grid->CustomerID->selectOptionListHtml("x{$Grid->RowIndex}_CustomerID") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->CustomerID->getErrorMessage() ?></div>
<?= $Grid->CustomerID->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_CustomerID") ?>
<script>
loadjs.ready("fordersgrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_CustomerID", selectId: "fordersgrid_x<?= $Grid->RowIndex ?>_CustomerID" };
    if (fordersgrid.lists.CustomerID?.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_CustomerID", form: "fordersgrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_CustomerID", form: "fordersgrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options = Object.assign({}, ew.modalLookupOptions, options, ew.vars.tables.orders.fields.CustomerID.modalLookupOptions);
    ew.createModalLookup(options);
});
</script>
</span>
<?php } ?>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_orders_CustomerID" class="el_orders_CustomerID">
<span<?= $Grid->CustomerID->viewAttributes() ?>>
<?= $Grid->CustomerID->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="orders" data-field="x_CustomerID" data-hidden="1" name="fordersgrid$x<?= $Grid->RowIndex ?>_CustomerID" id="fordersgrid$x<?= $Grid->RowIndex ?>_CustomerID" value="<?= HtmlEncode($Grid->CustomerID->FormValue) ?>">
<input type="hidden" data-table="orders" data-field="x_CustomerID" data-hidden="1" data-old name="fordersgrid$o<?= $Grid->RowIndex ?>_CustomerID" id="fordersgrid$o<?= $Grid->RowIndex ?>_CustomerID" value="<?= HtmlEncode($Grid->CustomerID->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->EmployeeID->Visible) { // EmployeeID ?>
        <td data-name="EmployeeID"<?= $Grid->EmployeeID->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<?php if (!$Security->isAdmin() && $Security->isLoggedIn() && !$Grid->userIDAllow("grid")) { // Non system admin ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_orders_EmployeeID" class="el_orders_EmployeeID">
    <select
        id="x<?= $Grid->RowIndex ?>_EmployeeID"
        name="x<?= $Grid->RowIndex ?>_EmployeeID"
        class="form-select ew-select<?= $Grid->EmployeeID->isInvalidClass() ?>"
        <?php if (!$Grid->EmployeeID->IsNativeSelect) { ?>
        data-select2-id="fordersgrid_x<?= $Grid->RowIndex ?>_EmployeeID"
        <?php } ?>
        data-table="orders"
        data-field="x_EmployeeID"
        data-value-separator="<?= $Grid->EmployeeID->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->EmployeeID->getPlaceHolder()) ?>"
        <?= $Grid->EmployeeID->editAttributes() ?>>
        <?= $Grid->EmployeeID->selectOptionListHtml("x{$Grid->RowIndex}_EmployeeID") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->EmployeeID->getErrorMessage() ?></div>
<?= $Grid->EmployeeID->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_EmployeeID") ?>
<?php if (!$Grid->EmployeeID->IsNativeSelect) { ?>
<script>
loadjs.ready("fordersgrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_EmployeeID", selectId: "fordersgrid_x<?= $Grid->RowIndex ?>_EmployeeID" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fordersgrid.lists.EmployeeID?.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_EmployeeID", form: "fordersgrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_EmployeeID", form: "fordersgrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.orders.fields.EmployeeID.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
<?php } else { ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_orders_EmployeeID" class="el_orders_EmployeeID">
    <select
        id="x<?= $Grid->RowIndex ?>_EmployeeID"
        name="x<?= $Grid->RowIndex ?>_EmployeeID"
        class="form-select ew-select<?= $Grid->EmployeeID->isInvalidClass() ?>"
        <?php if (!$Grid->EmployeeID->IsNativeSelect) { ?>
        data-select2-id="fordersgrid_x<?= $Grid->RowIndex ?>_EmployeeID"
        <?php } ?>
        data-table="orders"
        data-field="x_EmployeeID"
        data-value-separator="<?= $Grid->EmployeeID->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->EmployeeID->getPlaceHolder()) ?>"
        <?= $Grid->EmployeeID->editAttributes() ?>>
        <?= $Grid->EmployeeID->selectOptionListHtml("x{$Grid->RowIndex}_EmployeeID") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->EmployeeID->getErrorMessage() ?></div>
<?= $Grid->EmployeeID->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_EmployeeID") ?>
<?php if (!$Grid->EmployeeID->IsNativeSelect) { ?>
<script>
loadjs.ready("fordersgrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_EmployeeID", selectId: "fordersgrid_x<?= $Grid->RowIndex ?>_EmployeeID" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fordersgrid.lists.EmployeeID?.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_EmployeeID", form: "fordersgrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_EmployeeID", form: "fordersgrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.orders.fields.EmployeeID.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
<?php } ?>
<input type="hidden" data-table="orders" data-field="x_EmployeeID" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_EmployeeID" id="o<?= $Grid->RowIndex ?>_EmployeeID" value="<?= HtmlEncode($Grid->EmployeeID->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<?php if (!$Security->isAdmin() && $Security->isLoggedIn() && !$Grid->userIDAllow("grid")) { // Non system admin ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_orders_EmployeeID" class="el_orders_EmployeeID">
    <select
        id="x<?= $Grid->RowIndex ?>_EmployeeID"
        name="x<?= $Grid->RowIndex ?>_EmployeeID"
        class="form-select ew-select<?= $Grid->EmployeeID->isInvalidClass() ?>"
        <?php if (!$Grid->EmployeeID->IsNativeSelect) { ?>
        data-select2-id="fordersgrid_x<?= $Grid->RowIndex ?>_EmployeeID"
        <?php } ?>
        data-table="orders"
        data-field="x_EmployeeID"
        data-value-separator="<?= $Grid->EmployeeID->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->EmployeeID->getPlaceHolder()) ?>"
        <?= $Grid->EmployeeID->editAttributes() ?>>
        <?= $Grid->EmployeeID->selectOptionListHtml("x{$Grid->RowIndex}_EmployeeID") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->EmployeeID->getErrorMessage() ?></div>
<?= $Grid->EmployeeID->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_EmployeeID") ?>
<?php if (!$Grid->EmployeeID->IsNativeSelect) { ?>
<script>
loadjs.ready("fordersgrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_EmployeeID", selectId: "fordersgrid_x<?= $Grid->RowIndex ?>_EmployeeID" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fordersgrid.lists.EmployeeID?.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_EmployeeID", form: "fordersgrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_EmployeeID", form: "fordersgrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.orders.fields.EmployeeID.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
<?php } else { ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_orders_EmployeeID" class="el_orders_EmployeeID">
    <select
        id="x<?= $Grid->RowIndex ?>_EmployeeID"
        name="x<?= $Grid->RowIndex ?>_EmployeeID"
        class="form-select ew-select<?= $Grid->EmployeeID->isInvalidClass() ?>"
        <?php if (!$Grid->EmployeeID->IsNativeSelect) { ?>
        data-select2-id="fordersgrid_x<?= $Grid->RowIndex ?>_EmployeeID"
        <?php } ?>
        data-table="orders"
        data-field="x_EmployeeID"
        data-value-separator="<?= $Grid->EmployeeID->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->EmployeeID->getPlaceHolder()) ?>"
        <?= $Grid->EmployeeID->editAttributes() ?>>
        <?= $Grid->EmployeeID->selectOptionListHtml("x{$Grid->RowIndex}_EmployeeID") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->EmployeeID->getErrorMessage() ?></div>
<?= $Grid->EmployeeID->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_EmployeeID") ?>
<?php if (!$Grid->EmployeeID->IsNativeSelect) { ?>
<script>
loadjs.ready("fordersgrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_EmployeeID", selectId: "fordersgrid_x<?= $Grid->RowIndex ?>_EmployeeID" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fordersgrid.lists.EmployeeID?.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_EmployeeID", form: "fordersgrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_EmployeeID", form: "fordersgrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.orders.fields.EmployeeID.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
<?php } ?>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_orders_EmployeeID" class="el_orders_EmployeeID">
<span<?= $Grid->EmployeeID->viewAttributes() ?>>
<?= $Grid->EmployeeID->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="orders" data-field="x_EmployeeID" data-hidden="1" name="fordersgrid$x<?= $Grid->RowIndex ?>_EmployeeID" id="fordersgrid$x<?= $Grid->RowIndex ?>_EmployeeID" value="<?= HtmlEncode($Grid->EmployeeID->FormValue) ?>">
<input type="hidden" data-table="orders" data-field="x_EmployeeID" data-hidden="1" data-old name="fordersgrid$o<?= $Grid->RowIndex ?>_EmployeeID" id="fordersgrid$o<?= $Grid->RowIndex ?>_EmployeeID" value="<?= HtmlEncode($Grid->EmployeeID->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->OrderDate->Visible) { // OrderDate ?>
        <td data-name="OrderDate"<?= $Grid->OrderDate->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_orders_OrderDate" class="el_orders_OrderDate">
<input type="<?= $Grid->OrderDate->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_OrderDate" id="x<?= $Grid->RowIndex ?>_OrderDate" data-table="orders" data-field="x_OrderDate" value="<?= $Grid->OrderDate->EditValue ?>" placeholder="<?= HtmlEncode($Grid->OrderDate->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->OrderDate->formatPattern()) ?>"<?= $Grid->OrderDate->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->OrderDate->getErrorMessage() ?></div>
<?php if (!$Grid->OrderDate->ReadOnly && !$Grid->OrderDate->Disabled && !isset($Grid->OrderDate->EditAttrs["readonly"]) && !isset($Grid->OrderDate->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fordersgrid", "datetimepicker"], function () {
    let format = "<?= DateFormat(0) ?>",
        options = {
            localization: {
                locale: ew.LANGUAGE_ID + "-u-nu-" + ew.getNumberingSystem(),
                hourCycle: format.match(/H/) ? "h24" : "h12",
                format,
                ...ew.language.phrase("datetimepicker")
            },
            display: {
                icons: {
                    previous: ew.IS_RTL ? "fa-solid fa-chevron-right" : "fa-solid fa-chevron-left",
                    next: ew.IS_RTL ? "fa-solid fa-chevron-left" : "fa-solid fa-chevron-right"
                },
                components: {
                    hours: !!format.match(/h/i),
                    minutes: !!format.match(/m/),
                    seconds: !!format.match(/s/i)
                },
                theme: ew.isDark() ? "dark" : "auto"
            }
        };
    ew.createDateTimePicker("fordersgrid", "x<?= $Grid->RowIndex ?>_OrderDate", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</span>
<input type="hidden" data-table="orders" data-field="x_OrderDate" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_OrderDate" id="o<?= $Grid->RowIndex ?>_OrderDate" value="<?= HtmlEncode($Grid->OrderDate->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_orders_OrderDate" class="el_orders_OrderDate">
<input type="<?= $Grid->OrderDate->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_OrderDate" id="x<?= $Grid->RowIndex ?>_OrderDate" data-table="orders" data-field="x_OrderDate" value="<?= $Grid->OrderDate->EditValue ?>" placeholder="<?= HtmlEncode($Grid->OrderDate->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->OrderDate->formatPattern()) ?>"<?= $Grid->OrderDate->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->OrderDate->getErrorMessage() ?></div>
<?php if (!$Grid->OrderDate->ReadOnly && !$Grid->OrderDate->Disabled && !isset($Grid->OrderDate->EditAttrs["readonly"]) && !isset($Grid->OrderDate->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fordersgrid", "datetimepicker"], function () {
    let format = "<?= DateFormat(0) ?>",
        options = {
            localization: {
                locale: ew.LANGUAGE_ID + "-u-nu-" + ew.getNumberingSystem(),
                hourCycle: format.match(/H/) ? "h24" : "h12",
                format,
                ...ew.language.phrase("datetimepicker")
            },
            display: {
                icons: {
                    previous: ew.IS_RTL ? "fa-solid fa-chevron-right" : "fa-solid fa-chevron-left",
                    next: ew.IS_RTL ? "fa-solid fa-chevron-left" : "fa-solid fa-chevron-right"
                },
                components: {
                    hours: !!format.match(/h/i),
                    minutes: !!format.match(/m/),
                    seconds: !!format.match(/s/i)
                },
                theme: ew.isDark() ? "dark" : "auto"
            }
        };
    ew.createDateTimePicker("fordersgrid", "x<?= $Grid->RowIndex ?>_OrderDate", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_orders_OrderDate" class="el_orders_OrderDate">
<span<?= $Grid->OrderDate->viewAttributes() ?>>
<?= $Grid->OrderDate->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="orders" data-field="x_OrderDate" data-hidden="1" name="fordersgrid$x<?= $Grid->RowIndex ?>_OrderDate" id="fordersgrid$x<?= $Grid->RowIndex ?>_OrderDate" value="<?= HtmlEncode($Grid->OrderDate->FormValue) ?>">
<input type="hidden" data-table="orders" data-field="x_OrderDate" data-hidden="1" data-old name="fordersgrid$o<?= $Grid->RowIndex ?>_OrderDate" id="fordersgrid$o<?= $Grid->RowIndex ?>_OrderDate" value="<?= HtmlEncode($Grid->OrderDate->OldValue) ?>">
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
loadjs.ready(["fordersgrid","load"], () => fordersgrid.updateLists(<?= $Grid->RowIndex ?><?= $Grid->isAdd() || $Grid->isEdit() || $Grid->isCopy() || $Grid->RowIndex === '$rowindex$' ? ", true" : "" ?>));
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
<input type="hidden" name="detailpage" value="fordersgrid">
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
    ew.addEventHandlers("orders");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
