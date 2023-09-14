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
<form name="<?= $Page->FormName ?>" id="<?= $Page->FormName ?>" class="ew-form ew-list-form ew-multi-column-form" action="<?= $Page->PageAction ?>" method="post" novalidate autocomplete="on">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="cars">
<?php if ($Page->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div class="<?= $Page->getMultiColumnRowClass() ?>">
<?php if ($Page->TotalRecords > 0 || $Page->isGridEdit() || $Page->isMultiEdit()) { ?>
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
<div class="<?= $Page->getMultiColumnColClass() ?>" <?= $Page->rowAttributes() ?>>
<?php if (!$Page->isAddOrEdit()) { // Show custom card layout ?>
<div class="card h-100">
  <div class="row g-0">
    <div class="col-md-6">
<?php if ($Page->RowType == ROWTYPE_ADD) { // Add record ?>
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
<input type="hidden" data-table="cars" data-field="x_Picture" data-hidden="1" data-old name="o<?= $Page->RowIndex ?>_Picture" id="o<?= $Page->RowIndex ?>_Picture" value="<?= HtmlEncode($Page->Picture->OldValue) ?>">
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_EDIT) { // Edit record ?>
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
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_VIEW) { // View record ?>
<span>
<?= GetFileViewTag($Page->Picture, $Page->Picture->getViewValue(), false) ?>
</span>
<?php } ?>
    </div>
    <div class="col-md-6">
      <div class="card-body">
        <h5 class="card-title">
<?php if ($Page->RowType == ROWTYPE_ADD) { // Add record ?>
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
<input type="hidden" data-table="cars" data-field="x_Model" data-hidden="1" data-old name="o<?= $Page->RowIndex ?>_Model" id="o<?= $Page->RowIndex ?>_Model" value="<?= HtmlEncode($Page->Model->OldValue) ?>">
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_EDIT) { // Edit record ?>
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
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_VIEW) { // View record ?>
<span<?= $Page->Model->viewAttributes() ?>>
<?php if (!EmptyString($Page->Model->TooltipValue) && $Page->Model->linkAttributes() != "") { ?>
<a<?= $Page->Model->linkAttributes() ?>><?= $Page->Model->getViewValue() ?></a>
<?php } else { ?>
<?= $Page->Model->getViewValue() ?>
<?php } ?>
<span id="tt_cars_x<?= $Page->RowCount ?>_Model" class="d-none">
<?= $Page->Model->TooltipValue ?>
</span></span>
<?php } ?>
</h5>
        <p class="card-text"><small class="text-muted">
<?php if ($Page->RowType == ROWTYPE_ADD) { // Add record ?>
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
<input type="hidden" data-table="cars" data-field="x_Trademark" data-hidden="1" data-old name="o<?= $Page->RowIndex ?>_Trademark" id="o<?= $Page->RowIndex ?>_Trademark" value="<?= HtmlEncode($Page->Trademark->OldValue) ?>">
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_EDIT) { // Edit record ?>
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
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_VIEW) { // View record ?>
<span<?= $Page->Trademark->viewAttributes() ?>>
<?= $Page->Trademark->getViewValue() ?></span>
<?php } ?>
</small></p>
        <p class="card-text">
<?php if ($Page->RowType == ROWTYPE_ADD) { // Add record ?>
<input type="<?= $Page->HP->getInputTextType() ?>" name="x<?= $Page->RowIndex ?>_HP" id="x<?= $Page->RowIndex ?>_HP" data-table="cars" data-field="x_HP" value="<?= $Page->HP->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->HP->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->HP->formatPattern()) ?>"<?= $Page->HP->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->HP->getErrorMessage() ?></div>
<input type="hidden" data-table="cars" data-field="x_HP" data-hidden="1" data-old name="o<?= $Page->RowIndex ?>_HP" id="o<?= $Page->RowIndex ?>_HP" value="<?= HtmlEncode($Page->HP->OldValue) ?>">
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_EDIT) { // Edit record ?>
<input type="<?= $Page->HP->getInputTextType() ?>" name="x<?= $Page->RowIndex ?>_HP" id="x<?= $Page->RowIndex ?>_HP" data-table="cars" data-field="x_HP" value="<?= $Page->HP->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->HP->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->HP->formatPattern()) ?>"<?= $Page->HP->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->HP->getErrorMessage() ?></div>
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_VIEW) { // View record ?>
<span<?= $Page->HP->viewAttributes() ?>>
<?= $Page->HP->getViewValue() ?></span>
<?php } ?>
</p>
        <p class="card-text">
<?php if ($Page->RowType == ROWTYPE_ADD) { // Add record ?>
<input type="<?= $Page->Price->getInputTextType() ?>" name="x<?= $Page->RowIndex ?>_Price" id="x<?= $Page->RowIndex ?>_Price" data-table="cars" data-field="x_Price" value="<?= $Page->Price->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->Price->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Price->formatPattern()) ?>"<?= $Page->Price->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->Price->getErrorMessage() ?></div>
<input type="hidden" data-table="cars" data-field="x_Price" data-hidden="1" data-old name="o<?= $Page->RowIndex ?>_Price" id="o<?= $Page->RowIndex ?>_Price" value="<?= HtmlEncode($Page->Price->OldValue) ?>">
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_EDIT) { // Edit record ?>
<input type="<?= $Page->Price->getInputTextType() ?>" name="x<?= $Page->RowIndex ?>_Price" id="x<?= $Page->RowIndex ?>_Price" data-table="cars" data-field="x_Price" value="<?= $Page->Price->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->Price->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Price->formatPattern()) ?>"<?= $Page->Price->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->Price->getErrorMessage() ?></div>
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_VIEW) { // View record ?>
<span<?= $Page->Price->viewAttributes() ?>>
<?= $Page->Price->getViewValue() ?></span>
<?php } ?>
</p>
      </div>
    </div>
  </div>
  <div class="ew-multi-column-list-option ew-<?= $Page->MultiColumnListOptionsPosition ?>"><?php $Page->ListOptions->Tag = "div";$Page->ListOptions->render("body"); ?></div>
</div>
<?php } else { // Show normal card layout ?>
<div class="<?= $Page->MultiColumnCardClass ?>">
    <?php if (StartsText("top", $Page->MultiColumnListOptionsPosition)) { ?>
    <div class="card-header">
        <div class="ew-multi-column-list-option ew-<?= $Page->MultiColumnListOptionsPosition ?>">
<?php
// Render list options (body, bottom)
$Page->ListOptions->Tag = "div";
$Page->ListOptions->render("body", $Page->MultiColumnListOptionsPosition, $Page->RowCount);
?>
        </div><!-- /.ew-multi-column-list-option -->
    </div>
    <?php } ?>
    <div class="card-body">
    <?php if ($Page->ID->Visible) { // ID ?>
        <?php if ($Page->RowType == ROWTYPE_VIEW) { // View record ?>
        <div class="row cars_ID">
            <div class="col col-sm-4 cars_ID"><?= $Page->renderFieldHeader($Page->ID) ?></div>
            <div class="col col-sm-8"><div<?= $Page->ID->cellAttributes() ?>>
<span<?= $Page->ID->viewAttributes() ?>>
<?= $Page->ID->getViewValue() ?></span>
</div></div>
        </div>
        <?php } else { // Add/edit record ?>
        <div class="row cars_ID">
            <label class="<?= $Page->LeftColumnClass ?>"><?= $Page->ID->caption() ?></label>
            <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->ID->cellAttributes() ?>>
<?php if ($Page->RowType == ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-table="cars" data-field="x_ID" data-hidden="1" data-old name="o<?= $Page->RowIndex ?>_ID" id="o<?= $Page->RowIndex ?>_ID" value="<?= HtmlEncode($Page->ID->OldValue) ?>">
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span<?= $Page->ID->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->ID->getDisplayValue($Page->ID->EditValue))) ?>"></span>
<input type="hidden" data-table="cars" data-field="x_ID" data-hidden="1" name="x<?= $Page->RowIndex ?>_ID" id="x<?= $Page->RowIndex ?>_ID" value="<?= HtmlEncode($Page->ID->CurrentValue) ?>">
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_VIEW) { // View record ?>
<span<?= $Page->ID->viewAttributes() ?>>
<?= $Page->ID->getViewValue() ?></span>
<?php } ?>
</div></div>
        </div>
        <?php } ?>
    <?php } ?>
    <?php if ($Page->Trademark->Visible) { // Trademark ?>
        <?php if ($Page->RowType == ROWTYPE_VIEW) { // View record ?>
        <div class="row cars_Trademark">
            <div class="col col-sm-4 cars_Trademark"><?= $Page->renderFieldHeader($Page->Trademark) ?></div>
            <div class="col col-sm-8"><div<?= $Page->Trademark->cellAttributes() ?>>
<span<?= $Page->Trademark->viewAttributes() ?>>
<?= $Page->Trademark->getViewValue() ?></span>
</div></div>
        </div>
        <?php } else { // Add/edit record ?>
        <div class="row cars_Trademark">
            <label class="<?= $Page->LeftColumnClass ?>"><?= $Page->Trademark->caption() ?></label>
            <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Trademark->cellAttributes() ?>>
<?php if ($Page->RowType == ROWTYPE_ADD) { // Add record ?>
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
<input type="hidden" data-table="cars" data-field="x_Trademark" data-hidden="1" data-old name="o<?= $Page->RowIndex ?>_Trademark" id="o<?= $Page->RowIndex ?>_Trademark" value="<?= HtmlEncode($Page->Trademark->OldValue) ?>">
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_EDIT) { // Edit record ?>
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
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_VIEW) { // View record ?>
<span<?= $Page->Trademark->viewAttributes() ?>>
<?= $Page->Trademark->getViewValue() ?></span>
<?php } ?>
</div></div>
        </div>
        <?php } ?>
    <?php } ?>
    <?php if ($Page->Model->Visible) { // Model ?>
        <?php if ($Page->RowType == ROWTYPE_VIEW) { // View record ?>
        <div class="row cars_Model">
            <div class="col col-sm-4 cars_Model"><?= $Page->renderFieldHeader($Page->Model) ?></div>
            <div class="col col-sm-8"><div<?= $Page->Model->cellAttributes() ?>>
<span<?= $Page->Model->viewAttributes() ?>>
<?php if (!EmptyString($Page->Model->TooltipValue) && $Page->Model->linkAttributes() != "") { ?>
<a<?= $Page->Model->linkAttributes() ?>><?= $Page->Model->getViewValue() ?></a>
<?php } else { ?>
<?= $Page->Model->getViewValue() ?>
<?php } ?>
<span id="tt_cars_x<?= $Page->RowCount ?>_Model" class="d-none">
<?= $Page->Model->TooltipValue ?>
</span></span>
</div></div>
        </div>
        <?php } else { // Add/edit record ?>
        <div class="row cars_Model">
            <label class="<?= $Page->LeftColumnClass ?>"><?= $Page->Model->caption() ?></label>
            <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Model->cellAttributes() ?>>
<?php if ($Page->RowType == ROWTYPE_ADD) { // Add record ?>
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
<input type="hidden" data-table="cars" data-field="x_Model" data-hidden="1" data-old name="o<?= $Page->RowIndex ?>_Model" id="o<?= $Page->RowIndex ?>_Model" value="<?= HtmlEncode($Page->Model->OldValue) ?>">
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_EDIT) { // Edit record ?>
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
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_VIEW) { // View record ?>
<span<?= $Page->Model->viewAttributes() ?>>
<?php if (!EmptyString($Page->Model->TooltipValue) && $Page->Model->linkAttributes() != "") { ?>
<a<?= $Page->Model->linkAttributes() ?>><?= $Page->Model->getViewValue() ?></a>
<?php } else { ?>
<?= $Page->Model->getViewValue() ?>
<?php } ?>
<span id="tt_cars_x<?= $Page->RowCount ?>_Model" class="d-none">
<?= $Page->Model->TooltipValue ?>
</span></span>
<?php } ?>
</div></div>
        </div>
        <?php } ?>
    <?php } ?>
    <?php if ($Page->HP->Visible) { // HP ?>
        <?php if ($Page->RowType == ROWTYPE_VIEW) { // View record ?>
        <div class="row cars_HP">
            <div class="col col-sm-4 cars_HP"><?= $Page->renderFieldHeader($Page->HP) ?></div>
            <div class="col col-sm-8"><div<?= $Page->HP->cellAttributes() ?>>
<span<?= $Page->HP->viewAttributes() ?>>
<?= $Page->HP->getViewValue() ?></span>
</div></div>
        </div>
        <?php } else { // Add/edit record ?>
        <div class="row cars_HP">
            <label class="<?= $Page->LeftColumnClass ?>"><?= $Page->HP->caption() ?></label>
            <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->HP->cellAttributes() ?>>
<?php if ($Page->RowType == ROWTYPE_ADD) { // Add record ?>
<input type="<?= $Page->HP->getInputTextType() ?>" name="x<?= $Page->RowIndex ?>_HP" id="x<?= $Page->RowIndex ?>_HP" data-table="cars" data-field="x_HP" value="<?= $Page->HP->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->HP->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->HP->formatPattern()) ?>"<?= $Page->HP->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->HP->getErrorMessage() ?></div>
<input type="hidden" data-table="cars" data-field="x_HP" data-hidden="1" data-old name="o<?= $Page->RowIndex ?>_HP" id="o<?= $Page->RowIndex ?>_HP" value="<?= HtmlEncode($Page->HP->OldValue) ?>">
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_EDIT) { // Edit record ?>
<input type="<?= $Page->HP->getInputTextType() ?>" name="x<?= $Page->RowIndex ?>_HP" id="x<?= $Page->RowIndex ?>_HP" data-table="cars" data-field="x_HP" value="<?= $Page->HP->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->HP->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->HP->formatPattern()) ?>"<?= $Page->HP->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->HP->getErrorMessage() ?></div>
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_VIEW) { // View record ?>
<span<?= $Page->HP->viewAttributes() ?>>
<?= $Page->HP->getViewValue() ?></span>
<?php } ?>
</div></div>
        </div>
        <?php } ?>
    <?php } ?>
    <?php if ($Page->Cylinders->Visible) { // Cylinders ?>
        <?php if ($Page->RowType == ROWTYPE_VIEW) { // View record ?>
        <div class="row cars_Cylinders">
            <div class="col col-sm-4 cars_Cylinders"><?= $Page->renderFieldHeader($Page->Cylinders) ?></div>
            <div class="col col-sm-8"><div<?= $Page->Cylinders->cellAttributes() ?>>
<span<?= $Page->Cylinders->viewAttributes() ?>>
<?= $Page->Cylinders->getViewValue() ?></span>
</div></div>
        </div>
        <?php } else { // Add/edit record ?>
        <div class="row cars_Cylinders">
            <label class="<?= $Page->LeftColumnClass ?>"><?= $Page->Cylinders->caption() ?></label>
            <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Cylinders->cellAttributes() ?>>
<?php if ($Page->RowType == ROWTYPE_ADD) { // Add record ?>
<input type="<?= $Page->Cylinders->getInputTextType() ?>" name="x<?= $Page->RowIndex ?>_Cylinders" id="x<?= $Page->RowIndex ?>_Cylinders" data-table="cars" data-field="x_Cylinders" value="<?= $Page->Cylinders->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->Cylinders->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Cylinders->formatPattern()) ?>"<?= $Page->Cylinders->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->Cylinders->getErrorMessage() ?></div>
<input type="hidden" data-table="cars" data-field="x_Cylinders" data-hidden="1" data-old name="o<?= $Page->RowIndex ?>_Cylinders" id="o<?= $Page->RowIndex ?>_Cylinders" value="<?= HtmlEncode($Page->Cylinders->OldValue) ?>">
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_EDIT) { // Edit record ?>
<input type="<?= $Page->Cylinders->getInputTextType() ?>" name="x<?= $Page->RowIndex ?>_Cylinders" id="x<?= $Page->RowIndex ?>_Cylinders" data-table="cars" data-field="x_Cylinders" value="<?= $Page->Cylinders->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->Cylinders->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Cylinders->formatPattern()) ?>"<?= $Page->Cylinders->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->Cylinders->getErrorMessage() ?></div>
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_VIEW) { // View record ?>
<span<?= $Page->Cylinders->viewAttributes() ?>>
<?= $Page->Cylinders->getViewValue() ?></span>
<?php } ?>
</div></div>
        </div>
        <?php } ?>
    <?php } ?>
    <?php if ($Page->Price->Visible) { // Price ?>
        <?php if ($Page->RowType == ROWTYPE_VIEW) { // View record ?>
        <div class="row cars_Price">
            <div class="col col-sm-4 cars_Price"><?= $Page->renderFieldHeader($Page->Price) ?></div>
            <div class="col col-sm-8"><div<?= $Page->Price->cellAttributes() ?>>
<span<?= $Page->Price->viewAttributes() ?>>
<?= $Page->Price->getViewValue() ?></span>
</div></div>
        </div>
        <?php } else { // Add/edit record ?>
        <div class="row cars_Price">
            <label class="<?= $Page->LeftColumnClass ?>"><?= $Page->Price->caption() ?></label>
            <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Price->cellAttributes() ?>>
<?php if ($Page->RowType == ROWTYPE_ADD) { // Add record ?>
<input type="<?= $Page->Price->getInputTextType() ?>" name="x<?= $Page->RowIndex ?>_Price" id="x<?= $Page->RowIndex ?>_Price" data-table="cars" data-field="x_Price" value="<?= $Page->Price->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->Price->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Price->formatPattern()) ?>"<?= $Page->Price->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->Price->getErrorMessage() ?></div>
<input type="hidden" data-table="cars" data-field="x_Price" data-hidden="1" data-old name="o<?= $Page->RowIndex ?>_Price" id="o<?= $Page->RowIndex ?>_Price" value="<?= HtmlEncode($Page->Price->OldValue) ?>">
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_EDIT) { // Edit record ?>
<input type="<?= $Page->Price->getInputTextType() ?>" name="x<?= $Page->RowIndex ?>_Price" id="x<?= $Page->RowIndex ?>_Price" data-table="cars" data-field="x_Price" value="<?= $Page->Price->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->Price->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Price->formatPattern()) ?>"<?= $Page->Price->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->Price->getErrorMessage() ?></div>
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_VIEW) { // View record ?>
<span<?= $Page->Price->viewAttributes() ?>>
<?= $Page->Price->getViewValue() ?></span>
<?php } ?>
</div></div>
        </div>
        <?php } ?>
    <?php } ?>
    <?php if ($Page->Picture->Visible) { // Picture ?>
        <?php if ($Page->RowType == ROWTYPE_VIEW) { // View record ?>
        <div class="row cars_Picture">
            <div class="col col-sm-4 cars_Picture"><?= $Page->renderFieldHeader($Page->Picture) ?></div>
            <div class="col col-sm-8"><div<?= $Page->Picture->cellAttributes() ?>>
<span>
<?= GetFileViewTag($Page->Picture, $Page->Picture->getViewValue(), false) ?>
</span>
</div></div>
        </div>
        <?php } else { // Add/edit record ?>
        <div class="row cars_Picture">
            <label class="<?= $Page->LeftColumnClass ?>"><?= $Page->Picture->caption() ?></label>
            <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Picture->cellAttributes() ?>>
<?php if ($Page->RowType == ROWTYPE_ADD) { // Add record ?>
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
<input type="hidden" data-table="cars" data-field="x_Picture" data-hidden="1" data-old name="o<?= $Page->RowIndex ?>_Picture" id="o<?= $Page->RowIndex ?>_Picture" value="<?= HtmlEncode($Page->Picture->OldValue) ?>">
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_EDIT) { // Edit record ?>
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
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_VIEW) { // View record ?>
<span>
<?= GetFileViewTag($Page->Picture, $Page->Picture->getViewValue(), false) ?>
</span>
<?php } ?>
</div></div>
        </div>
        <?php } ?>
    <?php } ?>
    <?php if ($Page->Doors->Visible) { // Doors ?>
        <?php if ($Page->RowType == ROWTYPE_VIEW) { // View record ?>
        <div class="row cars_Doors">
            <div class="col col-sm-4 cars_Doors"><?= $Page->renderFieldHeader($Page->Doors) ?></div>
            <div class="col col-sm-8"><div<?= $Page->Doors->cellAttributes() ?>>
<span<?= $Page->Doors->viewAttributes() ?>>
<?= $Page->Doors->getViewValue() ?></span>
</div></div>
        </div>
        <?php } else { // Add/edit record ?>
        <div class="row cars_Doors">
            <label class="<?= $Page->LeftColumnClass ?>"><?= $Page->Doors->caption() ?></label>
            <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Doors->cellAttributes() ?>>
<?php if ($Page->RowType == ROWTYPE_ADD) { // Add record ?>
<input type="<?= $Page->Doors->getInputTextType() ?>" name="x<?= $Page->RowIndex ?>_Doors" id="x<?= $Page->RowIndex ?>_Doors" data-table="cars" data-field="x_Doors" value="<?= $Page->Doors->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->Doors->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Doors->formatPattern()) ?>"<?= $Page->Doors->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->Doors->getErrorMessage() ?></div>
<input type="hidden" data-table="cars" data-field="x_Doors" data-hidden="1" data-old name="o<?= $Page->RowIndex ?>_Doors" id="o<?= $Page->RowIndex ?>_Doors" value="<?= HtmlEncode($Page->Doors->OldValue) ?>">
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_EDIT) { // Edit record ?>
<input type="<?= $Page->Doors->getInputTextType() ?>" name="x<?= $Page->RowIndex ?>_Doors" id="x<?= $Page->RowIndex ?>_Doors" data-table="cars" data-field="x_Doors" value="<?= $Page->Doors->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->Doors->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Doors->formatPattern()) ?>"<?= $Page->Doors->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->Doors->getErrorMessage() ?></div>
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_VIEW) { // View record ?>
<span<?= $Page->Doors->viewAttributes() ?>>
<?= $Page->Doors->getViewValue() ?></span>
<?php } ?>
</div></div>
        </div>
        <?php } ?>
    <?php } ?>
    <?php if ($Page->Torque->Visible) { // Torque ?>
        <?php if ($Page->RowType == ROWTYPE_VIEW) { // View record ?>
        <div class="row cars_Torque">
            <div class="col col-sm-4 cars_Torque"><?= $Page->renderFieldHeader($Page->Torque) ?></div>
            <div class="col col-sm-8"><div<?= $Page->Torque->cellAttributes() ?>>
<span<?= $Page->Torque->viewAttributes() ?>>
<?= $Page->Torque->getViewValue() ?></span>
</div></div>
        </div>
        <?php } else { // Add/edit record ?>
        <div class="row cars_Torque">
            <label class="<?= $Page->LeftColumnClass ?>"><?= $Page->Torque->caption() ?></label>
            <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Torque->cellAttributes() ?>>
<?php if ($Page->RowType == ROWTYPE_ADD) { // Add record ?>
<input type="<?= $Page->Torque->getInputTextType() ?>" name="x<?= $Page->RowIndex ?>_Torque" id="x<?= $Page->RowIndex ?>_Torque" data-table="cars" data-field="x_Torque" value="<?= $Page->Torque->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->Torque->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Torque->formatPattern()) ?>"<?= $Page->Torque->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->Torque->getErrorMessage() ?></div>
<input type="hidden" data-table="cars" data-field="x_Torque" data-hidden="1" data-old name="o<?= $Page->RowIndex ?>_Torque" id="o<?= $Page->RowIndex ?>_Torque" value="<?= HtmlEncode($Page->Torque->OldValue) ?>">
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_EDIT) { // Edit record ?>
<input type="<?= $Page->Torque->getInputTextType() ?>" name="x<?= $Page->RowIndex ?>_Torque" id="x<?= $Page->RowIndex ?>_Torque" data-table="cars" data-field="x_Torque" value="<?= $Page->Torque->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->Torque->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Torque->formatPattern()) ?>"<?= $Page->Torque->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->Torque->getErrorMessage() ?></div>
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_VIEW) { // View record ?>
<span<?= $Page->Torque->viewAttributes() ?>>
<?= $Page->Torque->getViewValue() ?></span>
<?php } ?>
</div></div>
        </div>
        <?php } ?>
    <?php } ?>
    </div><!-- /.card-body -->
<?php if (!$Page->isExport()) { ?>
    <?php if (StartsText("bottom", $Page->MultiColumnListOptionsPosition)) { ?>
    <div class="card-footer">
        <div class="ew-multi-column-list-option ew-<?= $Page->MultiColumnListOptionsPosition ?>">
<?php
// Render list options (body, bottom)
$Page->ListOptions->Tag = "div";
$Page->ListOptions->render("body", $Page->MultiColumnListOptionsPosition, $Page->RowCount);
?>
        </div><!-- /.ew-multi-column-list-option -->
    </div><!-- /.card-footer -->
    <?php } ?>
<?php } ?>
</div><!-- /.card -->
<?php } // End normal card layout ?>
</div><!-- /.col-* -->
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
</div><!-- /.ew-multi-column-row -->
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
<div>
<?php if (!$Page->isGridAdd() && !($Page->isGridEdit() && $Page->ModalGridEdit) && !$Page->isMultiEdit()) { ?>
<?= $Page->Pager->render() ?>
<?php } ?>
<div class="ew-list-other-options">
<?php $Page->OtherOptions->render("body", "bottom") ?>
</div>
</div>
<?php } ?>
