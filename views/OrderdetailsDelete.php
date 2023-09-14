<?php

namespace PHPMaker2023\demo2023;

// Page object
$OrderdetailsDelete = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { orderdetails: currentTable } });
var currentPageID = ew.PAGE_ID = "delete";
var currentForm;
var forderdetailsdelete;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("forderdetailsdelete")
        .setPageId("delete")
        .build();
    window[form.id] = form;
    currentForm = form;
    loadjs.done(form.id);
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<form name="forderdetailsdelete" id="forderdetailsdelete" class="ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="on">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="orderdetails">
<input type="hidden" name="action" id="action" value="delete">
<?php foreach ($Page->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode(Config("COMPOSITE_KEY_SEPARATOR"), $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?= HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="card ew-card ew-grid <?= $Page->TableGridClass ?>">
<div class="card-body ew-grid-middle-panel <?= $Page->TableContainerClass ?>" style="<?= $Page->TableContainerStyle ?>">
<table class="<?= $Page->TableClass ?>">
    <thead>
    <tr class="ew-table-header">
<?php if ($Page->OrderID->Visible) { // OrderID ?>
        <th class="<?= $Page->OrderID->headerCellClass() ?>"><span id="elh_orderdetails_OrderID" class="orderdetails_OrderID"><?= $Page->OrderID->caption() ?></span></th>
<?php } ?>
<?php if ($Page->ProductID->Visible) { // ProductID ?>
        <th class="<?= $Page->ProductID->headerCellClass() ?>"><span id="elh_orderdetails_ProductID" class="orderdetails_ProductID"><?= $Page->ProductID->caption() ?></span></th>
<?php } ?>
<?php if ($Page->UnitPrice->Visible) { // UnitPrice ?>
        <th class="<?= $Page->UnitPrice->headerCellClass() ?>"><span id="elh_orderdetails_UnitPrice" class="orderdetails_UnitPrice"><?= $Page->UnitPrice->caption() ?></span></th>
<?php } ?>
<?php if ($Page->Quantity->Visible) { // Quantity ?>
        <th class="<?= $Page->Quantity->headerCellClass() ?>"><span id="elh_orderdetails_Quantity" class="orderdetails_Quantity"><?= $Page->Quantity->caption() ?></span></th>
<?php } ?>
<?php if ($Page->Discount->Visible) { // Discount ?>
        <th class="<?= $Page->Discount->headerCellClass() ?>"><span id="elh_orderdetails_Discount" class="orderdetails_Discount"><?= $Page->Discount->caption() ?></span></th>
<?php } ?>
<?php if ($Page->SubTotal->Visible) { // SubTotal ?>
        <th class="<?= $Page->SubTotal->headerCellClass() ?>"><span id="elh_orderdetails_SubTotal" class="orderdetails_SubTotal"><?= $Page->SubTotal->caption() ?></span></th>
<?php } ?>
    </tr>
    </thead>
    <tbody>
<?php
$Page->RecordCount = 0;
$i = 0;
while (!$Page->Recordset->EOF) {
    $Page->RecordCount++;
    $Page->RowCount++;

    // Set row properties
    $Page->resetAttributes();
    $Page->RowType = ROWTYPE_VIEW; // View

    // Get the field contents
    $Page->loadRowValues($Page->Recordset);

    // Render row
    $Page->renderRow();
?>
    <tr <?= $Page->rowAttributes() ?>>
<?php if ($Page->OrderID->Visible) { // OrderID ?>
        <td<?= $Page->OrderID->cellAttributes() ?>>
<span id="">
<span<?= $Page->OrderID->viewAttributes() ?>>
<?= $Page->OrderID->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->ProductID->Visible) { // ProductID ?>
        <td<?= $Page->ProductID->cellAttributes() ?>>
<span id="">
<span<?= $Page->ProductID->viewAttributes() ?>>
<?= $Page->ProductID->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->UnitPrice->Visible) { // UnitPrice ?>
        <td<?= $Page->UnitPrice->cellAttributes() ?>>
<span id="">
<span<?= $Page->UnitPrice->viewAttributes() ?>>
<?= $Page->UnitPrice->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->Quantity->Visible) { // Quantity ?>
        <td<?= $Page->Quantity->cellAttributes() ?>>
<span id="">
<span<?= $Page->Quantity->viewAttributes() ?>>
<?= $Page->Quantity->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->Discount->Visible) { // Discount ?>
        <td<?= $Page->Discount->cellAttributes() ?>>
<span id="">
<span<?= $Page->Discount->viewAttributes() ?>>
<?= $Page->Discount->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->SubTotal->Visible) { // SubTotal ?>
        <td<?= $Page->SubTotal->cellAttributes() ?>>
<span id="">
<span<?= $Page->SubTotal->viewAttributes() ?>>
<?= $Page->SubTotal->getViewValue() ?></span>
</span>
</td>
<?php } ?>
    </tr>
<?php
    $Page->Recordset->moveNext();
}
$Page->Recordset->close();
?>
</tbody>
</table>
</div>
</div>
<div class="ew-buttons ew-desktop-buttons">
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit"><?= $Language->phrase("DeleteBtn") ?></button>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
</div>
</form>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
