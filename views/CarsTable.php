<?php
namespace PHPMaker2023\demo2023;
?>
<?php if ($Page->ModalGridAdd && $Page->isGridAdd() || $Page->ModalGridEdit && $Page->isGridEdit() || $Page->ModalMultiEdit && $Page->isMultiEdit()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { cars: currentTable } });
var currentPageID = ew.PAGE_ID = "list";
var currentForm;
var <?= $Page->FormName ?>;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("<?= $Page->FormName ?>")
        .setPageId("list")
        .setSubmitWithFetch(<?= $Page->UseAjaxActions ? "true" : "false" ?>)
        .setFormKeyCountName("<?= $Page->FormKeyCountName ?>")

        // Add fields
        .setFields([
            ["ID", [fields.ID.visible && fields.ID.required ? ew.Validators.required(fields.ID.caption) : null], fields.ID.isInvalid],
            ["Trademark", [fields.Trademark.visible && fields.Trademark.required ? ew.Validators.required(fields.Trademark.caption) : null], fields.Trademark.isInvalid],
            ["Model", [fields.Model.visible && fields.Model.required ? ew.Validators.required(fields.Model.caption) : null], fields.Model.isInvalid],
            ["HP", [fields.HP.visible && fields.HP.required ? ew.Validators.required(fields.HP.caption) : null], fields.HP.isInvalid],
            ["Cylinders", [fields.Cylinders.visible && fields.Cylinders.required ? ew.Validators.required(fields.Cylinders.caption) : null, ew.Validators.integer], fields.Cylinders.isInvalid],
            ["Price", [fields.Price.visible && fields.Price.required ? ew.Validators.required(fields.Price.caption) : null, ew.Validators.float], fields.Price.isInvalid],
            ["Picture", [fields.Picture.visible && fields.Picture.required ? ew.Validators.fileRequired(fields.Picture.caption) : null], fields.Picture.isInvalid],
            ["Doors", [fields.Doors.visible && fields.Doors.required ? ew.Validators.required(fields.Doors.caption) : null, ew.Validators.integer], fields.Doors.isInvalid],
            ["Torque", [fields.Torque.visible && fields.Torque.required ? ew.Validators.required(fields.Torque.caption) : null], fields.Torque.isInvalid]
        ])

        // Check empty row
        .setEmptyRow(
            function (rowIndex) {
                let fobj = this.getForm(),
                    fields = [["Trademark",false],["Model",false],["HP",false],["Cylinders",false],["Price",false],["Picture",false],["Doors",false],["Torque",false]];
                if (fields.some(field => ew.valueChanged(fobj, rowIndex, ...field)))
                    return false;
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
            "Trademark": <?= $Page->Trademark->toClientList($Page) ?>,
            "Model": <?= $Page->Model->toClientList($Page) ?>,
        })
        .build();
    window[form.id] = form;
    currentForm = form;
    loadjs.done(form.id);
});
</script>
<?php } ?>
<div class="card ew-card ew-grid<?= $Page->isAddOrEdit() ? " ew-grid-add-edit" : "" ?> <?= $Page->TableGridClass ?>">
<form name="<?= $Page->FormName ?>" id="<?= $Page->FormName ?>" class="ew-form ew-list-form ew-multi-column-form" action="<?= $Page->PageAction ?>" method="post" novalidate autocomplete="on">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="cars">
<?php if ($Page->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div id="gmp_cars" class="card-body ew-grid-middle-panel <?= $Page->TableContainerClass ?>" style="<?= $Page->TableContainerStyle ?>">
<?php if ($Page->TotalRecords > 0 || $Page->isGridEdit() || $Page->isMultiEdit()) { ?>
<table id="tbl_carslist" class="<?= $Page->TableClass ?>"><!-- .ew-table -->
<thead>
    <tr class="ew-table-header">
<?php
// Header row
$Page->RowType = ROWTYPE_HEADER;

// Render list options
$Page->renderListOptions();

// Render list options (header, left)
$Page->ListOptions->render("header", "left");
?>
<?php if ($Page->ID->Visible) { // ID ?>
        <th data-name="ID" class="<?= $Page->ID->headerCellClass() ?>"><div id="elh_cars_ID" class="cars_ID"><?= $Page->renderFieldHeader($Page->ID) ?></div></th>
<?php } ?>
<?php if ($Page->Trademark->Visible) { // Trademark ?>
        <th data-name="Trademark" class="<?= $Page->Trademark->headerCellClass() ?>"><div id="elh_cars_Trademark" class="cars_Trademark"><?= $Page->renderFieldHeader($Page->Trademark) ?></div></th>
<?php } ?>
<?php if ($Page->Model->Visible) { // Model ?>
        <th data-name="Model" class="<?= $Page->Model->headerCellClass() ?>"><div id="elh_cars_Model" class="cars_Model"><?= $Page->renderFieldHeader($Page->Model) ?></div></th>
<?php } ?>
<?php if ($Page->HP->Visible) { // HP ?>
        <th data-name="HP" class="<?= $Page->HP->headerCellClass() ?>"><div id="elh_cars_HP" class="cars_HP"><?= $Page->renderFieldHeader($Page->HP) ?></div></th>
<?php } ?>
<?php if ($Page->Cylinders->Visible) { // Cylinders ?>
        <th data-name="Cylinders" class="<?= $Page->Cylinders->headerCellClass() ?>"><div id="elh_cars_Cylinders" class="cars_Cylinders"><?= $Page->renderFieldHeader($Page->Cylinders) ?></div></th>
<?php } ?>
<?php if ($Page->Price->Visible) { // Price ?>
        <th data-name="Price" class="<?= $Page->Price->headerCellClass() ?>"><div id="elh_cars_Price" class="cars_Price"><?= $Page->renderFieldHeader($Page->Price) ?></div></th>
<?php } ?>
<?php if ($Page->Picture->Visible) { // Picture ?>
        <th data-name="Picture" class="<?= $Page->Picture->headerCellClass() ?>"><div id="elh_cars_Picture" class="cars_Picture"><?= $Page->renderFieldHeader($Page->Picture) ?></div></th>
<?php } ?>
<?php if ($Page->Doors->Visible) { // Doors ?>
        <th data-name="Doors" class="<?= $Page->Doors->headerCellClass() ?>"><div id="elh_cars_Doors" class="cars_Doors"><?= $Page->renderFieldHeader($Page->Doors) ?></div></th>
<?php } ?>
<?php if ($Page->Torque->Visible) { // Torque ?>
        <th data-name="Torque" class="<?= $Page->Torque->headerCellClass() ?>"><div id="elh_cars_Torque" class="cars_Torque"><?= $Page->renderFieldHeader($Page->Torque) ?></div></th>
<?php } ?>
<?php
// Render list options (header, right)
$Page->ListOptions->render("header", "right");
?>
    </tr>
</thead>
<tbody data-page="<?= $Page->getPageNumber() ?>">
<?php
$Page->setupGrid();
while ($Page->RecordCount < $Page->StopRecord || $Page->RowIndex === '$rowindex$') {
    $Page->RecordCount++;
    if ($Page->RecordCount >= $Page->StartRecord) {
        $Page->setupRow();

        // Skip 1) delete row / empty row for confirm page, 2) hidden row
        if (
            $Page->RowAction != "delete" &&
            $Page->RowAction != "insertdelete" &&
            !($Page->RowAction == "insert" && $Page->isConfirm() && $Page->emptyRow()) &&
            $Page->RowAction != "hide"
        ) {
?>
    <tr <?= $Page->rowAttributes() ?>>
<?php
// Render list options (body, left)
$Page->ListOptions->render("body", "left", $Page->RowCount);
?>
    <?php if ($Page->ID->Visible) { // ID ?>
        <td data-name="ID"<?= $Page->ID->cellAttributes() ?>>
<?php if ($Page->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_cars_ID" class="el_cars_ID"></span>
<input type="hidden" data-table="cars" data-field="x_ID" data-hidden="1" data-old name="o<?= $Page->RowIndex ?>_ID" id="o<?= $Page->RowIndex ?>_ID" value="<?= HtmlEncode($Page->ID->OldValue) ?>">
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_cars_ID" class="el_cars_ID">
<span<?= $Page->ID->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->ID->getDisplayValue($Page->ID->EditValue))) ?>"></span>
<input type="hidden" data-table="cars" data-field="x_ID" data-hidden="1" name="x<?= $Page->RowIndex ?>_ID" id="x<?= $Page->RowIndex ?>_ID" value="<?= HtmlEncode($Page->ID->CurrentValue) ?>">
</span>
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_cars_ID" class="el_cars_ID">
<span<?= $Page->ID->viewAttributes() ?>>
<?= $Page->ID->getViewValue() ?></span>
</span>
<?php } ?>
</td>
    <?php } else { ?>
            <input type="hidden" data-table="cars" data-field="x_ID" data-hidden="1" name="x<?= $Page->RowIndex ?>_ID" id="x<?= $Page->RowIndex ?>_ID" value="<?= HtmlEncode($Page->ID->CurrentValue) ?>">
    <?php } ?>
    <?php if ($Page->Trademark->Visible) { // Trademark ?>
        <td data-name="Trademark"<?= $Page->Trademark->cellAttributes() ?>>
<?php if ($Page->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_cars_Trademark" class="el_cars_Trademark">
<div class="input-group flex-nowrap">
    <select
        id="x<?= $Page->RowIndex ?>_Trademark"
        name="x<?= $Page->RowIndex ?>_Trademark"
        class="form-select ew-select<?= $Page->Trademark->isInvalidClass() ?>"
        <?php if (!$Page->Trademark->IsNativeSelect) { ?>
        data-select2-id="<?= $Page->FormName ?>_x<?= $Page->RowIndex ?>_Trademark"
        <?php } ?>
        data-table="cars"
        data-field="x_Trademark"
        data-value-separator="<?= $Page->Trademark->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->Trademark->getPlaceHolder()) ?>"
        data-ew-action="update-options"
        <?= $Page->Trademark->editAttributes() ?>>
        <?= $Page->Trademark->selectOptionListHtml("x{$Page->RowIndex}_Trademark") ?>
    </select>
    <?php if (AllowAdd(CurrentProjectID() . "trademarks") && !$Page->Trademark->ReadOnly) { ?>
    <button type="button" class="btn btn-default ew-add-opt-btn" id="aol_x<?= $Page->RowIndex ?>_Trademark" title="<?= HtmlTitle($Language->phrase("AddLink")) . "&nbsp;" . $Page->Trademark->caption() ?>" data-title="<?= $Page->Trademark->caption() ?>" data-ew-action="add-option" data-el="x<?= $Page->RowIndex ?>_Trademark" data-url="<?= GetUrl("trademarksaddopt") ?>"><i class="fa-solid fa-plus ew-icon"></i></button>
    <?php } ?>
</div>
<div class="invalid-feedback"><?= $Page->Trademark->getErrorMessage() ?></div>
<?= $Page->Trademark->Lookup->getParamTag($Page, "p_x" . $Page->RowIndex . "_Trademark") ?>
<?php if (!$Page->Trademark->IsNativeSelect) { ?>
<script>
loadjs.ready("<?= $Page->FormName ?>", function() {
    var options = { name: "x<?= $Page->RowIndex ?>_Trademark", selectId: "<?= $Page->FormName ?>_x<?= $Page->RowIndex ?>_Trademark" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (<?= $Page->FormName ?>.lists.Trademark?.lookupOptions.length) {
        options.data = { id: "x<?= $Page->RowIndex ?>_Trademark", form: "<?= $Page->FormName ?>" };
    } else {
        options.ajax = { id: "x<?= $Page->RowIndex ?>_Trademark", form: "<?= $Page->FormName ?>", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.cars.fields.Trademark.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
<input type="hidden" data-table="cars" data-field="x_Trademark" data-hidden="1" data-old name="o<?= $Page->RowIndex ?>_Trademark" id="o<?= $Page->RowIndex ?>_Trademark" value="<?= HtmlEncode($Page->Trademark->OldValue) ?>">
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_cars_Trademark" class="el_cars_Trademark">
<div class="input-group flex-nowrap">
    <select
        id="x<?= $Page->RowIndex ?>_Trademark"
        name="x<?= $Page->RowIndex ?>_Trademark"
        class="form-select ew-select<?= $Page->Trademark->isInvalidClass() ?>"
        <?php if (!$Page->Trademark->IsNativeSelect) { ?>
        data-select2-id="<?= $Page->FormName ?>_x<?= $Page->RowIndex ?>_Trademark"
        <?php } ?>
        data-table="cars"
        data-field="x_Trademark"
        data-value-separator="<?= $Page->Trademark->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->Trademark->getPlaceHolder()) ?>"
        data-ew-action="update-options"
        <?= $Page->Trademark->editAttributes() ?>>
        <?= $Page->Trademark->selectOptionListHtml("x{$Page->RowIndex}_Trademark") ?>
    </select>
    <?php if (AllowAdd(CurrentProjectID() . "trademarks") && !$Page->Trademark->ReadOnly) { ?>
    <button type="button" class="btn btn-default ew-add-opt-btn" id="aol_x<?= $Page->RowIndex ?>_Trademark" title="<?= HtmlTitle($Language->phrase("AddLink")) . "&nbsp;" . $Page->Trademark->caption() ?>" data-title="<?= $Page->Trademark->caption() ?>" data-ew-action="add-option" data-el="x<?= $Page->RowIndex ?>_Trademark" data-url="<?= GetUrl("trademarksaddopt") ?>"><i class="fa-solid fa-plus ew-icon"></i></button>
    <?php } ?>
</div>
<div class="invalid-feedback"><?= $Page->Trademark->getErrorMessage() ?></div>
<?= $Page->Trademark->Lookup->getParamTag($Page, "p_x" . $Page->RowIndex . "_Trademark") ?>
<?php if (!$Page->Trademark->IsNativeSelect) { ?>
<script>
loadjs.ready("<?= $Page->FormName ?>", function() {
    var options = { name: "x<?= $Page->RowIndex ?>_Trademark", selectId: "<?= $Page->FormName ?>_x<?= $Page->RowIndex ?>_Trademark" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (<?= $Page->FormName ?>.lists.Trademark?.lookupOptions.length) {
        options.data = { id: "x<?= $Page->RowIndex ?>_Trademark", form: "<?= $Page->FormName ?>" };
    } else {
        options.ajax = { id: "x<?= $Page->RowIndex ?>_Trademark", form: "<?= $Page->FormName ?>", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.cars.fields.Trademark.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_cars_Trademark" class="el_cars_Trademark">
<span<?= $Page->Trademark->viewAttributes() ?>>
<?= $Page->Trademark->getViewValue() ?></span>
</span>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Page->Model->Visible) { // Model ?>
        <td data-name="Model"<?= $Page->Model->cellAttributes() ?>>
<?php if ($Page->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_cars_Model" class="el_cars_Model">
<div class="input-group flex-nowrap">
    <select
        id="x<?= $Page->RowIndex ?>_Model"
        name="x<?= $Page->RowIndex ?>_Model"
        class="form-select ew-select<?= $Page->Model->isInvalidClass() ?>"
        <?php if (!$Page->Model->IsNativeSelect) { ?>
        data-select2-id="<?= $Page->FormName ?>_x<?= $Page->RowIndex ?>_Model"
        <?php } ?>
        data-table="cars"
        data-field="x_Model"
        data-value-separator="<?= $Page->Model->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->Model->getPlaceHolder()) ?>"
        <?= $Page->Model->editAttributes() ?>>
        <?= $Page->Model->selectOptionListHtml("x{$Page->RowIndex}_Model") ?>
    </select>
    <?php if (AllowAdd(CurrentProjectID() . "models") && !$Page->Model->ReadOnly) { ?>
    <button type="button" class="btn btn-default ew-add-opt-btn" id="aol_x<?= $Page->RowIndex ?>_Model" title="<?= HtmlTitle($Language->phrase("AddLink")) . "&nbsp;" . $Page->Model->caption() ?>" data-title="<?= $Page->Model->caption() ?>" data-ew-action="add-option" data-el="x<?= $Page->RowIndex ?>_Model" data-url="<?= GetUrl("modelsaddopt") ?>"><i class="fa-solid fa-plus ew-icon"></i></button>
    <?php } ?>
</div>
<div class="invalid-feedback"><?= $Page->Model->getErrorMessage() ?></div>
<?= $Page->Model->Lookup->getParamTag($Page, "p_x" . $Page->RowIndex . "_Model") ?>
<?php if (!$Page->Model->IsNativeSelect) { ?>
<script>
loadjs.ready("<?= $Page->FormName ?>", function() {
    var options = { name: "x<?= $Page->RowIndex ?>_Model", selectId: "<?= $Page->FormName ?>_x<?= $Page->RowIndex ?>_Model" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (<?= $Page->FormName ?>.lists.Model?.lookupOptions.length) {
        options.data = { id: "x<?= $Page->RowIndex ?>_Model", form: "<?= $Page->FormName ?>" };
    } else {
        options.ajax = { id: "x<?= $Page->RowIndex ?>_Model", form: "<?= $Page->FormName ?>", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.cars.fields.Model.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
<input type="hidden" data-table="cars" data-field="x_Model" data-hidden="1" data-old name="o<?= $Page->RowIndex ?>_Model" id="o<?= $Page->RowIndex ?>_Model" value="<?= HtmlEncode($Page->Model->OldValue) ?>">
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_cars_Model" class="el_cars_Model">
<div class="input-group flex-nowrap">
    <select
        id="x<?= $Page->RowIndex ?>_Model"
        name="x<?= $Page->RowIndex ?>_Model"
        class="form-select ew-select<?= $Page->Model->isInvalidClass() ?>"
        <?php if (!$Page->Model->IsNativeSelect) { ?>
        data-select2-id="<?= $Page->FormName ?>_x<?= $Page->RowIndex ?>_Model"
        <?php } ?>
        data-table="cars"
        data-field="x_Model"
        data-value-separator="<?= $Page->Model->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->Model->getPlaceHolder()) ?>"
        <?= $Page->Model->editAttributes() ?>>
        <?= $Page->Model->selectOptionListHtml("x{$Page->RowIndex}_Model") ?>
    </select>
    <?php if (AllowAdd(CurrentProjectID() . "models") && !$Page->Model->ReadOnly) { ?>
    <button type="button" class="btn btn-default ew-add-opt-btn" id="aol_x<?= $Page->RowIndex ?>_Model" title="<?= HtmlTitle($Language->phrase("AddLink")) . "&nbsp;" . $Page->Model->caption() ?>" data-title="<?= $Page->Model->caption() ?>" data-ew-action="add-option" data-el="x<?= $Page->RowIndex ?>_Model" data-url="<?= GetUrl("modelsaddopt") ?>"><i class="fa-solid fa-plus ew-icon"></i></button>
    <?php } ?>
</div>
<div class="invalid-feedback"><?= $Page->Model->getErrorMessage() ?></div>
<?= $Page->Model->Lookup->getParamTag($Page, "p_x" . $Page->RowIndex . "_Model") ?>
<?php if (!$Page->Model->IsNativeSelect) { ?>
<script>
loadjs.ready("<?= $Page->FormName ?>", function() {
    var options = { name: "x<?= $Page->RowIndex ?>_Model", selectId: "<?= $Page->FormName ?>_x<?= $Page->RowIndex ?>_Model" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (<?= $Page->FormName ?>.lists.Model?.lookupOptions.length) {
        options.data = { id: "x<?= $Page->RowIndex ?>_Model", form: "<?= $Page->FormName ?>" };
    } else {
        options.ajax = { id: "x<?= $Page->RowIndex ?>_Model", form: "<?= $Page->FormName ?>", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.cars.fields.Model.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_cars_Model" class="el_cars_Model">
<span<?= $Page->Model->viewAttributes() ?>>
<?php if (!EmptyString($Page->Model->TooltipValue) && $Page->Model->linkAttributes() != "") { ?>
<a<?= $Page->Model->linkAttributes() ?>><?= $Page->Model->getViewValue() ?></a>
<?php } else { ?>
<?= $Page->Model->getViewValue() ?>
<?php } ?>
<span id="tt_cars_x<?= $Page->RowCount ?>_Model" class="d-none">
<?= $Page->Model->TooltipValue ?>
</span></span>
</span>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Page->HP->Visible) { // HP ?>
        <td data-name="HP"<?= $Page->HP->cellAttributes() ?>>
<?php if ($Page->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_cars_HP" class="el_cars_HP">
<input type="<?= $Page->HP->getInputTextType() ?>" name="x<?= $Page->RowIndex ?>_HP" id="x<?= $Page->RowIndex ?>_HP" data-table="cars" data-field="x_HP" value="<?= $Page->HP->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->HP->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->HP->formatPattern()) ?>"<?= $Page->HP->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->HP->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="cars" data-field="x_HP" data-hidden="1" data-old name="o<?= $Page->RowIndex ?>_HP" id="o<?= $Page->RowIndex ?>_HP" value="<?= HtmlEncode($Page->HP->OldValue) ?>">
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_cars_HP" class="el_cars_HP">
<input type="<?= $Page->HP->getInputTextType() ?>" name="x<?= $Page->RowIndex ?>_HP" id="x<?= $Page->RowIndex ?>_HP" data-table="cars" data-field="x_HP" value="<?= $Page->HP->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->HP->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->HP->formatPattern()) ?>"<?= $Page->HP->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->HP->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_cars_HP" class="el_cars_HP">
<span<?= $Page->HP->viewAttributes() ?>>
<?= $Page->HP->getViewValue() ?></span>
</span>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Page->Cylinders->Visible) { // Cylinders ?>
        <td data-name="Cylinders"<?= $Page->Cylinders->cellAttributes() ?>>
<?php if ($Page->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_cars_Cylinders" class="el_cars_Cylinders">
<input type="<?= $Page->Cylinders->getInputTextType() ?>" name="x<?= $Page->RowIndex ?>_Cylinders" id="x<?= $Page->RowIndex ?>_Cylinders" data-table="cars" data-field="x_Cylinders" value="<?= $Page->Cylinders->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->Cylinders->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Cylinders->formatPattern()) ?>"<?= $Page->Cylinders->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->Cylinders->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="cars" data-field="x_Cylinders" data-hidden="1" data-old name="o<?= $Page->RowIndex ?>_Cylinders" id="o<?= $Page->RowIndex ?>_Cylinders" value="<?= HtmlEncode($Page->Cylinders->OldValue) ?>">
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_cars_Cylinders" class="el_cars_Cylinders">
<input type="<?= $Page->Cylinders->getInputTextType() ?>" name="x<?= $Page->RowIndex ?>_Cylinders" id="x<?= $Page->RowIndex ?>_Cylinders" data-table="cars" data-field="x_Cylinders" value="<?= $Page->Cylinders->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->Cylinders->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Cylinders->formatPattern()) ?>"<?= $Page->Cylinders->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->Cylinders->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_cars_Cylinders" class="el_cars_Cylinders">
<span<?= $Page->Cylinders->viewAttributes() ?>>
<?= $Page->Cylinders->getViewValue() ?></span>
</span>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Page->Price->Visible) { // Price ?>
        <td data-name="Price"<?= $Page->Price->cellAttributes() ?>>
<?php if ($Page->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_cars_Price" class="el_cars_Price">
<input type="<?= $Page->Price->getInputTextType() ?>" name="x<?= $Page->RowIndex ?>_Price" id="x<?= $Page->RowIndex ?>_Price" data-table="cars" data-field="x_Price" value="<?= $Page->Price->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->Price->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Price->formatPattern()) ?>"<?= $Page->Price->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->Price->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="cars" data-field="x_Price" data-hidden="1" data-old name="o<?= $Page->RowIndex ?>_Price" id="o<?= $Page->RowIndex ?>_Price" value="<?= HtmlEncode($Page->Price->OldValue) ?>">
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_cars_Price" class="el_cars_Price">
<input type="<?= $Page->Price->getInputTextType() ?>" name="x<?= $Page->RowIndex ?>_Price" id="x<?= $Page->RowIndex ?>_Price" data-table="cars" data-field="x_Price" value="<?= $Page->Price->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->Price->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Price->formatPattern()) ?>"<?= $Page->Price->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->Price->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_cars_Price" class="el_cars_Price">
<span<?= $Page->Price->viewAttributes() ?>>
<?= $Page->Price->getViewValue() ?></span>
</span>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Page->Picture->Visible) { // Picture ?>
        <td data-name="Picture"<?= $Page->Picture->cellAttributes() ?>>
<?php if ($Page->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_cars_Picture" class="el_cars_Picture">
<div id="fd_x<?= $Page->RowIndex ?>_Picture" class="fileinput-button ew-file-drop-zone">
    <input
        type="file"
        id="x<?= $Page->RowIndex ?>_Picture"
        name="x<?= $Page->RowIndex ?>_Picture"
        class="form-control ew-file-input"
        title="<?= $Page->Picture->title() ?>"
        lang="<?= CurrentLanguageID() ?>"
        data-table="cars"
        data-field="x_Picture"
        data-size="0"
        data-accept-file-types="<?= $Page->Picture->acceptFileTypes() ?>"
        data-max-file-size="<?= $Page->Picture->UploadMaxFileSize ?>"
        data-max-number-of-files="null"
        data-disable-image-crop="<?= $Page->Picture->ImageCropper ? 0 : 1 ?>"
        <?= ($Page->Picture->ReadOnly || $Page->Picture->Disabled) ? " disabled" : "" ?>
        <?= $Page->Picture->editAttributes() ?>
    >
    <div class="text-muted ew-file-text"><?= $Language->phrase("ChooseFile") ?></div>
    <div class="invalid-feedback"><?= $Page->Picture->getErrorMessage() ?></div>
</div>
<input type="hidden" name="fn_x<?= $Page->RowIndex ?>_Picture" id= "fn_x<?= $Page->RowIndex ?>_Picture" value="<?= $Page->Picture->Upload->FileName ?>">
<input type="hidden" name="fa_x<?= $Page->RowIndex ?>_Picture" id= "fa_x<?= $Page->RowIndex ?>_Picture" value="0">
<table id="ft_x<?= $Page->RowIndex ?>_Picture" class="table table-sm float-start ew-upload-table"><tbody class="files"></tbody></table>
</span>
<input type="hidden" data-table="cars" data-field="x_Picture" data-hidden="1" data-old name="o<?= $Page->RowIndex ?>_Picture" id="o<?= $Page->RowIndex ?>_Picture" value="<?= HtmlEncode($Page->Picture->OldValue) ?>">
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_cars_Picture" class="el_cars_Picture">
<div id="fd_x<?= $Page->RowIndex ?>_Picture" class="fileinput-button ew-file-drop-zone">
    <input
        type="file"
        id="x<?= $Page->RowIndex ?>_Picture"
        name="x<?= $Page->RowIndex ?>_Picture"
        class="form-control ew-file-input"
        title="<?= $Page->Picture->title() ?>"
        lang="<?= CurrentLanguageID() ?>"
        data-table="cars"
        data-field="x_Picture"
        data-size="0"
        data-accept-file-types="<?= $Page->Picture->acceptFileTypes() ?>"
        data-max-file-size="<?= $Page->Picture->UploadMaxFileSize ?>"
        data-max-number-of-files="null"
        data-disable-image-crop="<?= $Page->Picture->ImageCropper ? 0 : 1 ?>"
        <?= ($Page->Picture->ReadOnly || $Page->Picture->Disabled) ? " disabled" : "" ?>
        <?= $Page->Picture->editAttributes() ?>
    >
    <div class="text-muted ew-file-text"><?= $Language->phrase("ChooseFile") ?></div>
    <div class="invalid-feedback"><?= $Page->Picture->getErrorMessage() ?></div>
</div>
<input type="hidden" name="fn_x<?= $Page->RowIndex ?>_Picture" id= "fn_x<?= $Page->RowIndex ?>_Picture" value="<?= $Page->Picture->Upload->FileName ?>">
<input type="hidden" name="fa_x<?= $Page->RowIndex ?>_Picture" id= "fa_x<?= $Page->RowIndex ?>_Picture" value="<?= (Post("fa_x<?= $Page->RowIndex ?>_Picture") == "0") ? "0" : "1" ?>">
<table id="ft_x<?= $Page->RowIndex ?>_Picture" class="table table-sm float-start ew-upload-table"><tbody class="files"></tbody></table>
</span>
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_cars_Picture" class="el_cars_Picture">
<span>
<?= GetFileViewTag($Page->Picture, $Page->Picture->getViewValue(), false) ?>
</span>
</span>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Page->Doors->Visible) { // Doors ?>
        <td data-name="Doors"<?= $Page->Doors->cellAttributes() ?>>
<?php if ($Page->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_cars_Doors" class="el_cars_Doors">
<input type="<?= $Page->Doors->getInputTextType() ?>" name="x<?= $Page->RowIndex ?>_Doors" id="x<?= $Page->RowIndex ?>_Doors" data-table="cars" data-field="x_Doors" value="<?= $Page->Doors->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->Doors->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Doors->formatPattern()) ?>"<?= $Page->Doors->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->Doors->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="cars" data-field="x_Doors" data-hidden="1" data-old name="o<?= $Page->RowIndex ?>_Doors" id="o<?= $Page->RowIndex ?>_Doors" value="<?= HtmlEncode($Page->Doors->OldValue) ?>">
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_cars_Doors" class="el_cars_Doors">
<input type="<?= $Page->Doors->getInputTextType() ?>" name="x<?= $Page->RowIndex ?>_Doors" id="x<?= $Page->RowIndex ?>_Doors" data-table="cars" data-field="x_Doors" value="<?= $Page->Doors->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->Doors->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Doors->formatPattern()) ?>"<?= $Page->Doors->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->Doors->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_cars_Doors" class="el_cars_Doors">
<span<?= $Page->Doors->viewAttributes() ?>>
<?= $Page->Doors->getViewValue() ?></span>
</span>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Page->Torque->Visible) { // Torque ?>
        <td data-name="Torque"<?= $Page->Torque->cellAttributes() ?>>
<?php if ($Page->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_cars_Torque" class="el_cars_Torque">
<input type="<?= $Page->Torque->getInputTextType() ?>" name="x<?= $Page->RowIndex ?>_Torque" id="x<?= $Page->RowIndex ?>_Torque" data-table="cars" data-field="x_Torque" value="<?= $Page->Torque->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->Torque->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Torque->formatPattern()) ?>"<?= $Page->Torque->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->Torque->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="cars" data-field="x_Torque" data-hidden="1" data-old name="o<?= $Page->RowIndex ?>_Torque" id="o<?= $Page->RowIndex ?>_Torque" value="<?= HtmlEncode($Page->Torque->OldValue) ?>">
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_cars_Torque" class="el_cars_Torque">
<input type="<?= $Page->Torque->getInputTextType() ?>" name="x<?= $Page->RowIndex ?>_Torque" id="x<?= $Page->RowIndex ?>_Torque" data-table="cars" data-field="x_Torque" value="<?= $Page->Torque->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->Torque->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Torque->formatPattern()) ?>"<?= $Page->Torque->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->Torque->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_cars_Torque" class="el_cars_Torque">
<span<?= $Page->Torque->viewAttributes() ?>>
<?= $Page->Torque->getViewValue() ?></span>
</span>
<?php } ?>
</td>
    <?php } ?>
<?php
// Render list options (body, right)
$Page->ListOptions->render("body", "right", $Page->RowCount);
?>
    </tr>
<?php if ($Page->RowType == ROWTYPE_ADD || $Page->RowType == ROWTYPE_EDIT) { ?>
<script data-rowindex="<?= $Page->RowIndex ?>">
loadjs.ready(["<?= $Page->FormName ?>","load"], () => <?= $Page->FormName ?>.updateLists(<?= $Page->RowIndex ?><?= $Page->isAdd() || $Page->isEdit() || $Page->isCopy() || $Page->RowIndex === '$rowindex$' ? ", true" : "" ?>));
</script>
<?php } ?>
<?php
    }
    } // End delete row checking
    if (
        $Page->Recordset &&
        !$Page->Recordset->EOF &&
        $Page->RowIndex !== '$rowindex$' &&
        (!$Page->isGridAdd() || $Page->CurrentMode == "copy") &&
        (!(($Page->isCopy() || $Page->isAdd()) && $Page->RowIndex == 0))
    ) {
        $Page->Recordset->moveNext();
    }
    // Reset for template row
    if ($Page->RowIndex === '$rowindex$') {
        $Page->RowIndex = 0;
    }
    // Reset inline add/copy row
    if (($Page->isCopy() || $Page->isAdd()) && $Page->RowIndex == 0) {
        $Page->RowIndex = 1;
    }
}
?>
</tbody>
<?php
// Render aggregate row
$Page->RowType = ROWTYPE_AGGREGATE;
$Page->resetAttributes();
$Page->renderRow();
?>
<?php if ($Page->TotalRecords > 0 && !$Page->isGridAdd() && !$Page->isGridEdit() && !$Page->isMultiEdit()) { ?>
<tfoot><!-- Table footer -->
    <tr class="ew-table-footer">
<?php
// Render list options
$Page->renderListOptions();

// Render list options (footer, left)
$Page->ListOptions->render("footer", "left");
?>
    <?php if ($Page->ID->Visible) { // ID ?>
        <td data-name="ID" class="<?= $Page->ID->footerCellClass() ?>"><span id="elf_cars_ID" class="cars_ID">
        </span></td>
    <?php } ?>
    <?php if ($Page->Trademark->Visible) { // Trademark ?>
        <td data-name="Trademark" class="<?= $Page->Trademark->footerCellClass() ?>"><span id="elf_cars_Trademark" class="cars_Trademark">
        <span class="ew-aggregate"><?= $Language->phrase("COUNT") ?></span><span class="ew-aggregate-value">
        <?= $Page->Trademark->ViewValue ?></span>
        </span></td>
    <?php } ?>
    <?php if ($Page->Model->Visible) { // Model ?>
        <td data-name="Model" class="<?= $Page->Model->footerCellClass() ?>"><span id="elf_cars_Model" class="cars_Model">
        </span></td>
    <?php } ?>
    <?php if ($Page->HP->Visible) { // HP ?>
        <td data-name="HP" class="<?= $Page->HP->footerCellClass() ?>"><span id="elf_cars_HP" class="cars_HP">
        </span></td>
    <?php } ?>
    <?php if ($Page->Cylinders->Visible) { // Cylinders ?>
        <td data-name="Cylinders" class="<?= $Page->Cylinders->footerCellClass() ?>"><span id="elf_cars_Cylinders" class="cars_Cylinders">
        </span></td>
    <?php } ?>
    <?php if ($Page->Price->Visible) { // Price ?>
        <td data-name="Price" class="<?= $Page->Price->footerCellClass() ?>"><span id="elf_cars_Price" class="cars_Price">
        </span></td>
    <?php } ?>
    <?php if ($Page->Picture->Visible) { // Picture ?>
        <td data-name="Picture" class="<?= $Page->Picture->footerCellClass() ?>"><span id="elf_cars_Picture" class="cars_Picture">
        </span></td>
    <?php } ?>
    <?php if ($Page->Doors->Visible) { // Doors ?>
        <td data-name="Doors" class="<?= $Page->Doors->footerCellClass() ?>"><span id="elf_cars_Doors" class="cars_Doors">
        </span></td>
    <?php } ?>
    <?php if ($Page->Torque->Visible) { // Torque ?>
        <td data-name="Torque" class="<?= $Page->Torque->footerCellClass() ?>"><span id="elf_cars_Torque" class="cars_Torque">
        </span></td>
    <?php } ?>
<?php
// Render list options (footer, right)
$Page->ListOptions->render("footer", "right");
?>
    </tr>
</tfoot>
<?php } ?>
</table><!-- /.ew-table -->
<?php } ?>
<?php if ($Page->isGridAdd()) { ?>
<input type="hidden" name="action" id="action" value="gridinsert">
<input type="hidden" name="<?= $Page->FormKeyCountName ?>" id="<?= $Page->FormKeyCountName ?>" value="<?= $Page->KeyCount ?>">
<?= $Page->MultiSelectKey ?>
<?php } ?>
<?php if ($Page->isGridEdit() || $Page->isMultiEdit()) { ?>
<?php if ($Page->UpdateConflict == "U") { // Record already updated by other user ?>
<input type="hidden" name="action" id="action" value="gridoverwrite">
<?php } else { ?>
<?php if ($Page->isGridEdit()) { ?>
<input type="hidden" name="action" id="action" value="gridupdate">
<?php } elseif ($Page->isMultiEdit()) { ?>
<input type="hidden" name="action" id="action" value="multiupdate">
<?php } ?>
<?php } ?>
<input type="hidden" name="<?= $Page->FormKeyCountName ?>" id="<?= $Page->FormKeyCountName ?>" value="<?= $Page->KeyCount ?>">
<?= $Page->MultiSelectKey ?>
<?php } ?>
</div><!-- /.ew-grid-middle-panel -->
<?php if (!$Page->CurrentAction && !$Page->UseAjaxActions) { ?>
<input type="hidden" name="action" id="action" value="">
<?php } ?>
</form><!-- /.ew-list-form -->
<?php
// Close recordset
if ($Page->Recordset) {
    $Page->Recordset->close();
}
?>
<?php if (!$Page->isExport()) { ?>
<div class="card-footer ew-grid-lower-panel">
<?php if (!$Page->isGridAdd() && !($Page->isGridEdit() && $Page->ModalGridEdit) && !$Page->isMultiEdit()) { ?>
<?= $Page->Pager->render() ?>
<?php } ?>
<div class="ew-list-other-options">
<?php $Page->OtherOptions->render("body", "bottom") ?>
</div>
</div>
<?php } ?>
</div><!-- /.ew-grid -->
