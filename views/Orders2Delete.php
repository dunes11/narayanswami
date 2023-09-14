<?php

namespace PHPMaker2023\demo2023;

// Page object
$Orders2Delete = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { orders2: currentTable } });
var currentPageID = ew.PAGE_ID = "delete";
var currentForm;
var forders2delete;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("forders2delete")
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
<form name="forders2delete" id="forders2delete" class="ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="on">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="orders2">
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
        <th class="<?= $Page->OrderID->headerCellClass() ?>"><span id="elh_orders2_OrderID" class="orders2_OrderID"><?= $Page->OrderID->caption() ?></span></th>
<?php } ?>
<?php if ($Page->CustomerID->Visible) { // CustomerID ?>
        <th class="<?= $Page->CustomerID->headerCellClass() ?>"><span id="elh_orders2_CustomerID" class="orders2_CustomerID"><?= $Page->CustomerID->caption() ?></span></th>
<?php } ?>
<?php if ($Page->EmployeeID->Visible) { // EmployeeID ?>
        <th class="<?= $Page->EmployeeID->headerCellClass() ?>"><span id="elh_orders2_EmployeeID" class="orders2_EmployeeID"><?= $Page->EmployeeID->caption() ?></span></th>
<?php } ?>
<?php if ($Page->OrderDate->Visible) { // OrderDate ?>
        <th class="<?= $Page->OrderDate->headerCellClass() ?>"><span id="elh_orders2_OrderDate" class="orders2_OrderDate"><?= $Page->OrderDate->caption() ?></span></th>
<?php } ?>
<?php if ($Page->RequiredDate->Visible) { // RequiredDate ?>
        <th class="<?= $Page->RequiredDate->headerCellClass() ?>"><span id="elh_orders2_RequiredDate" class="orders2_RequiredDate"><?= $Page->RequiredDate->caption() ?></span></th>
<?php } ?>
<?php if ($Page->ShippedDate->Visible) { // ShippedDate ?>
        <th class="<?= $Page->ShippedDate->headerCellClass() ?>"><span id="elh_orders2_ShippedDate" class="orders2_ShippedDate"><?= $Page->ShippedDate->caption() ?></span></th>
<?php } ?>
<?php if ($Page->Freight->Visible) { // Freight ?>
        <th class="<?= $Page->Freight->headerCellClass() ?>"><span id="elh_orders2_Freight" class="orders2_Freight"><?= $Page->Freight->caption() ?></span></th>
<?php } ?>
<?php if ($Page->ShipAddress->Visible) { // ShipAddress ?>
        <th class="<?= $Page->ShipAddress->headerCellClass() ?>"><span id="elh_orders2_ShipAddress" class="orders2_ShipAddress"><?= $Page->ShipAddress->caption() ?></span></th>
<?php } ?>
<?php if ($Page->ShipCity->Visible) { // ShipCity ?>
        <th class="<?= $Page->ShipCity->headerCellClass() ?>"><span id="elh_orders2_ShipCity" class="orders2_ShipCity"><?= $Page->ShipCity->caption() ?></span></th>
<?php } ?>
<?php if ($Page->ShipCountry->Visible) { // ShipCountry ?>
        <th class="<?= $Page->ShipCountry->headerCellClass() ?>"><span id="elh_orders2_ShipCountry" class="orders2_ShipCountry"><?= $Page->ShipCountry->caption() ?></span></th>
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
<?php if ($Page->CustomerID->Visible) { // CustomerID ?>
        <td<?= $Page->CustomerID->cellAttributes() ?>>
<span id="">
<span<?= $Page->CustomerID->viewAttributes() ?>>
<?= $Page->CustomerID->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->EmployeeID->Visible) { // EmployeeID ?>
        <td<?= $Page->EmployeeID->cellAttributes() ?>>
<span id="">
<span<?= $Page->EmployeeID->viewAttributes() ?>>
<?= $Page->EmployeeID->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->OrderDate->Visible) { // OrderDate ?>
        <td<?= $Page->OrderDate->cellAttributes() ?>>
<span id="">
<span<?= $Page->OrderDate->viewAttributes() ?>>
<?= $Page->OrderDate->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->RequiredDate->Visible) { // RequiredDate ?>
        <td<?= $Page->RequiredDate->cellAttributes() ?>>
<span id="">
<span<?= $Page->RequiredDate->viewAttributes() ?>>
<?= $Page->RequiredDate->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->ShippedDate->Visible) { // ShippedDate ?>
        <td<?= $Page->ShippedDate->cellAttributes() ?>>
<span id="">
<span<?= $Page->ShippedDate->viewAttributes() ?>>
<?= $Page->ShippedDate->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->Freight->Visible) { // Freight ?>
        <td<?= $Page->Freight->cellAttributes() ?>>
<span id="">
<span<?= $Page->Freight->viewAttributes() ?>>
<?= $Page->Freight->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->ShipAddress->Visible) { // ShipAddress ?>
        <td<?= $Page->ShipAddress->cellAttributes() ?>>
<span id="">
<span<?= $Page->ShipAddress->viewAttributes() ?>>
<?= $Page->ShipAddress->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->ShipCity->Visible) { // ShipCity ?>
        <td<?= $Page->ShipCity->cellAttributes() ?>>
<span id="">
<span<?= $Page->ShipCity->viewAttributes() ?>>
<?= $Page->ShipCity->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->ShipCountry->Visible) { // ShipCountry ?>
        <td<?= $Page->ShipCountry->cellAttributes() ?>>
<span id="">
<span<?= $Page->ShipCountry->viewAttributes() ?>>
<?= $Page->ShipCountry->getViewValue() ?></span>
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
