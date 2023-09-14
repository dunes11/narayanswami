<?php

namespace PHPMaker2023\demo2023;

// Page object
$OrdersView = &$Page;
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
<form name="fordersview" id="fordersview" class="ew-form ew-view-form overlay-wrapper" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="on">
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { orders: currentTable } });
var currentPageID = ew.PAGE_ID = "view";
var currentForm;
var fordersview;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fordersview")
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
<input type="hidden" name="t" value="orders">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="<?= $Page->TableClass ?>">
<?php if ($Page->OrderID->Visible) { // OrderID ?>
    <tr id="r_OrderID"<?= $Page->OrderID->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_orders_OrderID"><?= $Page->OrderID->caption() ?></span></td>
        <td data-name="OrderID"<?= $Page->OrderID->cellAttributes() ?>>
<span id="el_orders_OrderID">
<span<?= $Page->OrderID->viewAttributes() ?>>
<?= $Page->OrderID->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->CustomerID->Visible) { // CustomerID ?>
    <tr id="r_CustomerID"<?= $Page->CustomerID->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_orders_CustomerID"><?= $Page->CustomerID->caption() ?></span></td>
        <td data-name="CustomerID"<?= $Page->CustomerID->cellAttributes() ?>>
<span id="el_orders_CustomerID">
<span<?= $Page->CustomerID->viewAttributes() ?>>
<?= $Page->CustomerID->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->EmployeeID->Visible) { // EmployeeID ?>
    <tr id="r_EmployeeID"<?= $Page->EmployeeID->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_orders_EmployeeID"><?= $Page->EmployeeID->caption() ?></span></td>
        <td data-name="EmployeeID"<?= $Page->EmployeeID->cellAttributes() ?>>
<span id="el_orders_EmployeeID">
<span<?= $Page->EmployeeID->viewAttributes() ?>>
<?= $Page->EmployeeID->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->OrderDate->Visible) { // OrderDate ?>
    <tr id="r_OrderDate"<?= $Page->OrderDate->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_orders_OrderDate"><?= $Page->OrderDate->caption() ?></span></td>
        <td data-name="OrderDate"<?= $Page->OrderDate->cellAttributes() ?>>
<span id="el_orders_OrderDate">
<span<?= $Page->OrderDate->viewAttributes() ?>>
<?= $Page->OrderDate->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->RequiredDate->Visible) { // RequiredDate ?>
    <tr id="r_RequiredDate"<?= $Page->RequiredDate->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_orders_RequiredDate"><?= $Page->RequiredDate->caption() ?></span></td>
        <td data-name="RequiredDate"<?= $Page->RequiredDate->cellAttributes() ?>>
<span id="el_orders_RequiredDate">
<span<?= $Page->RequiredDate->viewAttributes() ?>>
<?= $Page->RequiredDate->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->ShippedDate->Visible) { // ShippedDate ?>
    <tr id="r_ShippedDate"<?= $Page->ShippedDate->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_orders_ShippedDate"><?= $Page->ShippedDate->caption() ?></span></td>
        <td data-name="ShippedDate"<?= $Page->ShippedDate->cellAttributes() ?>>
<span id="el_orders_ShippedDate">
<span<?= $Page->ShippedDate->viewAttributes() ?>>
<?= $Page->ShippedDate->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->ShipVia->Visible) { // ShipVia ?>
    <tr id="r_ShipVia"<?= $Page->ShipVia->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_orders_ShipVia"><?= $Page->ShipVia->caption() ?></span></td>
        <td data-name="ShipVia"<?= $Page->ShipVia->cellAttributes() ?>>
<span id="el_orders_ShipVia">
<span<?= $Page->ShipVia->viewAttributes() ?>>
<?= $Page->ShipVia->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Freight->Visible) { // Freight ?>
    <tr id="r_Freight"<?= $Page->Freight->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_orders_Freight"><?= $Page->Freight->caption() ?></span></td>
        <td data-name="Freight"<?= $Page->Freight->cellAttributes() ?>>
<span id="el_orders_Freight">
<span<?= $Page->Freight->viewAttributes() ?>>
<?= $Page->Freight->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->ShipName->Visible) { // ShipName ?>
    <tr id="r_ShipName"<?= $Page->ShipName->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_orders_ShipName"><?= $Page->ShipName->caption() ?></span></td>
        <td data-name="ShipName"<?= $Page->ShipName->cellAttributes() ?>>
<span id="el_orders_ShipName">
<span<?= $Page->ShipName->viewAttributes() ?>>
<?= $Page->ShipName->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->ShipAddress->Visible) { // ShipAddress ?>
    <tr id="r_ShipAddress"<?= $Page->ShipAddress->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_orders_ShipAddress"><?= $Page->ShipAddress->caption() ?></span></td>
        <td data-name="ShipAddress"<?= $Page->ShipAddress->cellAttributes() ?>>
<span id="el_orders_ShipAddress">
<span<?= $Page->ShipAddress->viewAttributes() ?>>
<?= $Page->ShipAddress->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->ShipCity->Visible) { // ShipCity ?>
    <tr id="r_ShipCity"<?= $Page->ShipCity->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_orders_ShipCity"><?= $Page->ShipCity->caption() ?></span></td>
        <td data-name="ShipCity"<?= $Page->ShipCity->cellAttributes() ?>>
<span id="el_orders_ShipCity">
<span<?= $Page->ShipCity->viewAttributes() ?>>
<?= $Page->ShipCity->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->ShipRegion->Visible) { // ShipRegion ?>
    <tr id="r_ShipRegion"<?= $Page->ShipRegion->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_orders_ShipRegion"><?= $Page->ShipRegion->caption() ?></span></td>
        <td data-name="ShipRegion"<?= $Page->ShipRegion->cellAttributes() ?>>
<span id="el_orders_ShipRegion">
<span<?= $Page->ShipRegion->viewAttributes() ?>>
<?= $Page->ShipRegion->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->ShipPostalCode->Visible) { // ShipPostalCode ?>
    <tr id="r_ShipPostalCode"<?= $Page->ShipPostalCode->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_orders_ShipPostalCode"><?= $Page->ShipPostalCode->caption() ?></span></td>
        <td data-name="ShipPostalCode"<?= $Page->ShipPostalCode->cellAttributes() ?>>
<span id="el_orders_ShipPostalCode">
<span<?= $Page->ShipPostalCode->viewAttributes() ?>>
<?= $Page->ShipPostalCode->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->ShipCountry->Visible) { // ShipCountry ?>
    <tr id="r_ShipCountry"<?= $Page->ShipCountry->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_orders_ShipCountry"><?= $Page->ShipCountry->caption() ?></span></td>
        <td data-name="ShipCountry"<?= $Page->ShipCountry->cellAttributes() ?>>
<span id="el_orders_ShipCountry">
<span<?= $Page->ShipCountry->viewAttributes() ?>>
<?= $Page->ShipCountry->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
</table>
<?php if ($Page->getCurrentDetailTable() != "") { ?>
<?php
    $Page->DetailPages->ValidKeys = explode(",", $Page->getCurrentDetailTable());
?>
<div class="ew-detail-pages"><!-- detail-pages -->
<div class="ew-nav<?= $Page->DetailPages->containerClasses() ?>" id="details_Page"><!-- tabs -->
    <ul class="<?= $Page->DetailPages->navClasses() ?>" role="tablist"><!-- .nav -->
<?php
    if (in_array("orderdetails", explode(",", $Page->getCurrentDetailTable())) && $orderdetails->DetailView) {
?>
        <li class="nav-item"><button class="<?= $Page->DetailPages->navLinkClasses("orderdetails") ?><?= $Page->DetailPages->activeClasses("orderdetails") ?>" data-bs-target="#tab_orderdetails" data-bs-toggle="tab" type="button" role="tab" aria-controls="tab_orderdetails" aria-selected="<?= JsonEncode($Page->DetailPages->isActive("orderdetails")) ?>"><?= $Language->tablePhrase("orderdetails", "TblCaption") ?>&nbsp;<?= str_replace("%c", Container("orderdetails")->Count, $Language->phrase("DetailCount")) ?></button></li>
<?php
    }
?>
<?php
    if (in_array("order_details_extended", explode(",", $Page->getCurrentDetailTable())) && $order_details_extended->DetailView) {
?>
        <li class="nav-item"><button class="<?= $Page->DetailPages->navLinkClasses("order_details_extended") ?><?= $Page->DetailPages->activeClasses("order_details_extended") ?>" data-bs-target="#tab_order_details_extended" data-bs-toggle="tab" type="button" role="tab" aria-controls="tab_order_details_extended" aria-selected="<?= JsonEncode($Page->DetailPages->isActive("order_details_extended")) ?>"><?= $Language->tablePhrase("order_details_extended", "TblCaption") ?>&nbsp;<?= str_replace("%c", Container("order_details_extended")->Count, $Language->phrase("DetailCount")) ?></button></li>
<?php
    }
?>
    </ul><!-- /.nav -->
    <div class="<?= $Page->DetailPages->tabContentClasses() ?>"><!-- .tab-content -->
<?php
    if (in_array("orderdetails", explode(",", $Page->getCurrentDetailTable())) && $orderdetails->DetailView) {
?>
        <div class="<?= $Page->DetailPages->tabPaneClasses("orderdetails") ?><?= $Page->DetailPages->activeClasses("orderdetails") ?>" id="tab_orderdetails" role="tabpanel"><!-- page* -->
<?php include_once "OrderdetailsGrid.php" ?>
        </div><!-- /page* -->
<?php } ?>
<?php
    if (in_array("order_details_extended", explode(",", $Page->getCurrentDetailTable())) && $order_details_extended->DetailView) {
?>
        <div class="<?= $Page->DetailPages->tabPaneClasses("order_details_extended") ?><?= $Page->DetailPages->activeClasses("order_details_extended") ?>" id="tab_order_details_extended" role="tabpanel"><!-- page* -->
<?php include_once "OrderDetailsExtendedGrid.php" ?>
        </div><!-- /page* -->
<?php } ?>
    </div><!-- /.tab-content -->
</div><!-- /tabs -->
</div><!-- /detail-pages -->
<?php } ?>
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
