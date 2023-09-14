<?php

namespace PHPMaker2023\demo2023;

// Page object
$CategoriesSearch = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { categories: currentTable } });
var currentPageID = ew.PAGE_ID = "search";
var currentForm;
var fcategoriessearch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery,
        fields = currentTable.fields;

    // Form object for search
    let form = new ew.FormBuilder()
        .setId("fcategoriessearch")
        .setPageId("search")
<?php if ($Page->IsModal && $Page->UseAjaxActions) { ?>
        .setSubmitWithFetch(true)
<?php } ?>

        // Add fields
        .addFields([
            ["CategoryID", [ew.Validators.integer], fields.CategoryID.isInvalid],
            ["CategoryName", [], fields.CategoryName.isInvalid],
            ["Description", [], fields.Description.isInvalid]
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
<form name="fcategoriessearch" id="fcategoriessearch" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="on">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="categories">
<input type="hidden" name="action" id="action" value="search">
<?php if ($Page->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div class="ew-search-div"><!-- page* -->
<?php if ($Page->CategoryID->Visible) { // CategoryID ?>
    <div id="r_CategoryID" class="row"<?= $Page->CategoryID->rowAttributes() ?>>
        <label for="x_CategoryID" class="<?= $Page->LeftColumnClass ?>"><span id="elh_categories_CategoryID"><?= $Page->CategoryID->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_CategoryID" id="z_CategoryID" value="=">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->CategoryID->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_categories_CategoryID" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->CategoryID->getInputTextType() ?>" name="x_CategoryID" id="x_CategoryID" data-table="categories" data-field="x_CategoryID" value="<?= $Page->CategoryID->EditValue ?>" placeholder="<?= HtmlEncode($Page->CategoryID->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->CategoryID->formatPattern()) ?>"<?= $Page->CategoryID->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->CategoryID->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->CategoryName->Visible) { // CategoryName ?>
    <div id="r_CategoryName" class="row"<?= $Page->CategoryName->rowAttributes() ?>>
        <label for="x_CategoryName" class="<?= $Page->LeftColumnClass ?>"><span id="elh_categories_CategoryName"><?= $Page->CategoryName->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_CategoryName" id="z_CategoryName" value="LIKE">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->CategoryName->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_categories_CategoryName" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->CategoryName->getInputTextType() ?>" name="x_CategoryName" id="x_CategoryName" data-table="categories" data-field="x_CategoryName" value="<?= $Page->CategoryName->EditValue ?>" size="30" maxlength="15" placeholder="<?= HtmlEncode($Page->CategoryName->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->CategoryName->formatPattern()) ?>"<?= $Page->CategoryName->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->CategoryName->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->Description->Visible) { // Description ?>
    <div id="r_Description" class="row"<?= $Page->Description->rowAttributes() ?>>
        <label for="x_Description" class="<?= $Page->LeftColumnClass ?>"><span id="elh_categories_Description"><?= $Page->Description->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_Description" id="z_Description" value="LIKE">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->Description->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_categories_Description" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->Description->getInputTextType() ?>" name="x_Description" id="x_Description" data-table="categories" data-field="x_Description" value="<?= $Page->Description->EditValue ?>" size="35" placeholder="<?= HtmlEncode($Page->Description->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Description->formatPattern()) ?>"<?= $Page->Description->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->Description->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
        <button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="fcategoriessearch"><?= $Language->phrase("Search") ?></button>
        <?php if ($Page->IsModal) { ?>
        <button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="fcategoriessearch"><?= $Language->phrase("Cancel") ?></button>
        <?php } else { ?>
        <button class="btn btn-default ew-btn" name="btn-reset" id="btn-reset" type="button" form="fcategoriessearch" data-ew-action="reload"><?= $Language->phrase("Reset") ?></button>
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
    ew.addEventHandlers("categories");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
