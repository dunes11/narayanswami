<?php

namespace PHPMaker2023\demo2023;

// Page object
$ModelsSearch = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { models: currentTable } });
var currentPageID = ew.PAGE_ID = "search";
var currentForm;
var fmodelssearch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery,
        fields = currentTable.fields;

    // Form object for search
    let form = new ew.FormBuilder()
        .setId("fmodelssearch")
        .setPageId("search")
<?php if ($Page->IsModal && $Page->UseAjaxActions) { ?>
        .setSubmitWithFetch(true)
<?php } ?>

        // Add fields
        .addFields([
            ["ID", [ew.Validators.integer], fields.ID.isInvalid],
            ["Trademark", [], fields.Trademark.isInvalid],
            ["Model", [], fields.Model.isInvalid]
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
            "Trademark": <?= $Page->Trademark->toClientList($Page) ?>,
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
<form name="fmodelssearch" id="fmodelssearch" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="on">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="models">
<input type="hidden" name="action" id="action" value="search">
<?php if ($Page->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div class="ew-search-div"><!-- page* -->
<?php if ($Page->ID->Visible) { // ID ?>
    <div id="r_ID" class="row"<?= $Page->ID->rowAttributes() ?>>
        <label for="x_ID" class="<?= $Page->LeftColumnClass ?>"><span id="elh_models_ID"><?= $Page->ID->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_ID" id="z_ID" value="=">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->ID->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_models_ID" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->ID->getInputTextType() ?>" name="x_ID" id="x_ID" data-table="models" data-field="x_ID" value="<?= $Page->ID->EditValue ?>" placeholder="<?= HtmlEncode($Page->ID->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->ID->formatPattern()) ?>"<?= $Page->ID->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->ID->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->Trademark->Visible) { // Trademark ?>
    <div id="r_Trademark" class="row"<?= $Page->Trademark->rowAttributes() ?>>
        <label for="x_Trademark" class="<?= $Page->LeftColumnClass ?>"><span id="elh_models_Trademark"><?= $Page->Trademark->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_Trademark" id="z_Trademark" value="=">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->Trademark->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_models_Trademark" class="ew-search-field ew-search-field-single">
    <select
        id="x_Trademark"
        name="x_Trademark"
        class="form-select ew-select<?= $Page->Trademark->isInvalidClass() ?>"
        <?php if (!$Page->Trademark->IsNativeSelect) { ?>
        data-select2-id="fmodelssearch_x_Trademark"
        <?php } ?>
        data-table="models"
        data-field="x_Trademark"
        data-value-separator="<?= $Page->Trademark->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->Trademark->getPlaceHolder()) ?>"
        <?= $Page->Trademark->editAttributes() ?>>
        <?= $Page->Trademark->selectOptionListHtml("x_Trademark") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->Trademark->getErrorMessage(false) ?></div>
<?= $Page->Trademark->Lookup->getParamTag($Page, "p_x_Trademark") ?>
<?php if (!$Page->Trademark->IsNativeSelect) { ?>
<script>
loadjs.ready("fmodelssearch", function() {
    var options = { name: "x_Trademark", selectId: "fmodelssearch_x_Trademark" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fmodelssearch.lists.Trademark?.lookupOptions.length) {
        options.data = { id: "x_Trademark", form: "fmodelssearch" };
    } else {
        options.ajax = { id: "x_Trademark", form: "fmodelssearch", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.models.fields.Trademark.selectOptions);
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
<?php if ($Page->Model->Visible) { // Model ?>
    <div id="r_Model" class="row"<?= $Page->Model->rowAttributes() ?>>
        <label for="x_Model" class="<?= $Page->LeftColumnClass ?>"><span id="elh_models_Model"><?= $Page->Model->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_Model" id="z_Model" value="LIKE">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->Model->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_models_Model" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->Model->getInputTextType() ?>" name="x_Model" id="x_Model" data-table="models" data-field="x_Model" value="<?= $Page->Model->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->Model->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Model->formatPattern()) ?>"<?= $Page->Model->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->Model->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
        <button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="fmodelssearch"><?= $Language->phrase("Search") ?></button>
        <?php if ($Page->IsModal) { ?>
        <button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="fmodelssearch"><?= $Language->phrase("Cancel") ?></button>
        <?php } else { ?>
        <button class="btn btn-default ew-btn" name="btn-reset" id="btn-reset" type="button" form="fmodelssearch" data-ew-action="reload"><?= $Language->phrase("Reset") ?></button>
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
