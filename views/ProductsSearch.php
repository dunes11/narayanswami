<?php

namespace PHPMaker2023\demo2023;

// Page object
$ProductsSearch = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { products: currentTable } });
var currentPageID = ew.PAGE_ID = "search";
var currentForm;
var fproductssearch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery,
        fields = currentTable.fields;

    // Form object for search
    let form = new ew.FormBuilder()
        .setId("fproductssearch")
        .setPageId("search")
<?php if ($Page->IsModal && $Page->UseAjaxActions) { ?>
        .setSubmitWithFetch(true)
<?php } ?>

        // Add fields
        .addFields([
            ["ProductID", [ew.Validators.integer], fields.ProductID.isInvalid],
            ["ProductName", [], fields.ProductName.isInvalid],
            ["SupplierID", [], fields.SupplierID.isInvalid],
            ["CategoryID", [], fields.CategoryID.isInvalid],
            ["QuantityPerUnit", [], fields.QuantityPerUnit.isInvalid],
            ["UnitPrice", [ew.Validators.float], fields.UnitPrice.isInvalid],
            ["UnitsInStock", [ew.Validators.integer], fields.UnitsInStock.isInvalid],
            ["UnitsOnOrder", [ew.Validators.integer], fields.UnitsOnOrder.isInvalid],
            ["ReorderLevel", [ew.Validators.integer], fields.ReorderLevel.isInvalid],
            ["Discontinued", [], fields.Discontinued.isInvalid],
            ["EAN13", [], fields.EAN13.isInvalid]
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
            "SupplierID": <?= $Page->SupplierID->toClientList($Page) ?>,
            "CategoryID": <?= $Page->CategoryID->toClientList($Page) ?>,
            "Discontinued": <?= $Page->Discontinued->toClientList($Page) ?>,
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
<form name="fproductssearch" id="fproductssearch" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="on">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="products">
<input type="hidden" name="action" id="action" value="search">
<?php if ($Page->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div class="ew-search-div"><!-- page* -->
<?php if ($Page->ProductID->Visible) { // ProductID ?>
    <div id="r_ProductID" class="row"<?= $Page->ProductID->rowAttributes() ?>>
        <label for="x_ProductID" class="<?= $Page->LeftColumnClass ?>"><span id="elh_products_ProductID"><?= $Page->ProductID->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_ProductID" id="z_ProductID" value="=">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->ProductID->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_products_ProductID" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->ProductID->getInputTextType() ?>" name="x_ProductID" id="x_ProductID" data-table="products" data-field="x_ProductID" value="<?= $Page->ProductID->EditValue ?>" placeholder="<?= HtmlEncode($Page->ProductID->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->ProductID->formatPattern()) ?>"<?= $Page->ProductID->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->ProductID->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->ProductName->Visible) { // ProductName ?>
    <div id="r_ProductName" class="row"<?= $Page->ProductName->rowAttributes() ?>>
        <label for="x_ProductName" class="<?= $Page->LeftColumnClass ?>"><span id="elh_products_ProductName"><?= $Page->ProductName->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_ProductName" id="z_ProductName" value="LIKE">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->ProductName->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_products_ProductName" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->ProductName->getInputTextType() ?>" name="x_ProductName" id="x_ProductName" data-table="products" data-field="x_ProductName" value="<?= $Page->ProductName->EditValue ?>" size="30" maxlength="40" placeholder="<?= HtmlEncode($Page->ProductName->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->ProductName->formatPattern()) ?>"<?= $Page->ProductName->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->ProductName->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->SupplierID->Visible) { // SupplierID ?>
    <div id="r_SupplierID" class="row"<?= $Page->SupplierID->rowAttributes() ?>>
        <label for="x_SupplierID" class="<?= $Page->LeftColumnClass ?>"><span id="elh_products_SupplierID"><?= $Page->SupplierID->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_SupplierID" id="z_SupplierID" value="=">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->SupplierID->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_products_SupplierID" class="ew-search-field ew-search-field-single">
    <select
        id="x_SupplierID"
        name="x_SupplierID"
        class="form-control ew-select<?= $Page->SupplierID->isInvalidClass() ?>"
        data-select2-id="fproductssearch_x_SupplierID"
        data-table="products"
        data-field="x_SupplierID"
        data-caption="<?= HtmlEncode(RemoveHtml($Page->SupplierID->caption())) ?>"
        data-modal-lookup="true"
        data-value-separator="<?= $Page->SupplierID->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->SupplierID->getPlaceHolder()) ?>"
        <?= $Page->SupplierID->editAttributes() ?>>
        <?= $Page->SupplierID->selectOptionListHtml("x_SupplierID") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->SupplierID->getErrorMessage(false) ?></div>
<?= $Page->SupplierID->Lookup->getParamTag($Page, "p_x_SupplierID") ?>
<script>
loadjs.ready("fproductssearch", function() {
    var options = { name: "x_SupplierID", selectId: "fproductssearch_x_SupplierID" };
    if (fproductssearch.lists.SupplierID?.lookupOptions.length) {
        options.data = { id: "x_SupplierID", form: "fproductssearch" };
    } else {
        options.ajax = { id: "x_SupplierID", form: "fproductssearch", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options = Object.assign({}, ew.modalLookupOptions, options, ew.vars.tables.products.fields.SupplierID.modalLookupOptions);
    ew.createModalLookup(options);
});
</script>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->CategoryID->Visible) { // CategoryID ?>
    <div id="r_CategoryID" class="row"<?= $Page->CategoryID->rowAttributes() ?>>
        <label for="x_CategoryID" class="<?= $Page->LeftColumnClass ?>"><span id="elh_products_CategoryID"><?= $Page->CategoryID->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_CategoryID" id="z_CategoryID" value="=">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->CategoryID->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_products_CategoryID" class="ew-search-field ew-search-field-single">
    <select
        id="x_CategoryID"
        name="x_CategoryID"
        class="form-select ew-select<?= $Page->CategoryID->isInvalidClass() ?>"
        <?php if (!$Page->CategoryID->IsNativeSelect) { ?>
        data-select2-id="fproductssearch_x_CategoryID"
        <?php } ?>
        data-table="products"
        data-field="x_CategoryID"
        data-value-separator="<?= $Page->CategoryID->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->CategoryID->getPlaceHolder()) ?>"
        <?= $Page->CategoryID->editAttributes() ?>>
        <?= $Page->CategoryID->selectOptionListHtml("x_CategoryID") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->CategoryID->getErrorMessage(false) ?></div>
<?= $Page->CategoryID->Lookup->getParamTag($Page, "p_x_CategoryID") ?>
<?php if (!$Page->CategoryID->IsNativeSelect) { ?>
<script>
loadjs.ready("fproductssearch", function() {
    var options = { name: "x_CategoryID", selectId: "fproductssearch_x_CategoryID" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fproductssearch.lists.CategoryID?.lookupOptions.length) {
        options.data = { id: "x_CategoryID", form: "fproductssearch" };
    } else {
        options.ajax = { id: "x_CategoryID", form: "fproductssearch", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.products.fields.CategoryID.selectOptions);
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
<?php if ($Page->QuantityPerUnit->Visible) { // QuantityPerUnit ?>
    <div id="r_QuantityPerUnit" class="row"<?= $Page->QuantityPerUnit->rowAttributes() ?>>
        <label for="x_QuantityPerUnit" class="<?= $Page->LeftColumnClass ?>"><span id="elh_products_QuantityPerUnit"><?= $Page->QuantityPerUnit->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_QuantityPerUnit" id="z_QuantityPerUnit" value="LIKE">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->QuantityPerUnit->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_products_QuantityPerUnit" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->QuantityPerUnit->getInputTextType() ?>" name="x_QuantityPerUnit" id="x_QuantityPerUnit" data-table="products" data-field="x_QuantityPerUnit" value="<?= $Page->QuantityPerUnit->EditValue ?>" size="30" maxlength="20" placeholder="<?= HtmlEncode($Page->QuantityPerUnit->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->QuantityPerUnit->formatPattern()) ?>"<?= $Page->QuantityPerUnit->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->QuantityPerUnit->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->UnitPrice->Visible) { // UnitPrice ?>
    <div id="r_UnitPrice" class="row"<?= $Page->UnitPrice->rowAttributes() ?>>
        <label for="x_UnitPrice" class="<?= $Page->LeftColumnClass ?>"><span id="elh_products_UnitPrice"><?= $Page->UnitPrice->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_UnitPrice" id="z_UnitPrice" value="=">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->UnitPrice->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_products_UnitPrice" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->UnitPrice->getInputTextType() ?>" name="x_UnitPrice" id="x_UnitPrice" data-table="products" data-field="x_UnitPrice" value="<?= $Page->UnitPrice->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->UnitPrice->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->UnitPrice->formatPattern()) ?>"<?= $Page->UnitPrice->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->UnitPrice->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->UnitsInStock->Visible) { // UnitsInStock ?>
    <div id="r_UnitsInStock" class="row"<?= $Page->UnitsInStock->rowAttributes() ?>>
        <label for="x_UnitsInStock" class="<?= $Page->LeftColumnClass ?>"><span id="elh_products_UnitsInStock"><?= $Page->UnitsInStock->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_UnitsInStock" id="z_UnitsInStock" value="=">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->UnitsInStock->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_products_UnitsInStock" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->UnitsInStock->getInputTextType() ?>" name="x_UnitsInStock" id="x_UnitsInStock" data-table="products" data-field="x_UnitsInStock" value="<?= $Page->UnitsInStock->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->UnitsInStock->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->UnitsInStock->formatPattern()) ?>"<?= $Page->UnitsInStock->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->UnitsInStock->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->UnitsOnOrder->Visible) { // UnitsOnOrder ?>
    <div id="r_UnitsOnOrder" class="row"<?= $Page->UnitsOnOrder->rowAttributes() ?>>
        <label for="x_UnitsOnOrder" class="<?= $Page->LeftColumnClass ?>"><span id="elh_products_UnitsOnOrder"><?= $Page->UnitsOnOrder->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_UnitsOnOrder" id="z_UnitsOnOrder" value="=">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->UnitsOnOrder->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_products_UnitsOnOrder" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->UnitsOnOrder->getInputTextType() ?>" name="x_UnitsOnOrder" id="x_UnitsOnOrder" data-table="products" data-field="x_UnitsOnOrder" value="<?= $Page->UnitsOnOrder->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->UnitsOnOrder->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->UnitsOnOrder->formatPattern()) ?>"<?= $Page->UnitsOnOrder->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->UnitsOnOrder->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->ReorderLevel->Visible) { // ReorderLevel ?>
    <div id="r_ReorderLevel" class="row"<?= $Page->ReorderLevel->rowAttributes() ?>>
        <label for="x_ReorderLevel" class="<?= $Page->LeftColumnClass ?>"><span id="elh_products_ReorderLevel"><?= $Page->ReorderLevel->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_ReorderLevel" id="z_ReorderLevel" value="=">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->ReorderLevel->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_products_ReorderLevel" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->ReorderLevel->getInputTextType() ?>" name="x_ReorderLevel" id="x_ReorderLevel" data-table="products" data-field="x_ReorderLevel" value="<?= $Page->ReorderLevel->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->ReorderLevel->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->ReorderLevel->formatPattern()) ?>"<?= $Page->ReorderLevel->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->ReorderLevel->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->Discontinued->Visible) { // Discontinued ?>
    <div id="r_Discontinued" class="row"<?= $Page->Discontinued->rowAttributes() ?>>
        <label class="<?= $Page->LeftColumnClass ?>"><span id="elh_products_Discontinued"><?= $Page->Discontinued->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_Discontinued" id="z_Discontinued" value="=">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->Discontinued->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_products_Discontinued" class="ew-search-field ew-search-field-single">
<div class="form-check d-inline-block">
    <input type="checkbox" class="form-check-input<?= $Page->Discontinued->isInvalidClass() ?>" data-table="products" data-field="x_Discontinued" data-boolean name="x_Discontinued" id="x_Discontinued" value="1"<?= ConvertToBool($Page->Discontinued->AdvancedSearch->SearchValue) ? " checked" : "" ?><?= $Page->Discontinued->editAttributes() ?>>
    <div class="invalid-feedback"><?= $Page->Discontinued->getErrorMessage(false) ?></div>
</div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->EAN13->Visible) { // EAN13 ?>
    <div id="r_EAN13" class="row"<?= $Page->EAN13->rowAttributes() ?>>
        <label for="x_EAN13" class="<?= $Page->LeftColumnClass ?>"><span id="elh_products_EAN13"><?= $Page->EAN13->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_EAN13" id="z_EAN13" value="LIKE">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->EAN13->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_products_EAN13" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->EAN13->getInputTextType() ?>" name="x_EAN13" id="x_EAN13" data-table="products" data-field="x_EAN13" value="<?= $Page->EAN13->EditValue ?>" size="30" maxlength="13" placeholder="<?= HtmlEncode($Page->EAN13->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->EAN13->formatPattern()) ?>"<?= $Page->EAN13->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->EAN13->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
        <button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="fproductssearch"><?= $Language->phrase("Search") ?></button>
        <?php if ($Page->IsModal) { ?>
        <button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="fproductssearch"><?= $Language->phrase("Cancel") ?></button>
        <?php } else { ?>
        <button class="btn btn-default ew-btn" name="btn-reset" id="btn-reset" type="button" form="fproductssearch" data-ew-action="reload"><?= $Language->phrase("Reset") ?></button>
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
    ew.addEventHandlers("products");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
