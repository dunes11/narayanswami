<?php

namespace PHPMaker2023\demo2023;

// Page object
$Calendar1Delete = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { Calendar1: currentTable } });
var currentPageID = ew.PAGE_ID = "delete";
var currentForm;
var fCalendar1delete;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fCalendar1delete")
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
<form name="fCalendar1delete" id="fCalendar1delete" class="ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="on">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="Calendar1">
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
<?php if ($Page->Id->Visible) { // Id ?>
        <th class="<?= $Page->Id->headerCellClass() ?>"><span id="elh_Calendar1_Id" class="Calendar1_Id"><?= $Page->Id->caption() ?></span></th>
<?php } ?>
<?php if ($Page->_Title->Visible) { // Title ?>
        <th class="<?= $Page->_Title->headerCellClass() ?>"><span id="elh_Calendar1__Title" class="Calendar1__Title"><?= $Page->_Title->caption() ?></span></th>
<?php } ?>
<?php if ($Page->Start->Visible) { // Start ?>
        <th class="<?= $Page->Start->headerCellClass() ?>"><span id="elh_Calendar1_Start" class="Calendar1_Start"><?= $Page->Start->caption() ?></span></th>
<?php } ?>
<?php if ($Page->End->Visible) { // End ?>
        <th class="<?= $Page->End->headerCellClass() ?>"><span id="elh_Calendar1_End" class="Calendar1_End"><?= $Page->End->caption() ?></span></th>
<?php } ?>
<?php if ($Page->AllDay->Visible) { // AllDay ?>
        <th class="<?= $Page->AllDay->headerCellClass() ?>"><span id="elh_Calendar1_AllDay" class="Calendar1_AllDay"><?= $Page->AllDay->caption() ?></span></th>
<?php } ?>
<?php if ($Page->Description->Visible) { // Description ?>
        <th class="<?= $Page->Description->headerCellClass() ?>"><span id="elh_Calendar1_Description" class="Calendar1_Description"><?= $Page->Description->caption() ?></span></th>
<?php } ?>
<?php if ($Page->GroupId->Visible) { // GroupId ?>
        <th class="<?= $Page->GroupId->headerCellClass() ?>"><span id="elh_Calendar1_GroupId" class="Calendar1_GroupId"><?= $Page->GroupId->caption() ?></span></th>
<?php } ?>
<?php if ($Page->Url->Visible) { // Url ?>
        <th class="<?= $Page->Url->headerCellClass() ?>"><span id="elh_Calendar1_Url" class="Calendar1_Url"><?= $Page->Url->caption() ?></span></th>
<?php } ?>
<?php if ($Page->ClassNames->Visible) { // ClassNames ?>
        <th class="<?= $Page->ClassNames->headerCellClass() ?>"><span id="elh_Calendar1_ClassNames" class="Calendar1_ClassNames"><?= $Page->ClassNames->caption() ?></span></th>
<?php } ?>
<?php if ($Page->Display->Visible) { // Display ?>
        <th class="<?= $Page->Display->headerCellClass() ?>"><span id="elh_Calendar1_Display" class="Calendar1_Display"><?= $Page->Display->caption() ?></span></th>
<?php } ?>
<?php if ($Page->BackgroundColor->Visible) { // BackgroundColor ?>
        <th class="<?= $Page->BackgroundColor->headerCellClass() ?>"><span id="elh_Calendar1_BackgroundColor" class="Calendar1_BackgroundColor"><?= $Page->BackgroundColor->caption() ?></span></th>
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
<?php if ($Page->Id->Visible) { // Id ?>
        <td<?= $Page->Id->cellAttributes() ?>>
<span id="">
<span<?= $Page->Id->viewAttributes() ?>>
<?= $Page->Id->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->_Title->Visible) { // Title ?>
        <td<?= $Page->_Title->cellAttributes() ?>>
<span id="">
<span<?= $Page->_Title->viewAttributes() ?>>
<?= $Page->_Title->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->Start->Visible) { // Start ?>
        <td<?= $Page->Start->cellAttributes() ?>>
<span id="">
<span<?= $Page->Start->viewAttributes() ?>>
<?= $Page->Start->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->End->Visible) { // End ?>
        <td<?= $Page->End->cellAttributes() ?>>
<span id="">
<span<?= $Page->End->viewAttributes() ?>>
<?= $Page->End->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->AllDay->Visible) { // AllDay ?>
        <td<?= $Page->AllDay->cellAttributes() ?>>
<span id="">
<span<?= $Page->AllDay->viewAttributes() ?>>
<div class="form-check d-inline-block">
    <input type="checkbox" id="x_AllDay_<?= $Page->RowCount ?>" class="form-check-input" value="<?= $Page->AllDay->getViewValue() ?>" disabled<?php if (ConvertToBool($Page->AllDay->CurrentValue)) { ?> checked<?php } ?>>
    <label class="form-check-label" for="x_AllDay_<?= $Page->RowCount ?>"></label>
</div></span>
</span>
</td>
<?php } ?>
<?php if ($Page->Description->Visible) { // Description ?>
        <td<?= $Page->Description->cellAttributes() ?>>
<span id="">
<span<?= $Page->Description->viewAttributes() ?>>
<?= $Page->Description->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->GroupId->Visible) { // GroupId ?>
        <td<?= $Page->GroupId->cellAttributes() ?>>
<span id="">
<span<?= $Page->GroupId->viewAttributes() ?>>
<?= $Page->GroupId->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->Url->Visible) { // Url ?>
        <td<?= $Page->Url->cellAttributes() ?>>
<span id="">
<span<?= $Page->Url->viewAttributes() ?>>
<?= $Page->Url->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->ClassNames->Visible) { // ClassNames ?>
        <td<?= $Page->ClassNames->cellAttributes() ?>>
<span id="">
<span<?= $Page->ClassNames->viewAttributes() ?>>
<?= $Page->ClassNames->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->Display->Visible) { // Display ?>
        <td<?= $Page->Display->cellAttributes() ?>>
<span id="">
<span<?= $Page->Display->viewAttributes() ?>>
<?= $Page->Display->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->BackgroundColor->Visible) { // BackgroundColor ?>
        <td<?= $Page->BackgroundColor->cellAttributes() ?>>
<span id="">
<span<?= $Page->BackgroundColor->viewAttributes() ?>>
<?= $Page->BackgroundColor->getViewValue() ?></span>
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
