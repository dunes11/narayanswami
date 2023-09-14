<?php

namespace PHPMaker2023\demo2023;

// Page object
$Orders2View = &$Page;
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
<form name="forders2view" id="forders2view" class="ew-form ew-view-form overlay-wrapper" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="on">
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { orders2: currentTable } });
var currentPageID = ew.PAGE_ID = "view";
var currentForm;
var forders2view;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("forders2view")
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
<input type="hidden" name="t" value="orders2">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="<?= $Page->TableClass ?>">
<?php if ($Page->OrderID->Visible) { // OrderID ?>
    <tr id="r_OrderID"<?= $Page->OrderID->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_orders2_OrderID"><?= $Page->OrderID->caption() ?></span></td>
        <td data-name="OrderID"<?= $Page->OrderID->cellAttributes() ?>>
<span id="el_orders2_OrderID">
<span<?= $Page->OrderID->viewAttributes() ?>>
<?= $Page->OrderID->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->CustomerID->Visible) { // CustomerID ?>
    <tr id="r_CustomerID"<?= $Page->CustomerID->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_orders2_CustomerID"><?= $Page->CustomerID->caption() ?></span></td>
        <td data-name="CustomerID"<?= $Page->CustomerID->cellAttributes() ?>>
<span id="el_orders2_CustomerID">
<span<?= $Page->CustomerID->viewAttributes() ?>>
<?= $Page->CustomerID->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->EmployeeID->Visible) { // EmployeeID ?>
    <tr id="r_EmployeeID"<?= $Page->EmployeeID->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_orders2_EmployeeID"><?= $Page->EmployeeID->caption() ?></span></td>
        <td data-name="EmployeeID"<?= $Page->EmployeeID->cellAttributes() ?>>
<span id="el_orders2_EmployeeID">
<span<?= $Page->EmployeeID->viewAttributes() ?>>
<?= $Page->EmployeeID->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->OrderDate->Visible) { // OrderDate ?>
    <tr id="r_OrderDate"<?= $Page->OrderDate->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_orders2_OrderDate"><?= $Page->OrderDate->caption() ?></span></td>
        <td data-name="OrderDate"<?= $Page->OrderDate->cellAttributes() ?>>
<span id="el_orders2_OrderDate">
<span<?= $Page->OrderDate->viewAttributes() ?>>
<?= $Page->OrderDate->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->RequiredDate->Visible) { // RequiredDate ?>
    <tr id="r_RequiredDate"<?= $Page->RequiredDate->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_orders2_RequiredDate"><?= $Page->RequiredDate->caption() ?></span></td>
        <td data-name="RequiredDate"<?= $Page->RequiredDate->cellAttributes() ?>>
<span id="el_orders2_RequiredDate">
<span<?= $Page->RequiredDate->viewAttributes() ?>>
<?= $Page->RequiredDate->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->ShippedDate->Visible) { // ShippedDate ?>
    <tr id="r_ShippedDate"<?= $Page->ShippedDate->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_orders2_ShippedDate"><?= $Page->ShippedDate->caption() ?></span></td>
        <td data-name="ShippedDate"<?= $Page->ShippedDate->cellAttributes() ?>>
<span id="el_orders2_ShippedDate">
<span<?= $Page->ShippedDate->viewAttributes() ?>>
<?= $Page->ShippedDate->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->ShipVia->Visible) { // ShipVia ?>
    <tr id="r_ShipVia"<?= $Page->ShipVia->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_orders2_ShipVia"><?= $Page->ShipVia->caption() ?></span></td>
        <td data-name="ShipVia"<?= $Page->ShipVia->cellAttributes() ?>>
<span id="el_orders2_ShipVia">
<span<?= $Page->ShipVia->viewAttributes() ?>>
<?= $Page->ShipVia->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Freight->Visible) { // Freight ?>
    <tr id="r_Freight"<?= $Page->Freight->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_orders2_Freight"><?= $Page->Freight->caption() ?></span></td>
        <td data-name="Freight"<?= $Page->Freight->cellAttributes() ?>>
<span id="el_orders2_Freight">
<span<?= $Page->Freight->viewAttributes() ?>>
<?= $Page->Freight->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->ShipName->Visible) { // ShipName ?>
    <tr id="r_ShipName"<?= $Page->ShipName->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_orders2_ShipName"><?= $Page->ShipName->caption() ?></span></td>
        <td data-name="ShipName"<?= $Page->ShipName->cellAttributes() ?>>
<span id="el_orders2_ShipName">
<span<?= $Page->ShipName->viewAttributes() ?>>
<?= $Page->ShipName->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->ShipAddress->Visible) { // ShipAddress ?>
    <tr id="r_ShipAddress"<?= $Page->ShipAddress->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_orders2_ShipAddress"><?= $Page->ShipAddress->caption() ?></span></td>
        <td data-name="ShipAddress"<?= $Page->ShipAddress->cellAttributes() ?>>
<span id="el_orders2_ShipAddress">
<span<?= $Page->ShipAddress->viewAttributes() ?>>
<?= $Page->ShipAddress->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->ShipCity->Visible) { // ShipCity ?>
    <tr id="r_ShipCity"<?= $Page->ShipCity->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_orders2_ShipCity"><?= $Page->ShipCity->caption() ?></span></td>
        <td data-name="ShipCity"<?= $Page->ShipCity->cellAttributes() ?>>
<span id="el_orders2_ShipCity">
<span<?= $Page->ShipCity->viewAttributes() ?>>
<?= $Page->ShipCity->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->ShipRegion->Visible) { // ShipRegion ?>
    <tr id="r_ShipRegion"<?= $Page->ShipRegion->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_orders2_ShipRegion"><?= $Page->ShipRegion->caption() ?></span></td>
        <td data-name="ShipRegion"<?= $Page->ShipRegion->cellAttributes() ?>>
<span id="el_orders2_ShipRegion">
<span<?= $Page->ShipRegion->viewAttributes() ?>>
<?= $Page->ShipRegion->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->ShipPostalCode->Visible) { // ShipPostalCode ?>
    <tr id="r_ShipPostalCode"<?= $Page->ShipPostalCode->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_orders2_ShipPostalCode"><?= $Page->ShipPostalCode->caption() ?></span></td>
        <td data-name="ShipPostalCode"<?= $Page->ShipPostalCode->cellAttributes() ?>>
<span id="el_orders2_ShipPostalCode">
<span<?= $Page->ShipPostalCode->viewAttributes() ?>>
<?= $Page->ShipPostalCode->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->ShipCountry->Visible) { // ShipCountry ?>
    <tr id="r_ShipCountry"<?= $Page->ShipCountry->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_orders2_ShipCountry"><?= $Page->ShipCountry->caption() ?></span></td>
        <td data-name="ShipCountry"<?= $Page->ShipCountry->cellAttributes() ?>>
<span id="el_orders2_ShipCountry">
<span<?= $Page->ShipCountry->viewAttributes() ?>>
<?= $Page->ShipCountry->getViewValue() ?></span>
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
