<?php

namespace PHPMaker2023\demo2023;

// Page object
$ProductsAdd = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { products: currentTable } });
var currentPageID = ew.PAGE_ID = "add";
var currentForm;
var fproductsadd;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fproductsadd")
        .setPageId("add")

        // Add fields
        .setFields([
            ["ProductName", [fields.ProductName.visible && fields.ProductName.required ? ew.Validators.required(fields.ProductName.caption) : null], fields.ProductName.isInvalid],
            ["SupplierID", [fields.SupplierID.visible && fields.SupplierID.required ? ew.Validators.required(fields.SupplierID.caption) : null], fields.SupplierID.isInvalid],
            ["CategoryID", [fields.CategoryID.visible && fields.CategoryID.required ? ew.Validators.required(fields.CategoryID.caption) : null], fields.CategoryID.isInvalid],
            ["QuantityPerUnit", [fields.QuantityPerUnit.visible && fields.QuantityPerUnit.required ? ew.Validators.required(fields.QuantityPerUnit.caption) : null], fields.QuantityPerUnit.isInvalid],
            ["UnitPrice", [fields.UnitPrice.visible && fields.UnitPrice.required ? ew.Validators.required(fields.UnitPrice.caption) : null, ew.Validators.float], fields.UnitPrice.isInvalid],
            ["UnitsInStock", [fields.UnitsInStock.visible && fields.UnitsInStock.required ? ew.Validators.required(fields.UnitsInStock.caption) : null, ew.Validators.integer], fields.UnitsInStock.isInvalid],
            ["UnitsOnOrder", [fields.UnitsOnOrder.visible && fields.UnitsOnOrder.required ? ew.Validators.required(fields.UnitsOnOrder.caption) : null, ew.Validators.integer], fields.UnitsOnOrder.isInvalid],
            ["ReorderLevel", [fields.ReorderLevel.visible && fields.ReorderLevel.required ? ew.Validators.required(fields.ReorderLevel.caption) : null, ew.Validators.integer], fields.ReorderLevel.isInvalid],
            ["Discontinued", [fields.Discontinued.visible && fields.Discontinued.required ? ew.Validators.required(fields.Discontinued.caption) : null], fields.Discontinued.isInvalid],
            ["EAN13", [fields.EAN13.visible && fields.EAN13.required ? ew.Validators.required(fields.EAN13.caption) : null], fields.EAN13.isInvalid]
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
            "SupplierID": <?= $Page->SupplierID->toClientList($Page) ?>,
            "CategoryID": <?= $Page->CategoryID->toClientList($Page) ?>,
            "Discontinued": <?= $Page->Discontinued->toClientList($Page) ?>,
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
<form name="fproductsadd" id="fproductsadd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="on">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="products">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<?php if ($Page->getCurrentMasterTable() == "categories") { ?>
<input type="hidden" name="<?= Config("TABLE_SHOW_MASTER") ?>" value="categories">
<input type="hidden" name="fk_CategoryID" value="<?= HtmlEncode($Page->CategoryID->getSessionValue()) ?>">
<?php } ?>
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->ProductName->Visible) { // ProductName ?>
    <div id="r_ProductName"<?= $Page->ProductName->rowAttributes() ?>>
        <label id="elh_products_ProductName" for="x_ProductName" class="<?= $Page->LeftColumnClass ?>"><?= $Page->ProductName->caption() ?><?= $Page->ProductName->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->ProductName->cellAttributes() ?>>
<span id="el_products_ProductName">
<input type="<?= $Page->ProductName->getInputTextType() ?>" name="x_ProductName" id="x_ProductName" data-table="products" data-field="x_ProductName" value="<?= $Page->ProductName->EditValue ?>" size="30" maxlength="40" placeholder="<?= HtmlEncode($Page->ProductName->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->ProductName->formatPattern()) ?>"<?= $Page->ProductName->editAttributes() ?> aria-describedby="x_ProductName_help">
<?= $Page->ProductName->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->ProductName->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->SupplierID->Visible) { // SupplierID ?>
    <div id="r_SupplierID"<?= $Page->SupplierID->rowAttributes() ?>>
        <label id="elh_products_SupplierID" for="x_SupplierID" class="<?= $Page->LeftColumnClass ?>"><?= $Page->SupplierID->caption() ?><?= $Page->SupplierID->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->SupplierID->cellAttributes() ?>>
<span id="el_products_SupplierID">
    <select
        id="x_SupplierID"
        name="x_SupplierID"
        class="form-control ew-select<?= $Page->SupplierID->isInvalidClass() ?>"
        data-select2-id="fproductsadd_x_SupplierID"
        data-table="products"
        data-field="x_SupplierID"
        data-caption="<?= HtmlEncode(RemoveHtml($Page->SupplierID->caption())) ?>"
        data-modal-lookup="true"
        data-value-separator="<?= $Page->SupplierID->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->SupplierID->getPlaceHolder()) ?>"
        <?= $Page->SupplierID->editAttributes() ?>>
        <?= $Page->SupplierID->selectOptionListHtml("x_SupplierID") ?>
    </select>
    <?= $Page->SupplierID->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->SupplierID->getErrorMessage() ?></div>
<?= $Page->SupplierID->Lookup->getParamTag($Page, "p_x_SupplierID") ?>
<script>
loadjs.ready("fproductsadd", function() {
    var options = { name: "x_SupplierID", selectId: "fproductsadd_x_SupplierID" };
    if (fproductsadd.lists.SupplierID?.lookupOptions.length) {
        options.data = { id: "x_SupplierID", form: "fproductsadd" };
    } else {
        options.ajax = { id: "x_SupplierID", form: "fproductsadd", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options = Object.assign({}, ew.modalLookupOptions, options, ew.vars.tables.products.fields.SupplierID.modalLookupOptions);
    ew.createModalLookup(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->CategoryID->Visible) { // CategoryID ?>
    <div id="r_CategoryID"<?= $Page->CategoryID->rowAttributes() ?>>
        <label id="elh_products_CategoryID" for="x_CategoryID" class="<?= $Page->LeftColumnClass ?>"><?= $Page->CategoryID->caption() ?><?= $Page->CategoryID->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->CategoryID->cellAttributes() ?>>
<?php if ($Page->CategoryID->getSessionValue() != "") { ?>
<span<?= $Page->CategoryID->viewAttributes() ?>>
<span class="form-control-plaintext"><?= $Page->CategoryID->getDisplayValue($Page->CategoryID->ViewValue) ?></span></span>
<input type="hidden" id="x_CategoryID" name="x_CategoryID" value="<?= HtmlEncode($Page->CategoryID->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el_products_CategoryID">
    <select
        id="x_CategoryID"
        name="x_CategoryID"
        class="form-select ew-select<?= $Page->CategoryID->isInvalidClass() ?>"
        <?php if (!$Page->CategoryID->IsNativeSelect) { ?>
        data-select2-id="fproductsadd_x_CategoryID"
        <?php } ?>
        data-table="products"
        data-field="x_CategoryID"
        data-value-separator="<?= $Page->CategoryID->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->CategoryID->getPlaceHolder()) ?>"
        <?= $Page->CategoryID->editAttributes() ?>>
        <?= $Page->CategoryID->selectOptionListHtml("x_CategoryID") ?>
    </select>
    <?= $Page->CategoryID->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->CategoryID->getErrorMessage() ?></div>
<?= $Page->CategoryID->Lookup->getParamTag($Page, "p_x_CategoryID") ?>
<?php if (!$Page->CategoryID->IsNativeSelect) { ?>
<script>
loadjs.ready("fproductsadd", function() {
    var options = { name: "x_CategoryID", selectId: "fproductsadd_x_CategoryID" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fproductsadd.lists.CategoryID?.lookupOptions.length) {
        options.data = { id: "x_CategoryID", form: "fproductsadd" };
    } else {
        options.ajax = { id: "x_CategoryID", form: "fproductsadd", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.products.fields.CategoryID.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
<?php } ?>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->QuantityPerUnit->Visible) { // QuantityPerUnit ?>
    <div id="r_QuantityPerUnit"<?= $Page->QuantityPerUnit->rowAttributes() ?>>
        <label id="elh_products_QuantityPerUnit" for="x_QuantityPerUnit" class="<?= $Page->LeftColumnClass ?>"><?= $Page->QuantityPerUnit->caption() ?><?= $Page->QuantityPerUnit->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->QuantityPerUnit->cellAttributes() ?>>
<span id="el_products_QuantityPerUnit">
<input type="<?= $Page->QuantityPerUnit->getInputTextType() ?>" name="x_QuantityPerUnit" id="x_QuantityPerUnit" data-table="products" data-field="x_QuantityPerUnit" value="<?= $Page->QuantityPerUnit->EditValue ?>" size="30" maxlength="20" placeholder="<?= HtmlEncode($Page->QuantityPerUnit->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->QuantityPerUnit->formatPattern()) ?>"<?= $Page->QuantityPerUnit->editAttributes() ?> aria-describedby="x_QuantityPerUnit_help">
<?= $Page->QuantityPerUnit->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->QuantityPerUnit->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->UnitPrice->Visible) { // UnitPrice ?>
    <div id="r_UnitPrice"<?= $Page->UnitPrice->rowAttributes() ?>>
        <label id="elh_products_UnitPrice" for="x_UnitPrice" class="<?= $Page->LeftColumnClass ?>"><?= $Page->UnitPrice->caption() ?><?= $Page->UnitPrice->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->UnitPrice->cellAttributes() ?>>
<span id="el_products_UnitPrice">
<input type="<?= $Page->UnitPrice->getInputTextType() ?>" name="x_UnitPrice" id="x_UnitPrice" data-table="products" data-field="x_UnitPrice" value="<?= $Page->UnitPrice->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->UnitPrice->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->UnitPrice->formatPattern()) ?>"<?= $Page->UnitPrice->editAttributes() ?> aria-describedby="x_UnitPrice_help">
<?= $Page->UnitPrice->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->UnitPrice->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->UnitsInStock->Visible) { // UnitsInStock ?>
    <div id="r_UnitsInStock"<?= $Page->UnitsInStock->rowAttributes() ?>>
        <label id="elh_products_UnitsInStock" for="x_UnitsInStock" class="<?= $Page->LeftColumnClass ?>"><?= $Page->UnitsInStock->caption() ?><?= $Page->UnitsInStock->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->UnitsInStock->cellAttributes() ?>>
<span id="el_products_UnitsInStock">
<input type="<?= $Page->UnitsInStock->getInputTextType() ?>" name="x_UnitsInStock" id="x_UnitsInStock" data-table="products" data-field="x_UnitsInStock" value="<?= $Page->UnitsInStock->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->UnitsInStock->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->UnitsInStock->formatPattern()) ?>"<?= $Page->UnitsInStock->editAttributes() ?> aria-describedby="x_UnitsInStock_help">
<?= $Page->UnitsInStock->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->UnitsInStock->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->UnitsOnOrder->Visible) { // UnitsOnOrder ?>
    <div id="r_UnitsOnOrder"<?= $Page->UnitsOnOrder->rowAttributes() ?>>
        <label id="elh_products_UnitsOnOrder" for="x_UnitsOnOrder" class="<?= $Page->LeftColumnClass ?>"><?= $Page->UnitsOnOrder->caption() ?><?= $Page->UnitsOnOrder->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->UnitsOnOrder->cellAttributes() ?>>
<span id="el_products_UnitsOnOrder">
<input type="<?= $Page->UnitsOnOrder->getInputTextType() ?>" name="x_UnitsOnOrder" id="x_UnitsOnOrder" data-table="products" data-field="x_UnitsOnOrder" value="<?= $Page->UnitsOnOrder->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->UnitsOnOrder->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->UnitsOnOrder->formatPattern()) ?>"<?= $Page->UnitsOnOrder->editAttributes() ?> aria-describedby="x_UnitsOnOrder_help">
<?= $Page->UnitsOnOrder->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->UnitsOnOrder->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->ReorderLevel->Visible) { // ReorderLevel ?>
    <div id="r_ReorderLevel"<?= $Page->ReorderLevel->rowAttributes() ?>>
        <label id="elh_products_ReorderLevel" for="x_ReorderLevel" class="<?= $Page->LeftColumnClass ?>"><?= $Page->ReorderLevel->caption() ?><?= $Page->ReorderLevel->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->ReorderLevel->cellAttributes() ?>>
<span id="el_products_ReorderLevel">
<input type="<?= $Page->ReorderLevel->getInputTextType() ?>" name="x_ReorderLevel" id="x_ReorderLevel" data-table="products" data-field="x_ReorderLevel" value="<?= $Page->ReorderLevel->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->ReorderLevel->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->ReorderLevel->formatPattern()) ?>"<?= $Page->ReorderLevel->editAttributes() ?> aria-describedby="x_ReorderLevel_help">
<?= $Page->ReorderLevel->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->ReorderLevel->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->Discontinued->Visible) { // Discontinued ?>
    <div id="r_Discontinued"<?= $Page->Discontinued->rowAttributes() ?>>
        <label id="elh_products_Discontinued" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Discontinued->caption() ?><?= $Page->Discontinued->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Discontinued->cellAttributes() ?>>
<span id="el_products_Discontinued">
<div class="form-check d-inline-block">
    <input type="checkbox" class="form-check-input<?= $Page->Discontinued->isInvalidClass() ?>" data-table="products" data-field="x_Discontinued" data-boolean name="x_Discontinued" id="x_Discontinued" value="1"<?= ConvertToBool($Page->Discontinued->CurrentValue) ? " checked" : "" ?><?= $Page->Discontinued->editAttributes() ?> aria-describedby="x_Discontinued_help">
    <div class="invalid-feedback"><?= $Page->Discontinued->getErrorMessage() ?></div>
</div>
<?= $Page->Discontinued->getCustomMessage() ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->EAN13->Visible) { // EAN13 ?>
    <div id="r_EAN13"<?= $Page->EAN13->rowAttributes() ?>>
        <label id="elh_products_EAN13" for="x_EAN13" class="<?= $Page->LeftColumnClass ?>"><?= $Page->EAN13->caption() ?><?= $Page->EAN13->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->EAN13->cellAttributes() ?>>
<span id="el_products_EAN13">
<input type="<?= $Page->EAN13->getInputTextType() ?>" name="x_EAN13" id="x_EAN13" data-table="products" data-field="x_EAN13" value="<?= $Page->EAN13->EditValue ?>" size="30" maxlength="13" placeholder="<?= HtmlEncode($Page->EAN13->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->EAN13->formatPattern()) ?>"<?= $Page->EAN13->editAttributes() ?> aria-describedby="x_EAN13_help">
<?= $Page->EAN13->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->EAN13->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="fproductsadd"><?= $Language->phrase("AddBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="fproductsadd" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
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
