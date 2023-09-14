<?php

namespace PHPMaker2023\demo2023;

// Page object
$ProductsView = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<?php } ?>
<?php if (!$Page->isExport()) { ?>
<div class="btn-toolbar ew-toolbar">
<?php $Page->ExportOptions->render("body") ?>
<?php $Page->OtherOptions->render("body") ?>
</div>
<?php } ?>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<main class="view">
<?php if (!$Page->IsModal) { ?>
<?php if (!$Page->isExport()) { ?>
<?= $Page->Pager->render() ?>
<?php } ?>
<?php } ?>
<form name="fproductsview" id="fproductsview" class="ew-form ew-view-form overlay-wrapper" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="on">
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { products: currentTable } });
var currentPageID = ew.PAGE_ID = "view";
var currentForm;
var fproductsview;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fproductsview")
        .setPageId("view")
        .build();
    window[form.id] = form;
    currentForm = form;
    loadjs.done(form.id);
});
</script>
<?php } ?>
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="products">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="<?= $Page->TableClass ?>">
<?php if ($Page->ProductID->Visible) { // ProductID ?>
    <tr id="r_ProductID"<?= $Page->ProductID->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_products_ProductID"><?= $Page->ProductID->caption() ?></span></td>
        <td data-name="ProductID"<?= $Page->ProductID->cellAttributes() ?>>
<span id="el_products_ProductID">
<span<?= $Page->ProductID->viewAttributes() ?>>
<?= $Page->ProductID->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->ProductName->Visible) { // ProductName ?>
    <tr id="r_ProductName"<?= $Page->ProductName->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_products_ProductName"><?= $Page->ProductName->caption() ?></span></td>
        <td data-name="ProductName"<?= $Page->ProductName->cellAttributes() ?>>
<span id="el_products_ProductName">
<span<?= $Page->ProductName->viewAttributes() ?>>
<?= $Page->ProductName->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->SupplierID->Visible) { // SupplierID ?>
    <tr id="r_SupplierID"<?= $Page->SupplierID->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_products_SupplierID"><?= $Page->SupplierID->caption() ?></span></td>
        <td data-name="SupplierID"<?= $Page->SupplierID->cellAttributes() ?>>
<span id="el_products_SupplierID">
<span<?= $Page->SupplierID->viewAttributes() ?>>
<?= $Page->SupplierID->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->CategoryID->Visible) { // CategoryID ?>
    <tr id="r_CategoryID"<?= $Page->CategoryID->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_products_CategoryID"><?= $Page->CategoryID->caption() ?></span></td>
        <td data-name="CategoryID"<?= $Page->CategoryID->cellAttributes() ?>>
<span id="el_products_CategoryID">
<span<?= $Page->CategoryID->viewAttributes() ?>>
<?= $Page->CategoryID->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->QuantityPerUnit->Visible) { // QuantityPerUnit ?>
    <tr id="r_QuantityPerUnit"<?= $Page->QuantityPerUnit->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_products_QuantityPerUnit"><?= $Page->QuantityPerUnit->caption() ?></span></td>
        <td data-name="QuantityPerUnit"<?= $Page->QuantityPerUnit->cellAttributes() ?>>
<span id="el_products_QuantityPerUnit">
<span<?= $Page->QuantityPerUnit->viewAttributes() ?>>
<?= $Page->QuantityPerUnit->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->UnitPrice->Visible) { // UnitPrice ?>
    <tr id="r_UnitPrice"<?= $Page->UnitPrice->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_products_UnitPrice"><?= $Page->UnitPrice->caption() ?></span></td>
        <td data-name="UnitPrice"<?= $Page->UnitPrice->cellAttributes() ?>>
<span id="el_products_UnitPrice">
<span<?= $Page->UnitPrice->viewAttributes() ?>>
<?= $Page->UnitPrice->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->UnitsInStock->Visible) { // UnitsInStock ?>
    <tr id="r_UnitsInStock"<?= $Page->UnitsInStock->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_products_UnitsInStock"><?= $Page->UnitsInStock->caption() ?></span></td>
        <td data-name="UnitsInStock"<?= $Page->UnitsInStock->cellAttributes() ?>>
<span id="el_products_UnitsInStock">
<span<?= $Page->UnitsInStock->viewAttributes() ?>>
<?= $Page->UnitsInStock->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->UnitsOnOrder->Visible) { // UnitsOnOrder ?>
    <tr id="r_UnitsOnOrder"<?= $Page->UnitsOnOrder->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_products_UnitsOnOrder"><?= $Page->UnitsOnOrder->caption() ?></span></td>
        <td data-name="UnitsOnOrder"<?= $Page->UnitsOnOrder->cellAttributes() ?>>
<span id="el_products_UnitsOnOrder">
<span<?= $Page->UnitsOnOrder->viewAttributes() ?>>
<?= $Page->UnitsOnOrder->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->ReorderLevel->Visible) { // ReorderLevel ?>
    <tr id="r_ReorderLevel"<?= $Page->ReorderLevel->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_products_ReorderLevel"><?= $Page->ReorderLevel->caption() ?></span></td>
        <td data-name="ReorderLevel"<?= $Page->ReorderLevel->cellAttributes() ?>>
<span id="el_products_ReorderLevel">
<span<?= $Page->ReorderLevel->viewAttributes() ?>>
<?= $Page->ReorderLevel->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Discontinued->Visible) { // Discontinued ?>
    <tr id="r_Discontinued"<?= $Page->Discontinued->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_products_Discontinued"><?= $Page->Discontinued->caption() ?></span></td>
        <td data-name="Discontinued"<?= $Page->Discontinued->cellAttributes() ?>>
<span id="el_products_Discontinued">
<span<?= $Page->Discontinued->viewAttributes() ?>>
<div class="form-check d-inline-block">
    <input type="checkbox" id="x_Discontinued_<?= $Page->RowCount ?>" class="form-check-input" value="<?= $Page->Discontinued->getViewValue() ?>" disabled<?php if (ConvertToBool($Page->Discontinued->CurrentValue)) { ?> checked<?php } ?>>
    <label class="form-check-label" for="x_Discontinued_<?= $Page->RowCount ?>"></label>
</div></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->EAN13->Visible) { // EAN13 ?>
    <tr id="r_EAN13"<?= $Page->EAN13->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_products_EAN13"><?= $Page->EAN13->caption() ?></span></td>
        <td data-name="EAN13"<?= $Page->EAN13->cellAttributes() ?>>
<span id="el_products_EAN13">
<span<?= $Page->EAN13->viewAttributes() ?>><?= PhpBarcode::barcode(true)->show($Page->EAN13->CurrentValue, 'EAN-13', 60) ?></span>
</span>
</td>
    </tr>
<?php } ?>
</table>
</form>
<?php if (!$Page->IsModal) { ?>
<?php if (!$Page->isExport()) { ?>
<?= $Page->Pager->render() ?>
<?php } ?>
<?php } ?>
</main>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<?php if (!$Page->isExport()) { ?>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
