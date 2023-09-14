<?php

namespace PHPMaker2023\demo2023;

// Page object
$ProductsDelete = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { products: currentTable } });
var currentPageID = ew.PAGE_ID = "delete";
var currentForm;
var fproductsdelete;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fproductsdelete")
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
<form name="fproductsdelete" id="fproductsdelete" class="ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="on">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="products">
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
<?php if ($Page->ProductID->Visible) { // ProductID ?>
        <th class="<?= $Page->ProductID->headerCellClass() ?>"><span id="elh_products_ProductID" class="products_ProductID"><?= $Page->ProductID->caption() ?></span></th>
<?php } ?>
<?php if ($Page->ProductName->Visible) { // ProductName ?>
        <th class="<?= $Page->ProductName->headerCellClass() ?>"><span id="elh_products_ProductName" class="products_ProductName"><?= $Page->ProductName->caption() ?></span></th>
<?php } ?>
<?php if ($Page->SupplierID->Visible) { // SupplierID ?>
        <th class="<?= $Page->SupplierID->headerCellClass() ?>"><span id="elh_products_SupplierID" class="products_SupplierID"><?= $Page->SupplierID->caption() ?></span></th>
<?php } ?>
<?php if ($Page->CategoryID->Visible) { // CategoryID ?>
        <th class="<?= $Page->CategoryID->headerCellClass() ?>"><span id="elh_products_CategoryID" class="products_CategoryID"><?= $Page->CategoryID->caption() ?></span></th>
<?php } ?>
<?php if ($Page->QuantityPerUnit->Visible) { // QuantityPerUnit ?>
        <th class="<?= $Page->QuantityPerUnit->headerCellClass() ?>"><span id="elh_products_QuantityPerUnit" class="products_QuantityPerUnit"><?= $Page->QuantityPerUnit->caption() ?></span></th>
<?php } ?>
<?php if ($Page->UnitPrice->Visible) { // UnitPrice ?>
        <th class="<?= $Page->UnitPrice->headerCellClass() ?>"><span id="elh_products_UnitPrice" class="products_UnitPrice"><?= $Page->UnitPrice->caption() ?></span></th>
<?php } ?>
<?php if ($Page->UnitsInStock->Visible) { // UnitsInStock ?>
        <th class="<?= $Page->UnitsInStock->headerCellClass() ?>"><span id="elh_products_UnitsInStock" class="products_UnitsInStock"><?= $Page->UnitsInStock->caption() ?></span></th>
<?php } ?>
<?php if ($Page->UnitsOnOrder->Visible) { // UnitsOnOrder ?>
        <th class="<?= $Page->UnitsOnOrder->headerCellClass() ?>"><span id="elh_products_UnitsOnOrder" class="products_UnitsOnOrder"><?= $Page->UnitsOnOrder->caption() ?></span></th>
<?php } ?>
<?php if ($Page->ReorderLevel->Visible) { // ReorderLevel ?>
        <th class="<?= $Page->ReorderLevel->headerCellClass() ?>"><span id="elh_products_ReorderLevel" class="products_ReorderLevel"><?= $Page->ReorderLevel->caption() ?></span></th>
<?php } ?>
<?php if ($Page->Discontinued->Visible) { // Discontinued ?>
        <th class="<?= $Page->Discontinued->headerCellClass() ?>"><span id="elh_products_Discontinued" class="products_Discontinued"><?= $Page->Discontinued->caption() ?></span></th>
<?php } ?>
<?php if ($Page->EAN13->Visible) { // EAN13 ?>
        <th class="<?= $Page->EAN13->headerCellClass() ?>"><span id="elh_products_EAN13" class="products_EAN13"><?= $Page->EAN13->caption() ?></span></th>
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
<?php if ($Page->ProductID->Visible) { // ProductID ?>
        <td<?= $Page->ProductID->cellAttributes() ?>>
<span id="">
<span<?= $Page->ProductID->viewAttributes() ?>>
<?= $Page->ProductID->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->ProductName->Visible) { // ProductName ?>
        <td<?= $Page->ProductName->cellAttributes() ?>>
<span id="">
<span<?= $Page->ProductName->viewAttributes() ?>>
<?= $Page->ProductName->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->SupplierID->Visible) { // SupplierID ?>
        <td<?= $Page->SupplierID->cellAttributes() ?>>
<span id="">
<span<?= $Page->SupplierID->viewAttributes() ?>>
<?= $Page->SupplierID->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->CategoryID->Visible) { // CategoryID ?>
        <td<?= $Page->CategoryID->cellAttributes() ?>>
<span id="">
<span<?= $Page->CategoryID->viewAttributes() ?>>
<?= $Page->CategoryID->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->QuantityPerUnit->Visible) { // QuantityPerUnit ?>
        <td<?= $Page->QuantityPerUnit->cellAttributes() ?>>
<span id="">
<span<?= $Page->QuantityPerUnit->viewAttributes() ?>>
<?= $Page->QuantityPerUnit->getViewValue() ?></span>
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
<?php if ($Page->UnitsInStock->Visible) { // UnitsInStock ?>
        <td<?= $Page->UnitsInStock->cellAttributes() ?>>
<span id="">
<span<?= $Page->UnitsInStock->viewAttributes() ?>>
<?= $Page->UnitsInStock->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->UnitsOnOrder->Visible) { // UnitsOnOrder ?>
        <td<?= $Page->UnitsOnOrder->cellAttributes() ?>>
<span id="">
<span<?= $Page->UnitsOnOrder->viewAttributes() ?>>
<?= $Page->UnitsOnOrder->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->ReorderLevel->Visible) { // ReorderLevel ?>
        <td<?= $Page->ReorderLevel->cellAttributes() ?>>
<span id="">
<span<?= $Page->ReorderLevel->viewAttributes() ?>>
<?= $Page->ReorderLevel->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->Discontinued->Visible) { // Discontinued ?>
        <td<?= $Page->Discontinued->cellAttributes() ?>>
<span id="">
<span<?= $Page->Discontinued->viewAttributes() ?>>
<div class="form-check d-inline-block">
    <input type="checkbox" id="x_Discontinued_<?= $Page->RowCount ?>" class="form-check-input" value="<?= $Page->Discontinued->getViewValue() ?>" disabled<?php if (ConvertToBool($Page->Discontinued->CurrentValue)) { ?> checked<?php } ?>>
    <label class="form-check-label" for="x_Discontinued_<?= $Page->RowCount ?>"></label>
</div></span>
</span>
</td>
<?php } ?>
<?php if ($Page->EAN13->Visible) { // EAN13 ?>
        <td<?= $Page->EAN13->cellAttributes() ?>>
<span id="">
<span<?= $Page->EAN13->viewAttributes() ?>><?= PhpBarcode::barcode(true)->show($Page->EAN13->CurrentValue, 'EAN-13', 60) ?></span>
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
