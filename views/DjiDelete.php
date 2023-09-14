<?php

namespace PHPMaker2023\demo2023;

// Page object
$DjiDelete = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { dji: currentTable } });
var currentPageID = ew.PAGE_ID = "delete";
var currentForm;
var fdjidelete;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fdjidelete")
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
<form name="fdjidelete" id="fdjidelete" class="ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="on">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="dji">
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
        <th class="<?= $Page->ID->headerCellClass() ?>"><span id="elh_dji_ID" class="dji_ID"><?= $Page->ID->caption() ?></span></th>
<?php } ?>
<?php if ($Page->Date->Visible) { // Date ?>
        <th class="<?= $Page->Date->headerCellClass() ?>"><span id="elh_dji_Date" class="dji_Date"><?= $Page->Date->caption() ?></span></th>
<?php } ?>
<?php if ($Page->Open->Visible) { // Open ?>
        <th class="<?= $Page->Open->headerCellClass() ?>"><span id="elh_dji_Open" class="dji_Open"><?= $Page->Open->caption() ?></span></th>
<?php } ?>
<?php if ($Page->High->Visible) { // High ?>
        <th class="<?= $Page->High->headerCellClass() ?>"><span id="elh_dji_High" class="dji_High"><?= $Page->High->caption() ?></span></th>
<?php } ?>
<?php if ($Page->Low->Visible) { // Low ?>
        <th class="<?= $Page->Low->headerCellClass() ?>"><span id="elh_dji_Low" class="dji_Low"><?= $Page->Low->caption() ?></span></th>
<?php } ?>
<?php if ($Page->Close->Visible) { // Close ?>
        <th class="<?= $Page->Close->headerCellClass() ?>"><span id="elh_dji_Close" class="dji_Close"><?= $Page->Close->caption() ?></span></th>
<?php } ?>
<?php if ($Page->Volume->Visible) { // Volume ?>
        <th class="<?= $Page->Volume->headerCellClass() ?>"><span id="elh_dji_Volume" class="dji_Volume"><?= $Page->Volume->caption() ?></span></th>
<?php } ?>
<?php if ($Page->AdjClose->Visible) { // Adj Close ?>
        <th class="<?= $Page->AdjClose->headerCellClass() ?>"><span id="elh_dji_AdjClose" class="dji_AdjClose"><?= $Page->AdjClose->caption() ?></span></th>
<?php } ?>
<?php if ($Page->Name->Visible) { // Name ?>
        <th class="<?= $Page->Name->headerCellClass() ?>"><span id="elh_dji_Name" class="dji_Name"><?= $Page->Name->caption() ?></span></th>
<?php } ?>
<?php if ($Page->Name2->Visible) { // Name2 ?>
        <th class="<?= $Page->Name2->headerCellClass() ?>"><span id="elh_dji_Name2" class="dji_Name2"><?= $Page->Name2->caption() ?></span></th>
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
<?php if ($Page->Date->Visible) { // Date ?>
        <td<?= $Page->Date->cellAttributes() ?>>
<span id="">
<span<?= $Page->Date->viewAttributes() ?>>
<?= $Page->Date->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->Open->Visible) { // Open ?>
        <td<?= $Page->Open->cellAttributes() ?>>
<span id="">
<span<?= $Page->Open->viewAttributes() ?>>
<?= $Page->Open->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->High->Visible) { // High ?>
        <td<?= $Page->High->cellAttributes() ?>>
<span id="">
<span<?= $Page->High->viewAttributes() ?>>
<?= $Page->High->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->Low->Visible) { // Low ?>
        <td<?= $Page->Low->cellAttributes() ?>>
<span id="">
<span<?= $Page->Low->viewAttributes() ?>>
<?= $Page->Low->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->Close->Visible) { // Close ?>
        <td<?= $Page->Close->cellAttributes() ?>>
<span id="">
<span<?= $Page->Close->viewAttributes() ?>>
<?= $Page->Close->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->Volume->Visible) { // Volume ?>
        <td<?= $Page->Volume->cellAttributes() ?>>
<span id="">
<span<?= $Page->Volume->viewAttributes() ?>>
<?= $Page->Volume->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->AdjClose->Visible) { // Adj Close ?>
        <td<?= $Page->AdjClose->cellAttributes() ?>>
<span id="">
<span<?= $Page->AdjClose->viewAttributes() ?>>
<?= $Page->AdjClose->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->Name->Visible) { // Name ?>
        <td<?= $Page->Name->cellAttributes() ?>>
<span id="">
<span<?= $Page->Name->viewAttributes() ?>>
<?= $Page->Name->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->Name2->Visible) { // Name2 ?>
        <td<?= $Page->Name2->cellAttributes() ?>>
<span id="">
<span<?= $Page->Name2->viewAttributes() ?>>
<?= $Page->Name2->getViewValue() ?></span>
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
