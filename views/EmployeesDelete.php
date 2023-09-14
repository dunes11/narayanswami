<?php

namespace PHPMaker2023\demo2023;

// Page object
$EmployeesDelete = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { employees: currentTable } });
var currentPageID = ew.PAGE_ID = "delete";
var currentForm;
var femployeesdelete;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("femployeesdelete")
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
<form name="femployeesdelete" id="femployeesdelete" class="ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="on">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="employees">
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
<?php if ($Page->EmployeeID->Visible) { // EmployeeID ?>
        <th class="<?= $Page->EmployeeID->headerCellClass() ?>"><span id="elh_employees_EmployeeID" class="employees_EmployeeID"><?= $Page->EmployeeID->caption() ?></span></th>
<?php } ?>
<?php if ($Page->_Username->Visible) { // Username ?>
        <th class="<?= $Page->_Username->headerCellClass() ?>"><span id="elh_employees__Username" class="employees__Username"><?= $Page->_Username->caption() ?></span></th>
<?php } ?>
<?php if ($Page->LastName->Visible) { // LastName ?>
        <th class="<?= $Page->LastName->headerCellClass() ?>"><span id="elh_employees_LastName" class="employees_LastName"><?= $Page->LastName->caption() ?></span></th>
<?php } ?>
<?php if ($Page->FirstName->Visible) { // FirstName ?>
        <th class="<?= $Page->FirstName->headerCellClass() ?>"><span id="elh_employees_FirstName" class="employees_FirstName"><?= $Page->FirstName->caption() ?></span></th>
<?php } ?>
<?php if ($Page->_Title->Visible) { // Title ?>
        <th class="<?= $Page->_Title->headerCellClass() ?>"><span id="elh_employees__Title" class="employees__Title"><?= $Page->_Title->caption() ?></span></th>
<?php } ?>
<?php if ($Page->Address->Visible) { // Address ?>
        <th class="<?= $Page->Address->headerCellClass() ?>"><span id="elh_employees_Address" class="employees_Address"><?= $Page->Address->caption() ?></span></th>
<?php } ?>
<?php if ($Page->Photo->Visible) { // Photo ?>
        <th class="<?= $Page->Photo->headerCellClass() ?>"><span id="elh_employees_Photo" class="employees_Photo"><?= $Page->Photo->caption() ?></span></th>
<?php } ?>
<?php if ($Page->_Password->Visible) { // Password ?>
        <th class="<?= $Page->_Password->headerCellClass() ?>"><span id="elh_employees__Password" class="employees__Password"><?= $Page->_Password->caption() ?></span></th>
<?php } ?>
<?php if ($Page->_Email->Visible) { // Email ?>
        <th class="<?= $Page->_Email->headerCellClass() ?>"><span id="elh_employees__Email" class="employees__Email"><?= $Page->_Email->caption() ?></span></th>
<?php } ?>
<?php if ($Page->Activated->Visible) { // Activated ?>
        <th class="<?= $Page->Activated->headerCellClass() ?>"><span id="elh_employees_Activated" class="employees_Activated"><?= $Page->Activated->caption() ?></span></th>
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
<?php if ($Page->EmployeeID->Visible) { // EmployeeID ?>
        <td<?= $Page->EmployeeID->cellAttributes() ?>>
<span id="">
<span<?= $Page->EmployeeID->viewAttributes() ?>>
<?= $Page->EmployeeID->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->_Username->Visible) { // Username ?>
        <td<?= $Page->_Username->cellAttributes() ?>>
<span id="">
<span<?= $Page->_Username->viewAttributes() ?>>
<?= $Page->_Username->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->LastName->Visible) { // LastName ?>
        <td<?= $Page->LastName->cellAttributes() ?>>
<span id="">
<span<?= $Page->LastName->viewAttributes() ?>>
<?= $Page->LastName->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->FirstName->Visible) { // FirstName ?>
        <td<?= $Page->FirstName->cellAttributes() ?>>
<span id="">
<span<?= $Page->FirstName->viewAttributes() ?>>
<?= $Page->FirstName->getViewValue() ?></span>
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
<?php if ($Page->Address->Visible) { // Address ?>
        <td<?= $Page->Address->cellAttributes() ?>>
<span id="">
<span<?= $Page->Address->viewAttributes() ?>>
<?= $Page->Address->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->Photo->Visible) { // Photo ?>
        <td<?= $Page->Photo->cellAttributes() ?>>
<span id="">
<span>
<?= GetFileViewTag($Page->Photo, $Page->Photo->getViewValue(), false) ?>
</span>
</span>
</td>
<?php } ?>
<?php if ($Page->_Password->Visible) { // Password ?>
        <td<?= $Page->_Password->cellAttributes() ?>>
<span id="">
<span<?= $Page->_Password->viewAttributes() ?>>
<?= $Page->_Password->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->_Email->Visible) { // Email ?>
        <td<?= $Page->_Email->cellAttributes() ?>>
<span id="">
<span<?= $Page->_Email->viewAttributes() ?>>
<?= $Page->_Email->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->Activated->Visible) { // Activated ?>
        <td<?= $Page->Activated->cellAttributes() ?>>
<span id="">
<span<?= $Page->Activated->viewAttributes() ?>>
<div class="form-check d-inline-block">
    <input type="checkbox" id="x_Activated_<?= $Page->RowCount ?>" class="form-check-input" value="<?= $Page->Activated->getViewValue() ?>" disabled<?php if (ConvertToBool($Page->Activated->CurrentValue)) { ?> checked<?php } ?>>
    <label class="form-check-label" for="x_Activated_<?= $Page->RowCount ?>"></label>
</div></span>
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
