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
        .setQueryBuilderLists({
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
<form name="forderssearch" id="forderssearch" class="<?= $Page->FormClassName ?>" action="<?= HtmlEncode(GetUrl("orderslist")) ?>" method="post" novalidate autocomplete="on">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="orders">
<input type="hidden" name="action" id="action" value="search">
<?php if ($Page->IsModal && $Page->UseAjaxActions) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<input type="hidden" name="rules" value="<?= HtmlEncode($Page->getSessionRules()) ?>">
<template id="tpx_orders_OrderID" class="orderssearch"><span id="el_orders_OrderID" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->OrderID->getInputTextType() ?>" name="x_OrderID" id="x_OrderID" data-table="orders" data-field="x_OrderID" value="<?= $Page->OrderID->EditValue ?>" placeholder="<?= HtmlEncode($Page->OrderID->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->OrderID->formatPattern()) ?>"<?= $Page->OrderID->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->OrderID->getErrorMessage(false) ?></div>
</span></template>
<template id="tpx_orders_CustomerID" class="orderssearch"><span id="el_orders_CustomerID" class="ew-search-field ew-search-field-single">
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
</span></template>
<template id="tpx_orders_EmployeeID" class="orderssearch"><span id="el_orders_EmployeeID" class="ew-search-field ew-search-field-single">
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
</span></template>
<template id="tpx_orders_OrderDate" class="orderssearch"><span id="el_orders_OrderDate" class="ew-search-field ew-search-field-single">
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
</span></template>
<template id="tpx_orders_RequiredDate" class="orderssearch"><span id="el_orders_RequiredDate" class="ew-search-field ew-search-field-single">
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
</span></template>
<template id="tpx_orders_ShippedDate" class="orderssearch"><span id="el_orders_ShippedDate" class="ew-search-field ew-search-field-single">
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
</span></template>
<template id="tpx_orders_ShipVia" class="orderssearch"><span id="el_orders_ShipVia" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->ShipVia->getInputTextType() ?>" name="x_ShipVia" id="x_ShipVia" data-table="orders" data-field="x_ShipVia" value="<?= $Page->ShipVia->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->ShipVia->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->ShipVia->formatPattern()) ?>"<?= $Page->ShipVia->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->ShipVia->getErrorMessage(false) ?></div>
</span></template>
<template id="tpx_orders_Freight" class="orderssearch"><span id="el_orders_Freight" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->Freight->getInputTextType() ?>" name="x_Freight" id="x_Freight" data-table="orders" data-field="x_Freight" value="<?= $Page->Freight->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->Freight->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Freight->formatPattern()) ?>"<?= $Page->Freight->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->Freight->getErrorMessage(false) ?></div>
</span></template>
<template id="tpx_orders_ShipName" class="orderssearch"><span id="el_orders_ShipName" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->ShipName->getInputTextType() ?>" name="x_ShipName" id="x_ShipName" data-table="orders" data-field="x_ShipName" value="<?= $Page->ShipName->EditValue ?>" size="30" maxlength="40" placeholder="<?= HtmlEncode($Page->ShipName->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->ShipName->formatPattern()) ?>"<?= $Page->ShipName->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->ShipName->getErrorMessage(false) ?></div>
</span></template>
<template id="tpx_orders_ShipAddress" class="orderssearch"><span id="el_orders_ShipAddress" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->ShipAddress->getInputTextType() ?>" name="x_ShipAddress" id="x_ShipAddress" data-table="orders" data-field="x_ShipAddress" value="<?= $Page->ShipAddress->EditValue ?>" size="30" maxlength="60" placeholder="<?= HtmlEncode($Page->ShipAddress->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->ShipAddress->formatPattern()) ?>"<?= $Page->ShipAddress->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->ShipAddress->getErrorMessage(false) ?></div>
</span></template>
<template id="tpx_orders_ShipCity" class="orderssearch"><span id="el_orders_ShipCity" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->ShipCity->getInputTextType() ?>" name="x_ShipCity" id="x_ShipCity" data-table="orders" data-field="x_ShipCity" value="<?= $Page->ShipCity->EditValue ?>" size="30" maxlength="15" placeholder="<?= HtmlEncode($Page->ShipCity->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->ShipCity->formatPattern()) ?>"<?= $Page->ShipCity->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->ShipCity->getErrorMessage(false) ?></div>
</span></template>
<template id="tpx_orders_ShipRegion" class="orderssearch"><span id="el_orders_ShipRegion" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->ShipRegion->getInputTextType() ?>" name="x_ShipRegion" id="x_ShipRegion" data-table="orders" data-field="x_ShipRegion" value="<?= $Page->ShipRegion->EditValue ?>" size="30" maxlength="15" placeholder="<?= HtmlEncode($Page->ShipRegion->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->ShipRegion->formatPattern()) ?>"<?= $Page->ShipRegion->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->ShipRegion->getErrorMessage(false) ?></div>
</span></template>
<template id="tpx_orders_ShipPostalCode" class="orderssearch"><span id="el_orders_ShipPostalCode" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->ShipPostalCode->getInputTextType() ?>" name="x_ShipPostalCode" id="x_ShipPostalCode" data-table="orders" data-field="x_ShipPostalCode" value="<?= $Page->ShipPostalCode->EditValue ?>" size="30" maxlength="10" placeholder="<?= HtmlEncode($Page->ShipPostalCode->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->ShipPostalCode->formatPattern()) ?>"<?= $Page->ShipPostalCode->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->ShipPostalCode->getErrorMessage(false) ?></div>
</span></template>
<template id="tpx_orders_ShipCountry" class="orderssearch"><span id="el_orders_ShipCountry" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->ShipCountry->getInputTextType() ?>" name="x_ShipCountry" id="x_ShipCountry" data-table="orders" data-field="x_ShipCountry" value="<?= $Page->ShipCountry->EditValue ?>" size="30" maxlength="15" placeholder="<?= HtmlEncode($Page->ShipCountry->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->ShipCountry->formatPattern()) ?>"<?= $Page->ShipCountry->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->ShipCountry->getErrorMessage(false) ?></div>
</span></template>
<div id="orders_query_builder" class="query-builder mb-3"></div>
<div class="btn-group mb-3 query-btn-group"></div>
<button type="button" id="btn-view-rules" class="btn btn-primary d-none disabled" title="<?= HtmlEncode($Language->phrase("View", true)) ?>"><i class="fa-solid fa-eye ew-icon"></i></button>
<button type="button" id="btn-clear-rules" class="btn btn-primary d-none disabled" title="<?= HtmlEncode($Language->phrase("Clear", true)) ?>"><i class="fa-solid fa-xmark ew-icon"></i></button>
<script>
// Filter builder
loadjs.ready(["wrapper", "head"], () => {
    let filters = [
            {
                id: "OrderID",
                type: "integer",
                label: currentTable.fields.OrderID.caption,
                operators: currentTable.fields.OrderID.clientSearchOperators,
                default_operator: "equal",
                input: ew.getQueryBuilderFilterInput(),
                validation: ew.getQueryBuilderFilterValidation(forderssearch.fields.OrderID.validators),
                data: {
                    format: currentTable.fields.OrderID.clientFormatPattern
                }
            },
            {
                id: "CustomerID",
                type: "string",
                label: currentTable.fields.CustomerID.caption,
                operators: currentTable.fields.CustomerID.clientSearchOperators,
                default_operator: "equal",
                input: ew.getQueryBuilderFilterInput(),
                valueSetter: ew.getQueryBuilderValueSetter(),
                validation: ew.getQueryBuilderFilterValidation(forderssearch.fields.CustomerID.validators),
                data: {
                    format: currentTable.fields.CustomerID.clientFormatPattern
                }
            },
            {
                id: "EmployeeID",
                type: "integer",
                label: currentTable.fields.EmployeeID.caption,
                operators: currentTable.fields.EmployeeID.clientSearchOperators,
                default_operator: "equal",
                input: ew.getQueryBuilderFilterInput(),
                valueSetter: ew.getQueryBuilderValueSetter(),
                validation: ew.getQueryBuilderFilterValidation(forderssearch.fields.EmployeeID.validators),
                data: {
                    format: currentTable.fields.EmployeeID.clientFormatPattern
                }
            },
            {
                id: "OrderDate",
                type: "datetime",
                label: currentTable.fields.OrderDate.caption,
                operators: currentTable.fields.OrderDate.clientSearchOperators,
                input: ew.getQueryBuilderFilterInput(),
                value_separator: ew.IN_OPERATOR_VALUE_SEPARATOR,
                validation: ew.getQueryBuilderFilterValidation(forderssearch.fields.OrderDate.validators),
                data: {
                    format: currentTable.fields.OrderDate.clientFormatPattern
                }
            },
            {
                id: "RequiredDate",
                type: "datetime",
                label: currentTable.fields.RequiredDate.caption,
                operators: currentTable.fields.RequiredDate.clientSearchOperators,
                default_operator: "equal",
                input: ew.getQueryBuilderFilterInput(),
                value_separator: ew.IN_OPERATOR_VALUE_SEPARATOR,
                validation: ew.getQueryBuilderFilterValidation(forderssearch.fields.RequiredDate.validators),
                data: {
                    format: currentTable.fields.RequiredDate.clientFormatPattern
                }
            },
            {
                id: "ShippedDate",
                type: "datetime",
                label: currentTable.fields.ShippedDate.caption,
                operators: currentTable.fields.ShippedDate.clientSearchOperators,
                default_operator: "equal",
                input: ew.getQueryBuilderFilterInput(),
                value_separator: ew.IN_OPERATOR_VALUE_SEPARATOR,
                validation: ew.getQueryBuilderFilterValidation(forderssearch.fields.ShippedDate.validators),
                data: {
                    format: currentTable.fields.ShippedDate.clientFormatPattern
                }
            },
            {
                id: "ShipVia",
                type: "integer",
                label: currentTable.fields.ShipVia.caption,
                operators: currentTable.fields.ShipVia.clientSearchOperators,
                default_operator: "equal",
                input: ew.getQueryBuilderFilterInput(),
                value_separator: ew.IN_OPERATOR_VALUE_SEPARATOR,
                validation: ew.getQueryBuilderFilterValidation(forderssearch.fields.ShipVia.validators),
                data: {
                    format: currentTable.fields.ShipVia.clientFormatPattern
                }
            },
            {
                id: "Freight",
                type: "double",
                label: currentTable.fields.Freight.caption,
                operators: currentTable.fields.Freight.clientSearchOperators,
                default_operator: "equal",
                input: ew.getQueryBuilderFilterInput(),
                value_separator: ew.IN_OPERATOR_VALUE_SEPARATOR,
                validation: ew.getQueryBuilderFilterValidation(forderssearch.fields.Freight.validators),
                data: {
                    format: currentTable.fields.Freight.clientFormatPattern
                }
            },
            {
                id: "ShipName",
                type: "string",
                label: currentTable.fields.ShipName.caption,
                operators: currentTable.fields.ShipName.clientSearchOperators,
                default_operator: "contains",
                input: ew.getQueryBuilderFilterInput(),
                value_separator: ew.IN_OPERATOR_VALUE_SEPARATOR,
                validation: ew.getQueryBuilderFilterValidation(forderssearch.fields.ShipName.validators),
                data: {
                    format: currentTable.fields.ShipName.clientFormatPattern
                }
            },
            {
                id: "ShipAddress",
                type: "string",
                label: currentTable.fields.ShipAddress.caption,
                operators: currentTable.fields.ShipAddress.clientSearchOperators,
                default_operator: "contains",
                input: ew.getQueryBuilderFilterInput(),
                value_separator: ew.IN_OPERATOR_VALUE_SEPARATOR,
                validation: ew.getQueryBuilderFilterValidation(forderssearch.fields.ShipAddress.validators),
                data: {
                    format: currentTable.fields.ShipAddress.clientFormatPattern
                }
            },
            {
                id: "ShipCity",
                type: "string",
                label: currentTable.fields.ShipCity.caption,
                operators: currentTable.fields.ShipCity.clientSearchOperators,
                default_operator: "contains",
                input: ew.getQueryBuilderFilterInput(),
                value_separator: ew.IN_OPERATOR_VALUE_SEPARATOR,
                validation: ew.getQueryBuilderFilterValidation(forderssearch.fields.ShipCity.validators),
                data: {
                    format: currentTable.fields.ShipCity.clientFormatPattern
                }
            },
            {
                id: "ShipRegion",
                type: "string",
                label: currentTable.fields.ShipRegion.caption,
                operators: currentTable.fields.ShipRegion.clientSearchOperators,
                default_operator: "contains",
                input: ew.getQueryBuilderFilterInput(),
                value_separator: ew.IN_OPERATOR_VALUE_SEPARATOR,
                validation: ew.getQueryBuilderFilterValidation(forderssearch.fields.ShipRegion.validators),
                data: {
                    format: currentTable.fields.ShipRegion.clientFormatPattern
                }
            },
            {
                id: "ShipPostalCode",
                type: "string",
                label: currentTable.fields.ShipPostalCode.caption,
                operators: currentTable.fields.ShipPostalCode.clientSearchOperators,
                default_operator: "contains",
                input: ew.getQueryBuilderFilterInput(),
                value_separator: ew.IN_OPERATOR_VALUE_SEPARATOR,
                validation: ew.getQueryBuilderFilterValidation(forderssearch.fields.ShipPostalCode.validators),
                data: {
                    format: currentTable.fields.ShipPostalCode.clientFormatPattern
                }
            },
            {
                id: "ShipCountry",
                type: "string",
                label: currentTable.fields.ShipCountry.caption,
                operators: currentTable.fields.ShipCountry.clientSearchOperators,
                default_operator: "contains",
                input: ew.getQueryBuilderFilterInput(),
                value_separator: ew.IN_OPERATOR_VALUE_SEPARATOR,
                validation: ew.getQueryBuilderFilterValidation(forderssearch.fields.ShipCountry.validators),
                data: {
                    format: currentTable.fields.ShipCountry.clientFormatPattern
                }
            },
        ],
        $ = jQuery,
        $qb = $("#orders_query_builder"),
        args = {},
        rules = ew.parseJson($("#forderssearch input[name=rules]").val()),
        queryBuilderOptions = Object.assign({}, ew.queryBuilderOptions),
        allowViewRules = queryBuilderOptions.allowViewRules,
        allowClearRules = queryBuilderOptions.allowClearRules,
        hasRules = group => Array.isArray(group?.rules) && group.rules.length > 0,
        getRules = () => $qb.queryBuilder("getRules", { skip_empty: true }),
        getSql = () => $qb.queryBuilder("getSQL", false, false, rules)?.sql;
    delete queryBuilderOptions.allowViewRules;
    delete queryBuilderOptions.allowClearRules;
    args.options = ew.deepAssign({
        plugins: Object.assign({}, ew.queryBuilderPlugins),
        lang: ew.language.phrase("querybuilderjs"),
        select_placeholder: ew.language.phrase("PleaseSelect"),
        inputs_separator: `<div class="d-inline-flex ms-2 me-2">${ew.language.phrase("AND")}</div>`, // For "between"
        filters,
        rules
    }, queryBuilderOptions);
    $qb.trigger("querybuilder", [args]);
    $qb.queryBuilder(args.options).on("rulesChanged.queryBuilder", () => {
        let rules = getRules();
        !ew.DEBUG || console.log(rules, getSql());
        $("#btn-reset, #btn-action, #btn-clear-rules, #btn-view-rules").toggleClass("disabled", !rules);
    }).on("afterCreateRuleInput.queryBuilder", function(e, rule) {
        let select = rule.$el.find(".rule-value-container").find("selection-list, select")[0];
        if (select) { // Selection list
            let id = select.dataset.field.replace("^x_", ""),
                form = ew.forms.get(select);
            form.updateList(select, undefined, undefined, true); // Update immediately
        }
    });
    $("#forderssearch").on("beforesubmit", function () {
        this.rules.value = JSON.stringify(getRules());
    });
    $("#btn-reset").toggleClass("d-none", false).on("click", () => {
        hasRules(rules) ? $qb.queryBuilder("setRules", rules) : $qb.queryBuilder("reset");
        return false;
    });
    $("#btn-action").toggleClass("d-none", false);
    if (allowClearRules) {
        $("#btn-clear-rules").appendTo(".query-btn-group").removeClass("d-none").on("click", () => $qb.queryBuilder("reset"));
    }
    if (allowViewRules) {
        $("#btn-view-rules").appendTo(".query-btn-group").removeClass("d-none").on("click", () => {
            let rules = getRules();
            if (hasRules(rules)) {
                let sql = getSql();
                ew.alert(sql ? '<pre class="text-start fs-6">' + sql + '</pre>' : '', "dark");
                !ew.DEBUG || console.log(rules, sql);
            } else {
                ew.alert(ew.language.phrase("EmptyLabel"));
            }
        });
    }
    if (hasRules(rules)) { // Enable buttons if rules exist initially
        $("#btn-reset, #btn-action, #btn-clear-rules, #btn-view-rules").removeClass("disabled");
    }
});
</script>
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
        <button class="btn btn-primary ew-btn d-none disabled" name="btn-action" id="btn-action" type="submit" form="forderssearch" formaction="<?= HtmlEncode(GetUrl("orderslist")) ?>" data-ajax="<?= $Page->UseAjaxActions ? "true" : "false" ?>"><?= $Language->phrase("Search") ?></button>
        <?php if ($Page->IsModal) { ?>
        <button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="forderssearch"><?= $Language->phrase("Cancel") ?></button>
        <?php } else { ?>
        <button class="btn btn-default ew-btn d-none disabled" name="btn-reset" id="btn-reset" type="button" form="forderssearch" data-ew-action="reload"><?= $Language->phrase("Reset") ?></button>
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
