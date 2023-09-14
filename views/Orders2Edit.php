<?php

namespace PHPMaker2023\demo2023;

// Page object
$Orders2Edit = &$Page;
?>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<main class="edit">
<form name="forders2edit" id="forders2edit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="on">
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { orders2: currentTable } });
var currentPageID = ew.PAGE_ID = "edit";
var currentForm;
var forders2edit;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("forders2edit")
        .setPageId("edit")

        // Add fields
        .setFields([
            ["OrderID", [fields.OrderID.visible && fields.OrderID.required ? ew.Validators.required(fields.OrderID.caption) : null], fields.OrderID.isInvalid],
            ["CustomerID", [fields.CustomerID.visible && fields.CustomerID.required ? ew.Validators.required(fields.CustomerID.caption) : null], fields.CustomerID.isInvalid],
            ["EmployeeID", [fields.EmployeeID.visible && fields.EmployeeID.required ? ew.Validators.required(fields.EmployeeID.caption) : null], fields.EmployeeID.isInvalid],
            ["OrderDate", [fields.OrderDate.visible && fields.OrderDate.required ? ew.Validators.required(fields.OrderDate.caption) : null, ew.Validators.datetime(fields.OrderDate.clientFormatPattern)], fields.OrderDate.isInvalid],
            ["RequiredDate", [fields.RequiredDate.visible && fields.RequiredDate.required ? ew.Validators.required(fields.RequiredDate.caption) : null, ew.Validators.datetime(fields.RequiredDate.clientFormatPattern)], fields.RequiredDate.isInvalid],
            ["ShippedDate", [fields.ShippedDate.visible && fields.ShippedDate.required ? ew.Validators.required(fields.ShippedDate.caption) : null, ew.Validators.datetime(fields.ShippedDate.clientFormatPattern)], fields.ShippedDate.isInvalid],
            ["ShipVia", [fields.ShipVia.visible && fields.ShipVia.required ? ew.Validators.required(fields.ShipVia.caption) : null, ew.Validators.integer], fields.ShipVia.isInvalid],
            ["Freight", [fields.Freight.visible && fields.Freight.required ? ew.Validators.required(fields.Freight.caption) : null, ew.Validators.float], fields.Freight.isInvalid],
            ["ShipName", [fields.ShipName.visible && fields.ShipName.required ? ew.Validators.required(fields.ShipName.caption) : null], fields.ShipName.isInvalid],
            ["ShipAddress", [fields.ShipAddress.visible && fields.ShipAddress.required ? ew.Validators.required(fields.ShipAddress.caption) : null], fields.ShipAddress.isInvalid],
            ["ShipCity", [fields.ShipCity.visible && fields.ShipCity.required ? ew.Validators.required(fields.ShipCity.caption) : null], fields.ShipCity.isInvalid],
            ["ShipRegion", [fields.ShipRegion.visible && fields.ShipRegion.required ? ew.Validators.required(fields.ShipRegion.caption) : null], fields.ShipRegion.isInvalid],
            ["ShipPostalCode", [fields.ShipPostalCode.visible && fields.ShipPostalCode.required ? ew.Validators.required(fields.ShipPostalCode.caption) : null], fields.ShipPostalCode.isInvalid],
            ["ShipCountry", [fields.ShipCountry.visible && fields.ShipCountry.required ? ew.Validators.required(fields.ShipCountry.caption) : null], fields.ShipCountry.isInvalid]
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
            "CustomerID": <?= $Page->CustomerID->toClientList($Page) ?>,
            "EmployeeID": <?= $Page->EmployeeID->toClientList($Page) ?>,
        })
        .build();
    window[form.id] = form;
    currentForm = form;
    loadjs.done(form.id);
});
</script>
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="orders2">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->OrderID->Visible) { // OrderID ?>
    <div id="r_OrderID"<?= $Page->OrderID->rowAttributes() ?>>
        <label id="elh_orders2_OrderID" class="<?= $Page->LeftColumnClass ?>"><?= $Page->OrderID->caption() ?><?= $Page->OrderID->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->OrderID->cellAttributes() ?>>
<span id="el_orders2_OrderID">
<span<?= $Page->OrderID->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->OrderID->getDisplayValue($Page->OrderID->EditValue))) ?>"></span>
<input type="hidden" data-table="orders2" data-field="x_OrderID" data-hidden="1" name="x_OrderID" id="x_OrderID" value="<?= HtmlEncode($Page->OrderID->CurrentValue) ?>">
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->CustomerID->Visible) { // CustomerID ?>
    <div id="r_CustomerID"<?= $Page->CustomerID->rowAttributes() ?>>
        <label id="elh_orders2_CustomerID" for="x_CustomerID" class="<?= $Page->LeftColumnClass ?>"><?= $Page->CustomerID->caption() ?><?= $Page->CustomerID->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->CustomerID->cellAttributes() ?>>
<span id="el_orders2_CustomerID">
    <select
        id="x_CustomerID"
        name="x_CustomerID"
        class="form-select ew-select<?= $Page->CustomerID->isInvalidClass() ?>"
        <?php if (!$Page->CustomerID->IsNativeSelect) { ?>
        data-select2-id="forders2edit_x_CustomerID"
        <?php } ?>
        data-table="orders2"
        data-field="x_CustomerID"
        data-value-separator="<?= $Page->CustomerID->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->CustomerID->getPlaceHolder()) ?>"
        <?= $Page->CustomerID->editAttributes() ?>>
        <?= $Page->CustomerID->selectOptionListHtml("x_CustomerID") ?>
    </select>
    <?= $Page->CustomerID->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->CustomerID->getErrorMessage() ?></div>
<?= $Page->CustomerID->Lookup->getParamTag($Page, "p_x_CustomerID") ?>
<?php if (!$Page->CustomerID->IsNativeSelect) { ?>
<script>
loadjs.ready("forders2edit", function() {
    var options = { name: "x_CustomerID", selectId: "forders2edit_x_CustomerID" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (forders2edit.lists.CustomerID?.lookupOptions.length) {
        options.data = { id: "x_CustomerID", form: "forders2edit" };
    } else {
        options.ajax = { id: "x_CustomerID", form: "forders2edit", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.orders2.fields.CustomerID.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->EmployeeID->Visible) { // EmployeeID ?>
    <div id="r_EmployeeID"<?= $Page->EmployeeID->rowAttributes() ?>>
        <label id="elh_orders2_EmployeeID" for="x_EmployeeID" class="<?= $Page->LeftColumnClass ?>"><?= $Page->EmployeeID->caption() ?><?= $Page->EmployeeID->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->EmployeeID->cellAttributes() ?>>
<span id="el_orders2_EmployeeID">
    <select
        id="x_EmployeeID"
        name="x_EmployeeID"
        class="form-select ew-select<?= $Page->EmployeeID->isInvalidClass() ?>"
        <?php if (!$Page->EmployeeID->IsNativeSelect) { ?>
        data-select2-id="forders2edit_x_EmployeeID"
        <?php } ?>
        data-table="orders2"
        data-field="x_EmployeeID"
        data-value-separator="<?= $Page->EmployeeID->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->EmployeeID->getPlaceHolder()) ?>"
        <?= $Page->EmployeeID->editAttributes() ?>>
        <?= $Page->EmployeeID->selectOptionListHtml("x_EmployeeID") ?>
    </select>
    <?= $Page->EmployeeID->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->EmployeeID->getErrorMessage() ?></div>
<?= $Page->EmployeeID->Lookup->getParamTag($Page, "p_x_EmployeeID") ?>
<?php if (!$Page->EmployeeID->IsNativeSelect) { ?>
<script>
loadjs.ready("forders2edit", function() {
    var options = { name: "x_EmployeeID", selectId: "forders2edit_x_EmployeeID" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (forders2edit.lists.EmployeeID?.lookupOptions.length) {
        options.data = { id: "x_EmployeeID", form: "forders2edit" };
    } else {
        options.ajax = { id: "x_EmployeeID", form: "forders2edit", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.orders2.fields.EmployeeID.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->OrderDate->Visible) { // OrderDate ?>
    <div id="r_OrderDate"<?= $Page->OrderDate->rowAttributes() ?>>
        <label id="elh_orders2_OrderDate" for="x_OrderDate" class="<?= $Page->LeftColumnClass ?>"><?= $Page->OrderDate->caption() ?><?= $Page->OrderDate->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->OrderDate->cellAttributes() ?>>
<span id="el_orders2_OrderDate">
<input type="<?= $Page->OrderDate->getInputTextType() ?>" name="x_OrderDate" id="x_OrderDate" data-table="orders2" data-field="x_OrderDate" value="<?= $Page->OrderDate->EditValue ?>" placeholder="<?= HtmlEncode($Page->OrderDate->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->OrderDate->formatPattern()) ?>"<?= $Page->OrderDate->editAttributes() ?> aria-describedby="x_OrderDate_help">
<?= $Page->OrderDate->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->OrderDate->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->RequiredDate->Visible) { // RequiredDate ?>
    <div id="r_RequiredDate"<?= $Page->RequiredDate->rowAttributes() ?>>
        <label id="elh_orders2_RequiredDate" for="x_RequiredDate" class="<?= $Page->LeftColumnClass ?>"><?= $Page->RequiredDate->caption() ?><?= $Page->RequiredDate->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->RequiredDate->cellAttributes() ?>>
<span id="el_orders2_RequiredDate">
<input type="<?= $Page->RequiredDate->getInputTextType() ?>" name="x_RequiredDate" id="x_RequiredDate" data-table="orders2" data-field="x_RequiredDate" value="<?= $Page->RequiredDate->EditValue ?>" placeholder="<?= HtmlEncode($Page->RequiredDate->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->RequiredDate->formatPattern()) ?>"<?= $Page->RequiredDate->editAttributes() ?> aria-describedby="x_RequiredDate_help">
<?= $Page->RequiredDate->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->RequiredDate->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->ShippedDate->Visible) { // ShippedDate ?>
    <div id="r_ShippedDate"<?= $Page->ShippedDate->rowAttributes() ?>>
        <label id="elh_orders2_ShippedDate" for="x_ShippedDate" class="<?= $Page->LeftColumnClass ?>"><?= $Page->ShippedDate->caption() ?><?= $Page->ShippedDate->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->ShippedDate->cellAttributes() ?>>
<span id="el_orders2_ShippedDate">
<input type="<?= $Page->ShippedDate->getInputTextType() ?>" name="x_ShippedDate" id="x_ShippedDate" data-table="orders2" data-field="x_ShippedDate" value="<?= $Page->ShippedDate->EditValue ?>" placeholder="<?= HtmlEncode($Page->ShippedDate->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->ShippedDate->formatPattern()) ?>"<?= $Page->ShippedDate->editAttributes() ?> aria-describedby="x_ShippedDate_help">
<?= $Page->ShippedDate->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->ShippedDate->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->ShipVia->Visible) { // ShipVia ?>
    <div id="r_ShipVia"<?= $Page->ShipVia->rowAttributes() ?>>
        <label id="elh_orders2_ShipVia" for="x_ShipVia" class="<?= $Page->LeftColumnClass ?>"><?= $Page->ShipVia->caption() ?><?= $Page->ShipVia->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->ShipVia->cellAttributes() ?>>
<span id="el_orders2_ShipVia">
<input type="<?= $Page->ShipVia->getInputTextType() ?>" name="x_ShipVia" id="x_ShipVia" data-table="orders2" data-field="x_ShipVia" value="<?= $Page->ShipVia->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->ShipVia->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->ShipVia->formatPattern()) ?>"<?= $Page->ShipVia->editAttributes() ?> aria-describedby="x_ShipVia_help">
<?= $Page->ShipVia->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->ShipVia->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->Freight->Visible) { // Freight ?>
    <div id="r_Freight"<?= $Page->Freight->rowAttributes() ?>>
        <label id="elh_orders2_Freight" for="x_Freight" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Freight->caption() ?><?= $Page->Freight->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Freight->cellAttributes() ?>>
<span id="el_orders2_Freight">
<input type="<?= $Page->Freight->getInputTextType() ?>" name="x_Freight" id="x_Freight" data-table="orders2" data-field="x_Freight" value="<?= $Page->Freight->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->Freight->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Freight->formatPattern()) ?>"<?= $Page->Freight->editAttributes() ?> aria-describedby="x_Freight_help">
<?= $Page->Freight->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->Freight->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->ShipName->Visible) { // ShipName ?>
    <div id="r_ShipName"<?= $Page->ShipName->rowAttributes() ?>>
        <label id="elh_orders2_ShipName" for="x_ShipName" class="<?= $Page->LeftColumnClass ?>"><?= $Page->ShipName->caption() ?><?= $Page->ShipName->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->ShipName->cellAttributes() ?>>
<span id="el_orders2_ShipName">
<input type="<?= $Page->ShipName->getInputTextType() ?>" name="x_ShipName" id="x_ShipName" data-table="orders2" data-field="x_ShipName" value="<?= $Page->ShipName->EditValue ?>" size="30" maxlength="40" placeholder="<?= HtmlEncode($Page->ShipName->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->ShipName->formatPattern()) ?>"<?= $Page->ShipName->editAttributes() ?> aria-describedby="x_ShipName_help">
<?= $Page->ShipName->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->ShipName->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->ShipAddress->Visible) { // ShipAddress ?>
    <div id="r_ShipAddress"<?= $Page->ShipAddress->rowAttributes() ?>>
        <label id="elh_orders2_ShipAddress" for="x_ShipAddress" class="<?= $Page->LeftColumnClass ?>"><?= $Page->ShipAddress->caption() ?><?= $Page->ShipAddress->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->ShipAddress->cellAttributes() ?>>
<span id="el_orders2_ShipAddress">
<input type="<?= $Page->ShipAddress->getInputTextType() ?>" name="x_ShipAddress" id="x_ShipAddress" data-table="orders2" data-field="x_ShipAddress" value="<?= $Page->ShipAddress->EditValue ?>" size="30" maxlength="60" placeholder="<?= HtmlEncode($Page->ShipAddress->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->ShipAddress->formatPattern()) ?>"<?= $Page->ShipAddress->editAttributes() ?> aria-describedby="x_ShipAddress_help">
<?= $Page->ShipAddress->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->ShipAddress->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->ShipCity->Visible) { // ShipCity ?>
    <div id="r_ShipCity"<?= $Page->ShipCity->rowAttributes() ?>>
        <label id="elh_orders2_ShipCity" for="x_ShipCity" class="<?= $Page->LeftColumnClass ?>"><?= $Page->ShipCity->caption() ?><?= $Page->ShipCity->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->ShipCity->cellAttributes() ?>>
<span id="el_orders2_ShipCity">
<input type="<?= $Page->ShipCity->getInputTextType() ?>" name="x_ShipCity" id="x_ShipCity" data-table="orders2" data-field="x_ShipCity" value="<?= $Page->ShipCity->EditValue ?>" size="30" maxlength="15" placeholder="<?= HtmlEncode($Page->ShipCity->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->ShipCity->formatPattern()) ?>"<?= $Page->ShipCity->editAttributes() ?> aria-describedby="x_ShipCity_help">
<?= $Page->ShipCity->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->ShipCity->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->ShipRegion->Visible) { // ShipRegion ?>
    <div id="r_ShipRegion"<?= $Page->ShipRegion->rowAttributes() ?>>
        <label id="elh_orders2_ShipRegion" for="x_ShipRegion" class="<?= $Page->LeftColumnClass ?>"><?= $Page->ShipRegion->caption() ?><?= $Page->ShipRegion->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->ShipRegion->cellAttributes() ?>>
<span id="el_orders2_ShipRegion">
<input type="<?= $Page->ShipRegion->getInputTextType() ?>" name="x_ShipRegion" id="x_ShipRegion" data-table="orders2" data-field="x_ShipRegion" value="<?= $Page->ShipRegion->EditValue ?>" size="30" maxlength="15" placeholder="<?= HtmlEncode($Page->ShipRegion->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->ShipRegion->formatPattern()) ?>"<?= $Page->ShipRegion->editAttributes() ?> aria-describedby="x_ShipRegion_help">
<?= $Page->ShipRegion->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->ShipRegion->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->ShipPostalCode->Visible) { // ShipPostalCode ?>
    <div id="r_ShipPostalCode"<?= $Page->ShipPostalCode->rowAttributes() ?>>
        <label id="elh_orders2_ShipPostalCode" for="x_ShipPostalCode" class="<?= $Page->LeftColumnClass ?>"><?= $Page->ShipPostalCode->caption() ?><?= $Page->ShipPostalCode->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->ShipPostalCode->cellAttributes() ?>>
<span id="el_orders2_ShipPostalCode">
<input type="<?= $Page->ShipPostalCode->getInputTextType() ?>" name="x_ShipPostalCode" id="x_ShipPostalCode" data-table="orders2" data-field="x_ShipPostalCode" value="<?= $Page->ShipPostalCode->EditValue ?>" size="30" maxlength="10" placeholder="<?= HtmlEncode($Page->ShipPostalCode->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->ShipPostalCode->formatPattern()) ?>"<?= $Page->ShipPostalCode->editAttributes() ?> aria-describedby="x_ShipPostalCode_help">
<?= $Page->ShipPostalCode->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->ShipPostalCode->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->ShipCountry->Visible) { // ShipCountry ?>
    <div id="r_ShipCountry"<?= $Page->ShipCountry->rowAttributes() ?>>
        <label id="elh_orders2_ShipCountry" for="x_ShipCountry" class="<?= $Page->LeftColumnClass ?>"><?= $Page->ShipCountry->caption() ?><?= $Page->ShipCountry->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->ShipCountry->cellAttributes() ?>>
<span id="el_orders2_ShipCountry">
<input type="<?= $Page->ShipCountry->getInputTextType() ?>" name="x_ShipCountry" id="x_ShipCountry" data-table="orders2" data-field="x_ShipCountry" value="<?= $Page->ShipCountry->EditValue ?>" size="30" maxlength="15" placeholder="<?= HtmlEncode($Page->ShipCountry->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->ShipCountry->formatPattern()) ?>"<?= $Page->ShipCountry->editAttributes() ?> aria-describedby="x_ShipCountry_help">
<?= $Page->ShipCountry->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->ShipCountry->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="forders2edit"><?= $Language->phrase("SaveBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="forders2edit" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
<?php } ?>
    </div><!-- /buttons offset -->
<?= $Page->IsModal ? "</template>" : "</div>" ?><!-- /buttons .row -->
</form>
</main>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<script>
// Field event handlers
loadjs.ready("head", function() {
    ew.addEventHandlers("orders2");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
