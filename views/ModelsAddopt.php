<?php

namespace PHPMaker2023\demo2023;

// Page object
$ModelsAddopt = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { models: currentTable } });
var currentPageID = ew.PAGE_ID = "addopt";
var currentForm;
var fmodelsaddopt;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fmodelsaddopt")
        .setPageId("addopt")

        // Add fields
        .setFields([
            ["Trademark", [fields.Trademark.visible && fields.Trademark.required ? ew.Validators.required(fields.Trademark.caption) : null], fields.Trademark.isInvalid],
            ["Model", [fields.Model.visible && fields.Model.required ? ew.Validators.required(fields.Model.caption) : null], fields.Model.isInvalid]
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
            "Trademark": <?= $Page->Trademark->toClientList($Page) ?>,
        })
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
<form name="fmodelsaddopt" id="fmodelsaddopt" class="ew-form" action="<?= HtmlEncode(GetUrl(Config("API_URL"))) ?>" method="post" novalidate autocomplete="on">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="<?= Config("API_ACTION_NAME") ?>" id="<?= Config("API_ACTION_NAME") ?>" value="<?= Config("API_ADD_ACTION") ?>">
<input type="hidden" name="<?= Config("API_OBJECT_NAME") ?>" id="<?= Config("API_OBJECT_NAME") ?>" value="models">
<input type="hidden" name="addopt" id="addopt" value="1">
<?php if ($Page->Trademark->Visible) { // Trademark ?>
    <div<?= $Page->Trademark->rowAttributes() ?>>
        <label class="col-sm-2 col-form-label ew-label" for="x_Trademark"><?= $Page->Trademark->caption() ?><?= $Page->Trademark->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10"><div<?= $Page->Trademark->cellAttributes() ?>>
    <select
        id="x_Trademark"
        name="x_Trademark"
        class="form-select ew-select<?= $Page->Trademark->isInvalidClass() ?>"
        <?php if (!$Page->Trademark->IsNativeSelect) { ?>
        data-select2-id="fmodelsaddopt_x_Trademark"
        <?php } ?>
        data-table="models"
        data-field="x_Trademark"
        data-value-separator="<?= $Page->Trademark->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->Trademark->getPlaceHolder()) ?>"
        <?= $Page->Trademark->editAttributes() ?>>
        <?= $Page->Trademark->selectOptionListHtml("x_Trademark") ?>
    </select>
    <?= $Page->Trademark->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->Trademark->getErrorMessage() ?></div>
<?= $Page->Trademark->Lookup->getParamTag($Page, "p_x_Trademark") ?>
<?php if (!$Page->Trademark->IsNativeSelect) { ?>
<script>
loadjs.ready("fmodelsaddopt", function() {
    var options = { name: "x_Trademark", selectId: "fmodelsaddopt_x_Trademark" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fmodelsaddopt.lists.Trademark?.lookupOptions.length) {
        options.data = { id: "x_Trademark", form: "fmodelsaddopt" };
    } else {
        options.ajax = { id: "x_Trademark", form: "fmodelsaddopt", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.models.fields.Trademark.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->Model->Visible) { // Model ?>
    <div<?= $Page->Model->rowAttributes() ?>>
        <label class="col-sm-2 col-form-label ew-label" for="x_Model"><?= $Page->Model->caption() ?><?= $Page->Model->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10"><div<?= $Page->Model->cellAttributes() ?>>
<input type="<?= $Page->Model->getInputTextType() ?>" name="x_Model" id="x_Model" data-table="models" data-field="x_Model" value="<?= $Page->Model->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->Model->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Model->formatPattern()) ?>"<?= $Page->Model->editAttributes() ?> aria-describedby="x_Model_help">
<?= $Page->Model->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->Model->getErrorMessage() ?></div>
</div></div>
    </div>
<?php } ?>
</form>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<script>
// Field event handlers
loadjs.ready("head", function() {
    ew.addEventHandlers("models");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
