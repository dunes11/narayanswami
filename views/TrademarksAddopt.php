<?php

namespace PHPMaker2023\demo2023;

// Page object
$TrademarksAddopt = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { trademarks: currentTable } });
var currentPageID = ew.PAGE_ID = "addopt";
var currentForm;
var ftrademarksaddopt;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("ftrademarksaddopt")
        .setPageId("addopt")

        // Add fields
        .setFields([
            ["Trademark", [fields.Trademark.visible && fields.Trademark.required ? ew.Validators.required(fields.Trademark.caption) : null], fields.Trademark.isInvalid]
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
<form name="ftrademarksaddopt" id="ftrademarksaddopt" class="ew-form" action="<?= HtmlEncode(GetUrl(Config("API_URL"))) ?>" method="post" novalidate autocomplete="on">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="<?= Config("API_ACTION_NAME") ?>" id="<?= Config("API_ACTION_NAME") ?>" value="<?= Config("API_ADD_ACTION") ?>">
<input type="hidden" name="<?= Config("API_OBJECT_NAME") ?>" id="<?= Config("API_OBJECT_NAME") ?>" value="trademarks">
<input type="hidden" name="addopt" id="addopt" value="1">
<?php if ($Page->Trademark->Visible) { // Trademark ?>
    <div<?= $Page->Trademark->rowAttributes() ?>>
        <label class="col-sm-2 col-form-label ew-label" for="x_Trademark"><?= $Page->Trademark->caption() ?><?= $Page->Trademark->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10"><div<?= $Page->Trademark->cellAttributes() ?>>
<input type="<?= $Page->Trademark->getInputTextType() ?>" name="x_Trademark" id="x_Trademark" data-table="trademarks" data-field="x_Trademark" value="<?= $Page->Trademark->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->Trademark->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Trademark->formatPattern()) ?>"<?= $Page->Trademark->editAttributes() ?> aria-describedby="x_Trademark_help">
<?= $Page->Trademark->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->Trademark->getErrorMessage() ?></div>
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
    ew.addEventHandlers("trademarks");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
