<?php

namespace PHPMaker2023\demo2023;

// Page object
$TrademarksSearch = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { trademarks: currentTable } });
var currentPageID = ew.PAGE_ID = "search";
var currentForm;
var ftrademarkssearch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery,
        fields = currentTable.fields;

    // Form object for search
    let form = new ew.FormBuilder()
        .setId("ftrademarkssearch")
        .setPageId("search")
<?php if ($Page->IsModal && $Page->UseAjaxActions) { ?>
        .setSubmitWithFetch(true)
<?php } ?>

        // Add fields
        .addFields([
            ["ID", [ew.Validators.integer], fields.ID.isInvalid],
            ["Trademark", [], fields.Trademark.isInvalid]
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
<form name="ftrademarkssearch" id="ftrademarkssearch" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="on">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="trademarks">
<input type="hidden" name="action" id="action" value="search">
<?php if ($Page->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div class="ew-search-div"><!-- page* -->
<?php if ($Page->ID->Visible) { // ID ?>
    <div id="r_ID" class="row"<?= $Page->ID->rowAttributes() ?>>
        <label for="x_ID" class="<?= $Page->LeftColumnClass ?>"><span id="elh_trademarks_ID"><?= $Page->ID->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_ID" id="z_ID" value="=">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->ID->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_trademarks_ID" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->ID->getInputTextType() ?>" name="x_ID" id="x_ID" data-table="trademarks" data-field="x_ID" value="<?= $Page->ID->EditValue ?>" placeholder="<?= HtmlEncode($Page->ID->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->ID->formatPattern()) ?>"<?= $Page->ID->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->ID->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->Trademark->Visible) { // Trademark ?>
    <div id="r_Trademark" class="row"<?= $Page->Trademark->rowAttributes() ?>>
        <label for="x_Trademark" class="<?= $Page->LeftColumnClass ?>"><span id="elh_trademarks_Trademark"><?= $Page->Trademark->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_Trademark" id="z_Trademark" value="LIKE">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->Trademark->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_trademarks_Trademark" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->Trademark->getInputTextType() ?>" name="x_Trademark" id="x_Trademark" data-table="trademarks" data-field="x_Trademark" value="<?= $Page->Trademark->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->Trademark->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Trademark->formatPattern()) ?>"<?= $Page->Trademark->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->Trademark->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
        <button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="ftrademarkssearch"><?= $Language->phrase("Search") ?></button>
        <?php if ($Page->IsModal) { ?>
        <button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="ftrademarkssearch"><?= $Language->phrase("Cancel") ?></button>
        <?php } else { ?>
        <button class="btn btn-default ew-btn" name="btn-reset" id="btn-reset" type="button" form="ftrademarkssearch" data-ew-action="reload"><?= $Language->phrase("Reset") ?></button>
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
    ew.addEventHandlers("trademarks");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
