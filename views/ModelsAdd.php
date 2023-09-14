<?php

namespace PHPMaker2023\demo2023;

// Page object
$ModelsAdd = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { models: currentTable } });
var currentPageID = ew.PAGE_ID = "add";
var currentForm;
var fmodelsadd;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fmodelsadd")
        .setPageId("add")

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
<?php
$Page->showMessage();
?>
<form name="fmodelsadd" id="fmodelsadd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="on">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="models">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->Trademark->Visible) { // Trademark ?>
    <div id="r_Trademark"<?= $Page->Trademark->rowAttributes() ?>>
        <label id="elh_models_Trademark" for="x_Trademark" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Trademark->caption() ?><?= $Page->Trademark->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Trademark->cellAttributes() ?>>
<span id="el_models_Trademark">
    <select
        id="x_Trademark"
        name="x_Trademark"
        class="form-select ew-select<?= $Page->Trademark->isInvalidClass() ?>"
        <?php if (!$Page->Trademark->IsNativeSelect) { ?>
        data-select2-id="fmodelsadd_x_Trademark"
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
loadjs.ready("fmodelsadd", function() {
    var options = { name: "x_Trademark", selectId: "fmodelsadd_x_Trademark" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fmodelsadd.lists.Trademark?.lookupOptions.length) {
        options.data = { id: "x_Trademark", form: "fmodelsadd" };
    } else {
        options.ajax = { id: "x_Trademark", form: "fmodelsadd", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.models.fields.Trademark.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->Model->Visible) { // Model ?>
    <div id="r_Model"<?= $Page->Model->rowAttributes() ?>>
        <label id="elh_models_Model" for="x_Model" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Model->caption() ?><?= $Page->Model->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Model->cellAttributes() ?>>
<span id="el_models_Model">
<input type="<?= $Page->Model->getInputTextType() ?>" name="x_Model" id="x_Model" data-table="models" data-field="x_Model" value="<?= $Page->Model->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->Model->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Model->formatPattern()) ?>"<?= $Page->Model->editAttributes() ?> aria-describedby="x_Model_help">
<?= $Page->Model->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->Model->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="fmodelsadd"><?= $Language->phrase("AddBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="fmodelsadd" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
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
    ew.addEventHandlers("models");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
