<?php

namespace PHPMaker2023\demo2023;

// Page object
$EmployeesEdit = &$Page;
?>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<main class="edit">
<?php if (!$Page->IsModal) { ?>
<?= $Page->Pager->render() ?>
<?php } ?>
<form name="femployeesedit" id="femployeesedit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="on">
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { employees: currentTable } });
var currentPageID = ew.PAGE_ID = "edit";
var currentForm;
var femployeesedit;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("femployeesedit")
        .setPageId("edit")

        // Add fields
        .setFields([
            ["EmployeeID", [fields.EmployeeID.visible && fields.EmployeeID.required ? ew.Validators.required(fields.EmployeeID.caption) : null], fields.EmployeeID.isInvalid],
            ["_Username", [fields._Username.visible && fields._Username.required ? ew.Validators.required(fields._Username.caption) : null], fields._Username.isInvalid],
            ["LastName", [fields.LastName.visible && fields.LastName.required ? ew.Validators.required(fields.LastName.caption) : null], fields.LastName.isInvalid],
            ["FirstName", [fields.FirstName.visible && fields.FirstName.required ? ew.Validators.required(fields.FirstName.caption) : null], fields.FirstName.isInvalid],
            ["_Title", [fields._Title.visible && fields._Title.required ? ew.Validators.required(fields._Title.caption) : null], fields._Title.isInvalid],
            ["TitleOfCourtesy", [fields.TitleOfCourtesy.visible && fields.TitleOfCourtesy.required ? ew.Validators.required(fields.TitleOfCourtesy.caption) : null], fields.TitleOfCourtesy.isInvalid],
            ["BirthDate", [fields.BirthDate.visible && fields.BirthDate.required ? ew.Validators.required(fields.BirthDate.caption) : null, ew.Validators.datetime(fields.BirthDate.clientFormatPattern)], fields.BirthDate.isInvalid],
            ["HireDate", [fields.HireDate.visible && fields.HireDate.required ? ew.Validators.required(fields.HireDate.caption) : null, ew.Validators.datetime(fields.HireDate.clientFormatPattern)], fields.HireDate.isInvalid],
            ["Address", [fields.Address.visible && fields.Address.required ? ew.Validators.required(fields.Address.caption) : null], fields.Address.isInvalid],
            ["City", [fields.City.visible && fields.City.required ? ew.Validators.required(fields.City.caption) : null], fields.City.isInvalid],
            ["Region", [fields.Region.visible && fields.Region.required ? ew.Validators.required(fields.Region.caption) : null], fields.Region.isInvalid],
            ["PostalCode", [fields.PostalCode.visible && fields.PostalCode.required ? ew.Validators.required(fields.PostalCode.caption) : null], fields.PostalCode.isInvalid],
            ["Country", [fields.Country.visible && fields.Country.required ? ew.Validators.required(fields.Country.caption) : null], fields.Country.isInvalid],
            ["HomePhone", [fields.HomePhone.visible && fields.HomePhone.required ? ew.Validators.required(fields.HomePhone.caption) : null], fields.HomePhone.isInvalid],
            ["Extension", [fields.Extension.visible && fields.Extension.required ? ew.Validators.required(fields.Extension.caption) : null], fields.Extension.isInvalid],
            ["Photo", [fields.Photo.visible && fields.Photo.required ? ew.Validators.fileRequired(fields.Photo.caption) : null], fields.Photo.isInvalid],
            ["Notes", [fields.Notes.visible && fields.Notes.required ? ew.Validators.required(fields.Notes.caption) : null], fields.Notes.isInvalid],
            ["ReportsTo", [fields.ReportsTo.visible && fields.ReportsTo.required ? ew.Validators.required(fields.ReportsTo.caption) : null], fields.ReportsTo.isInvalid],
            ["_Password", [fields._Password.visible && fields._Password.required ? ew.Validators.required(fields._Password.caption) : null, ew.Validators.passwordStrength], fields._Password.isInvalid],
            ["_UserLevel", [fields._UserLevel.visible && fields._UserLevel.required ? ew.Validators.required(fields._UserLevel.caption) : null], fields._UserLevel.isInvalid],
            ["_Email", [fields._Email.visible && fields._Email.required ? ew.Validators.required(fields._Email.caption) : null, ew.Validators.email], fields._Email.isInvalid],
            ["Activated", [fields.Activated.visible && fields.Activated.required ? ew.Validators.required(fields.Activated.caption) : null], fields.Activated.isInvalid],
            ["_Profile", [fields._Profile.visible && fields._Profile.required ? ew.Validators.required(fields._Profile.caption) : null], fields._Profile.isInvalid]
        ])

        // Form_CustomValidate
        .setCustomValidate(
            function (fobj) { // DO NOT CHANGE THIS LINE! (except for adding "async" keyword)!
                    // Your custom validation code here, return false if invalid.
                    return true;
                }
        )

        // Use JavaScript validation or not
        .setValidateRequired(ew.CLIENT_VALIDATE)

        // Dynamic selection lists
        .setLists({
            "TitleOfCourtesy": <?= $Page->TitleOfCourtesy->toClientList($Page) ?>,
            "ReportsTo": <?= $Page->ReportsTo->toClientList($Page) ?>,
            "_UserLevel": <?= $Page->_UserLevel->toClientList($Page) ?>,
            "Activated": <?= $Page->Activated->toClientList($Page) ?>,
        })
        .build();
    window[form.id] = form;
    currentForm = form;
    loadjs.done(form.id);
});
</script>
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="employees">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->EmployeeID->Visible) { // EmployeeID ?>
    <div id="r_EmployeeID"<?= $Page->EmployeeID->rowAttributes() ?>>
        <label id="elh_employees_EmployeeID" class="<?= $Page->LeftColumnClass ?>"><?= $Page->EmployeeID->caption() ?><?= $Page->EmployeeID->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->EmployeeID->cellAttributes() ?>>
<span id="el_employees_EmployeeID">
<span<?= $Page->EmployeeID->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->EmployeeID->getDisplayValue($Page->EmployeeID->EditValue))) ?>"></span>
<input type="hidden" data-table="employees" data-field="x_EmployeeID" data-hidden="1" name="x_EmployeeID" id="x_EmployeeID" value="<?= HtmlEncode($Page->EmployeeID->CurrentValue) ?>">
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->_Username->Visible) { // Username ?>
    <div id="r__Username"<?= $Page->_Username->rowAttributes() ?>>
        <label id="elh_employees__Username" for="x__Username" class="<?= $Page->LeftColumnClass ?>"><?= $Page->_Username->caption() ?><?= $Page->_Username->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->_Username->cellAttributes() ?>>
<span id="el_employees__Username">
<input type="<?= $Page->_Username->getInputTextType() ?>" name="x__Username" id="x__Username" data-table="employees" data-field="x__Username" value="<?= $Page->_Username->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->_Username->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->_Username->formatPattern()) ?>"<?= $Page->_Username->editAttributes() ?> aria-describedby="x__Username_help">
<?= $Page->_Username->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->_Username->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->LastName->Visible) { // LastName ?>
    <div id="r_LastName"<?= $Page->LastName->rowAttributes() ?>>
        <label id="elh_employees_LastName" for="x_LastName" class="<?= $Page->LeftColumnClass ?>"><?= $Page->LastName->caption() ?><?= $Page->LastName->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->LastName->cellAttributes() ?>>
<span id="el_employees_LastName">
<input type="<?= $Page->LastName->getInputTextType() ?>" name="x_LastName" id="x_LastName" data-table="employees" data-field="x_LastName" value="<?= $Page->LastName->EditValue ?>" size="30" maxlength="20" placeholder="<?= HtmlEncode($Page->LastName->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->LastName->formatPattern()) ?>"<?= $Page->LastName->editAttributes() ?> aria-describedby="x_LastName_help">
<?= $Page->LastName->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->LastName->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->FirstName->Visible) { // FirstName ?>
    <div id="r_FirstName"<?= $Page->FirstName->rowAttributes() ?>>
        <label id="elh_employees_FirstName" for="x_FirstName" class="<?= $Page->LeftColumnClass ?>"><?= $Page->FirstName->caption() ?><?= $Page->FirstName->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->FirstName->cellAttributes() ?>>
<span id="el_employees_FirstName">
<input type="<?= $Page->FirstName->getInputTextType() ?>" name="x_FirstName" id="x_FirstName" data-table="employees" data-field="x_FirstName" value="<?= $Page->FirstName->EditValue ?>" size="30" maxlength="10" placeholder="<?= HtmlEncode($Page->FirstName->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->FirstName->formatPattern()) ?>"<?= $Page->FirstName->editAttributes() ?> aria-describedby="x_FirstName_help">
<?= $Page->FirstName->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->FirstName->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->_Title->Visible) { // Title ?>
    <div id="r__Title"<?= $Page->_Title->rowAttributes() ?>>
        <label id="elh_employees__Title" for="x__Title" class="<?= $Page->LeftColumnClass ?>"><?= $Page->_Title->caption() ?><?= $Page->_Title->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->_Title->cellAttributes() ?>>
<span id="el_employees__Title">
<input type="<?= $Page->_Title->getInputTextType() ?>" name="x__Title" id="x__Title" data-table="employees" data-field="x__Title" value="<?= $Page->_Title->EditValue ?>" size="30" maxlength="30" placeholder="<?= HtmlEncode($Page->_Title->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->_Title->formatPattern()) ?>"<?= $Page->_Title->editAttributes() ?> aria-describedby="x__Title_help">
<?= $Page->_Title->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->_Title->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->TitleOfCourtesy->Visible) { // TitleOfCourtesy ?>
    <div id="r_TitleOfCourtesy"<?= $Page->TitleOfCourtesy->rowAttributes() ?>>
        <label id="elh_employees_TitleOfCourtesy" for="x_TitleOfCourtesy" class="<?= $Page->LeftColumnClass ?>"><?= $Page->TitleOfCourtesy->caption() ?><?= $Page->TitleOfCourtesy->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->TitleOfCourtesy->cellAttributes() ?>>
<span id="el_employees_TitleOfCourtesy">
    <select
        id="x_TitleOfCourtesy"
        name="x_TitleOfCourtesy"
        class="form-select ew-select<?= $Page->TitleOfCourtesy->isInvalidClass() ?>"
        <?php if (!$Page->TitleOfCourtesy->IsNativeSelect) { ?>
        data-select2-id="femployeesedit_x_TitleOfCourtesy"
        <?php } ?>
        data-table="employees"
        data-field="x_TitleOfCourtesy"
        data-value-separator="<?= $Page->TitleOfCourtesy->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->TitleOfCourtesy->getPlaceHolder()) ?>"
        <?= $Page->TitleOfCourtesy->editAttributes() ?>>
        <?= $Page->TitleOfCourtesy->selectOptionListHtml("x_TitleOfCourtesy") ?>
    </select>
    <?= $Page->TitleOfCourtesy->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->TitleOfCourtesy->getErrorMessage() ?></div>
<?php if (!$Page->TitleOfCourtesy->IsNativeSelect) { ?>
<script>
loadjs.ready("femployeesedit", function() {
    var options = { name: "x_TitleOfCourtesy", selectId: "femployeesedit_x_TitleOfCourtesy" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (femployeesedit.lists.TitleOfCourtesy?.lookupOptions.length) {
        options.data = { id: "x_TitleOfCourtesy", form: "femployeesedit" };
    } else {
        options.ajax = { id: "x_TitleOfCourtesy", form: "femployeesedit", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.employees.fields.TitleOfCourtesy.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->BirthDate->Visible) { // BirthDate ?>
    <div id="r_BirthDate"<?= $Page->BirthDate->rowAttributes() ?>>
        <label id="elh_employees_BirthDate" for="x_BirthDate" class="<?= $Page->LeftColumnClass ?>"><?= $Page->BirthDate->caption() ?><?= $Page->BirthDate->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->BirthDate->cellAttributes() ?>>
<span id="el_employees_BirthDate">
<input type="<?= $Page->BirthDate->getInputTextType() ?>" name="x_BirthDate" id="x_BirthDate" data-table="employees" data-field="x_BirthDate" value="<?= $Page->BirthDate->EditValue ?>" placeholder="<?= HtmlEncode($Page->BirthDate->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->BirthDate->formatPattern()) ?>"<?= $Page->BirthDate->editAttributes() ?> aria-describedby="x_BirthDate_help">
<?= $Page->BirthDate->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->BirthDate->getErrorMessage() ?></div>
<?php if (!$Page->BirthDate->ReadOnly && !$Page->BirthDate->Disabled && !isset($Page->BirthDate->EditAttrs["readonly"]) && !isset($Page->BirthDate->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["femployeesedit", "datetimepicker"], function () {
    let format = "<?= DateFormat(0) ?>",
        options = {
            localization: {
                locale: ew.LANGUAGE_ID + "-u-nu-" + ew.getNumberingSystem(),
                hourCycle: format.match(/H/) ? "h24" : "h12",
                format,
                ...ew.language.phrase("datetimepicker")
            },
            display: {
                icons: {
                    previous: ew.IS_RTL ? "fa-solid fa-chevron-right" : "fa-solid fa-chevron-left",
                    next: ew.IS_RTL ? "fa-solid fa-chevron-left" : "fa-solid fa-chevron-right"
                },
                components: {
                    hours: !!format.match(/h/i),
                    minutes: !!format.match(/m/),
                    seconds: !!format.match(/s/i)
                },
                theme: ew.isDark() ? "dark" : "auto"
            }
        };
    ew.createDateTimePicker("femployeesedit", "x_BirthDate", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->HireDate->Visible) { // HireDate ?>
    <div id="r_HireDate"<?= $Page->HireDate->rowAttributes() ?>>
        <label id="elh_employees_HireDate" for="x_HireDate" class="<?= $Page->LeftColumnClass ?>"><?= $Page->HireDate->caption() ?><?= $Page->HireDate->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->HireDate->cellAttributes() ?>>
<span id="el_employees_HireDate">
<input type="<?= $Page->HireDate->getInputTextType() ?>" name="x_HireDate" id="x_HireDate" data-table="employees" data-field="x_HireDate" value="<?= $Page->HireDate->EditValue ?>" placeholder="<?= HtmlEncode($Page->HireDate->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->HireDate->formatPattern()) ?>"<?= $Page->HireDate->editAttributes() ?> aria-describedby="x_HireDate_help">
<?= $Page->HireDate->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->HireDate->getErrorMessage() ?></div>
<?php if (!$Page->HireDate->ReadOnly && !$Page->HireDate->Disabled && !isset($Page->HireDate->EditAttrs["readonly"]) && !isset($Page->HireDate->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["femployeesedit", "datetimepicker"], function () {
    let format = "<?= DateFormat(0) ?>",
        options = {
            localization: {
                locale: ew.LANGUAGE_ID + "-u-nu-" + ew.getNumberingSystem(),
                hourCycle: format.match(/H/) ? "h24" : "h12",
                format,
                ...ew.language.phrase("datetimepicker")
            },
            display: {
                icons: {
                    previous: ew.IS_RTL ? "fa-solid fa-chevron-right" : "fa-solid fa-chevron-left",
                    next: ew.IS_RTL ? "fa-solid fa-chevron-left" : "fa-solid fa-chevron-right"
                },
                components: {
                    hours: !!format.match(/h/i),
                    minutes: !!format.match(/m/),
                    seconds: !!format.match(/s/i)
                },
                theme: ew.isDark() ? "dark" : "auto"
            }
        };
    ew.createDateTimePicker("femployeesedit", "x_HireDate", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->Address->Visible) { // Address ?>
    <div id="r_Address"<?= $Page->Address->rowAttributes() ?>>
        <label id="elh_employees_Address" for="x_Address" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Address->caption() ?><?= $Page->Address->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Address->cellAttributes() ?>>
<span id="el_employees_Address">
<input type="<?= $Page->Address->getInputTextType() ?>" name="x_Address" id="x_Address" data-table="employees" data-field="x_Address" value="<?= $Page->Address->EditValue ?>" size="30" maxlength="60" placeholder="<?= HtmlEncode($Page->Address->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Address->formatPattern()) ?>"<?= $Page->Address->editAttributes() ?> aria-describedby="x_Address_help">
<?= $Page->Address->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->Address->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->City->Visible) { // City ?>
    <div id="r_City"<?= $Page->City->rowAttributes() ?>>
        <label id="elh_employees_City" for="x_City" class="<?= $Page->LeftColumnClass ?>"><?= $Page->City->caption() ?><?= $Page->City->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->City->cellAttributes() ?>>
<span id="el_employees_City">
<input type="<?= $Page->City->getInputTextType() ?>" name="x_City" id="x_City" data-table="employees" data-field="x_City" value="<?= $Page->City->EditValue ?>" size="30" maxlength="15" placeholder="<?= HtmlEncode($Page->City->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->City->formatPattern()) ?>"<?= $Page->City->editAttributes() ?> aria-describedby="x_City_help">
<?= $Page->City->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->City->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->Region->Visible) { // Region ?>
    <div id="r_Region"<?= $Page->Region->rowAttributes() ?>>
        <label id="elh_employees_Region" for="x_Region" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Region->caption() ?><?= $Page->Region->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Region->cellAttributes() ?>>
<span id="el_employees_Region">
<input type="<?= $Page->Region->getInputTextType() ?>" name="x_Region" id="x_Region" data-table="employees" data-field="x_Region" value="<?= $Page->Region->EditValue ?>" size="30" maxlength="15" placeholder="<?= HtmlEncode($Page->Region->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Region->formatPattern()) ?>"<?= $Page->Region->editAttributes() ?> aria-describedby="x_Region_help">
<?= $Page->Region->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->Region->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->PostalCode->Visible) { // PostalCode ?>
    <div id="r_PostalCode"<?= $Page->PostalCode->rowAttributes() ?>>
        <label id="elh_employees_PostalCode" for="x_PostalCode" class="<?= $Page->LeftColumnClass ?>"><?= $Page->PostalCode->caption() ?><?= $Page->PostalCode->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->PostalCode->cellAttributes() ?>>
<span id="el_employees_PostalCode">
<input type="<?= $Page->PostalCode->getInputTextType() ?>" name="x_PostalCode" id="x_PostalCode" data-table="employees" data-field="x_PostalCode" value="<?= $Page->PostalCode->EditValue ?>" size="30" maxlength="10" placeholder="<?= HtmlEncode($Page->PostalCode->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->PostalCode->formatPattern()) ?>"<?= $Page->PostalCode->editAttributes() ?> aria-describedby="x_PostalCode_help">
<?= $Page->PostalCode->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->PostalCode->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->Country->Visible) { // Country ?>
    <div id="r_Country"<?= $Page->Country->rowAttributes() ?>>
        <label id="elh_employees_Country" for="x_Country" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Country->caption() ?><?= $Page->Country->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Country->cellAttributes() ?>>
<span id="el_employees_Country">
<input type="<?= $Page->Country->getInputTextType() ?>" name="x_Country" id="x_Country" data-table="employees" data-field="x_Country" value="<?= $Page->Country->EditValue ?>" size="30" maxlength="15" placeholder="<?= HtmlEncode($Page->Country->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Country->formatPattern()) ?>"<?= $Page->Country->editAttributes() ?> aria-describedby="x_Country_help">
<?= $Page->Country->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->Country->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->HomePhone->Visible) { // HomePhone ?>
    <div id="r_HomePhone"<?= $Page->HomePhone->rowAttributes() ?>>
        <label id="elh_employees_HomePhone" for="x_HomePhone" class="<?= $Page->LeftColumnClass ?>"><?= $Page->HomePhone->caption() ?><?= $Page->HomePhone->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->HomePhone->cellAttributes() ?>>
<span id="el_employees_HomePhone">
<input type="<?= $Page->HomePhone->getInputTextType() ?>" name="x_HomePhone" id="x_HomePhone" data-table="employees" data-field="x_HomePhone" value="<?= $Page->HomePhone->EditValue ?>" size="30" maxlength="24" placeholder="<?= HtmlEncode($Page->HomePhone->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->HomePhone->formatPattern()) ?>"<?= $Page->HomePhone->editAttributes() ?> aria-describedby="x_HomePhone_help">
<?= $Page->HomePhone->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->HomePhone->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->Extension->Visible) { // Extension ?>
    <div id="r_Extension"<?= $Page->Extension->rowAttributes() ?>>
        <label id="elh_employees_Extension" for="x_Extension" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Extension->caption() ?><?= $Page->Extension->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Extension->cellAttributes() ?>>
<span id="el_employees_Extension">
<input type="<?= $Page->Extension->getInputTextType() ?>" name="x_Extension" id="x_Extension" data-table="employees" data-field="x_Extension" value="<?= $Page->Extension->EditValue ?>" size="30" maxlength="4" placeholder="<?= HtmlEncode($Page->Extension->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Extension->formatPattern()) ?>"<?= $Page->Extension->editAttributes() ?> aria-describedby="x_Extension_help">
<?= $Page->Extension->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->Extension->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->Photo->Visible) { // Photo ?>
    <div id="r_Photo"<?= $Page->Photo->rowAttributes() ?>>
        <label id="elh_employees_Photo" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Photo->caption() ?><?= $Page->Photo->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Photo->cellAttributes() ?>>
<span id="el_employees_Photo">
<div id="fd_x_Photo" class="fileinput-button ew-file-drop-zone">
    <input
        type="file"
        id="x_Photo"
        name="x_Photo"
        class="form-control ew-file-input"
        title="<?= $Page->Photo->title() ?>"
        lang="<?= CurrentLanguageID() ?>"
        data-table="employees"
        data-field="x_Photo"
        data-size="50"
        data-accept-file-types="<?= $Page->Photo->acceptFileTypes() ?>"
        data-max-file-size="<?= $Page->Photo->UploadMaxFileSize ?>"
        data-max-number-of-files="<?= $Page->Photo->UploadMaxFileCount ?>"
        data-disable-image-crop="<?= $Page->Photo->ImageCropper ? 0 : 1 ?>"
        multiple
        aria-describedby="x_Photo_help"
        <?= ($Page->Photo->ReadOnly || $Page->Photo->Disabled) ? " disabled" : "" ?>
        <?= $Page->Photo->editAttributes() ?>
    >
    <div class="text-muted ew-file-text"><?= $Language->phrase("ChooseFiles") ?></div>
    <?= $Page->Photo->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->Photo->getErrorMessage() ?></div>
</div>
<input type="hidden" name="fn_x_Photo" id= "fn_x_Photo" value="<?= $Page->Photo->Upload->FileName ?>">
<input type="hidden" name="fa_x_Photo" id= "fa_x_Photo" value="<?= (Post("fa_x_Photo") == "0") ? "0" : "1" ?>">
<table id="ft_x_Photo" class="table table-sm float-start ew-upload-table"><tbody class="files"></tbody></table>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->Notes->Visible) { // Notes ?>
    <div id="r_Notes"<?= $Page->Notes->rowAttributes() ?>>
        <label id="elh_employees_Notes" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Notes->caption() ?><?= $Page->Notes->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Notes->cellAttributes() ?>>
<span id="el_employees_Notes">
<?php $Page->Notes->EditAttrs->appendClass("editor"); ?>
<textarea data-table="employees" data-field="x_Notes" name="x_Notes" id="x_Notes" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->Notes->getPlaceHolder()) ?>"<?= $Page->Notes->editAttributes() ?> aria-describedby="x_Notes_help"><?= $Page->Notes->EditValue ?></textarea>
<?= $Page->Notes->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->Notes->getErrorMessage() ?></div>
<script>
loadjs.ready(["femployeesedit", "editor"], function() {
    ew.createEditor("femployeesedit", "x_Notes", 35, 4, <?= $Page->Notes->ReadOnly || false ? "true" : "false" ?>);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->ReportsTo->Visible) { // ReportsTo ?>
    <div id="r_ReportsTo"<?= $Page->ReportsTo->rowAttributes() ?>>
        <label id="elh_employees_ReportsTo" for="x_ReportsTo" class="<?= $Page->LeftColumnClass ?>"><?= $Page->ReportsTo->caption() ?><?= $Page->ReportsTo->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->ReportsTo->cellAttributes() ?>>
<?php if (!$Security->isAdmin() && $Security->isLoggedIn()) { // Non system admin ?>
<?php if (SameString($Page->EmployeeID->CurrentValue, CurrentUserID())) { ?>
    <span<?= $Page->ReportsTo->viewAttributes() ?>>
    <span class="form-control-plaintext"><?= $Page->ReportsTo->getDisplayValue($Page->ReportsTo->EditValue) ?></span></span>
    <input type="hidden" data-table="employees" data-field="x_ReportsTo" data-hidden="1" name="x_ReportsTo" id="x_ReportsTo" value="<?= HtmlEncode($Page->ReportsTo->CurrentValue) ?>">
<?php } else { ?>
<span id="el_employees_ReportsTo">
    <select
        id="x_ReportsTo"
        name="x_ReportsTo"
        class="form-select ew-select<?= $Page->ReportsTo->isInvalidClass() ?>"
        <?php if (!$Page->ReportsTo->IsNativeSelect) { ?>
        data-select2-id="femployeesedit_x_ReportsTo"
        <?php } ?>
        data-table="employees"
        data-field="x_ReportsTo"
        data-value-separator="<?= $Page->ReportsTo->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->ReportsTo->getPlaceHolder()) ?>"
        <?= $Page->ReportsTo->editAttributes() ?>>
        <?= $Page->ReportsTo->selectOptionListHtml("x_ReportsTo") ?>
    </select>
    <?= $Page->ReportsTo->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->ReportsTo->getErrorMessage() ?></div>
<?= $Page->ReportsTo->Lookup->getParamTag($Page, "p_x_ReportsTo") ?>
<?php if (!$Page->ReportsTo->IsNativeSelect) { ?>
<script>
loadjs.ready("femployeesedit", function() {
    var options = { name: "x_ReportsTo", selectId: "femployeesedit_x_ReportsTo" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (femployeesedit.lists.ReportsTo?.lookupOptions.length) {
        options.data = { id: "x_ReportsTo", form: "femployeesedit" };
    } else {
        options.ajax = { id: "x_ReportsTo", form: "femployeesedit", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.employees.fields.ReportsTo.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
<?php } ?>
<?php } else { ?>
<span id="el_employees_ReportsTo">
    <select
        id="x_ReportsTo"
        name="x_ReportsTo"
        class="form-select ew-select<?= $Page->ReportsTo->isInvalidClass() ?>"
        <?php if (!$Page->ReportsTo->IsNativeSelect) { ?>
        data-select2-id="femployeesedit_x_ReportsTo"
        <?php } ?>
        data-table="employees"
        data-field="x_ReportsTo"
        data-value-separator="<?= $Page->ReportsTo->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->ReportsTo->getPlaceHolder()) ?>"
        <?= $Page->ReportsTo->editAttributes() ?>>
        <?= $Page->ReportsTo->selectOptionListHtml("x_ReportsTo") ?>
    </select>
    <?= $Page->ReportsTo->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->ReportsTo->getErrorMessage() ?></div>
<?= $Page->ReportsTo->Lookup->getParamTag($Page, "p_x_ReportsTo") ?>
<?php if (!$Page->ReportsTo->IsNativeSelect) { ?>
<script>
loadjs.ready("femployeesedit", function() {
    var options = { name: "x_ReportsTo", selectId: "femployeesedit_x_ReportsTo" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (femployeesedit.lists.ReportsTo?.lookupOptions.length) {
        options.data = { id: "x_ReportsTo", form: "femployeesedit" };
    } else {
        options.ajax = { id: "x_ReportsTo", form: "femployeesedit", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.employees.fields.ReportsTo.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
<?php } ?>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->_Password->Visible) { // Password ?>
    <div id="r__Password"<?= $Page->_Password->rowAttributes() ?>>
        <label id="elh_employees__Password" for="x__Password" class="<?= $Page->LeftColumnClass ?>"><?= $Page->_Password->caption() ?><?= $Page->_Password->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->_Password->cellAttributes() ?>>
<span id="el_employees__Password">
<div class="input-group" id="ig__Password">
    <input type="password" autocomplete="new-password" data-password-strength="pst__Password" data-table="employees" data-field="x__Password" name="x__Password" id="x__Password" value="<?= $Page->_Password->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->_Password->getPlaceHolder()) ?>"<?= $Page->_Password->editAttributes() ?> aria-describedby="x__Password_help">
    <button type="button" class="btn btn-default ew-toggle-password" data-ew-action="password"><i class="fa-solid fa-eye"></i></button>
    <button type="button" class="btn btn-default ew-password-generator rounded-end" title="<?= HtmlTitle($Language->phrase("GeneratePassword")) ?>" data-password-field="x__Password" data-password-confirm="c__Password" data-password-strength="pst__Password"><?= $Language->phrase("GeneratePassword") ?></button>
</div>
<div class="progress ew-password-strength-bar form-text mt-1 d-none" id="pst__Password">
    <div class="progress-bar" role="progressbar"></div>
</div>
<?= $Page->_Password->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->_Password->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->_UserLevel->Visible) { // UserLevel ?>
    <div id="r__UserLevel"<?= $Page->_UserLevel->rowAttributes() ?>>
        <label id="elh_employees__UserLevel" for="x__UserLevel" class="<?= $Page->LeftColumnClass ?>"><?= $Page->_UserLevel->caption() ?><?= $Page->_UserLevel->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->_UserLevel->cellAttributes() ?>>
<?php if (!$Security->isAdmin() && $Security->isLoggedIn()) { // Non system admin ?>
<span id="el_employees__UserLevel">
<span class="form-control-plaintext"><?= $Page->_UserLevel->getDisplayValue($Page->_UserLevel->EditValue) ?></span>
</span>
<?php } else { ?>
<span id="el_employees__UserLevel">
    <select
        id="x__UserLevel"
        name="x__UserLevel"
        class="form-select ew-select<?= $Page->_UserLevel->isInvalidClass() ?>"
        <?php if (!$Page->_UserLevel->IsNativeSelect) { ?>
        data-select2-id="femployeesedit_x__UserLevel"
        <?php } ?>
        data-table="employees"
        data-field="x__UserLevel"
        data-value-separator="<?= $Page->_UserLevel->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->_UserLevel->getPlaceHolder()) ?>"
        <?= $Page->_UserLevel->editAttributes() ?>>
        <?= $Page->_UserLevel->selectOptionListHtml("x__UserLevel") ?>
    </select>
    <?= $Page->_UserLevel->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->_UserLevel->getErrorMessage() ?></div>
<?= $Page->_UserLevel->Lookup->getParamTag($Page, "p_x__UserLevel") ?>
<?php if (!$Page->_UserLevel->IsNativeSelect) { ?>
<script>
loadjs.ready("femployeesedit", function() {
    var options = { name: "x__UserLevel", selectId: "femployeesedit_x__UserLevel" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (femployeesedit.lists._UserLevel?.lookupOptions.length) {
        options.data = { id: "x__UserLevel", form: "femployeesedit" };
    } else {
        options.ajax = { id: "x__UserLevel", form: "femployeesedit", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.employees.fields._UserLevel.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
<?php } ?>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->_Email->Visible) { // Email ?>
    <div id="r__Email"<?= $Page->_Email->rowAttributes() ?>>
        <label id="elh_employees__Email" for="x__Email" class="<?= $Page->LeftColumnClass ?>"><?= $Page->_Email->caption() ?><?= $Page->_Email->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->_Email->cellAttributes() ?>>
<span id="el_employees__Email">
<input type="<?= $Page->_Email->getInputTextType() ?>" name="x__Email" id="x__Email" data-table="employees" data-field="x__Email" value="<?= $Page->_Email->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->_Email->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->_Email->formatPattern()) ?>"<?= $Page->_Email->editAttributes() ?> aria-describedby="x__Email_help">
<?= $Page->_Email->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->_Email->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->Activated->Visible) { // Activated ?>
    <div id="r_Activated"<?= $Page->Activated->rowAttributes() ?>>
        <label id="elh_employees_Activated" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Activated->caption() ?><?= $Page->Activated->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Activated->cellAttributes() ?>>
<span id="el_employees_Activated">
<div class="form-check d-inline-block">
    <input type="checkbox" class="form-check-input<?= $Page->Activated->isInvalidClass() ?>" data-table="employees" data-field="x_Activated" data-boolean name="x_Activated" id="x_Activated" value="1"<?= ConvertToBool($Page->Activated->CurrentValue) ? " checked" : "" ?><?= $Page->Activated->editAttributes() ?> aria-describedby="x_Activated_help">
    <div class="invalid-feedback"><?= $Page->Activated->getErrorMessage() ?></div>
</div>
<?= $Page->Activated->getCustomMessage() ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->_Profile->Visible) { // Profile ?>
    <div id="r__Profile"<?= $Page->_Profile->rowAttributes() ?>>
        <label id="elh_employees__Profile" for="x__Profile" class="<?= $Page->LeftColumnClass ?>"><?= $Page->_Profile->caption() ?><?= $Page->_Profile->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->_Profile->cellAttributes() ?>>
<span id="el_employees__Profile">
<textarea data-table="employees" data-field="x__Profile" name="x__Profile" id="x__Profile" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->_Profile->getPlaceHolder()) ?>"<?= $Page->_Profile->editAttributes() ?> aria-describedby="x__Profile_help"><?= $Page->_Profile->EditValue ?></textarea>
<?= $Page->_Profile->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->_Profile->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="femployeesedit"><?= $Language->phrase("SaveBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="femployeesedit" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
<?php } ?>
    </div><!-- /buttons offset -->
<?= $Page->IsModal ? "</template>" : "</div>" ?><!-- /buttons .row -->
</form>
<?php if (!$Page->IsModal) { ?>
<?= $Page->Pager->render() ?>
<?php } ?>
</main>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<script>
// Field event handlers
loadjs.ready("head", function() {
    ew.addEventHandlers("employees");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
