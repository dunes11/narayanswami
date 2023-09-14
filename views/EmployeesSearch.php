<?php

namespace PHPMaker2023\demo2023;

// Page object
$EmployeesSearch = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { employees: currentTable } });
var currentPageID = ew.PAGE_ID = "search";
var currentForm;
var femployeessearch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery,
        fields = currentTable.fields;

    // Form object for search
    let form = new ew.FormBuilder()
        .setId("femployeessearch")
        .setPageId("search")
<?php if ($Page->IsModal && $Page->UseAjaxActions) { ?>
        .setSubmitWithFetch(true)
<?php } ?>

        // Add fields
        .addFields([
            ["EmployeeID", [ew.Validators.integer], fields.EmployeeID.isInvalid],
            ["_Username", [], fields._Username.isInvalid],
            ["LastName", [], fields.LastName.isInvalid],
            ["FirstName", [], fields.FirstName.isInvalid],
            ["_Title", [], fields._Title.isInvalid],
            ["TitleOfCourtesy", [], fields.TitleOfCourtesy.isInvalid],
            ["BirthDate", [ew.Validators.datetime(fields.BirthDate.clientFormatPattern)], fields.BirthDate.isInvalid],
            ["HireDate", [ew.Validators.datetime(fields.HireDate.clientFormatPattern)], fields.HireDate.isInvalid],
            ["Address", [], fields.Address.isInvalid],
            ["City", [], fields.City.isInvalid],
            ["Region", [], fields.Region.isInvalid],
            ["PostalCode", [], fields.PostalCode.isInvalid],
            ["Country", [], fields.Country.isInvalid],
            ["HomePhone", [], fields.HomePhone.isInvalid],
            ["Extension", [], fields.Extension.isInvalid],
            ["Photo", [], fields.Photo.isInvalid],
            ["Notes", [], fields.Notes.isInvalid],
            ["ReportsTo", [], fields.ReportsTo.isInvalid],
            ["_Password", [], fields._Password.isInvalid],
            ["_UserLevel", [], fields._UserLevel.isInvalid],
            ["_Email", [], fields._Email.isInvalid],
            ["Activated", [], fields.Activated.isInvalid],
            ["_Profile", [], fields._Profile.isInvalid]
        ])
        // Validate form
        .setValidate(
            async function () {
                if (!this.validateRequired)
                    return true; // Ignore validation
                let fobj = this.getForm();

                // Validate fields
                if (!this.validateFields())
                    return false;

                // Call Form_CustomValidate event
                if (!(await this.customValidate?.(fobj) ?? true)) {
                    this.focus();
                    return false;
                }
                return true;
            }
        )

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
<?php if ($Page->IsModal) { ?>
    currentAdvancedSearchForm = form;
<?php } else { ?>
    currentForm = form;
<?php } ?>
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
<form name="femployeessearch" id="femployeessearch" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="on">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="employees">
<input type="hidden" name="action" id="action" value="search">
<?php if ($Page->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div class="ew-search-div"><!-- page* -->
<?php if ($Page->EmployeeID->Visible) { // EmployeeID ?>
    <div id="r_EmployeeID" class="row"<?= $Page->EmployeeID->rowAttributes() ?>>
        <label for="x_EmployeeID" class="<?= $Page->LeftColumnClass ?>"><span id="elh_employees_EmployeeID"><?= $Page->EmployeeID->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_EmployeeID" id="z_EmployeeID" value="=">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->EmployeeID->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_employees_EmployeeID" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->EmployeeID->getInputTextType() ?>" name="x_EmployeeID" id="x_EmployeeID" data-table="employees" data-field="x_EmployeeID" value="<?= $Page->EmployeeID->EditValue ?>" placeholder="<?= HtmlEncode($Page->EmployeeID->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->EmployeeID->formatPattern()) ?>"<?= $Page->EmployeeID->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->EmployeeID->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->_Username->Visible) { // Username ?>
    <div id="r__Username" class="row"<?= $Page->_Username->rowAttributes() ?>>
        <label for="x__Username" class="<?= $Page->LeftColumnClass ?>"><span id="elh_employees__Username"><?= $Page->_Username->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z__Username" id="z__Username" value="LIKE">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->_Username->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_employees__Username" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->_Username->getInputTextType() ?>" name="x__Username" id="x__Username" data-table="employees" data-field="x__Username" value="<?= $Page->_Username->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->_Username->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->_Username->formatPattern()) ?>"<?= $Page->_Username->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->_Username->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->LastName->Visible) { // LastName ?>
    <div id="r_LastName" class="row"<?= $Page->LastName->rowAttributes() ?>>
        <label for="x_LastName" class="<?= $Page->LeftColumnClass ?>"><span id="elh_employees_LastName"><?= $Page->LastName->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_LastName" id="z_LastName" value="LIKE">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->LastName->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_employees_LastName" class="ew-search-field">
<input type="<?= $Page->LastName->getInputTextType() ?>" name="x_LastName" id="x_LastName" data-table="employees" data-field="x_LastName" value="<?= $Page->LastName->EditValue ?>" size="30" maxlength="20" placeholder="<?= HtmlEncode($Page->LastName->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->LastName->formatPattern()) ?>"<?= $Page->LastName->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->LastName->getErrorMessage(false) ?></div>
</span>
                    <span class="ew-search-cond">
<div class="form-check"><input class="form-check-input" type="radio" id="v_LastName_1" name="v_LastName" value="AND"<?= ($Page->LastName->AdvancedSearch->SearchCondition != "OR") ? " checked" : "" ?>><label class="form-check-label" for="v_LastName_1"><?= $Language->phrase("AND") ?></label></div>
<div class="form-check"><input class="form-check-input" type="radio" id="v_LastName_2" name="v_LastName" value="OR"<?= ($Page->LastName->AdvancedSearch->SearchCondition == "OR") ? " checked" : "" ?>><label class="form-check-label" for="v_LastName_2"><?= $Language->phrase("OR") ?></label></div></span>
                    <span class="ew-search-operator2">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="w_LastName" id="w_LastName" value="LIKE">
</span>
                    <span id="el2_employees_LastName" class="ew-search-field2">
<input type="<?= $Page->LastName->getInputTextType() ?>" name="y_LastName" id="y_LastName" data-table="employees" data-field="x_LastName" value="<?= $Page->LastName->EditValue2 ?>" size="30" maxlength="20" placeholder="<?= HtmlEncode($Page->LastName->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->LastName->formatPattern()) ?>"<?= $Page->LastName->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->LastName->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->FirstName->Visible) { // FirstName ?>
    <div id="r_FirstName" class="row"<?= $Page->FirstName->rowAttributes() ?>>
        <label for="x_FirstName" class="<?= $Page->LeftColumnClass ?>"><span id="elh_employees_FirstName"><?= $Page->FirstName->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_FirstName" id="z_FirstName" value="LIKE">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->FirstName->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_employees_FirstName" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->FirstName->getInputTextType() ?>" name="x_FirstName" id="x_FirstName" data-table="employees" data-field="x_FirstName" value="<?= $Page->FirstName->EditValue ?>" size="30" maxlength="10" placeholder="<?= HtmlEncode($Page->FirstName->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->FirstName->formatPattern()) ?>"<?= $Page->FirstName->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->FirstName->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->_Title->Visible) { // Title ?>
    <div id="r__Title" class="row"<?= $Page->_Title->rowAttributes() ?>>
        <label for="x__Title" class="<?= $Page->LeftColumnClass ?>"><span id="elh_employees__Title"><?= $Page->_Title->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z__Title" id="z__Title" value="LIKE">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->_Title->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_employees__Title" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->_Title->getInputTextType() ?>" name="x__Title" id="x__Title" data-table="employees" data-field="x__Title" value="<?= $Page->_Title->EditValue ?>" size="30" maxlength="30" placeholder="<?= HtmlEncode($Page->_Title->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->_Title->formatPattern()) ?>"<?= $Page->_Title->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->_Title->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->TitleOfCourtesy->Visible) { // TitleOfCourtesy ?>
    <div id="r_TitleOfCourtesy" class="row"<?= $Page->TitleOfCourtesy->rowAttributes() ?>>
        <label for="x_TitleOfCourtesy" class="<?= $Page->LeftColumnClass ?>"><span id="elh_employees_TitleOfCourtesy"><?= $Page->TitleOfCourtesy->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_TitleOfCourtesy" id="z_TitleOfCourtesy" value="LIKE">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->TitleOfCourtesy->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_employees_TitleOfCourtesy" class="ew-search-field ew-search-field-single">
    <select
        id="x_TitleOfCourtesy"
        name="x_TitleOfCourtesy"
        class="form-select ew-select<?= $Page->TitleOfCourtesy->isInvalidClass() ?>"
        <?php if (!$Page->TitleOfCourtesy->IsNativeSelect) { ?>
        data-select2-id="femployeessearch_x_TitleOfCourtesy"
        <?php } ?>
        data-table="employees"
        data-field="x_TitleOfCourtesy"
        data-value-separator="<?= $Page->TitleOfCourtesy->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->TitleOfCourtesy->getPlaceHolder()) ?>"
        <?= $Page->TitleOfCourtesy->editAttributes() ?>>
        <?= $Page->TitleOfCourtesy->selectOptionListHtml("x_TitleOfCourtesy") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->TitleOfCourtesy->getErrorMessage(false) ?></div>
<?php if (!$Page->TitleOfCourtesy->IsNativeSelect) { ?>
<script>
loadjs.ready("femployeessearch", function() {
    var options = { name: "x_TitleOfCourtesy", selectId: "femployeessearch_x_TitleOfCourtesy" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (femployeessearch.lists.TitleOfCourtesy?.lookupOptions.length) {
        options.data = { id: "x_TitleOfCourtesy", form: "femployeessearch" };
    } else {
        options.ajax = { id: "x_TitleOfCourtesy", form: "femployeessearch", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.employees.fields.TitleOfCourtesy.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->BirthDate->Visible) { // BirthDate ?>
    <div id="r_BirthDate" class="row"<?= $Page->BirthDate->rowAttributes() ?>>
        <label for="x_BirthDate" class="<?= $Page->LeftColumnClass ?>"><span id="elh_employees_BirthDate"><?= $Page->BirthDate->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_BirthDate" id="z_BirthDate" value="=">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->BirthDate->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_employees_BirthDate" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->BirthDate->getInputTextType() ?>" name="x_BirthDate" id="x_BirthDate" data-table="employees" data-field="x_BirthDate" value="<?= $Page->BirthDate->EditValue ?>" placeholder="<?= HtmlEncode($Page->BirthDate->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->BirthDate->formatPattern()) ?>"<?= $Page->BirthDate->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->BirthDate->getErrorMessage(false) ?></div>
<?php if (!$Page->BirthDate->ReadOnly && !$Page->BirthDate->Disabled && !isset($Page->BirthDate->EditAttrs["readonly"]) && !isset($Page->BirthDate->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["femployeessearch", "datetimepicker"], function () {
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
    ew.createDateTimePicker("femployeessearch", "x_BirthDate", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->HireDate->Visible) { // HireDate ?>
    <div id="r_HireDate" class="row"<?= $Page->HireDate->rowAttributes() ?>>
        <label for="x_HireDate" class="<?= $Page->LeftColumnClass ?>"><span id="elh_employees_HireDate"><?= $Page->HireDate->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_HireDate" id="z_HireDate" value="=">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->HireDate->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_employees_HireDate" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->HireDate->getInputTextType() ?>" name="x_HireDate" id="x_HireDate" data-table="employees" data-field="x_HireDate" value="<?= $Page->HireDate->EditValue ?>" placeholder="<?= HtmlEncode($Page->HireDate->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->HireDate->formatPattern()) ?>"<?= $Page->HireDate->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->HireDate->getErrorMessage(false) ?></div>
<?php if (!$Page->HireDate->ReadOnly && !$Page->HireDate->Disabled && !isset($Page->HireDate->EditAttrs["readonly"]) && !isset($Page->HireDate->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["femployeessearch", "datetimepicker"], function () {
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
    ew.createDateTimePicker("femployeessearch", "x_HireDate", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->Address->Visible) { // Address ?>
    <div id="r_Address" class="row"<?= $Page->Address->rowAttributes() ?>>
        <label for="x_Address" class="<?= $Page->LeftColumnClass ?>"><span id="elh_employees_Address"><?= $Page->Address->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_Address" id="z_Address" value="LIKE">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->Address->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_employees_Address" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->Address->getInputTextType() ?>" name="x_Address" id="x_Address" data-table="employees" data-field="x_Address" value="<?= $Page->Address->EditValue ?>" size="30" maxlength="60" placeholder="<?= HtmlEncode($Page->Address->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Address->formatPattern()) ?>"<?= $Page->Address->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->Address->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->City->Visible) { // City ?>
    <div id="r_City" class="row"<?= $Page->City->rowAttributes() ?>>
        <label for="x_City" class="<?= $Page->LeftColumnClass ?>"><span id="elh_employees_City"><?= $Page->City->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_City" id="z_City" value="LIKE">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->City->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_employees_City" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->City->getInputTextType() ?>" name="x_City" id="x_City" data-table="employees" data-field="x_City" value="<?= $Page->City->EditValue ?>" size="30" maxlength="15" placeholder="<?= HtmlEncode($Page->City->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->City->formatPattern()) ?>"<?= $Page->City->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->City->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->Region->Visible) { // Region ?>
    <div id="r_Region" class="row"<?= $Page->Region->rowAttributes() ?>>
        <label for="x_Region" class="<?= $Page->LeftColumnClass ?>"><span id="elh_employees_Region"><?= $Page->Region->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_Region" id="z_Region" value="LIKE">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->Region->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_employees_Region" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->Region->getInputTextType() ?>" name="x_Region" id="x_Region" data-table="employees" data-field="x_Region" value="<?= $Page->Region->EditValue ?>" size="30" maxlength="15" placeholder="<?= HtmlEncode($Page->Region->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Region->formatPattern()) ?>"<?= $Page->Region->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->Region->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->PostalCode->Visible) { // PostalCode ?>
    <div id="r_PostalCode" class="row"<?= $Page->PostalCode->rowAttributes() ?>>
        <label for="x_PostalCode" class="<?= $Page->LeftColumnClass ?>"><span id="elh_employees_PostalCode"><?= $Page->PostalCode->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_PostalCode" id="z_PostalCode" value="LIKE">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->PostalCode->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_employees_PostalCode" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->PostalCode->getInputTextType() ?>" name="x_PostalCode" id="x_PostalCode" data-table="employees" data-field="x_PostalCode" value="<?= $Page->PostalCode->EditValue ?>" size="30" maxlength="10" placeholder="<?= HtmlEncode($Page->PostalCode->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->PostalCode->formatPattern()) ?>"<?= $Page->PostalCode->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->PostalCode->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->Country->Visible) { // Country ?>
    <div id="r_Country" class="row"<?= $Page->Country->rowAttributes() ?>>
        <label for="x_Country" class="<?= $Page->LeftColumnClass ?>"><span id="elh_employees_Country"><?= $Page->Country->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_Country" id="z_Country" value="LIKE">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->Country->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_employees_Country" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->Country->getInputTextType() ?>" name="x_Country" id="x_Country" data-table="employees" data-field="x_Country" value="<?= $Page->Country->EditValue ?>" size="30" maxlength="15" placeholder="<?= HtmlEncode($Page->Country->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Country->formatPattern()) ?>"<?= $Page->Country->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->Country->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->HomePhone->Visible) { // HomePhone ?>
    <div id="r_HomePhone" class="row"<?= $Page->HomePhone->rowAttributes() ?>>
        <label for="x_HomePhone" class="<?= $Page->LeftColumnClass ?>"><span id="elh_employees_HomePhone"><?= $Page->HomePhone->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_HomePhone" id="z_HomePhone" value="LIKE">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->HomePhone->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_employees_HomePhone" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->HomePhone->getInputTextType() ?>" name="x_HomePhone" id="x_HomePhone" data-table="employees" data-field="x_HomePhone" value="<?= $Page->HomePhone->EditValue ?>" size="30" maxlength="24" placeholder="<?= HtmlEncode($Page->HomePhone->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->HomePhone->formatPattern()) ?>"<?= $Page->HomePhone->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->HomePhone->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->Extension->Visible) { // Extension ?>
    <div id="r_Extension" class="row"<?= $Page->Extension->rowAttributes() ?>>
        <label for="x_Extension" class="<?= $Page->LeftColumnClass ?>"><span id="elh_employees_Extension"><?= $Page->Extension->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_Extension" id="z_Extension" value="LIKE">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->Extension->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_employees_Extension" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->Extension->getInputTextType() ?>" name="x_Extension" id="x_Extension" data-table="employees" data-field="x_Extension" value="<?= $Page->Extension->EditValue ?>" size="30" maxlength="4" placeholder="<?= HtmlEncode($Page->Extension->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Extension->formatPattern()) ?>"<?= $Page->Extension->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->Extension->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->Photo->Visible) { // Photo ?>
    <div id="r_Photo" class="row"<?= $Page->Photo->rowAttributes() ?>>
        <label class="<?= $Page->LeftColumnClass ?>"><span id="elh_employees_Photo"><?= $Page->Photo->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_Photo" id="z_Photo" value="LIKE">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->Photo->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_employees_Photo" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->Photo->getInputTextType() ?>" name="x_Photo" id="x_Photo" data-table="employees" data-field="x_Photo" value="<?= $Page->Photo->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->Photo->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Photo->formatPattern()) ?>"<?= $Page->Photo->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->Photo->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->Notes->Visible) { // Notes ?>
    <div id="r_Notes" class="row"<?= $Page->Notes->rowAttributes() ?>>
        <label class="<?= $Page->LeftColumnClass ?>"><span id="elh_employees_Notes"><?= $Page->Notes->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_Notes" id="z_Notes" value="LIKE">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->Notes->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_employees_Notes" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->Notes->getInputTextType() ?>" name="x_Notes" id="x_Notes" data-table="employees" data-field="x_Notes" value="<?= $Page->Notes->EditValue ?>" size="35" placeholder="<?= HtmlEncode($Page->Notes->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Notes->formatPattern()) ?>"<?= $Page->Notes->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->Notes->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->ReportsTo->Visible) { // ReportsTo ?>
    <div id="r_ReportsTo" class="row"<?= $Page->ReportsTo->rowAttributes() ?>>
        <label for="x_ReportsTo" class="<?= $Page->LeftColumnClass ?>"><span id="elh_employees_ReportsTo"><?= $Page->ReportsTo->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_ReportsTo" id="z_ReportsTo" value="=">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->ReportsTo->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_employees_ReportsTo" class="ew-search-field ew-search-field-single">
    <select
        id="x_ReportsTo"
        name="x_ReportsTo"
        class="form-select ew-select<?= $Page->ReportsTo->isInvalidClass() ?>"
        <?php if (!$Page->ReportsTo->IsNativeSelect) { ?>
        data-select2-id="femployeessearch_x_ReportsTo"
        <?php } ?>
        data-table="employees"
        data-field="x_ReportsTo"
        data-value-separator="<?= $Page->ReportsTo->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->ReportsTo->getPlaceHolder()) ?>"
        <?= $Page->ReportsTo->editAttributes() ?>>
        <?= $Page->ReportsTo->selectOptionListHtml("x_ReportsTo") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->ReportsTo->getErrorMessage(false) ?></div>
<?= $Page->ReportsTo->Lookup->getParamTag($Page, "p_x_ReportsTo") ?>
<?php if (!$Page->ReportsTo->IsNativeSelect) { ?>
<script>
loadjs.ready("femployeessearch", function() {
    var options = { name: "x_ReportsTo", selectId: "femployeessearch_x_ReportsTo" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (femployeessearch.lists.ReportsTo?.lookupOptions.length) {
        options.data = { id: "x_ReportsTo", form: "femployeessearch" };
    } else {
        options.ajax = { id: "x_ReportsTo", form: "femployeessearch", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.employees.fields.ReportsTo.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->_Password->Visible) { // Password ?>
    <div id="r__Password" class="row"<?= $Page->_Password->rowAttributes() ?>>
        <label for="x__Password" class="<?= $Page->LeftColumnClass ?>"><span id="elh_employees__Password"><?= $Page->_Password->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z__Password" id="z__Password" value="LIKE">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->_Password->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_employees__Password" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->_Password->getInputTextType() ?>" name="x__Password" id="x__Password" data-table="employees" data-field="x__Password" value="<?= $Page->_Password->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->_Password->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->_Password->formatPattern()) ?>"<?= $Page->_Password->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->_Password->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->_UserLevel->Visible) { // UserLevel ?>
    <div id="r__UserLevel" class="row"<?= $Page->_UserLevel->rowAttributes() ?>>
        <label for="x__UserLevel" class="<?= $Page->LeftColumnClass ?>"><span id="elh_employees__UserLevel"><?= $Page->_UserLevel->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z__UserLevel" id="z__UserLevel" value="=">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->_UserLevel->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_employees__UserLevel" class="ew-search-field ew-search-field-single">
<?php if (!$Security->isAdmin() && $Security->isLoggedIn()) { // Non system admin ?>
<span class="form-control-plaintext"><?= $Page->_UserLevel->getDisplayValue($Page->_UserLevel->EditValue) ?></span>
<?php } else { ?>
    <select
        id="x__UserLevel"
        name="x__UserLevel"
        class="form-select ew-select<?= $Page->_UserLevel->isInvalidClass() ?>"
        <?php if (!$Page->_UserLevel->IsNativeSelect) { ?>
        data-select2-id="femployeessearch_x__UserLevel"
        <?php } ?>
        data-table="employees"
        data-field="x__UserLevel"
        data-value-separator="<?= $Page->_UserLevel->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->_UserLevel->getPlaceHolder()) ?>"
        <?= $Page->_UserLevel->editAttributes() ?>>
        <?= $Page->_UserLevel->selectOptionListHtml("x__UserLevel") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->_UserLevel->getErrorMessage(false) ?></div>
<?= $Page->_UserLevel->Lookup->getParamTag($Page, "p_x__UserLevel") ?>
<?php if (!$Page->_UserLevel->IsNativeSelect) { ?>
<script>
loadjs.ready("femployeessearch", function() {
    var options = { name: "x__UserLevel", selectId: "femployeessearch_x__UserLevel" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (femployeessearch.lists._UserLevel?.lookupOptions.length) {
        options.data = { id: "x__UserLevel", form: "femployeessearch" };
    } else {
        options.ajax = { id: "x__UserLevel", form: "femployeessearch", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.employees.fields._UserLevel.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
<?php } ?>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->_Email->Visible) { // Email ?>
    <div id="r__Email" class="row"<?= $Page->_Email->rowAttributes() ?>>
        <label for="x__Email" class="<?= $Page->LeftColumnClass ?>"><span id="elh_employees__Email"><?= $Page->_Email->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z__Email" id="z__Email" value="LIKE">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->_Email->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_employees__Email" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->_Email->getInputTextType() ?>" name="x__Email" id="x__Email" data-table="employees" data-field="x__Email" value="<?= $Page->_Email->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->_Email->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->_Email->formatPattern()) ?>"<?= $Page->_Email->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->_Email->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->Activated->Visible) { // Activated ?>
    <div id="r_Activated" class="row"<?= $Page->Activated->rowAttributes() ?>>
        <label class="<?= $Page->LeftColumnClass ?>"><span id="elh_employees_Activated"><?= $Page->Activated->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_Activated" id="z_Activated" value="=">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->Activated->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_employees_Activated" class="ew-search-field ew-search-field-single">
<div class="form-check d-inline-block">
    <input type="checkbox" class="form-check-input<?= $Page->Activated->isInvalidClass() ?>" data-table="employees" data-field="x_Activated" data-boolean name="x_Activated" id="x_Activated" value="1"<?= ConvertToBool($Page->Activated->AdvancedSearch->SearchValue) ? " checked" : "" ?><?= $Page->Activated->editAttributes() ?>>
    <div class="invalid-feedback"><?= $Page->Activated->getErrorMessage(false) ?></div>
</div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->_Profile->Visible) { // Profile ?>
    <div id="r__Profile" class="row"<?= $Page->_Profile->rowAttributes() ?>>
        <label for="x__Profile" class="<?= $Page->LeftColumnClass ?>"><span id="elh_employees__Profile"><?= $Page->_Profile->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z__Profile" id="z__Profile" value="LIKE">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->_Profile->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_employees__Profile" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->_Profile->getInputTextType() ?>" name="x__Profile" id="x__Profile" data-table="employees" data-field="x__Profile" value="<?= $Page->_Profile->EditValue ?>" size="35" placeholder="<?= HtmlEncode($Page->_Profile->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->_Profile->formatPattern()) ?>"<?= $Page->_Profile->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->_Profile->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
        <button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="femployeessearch"><?= $Language->phrase("Search") ?></button>
        <?php if ($Page->IsModal) { ?>
        <button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="femployeessearch"><?= $Language->phrase("Cancel") ?></button>
        <?php } else { ?>
        <button class="btn btn-default ew-btn" name="btn-reset" id="btn-reset" type="button" form="femployeessearch" data-ew-action="reload"><?= $Language->phrase("Reset") ?></button>
        <?php } ?>
    </div><!-- /buttons offset -->
<?= $Page->IsModal ? "</template>" : "</div>" ?><!-- /buttons .row -->
</form>
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
