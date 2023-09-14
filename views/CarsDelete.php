<?php

namespace PHPMaker2023\demo2023;

// Page object
$CarsDelete = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { cars: currentTable } });
var currentPageID = ew.PAGE_ID = "delete";
var currentForm;
var fcarsdelete;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fcarsdelete")
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
<form name="fcarsdelete" id="fcarsdelete" class="ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="on">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="cars">
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
<?php if ($Page->ID->Visible) { // ID ?>
        <th class="<?= $Page->ID->headerCellClass() ?>"><span id="elh_cars_ID" class="cars_ID"><?= $Page->ID->caption() ?></span></th>
<?php } ?>
<?php if ($Page->Trademark->Visible) { // Trademark ?>
        <th class="<?= $Page->Trademark->headerCellClass() ?>"><span id="elh_cars_Trademark" class="cars_Trademark"><?= $Page->Trademark->caption() ?></span></th>
<?php } ?>
<?php if ($Page->Model->Visible) { // Model ?>
        <th class="<?= $Page->Model->headerCellClass() ?>"><span id="elh_cars_Model" class="cars_Model"><?= $Page->Model->caption() ?></span></th>
<?php } ?>
<?php if ($Page->HP->Visible) { // HP ?>
        <th class="<?= $Page->HP->headerCellClass() ?>"><span id="elh_cars_HP" class="cars_HP"><?= $Page->HP->caption() ?></span></th>
<?php } ?>
<?php if ($Page->Cylinders->Visible) { // Cylinders ?>
        <th class="<?= $Page->Cylinders->headerCellClass() ?>"><span id="elh_cars_Cylinders" class="cars_Cylinders"><?= $Page->Cylinders->caption() ?></span></th>
<?php } ?>
<?php if ($Page->Price->Visible) { // Price ?>
        <th class="<?= $Page->Price->headerCellClass() ?>"><span id="elh_cars_Price" class="cars_Price"><?= $Page->Price->caption() ?></span></th>
<?php } ?>
<?php if ($Page->Picture->Visible) { // Picture ?>
        <th class="<?= $Page->Picture->headerCellClass() ?>"><span id="elh_cars_Picture" class="cars_Picture"><?= $Page->Picture->caption() ?></span></th>
<?php } ?>
<?php if ($Page->Doors->Visible) { // Doors ?>
        <th class="<?= $Page->Doors->headerCellClass() ?>"><span id="elh_cars_Doors" class="cars_Doors"><?= $Page->Doors->caption() ?></span></th>
<?php } ?>
<?php if ($Page->Torque->Visible) { // Torque ?>
        <th class="<?= $Page->Torque->headerCellClass() ?>"><span id="elh_cars_Torque" class="cars_Torque"><?= $Page->Torque->caption() ?></span></th>
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
<?php if ($Page->ID->Visible) { // ID ?>
        <td<?= $Page->ID->cellAttributes() ?>>
<span id="">
<span<?= $Page->ID->viewAttributes() ?>>
<?= $Page->ID->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->Trademark->Visible) { // Trademark ?>
        <td<?= $Page->Trademark->cellAttributes() ?>>
<span id="">
<span<?= $Page->Trademark->viewAttributes() ?>>
<?= $Page->Trademark->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->Model->Visible) { // Model ?>
        <td<?= $Page->Model->cellAttributes() ?>>
<span id="">
<span<?= $Page->Model->viewAttributes() ?>>
<?php if (!EmptyString($Page->Model->TooltipValue) && $Page->Model->linkAttributes() != "") { ?>
<a<?= $Page->Model->linkAttributes() ?>><?= $Page->Model->getViewValue() ?></a>
<?php } else { ?>
<?= $Page->Model->getViewValue() ?>
<?php } ?>
<span id="tt_cars_x_Model" class="d-none">
<?= $Page->Model->TooltipValue ?>
</span></span>
</span>
</td>
<?php } ?>
<?php if ($Page->HP->Visible) { // HP ?>
        <td<?= $Page->HP->cellAttributes() ?>>
<span id="">
<span<?= $Page->HP->viewAttributes() ?>>
<?= $Page->HP->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->Cylinders->Visible) { // Cylinders ?>
        <td<?= $Page->Cylinders->cellAttributes() ?>>
<span id="">
<span<?= $Page->Cylinders->viewAttributes() ?>>
<?= $Page->Cylinders->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->Price->Visible) { // Price ?>
        <td<?= $Page->Price->cellAttributes() ?>>
<span id="">
<span<?= $Page->Price->viewAttributes() ?>>
<?= $Page->Price->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->Picture->Visible) { // Picture ?>
        <td<?= $Page->Picture->cellAttributes() ?>>
<span id="">
<span>
<?= GetFileViewTag($Page->Picture, $Page->Picture->getViewValue(), false) ?>
</span>
</span>
</td>
<?php } ?>
<?php if ($Page->Doors->Visible) { // Doors ?>
        <td<?= $Page->Doors->cellAttributes() ?>>
<span id="">
<span<?= $Page->Doors->viewAttributes() ?>>
<?= $Page->Doors->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->Torque->Visible) { // Torque ?>
        <td<?= $Page->Torque->cellAttributes() ?>>
<span id="">
<span<?= $Page->Torque->viewAttributes() ?>>
<?= $Page->Torque->getViewValue() ?></span>
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
