<?php

namespace PHPMaker2023\demo2023;

// Page object
$OrderdetailsSearch = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { orderdetails: currentTable } });
var currentPageID = ew.PAGE_ID = "search";
var currentForm;
var forderdetailssearch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery,
        fields = currentTable.fields;

    // Form object for search
    let form = new ew.FormBuilder()
        .setId("forderdetailssearch")
        .setPageId("search")
<?php if ($Page->IsModal && $Page->UseAjaxActions) { ?>
        .setSubmitWithFetch(true)
<?php } ?>

        // Add fields
        .addFields([
            ["OrderID", [ew.Validators.integer], fields.OrderID.isInvalid],
            ["ProductID", [], fields.ProductID.isInvalid],
            ["UnitPrice", [ew.Validators.float], fields.UnitPrice.isInvalid],
            ["Quantity", [ew.Validators.integer], fields.Quantity.isInvalid],
            ["Discount", [ew.Validators.float], fields.Discount.isInvalid],
            ["SubTotal", [ew.Validators.float], fields.SubTotal.isInvalid]
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
            "ProductID": <?= $Page->ProductID->toClientList($Page) ?>,
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
<form name="forderdetailssearch" id="forderdetailssearch" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="on">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="orderdetails">
<input type="hidden" name="action" id="action" value="search">
<?php if ($Page->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div class="ew-search-div"><!-- page* -->
<?php if ($Page->OrderID->Visible) { // OrderID ?>
    <div id="r_OrderID" class="row"<?= $Page->OrderID->rowAttributes() ?>>
        <label for="x_OrderID" class="<?= $Page->LeftColumnClass ?>"><span id="elh_orderdetails_OrderID"><?= $Page->OrderID->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_OrderID" id="z_OrderID" value="=">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->OrderID->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_orderdetails_OrderID" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->OrderID->getInputTextType() ?>" name="x_OrderID" id="x_OrderID" data-table="orderdetails" data-field="x_OrderID" value="<?= $Page->OrderID->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->OrderID->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->OrderID->formatPattern()) ?>"<?= $Page->OrderID->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->OrderID->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->ProductID->Visible) { // ProductID ?>
    <div id="r_ProductID" class="row"<?= $Page->ProductID->rowAttributes() ?>>
        <label for="x_ProductID" class="<?= $Page->LeftColumnClass ?>"><span id="elh_orderdetails_ProductID"><?= $Page->ProductID->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_ProductID" id="z_ProductID" value="=">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->ProductID->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_orderdetails_ProductID" class="ew-search-field ew-search-field-single">
    <select
        id="x_ProductID"
        name="x_ProductID"
        class="form-control ew-select<?= $Page->ProductID->isInvalidClass() ?>"
        data-select2-id="forderdetailssearch_x_ProductID"
        data-table="orderdetails"
        data-field="x_ProductID"
        data-caption="<?= HtmlEncode(RemoveHtml($Page->ProductID->caption())) ?>"
        data-modal-lookup="true"
        data-value-separator="<?= $Page->ProductID->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->ProductID->getPlaceHolder()) ?>"
        <?= $Page->ProductID->editAttributes() ?>>
        <?= $Page->ProductID->selectOptionListHtml("x_ProductID") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->ProductID->getErrorMessage(false) ?></div>
<?= $Page->ProductID->Lookup->getParamTag($Page, "p_x_ProductID") ?>
<script>
loadjs.ready("forderdetailssearch", function() {
    var options = { name: "x_ProductID", selectId: "forderdetailssearch_x_ProductID" };
    if (forderdetailssearch.lists.ProductID?.lookupOptions.length) {
        options.data = { id: "x_ProductID", form: "forderdetailssearch" };
    } else {
        options.ajax = { id: "x_ProductID", form: "forderdetailssearch", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options = Object.assign({}, ew.modalLookupOptions, options, ew.vars.tables.orderdetails.fields.ProductID.modalLookupOptions);
    ew.createModalLookup(options);
});
</script>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->UnitPrice->Visible) { // UnitPrice ?>
    <div id="r_UnitPrice" class="row"<?= $Page->UnitPrice->rowAttributes() ?>>
        <label for="x_UnitPrice" class="<?= $Page->LeftColumnClass ?>"><span id="elh_orderdetails_UnitPrice"><?= $Page->UnitPrice->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_UnitPrice" id="z_UnitPrice" value="=">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->UnitPrice->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_orderdetails_UnitPrice" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->UnitPrice->getInputTextType() ?>" name="x_UnitPrice" id="x_UnitPrice" data-table="orderdetails" data-field="x_UnitPrice" value="<?= $Page->UnitPrice->EditValue ?>" size="20" placeholder="<?= HtmlEncode($Page->UnitPrice->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->UnitPrice->formatPattern()) ?>"<?= $Page->UnitPrice->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->UnitPrice->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->Quantity->Visible) { // Quantity ?>
    <div id="r_Quantity" class="row"<?= $Page->Quantity->rowAttributes() ?>>
        <label for="x_Quantity" class="<?= $Page->LeftColumnClass ?>"><span id="elh_orderdetails_Quantity"><?= $Page->Quantity->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_Quantity" id="z_Quantity" value="=">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->Quantity->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_orderdetails_Quantity" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->Quantity->getInputTextType() ?>" name="x_Quantity" id="x_Quantity" data-table="orderdetails" data-field="x_Quantity" value="<?= $Page->Quantity->EditValue ?>" size="20" placeholder="<?= HtmlEncode($Page->Quantity->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Quantity->formatPattern()) ?>"<?= $Page->Quantity->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->Quantity->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->Discount->Visible) { // Discount ?>
    <div id="r_Discount" class="row"<?= $Page->Discount->rowAttributes() ?>>
        <label for="x_Discount" class="<?= $Page->LeftColumnClass ?>"><span id="elh_orderdetails_Discount"><?= $Page->Discount->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_Discount" id="z_Discount" value="=">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->Discount->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_orderdetails_Discount" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->Discount->getInputTextType() ?>" name="x_Discount" id="x_Discount" data-table="orderdetails" data-field="x_Discount" value="<?= $Page->Discount->EditValue ?>" size="20" placeholder="<?= HtmlEncode($Page->Discount->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Discount->formatPattern()) ?>"<?= $Page->Discount->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->Discount->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->SubTotal->Visible) { // SubTotal ?>
    <div id="r_SubTotal" class="row"<?= $Page->SubTotal->rowAttributes() ?>>
        <label for="x_SubTotal" class="<?= $Page->LeftColumnClass ?>"><span id="elh_orderdetails_SubTotal"><?= $Page->SubTotal->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_SubTotal" id="z_SubTotal" value="=">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->SubTotal->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_orderdetails_SubTotal" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->SubTotal->getInputTextType() ?>" name="x_SubTotal" id="x_SubTotal" data-table="orderdetails" data-field="x_SubTotal" value="<?= $Page->SubTotal->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->SubTotal->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->SubTotal->formatPattern()) ?>"<?= $Page->SubTotal->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->SubTotal->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
        <button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="forderdetailssearch"><?= $Language->phrase("Search") ?></button>
        <?php if ($Page->IsModal) { ?>
        <button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="forderdetailssearch"><?= $Language->phrase("Cancel") ?></button>
        <?php } else { ?>
        <button class="btn btn-default ew-btn" name="btn-reset" id="btn-reset" type="button" form="forderdetailssearch" data-ew-action="reload"><?= $Language->phrase("Reset") ?></button>
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
    ew.addEventHandlers("orderdetails");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
