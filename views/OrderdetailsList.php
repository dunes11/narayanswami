<?php

namespace PHPMaker2023\demo2023;

// Page object
$OrderdetailsList = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { orderdetails: currentTable } });
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
            "ProductID": <?= $Page->ProductID->toClientList($Page) ?>,
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
if ($Page->DbMasterFilter != "" && $Page->getCurrentMasterTable() == "orders") {
    if ($Page->MasterRecordExists) {
        include_once "views/OrdersMaster.php";
    }
}
?>
<?php } ?>
<?php if (!$Page->IsModal) { ?>
<form name="forderdetailssrch" id="forderdetailssrch" class="ew-form ew-ext-search-form" action="<?= CurrentPageUrl(false) ?>" novalidate autocomplete="on">
<div id="forderdetailssrch_search_panel" class="mb-2 mb-sm-0 <?= $Page->SearchPanelClass ?>"><!-- .ew-search-panel -->
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { orderdetails: currentTable } });
var currentForm;
var forderdetailssrch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery,
        fields = currentTable.fields;

    // Form object for search
    let form = new ew.FormBuilder()
        .setId("forderdetailssrch")
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
<input type="hidden" name="t" value="orderdetails">
<?php if ($Page->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<?php if ($Page->getCurrentMasterTable() == "orders" && $Page->CurrentAction) { ?>
<input type="hidden" name="<?= Config("TABLE_SHOW_MASTER") ?>" value="orders">
<input type="hidden" name="fk_OrderID" value="<?= HtmlEncode($Page->OrderID->getSessionValue()) ?>">
<?php } ?>
<div id="gmp_orderdetails" class="card-body ew-grid-middle-panel <?= $Page->TableContainerClass ?>" style="<?= $Page->TableContainerStyle ?>">
<?php if ($Page->TotalRecords > 0 || $Page->isGridEdit() || $Page->isMultiEdit()) { ?>
<table id="tbl_orderdetailslist" class="<?= $Page->TableClass ?>"><!-- .ew-table -->
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
        <th data-name="OrderID" class="<?= $Page->OrderID->headerCellClass() ?>"><div id="elh_orderdetails_OrderID" class="orderdetails_OrderID"><?= $Page->renderFieldHeader($Page->OrderID) ?></div></th>
<?php } ?>
<?php if ($Page->ProductID->Visible) { // ProductID ?>
        <th data-name="ProductID" class="<?= $Page->ProductID->headerCellClass() ?>"><div id="elh_orderdetails_ProductID" class="orderdetails_ProductID"><?= $Page->renderFieldHeader($Page->ProductID) ?></div></th>
<?php } ?>
<?php if ($Page->UnitPrice->Visible) { // UnitPrice ?>
        <th data-name="UnitPrice" class="<?= $Page->UnitPrice->headerCellClass() ?>"><div id="elh_orderdetails_UnitPrice" class="orderdetails_UnitPrice"><?= $Page->renderFieldHeader($Page->UnitPrice) ?></div></th>
<?php } ?>
<?php if ($Page->Quantity->Visible) { // Quantity ?>
        <th data-name="Quantity" class="<?= $Page->Quantity->headerCellClass() ?>"><div id="elh_orderdetails_Quantity" class="orderdetails_Quantity"><?= $Page->renderFieldHeader($Page->Quantity) ?></div></th>
<?php } ?>
<?php if ($Page->Discount->Visible) { // Discount ?>
        <th data-name="Discount" class="<?= $Page->Discount->headerCellClass() ?>"><div id="elh_orderdetails_Discount" class="orderdetails_Discount"><?= $Page->renderFieldHeader($Page->Discount) ?></div></th>
<?php } ?>
<?php if ($Page->SubTotal->Visible) { // SubTotal ?>
        <th data-name="SubTotal" class="<?= $Page->SubTotal->headerCellClass() ?>"><div id="elh_orderdetails_SubTotal" class="orderdetails_SubTotal"><?= $Page->renderFieldHeader($Page->SubTotal) ?></div></th>
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
<?php if ($Page->OrderID->getSessionValue() != "") { ?>
<span<?= $Page->OrderID->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->OrderID->getDisplayValue($Page->OrderID->ViewValue))) ?>"></span>
<input type="hidden" id="x<?= $Page->RowIndex ?>_OrderID" name="x<?= $Page->RowIndex ?>_OrderID" value="<?= HtmlEncode($Page->OrderID->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_orderdetails_OrderID" class="el_orderdetails_OrderID">
<input type="<?= $Page->OrderID->getInputTextType() ?>" name="x<?= $Page->RowIndex ?>_OrderID" id="x<?= $Page->RowIndex ?>_OrderID" data-table="orderdetails" data-field="x_OrderID" value="<?= $Page->OrderID->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->OrderID->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->OrderID->formatPattern()) ?>"<?= $Page->OrderID->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->OrderID->getErrorMessage() ?></div>
</span>
<?php } ?>
<input type="hidden" data-table="orderdetails" data-field="x_OrderID" data-hidden="1" data-old name="o<?= $Page->RowIndex ?>_OrderID" id="o<?= $Page->RowIndex ?>_OrderID" value="<?= HtmlEncode($Page->OrderID->OldValue) ?>">
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_orderdetails_OrderID" class="el_orderdetails_OrderID">
<?php if ($Page->OrderID->getSessionValue() != "") { ?>
<span<?= $Page->OrderID->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->OrderID->getDisplayValue($Page->OrderID->EditValue))) ?>"></span>
<input type="hidden" id="x<?= $Page->RowIndex ?>_OrderID" name="x<?= $Page->RowIndex ?>_OrderID" value="<?= HtmlEncode($Page->OrderID->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<input type="<?= $Page->OrderID->getInputTextType() ?>" name="x<?= $Page->RowIndex ?>_OrderID" id="x<?= $Page->RowIndex ?>_OrderID" data-table="orderdetails" data-field="x_OrderID" value="<?= $Page->OrderID->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->OrderID->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->OrderID->formatPattern()) ?>"<?= $Page->OrderID->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->OrderID->getErrorMessage() ?></div>
<?php } ?>
<input type="hidden" data-table="orderdetails" data-field="x_OrderID" data-hidden="1" data-old name="o<?= $Page->RowIndex ?>_OrderID" id="o<?= $Page->RowIndex ?>_OrderID" value="<?= HtmlEncode($Page->OrderID->OldValue ?? $Page->OrderID->CurrentValue) ?>">
</span>
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_orderdetails_OrderID" class="el_orderdetails_OrderID">
<span<?= $Page->OrderID->viewAttributes() ?>>
<?= $Page->OrderID->getViewValue() ?></span>
</span>
<?php } ?>
</td>
    <?php } else { ?>
            <input type="hidden" data-table="orderdetails" data-field="x_OrderID" data-hidden="1" name="x<?= $Page->RowIndex ?>_OrderID" id="x<?= $Page->RowIndex ?>_OrderID" value="<?= HtmlEncode($Page->OrderID->CurrentValue) ?>">
    <?php } ?>
    <?php if ($Page->ProductID->Visible) { // ProductID ?>
        <td data-name="ProductID"<?= $Page->ProductID->cellAttributes() ?>>
<?php if ($Page->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_orderdetails_ProductID" class="el_orderdetails_ProductID">
    <select
        id="x<?= $Page->RowIndex ?>_ProductID"
        name="x<?= $Page->RowIndex ?>_ProductID"
        class="form-control ew-select<?= $Page->ProductID->isInvalidClass() ?>"
        data-select2-id="<?= $Page->FormName ?>_x<?= $Page->RowIndex ?>_ProductID"
        data-table="orderdetails"
        data-field="x_ProductID"
        data-caption="<?= HtmlEncode(RemoveHtml($Page->ProductID->caption())) ?>"
        data-modal-lookup="true"
        data-value-separator="<?= $Page->ProductID->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->ProductID->getPlaceHolder()) ?>"
        data-ew-action="autofill"
        <?= $Page->ProductID->editAttributes() ?>>
        <?= $Page->ProductID->selectOptionListHtml("x{$Page->RowIndex}_ProductID") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->ProductID->getErrorMessage() ?></div>
<?= $Page->ProductID->Lookup->getParamTag($Page, "p_x" . $Page->RowIndex . "_ProductID") ?>
<script>
loadjs.ready("<?= $Page->FormName ?>", function() {
    var options = { name: "x<?= $Page->RowIndex ?>_ProductID", selectId: "<?= $Page->FormName ?>_x<?= $Page->RowIndex ?>_ProductID" };
    if (<?= $Page->FormName ?>.lists.ProductID?.lookupOptions.length) {
        options.data = { id: "x<?= $Page->RowIndex ?>_ProductID", form: "<?= $Page->FormName ?>" };
    } else {
        options.ajax = { id: "x<?= $Page->RowIndex ?>_ProductID", form: "<?= $Page->FormName ?>", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options = Object.assign({}, ew.modalLookupOptions, options, ew.vars.tables.orderdetails.fields.ProductID.modalLookupOptions);
    ew.createModalLookup(options);
});
</script>
</span>
<input type="hidden" data-table="orderdetails" data-field="x_ProductID" data-hidden="1" data-old name="o<?= $Page->RowIndex ?>_ProductID" id="o<?= $Page->RowIndex ?>_ProductID" value="<?= HtmlEncode($Page->ProductID->OldValue) ?>">
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_orderdetails_ProductID" class="el_orderdetails_ProductID">
    <select
        id="x<?= $Page->RowIndex ?>_ProductID"
        name="x<?= $Page->RowIndex ?>_ProductID"
        class="form-control ew-select<?= $Page->ProductID->isInvalidClass() ?>"
        data-select2-id="<?= $Page->FormName ?>_x<?= $Page->RowIndex ?>_ProductID"
        data-table="orderdetails"
        data-field="x_ProductID"
        data-caption="<?= HtmlEncode(RemoveHtml($Page->ProductID->caption())) ?>"
        data-modal-lookup="true"
        data-value-separator="<?= $Page->ProductID->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->ProductID->getPlaceHolder()) ?>"
        data-ew-action="autofill"
        <?= $Page->ProductID->editAttributes() ?>>
        <?= $Page->ProductID->selectOptionListHtml("x{$Page->RowIndex}_ProductID") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->ProductID->getErrorMessage() ?></div>
<?= $Page->ProductID->Lookup->getParamTag($Page, "p_x" . $Page->RowIndex . "_ProductID") ?>
<script>
loadjs.ready("<?= $Page->FormName ?>", function() {
    var options = { name: "x<?= $Page->RowIndex ?>_ProductID", selectId: "<?= $Page->FormName ?>_x<?= $Page->RowIndex ?>_ProductID" };
    if (<?= $Page->FormName ?>.lists.ProductID?.lookupOptions.length) {
        options.data = { id: "x<?= $Page->RowIndex ?>_ProductID", form: "<?= $Page->FormName ?>" };
    } else {
        options.ajax = { id: "x<?= $Page->RowIndex ?>_ProductID", form: "<?= $Page->FormName ?>", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options = Object.assign({}, ew.modalLookupOptions, options, ew.vars.tables.orderdetails.fields.ProductID.modalLookupOptions);
    ew.createModalLookup(options);
});
</script>
<input type="hidden" data-table="orderdetails" data-field="x_ProductID" data-hidden="1" data-old name="o<?= $Page->RowIndex ?>_ProductID" id="o<?= $Page->RowIndex ?>_ProductID" value="<?= HtmlEncode($Page->ProductID->OldValue ?? $Page->ProductID->CurrentValue) ?>">
</span>
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_orderdetails_ProductID" class="el_orderdetails_ProductID">
<span<?= $Page->ProductID->viewAttributes() ?>>
<?= $Page->ProductID->getViewValue() ?></span>
</span>
<?php } ?>
</td>
    <?php } else { ?>
            <input type="hidden" data-table="orderdetails" data-field="x_ProductID" data-hidden="1" name="x<?= $Page->RowIndex ?>_ProductID" id="x<?= $Page->RowIndex ?>_ProductID" value="<?= HtmlEncode($Page->ProductID->CurrentValue) ?>">
    <?php } ?>
    <?php if ($Page->UnitPrice->Visible) { // UnitPrice ?>
        <td data-name="UnitPrice"<?= $Page->UnitPrice->cellAttributes() ?>>
<?php if ($Page->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_orderdetails_UnitPrice" class="el_orderdetails_UnitPrice">
<input type="<?= $Page->UnitPrice->getInputTextType() ?>" name="x<?= $Page->RowIndex ?>_UnitPrice" id="x<?= $Page->RowIndex ?>_UnitPrice" data-table="orderdetails" data-field="x_UnitPrice" value="<?= $Page->UnitPrice->EditValue ?>" size="20" placeholder="<?= HtmlEncode($Page->UnitPrice->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->UnitPrice->formatPattern()) ?>"<?= $Page->UnitPrice->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->UnitPrice->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="orderdetails" data-field="x_UnitPrice" data-hidden="1" data-old name="o<?= $Page->RowIndex ?>_UnitPrice" id="o<?= $Page->RowIndex ?>_UnitPrice" value="<?= HtmlEncode($Page->UnitPrice->OldValue) ?>">
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_orderdetails_UnitPrice" class="el_orderdetails_UnitPrice">
<input type="<?= $Page->UnitPrice->getInputTextType() ?>" name="x<?= $Page->RowIndex ?>_UnitPrice" id="x<?= $Page->RowIndex ?>_UnitPrice" data-table="orderdetails" data-field="x_UnitPrice" value="<?= $Page->UnitPrice->EditValue ?>" size="20" placeholder="<?= HtmlEncode($Page->UnitPrice->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->UnitPrice->formatPattern()) ?>"<?= $Page->UnitPrice->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->UnitPrice->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_orderdetails_UnitPrice" class="el_orderdetails_UnitPrice">
<span<?= $Page->UnitPrice->viewAttributes() ?>>
<?= $Page->UnitPrice->getViewValue() ?></span>
</span>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Page->Quantity->Visible) { // Quantity ?>
        <td data-name="Quantity"<?= $Page->Quantity->cellAttributes() ?>>
<?php if ($Page->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_orderdetails_Quantity" class="el_orderdetails_Quantity">
<input type="<?= $Page->Quantity->getInputTextType() ?>" name="x<?= $Page->RowIndex ?>_Quantity" id="x<?= $Page->RowIndex ?>_Quantity" data-table="orderdetails" data-field="x_Quantity" value="<?= $Page->Quantity->EditValue ?>" size="20" placeholder="<?= HtmlEncode($Page->Quantity->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Quantity->formatPattern()) ?>"<?= $Page->Quantity->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->Quantity->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="orderdetails" data-field="x_Quantity" data-hidden="1" data-old name="o<?= $Page->RowIndex ?>_Quantity" id="o<?= $Page->RowIndex ?>_Quantity" value="<?= HtmlEncode($Page->Quantity->OldValue) ?>">
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_orderdetails_Quantity" class="el_orderdetails_Quantity">
<input type="<?= $Page->Quantity->getInputTextType() ?>" name="x<?= $Page->RowIndex ?>_Quantity" id="x<?= $Page->RowIndex ?>_Quantity" data-table="orderdetails" data-field="x_Quantity" value="<?= $Page->Quantity->EditValue ?>" size="20" placeholder="<?= HtmlEncode($Page->Quantity->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Quantity->formatPattern()) ?>"<?= $Page->Quantity->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->Quantity->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_orderdetails_Quantity" class="el_orderdetails_Quantity">
<span<?= $Page->Quantity->viewAttributes() ?>>
<?= $Page->Quantity->getViewValue() ?></span>
</span>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Page->Discount->Visible) { // Discount ?>
        <td data-name="Discount"<?= $Page->Discount->cellAttributes() ?>>
<?php if ($Page->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_orderdetails_Discount" class="el_orderdetails_Discount">
<input type="<?= $Page->Discount->getInputTextType() ?>" name="x<?= $Page->RowIndex ?>_Discount" id="x<?= $Page->RowIndex ?>_Discount" data-table="orderdetails" data-field="x_Discount" value="<?= $Page->Discount->EditValue ?>" size="20" placeholder="<?= HtmlEncode($Page->Discount->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Discount->formatPattern()) ?>"<?= $Page->Discount->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->Discount->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="orderdetails" data-field="x_Discount" data-hidden="1" data-old name="o<?= $Page->RowIndex ?>_Discount" id="o<?= $Page->RowIndex ?>_Discount" value="<?= HtmlEncode($Page->Discount->OldValue) ?>">
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_orderdetails_Discount" class="el_orderdetails_Discount">
<input type="<?= $Page->Discount->getInputTextType() ?>" name="x<?= $Page->RowIndex ?>_Discount" id="x<?= $Page->RowIndex ?>_Discount" data-table="orderdetails" data-field="x_Discount" value="<?= $Page->Discount->EditValue ?>" size="20" placeholder="<?= HtmlEncode($Page->Discount->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Discount->formatPattern()) ?>"<?= $Page->Discount->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->Discount->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_orderdetails_Discount" class="el_orderdetails_Discount">
<span<?= $Page->Discount->viewAttributes() ?>>
<?= $Page->Discount->getViewValue() ?></span>
</span>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Page->SubTotal->Visible) { // SubTotal ?>
        <td data-name="SubTotal"<?= $Page->SubTotal->cellAttributes() ?>>
<?php if ($Page->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_orderdetails_SubTotal" class="el_orderdetails_SubTotal">
<input type="<?= $Page->SubTotal->getInputTextType() ?>" name="x<?= $Page->RowIndex ?>_SubTotal" id="x<?= $Page->RowIndex ?>_SubTotal" data-table="orderdetails" data-field="x_SubTotal" value="<?= $Page->SubTotal->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->SubTotal->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->SubTotal->formatPattern()) ?>"<?= $Page->SubTotal->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->SubTotal->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="orderdetails" data-field="x_SubTotal" data-hidden="1" data-old name="o<?= $Page->RowIndex ?>_SubTotal" id="o<?= $Page->RowIndex ?>_SubTotal" value="<?= HtmlEncode($Page->SubTotal->OldValue) ?>">
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_orderdetails_SubTotal" class="el_orderdetails_SubTotal">
<input type="<?= $Page->SubTotal->getInputTextType() ?>" name="x<?= $Page->RowIndex ?>_SubTotal" id="x<?= $Page->RowIndex ?>_SubTotal" data-table="orderdetails" data-field="x_SubTotal" value="<?= $Page->SubTotal->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->SubTotal->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->SubTotal->formatPattern()) ?>"<?= $Page->SubTotal->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->SubTotal->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_orderdetails_SubTotal" class="el_orderdetails_SubTotal">
<span<?= $Page->SubTotal->viewAttributes() ?>>
<?= $Page->SubTotal->getViewValue() ?></span>
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
<?php if ($Page->isGridAdd()) { ?>
<input type="hidden" name="action" id="action" value="gridinsert">
<input type="hidden" name="<?= $Page->FormKeyCountName ?>" id="<?= $Page->FormKeyCountName ?>" value="<?= $Page->KeyCount ?>">
<?= $Page->MultiSelectKey ?>
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
    ew.addEventHandlers("orderdetails");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
