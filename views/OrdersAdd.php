<?php

namespace PHPMaker2023\demo2023;

// Page object
$OrdersAdd = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { orders: currentTable } });
var currentPageID = ew.PAGE_ID = "add";
var currentForm;
var fordersadd;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fordersadd")
        .setPageId("add")

        // Add fields
        .setFields([
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
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<form name="fordersadd" id="fordersadd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="on">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="orders">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<?php if ($Page->getCurrentMasterTable() == "customers") { ?>
<input type="hidden" name="<?= Config("TABLE_SHOW_MASTER") ?>" value="customers">
<input type="hidden" name="fk_CustomerID" value="<?= HtmlEncode($Page->CustomerID->getSessionValue()) ?>">
<?php } ?>
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->CustomerID->Visible) { // CustomerID ?>
    <div id="r_CustomerID"<?= $Page->CustomerID->rowAttributes() ?>>
        <label id="elh_orders_CustomerID" for="x_CustomerID" class="<?= $Page->LeftColumnClass ?>"><?= $Page->CustomerID->caption() ?><?= $Page->CustomerID->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->CustomerID->cellAttributes() ?>>
<?php if ($Page->CustomerID->getSessionValue() != "") { ?>
<span<?= $Page->CustomerID->viewAttributes() ?>>
<span class="form-control-plaintext"><?= $Page->CustomerID->getDisplayValue($Page->CustomerID->ViewValue) ?></span></span>
<input type="hidden" id="x_CustomerID" name="x_CustomerID" value="<?= HtmlEncode($Page->CustomerID->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el_orders_CustomerID">
    <select
        id="x_CustomerID"
        name="x_CustomerID"
        class="form-control ew-select<?= $Page->CustomerID->isInvalidClass() ?>"
        data-select2-id="fordersadd_x_CustomerID"
        data-table="orders"
        data-field="x_CustomerID"
        data-caption="<?= HtmlEncode(RemoveHtml($Page->CustomerID->caption())) ?>"
        data-modal-lookup="true"
        data-value-separator="<?= $Page->CustomerID->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->CustomerID->getPlaceHolder()) ?>"
        <?= $Page->CustomerID->editAttributes() ?>>
        <?= $Page->CustomerID->selectOptionListHtml("x_CustomerID") ?>
    </select>
    <?= $Page->CustomerID->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->CustomerID->getErrorMessage() ?></div>
<?= $Page->CustomerID->Lookup->getParamTag($Page, "p_x_CustomerID") ?>
<script>
loadjs.ready("fordersadd", function() {
    var options = { name: "x_CustomerID", selectId: "fordersadd_x_CustomerID" };
    if (fordersadd.lists.CustomerID?.lookupOptions.length) {
        options.data = { id: "x_CustomerID", form: "fordersadd" };
    } else {
        options.ajax = { id: "x_CustomerID", form: "fordersadd", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options = Object.assign({}, ew.modalLookupOptions, options, ew.vars.tables.orders.fields.CustomerID.modalLookupOptions);
    ew.createModalLookup(options);
});
</script>
</span>
<?php } ?>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->EmployeeID->Visible) { // EmployeeID ?>
    <div id="r_EmployeeID"<?= $Page->EmployeeID->rowAttributes() ?>>
        <label id="elh_orders_EmployeeID" for="x_EmployeeID" class="<?= $Page->LeftColumnClass ?>"><?= $Page->EmployeeID->caption() ?><?= $Page->EmployeeID->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->EmployeeID->cellAttributes() ?>>
<?php if (!$Security->isAdmin() && $Security->isLoggedIn() && !$Page->userIDAllow("add")) { // Non system admin ?>
<span id="el_orders_EmployeeID">
    <select
        id="x_EmployeeID"
        name="x_EmployeeID"
        class="form-select ew-select<?= $Page->EmployeeID->isInvalidClass() ?>"
        <?php if (!$Page->EmployeeID->IsNativeSelect) { ?>
        data-select2-id="fordersadd_x_EmployeeID"
        <?php } ?>
        data-table="orders"
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
loadjs.ready("fordersadd", function() {
    var options = { name: "x_EmployeeID", selectId: "fordersadd_x_EmployeeID" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fordersadd.lists.EmployeeID?.lookupOptions.length) {
        options.data = { id: "x_EmployeeID", form: "fordersadd" };
    } else {
        options.ajax = { id: "x_EmployeeID", form: "fordersadd", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.orders.fields.EmployeeID.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
<?php } else { ?>
<span id="el_orders_EmployeeID">
    <select
        id="x_EmployeeID"
        name="x_EmployeeID"
        class="form-select ew-select<?= $Page->EmployeeID->isInvalidClass() ?>"
        <?php if (!$Page->EmployeeID->IsNativeSelect) { ?>
        data-select2-id="fordersadd_x_EmployeeID"
        <?php } ?>
        data-table="orders"
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
loadjs.ready("fordersadd", function() {
    var options = { name: "x_EmployeeID", selectId: "fordersadd_x_EmployeeID" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fordersadd.lists.EmployeeID?.lookupOptions.length) {
        options.data = { id: "x_EmployeeID", form: "fordersadd" };
    } else {
        options.ajax = { id: "x_EmployeeID", form: "fordersadd", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.orders.fields.EmployeeID.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
<?php } ?>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->OrderDate->Visible) { // OrderDate ?>
    <div id="r_OrderDate"<?= $Page->OrderDate->rowAttributes() ?>>
        <label id="elh_orders_OrderDate" for="x_OrderDate" class="<?= $Page->LeftColumnClass ?>"><?= $Page->OrderDate->caption() ?><?= $Page->OrderDate->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->OrderDate->cellAttributes() ?>>
<span id="el_orders_OrderDate">
<input type="<?= $Page->OrderDate->getInputTextType() ?>" name="x_OrderDate" id="x_OrderDate" data-table="orders" data-field="x_OrderDate" value="<?= $Page->OrderDate->EditValue ?>" placeholder="<?= HtmlEncode($Page->OrderDate->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->OrderDate->formatPattern()) ?>"<?= $Page->OrderDate->editAttributes() ?> aria-describedby="x_OrderDate_help">
<?= $Page->OrderDate->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->OrderDate->getErrorMessage() ?></div>
<?php if (!$Page->OrderDate->ReadOnly && !$Page->OrderDate->Disabled && !isset($Page->OrderDate->EditAttrs["readonly"]) && !isset($Page->OrderDate->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fordersadd", "datetimepicker"], function () {
    let format = "<?= DateFormat(0) ?>",
        options = {
            localization: {
                locale: ew.LANGUAGE_ID + "-u-nu-" + ew.getNumberingSystem(),
                hourCycle: format.match(/H/) ? "h24" : "h12",
                format,
                ...ew.language.phrase("datetimepicker")
            },
            display: {
                icons: {
                    previous: ew.IS_RTL ? "fa-solid fa-chevron-right" : "fa-solid fa-chevron-left",
                    next: ew.IS_RTL ? "fa-solid fa-chevron-left" : "fa-solid fa-chevron-right"
                },
                components: {
                    hours: !!format.match(/h/i),
                    minutes: !!format.match(/m/),
                    seconds: !!format.match(/s/i)
                },
                theme: ew.isDark() ? "dark" : "auto"
            }
        };
    ew.createDateTimePicker("fordersadd", "x_OrderDate", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->RequiredDate->Visible) { // RequiredDate ?>
    <div id="r_RequiredDate"<?= $Page->RequiredDate->rowAttributes() ?>>
        <label id="elh_orders_RequiredDate" for="x_RequiredDate" class="<?= $Page->LeftColumnClass ?>"><?= $Page->RequiredDate->caption() ?><?= $Page->RequiredDate->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->RequiredDate->cellAttributes() ?>>
<span id="el_orders_RequiredDate">
<input type="<?= $Page->RequiredDate->getInputTextType() ?>" name="x_RequiredDate" id="x_RequiredDate" data-table="orders" data-field="x_RequiredDate" value="<?= $Page->RequiredDate->EditValue ?>" placeholder="<?= HtmlEncode($Page->RequiredDate->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->RequiredDate->formatPattern()) ?>"<?= $Page->RequiredDate->editAttributes() ?> aria-describedby="x_RequiredDate_help">
<?= $Page->RequiredDate->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->RequiredDate->getErrorMessage() ?></div>
<?php if (!$Page->RequiredDate->ReadOnly && !$Page->RequiredDate->Disabled && !isset($Page->RequiredDate->EditAttrs["readonly"]) && !isset($Page->RequiredDate->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fordersadd", "datetimepicker"], function () {
    let format = "<?= DateFormat(0) ?>",
        options = {
            localization: {
                locale: ew.LANGUAGE_ID + "-u-nu-" + ew.getNumberingSystem(),
                hourCycle: format.match(/H/) ? "h24" : "h12",
                format,
                ...ew.language.phrase("datetimepicker")
            },
            display: {
                icons: {
                    previous: ew.IS_RTL ? "fa-solid fa-chevron-right" : "fa-solid fa-chevron-left",
                    next: ew.IS_RTL ? "fa-solid fa-chevron-left" : "fa-solid fa-chevron-right"
                },
                components: {
                    hours: !!format.match(/h/i),
                    minutes: !!format.match(/m/),
                    seconds: !!format.match(/s/i)
                },
                theme: ew.isDark() ? "dark" : "auto"
            }
        };
    ew.createDateTimePicker("fordersadd", "x_RequiredDate", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->ShippedDate->Visible) { // ShippedDate ?>
    <div id="r_ShippedDate"<?= $Page->ShippedDate->rowAttributes() ?>>
        <label id="elh_orders_ShippedDate" for="x_ShippedDate" class="<?= $Page->LeftColumnClass ?>"><?= $Page->ShippedDate->caption() ?><?= $Page->ShippedDate->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->ShippedDate->cellAttributes() ?>>
<span id="el_orders_ShippedDate">
<input type="<?= $Page->ShippedDate->getInputTextType() ?>" name="x_ShippedDate" id="x_ShippedDate" data-table="orders" data-field="x_ShippedDate" value="<?= $Page->ShippedDate->EditValue ?>" placeholder="<?= HtmlEncode($Page->ShippedDate->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->ShippedDate->formatPattern()) ?>"<?= $Page->ShippedDate->editAttributes() ?> aria-describedby="x_ShippedDate_help">
<?= $Page->ShippedDate->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->ShippedDate->getErrorMessage() ?></div>
<?php if (!$Page->ShippedDate->ReadOnly && !$Page->ShippedDate->Disabled && !isset($Page->ShippedDate->EditAttrs["readonly"]) && !isset($Page->ShippedDate->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fordersadd", "datetimepicker"], function () {
    let format = "<?= DateFormat(0) ?>",
        options = {
            localization: {
                locale: ew.LANGUAGE_ID + "-u-nu-" + ew.getNumberingSystem(),
                hourCycle: format.match(/H/) ? "h24" : "h12",
                format,
                ...ew.language.phrase("datetimepicker")
            },
            display: {
                icons: {
                    previous: ew.IS_RTL ? "fa-solid fa-chevron-right" : "fa-solid fa-chevron-left",
                    next: ew.IS_RTL ? "fa-solid fa-chevron-left" : "fa-solid fa-chevron-right"
                },
                components: {
                    hours: !!format.match(/h/i),
                    minutes: !!format.match(/m/),
                    seconds: !!format.match(/s/i)
                },
                theme: ew.isDark() ? "dark" : "auto"
            }
        };
    ew.createDateTimePicker("fordersadd", "x_ShippedDate", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->ShipVia->Visible) { // ShipVia ?>
    <div id="r_ShipVia"<?= $Page->ShipVia->rowAttributes() ?>>
        <label id="elh_orders_ShipVia" for="x_ShipVia" class="<?= $Page->LeftColumnClass ?>"><?= $Page->ShipVia->caption() ?><?= $Page->ShipVia->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->ShipVia->cellAttributes() ?>>
<span id="el_orders_ShipVia">
<input type="<?= $Page->ShipVia->getInputTextType() ?>" name="x_ShipVia" id="x_ShipVia" data-table="orders" data-field="x_ShipVia" value="<?= $Page->ShipVia->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->ShipVia->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->ShipVia->formatPattern()) ?>"<?= $Page->ShipVia->editAttributes() ?> aria-describedby="x_ShipVia_help">
<?= $Page->ShipVia->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->ShipVia->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->Freight->Visible) { // Freight ?>
    <div id="r_Freight"<?= $Page->Freight->rowAttributes() ?>>
        <label id="elh_orders_Freight" for="x_Freight" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Freight->caption() ?><?= $Page->Freight->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Freight->cellAttributes() ?>>
<span id="el_orders_Freight">
<input type="<?= $Page->Freight->getInputTextType() ?>" name="x_Freight" id="x_Freight" data-table="orders" data-field="x_Freight" value="<?= $Page->Freight->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->Freight->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Freight->formatPattern()) ?>"<?= $Page->Freight->editAttributes() ?> aria-describedby="x_Freight_help">
<?= $Page->Freight->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->Freight->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->ShipName->Visible) { // ShipName ?>
    <div id="r_ShipName"<?= $Page->ShipName->rowAttributes() ?>>
        <label id="elh_orders_ShipName" for="x_ShipName" class="<?= $Page->LeftColumnClass ?>"><?= $Page->ShipName->caption() ?><?= $Page->ShipName->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->ShipName->cellAttributes() ?>>
<span id="el_orders_ShipName">
<input type="<?= $Page->ShipName->getInputTextType() ?>" name="x_ShipName" id="x_ShipName" data-table="orders" data-field="x_ShipName" value="<?= $Page->ShipName->EditValue ?>" size="30" maxlength="40" placeholder="<?= HtmlEncode($Page->ShipName->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->ShipName->formatPattern()) ?>"<?= $Page->ShipName->editAttributes() ?> aria-describedby="x_ShipName_help">
<?= $Page->ShipName->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->ShipName->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->ShipAddress->Visible) { // ShipAddress ?>
    <div id="r_ShipAddress"<?= $Page->ShipAddress->rowAttributes() ?>>
        <label id="elh_orders_ShipAddress" for="x_ShipAddress" class="<?= $Page->LeftColumnClass ?>"><?= $Page->ShipAddress->caption() ?><?= $Page->ShipAddress->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->ShipAddress->cellAttributes() ?>>
<span id="el_orders_ShipAddress">
<input type="<?= $Page->ShipAddress->getInputTextType() ?>" name="x_ShipAddress" id="x_ShipAddress" data-table="orders" data-field="x_ShipAddress" value="<?= $Page->ShipAddress->EditValue ?>" size="30" maxlength="60" placeholder="<?= HtmlEncode($Page->ShipAddress->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->ShipAddress->formatPattern()) ?>"<?= $Page->ShipAddress->editAttributes() ?> aria-describedby="x_ShipAddress_help">
<?= $Page->ShipAddress->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->ShipAddress->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->ShipCity->Visible) { // ShipCity ?>
    <div id="r_ShipCity"<?= $Page->ShipCity->rowAttributes() ?>>
        <label id="elh_orders_ShipCity" for="x_ShipCity" class="<?= $Page->LeftColumnClass ?>"><?= $Page->ShipCity->caption() ?><?= $Page->ShipCity->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->ShipCity->cellAttributes() ?>>
<span id="el_orders_ShipCity">
<input type="<?= $Page->ShipCity->getInputTextType() ?>" name="x_ShipCity" id="x_ShipCity" data-table="orders" data-field="x_ShipCity" value="<?= $Page->ShipCity->EditValue ?>" size="30" maxlength="15" placeholder="<?= HtmlEncode($Page->ShipCity->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->ShipCity->formatPattern()) ?>"<?= $Page->ShipCity->editAttributes() ?> aria-describedby="x_ShipCity_help">
<?= $Page->ShipCity->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->ShipCity->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->ShipRegion->Visible) { // ShipRegion ?>
    <div id="r_ShipRegion"<?= $Page->ShipRegion->rowAttributes() ?>>
        <label id="elh_orders_ShipRegion" for="x_ShipRegion" class="<?= $Page->LeftColumnClass ?>"><?= $Page->ShipRegion->caption() ?><?= $Page->ShipRegion->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->ShipRegion->cellAttributes() ?>>
<span id="el_orders_ShipRegion">
<input type="<?= $Page->ShipRegion->getInputTextType() ?>" name="x_ShipRegion" id="x_ShipRegion" data-table="orders" data-field="x_ShipRegion" value="<?= $Page->ShipRegion->EditValue ?>" size="30" maxlength="15" placeholder="<?= HtmlEncode($Page->ShipRegion->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->ShipRegion->formatPattern()) ?>"<?= $Page->ShipRegion->editAttributes() ?> aria-describedby="x_ShipRegion_help">
<?= $Page->ShipRegion->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->ShipRegion->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->ShipPostalCode->Visible) { // ShipPostalCode ?>
    <div id="r_ShipPostalCode"<?= $Page->ShipPostalCode->rowAttributes() ?>>
        <label id="elh_orders_ShipPostalCode" for="x_ShipPostalCode" class="<?= $Page->LeftColumnClass ?>"><?= $Page->ShipPostalCode->caption() ?><?= $Page->ShipPostalCode->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->ShipPostalCode->cellAttributes() ?>>
<span id="el_orders_ShipPostalCode">
<input type="<?= $Page->ShipPostalCode->getInputTextType() ?>" name="x_ShipPostalCode" id="x_ShipPostalCode" data-table="orders" data-field="x_ShipPostalCode" value="<?= $Page->ShipPostalCode->EditValue ?>" size="30" maxlength="10" placeholder="<?= HtmlEncode($Page->ShipPostalCode->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->ShipPostalCode->formatPattern()) ?>"<?= $Page->ShipPostalCode->editAttributes() ?> aria-describedby="x_ShipPostalCode_help">
<?= $Page->ShipPostalCode->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->ShipPostalCode->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->ShipCountry->Visible) { // ShipCountry ?>
    <div id="r_ShipCountry"<?= $Page->ShipCountry->rowAttributes() ?>>
        <label id="elh_orders_ShipCountry" for="x_ShipCountry" class="<?= $Page->LeftColumnClass ?>"><?= $Page->ShipCountry->caption() ?><?= $Page->ShipCountry->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->ShipCountry->cellAttributes() ?>>
<span id="el_orders_ShipCountry">
<input type="<?= $Page->ShipCountry->getInputTextType() ?>" name="x_ShipCountry" id="x_ShipCountry" data-table="orders" data-field="x_ShipCountry" value="<?= $Page->ShipCountry->EditValue ?>" size="30" maxlength="15" placeholder="<?= HtmlEncode($Page->ShipCountry->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->ShipCountry->formatPattern()) ?>"<?= $Page->ShipCountry->editAttributes() ?> aria-describedby="x_ShipCountry_help">
<?= $Page->ShipCountry->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->ShipCountry->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?php if ($Page->getCurrentDetailTable() != "") { ?>
<?php
    $Page->DetailPages->ValidKeys = explode(",", $Page->getCurrentDetailTable());
?>
<div class="ew-detail-pages"><!-- detail-pages -->
<div class="ew-nav<?= $Page->DetailPages->containerClasses() ?>" id="details_Page"><!-- tabs -->
    <ul class="<?= $Page->DetailPages->navClasses() ?>" role="tablist"><!-- .nav -->
<?php
    if (in_array("orderdetails", explode(",", $Page->getCurrentDetailTable())) && $orderdetails->DetailAdd) {
?>
        <li class="nav-item"><button class="<?= $Page->DetailPages->navLinkClasses("orderdetails") ?><?= $Page->DetailPages->activeClasses("orderdetails") ?>" data-bs-target="#tab_orderdetails" data-bs-toggle="tab" type="button" role="tab" aria-controls="tab_orderdetails" aria-selected="<?= JsonEncode($Page->DetailPages->isActive("orderdetails")) ?>"><?= $Language->tablePhrase("orderdetails", "TblCaption") ?></button></li>
<?php
    }
?>
<?php
    if (in_array("order_details_extended", explode(",", $Page->getCurrentDetailTable())) && $order_details_extended->DetailAdd) {
?>
        <li class="nav-item"><button class="<?= $Page->DetailPages->navLinkClasses("order_details_extended") ?><?= $Page->DetailPages->activeClasses("order_details_extended") ?>" data-bs-target="#tab_order_details_extended" data-bs-toggle="tab" type="button" role="tab" aria-controls="tab_order_details_extended" aria-selected="<?= JsonEncode($Page->DetailPages->isActive("order_details_extended")) ?>"><?= $Language->tablePhrase("order_details_extended", "TblCaption") ?></button></li>
<?php
    }
?>
    </ul><!-- /.nav -->
    <div class="<?= $Page->DetailPages->tabContentClasses() ?>"><!-- .tab-content -->
<?php
    if (in_array("orderdetails", explode(",", $Page->getCurrentDetailTable())) && $orderdetails->DetailAdd) {
?>
        <div class="<?= $Page->DetailPages->tabPaneClasses("orderdetails") ?><?= $Page->DetailPages->activeClasses("orderdetails") ?>" id="tab_orderdetails" role="tabpanel"><!-- page* -->
<?php include_once "OrderdetailsGrid.php" ?>
        </div><!-- /page* -->
<?php } ?>
<?php
    if (in_array("order_details_extended", explode(",", $Page->getCurrentDetailTable())) && $order_details_extended->DetailAdd) {
?>
        <div class="<?= $Page->DetailPages->tabPaneClasses("order_details_extended") ?><?= $Page->DetailPages->activeClasses("order_details_extended") ?>" id="tab_order_details_extended" role="tabpanel"><!-- page* -->
<?php include_once "OrderDetailsExtendedGrid.php" ?>
        </div><!-- /page* -->
<?php } ?>
    </div><!-- /.tab-content -->
</div><!-- /tabs -->
</div><!-- /detail-pages -->
<?php } ?>
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="fordersadd"><?= $Language->phrase("AddBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="fordersadd" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
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
    ew.addEventHandlers("orders");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
