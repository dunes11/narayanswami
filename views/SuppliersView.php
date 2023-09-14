<?php

namespace PHPMaker2023\demo2023;

// Page object
$SuppliersView = &$Page;
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
<form name="fsuppliersview" id="fsuppliersview" class="ew-form ew-view-form overlay-wrapper" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="on">
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { suppliers: currentTable } });
var currentPageID = ew.PAGE_ID = "view";
var currentForm;
var fsuppliersview;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fsuppliersview")
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
<input type="hidden" name="t" value="suppliers">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="<?= $Page->TableClass ?>">
<?php if ($Page->SupplierID->Visible) { // SupplierID ?>
    <tr id="r_SupplierID"<?= $Page->SupplierID->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_suppliers_SupplierID"><?= $Page->SupplierID->caption() ?></span></td>
        <td data-name="SupplierID"<?= $Page->SupplierID->cellAttributes() ?>>
<span id="el_suppliers_SupplierID">
<span<?= $Page->SupplierID->viewAttributes() ?>>
<?= $Page->SupplierID->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->CompanyName->Visible) { // CompanyName ?>
    <tr id="r_CompanyName"<?= $Page->CompanyName->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_suppliers_CompanyName"><?= $Page->CompanyName->caption() ?></span></td>
        <td data-name="CompanyName"<?= $Page->CompanyName->cellAttributes() ?>>
<span id="el_suppliers_CompanyName">
<span<?= $Page->CompanyName->viewAttributes() ?>>
<?= $Page->CompanyName->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->ContactName->Visible) { // ContactName ?>
    <tr id="r_ContactName"<?= $Page->ContactName->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_suppliers_ContactName"><?= $Page->ContactName->caption() ?></span></td>
        <td data-name="ContactName"<?= $Page->ContactName->cellAttributes() ?>>
<span id="el_suppliers_ContactName">
<span<?= $Page->ContactName->viewAttributes() ?>>
<?= $Page->ContactName->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->ContactTitle->Visible) { // ContactTitle ?>
    <tr id="r_ContactTitle"<?= $Page->ContactTitle->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_suppliers_ContactTitle"><?= $Page->ContactTitle->caption() ?></span></td>
        <td data-name="ContactTitle"<?= $Page->ContactTitle->cellAttributes() ?>>
<span id="el_suppliers_ContactTitle">
<span<?= $Page->ContactTitle->viewAttributes() ?>>
<?= $Page->ContactTitle->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Address->Visible) { // Address ?>
    <tr id="r_Address"<?= $Page->Address->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_suppliers_Address"><?= $Page->Address->caption() ?></span></td>
        <td data-name="Address"<?= $Page->Address->cellAttributes() ?>>
<span id="el_suppliers_Address">
<span<?= $Page->Address->viewAttributes() ?>>
<?= $Page->Address->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->City->Visible) { // City ?>
    <tr id="r_City"<?= $Page->City->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_suppliers_City"><?= $Page->City->caption() ?></span></td>
        <td data-name="City"<?= $Page->City->cellAttributes() ?>>
<span id="el_suppliers_City">
<span<?= $Page->City->viewAttributes() ?>>
<?= $Page->City->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Region->Visible) { // Region ?>
    <tr id="r_Region"<?= $Page->Region->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_suppliers_Region"><?= $Page->Region->caption() ?></span></td>
        <td data-name="Region"<?= $Page->Region->cellAttributes() ?>>
<span id="el_suppliers_Region">
<span<?= $Page->Region->viewAttributes() ?>>
<?= $Page->Region->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->PostalCode->Visible) { // PostalCode ?>
    <tr id="r_PostalCode"<?= $Page->PostalCode->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_suppliers_PostalCode"><?= $Page->PostalCode->caption() ?></span></td>
        <td data-name="PostalCode"<?= $Page->PostalCode->cellAttributes() ?>>
<span id="el_suppliers_PostalCode">
<span<?= $Page->PostalCode->viewAttributes() ?>>
<?= $Page->PostalCode->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Country->Visible) { // Country ?>
    <tr id="r_Country"<?= $Page->Country->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_suppliers_Country"><?= $Page->Country->caption() ?></span></td>
        <td data-name="Country"<?= $Page->Country->cellAttributes() ?>>
<span id="el_suppliers_Country">
<span<?= $Page->Country->viewAttributes() ?>>
<?= $Page->Country->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Phone->Visible) { // Phone ?>
    <tr id="r_Phone"<?= $Page->Phone->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_suppliers_Phone"><?= $Page->Phone->caption() ?></span></td>
        <td data-name="Phone"<?= $Page->Phone->cellAttributes() ?>>
<span id="el_suppliers_Phone">
<span<?= $Page->Phone->viewAttributes() ?>>
<?= $Page->Phone->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Fax->Visible) { // Fax ?>
    <tr id="r_Fax"<?= $Page->Fax->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_suppliers_Fax"><?= $Page->Fax->caption() ?></span></td>
        <td data-name="Fax"<?= $Page->Fax->cellAttributes() ?>>
<span id="el_suppliers_Fax">
<span<?= $Page->Fax->viewAttributes() ?>>
<?= $Page->Fax->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->HomePage->Visible) { // HomePage ?>
    <tr id="r_HomePage"<?= $Page->HomePage->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_suppliers_HomePage"><?= $Page->HomePage->caption() ?></span></td>
        <td data-name="HomePage"<?= $Page->HomePage->cellAttributes() ?>>
<span id="el_suppliers_HomePage">
<span<?= $Page->HomePage->viewAttributes() ?>>
<?= $Page->HomePage->getViewValue() ?></span>
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
