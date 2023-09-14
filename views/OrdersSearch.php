<?php

namespace PHPMaker2023\demo2023;

// Page object
$OrdersSearch = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { orders: currentTable } });
var currentPageID = ew.PAGE_ID = "search";
var currentForm;
var forderssearch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery,
        fields = currentTable.fields;

    // Form object for search
    let form = new ew.FormBuilder()
        .setId("forderssearch")
        .setPageId("search")
<?php if ($Page->IsModal && $Page->UseAjaxActions) { ?>
        .setSubmitWithFetch(true)
<?php } ?>

        // Add fields
        .addFields([
            ["OrderID", [ew.Validators.integer], fields.OrderID.isInvalid],
            ["CustomerID", [], fields.CustomerID.isInvalid],
            ["EmployeeID", [], fields.EmployeeID.isInvalid],
            ["OrderDate", [ew.Validators.datetime(fields.OrderDate.clientFormatPattern)], fields.OrderDate.isInvalid],
            ["y_OrderDate", [ew.Validators.between], false],
            ["RequiredDate", [ew.Validators.datetime(fields.RequiredDate.clientFormatPattern)], fields.RequiredDate.isInvalid],
            ["ShippedDate", [ew.Validators.datetime(fields.ShippedDate.clientFormatPattern)], fields.ShippedDate.isInvalid],
            ["ShipVia", [ew.Validators.integer], fields.ShipVia.isInvalid],
            ["Freight", [ew.Validators.float], fields.Freight.isInvalid],
            ["ShipName", [], fields.ShipName.isInvalid],
            ["ShipAddress", [], fields.ShipAddress.isInvalid],
            ["ShipCity", [], fields.ShipCity.isInvalid],
            ["ShipRegion", [], fields.ShipRegion.isInvalid],
            ["ShipPostalCode", [], fields.ShipPostalCode.isInvalid],
            ["ShipCountry", [], fields.ShipCountry.isInvalid]
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
            "CustomerID": <?= $Page->CustomerID->toClientList($Page) ?>,
            "EmployeeID": <?= $Page->EmployeeID->toClientList($Page) ?>,
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
<form name="forderssearch" id="forderssearch" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="on">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="orders">
<input type="hidden" name="action" id="action" value="search">
<?php if ($Page->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div class="ew-search-div"><!-- page* -->
<?php if ($Page->OrderID->Visible) { // OrderID ?>
    <div id="r_OrderID" class="row"<?= $Page->OrderID->rowAttributes() ?>>
        <label for="x_OrderID" class="<?= $Page->LeftColumnClass ?>"><span id="elh_orders_OrderID"><?= $Page->OrderID->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_OrderID" id="z_OrderID" value="=">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->OrderID->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_orders_OrderID" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->OrderID->getInputTextType() ?>" name="x_OrderID" id="x_OrderID" data-table="orders" data-field="x_OrderID" value="<?= $Page->OrderID->EditValue ?>" placeholder="<?= HtmlEncode($Page->OrderID->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->OrderID->formatPattern()) ?>"<?= $Page->OrderID->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->OrderID->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->CustomerID->Visible) { // CustomerID ?>
    <div id="r_CustomerID" class="row"<?= $Page->CustomerID->rowAttributes() ?>>
        <label for="x_CustomerID" class="<?= $Page->LeftColumnClass ?>"><span id="elh_orders_CustomerID"><?= $Page->CustomerID->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_CustomerID" id="z_CustomerID" value="=">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->CustomerID->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_orders_CustomerID" class="ew-search-field ew-search-field-single">
    <select
        id="x_CustomerID"
        name="x_CustomerID"
        class="form-control ew-select<?= $Page->CustomerID->isInvalidClass() ?>"
        data-select2-id="forderssearch_x_CustomerID"
        data-table="orders"
        data-field="x_CustomerID"
        data-caption="<?= HtmlEncode(RemoveHtml($Page->CustomerID->caption())) ?>"
        data-modal-lookup="true"
        data-value-separator="<?= $Page->CustomerID->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->CustomerID->getPlaceHolder()) ?>"
        <?= $Page->CustomerID->editAttributes() ?>>
        <?= $Page->CustomerID->selectOptionListHtml("x_CustomerID") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->CustomerID->getErrorMessage(false) ?></div>
<?= $Page->CustomerID->Lookup->getParamTag($Page, "p_x_CustomerID") ?>
<script>
loadjs.ready("forderssearch", function() {
    var options = { name: "x_CustomerID", selectId: "forderssearch_x_CustomerID" };
    if (forderssearch.lists.CustomerID?.lookupOptions.length) {
        options.data = { id: "x_CustomerID", form: "forderssearch" };
    } else {
        options.ajax = { id: "x_CustomerID", form: "forderssearch", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options = Object.assign({}, ew.modalLookupOptions, options, ew.vars.tables.orders.fields.CustomerID.modalLookupOptions);
    ew.createModalLookup(options);
});
</script>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->EmployeeID->Visible) { // EmployeeID ?>
    <div id="r_EmployeeID" class="row"<?= $Page->EmployeeID->rowAttributes() ?>>
        <label for="x_EmployeeID" class="<?= $Page->LeftColumnClass ?>"><span id="elh_orders_EmployeeID"><?= $Page->EmployeeID->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_EmployeeID" id="z_EmployeeID" value="=">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->EmployeeID->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_orders_EmployeeID" class="ew-search-field ew-search-field-single">
    <select
        id="x_EmployeeID"
        name="x_EmployeeID"
        class="form-select ew-select<?= $Page->EmployeeID->isInvalidClass() ?>"
        <?php if (!$Page->EmployeeID->IsNativeSelect) { ?>
        data-select2-id="forderssearch_x_EmployeeID"
        <?php } ?>
        data-table="orders"
        data-field="x_EmployeeID"
        data-value-separator="<?= $Page->EmployeeID->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->EmployeeID->getPlaceHolder()) ?>"
        <?= $Page->EmployeeID->editAttributes() ?>>
        <?= $Page->EmployeeID->selectOptionListHtml("x_EmployeeID") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->EmployeeID->getErrorMessage(false) ?></div>
<?= $Page->EmployeeID->Lookup->getParamTag($Page, "p_x_EmployeeID") ?>
<?php if (!$Page->EmployeeID->IsNativeSelect) { ?>
<script>
loadjs.ready("forderssearch", function() {
    var options = { name: "x_EmployeeID", selectId: "forderssearch_x_EmployeeID" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (forderssearch.lists.EmployeeID?.lookupOptions.length) {
        options.data = { id: "x_EmployeeID", form: "forderssearch" };
    } else {
        options.ajax = { id: "x_EmployeeID", form: "forderssearch", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.orders.fields.EmployeeID.selectOptions);
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
<?php if ($Page->OrderDate->Visible) { // OrderDate ?>
    <div id="r_OrderDate" class="row"<?= $Page->OrderDate->rowAttributes() ?>>
        <label for="x_OrderDate" class="<?= $Page->LeftColumnClass ?>"><span id="elh_orders_OrderDate"><?= $Page->OrderDate->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("BETWEEN") ?>
<input type="hidden" name="z_OrderDate" id="z_OrderDate" value="BETWEEN">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->OrderDate->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_orders_OrderDate" class="ew-search-field">
<input type="<?= $Page->OrderDate->getInputTextType() ?>" name="x_OrderDate" id="x_OrderDate" data-table="orders" data-field="x_OrderDate" value="<?= $Page->OrderDate->EditValue ?>" placeholder="<?= HtmlEncode($Page->OrderDate->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->OrderDate->formatPattern()) ?>"<?= $Page->OrderDate->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->OrderDate->getErrorMessage(false) ?></div>
<?php if (!$Page->OrderDate->ReadOnly && !$Page->OrderDate->Disabled && !isset($Page->OrderDate->EditAttrs["readonly"]) && !isset($Page->OrderDate->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["forderssearch", "datetimepicker"], function () {
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
    ew.createDateTimePicker("forderssearch", "x_OrderDate", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</span>
                    <span class="ew-search-and"><label><?= $Language->phrase("AND") ?></label></span>
                    <span id="el2_orders_OrderDate" class="ew-search-field2">
<input type="<?= $Page->OrderDate->getInputTextType() ?>" name="y_OrderDate" id="y_OrderDate" data-table="orders" data-field="x_OrderDate" value="<?= $Page->OrderDate->EditValue2 ?>" placeholder="<?= HtmlEncode($Page->OrderDate->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->OrderDate->formatPattern()) ?>"<?= $Page->OrderDate->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->OrderDate->getErrorMessage(false) ?></div>
<?php if (!$Page->OrderDate->ReadOnly && !$Page->OrderDate->Disabled && !isset($Page->OrderDate->EditAttrs["readonly"]) && !isset($Page->OrderDate->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["forderssearch", "datetimepicker"], function () {
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
    ew.createDateTimePicker("forderssearch", "y_OrderDate", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->RequiredDate->Visible) { // RequiredDate ?>
    <div id="r_RequiredDate" class="row"<?= $Page->RequiredDate->rowAttributes() ?>>
        <label for="x_RequiredDate" class="<?= $Page->LeftColumnClass ?>"><span id="elh_orders_RequiredDate"><?= $Page->RequiredDate->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_RequiredDate" id="z_RequiredDate" value="=">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->RequiredDate->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_orders_RequiredDate" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->RequiredDate->getInputTextType() ?>" name="x_RequiredDate" id="x_RequiredDate" data-table="orders" data-field="x_RequiredDate" value="<?= $Page->RequiredDate->EditValue ?>" placeholder="<?= HtmlEncode($Page->RequiredDate->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->RequiredDate->formatPattern()) ?>"<?= $Page->RequiredDate->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->RequiredDate->getErrorMessage(false) ?></div>
<?php if (!$Page->RequiredDate->ReadOnly && !$Page->RequiredDate->Disabled && !isset($Page->RequiredDate->EditAttrs["readonly"]) && !isset($Page->RequiredDate->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["forderssearch", "datetimepicker"], function () {
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
    ew.createDateTimePicker("forderssearch", "x_RequiredDate", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->ShippedDate->Visible) { // ShippedDate ?>
    <div id="r_ShippedDate" class="row"<?= $Page->ShippedDate->rowAttributes() ?>>
        <label for="x_ShippedDate" class="<?= $Page->LeftColumnClass ?>"><span id="elh_orders_ShippedDate"><?= $Page->ShippedDate->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_ShippedDate" id="z_ShippedDate" value="=">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->ShippedDate->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_orders_ShippedDate" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->ShippedDate->getInputTextType() ?>" name="x_ShippedDate" id="x_ShippedDate" data-table="orders" data-field="x_ShippedDate" value="<?= $Page->ShippedDate->EditValue ?>" placeholder="<?= HtmlEncode($Page->ShippedDate->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->ShippedDate->formatPattern()) ?>"<?= $Page->ShippedDate->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->ShippedDate->getErrorMessage(false) ?></div>
<?php if (!$Page->ShippedDate->ReadOnly && !$Page->ShippedDate->Disabled && !isset($Page->ShippedDate->EditAttrs["readonly"]) && !isset($Page->ShippedDate->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["forderssearch", "datetimepicker"], function () {
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
    ew.createDateTimePicker("forderssearch", "x_ShippedDate", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->ShipVia->Visible) { // ShipVia ?>
    <div id="r_ShipVia" class="row"<?= $Page->ShipVia->rowAttributes() ?>>
        <label for="x_ShipVia" class="<?= $Page->LeftColumnClass ?>"><span id="elh_orders_ShipVia"><?= $Page->ShipVia->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_ShipVia" id="z_ShipVia" value="=">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->ShipVia->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_orders_ShipVia" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->ShipVia->getInputTextType() ?>" name="x_ShipVia" id="x_ShipVia" data-table="orders" data-field="x_ShipVia" value="<?= $Page->ShipVia->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->ShipVia->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->ShipVia->formatPattern()) ?>"<?= $Page->ShipVia->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->ShipVia->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->Freight->Visible) { // Freight ?>
    <div id="r_Freight" class="row"<?= $Page->Freight->rowAttributes() ?>>
        <label for="x_Freight" class="<?= $Page->LeftColumnClass ?>"><span id="elh_orders_Freight"><?= $Page->Freight->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_Freight" id="z_Freight" value="=">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->Freight->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_orders_Freight" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->Freight->getInputTextType() ?>" name="x_Freight" id="x_Freight" data-table="orders" data-field="x_Freight" value="<?= $Page->Freight->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->Freight->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Freight->formatPattern()) ?>"<?= $Page->Freight->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->Freight->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->ShipName->Visible) { // ShipName ?>
    <div id="r_ShipName" class="row"<?= $Page->ShipName->rowAttributes() ?>>
        <label for="x_ShipName" class="<?= $Page->LeftColumnClass ?>"><span id="elh_orders_ShipName"><?= $Page->ShipName->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_ShipName" id="z_ShipName" value="LIKE">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->ShipName->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_orders_ShipName" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->ShipName->getInputTextType() ?>" name="x_ShipName" id="x_ShipName" data-table="orders" data-field="x_ShipName" value="<?= $Page->ShipName->EditValue ?>" size="30" maxlength="40" placeholder="<?= HtmlEncode($Page->ShipName->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->ShipName->formatPattern()) ?>"<?= $Page->ShipName->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->ShipName->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->ShipAddress->Visible) { // ShipAddress ?>
    <div id="r_ShipAddress" class="row"<?= $Page->ShipAddress->rowAttributes() ?>>
        <label for="x_ShipAddress" class="<?= $Page->LeftColumnClass ?>"><span id="elh_orders_ShipAddress"><?= $Page->ShipAddress->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_ShipAddress" id="z_ShipAddress" value="LIKE">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->ShipAddress->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_orders_ShipAddress" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->ShipAddress->getInputTextType() ?>" name="x_ShipAddress" id="x_ShipAddress" data-table="orders" data-field="x_ShipAddress" value="<?= $Page->ShipAddress->EditValue ?>" size="30" maxlength="60" placeholder="<?= HtmlEncode($Page->ShipAddress->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->ShipAddress->formatPattern()) ?>"<?= $Page->ShipAddress->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->ShipAddress->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->ShipCity->Visible) { // ShipCity ?>
    <div id="r_ShipCity" class="row"<?= $Page->ShipCity->rowAttributes() ?>>
        <label for="x_ShipCity" class="<?= $Page->LeftColumnClass ?>"><span id="elh_orders_ShipCity"><?= $Page->ShipCity->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_ShipCity" id="z_ShipCity" value="LIKE">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->ShipCity->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_orders_ShipCity" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->ShipCity->getInputTextType() ?>" name="x_ShipCity" id="x_ShipCity" data-table="orders" data-field="x_ShipCity" value="<?= $Page->ShipCity->EditValue ?>" size="30" maxlength="15" placeholder="<?= HtmlEncode($Page->ShipCity->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->ShipCity->formatPattern()) ?>"<?= $Page->ShipCity->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->ShipCity->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->ShipRegion->Visible) { // ShipRegion ?>
    <div id="r_ShipRegion" class="row"<?= $Page->ShipRegion->rowAttributes() ?>>
        <label for="x_ShipRegion" class="<?= $Page->LeftColumnClass ?>"><span id="elh_orders_ShipRegion"><?= $Page->ShipRegion->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_ShipRegion" id="z_ShipRegion" value="LIKE">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->ShipRegion->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_orders_ShipRegion" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->ShipRegion->getInputTextType() ?>" name="x_ShipRegion" id="x_ShipRegion" data-table="orders" data-field="x_ShipRegion" value="<?= $Page->ShipRegion->EditValue ?>" size="30" maxlength="15" placeholder="<?= HtmlEncode($Page->ShipRegion->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->ShipRegion->formatPattern()) ?>"<?= $Page->ShipRegion->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->ShipRegion->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->ShipPostalCode->Visible) { // ShipPostalCode ?>
    <div id="r_ShipPostalCode" class="row"<?= $Page->ShipPostalCode->rowAttributes() ?>>
        <label for="x_ShipPostalCode" class="<?= $Page->LeftColumnClass ?>"><span id="elh_orders_ShipPostalCode"><?= $Page->ShipPostalCode->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_ShipPostalCode" id="z_ShipPostalCode" value="LIKE">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->ShipPostalCode->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_orders_ShipPostalCode" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->ShipPostalCode->getInputTextType() ?>" name="x_ShipPostalCode" id="x_ShipPostalCode" data-table="orders" data-field="x_ShipPostalCode" value="<?= $Page->ShipPostalCode->EditValue ?>" size="30" maxlength="10" placeholder="<?= HtmlEncode($Page->ShipPostalCode->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->ShipPostalCode->formatPattern()) ?>"<?= $Page->ShipPostalCode->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->ShipPostalCode->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->ShipCountry->Visible) { // ShipCountry ?>
    <div id="r_ShipCountry" class="row"<?= $Page->ShipCountry->rowAttributes() ?>>
        <label for="x_ShipCountry" class="<?= $Page->LeftColumnClass ?>"><span id="elh_orders_ShipCountry"><?= $Page->ShipCountry->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_ShipCountry" id="z_ShipCountry" value="LIKE">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->ShipCountry->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_orders_ShipCountry" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->ShipCountry->getInputTextType() ?>" name="x_ShipCountry" id="x_ShipCountry" data-table="orders" data-field="x_ShipCountry" value="<?= $Page->ShipCountry->EditValue ?>" size="30" maxlength="15" placeholder="<?= HtmlEncode($Page->ShipCountry->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->ShipCountry->formatPattern()) ?>"<?= $Page->ShipCountry->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->ShipCountry->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
        <button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="forderssearch"><?= $Language->phrase("Search") ?></button>
        <?php if ($Page->IsModal) { ?>
        <button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="forderssearch"><?= $Language->phrase("Cancel") ?></button>
        <?php } else { ?>
        <button class="btn btn-default ew-btn" name="btn-reset" id="btn-reset" type="button" form="forderssearch" data-ew-action="reload"><?= $Language->phrase("Reset") ?></button>
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
