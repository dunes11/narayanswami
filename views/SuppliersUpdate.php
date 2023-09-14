<?php

namespace PHPMaker2023\demo2023;

// Page object
$SuppliersUpdate = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { suppliers: currentTable } });
var currentPageID = ew.PAGE_ID = "update";
var currentForm;
var fsuppliersupdate;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fsuppliersupdate")
        .setPageId("update")

        // Add fields
        .setFields([
            ["SupplierID", [fields.SupplierID.visible && fields.SupplierID.required ? ew.Validators.required(fields.SupplierID.caption) : null], fields.SupplierID.isInvalid],
            ["CompanyName", [fields.CompanyName.visible && fields.CompanyName.required ? ew.Validators.required(fields.CompanyName.caption) : null], fields.CompanyName.isInvalid],
            ["ContactName", [fields.ContactName.visible && fields.ContactName.required ? ew.Validators.required(fields.ContactName.caption) : null], fields.ContactName.isInvalid],
            ["ContactTitle", [fields.ContactTitle.visible && fields.ContactTitle.required ? ew.Validators.required(fields.ContactTitle.caption) : null], fields.ContactTitle.isInvalid],
            ["Address", [fields.Address.visible && fields.Address.required ? ew.Validators.required(fields.Address.caption) : null], fields.Address.isInvalid],
            ["City", [fields.City.visible && fields.City.required ? ew.Validators.required(fields.City.caption) : null], fields.City.isInvalid],
            ["Region", [fields.Region.visible && fields.Region.required ? ew.Validators.required(fields.Region.caption) : null], fields.Region.isInvalid],
            ["PostalCode", [fields.PostalCode.visible && fields.PostalCode.required ? ew.Validators.required(fields.PostalCode.caption) : null], fields.PostalCode.isInvalid],
            ["Country", [fields.Country.visible && fields.Country.required ? ew.Validators.required(fields.Country.caption) : null], fields.Country.isInvalid],
            ["Phone", [fields.Phone.visible && fields.Phone.required ? ew.Validators.required(fields.Phone.caption) : null], fields.Phone.isInvalid],
            ["Fax", [fields.Fax.visible && fields.Fax.required ? ew.Validators.required(fields.Fax.caption) : null], fields.Fax.isInvalid],
            ["HomePage", [fields.HomePage.visible && fields.HomePage.required ? ew.Validators.required(fields.HomePage.caption) : null], fields.HomePage.isInvalid]
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
<?php
$Page->showMessage();
?>
<form name="fsuppliersupdate" id="fsuppliersupdate" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="on">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="suppliers">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php foreach ($Page->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode(Config("COMPOSITE_KEY_SEPARATOR"), $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?= HtmlEncode($keyvalue) ?>">
<?php } ?>
<div id="tbl_suppliersupdate" class="ew-update-div"><!-- page -->
    <?php if (!$Page->isConfirm()) { // Confirm page ?>
    <div class="form-check">
        <input type="checkbox" class="form-check-input" name="u" id="u" data-ew-action="select-all"><label class="form-check-label" for="u"><?= $Language->phrase("SelectAll") ?></label>
    </div>
    <?php } ?>
<?php if ($Page->CompanyName->Visible && (!$Page->isConfirm() || $Page->CompanyName->multiUpdateSelected())) { // CompanyName ?>
    <div id="r_CompanyName"<?= $Page->CompanyName->rowAttributes() ?>>
        <label for="x_CompanyName" class="<?= $Page->LeftColumnClass ?>">
            <div class="form-check">
                <input type="checkbox" name="u_CompanyName" id="u_CompanyName" class="form-check-input ew-multi-select" value="1"<?= $Page->CompanyName->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="form-check-label" for="u_CompanyName"><?= $Page->CompanyName->caption() ?></label>
            </div>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->CompanyName->cellAttributes() ?>>
                <span id="el_suppliers_CompanyName">
                <input type="<?= $Page->CompanyName->getInputTextType() ?>" name="x_CompanyName" id="x_CompanyName" data-table="suppliers" data-field="x_CompanyName" value="<?= $Page->CompanyName->EditValue ?>" size="30" maxlength="40" placeholder="<?= HtmlEncode($Page->CompanyName->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->CompanyName->formatPattern()) ?>"<?= $Page->CompanyName->editAttributes() ?> aria-describedby="x_CompanyName_help">
                <?= $Page->CompanyName->getCustomMessage() ?>
                <div class="invalid-feedback"><?= $Page->CompanyName->getErrorMessage() ?></div>
                </span>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->ContactName->Visible && (!$Page->isConfirm() || $Page->ContactName->multiUpdateSelected())) { // ContactName ?>
    <div id="r_ContactName"<?= $Page->ContactName->rowAttributes() ?>>
        <label for="x_ContactName" class="<?= $Page->LeftColumnClass ?>">
            <div class="form-check">
                <input type="checkbox" name="u_ContactName" id="u_ContactName" class="form-check-input ew-multi-select" value="1"<?= $Page->ContactName->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="form-check-label" for="u_ContactName"><?= $Page->ContactName->caption() ?></label>
            </div>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->ContactName->cellAttributes() ?>>
                <span id="el_suppliers_ContactName">
                <input type="<?= $Page->ContactName->getInputTextType() ?>" name="x_ContactName" id="x_ContactName" data-table="suppliers" data-field="x_ContactName" value="<?= $Page->ContactName->EditValue ?>" size="30" maxlength="30" placeholder="<?= HtmlEncode($Page->ContactName->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->ContactName->formatPattern()) ?>"<?= $Page->ContactName->editAttributes() ?> aria-describedby="x_ContactName_help">
                <?= $Page->ContactName->getCustomMessage() ?>
                <div class="invalid-feedback"><?= $Page->ContactName->getErrorMessage() ?></div>
                </span>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->ContactTitle->Visible && (!$Page->isConfirm() || $Page->ContactTitle->multiUpdateSelected())) { // ContactTitle ?>
    <div id="r_ContactTitle"<?= $Page->ContactTitle->rowAttributes() ?>>
        <label for="x_ContactTitle" class="<?= $Page->LeftColumnClass ?>">
            <div class="form-check">
                <input type="checkbox" name="u_ContactTitle" id="u_ContactTitle" class="form-check-input ew-multi-select" value="1"<?= $Page->ContactTitle->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="form-check-label" for="u_ContactTitle"><?= $Page->ContactTitle->caption() ?></label>
            </div>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->ContactTitle->cellAttributes() ?>>
                <span id="el_suppliers_ContactTitle">
                <input type="<?= $Page->ContactTitle->getInputTextType() ?>" name="x_ContactTitle" id="x_ContactTitle" data-table="suppliers" data-field="x_ContactTitle" value="<?= $Page->ContactTitle->EditValue ?>" size="30" maxlength="30" placeholder="<?= HtmlEncode($Page->ContactTitle->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->ContactTitle->formatPattern()) ?>"<?= $Page->ContactTitle->editAttributes() ?> aria-describedby="x_ContactTitle_help">
                <?= $Page->ContactTitle->getCustomMessage() ?>
                <div class="invalid-feedback"><?= $Page->ContactTitle->getErrorMessage() ?></div>
                </span>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->Address->Visible && (!$Page->isConfirm() || $Page->Address->multiUpdateSelected())) { // Address ?>
    <div id="r_Address"<?= $Page->Address->rowAttributes() ?>>
        <label for="x_Address" class="<?= $Page->LeftColumnClass ?>">
            <div class="form-check">
                <input type="checkbox" name="u_Address" id="u_Address" class="form-check-input ew-multi-select" value="1"<?= $Page->Address->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="form-check-label" for="u_Address"><?= $Page->Address->caption() ?></label>
            </div>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->Address->cellAttributes() ?>>
                <span id="el_suppliers_Address">
                <input type="<?= $Page->Address->getInputTextType() ?>" name="x_Address" id="x_Address" data-table="suppliers" data-field="x_Address" value="<?= $Page->Address->EditValue ?>" size="30" maxlength="60" placeholder="<?= HtmlEncode($Page->Address->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Address->formatPattern()) ?>"<?= $Page->Address->editAttributes() ?> aria-describedby="x_Address_help">
                <?= $Page->Address->getCustomMessage() ?>
                <div class="invalid-feedback"><?= $Page->Address->getErrorMessage() ?></div>
                </span>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->City->Visible && (!$Page->isConfirm() || $Page->City->multiUpdateSelected())) { // City ?>
    <div id="r_City"<?= $Page->City->rowAttributes() ?>>
        <label for="x_City" class="<?= $Page->LeftColumnClass ?>">
            <div class="form-check">
                <input type="checkbox" name="u_City" id="u_City" class="form-check-input ew-multi-select" value="1"<?= $Page->City->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="form-check-label" for="u_City"><?= $Page->City->caption() ?></label>
            </div>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->City->cellAttributes() ?>>
                <span id="el_suppliers_City">
                <input type="<?= $Page->City->getInputTextType() ?>" name="x_City" id="x_City" data-table="suppliers" data-field="x_City" value="<?= $Page->City->EditValue ?>" size="30" maxlength="15" placeholder="<?= HtmlEncode($Page->City->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->City->formatPattern()) ?>"<?= $Page->City->editAttributes() ?> aria-describedby="x_City_help">
                <?= $Page->City->getCustomMessage() ?>
                <div class="invalid-feedback"><?= $Page->City->getErrorMessage() ?></div>
                </span>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->Region->Visible && (!$Page->isConfirm() || $Page->Region->multiUpdateSelected())) { // Region ?>
    <div id="r_Region"<?= $Page->Region->rowAttributes() ?>>
        <label for="x_Region" class="<?= $Page->LeftColumnClass ?>">
            <div class="form-check">
                <input type="checkbox" name="u_Region" id="u_Region" class="form-check-input ew-multi-select" value="1"<?= $Page->Region->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="form-check-label" for="u_Region"><?= $Page->Region->caption() ?></label>
            </div>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->Region->cellAttributes() ?>>
                <span id="el_suppliers_Region">
                <input type="<?= $Page->Region->getInputTextType() ?>" name="x_Region" id="x_Region" data-table="suppliers" data-field="x_Region" value="<?= $Page->Region->EditValue ?>" size="30" maxlength="15" placeholder="<?= HtmlEncode($Page->Region->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Region->formatPattern()) ?>"<?= $Page->Region->editAttributes() ?> aria-describedby="x_Region_help">
                <?= $Page->Region->getCustomMessage() ?>
                <div class="invalid-feedback"><?= $Page->Region->getErrorMessage() ?></div>
                </span>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->PostalCode->Visible && (!$Page->isConfirm() || $Page->PostalCode->multiUpdateSelected())) { // PostalCode ?>
    <div id="r_PostalCode"<?= $Page->PostalCode->rowAttributes() ?>>
        <label for="x_PostalCode" class="<?= $Page->LeftColumnClass ?>">
            <div class="form-check">
                <input type="checkbox" name="u_PostalCode" id="u_PostalCode" class="form-check-input ew-multi-select" value="1"<?= $Page->PostalCode->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="form-check-label" for="u_PostalCode"><?= $Page->PostalCode->caption() ?></label>
            </div>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->PostalCode->cellAttributes() ?>>
                <span id="el_suppliers_PostalCode">
                <input type="<?= $Page->PostalCode->getInputTextType() ?>" name="x_PostalCode" id="x_PostalCode" data-table="suppliers" data-field="x_PostalCode" value="<?= $Page->PostalCode->EditValue ?>" size="30" maxlength="10" placeholder="<?= HtmlEncode($Page->PostalCode->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->PostalCode->formatPattern()) ?>"<?= $Page->PostalCode->editAttributes() ?> aria-describedby="x_PostalCode_help">
                <?= $Page->PostalCode->getCustomMessage() ?>
                <div class="invalid-feedback"><?= $Page->PostalCode->getErrorMessage() ?></div>
                </span>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->Country->Visible && (!$Page->isConfirm() || $Page->Country->multiUpdateSelected())) { // Country ?>
    <div id="r_Country"<?= $Page->Country->rowAttributes() ?>>
        <label for="x_Country" class="<?= $Page->LeftColumnClass ?>">
            <div class="form-check">
                <input type="checkbox" name="u_Country" id="u_Country" class="form-check-input ew-multi-select" value="1"<?= $Page->Country->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="form-check-label" for="u_Country"><?= $Page->Country->caption() ?></label>
            </div>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->Country->cellAttributes() ?>>
                <span id="el_suppliers_Country">
                <input type="<?= $Page->Country->getInputTextType() ?>" name="x_Country" id="x_Country" data-table="suppliers" data-field="x_Country" value="<?= $Page->Country->EditValue ?>" size="30" maxlength="15" placeholder="<?= HtmlEncode($Page->Country->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Country->formatPattern()) ?>"<?= $Page->Country->editAttributes() ?> aria-describedby="x_Country_help">
                <?= $Page->Country->getCustomMessage() ?>
                <div class="invalid-feedback"><?= $Page->Country->getErrorMessage() ?></div>
                </span>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->Phone->Visible && (!$Page->isConfirm() || $Page->Phone->multiUpdateSelected())) { // Phone ?>
    <div id="r_Phone"<?= $Page->Phone->rowAttributes() ?>>
        <label for="x_Phone" class="<?= $Page->LeftColumnClass ?>">
            <div class="form-check">
                <input type="checkbox" name="u_Phone" id="u_Phone" class="form-check-input ew-multi-select" value="1"<?= $Page->Phone->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="form-check-label" for="u_Phone"><?= $Page->Phone->caption() ?></label>
            </div>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->Phone->cellAttributes() ?>>
                <span id="el_suppliers_Phone">
                <input type="<?= $Page->Phone->getInputTextType() ?>" name="x_Phone" id="x_Phone" data-table="suppliers" data-field="x_Phone" value="<?= $Page->Phone->EditValue ?>" size="30" maxlength="24" placeholder="<?= HtmlEncode($Page->Phone->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Phone->formatPattern()) ?>"<?= $Page->Phone->editAttributes() ?> aria-describedby="x_Phone_help">
                <?= $Page->Phone->getCustomMessage() ?>
                <div class="invalid-feedback"><?= $Page->Phone->getErrorMessage() ?></div>
                </span>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->Fax->Visible && (!$Page->isConfirm() || $Page->Fax->multiUpdateSelected())) { // Fax ?>
    <div id="r_Fax"<?= $Page->Fax->rowAttributes() ?>>
        <label for="x_Fax" class="<?= $Page->LeftColumnClass ?>">
            <div class="form-check">
                <input type="checkbox" name="u_Fax" id="u_Fax" class="form-check-input ew-multi-select" value="1"<?= $Page->Fax->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="form-check-label" for="u_Fax"><?= $Page->Fax->caption() ?></label>
            </div>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->Fax->cellAttributes() ?>>
                <span id="el_suppliers_Fax">
                <input type="<?= $Page->Fax->getInputTextType() ?>" name="x_Fax" id="x_Fax" data-table="suppliers" data-field="x_Fax" value="<?= $Page->Fax->EditValue ?>" size="30" maxlength="24" placeholder="<?= HtmlEncode($Page->Fax->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Fax->formatPattern()) ?>"<?= $Page->Fax->editAttributes() ?> aria-describedby="x_Fax_help">
                <?= $Page->Fax->getCustomMessage() ?>
                <div class="invalid-feedback"><?= $Page->Fax->getErrorMessage() ?></div>
                </span>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->HomePage->Visible && (!$Page->isConfirm() || $Page->HomePage->multiUpdateSelected())) { // HomePage ?>
    <div id="r_HomePage"<?= $Page->HomePage->rowAttributes() ?>>
        <label for="x_HomePage" class="<?= $Page->LeftColumnClass ?>">
            <div class="form-check">
                <input type="checkbox" name="u_HomePage" id="u_HomePage" class="form-check-input ew-multi-select" value="1"<?= $Page->HomePage->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="form-check-label" for="u_HomePage"><?= $Page->HomePage->caption() ?></label>
            </div>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->HomePage->cellAttributes() ?>>
                <span id="el_suppliers_HomePage">
                <textarea data-table="suppliers" data-field="x_HomePage" name="x_HomePage" id="x_HomePage" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->HomePage->getPlaceHolder()) ?>"<?= $Page->HomePage->editAttributes() ?> aria-describedby="x_HomePage_help"><?= $Page->HomePage->EditValue ?></textarea>
                <?= $Page->HomePage->getCustomMessage() ?>
                <div class="invalid-feedback"><?= $Page->HomePage->getErrorMessage() ?></div>
                </span>
            </div>
        </div>
    </div>
<?php } ?>
</div><!-- /page -->
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="fsuppliersupdate"><?= $Language->phrase("UpdateBtn") ?></button>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="fsuppliersupdate" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
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
