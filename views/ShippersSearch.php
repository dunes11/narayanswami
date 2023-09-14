<?php

namespace PHPMaker2023\demo2023;

// Page object
$ShippersSearch = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { shippers: currentTable } });
var currentPageID = ew.PAGE_ID = "search";
var currentForm;
var fshipperssearch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery,
        fields = currentTable.fields;

    // Form object for search
    let form = new ew.FormBuilder()
        .setId("fshipperssearch")
        .setPageId("search")
<?php if ($Page->IsModal && $Page->UseAjaxActions) { ?>
        .setSubmitWithFetch(true)
<?php } ?>

        // Add fields
        .addFields([
            ["ShipperID", [ew.Validators.integer], fields.ShipperID.isInvalid],
            ["CompanyName", [], fields.CompanyName.isInvalid],
            ["Phone", [], fields.Phone.isInvalid]
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
<form name="fshipperssearch" id="fshipperssearch" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="on">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="shippers">
<input type="hidden" name="action" id="action" value="search">
<?php if ($Page->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div class="ew-search-div"><!-- page* -->
<?php if ($Page->ShipperID->Visible) { // ShipperID ?>
    <div id="r_ShipperID" class="row"<?= $Page->ShipperID->rowAttributes() ?>>
        <label for="x_ShipperID" class="<?= $Page->LeftColumnClass ?>"><span id="elh_shippers_ShipperID"><?= $Page->ShipperID->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_ShipperID" id="z_ShipperID" value="=">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->ShipperID->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_shippers_ShipperID" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->ShipperID->getInputTextType() ?>" name="x_ShipperID" id="x_ShipperID" data-table="shippers" data-field="x_ShipperID" value="<?= $Page->ShipperID->EditValue ?>" placeholder="<?= HtmlEncode($Page->ShipperID->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->ShipperID->formatPattern()) ?>"<?= $Page->ShipperID->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->ShipperID->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->CompanyName->Visible) { // CompanyName ?>
    <div id="r_CompanyName" class="row"<?= $Page->CompanyName->rowAttributes() ?>>
        <label for="x_CompanyName" class="<?= $Page->LeftColumnClass ?>"><span id="elh_shippers_CompanyName"><?= $Page->CompanyName->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_CompanyName" id="z_CompanyName" value="LIKE">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->CompanyName->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_shippers_CompanyName" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->CompanyName->getInputTextType() ?>" name="x_CompanyName" id="x_CompanyName" data-table="shippers" data-field="x_CompanyName" value="<?= $Page->CompanyName->EditValue ?>" size="30" maxlength="40" placeholder="<?= HtmlEncode($Page->CompanyName->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->CompanyName->formatPattern()) ?>"<?= $Page->CompanyName->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->CompanyName->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->Phone->Visible) { // Phone ?>
    <div id="r_Phone" class="row"<?= $Page->Phone->rowAttributes() ?>>
        <label for="x_Phone" class="<?= $Page->LeftColumnClass ?>"><span id="elh_shippers_Phone"><?= $Page->Phone->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_Phone" id="z_Phone" value="LIKE">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->Phone->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_shippers_Phone" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->Phone->getInputTextType() ?>" name="x_Phone" id="x_Phone" data-table="shippers" data-field="x_Phone" value="<?= $Page->Phone->EditValue ?>" size="30" maxlength="24" placeholder="<?= HtmlEncode($Page->Phone->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Phone->formatPattern()) ?>"<?= $Page->Phone->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->Phone->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
        <button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="fshipperssearch"><?= $Language->phrase("Search") ?></button>
        <?php if ($Page->IsModal) { ?>
        <button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="fshipperssearch"><?= $Language->phrase("Cancel") ?></button>
        <?php } else { ?>
        <button class="btn btn-default ew-btn" name="btn-reset" id="btn-reset" type="button" form="fshipperssearch" data-ew-action="reload"><?= $Language->phrase("Reset") ?></button>
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
    ew.addEventHandlers("shippers");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
