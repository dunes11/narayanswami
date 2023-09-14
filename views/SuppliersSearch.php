<?php

namespace PHPMaker2023\demo2023;

// Page object
$SuppliersSearch = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { suppliers: currentTable } });
var currentPageID = ew.PAGE_ID = "search";
var currentForm;
var fsupplierssearch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery,
        fields = currentTable.fields;

    // Form object for search
    let form = new ew.FormBuilder()
        .setId("fsupplierssearch")
        .setPageId("search")
<?php if ($Page->IsModal && $Page->UseAjaxActions) { ?>
        .setSubmitWithFetch(true)
<?php } ?>

        // Add fields
        .addFields([
            ["SupplierID", [ew.Validators.integer], fields.SupplierID.isInvalid],
            ["CompanyName", [], fields.CompanyName.isInvalid],
            ["ContactName", [], fields.ContactName.isInvalid],
            ["ContactTitle", [], fields.ContactTitle.isInvalid],
            ["Address", [], fields.Address.isInvalid],
            ["City", [], fields.City.isInvalid],
            ["Region", [], fields.Region.isInvalid],
            ["PostalCode", [], fields.PostalCode.isInvalid],
            ["Country", [], fields.Country.isInvalid],
            ["Phone", [], fields.Phone.isInvalid],
            ["Fax", [], fields.Fax.isInvalid],
            ["HomePage", [], fields.HomePage.isInvalid]
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
<form name="fsupplierssearch" id="fsupplierssearch" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="on">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="suppliers">
<input type="hidden" name="action" id="action" value="search">
<?php if ($Page->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div class="ew-search-div"><!-- page* -->
<?php if ($Page->SupplierID->Visible) { // SupplierID ?>
    <div id="r_SupplierID" class="row"<?= $Page->SupplierID->rowAttributes() ?>>
        <label for="x_SupplierID" class="<?= $Page->LeftColumnClass ?>"><span id="elh_suppliers_SupplierID"><?= $Page->SupplierID->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_SupplierID" id="z_SupplierID" value="=">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->SupplierID->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_suppliers_SupplierID" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->SupplierID->getInputTextType() ?>" name="x_SupplierID" id="x_SupplierID" data-table="suppliers" data-field="x_SupplierID" value="<?= $Page->SupplierID->EditValue ?>" placeholder="<?= HtmlEncode($Page->SupplierID->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->SupplierID->formatPattern()) ?>"<?= $Page->SupplierID->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->SupplierID->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->CompanyName->Visible) { // CompanyName ?>
    <div id="r_CompanyName" class="row"<?= $Page->CompanyName->rowAttributes() ?>>
        <label for="x_CompanyName" class="<?= $Page->LeftColumnClass ?>"><span id="elh_suppliers_CompanyName"><?= $Page->CompanyName->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_CompanyName" id="z_CompanyName" value="LIKE">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->CompanyName->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_suppliers_CompanyName" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->CompanyName->getInputTextType() ?>" name="x_CompanyName" id="x_CompanyName" data-table="suppliers" data-field="x_CompanyName" value="<?= $Page->CompanyName->EditValue ?>" size="30" maxlength="40" placeholder="<?= HtmlEncode($Page->CompanyName->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->CompanyName->formatPattern()) ?>"<?= $Page->CompanyName->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->CompanyName->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->ContactName->Visible) { // ContactName ?>
    <div id="r_ContactName" class="row"<?= $Page->ContactName->rowAttributes() ?>>
        <label for="x_ContactName" class="<?= $Page->LeftColumnClass ?>"><span id="elh_suppliers_ContactName"><?= $Page->ContactName->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_ContactName" id="z_ContactName" value="LIKE">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->ContactName->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_suppliers_ContactName" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->ContactName->getInputTextType() ?>" name="x_ContactName" id="x_ContactName" data-table="suppliers" data-field="x_ContactName" value="<?= $Page->ContactName->EditValue ?>" size="30" maxlength="30" placeholder="<?= HtmlEncode($Page->ContactName->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->ContactName->formatPattern()) ?>"<?= $Page->ContactName->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->ContactName->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->ContactTitle->Visible) { // ContactTitle ?>
    <div id="r_ContactTitle" class="row"<?= $Page->ContactTitle->rowAttributes() ?>>
        <label for="x_ContactTitle" class="<?= $Page->LeftColumnClass ?>"><span id="elh_suppliers_ContactTitle"><?= $Page->ContactTitle->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_ContactTitle" id="z_ContactTitle" value="LIKE">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->ContactTitle->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_suppliers_ContactTitle" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->ContactTitle->getInputTextType() ?>" name="x_ContactTitle" id="x_ContactTitle" data-table="suppliers" data-field="x_ContactTitle" value="<?= $Page->ContactTitle->EditValue ?>" size="30" maxlength="30" placeholder="<?= HtmlEncode($Page->ContactTitle->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->ContactTitle->formatPattern()) ?>"<?= $Page->ContactTitle->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->ContactTitle->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->Address->Visible) { // Address ?>
    <div id="r_Address" class="row"<?= $Page->Address->rowAttributes() ?>>
        <label for="x_Address" class="<?= $Page->LeftColumnClass ?>"><span id="elh_suppliers_Address"><?= $Page->Address->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_Address" id="z_Address" value="LIKE">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->Address->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_suppliers_Address" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->Address->getInputTextType() ?>" name="x_Address" id="x_Address" data-table="suppliers" data-field="x_Address" value="<?= $Page->Address->EditValue ?>" size="30" maxlength="60" placeholder="<?= HtmlEncode($Page->Address->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Address->formatPattern()) ?>"<?= $Page->Address->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->Address->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->City->Visible) { // City ?>
    <div id="r_City" class="row"<?= $Page->City->rowAttributes() ?>>
        <label for="x_City" class="<?= $Page->LeftColumnClass ?>"><span id="elh_suppliers_City"><?= $Page->City->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_City" id="z_City" value="LIKE">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->City->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_suppliers_City" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->City->getInputTextType() ?>" name="x_City" id="x_City" data-table="suppliers" data-field="x_City" value="<?= $Page->City->EditValue ?>" size="30" maxlength="15" placeholder="<?= HtmlEncode($Page->City->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->City->formatPattern()) ?>"<?= $Page->City->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->City->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->Region->Visible) { // Region ?>
    <div id="r_Region" class="row"<?= $Page->Region->rowAttributes() ?>>
        <label for="x_Region" class="<?= $Page->LeftColumnClass ?>"><span id="elh_suppliers_Region"><?= $Page->Region->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_Region" id="z_Region" value="LIKE">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->Region->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_suppliers_Region" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->Region->getInputTextType() ?>" name="x_Region" id="x_Region" data-table="suppliers" data-field="x_Region" value="<?= $Page->Region->EditValue ?>" size="30" maxlength="15" placeholder="<?= HtmlEncode($Page->Region->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Region->formatPattern()) ?>"<?= $Page->Region->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->Region->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->PostalCode->Visible) { // PostalCode ?>
    <div id="r_PostalCode" class="row"<?= $Page->PostalCode->rowAttributes() ?>>
        <label for="x_PostalCode" class="<?= $Page->LeftColumnClass ?>"><span id="elh_suppliers_PostalCode"><?= $Page->PostalCode->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_PostalCode" id="z_PostalCode" value="LIKE">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->PostalCode->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_suppliers_PostalCode" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->PostalCode->getInputTextType() ?>" name="x_PostalCode" id="x_PostalCode" data-table="suppliers" data-field="x_PostalCode" value="<?= $Page->PostalCode->EditValue ?>" size="30" maxlength="10" placeholder="<?= HtmlEncode($Page->PostalCode->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->PostalCode->formatPattern()) ?>"<?= $Page->PostalCode->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->PostalCode->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->Country->Visible) { // Country ?>
    <div id="r_Country" class="row"<?= $Page->Country->rowAttributes() ?>>
        <label for="x_Country" class="<?= $Page->LeftColumnClass ?>"><span id="elh_suppliers_Country"><?= $Page->Country->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_Country" id="z_Country" value="LIKE">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->Country->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_suppliers_Country" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->Country->getInputTextType() ?>" name="x_Country" id="x_Country" data-table="suppliers" data-field="x_Country" value="<?= $Page->Country->EditValue ?>" size="30" maxlength="15" placeholder="<?= HtmlEncode($Page->Country->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Country->formatPattern()) ?>"<?= $Page->Country->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->Country->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->Phone->Visible) { // Phone ?>
    <div id="r_Phone" class="row"<?= $Page->Phone->rowAttributes() ?>>
        <label for="x_Phone" class="<?= $Page->LeftColumnClass ?>"><span id="elh_suppliers_Phone"><?= $Page->Phone->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_Phone" id="z_Phone" value="LIKE">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->Phone->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_suppliers_Phone" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->Phone->getInputTextType() ?>" name="x_Phone" id="x_Phone" data-table="suppliers" data-field="x_Phone" value="<?= $Page->Phone->EditValue ?>" size="30" maxlength="24" placeholder="<?= HtmlEncode($Page->Phone->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Phone->formatPattern()) ?>"<?= $Page->Phone->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->Phone->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->Fax->Visible) { // Fax ?>
    <div id="r_Fax" class="row"<?= $Page->Fax->rowAttributes() ?>>
        <label for="x_Fax" class="<?= $Page->LeftColumnClass ?>"><span id="elh_suppliers_Fax"><?= $Page->Fax->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_Fax" id="z_Fax" value="LIKE">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->Fax->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_suppliers_Fax" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->Fax->getInputTextType() ?>" name="x_Fax" id="x_Fax" data-table="suppliers" data-field="x_Fax" value="<?= $Page->Fax->EditValue ?>" size="30" maxlength="24" placeholder="<?= HtmlEncode($Page->Fax->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Fax->formatPattern()) ?>"<?= $Page->Fax->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->Fax->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->HomePage->Visible) { // HomePage ?>
    <div id="r_HomePage" class="row"<?= $Page->HomePage->rowAttributes() ?>>
        <label for="x_HomePage" class="<?= $Page->LeftColumnClass ?>"><span id="elh_suppliers_HomePage"><?= $Page->HomePage->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_HomePage" id="z_HomePage" value="LIKE">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->HomePage->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_suppliers_HomePage" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->HomePage->getInputTextType() ?>" name="x_HomePage" id="x_HomePage" data-table="suppliers" data-field="x_HomePage" value="<?= $Page->HomePage->EditValue ?>" size="35" placeholder="<?= HtmlEncode($Page->HomePage->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->HomePage->formatPattern()) ?>"<?= $Page->HomePage->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->HomePage->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
        <button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="fsupplierssearch"><?= $Language->phrase("Search") ?></button>
        <?php if ($Page->IsModal) { ?>
        <button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="fsupplierssearch"><?= $Language->phrase("Cancel") ?></button>
        <?php } else { ?>
        <button class="btn btn-default ew-btn" name="btn-reset" id="btn-reset" type="button" form="fsupplierssearch" data-ew-action="reload"><?= $Language->phrase("Reset") ?></button>
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
    ew.addEventHandlers("suppliers");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
