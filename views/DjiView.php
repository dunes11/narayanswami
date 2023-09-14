<?php

namespace PHPMaker2023\demo2023;

// Page object
$DjiView = &$Page;
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
<form name="fdjiview" id="fdjiview" class="ew-form ew-view-form overlay-wrapper" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="on">
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { dji: currentTable } });
var currentPageID = ew.PAGE_ID = "view";
var currentForm;
var fdjiview;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fdjiview")
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
<input type="hidden" name="t" value="dji">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="<?= $Page->TableClass ?>">
<?php if ($Page->ID->Visible) { // ID ?>
    <tr id="r_ID"<?= $Page->ID->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_dji_ID"><?= $Page->ID->caption() ?></span></td>
        <td data-name="ID"<?= $Page->ID->cellAttributes() ?>>
<span id="el_dji_ID">
<span<?= $Page->ID->viewAttributes() ?>>
<?= $Page->ID->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Date->Visible) { // Date ?>
    <tr id="r_Date"<?= $Page->Date->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_dji_Date"><?= $Page->Date->caption() ?></span></td>
        <td data-name="Date"<?= $Page->Date->cellAttributes() ?>>
<span id="el_dji_Date">
<span<?= $Page->Date->viewAttributes() ?>>
<?= $Page->Date->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Open->Visible) { // Open ?>
    <tr id="r_Open"<?= $Page->Open->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_dji_Open"><?= $Page->Open->caption() ?></span></td>
        <td data-name="Open"<?= $Page->Open->cellAttributes() ?>>
<span id="el_dji_Open">
<span<?= $Page->Open->viewAttributes() ?>>
<?= $Page->Open->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->High->Visible) { // High ?>
    <tr id="r_High"<?= $Page->High->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_dji_High"><?= $Page->High->caption() ?></span></td>
        <td data-name="High"<?= $Page->High->cellAttributes() ?>>
<span id="el_dji_High">
<span<?= $Page->High->viewAttributes() ?>>
<?= $Page->High->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Low->Visible) { // Low ?>
    <tr id="r_Low"<?= $Page->Low->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_dji_Low"><?= $Page->Low->caption() ?></span></td>
        <td data-name="Low"<?= $Page->Low->cellAttributes() ?>>
<span id="el_dji_Low">
<span<?= $Page->Low->viewAttributes() ?>>
<?= $Page->Low->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Close->Visible) { // Close ?>
    <tr id="r_Close"<?= $Page->Close->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_dji_Close"><?= $Page->Close->caption() ?></span></td>
        <td data-name="Close"<?= $Page->Close->cellAttributes() ?>>
<span id="el_dji_Close">
<span<?= $Page->Close->viewAttributes() ?>>
<?= $Page->Close->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Volume->Visible) { // Volume ?>
    <tr id="r_Volume"<?= $Page->Volume->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_dji_Volume"><?= $Page->Volume->caption() ?></span></td>
        <td data-name="Volume"<?= $Page->Volume->cellAttributes() ?>>
<span id="el_dji_Volume">
<span<?= $Page->Volume->viewAttributes() ?>>
<?= $Page->Volume->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->AdjClose->Visible) { // Adj Close ?>
    <tr id="r_AdjClose"<?= $Page->AdjClose->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_dji_AdjClose"><?= $Page->AdjClose->caption() ?></span></td>
        <td data-name="AdjClose"<?= $Page->AdjClose->cellAttributes() ?>>
<span id="el_dji_AdjClose">
<span<?= $Page->AdjClose->viewAttributes() ?>>
<?= $Page->AdjClose->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Name->Visible) { // Name ?>
    <tr id="r_Name"<?= $Page->Name->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_dji_Name"><?= $Page->Name->caption() ?></span></td>
        <td data-name="Name"<?= $Page->Name->cellAttributes() ?>>
<span id="el_dji_Name">
<span<?= $Page->Name->viewAttributes() ?>>
<?= $Page->Name->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Name2->Visible) { // Name2 ?>
    <tr id="r_Name2"<?= $Page->Name2->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_dji_Name2"><?= $Page->Name2->caption() ?></span></td>
        <td data-name="Name2"<?= $Page->Name2->cellAttributes() ?>>
<span id="el_dji_Name2">
<span<?= $Page->Name2->viewAttributes() ?>>
<?= $Page->Name2->getViewValue() ?></span>
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
