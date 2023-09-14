<?php

namespace PHPMaker2023\demo2023;

// Page object
$Calendar1Edit = &$Page;
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
<form name="fCalendar1edit" id="fCalendar1edit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="on">
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { Calendar1: currentTable } });
var currentPageID = ew.PAGE_ID = "edit";
var currentForm;
var fCalendar1edit;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fCalendar1edit")
        .setPageId("edit")

        // Add fields
        .setFields([
            ["Id", [fields.Id.visible && fields.Id.required ? ew.Validators.required(fields.Id.caption) : null], fields.Id.isInvalid],
            ["_Title", [fields._Title.visible && fields._Title.required ? ew.Validators.required(fields._Title.caption) : null], fields._Title.isInvalid],
            ["Start", [fields.Start.visible && fields.Start.required ? ew.Validators.required(fields.Start.caption) : null, ew.Validators.datetime(fields.Start.clientFormatPattern)], fields.Start.isInvalid],
            ["End", [fields.End.visible && fields.End.required ? ew.Validators.required(fields.End.caption) : null, ew.Validators.datetime(fields.End.clientFormatPattern), ew.Validators.minDate(() => fCalendar1edit.getFieldElements("Start"))], fields.End.isInvalid],
            ["AllDay", [fields.AllDay.visible && fields.AllDay.required ? ew.Validators.required(fields.AllDay.caption) : null], fields.AllDay.isInvalid],
            ["Description", [fields.Description.visible && fields.Description.required ? ew.Validators.required(fields.Description.caption) : null], fields.Description.isInvalid],
            ["GroupId", [fields.GroupId.visible && fields.GroupId.required ? ew.Validators.required(fields.GroupId.caption) : null], fields.GroupId.isInvalid],
            ["Url", [fields.Url.visible && fields.Url.required ? ew.Validators.required(fields.Url.caption) : null], fields.Url.isInvalid],
            ["ClassNames", [fields.ClassNames.visible && fields.ClassNames.required ? ew.Validators.required(fields.ClassNames.caption) : null], fields.ClassNames.isInvalid],
            ["Display", [fields.Display.visible && fields.Display.required ? ew.Validators.required(fields.Display.caption) : null], fields.Display.isInvalid],
            ["BackgroundColor", [fields.BackgroundColor.visible && fields.BackgroundColor.required ? ew.Validators.required(fields.BackgroundColor.caption) : null], fields.BackgroundColor.isInvalid]
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
            "AllDay": <?= $Page->AllDay->toClientList($Page) ?>,
            "ClassNames": <?= $Page->ClassNames->toClientList($Page) ?>,
            "Display": <?= $Page->Display->toClientList($Page) ?>,
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
<input type="hidden" name="t" value="Calendar1">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->Id->Visible) { // Id ?>
    <div id="r_Id"<?= $Page->Id->rowAttributes() ?>>
        <label id="elh_Calendar1_Id" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Id->caption() ?><?= $Page->Id->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Id->cellAttributes() ?>>
<span id="el_Calendar1_Id">
<span<?= $Page->Id->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->Id->getDisplayValue($Page->Id->EditValue))) ?>"></span>
<input type="hidden" data-table="Calendar1" data-field="x_Id" data-hidden="1" name="x_Id" id="x_Id" value="<?= HtmlEncode($Page->Id->CurrentValue) ?>">
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->_Title->Visible) { // Title ?>
    <div id="r__Title"<?= $Page->_Title->rowAttributes() ?>>
        <label id="elh_Calendar1__Title" for="x__Title" class="<?= $Page->LeftColumnClass ?>"><?= $Page->_Title->caption() ?><?= $Page->_Title->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->_Title->cellAttributes() ?>>
<span id="el_Calendar1__Title">
<input type="<?= $Page->_Title->getInputTextType() ?>" name="x__Title" id="x__Title" data-table="Calendar1" data-field="x__Title" value="<?= $Page->_Title->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->_Title->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->_Title->formatPattern()) ?>"<?= $Page->_Title->editAttributes() ?> aria-describedby="x__Title_help">
<?= $Page->_Title->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->_Title->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->Start->Visible) { // Start ?>
    <div id="r_Start"<?= $Page->Start->rowAttributes() ?>>
        <label id="elh_Calendar1_Start" for="x_Start" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Start->caption() ?><?= $Page->Start->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Start->cellAttributes() ?>>
<span id="el_Calendar1_Start">
<input type="<?= $Page->Start->getInputTextType() ?>" name="x_Start" id="x_Start" data-table="Calendar1" data-field="x_Start" value="<?= $Page->Start->EditValue ?>" placeholder="<?= HtmlEncode($Page->Start->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Start->formatPattern()) ?>"<?= $Page->Start->editAttributes() ?> aria-describedby="x_Start_help">
<?= $Page->Start->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->Start->getErrorMessage() ?></div>
<?php if (!$Page->Start->ReadOnly && !$Page->Start->Disabled && !isset($Page->Start->EditAttrs["readonly"]) && !isset($Page->Start->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fCalendar1edit", "datetimepicker"], function () {
    let format = "<?= DateFormat(1) ?>",
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
    ew.createDateTimePicker("fCalendar1edit", "x_Start", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":true}}, options));
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->End->Visible) { // End ?>
    <div id="r_End"<?= $Page->End->rowAttributes() ?>>
        <label id="elh_Calendar1_End" for="x_End" class="<?= $Page->LeftColumnClass ?>"><?= $Page->End->caption() ?><?= $Page->End->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->End->cellAttributes() ?>>
<span id="el_Calendar1_End">
<input type="<?= $Page->End->getInputTextType() ?>" name="x_End" id="x_End" data-table="Calendar1" data-field="x_End" value="<?= $Page->End->EditValue ?>" placeholder="<?= HtmlEncode($Page->End->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->End->formatPattern()) ?>"<?= $Page->End->editAttributes() ?> aria-describedby="x_End_help">
<?= $Page->End->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->End->getErrorMessage() ?></div>
<?php if (!$Page->End->ReadOnly && !$Page->End->Disabled && !isset($Page->End->EditAttrs["readonly"]) && !isset($Page->End->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fCalendar1edit", "datetimepicker"], function () {
    let format = "<?= DateFormat(1) ?>",
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
    ew.createDateTimePicker("fCalendar1edit", "x_End", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":true}}, options));
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->AllDay->Visible) { // AllDay ?>
    <div id="r_AllDay"<?= $Page->AllDay->rowAttributes() ?>>
        <label id="elh_Calendar1_AllDay" class="<?= $Page->LeftColumnClass ?>"><?= $Page->AllDay->caption() ?><?= $Page->AllDay->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->AllDay->cellAttributes() ?>>
<span id="el_Calendar1_AllDay">
<div class="form-check d-inline-block">
    <input type="checkbox" class="form-check-input<?= $Page->AllDay->isInvalidClass() ?>" data-table="Calendar1" data-field="x_AllDay" data-boolean name="x_AllDay" id="x_AllDay" value="1"<?= ConvertToBool($Page->AllDay->CurrentValue) ? " checked" : "" ?><?= $Page->AllDay->editAttributes() ?> aria-describedby="x_AllDay_help">
    <div class="invalid-feedback"><?= $Page->AllDay->getErrorMessage() ?></div>
</div>
<?= $Page->AllDay->getCustomMessage() ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->Description->Visible) { // Description ?>
    <div id="r_Description"<?= $Page->Description->rowAttributes() ?>>
        <label id="elh_Calendar1_Description" for="x_Description" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Description->caption() ?><?= $Page->Description->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Description->cellAttributes() ?>>
<span id="el_Calendar1_Description">
<textarea data-table="Calendar1" data-field="x_Description" name="x_Description" id="x_Description" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->Description->getPlaceHolder()) ?>"<?= $Page->Description->editAttributes() ?> aria-describedby="x_Description_help"><?= $Page->Description->EditValue ?></textarea>
<?= $Page->Description->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->Description->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->GroupId->Visible) { // GroupId ?>
    <div id="r_GroupId"<?= $Page->GroupId->rowAttributes() ?>>
        <label id="elh_Calendar1_GroupId" for="x_GroupId" class="<?= $Page->LeftColumnClass ?>"><?= $Page->GroupId->caption() ?><?= $Page->GroupId->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->GroupId->cellAttributes() ?>>
<span id="el_Calendar1_GroupId">
<input type="<?= $Page->GroupId->getInputTextType() ?>" name="x_GroupId" id="x_GroupId" data-table="Calendar1" data-field="x_GroupId" value="<?= $Page->GroupId->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->GroupId->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->GroupId->formatPattern()) ?>"<?= $Page->GroupId->editAttributes() ?> aria-describedby="x_GroupId_help">
<?= $Page->GroupId->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->GroupId->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->Url->Visible) { // Url ?>
    <div id="r_Url"<?= $Page->Url->rowAttributes() ?>>
        <label id="elh_Calendar1_Url" for="x_Url" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Url->caption() ?><?= $Page->Url->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Url->cellAttributes() ?>>
<span id="el_Calendar1_Url">
<input type="<?= $Page->Url->getInputTextType() ?>" name="x_Url" id="x_Url" data-table="Calendar1" data-field="x_Url" value="<?= $Page->Url->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->Url->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Url->formatPattern()) ?>"<?= $Page->Url->editAttributes() ?> aria-describedby="x_Url_help">
<?= $Page->Url->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->Url->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->ClassNames->Visible) { // ClassNames ?>
    <div id="r_ClassNames"<?= $Page->ClassNames->rowAttributes() ?>>
        <label id="elh_Calendar1_ClassNames" class="<?= $Page->LeftColumnClass ?>"><?= $Page->ClassNames->caption() ?><?= $Page->ClassNames->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->ClassNames->cellAttributes() ?>>
<span id="el_Calendar1_ClassNames">
<?php
if (IsRTL()) {
    $Page->ClassNames->EditAttrs["dir"] = "rtl";
}
?>
<span id="as_x_ClassNames" class="ew-auto-suggest">
    <input type="<?= $Page->ClassNames->getInputTextType() ?>" class="form-control" name="sv_x_ClassNames" id="sv_x_ClassNames" value="<?= RemoveHtml($Page->ClassNames->EditValue) ?>" autocomplete="off" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->ClassNames->getPlaceHolder()) ?>" data-placeholder="<?= HtmlEncode($Page->ClassNames->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->ClassNames->formatPattern()) ?>"<?= $Page->ClassNames->editAttributes() ?> aria-describedby="x_ClassNames_help">
</span>
<selection-list hidden class="form-control" data-table="Calendar1" data-field="x_ClassNames" data-input="sv_x_ClassNames" data-value-separator="<?= $Page->ClassNames->displayValueSeparatorAttribute() ?>" name="x_ClassNames" id="x_ClassNames" value="<?= HtmlEncode($Page->ClassNames->CurrentValue) ?>"></selection-list>
<?= $Page->ClassNames->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->ClassNames->getErrorMessage() ?></div>
<script>
loadjs.ready("fCalendar1edit", function() {
    fCalendar1edit.createAutoSuggest(Object.assign({"id":"x_ClassNames","forceSelect":false}, { lookupAllDisplayFields: <?= $Page->ClassNames->Lookup->LookupAllDisplayFields ? "true" : "false" ?> }, ew.vars.tables.Calendar1.fields.ClassNames.autoSuggestOptions));
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->Display->Visible) { // Display ?>
    <div id="r_Display"<?= $Page->Display->rowAttributes() ?>>
        <label id="elh_Calendar1_Display" for="x_Display" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Display->caption() ?><?= $Page->Display->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Display->cellAttributes() ?>>
<span id="el_Calendar1_Display">
    <select
        id="x_Display"
        name="x_Display"
        class="form-select ew-select<?= $Page->Display->isInvalidClass() ?>"
        <?php if (!$Page->Display->IsNativeSelect) { ?>
        data-select2-id="fCalendar1edit_x_Display"
        <?php } ?>
        data-table="Calendar1"
        data-field="x_Display"
        data-value-separator="<?= $Page->Display->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->Display->getPlaceHolder()) ?>"
        <?= $Page->Display->editAttributes() ?>>
        <?= $Page->Display->selectOptionListHtml("x_Display") ?>
    </select>
    <?= $Page->Display->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->Display->getErrorMessage() ?></div>
<?php if (!$Page->Display->IsNativeSelect) { ?>
<script>
loadjs.ready("fCalendar1edit", function() {
    var options = { name: "x_Display", selectId: "fCalendar1edit_x_Display" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fCalendar1edit.lists.Display?.lookupOptions.length) {
        options.data = { id: "x_Display", form: "fCalendar1edit" };
    } else {
        options.ajax = { id: "x_Display", form: "fCalendar1edit", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.Calendar1.fields.Display.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->BackgroundColor->Visible) { // BackgroundColor ?>
    <div id="r_BackgroundColor"<?= $Page->BackgroundColor->rowAttributes() ?>>
        <label id="elh_Calendar1_BackgroundColor" for="x_BackgroundColor" class="<?= $Page->LeftColumnClass ?>"><?= $Page->BackgroundColor->caption() ?><?= $Page->BackgroundColor->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->BackgroundColor->cellAttributes() ?>>
<span id="el_Calendar1_BackgroundColor">
<input type="<?= $Page->BackgroundColor->getInputTextType() ?>" name="x_BackgroundColor" id="x_BackgroundColor" data-table="Calendar1" data-field="x_BackgroundColor" value="<?= $Page->BackgroundColor->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->BackgroundColor->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->BackgroundColor->formatPattern()) ?>"<?= $Page->BackgroundColor->editAttributes() ?> aria-describedby="x_BackgroundColor_help">
<?= $Page->BackgroundColor->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->BackgroundColor->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="fCalendar1edit"><?= $Language->phrase("SaveBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="fCalendar1edit" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
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
    ew.addEventHandlers("Calendar1");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
