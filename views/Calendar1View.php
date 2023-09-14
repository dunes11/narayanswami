<?php

namespace PHPMaker2023\demo2023;

// Page object
$Calendar1View = &$Page;
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
<form name="fCalendar1view" id="fCalendar1view" class="ew-form ew-view-form overlay-wrapper" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="on">
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { Calendar1: currentTable } });
var currentPageID = ew.PAGE_ID = "view";
var currentForm;
var fCalendar1view;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fCalendar1view")
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
<input type="hidden" name="t" value="Calendar1">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="<?= $Page->TableClass ?>">
<?php if ($Page->Id->Visible) { // Id ?>
    <tr id="r_Id"<?= $Page->Id->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_Calendar1_Id"><?= $Page->Id->caption() ?></span></td>
        <td data-name="Id"<?= $Page->Id->cellAttributes() ?>>
<span id="el_Calendar1_Id">
<span<?= $Page->Id->viewAttributes() ?>>
<?= $Page->Id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->_Title->Visible) { // Title ?>
    <tr id="r__Title"<?= $Page->_Title->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_Calendar1__Title"><?= $Page->_Title->caption() ?></span></td>
        <td data-name="_Title"<?= $Page->_Title->cellAttributes() ?>>
<span id="el_Calendar1__Title">
<span<?= $Page->_Title->viewAttributes() ?>>
<?= $Page->_Title->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Start->Visible) { // Start ?>
    <tr id="r_Start"<?= $Page->Start->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_Calendar1_Start"><?= $Page->Start->caption() ?></span></td>
        <td data-name="Start"<?= $Page->Start->cellAttributes() ?>>
<span id="el_Calendar1_Start">
<span<?= $Page->Start->viewAttributes() ?>>
<?= $Page->Start->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->End->Visible) { // End ?>
    <tr id="r_End"<?= $Page->End->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_Calendar1_End"><?= $Page->End->caption() ?></span></td>
        <td data-name="End"<?= $Page->End->cellAttributes() ?>>
<span id="el_Calendar1_End">
<span<?= $Page->End->viewAttributes() ?>>
<?= $Page->End->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->AllDay->Visible) { // AllDay ?>
    <tr id="r_AllDay"<?= $Page->AllDay->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_Calendar1_AllDay"><?= $Page->AllDay->caption() ?></span></td>
        <td data-name="AllDay"<?= $Page->AllDay->cellAttributes() ?>>
<span id="el_Calendar1_AllDay">
<span<?= $Page->AllDay->viewAttributes() ?>>
<div class="form-check d-inline-block">
    <input type="checkbox" id="x_AllDay_<?= $Page->RowCount ?>" class="form-check-input" value="<?= $Page->AllDay->getViewValue() ?>" disabled<?php if (ConvertToBool($Page->AllDay->CurrentValue)) { ?> checked<?php } ?>>
    <label class="form-check-label" for="x_AllDay_<?= $Page->RowCount ?>"></label>
</div></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Description->Visible) { // Description ?>
    <tr id="r_Description"<?= $Page->Description->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_Calendar1_Description"><?= $Page->Description->caption() ?></span></td>
        <td data-name="Description"<?= $Page->Description->cellAttributes() ?>>
<span id="el_Calendar1_Description">
<span<?= $Page->Description->viewAttributes() ?>>
<?= $Page->Description->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->GroupId->Visible) { // GroupId ?>
    <tr id="r_GroupId"<?= $Page->GroupId->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_Calendar1_GroupId"><?= $Page->GroupId->caption() ?></span></td>
        <td data-name="GroupId"<?= $Page->GroupId->cellAttributes() ?>>
<span id="el_Calendar1_GroupId">
<span<?= $Page->GroupId->viewAttributes() ?>>
<?= $Page->GroupId->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Url->Visible) { // Url ?>
    <tr id="r_Url"<?= $Page->Url->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_Calendar1_Url"><?= $Page->Url->caption() ?></span></td>
        <td data-name="Url"<?= $Page->Url->cellAttributes() ?>>
<span id="el_Calendar1_Url">
<span<?= $Page->Url->viewAttributes() ?>>
<?= $Page->Url->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->ClassNames->Visible) { // ClassNames ?>
    <tr id="r_ClassNames"<?= $Page->ClassNames->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_Calendar1_ClassNames"><?= $Page->ClassNames->caption() ?></span></td>
        <td data-name="ClassNames"<?= $Page->ClassNames->cellAttributes() ?>>
<span id="el_Calendar1_ClassNames">
<span<?= $Page->ClassNames->viewAttributes() ?>>
<?= $Page->ClassNames->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Display->Visible) { // Display ?>
    <tr id="r_Display"<?= $Page->Display->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_Calendar1_Display"><?= $Page->Display->caption() ?></span></td>
        <td data-name="Display"<?= $Page->Display->cellAttributes() ?>>
<span id="el_Calendar1_Display">
<span<?= $Page->Display->viewAttributes() ?>>
<?= $Page->Display->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->BackgroundColor->Visible) { // BackgroundColor ?>
    <tr id="r_BackgroundColor"<?= $Page->BackgroundColor->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_Calendar1_BackgroundColor"><?= $Page->BackgroundColor->caption() ?></span></td>
        <td data-name="BackgroundColor"<?= $Page->BackgroundColor->cellAttributes() ?>>
<span id="el_Calendar1_BackgroundColor">
<span<?= $Page->BackgroundColor->viewAttributes() ?>>
<?= $Page->BackgroundColor->getViewValue() ?></span>
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
