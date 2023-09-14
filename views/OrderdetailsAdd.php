<?php

namespace PHPMaker2023\demo2023;

// Page object
$OrderdetailsAdd = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { orderdetails: currentTable } });
var currentPageID = ew.PAGE_ID = "add";
var currentForm;
var forderdetailsadd;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("forderdetailsadd")
        .setPageId("add")

        // Add fields
        .setFields([
            ["OrderID", [fields.OrderID.visible && fields.OrderID.required ? ew.Validators.required(fields.OrderID.caption) : null, ew.Validators.integer], fields.OrderID.isInvalid],
            ["ProductID", [fields.ProductID.visible && fields.ProductID.required ? ew.Validators.required(fields.ProductID.caption) : null], fields.ProductID.isInvalid],
            ["UnitPrice", [fields.UnitPrice.visible && fields.UnitPrice.required ? ew.Validators.required(fields.UnitPrice.caption) : null, ew.Validators.float], fields.UnitPrice.isInvalid],
            ["Quantity", [fields.Quantity.visible && fields.Quantity.required ? ew.Validators.required(fields.Quantity.caption) : null, ew.Validators.integer], fields.Quantity.isInvalid],
            ["Discount", [fields.Discount.visible && fields.Discount.required ? ew.Validators.required(fields.Discount.caption) : null, ew.Validators.float], fields.Discount.isInvalid],
            ["SubTotal", [fields.SubTotal.visible && fields.SubTotal.required ? ew.Validators.required(fields.SubTotal.caption) : null, ew.Validators.float], fields.SubTotal.isInvalid]
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
            "ProductID": <?= $Page->ProductID->toClientList($Page) ?>,
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
<form name="forderdetailsadd" id="forderdetailsadd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="on">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="orderdetails">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<?php if ($Page->getCurrentMasterTable() == "orders") { ?>
<input type="hidden" name="<?= Config("TABLE_SHOW_MASTER") ?>" value="orders">
<input type="hidden" name="fk_OrderID" value="<?= HtmlEncode($Page->OrderID->getSessionValue()) ?>">
<?php } ?>
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->OrderID->Visible) { // OrderID ?>
    <div id="r_OrderID"<?= $Page->OrderID->rowAttributes() ?>>
        <label id="elh_orderdetails_OrderID" for="x_OrderID" class="<?= $Page->LeftColumnClass ?>"><?= $Page->OrderID->caption() ?><?= $Page->OrderID->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->OrderID->cellAttributes() ?>>
<?php if ($Page->OrderID->getSessionValue() != "") { ?>
<span<?= $Page->OrderID->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->OrderID->getDisplayValue($Page->OrderID->ViewValue))) ?>"></span>
<input type="hidden" id="x_OrderID" name="x_OrderID" value="<?= HtmlEncode($Page->OrderID->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el_orderdetails_OrderID">
<input type="<?= $Page->OrderID->getInputTextType() ?>" name="x_OrderID" id="x_OrderID" data-table="orderdetails" data-field="x_OrderID" value="<?= $Page->OrderID->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->OrderID->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->OrderID->formatPattern()) ?>"<?= $Page->OrderID->editAttributes() ?> aria-describedby="x_OrderID_help">
<?= $Page->OrderID->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->OrderID->getErrorMessage() ?></div>
</span>
<?php } ?>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->ProductID->Visible) { // ProductID ?>
    <div id="r_ProductID"<?= $Page->ProductID->rowAttributes() ?>>
        <label id="elh_orderdetails_ProductID" for="x_ProductID" class="<?= $Page->LeftColumnClass ?>"><?= $Page->ProductID->caption() ?><?= $Page->ProductID->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->ProductID->cellAttributes() ?>>
<span id="el_orderdetails_ProductID">
    <select
        id="x_ProductID"
        name="x_ProductID"
        class="form-control ew-select<?= $Page->ProductID->isInvalidClass() ?>"
        data-select2-id="forderdetailsadd_x_ProductID"
        data-table="orderdetails"
        data-field="x_ProductID"
        data-caption="<?= HtmlEncode(RemoveHtml($Page->ProductID->caption())) ?>"
        data-modal-lookup="true"
        data-value-separator="<?= $Page->ProductID->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->ProductID->getPlaceHolder()) ?>"
        data-ew-action="autofill"
        <?= $Page->ProductID->editAttributes() ?>>
        <?= $Page->ProductID->selectOptionListHtml("x_ProductID") ?>
    </select>
    <?= $Page->ProductID->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->ProductID->getErrorMessage() ?></div>
<?= $Page->ProductID->Lookup->getParamTag($Page, "p_x_ProductID") ?>
<script>
loadjs.ready("forderdetailsadd", function() {
    var options = { name: "x_ProductID", selectId: "forderdetailsadd_x_ProductID" };
    if (forderdetailsadd.lists.ProductID?.lookupOptions.length) {
        options.data = { id: "x_ProductID", form: "forderdetailsadd" };
    } else {
        options.ajax = { id: "x_ProductID", form: "forderdetailsadd", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options = Object.assign({}, ew.modalLookupOptions, options, ew.vars.tables.orderdetails.fields.ProductID.modalLookupOptions);
    ew.createModalLookup(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->UnitPrice->Visible) { // UnitPrice ?>
    <div id="r_UnitPrice"<?= $Page->UnitPrice->rowAttributes() ?>>
        <label id="elh_orderdetails_UnitPrice" for="x_UnitPrice" class="<?= $Page->LeftColumnClass ?>"><?= $Page->UnitPrice->caption() ?><?= $Page->UnitPrice->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->UnitPrice->cellAttributes() ?>>
<span id="el_orderdetails_UnitPrice">
<input type="<?= $Page->UnitPrice->getInputTextType() ?>" name="x_UnitPrice" id="x_UnitPrice" data-table="orderdetails" data-field="x_UnitPrice" value="<?= $Page->UnitPrice->EditValue ?>" size="20" placeholder="<?= HtmlEncode($Page->UnitPrice->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->UnitPrice->formatPattern()) ?>"<?= $Page->UnitPrice->editAttributes() ?> aria-describedby="x_UnitPrice_help">
<?= $Page->UnitPrice->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->UnitPrice->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->Quantity->Visible) { // Quantity ?>
    <div id="r_Quantity"<?= $Page->Quantity->rowAttributes() ?>>
        <label id="elh_orderdetails_Quantity" for="x_Quantity" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Quantity->caption() ?><?= $Page->Quantity->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Quantity->cellAttributes() ?>>
<span id="el_orderdetails_Quantity">
<input type="<?= $Page->Quantity->getInputTextType() ?>" name="x_Quantity" id="x_Quantity" data-table="orderdetails" data-field="x_Quantity" value="<?= $Page->Quantity->EditValue ?>" size="20" placeholder="<?= HtmlEncode($Page->Quantity->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Quantity->formatPattern()) ?>"<?= $Page->Quantity->editAttributes() ?> aria-describedby="x_Quantity_help">
<?= $Page->Quantity->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->Quantity->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->Discount->Visible) { // Discount ?>
    <div id="r_Discount"<?= $Page->Discount->rowAttributes() ?>>
        <label id="elh_orderdetails_Discount" for="x_Discount" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Discount->caption() ?><?= $Page->Discount->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Discount->cellAttributes() ?>>
<span id="el_orderdetails_Discount">
<input type="<?= $Page->Discount->getInputTextType() ?>" name="x_Discount" id="x_Discount" data-table="orderdetails" data-field="x_Discount" value="<?= $Page->Discount->EditValue ?>" size="20" placeholder="<?= HtmlEncode($Page->Discount->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Discount->formatPattern()) ?>"<?= $Page->Discount->editAttributes() ?> aria-describedby="x_Discount_help">
<?= $Page->Discount->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->Discount->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->SubTotal->Visible) { // SubTotal ?>
    <div id="r_SubTotal"<?= $Page->SubTotal->rowAttributes() ?>>
        <label id="elh_orderdetails_SubTotal" for="x_SubTotal" class="<?= $Page->LeftColumnClass ?>"><?= $Page->SubTotal->caption() ?><?= $Page->SubTotal->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->SubTotal->cellAttributes() ?>>
<span id="el_orderdetails_SubTotal">
<input type="<?= $Page->SubTotal->getInputTextType() ?>" name="x_SubTotal" id="x_SubTotal" data-table="orderdetails" data-field="x_SubTotal" value="<?= $Page->SubTotal->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->SubTotal->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->SubTotal->formatPattern()) ?>"<?= $Page->SubTotal->editAttributes() ?> aria-describedby="x_SubTotal_help">
<?= $Page->SubTotal->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->SubTotal->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="forderdetailsadd"><?= $Language->phrase("AddBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="forderdetailsadd" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
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
