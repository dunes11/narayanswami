<?php

namespace PHPMaker2023\demo2023;

// Page object
$CarsSearch = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { cars: currentTable } });
var currentPageID = ew.PAGE_ID = "search";
var currentForm;
var fcarssearch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery,
        fields = currentTable.fields;

    // Form object for search
    let form = new ew.FormBuilder()
        .setId("fcarssearch")
        .setPageId("search")
<?php if ($Page->IsModal && $Page->UseAjaxActions) { ?>
        .setSubmitWithFetch(true)
<?php } ?>

        // Add fields
        .addFields([
            ["ID", [ew.Validators.integer], fields.ID.isInvalid],
            ["Trademark", [], fields.Trademark.isInvalid],
            ["Model", [], fields.Model.isInvalid],
            ["HP", [], fields.HP.isInvalid],
            ["Cylinders", [ew.Validators.integer], fields.Cylinders.isInvalid],
            ["Description", [], fields.Description.isInvalid],
            ["Price", [ew.Validators.float], fields.Price.isInvalid],
            ["Doors", [ew.Validators.integer], fields.Doors.isInvalid],
            ["Torque", [], fields.Torque.isInvalid]
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
            "Trademark": <?= $Page->Trademark->toClientList($Page) ?>,
            "Model": <?= $Page->Model->toClientList($Page) ?>,
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
<form name="fcarssearch" id="fcarssearch" class="<?= $Page->FormClassName ?>" action="<?= HtmlEncode(GetUrl("carslist")) ?>" method="post" novalidate autocomplete="on">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="cars">
<input type="hidden" name="action" id="action" value="search">
<?php if ($Page->IsModal && $Page->UseAjaxActions) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<input type="hidden" name="rules" value="<?= HtmlEncode($Page->getSessionRules()) ?>">
<template id="tpx_cars_ID" class="carssearch"><span id="el_cars_ID" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->ID->getInputTextType() ?>" name="x_ID" id="x_ID" data-table="cars" data-field="x_ID" value="<?= $Page->ID->EditValue ?>" placeholder="<?= HtmlEncode($Page->ID->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->ID->formatPattern()) ?>"<?= $Page->ID->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->ID->getErrorMessage(false) ?></div>
</span></template>
<template id="tpx_cars_Trademark" class="carssearch"><span id="el_cars_Trademark" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->Trademark->getInputTextType() ?>" name="x_Trademark" id="x_Trademark" data-table="cars" data-field="x_Trademark" value="<?= $Page->Trademark->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->Trademark->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Trademark->formatPattern()) ?>"<?= $Page->Trademark->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->Trademark->getErrorMessage(false) ?></div>
<?= $Page->Trademark->Lookup->getParamTag($Page, "p_x_Trademark") ?>
</span></template>
<template id="tpx_cars_Model" class="carssearch"><span id="el_cars_Model" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->Model->getInputTextType() ?>" name="x_Model" id="x_Model" data-table="cars" data-field="x_Model" value="<?= $Page->Model->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->Model->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Model->formatPattern()) ?>"<?= $Page->Model->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->Model->getErrorMessage(false) ?></div>
<?= $Page->Model->Lookup->getParamTag($Page, "p_x_Model") ?>
</span></template>
<template id="tpx_cars_HP" class="carssearch"><span id="el_cars_HP" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->HP->getInputTextType() ?>" name="x_HP" id="x_HP" data-table="cars" data-field="x_HP" value="<?= $Page->HP->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->HP->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->HP->formatPattern()) ?>"<?= $Page->HP->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->HP->getErrorMessage(false) ?></div>
</span></template>
<template id="tpx_cars_Cylinders" class="carssearch"><span id="el_cars_Cylinders" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->Cylinders->getInputTextType() ?>" name="x_Cylinders" id="x_Cylinders" data-table="cars" data-field="x_Cylinders" value="<?= $Page->Cylinders->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->Cylinders->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Cylinders->formatPattern()) ?>"<?= $Page->Cylinders->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->Cylinders->getErrorMessage(false) ?></div>
</span></template>
<template id="tpx_cars_Description" class="carssearch"><span id="el_cars_Description" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->Description->getInputTextType() ?>" name="x_Description" id="x_Description" data-table="cars" data-field="x_Description" value="<?= $Page->Description->EditValue ?>" size="35" placeholder="<?= HtmlEncode($Page->Description->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Description->formatPattern()) ?>"<?= $Page->Description->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->Description->getErrorMessage(false) ?></div>
</span></template>
<template id="tpx_cars_Price" class="carssearch"><span id="el_cars_Price" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->Price->getInputTextType() ?>" name="x_Price" id="x_Price" data-table="cars" data-field="x_Price" value="<?= $Page->Price->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->Price->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Price->formatPattern()) ?>"<?= $Page->Price->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->Price->getErrorMessage(false) ?></div>
</span></template>
<template id="tpx_cars_Doors" class="carssearch"><span id="el_cars_Doors" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->Doors->getInputTextType() ?>" name="x_Doors" id="x_Doors" data-table="cars" data-field="x_Doors" value="<?= $Page->Doors->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->Doors->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Doors->formatPattern()) ?>"<?= $Page->Doors->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->Doors->getErrorMessage(false) ?></div>
</span></template>
<template id="tpx_cars_Torque" class="carssearch"><span id="el_cars_Torque" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->Torque->getInputTextType() ?>" name="x_Torque" id="x_Torque" data-table="cars" data-field="x_Torque" value="<?= $Page->Torque->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->Torque->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Torque->formatPattern()) ?>"<?= $Page->Torque->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->Torque->getErrorMessage(false) ?></div>
</span></template>
<div id="cars_query_builder" class="query-builder mb-3"></div>
<div class="btn-group mb-3 query-btn-group"></div>
<button type="button" id="btn-view-rules" class="btn btn-primary d-none disabled" title="<?= HtmlEncode($Language->phrase("View", true)) ?>"><i class="fa-solid fa-eye ew-icon"></i></button>
<button type="button" id="btn-clear-rules" class="btn btn-primary d-none disabled" title="<?= HtmlEncode($Language->phrase("Clear", true)) ?>"><i class="fa-solid fa-xmark ew-icon"></i></button>
<script>
// Filter builder
loadjs.ready(["wrapper", "head"], () => {
    let filters = [
            {
                id: "ID",
                type: "integer",
                label: currentTable.fields.ID.caption,
                operators: currentTable.fields.ID.clientSearchOperators,
                default_operator: "equal",
                input: ew.getQueryBuilderFilterInput(),
                validation: ew.getQueryBuilderFilterValidation(fcarssearch.fields.ID.validators),
                data: {
                    format: currentTable.fields.ID.clientFormatPattern
                }
            },
            {
                id: "Trademark",
                type: "string",
                label: currentTable.fields.Trademark.caption,
                operators: currentTable.fields.Trademark.clientSearchOperators,
                default_operator: "contains",
                input: ew.getQueryBuilderFilterInput(),
                valueSetter: ew.getQueryBuilderValueSetter(),
                validation: ew.getQueryBuilderFilterValidation(fcarssearch.fields.Trademark.validators),
                data: {
                    format: currentTable.fields.Trademark.clientFormatPattern
                }
            },
            {
                id: "Model",
                type: "string",
                label: currentTable.fields.Model.caption,
                operators: currentTable.fields.Model.clientSearchOperators,
                default_operator: "contains",
                input: ew.getQueryBuilderFilterInput(),
                valueSetter: ew.getQueryBuilderValueSetter(),
                validation: ew.getQueryBuilderFilterValidation(fcarssearch.fields.Model.validators),
                data: {
                    format: currentTable.fields.Model.clientFormatPattern
                }
            },
            {
                id: "HP",
                type: "string",
                label: currentTable.fields.HP.caption,
                operators: currentTable.fields.HP.clientSearchOperators,
                default_operator: "contains",
                input: ew.getQueryBuilderFilterInput(),
                value_separator: ew.IN_OPERATOR_VALUE_SEPARATOR,
                validation: ew.getQueryBuilderFilterValidation(fcarssearch.fields.HP.validators),
                data: {
                    format: currentTable.fields.HP.clientFormatPattern
                }
            },
            {
                id: "Cylinders",
                type: "integer",
                label: currentTable.fields.Cylinders.caption,
                operators: currentTable.fields.Cylinders.clientSearchOperators,
                default_operator: "equal",
                input: ew.getQueryBuilderFilterInput(),
                value_separator: ew.IN_OPERATOR_VALUE_SEPARATOR,
                validation: ew.getQueryBuilderFilterValidation(fcarssearch.fields.Cylinders.validators),
                data: {
                    format: currentTable.fields.Cylinders.clientFormatPattern
                }
            },
            {
                id: "Description",
                type: "string",
                label: currentTable.fields.Description.caption,
                operators: currentTable.fields.Description.clientSearchOperators,
                default_operator: "contains",
                input: ew.getQueryBuilderFilterInput(),
                value_separator: ew.IN_OPERATOR_VALUE_SEPARATOR,
                validation: ew.getQueryBuilderFilterValidation(fcarssearch.fields.Description.validators),
                data: {
                    format: currentTable.fields.Description.clientFormatPattern
                }
            },
            {
                id: "Price",
                type: "double",
                label: currentTable.fields.Price.caption,
                operators: currentTable.fields.Price.clientSearchOperators,
                default_operator: "equal",
                input: ew.getQueryBuilderFilterInput(),
                value_separator: ew.IN_OPERATOR_VALUE_SEPARATOR,
                validation: ew.getQueryBuilderFilterValidation(fcarssearch.fields.Price.validators),
                data: {
                    format: currentTable.fields.Price.clientFormatPattern
                }
            },
            {
                id: "Doors",
                type: "integer",
                label: currentTable.fields.Doors.caption,
                operators: currentTable.fields.Doors.clientSearchOperators,
                default_operator: "equal",
                input: ew.getQueryBuilderFilterInput(),
                value_separator: ew.IN_OPERATOR_VALUE_SEPARATOR,
                validation: ew.getQueryBuilderFilterValidation(fcarssearch.fields.Doors.validators),
                data: {
                    format: currentTable.fields.Doors.clientFormatPattern
                }
            },
            {
                id: "Torque",
                type: "string",
                label: currentTable.fields.Torque.caption,
                operators: currentTable.fields.Torque.clientSearchOperators,
                default_operator: "contains",
                input: ew.getQueryBuilderFilterInput(),
                value_separator: ew.IN_OPERATOR_VALUE_SEPARATOR,
                validation: ew.getQueryBuilderFilterValidation(fcarssearch.fields.Torque.validators),
                data: {
                    format: currentTable.fields.Torque.clientFormatPattern
                }
            },
        ],
        $ = jQuery,
        $qb = $("#cars_query_builder"),
        args = {},
        rules = ew.parseJson($("#fcarssearch input[name=rules]").val()),
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
    $("#fcarssearch").on("beforesubmit", function () {
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
        <button class="btn btn-primary ew-btn d-none disabled" name="btn-action" id="btn-action" type="submit" form="fcarssearch" formaction="<?= HtmlEncode(GetUrl("carslist")) ?>" data-ajax="<?= $Page->UseAjaxActions ? "true" : "false" ?>"><?= $Language->phrase("Search") ?></button>
        <?php if ($Page->IsModal) { ?>
        <button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="fcarssearch"><?= $Language->phrase("Cancel") ?></button>
        <?php } else { ?>
        <button class="btn btn-default ew-btn d-none disabled" name="btn-reset" id="btn-reset" type="button" form="fcarssearch" data-ew-action="reload"><?= $Language->phrase("Reset") ?></button>
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
    ew.addEventHandlers("cars");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
