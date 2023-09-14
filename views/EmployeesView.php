<?php

namespace PHPMaker2023\demo2023;

// Page object
$EmployeesView = &$Page;
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
<form name="femployeesview" id="femployeesview" class="ew-form ew-view-form overlay-wrapper" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="on">
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { employees: currentTable } });
var currentPageID = ew.PAGE_ID = "view";
var currentForm;
var femployeesview;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("femployeesview")
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
<input type="hidden" name="t" value="employees">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="<?= $Page->TableClass ?>">
<?php if ($Page->EmployeeID->Visible) { // EmployeeID ?>
    <tr id="r_EmployeeID"<?= $Page->EmployeeID->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_employees_EmployeeID"><?= $Page->EmployeeID->caption() ?></span></td>
        <td data-name="EmployeeID"<?= $Page->EmployeeID->cellAttributes() ?>>
<span id="el_employees_EmployeeID">
<span<?= $Page->EmployeeID->viewAttributes() ?>>
<?= $Page->EmployeeID->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->_Username->Visible) { // Username ?>
    <tr id="r__Username"<?= $Page->_Username->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_employees__Username"><?= $Page->_Username->caption() ?></span></td>
        <td data-name="_Username"<?= $Page->_Username->cellAttributes() ?>>
<span id="el_employees__Username">
<span<?= $Page->_Username->viewAttributes() ?>>
<?= $Page->_Username->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->LastName->Visible) { // LastName ?>
    <tr id="r_LastName"<?= $Page->LastName->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_employees_LastName"><?= $Page->LastName->caption() ?></span></td>
        <td data-name="LastName"<?= $Page->LastName->cellAttributes() ?>>
<span id="el_employees_LastName">
<span<?= $Page->LastName->viewAttributes() ?>>
<?= $Page->LastName->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->FirstName->Visible) { // FirstName ?>
    <tr id="r_FirstName"<?= $Page->FirstName->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_employees_FirstName"><?= $Page->FirstName->caption() ?></span></td>
        <td data-name="FirstName"<?= $Page->FirstName->cellAttributes() ?>>
<span id="el_employees_FirstName">
<span<?= $Page->FirstName->viewAttributes() ?>>
<?= $Page->FirstName->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->_Title->Visible) { // Title ?>
    <tr id="r__Title"<?= $Page->_Title->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_employees__Title"><?= $Page->_Title->caption() ?></span></td>
        <td data-name="_Title"<?= $Page->_Title->cellAttributes() ?>>
<span id="el_employees__Title">
<span<?= $Page->_Title->viewAttributes() ?>>
<?= $Page->_Title->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->TitleOfCourtesy->Visible) { // TitleOfCourtesy ?>
    <tr id="r_TitleOfCourtesy"<?= $Page->TitleOfCourtesy->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_employees_TitleOfCourtesy"><?= $Page->TitleOfCourtesy->caption() ?></span></td>
        <td data-name="TitleOfCourtesy"<?= $Page->TitleOfCourtesy->cellAttributes() ?>>
<span id="el_employees_TitleOfCourtesy">
<span<?= $Page->TitleOfCourtesy->viewAttributes() ?>>
<?= $Page->TitleOfCourtesy->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->BirthDate->Visible) { // BirthDate ?>
    <tr id="r_BirthDate"<?= $Page->BirthDate->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_employees_BirthDate"><?= $Page->BirthDate->caption() ?></span></td>
        <td data-name="BirthDate"<?= $Page->BirthDate->cellAttributes() ?>>
<span id="el_employees_BirthDate">
<span<?= $Page->BirthDate->viewAttributes() ?>>
<?= $Page->BirthDate->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->HireDate->Visible) { // HireDate ?>
    <tr id="r_HireDate"<?= $Page->HireDate->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_employees_HireDate"><?= $Page->HireDate->caption() ?></span></td>
        <td data-name="HireDate"<?= $Page->HireDate->cellAttributes() ?>>
<span id="el_employees_HireDate">
<span<?= $Page->HireDate->viewAttributes() ?>>
<?= $Page->HireDate->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Address->Visible) { // Address ?>
    <tr id="r_Address"<?= $Page->Address->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_employees_Address"><?= $Page->Address->caption() ?></span></td>
        <td data-name="Address"<?= $Page->Address->cellAttributes() ?>>
<span id="el_employees_Address">
<span<?= $Page->Address->viewAttributes() ?>>
<?= $Page->Address->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->City->Visible) { // City ?>
    <tr id="r_City"<?= $Page->City->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_employees_City"><?= $Page->City->caption() ?></span></td>
        <td data-name="City"<?= $Page->City->cellAttributes() ?>>
<span id="el_employees_City">
<span<?= $Page->City->viewAttributes() ?>>
<?= $Page->City->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Region->Visible) { // Region ?>
    <tr id="r_Region"<?= $Page->Region->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_employees_Region"><?= $Page->Region->caption() ?></span></td>
        <td data-name="Region"<?= $Page->Region->cellAttributes() ?>>
<span id="el_employees_Region">
<span<?= $Page->Region->viewAttributes() ?>>
<?= $Page->Region->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->PostalCode->Visible) { // PostalCode ?>
    <tr id="r_PostalCode"<?= $Page->PostalCode->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_employees_PostalCode"><?= $Page->PostalCode->caption() ?></span></td>
        <td data-name="PostalCode"<?= $Page->PostalCode->cellAttributes() ?>>
<span id="el_employees_PostalCode">
<span<?= $Page->PostalCode->viewAttributes() ?>>
<?= $Page->PostalCode->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Country->Visible) { // Country ?>
    <tr id="r_Country"<?= $Page->Country->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_employees_Country"><?= $Page->Country->caption() ?></span></td>
        <td data-name="Country"<?= $Page->Country->cellAttributes() ?>>
<span id="el_employees_Country">
<span<?= $Page->Country->viewAttributes() ?>>
<?= $Page->Country->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->HomePhone->Visible) { // HomePhone ?>
    <tr id="r_HomePhone"<?= $Page->HomePhone->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_employees_HomePhone"><?= $Page->HomePhone->caption() ?></span></td>
        <td data-name="HomePhone"<?= $Page->HomePhone->cellAttributes() ?>>
<span id="el_employees_HomePhone">
<span<?= $Page->HomePhone->viewAttributes() ?>>
<?= $Page->HomePhone->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Extension->Visible) { // Extension ?>
    <tr id="r_Extension"<?= $Page->Extension->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_employees_Extension"><?= $Page->Extension->caption() ?></span></td>
        <td data-name="Extension"<?= $Page->Extension->cellAttributes() ?>>
<span id="el_employees_Extension">
<span<?= $Page->Extension->viewAttributes() ?>>
<?= $Page->Extension->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Photo->Visible) { // Photo ?>
    <tr id="r_Photo"<?= $Page->Photo->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_employees_Photo"><?= $Page->Photo->caption() ?></span></td>
        <td data-name="Photo"<?= $Page->Photo->cellAttributes() ?>>
<span id="el_employees_Photo">
<span>
<?= GetFileViewTag($Page->Photo, $Page->Photo->getViewValue(), false) ?>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Notes->Visible) { // Notes ?>
    <tr id="r_Notes"<?= $Page->Notes->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_employees_Notes"><?= $Page->Notes->caption() ?></span></td>
        <td data-name="Notes"<?= $Page->Notes->cellAttributes() ?>>
<span id="el_employees_Notes">
<span<?= $Page->Notes->viewAttributes() ?>>
<?= $Page->Notes->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->ReportsTo->Visible) { // ReportsTo ?>
    <tr id="r_ReportsTo"<?= $Page->ReportsTo->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_employees_ReportsTo"><?= $Page->ReportsTo->caption() ?></span></td>
        <td data-name="ReportsTo"<?= $Page->ReportsTo->cellAttributes() ?>>
<span id="el_employees_ReportsTo">
<span<?= $Page->ReportsTo->viewAttributes() ?>>
<?= $Page->ReportsTo->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->_Password->Visible) { // Password ?>
    <tr id="r__Password"<?= $Page->_Password->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_employees__Password"><?= $Page->_Password->caption() ?></span></td>
        <td data-name="_Password"<?= $Page->_Password->cellAttributes() ?>>
<span id="el_employees__Password">
<span<?= $Page->_Password->viewAttributes() ?>>
<?= $Page->_Password->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->_UserLevel->Visible) { // UserLevel ?>
    <tr id="r__UserLevel"<?= $Page->_UserLevel->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_employees__UserLevel"><?= $Page->_UserLevel->caption() ?></span></td>
        <td data-name="_UserLevel"<?= $Page->_UserLevel->cellAttributes() ?>>
<span id="el_employees__UserLevel">
<span<?= $Page->_UserLevel->viewAttributes() ?>>
<?= $Page->_UserLevel->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->_Email->Visible) { // Email ?>
    <tr id="r__Email"<?= $Page->_Email->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_employees__Email"><?= $Page->_Email->caption() ?></span></td>
        <td data-name="_Email"<?= $Page->_Email->cellAttributes() ?>>
<span id="el_employees__Email">
<span<?= $Page->_Email->viewAttributes() ?>>
<?= $Page->_Email->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Activated->Visible) { // Activated ?>
    <tr id="r_Activated"<?= $Page->Activated->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_employees_Activated"><?= $Page->Activated->caption() ?></span></td>
        <td data-name="Activated"<?= $Page->Activated->cellAttributes() ?>>
<span id="el_employees_Activated">
<span<?= $Page->Activated->viewAttributes() ?>>
<div class="form-check d-inline-block">
    <input type="checkbox" id="x_Activated_<?= $Page->RowCount ?>" class="form-check-input" value="<?= $Page->Activated->getViewValue() ?>" disabled<?php if (ConvertToBool($Page->Activated->CurrentValue)) { ?> checked<?php } ?>>
    <label class="form-check-label" for="x_Activated_<?= $Page->RowCount ?>"></label>
</div></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->_Profile->Visible) { // Profile ?>
    <tr id="r__Profile"<?= $Page->_Profile->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_employees__Profile"><?= $Page->_Profile->caption() ?></span></td>
        <td data-name="_Profile"<?= $Page->_Profile->cellAttributes() ?>>
<span id="el_employees__Profile">
<span<?= $Page->_Profile->viewAttributes() ?>>
<?= $Page->_Profile->getViewValue() ?></span>
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
