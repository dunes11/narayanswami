<?php

namespace PHPMaker2023\demo2023;

// Page object
$Cars2List = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { cars2: currentTable } });
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
        .setFormKeyCountName("<?= $Page->FormKeyCountName ?>")
        .build();
    window[form.id] = form;
    currentForm = form;
    loadjs.done(form.id);
});
</script>
<script>
window.Tabulator || loadjs([
    ew.PATH_BASE + "js/tabulator.min.js?v=19.13.4",
    ew.PATH_BASE + "css/<?= CssFile("tabulator_bootstrap5.css", false) ?>?v=19.13.4"
], "import");
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<?php } ?>
<?php if (!$Page->isExport()) { ?>
<div class="btn-toolbar ew-toolbar">
<?php if ($Page->TotalRecords > 0 && $Page->ExportOptions->visible()) { ?>
<?php $Page->ExportOptions->render("body") ?>
<?php } ?>
<?php if ($Page->ImportOptions->visible()) { ?>
<?php $Page->ImportOptions->render("body") ?>
<?php } ?>
<?php if ($Page->SearchOptions->visible()) { ?>
<?php $Page->SearchOptions->render("body") ?>
<?php } ?>
<?php if ($Page->FilterOptions->visible()) { ?>
<?php $Page->FilterOptions->render("body") ?>
<?php } ?>
</div>
<?php } ?>
<?php if ($Page->ShowCurrentFilter) { ?>
<?php $Page->showFilterList() ?>
<?php } ?>
<?php if (!$Page->IsModal) { ?>
<form name="fcars2srch" id="fcars2srch" class="ew-form ew-ext-search-form" action="<?= CurrentPageUrl(false) ?>" novalidate autocomplete="on">
<div id="fcars2srch_search_panel" class="mb-2 mb-sm-0 <?= $Page->SearchPanelClass ?>"><!-- .ew-search-panel -->
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { cars2: currentTable } });
var currentForm;
var fcars2srch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery,
        fields = currentTable.fields;

    // Form object for search
    let form = new ew.FormBuilder()
        .setId("fcars2srch")
        .setPageId("list")

        // Add fields
        .addFields([
            ["ID", [], fields.ID.isInvalid],
            ["Trademark", [], fields.Trademark.isInvalid],
            ["Model", [], fields.Model.isInvalid],
            ["HP", [], fields.HP.isInvalid],
            ["Cylinders", [], fields.Cylinders.isInvalid],
            ["Price", [], fields.Price.isInvalid],
            ["Picture", [], fields.Picture.isInvalid],
            ["Doors", [], fields.Doors.isInvalid],
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
        .setLists({
            "Trademark": <?= $Page->Trademark->toClientList($Page) ?>,
            "Model": <?= $Page->Model->toClientList($Page) ?>,
        })

        // Filters
        .setFilterList(<?= $Page->getFilterList() ?>)
        .build();
    window[form.id] = form;
    currentSearchForm = form;
    loadjs.done(form.id);
});
</script>
<input type="hidden" name="cmd" value="search">
<?php if ($Security->canSearch()) { ?>
<?php if (!$Page->isExport() && !($Page->CurrentAction && $Page->CurrentAction != "search") && $Page->hasSearchFields()) { ?>
<div class="ew-extended-search container-fluid ps-2">
<div class="row mb-0<?= ($Page->SearchFieldsPerRow > 0) ? " row-cols-sm-" . $Page->SearchFieldsPerRow : "" ?>">
<?php
// Render search row
$Page->RowType = ROWTYPE_SEARCH;
$Page->resetAttributes();
$Page->renderRow();
?>
<?php if ($Page->Trademark->Visible) { // Trademark ?>
<?php
if (!$Page->Trademark->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_Trademark" class="col-sm-auto d-sm-flex align-items-start mb-3 px-0 pe-sm-2<?= $Page->Trademark->UseFilter ? " ew-filter-field" : "" ?>">
        <div class="d-flex my-1 my-sm-0">
            <label for="x_Trademark" class="ew-search-caption ew-label"><?= $Page->Trademark->caption() ?></label>
            <div class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_Trademark" id="z_Trademark" value="LIKE">
</div>
        </div>
        <div id="el_cars2_Trademark" class="ew-search-field">
<input type="<?= $Page->Trademark->getInputTextType() ?>" name="x_Trademark" id="x_Trademark" data-table="cars2" data-field="x_Trademark" value="<?= $Page->Trademark->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->Trademark->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Trademark->formatPattern()) ?>"<?= $Page->Trademark->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->Trademark->getErrorMessage(false) ?></div>
<?= $Page->Trademark->Lookup->getParamTag($Page, "p_x_Trademark") ?>
</div>
        <div class="d-flex my-1 my-sm-0">
        </div><!-- /.ew-search-field -->
    </div><!-- /.col-sm-auto -->
<?php } ?>
<?php if ($Page->Model->Visible) { // Model ?>
<?php
if (!$Page->Model->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_Model" class="col-sm-auto d-sm-flex align-items-start mb-3 px-0 pe-sm-2<?= $Page->Model->UseFilter ? " ew-filter-field" : "" ?>">
        <div class="d-flex my-1 my-sm-0">
            <label for="x_Model" class="ew-search-caption ew-label"><?= $Page->Model->caption() ?></label>
            <div class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_Model" id="z_Model" value="LIKE">
</div>
        </div>
        <div id="el_cars2_Model" class="ew-search-field">
<input type="<?= $Page->Model->getInputTextType() ?>" name="x_Model" id="x_Model" data-table="cars2" data-field="x_Model" value="<?= $Page->Model->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->Model->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Model->formatPattern()) ?>"<?= $Page->Model->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->Model->getErrorMessage(false) ?></div>
<?= $Page->Model->Lookup->getParamTag($Page, "p_x_Model") ?>
</div>
        <div class="d-flex my-1 my-sm-0">
        </div><!-- /.ew-search-field -->
    </div><!-- /.col-sm-auto -->
<?php } ?>
</div><!-- /.row -->
<div class="row mb-0">
    <div class="col-sm-auto px-0 pe-sm-2">
        <div class="ew-basic-search input-group">
            <input type="search" name="<?= Config("TABLE_BASIC_SEARCH") ?>" id="<?= Config("TABLE_BASIC_SEARCH") ?>" class="form-control ew-basic-search-keyword" value="<?= HtmlEncode($Page->BasicSearch->getKeyword()) ?>" placeholder="<?= HtmlEncode($Language->phrase("Search")) ?>" aria-label="<?= HtmlEncode($Language->phrase("Search")) ?>">
            <input type="hidden" name="<?= Config("TABLE_BASIC_SEARCH_TYPE") ?>" id="<?= Config("TABLE_BASIC_SEARCH_TYPE") ?>" class="ew-basic-search-type" value="<?= HtmlEncode($Page->BasicSearch->getType()) ?>">
            <button type="button" data-bs-toggle="dropdown" class="btn btn-outline-secondary dropdown-toggle dropdown-toggle-split" aria-haspopup="true" aria-expanded="false">
                <span id="searchtype"><?= $Page->BasicSearch->getTypeNameShort() ?></span>
            </button>
            <div class="dropdown-menu dropdown-menu-end">
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "" ? " active" : "" ?>" form="fcars2srch" data-ew-action="search-type"><?= $Language->phrase("QuickSearchAuto") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "=" ? " active" : "" ?>" form="fcars2srch" data-ew-action="search-type" data-search-type="="><?= $Language->phrase("QuickSearchExact") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "AND" ? " active" : "" ?>" form="fcars2srch" data-ew-action="search-type" data-search-type="AND"><?= $Language->phrase("QuickSearchAll") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "OR" ? " active" : "" ?>" form="fcars2srch" data-ew-action="search-type" data-search-type="OR"><?= $Language->phrase("QuickSearchAny") ?></button>
            </div>
        </div>
    </div>
    <div class="col-sm-auto mb-3">
        <button class="btn btn-primary" name="btn-submit" id="btn-submit" type="submit"><?= $Language->phrase("SearchBtn") ?></button>
    </div>
</div>
</div><!-- /.ew-extended-search -->
<?php } ?>
<?php } ?>
</div><!-- /.ew-search-panel -->
</form>
<?php } ?>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<main class="list<?= ($Page->TotalRecords == 0 && !$Page->isAdd()) ? " ew-no-record" : "" ?>">
<div id="ew-list">
<?php if ($Page->TotalRecords > 0 || $Page->CurrentAction) { ?>
<div class="card ew-card ew-grid<?= $Page->isAddOrEdit() ? " ew-grid-add-edit" : "" ?> <?= $Page->TableGridClass ?>">
<form name="<?= $Page->FormName ?>" id="<?= $Page->FormName ?>" class="ew-form ew-list-form" action="<?= $Page->PageAction ?>" method="post" novalidate autocomplete="on">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="cars2">
<?php if ($Page->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div id="gmp_cars2" class="card-body ew-grid-middle-panel <?= $Page->TableContainerClass ?>" style="<?= $Page->TableContainerStyle ?>">
<?php if ($Page->TotalRecords > 0 || $Page->isGridEdit() || $Page->isMultiEdit()) { ?>
<table id="tbl_cars2list" class="<?= $Page->TableClass ?>"><!-- .ew-table -->
<thead>
    <tr class="ew-table-header">
<?php
// Header row
$Page->RowType = ROWTYPE_HEADER;

// Render list options
$Page->renderListOptions();

// Render list options (header, left)
$Page->ListOptions->render("header", "left", "", "block", $Page->TableVar, "cars2list");
?>
<?php if ($Page->ID->Visible) { // ID ?>
        <th data-name="ID" class="<?= $Page->ID->headerCellClass() ?>"><div id="elh_cars2_ID" class="cars2_ID"><?= $Page->renderFieldHeader($Page->ID) ?></div></th>
<?php } ?>
<?php if ($Page->Trademark->Visible) { // Trademark ?>
        <th data-name="Trademark" class="<?= $Page->Trademark->headerCellClass() ?>"><div id="elh_cars2_Trademark" class="cars2_Trademark"><?= $Page->renderFieldHeader($Page->Trademark) ?></div></th>
<?php } ?>
<?php if ($Page->Model->Visible) { // Model ?>
        <th data-name="Model" class="<?= $Page->Model->headerCellClass() ?>"><div id="elh_cars2_Model" class="cars2_Model"><?= $Page->renderFieldHeader($Page->Model) ?></div></th>
<?php } ?>
<?php if ($Page->HP->Visible) { // HP ?>
        <th data-name="HP" class="<?= $Page->HP->headerCellClass() ?>"><div id="elh_cars2_HP" class="cars2_HP"><?= $Page->renderFieldHeader($Page->HP) ?></div></th>
<?php } ?>
<?php if ($Page->Cylinders->Visible) { // Cylinders ?>
        <th data-name="Cylinders" class="<?= $Page->Cylinders->headerCellClass() ?>"><div id="elh_cars2_Cylinders" class="cars2_Cylinders"><?= $Page->renderFieldHeader($Page->Cylinders) ?></div></th>
<?php } ?>
<?php if ($Page->Price->Visible) { // Price ?>
        <th data-name="Price" class="<?= $Page->Price->headerCellClass() ?>"><div id="elh_cars2_Price" class="cars2_Price"><?= $Page->renderFieldHeader($Page->Price) ?></div></th>
<?php } ?>
<?php if ($Page->Picture->Visible) { // Picture ?>
        <th data-name="Picture" class="<?= $Page->Picture->headerCellClass() ?>"><div id="elh_cars2_Picture" class="cars2_Picture"><?= $Page->renderFieldHeader($Page->Picture) ?></div></th>
<?php } ?>
<?php if ($Page->Doors->Visible) { // Doors ?>
        <th data-name="Doors" class="<?= $Page->Doors->headerCellClass() ?>"><div id="elh_cars2_Doors" class="cars2_Doors"><?= $Page->renderFieldHeader($Page->Doors) ?></div></th>
<?php } ?>
<?php if ($Page->Torque->Visible) { // Torque ?>
        <th data-name="Torque" class="<?= $Page->Torque->headerCellClass() ?>"><div id="elh_cars2_Torque" class="cars2_Torque"><?= $Page->renderFieldHeader($Page->Torque) ?></div></th>
<?php } ?>
<?php
// Render list options (header, right)
$Page->ListOptions->render("header", "right", "", "block", $Page->TableVar, "cars2list");
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
?>
    <tr <?= $Page->rowAttributes() ?>>
<?php
// Render list options (body, left)
$Page->ListOptions->render("body", "left", $Page->RowCount, "block", $Page->TableVar, "cars2list");
?>
    <?php if ($Page->ID->Visible) { // ID ?>
        <td data-name="ID"<?= $Page->ID->cellAttributes() ?>>
<template id="tpx<?= $Page->RowCount ?>_cars2_ID"><span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_cars2_ID" class="el_cars2_ID">
<span<?= $Page->ID->viewAttributes() ?>>
<?= $Page->ID->getViewValue() ?></span>
</span></template>
</td>
    <?php } ?>
    <?php if ($Page->Trademark->Visible) { // Trademark ?>
        <td data-name="Trademark"<?= $Page->Trademark->cellAttributes() ?>>
<template id="tpx<?= $Page->RowCount ?>_cars2_Trademark"><span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_cars2_Trademark" class="el_cars2_Trademark">
<span<?= $Page->Trademark->viewAttributes() ?>>
<?= $Page->Trademark->getViewValue() ?></span>
</span></template>
</td>
    <?php } ?>
    <?php if ($Page->Model->Visible) { // Model ?>
        <td data-name="Model"<?= $Page->Model->cellAttributes() ?>>
<template id="tpx<?= $Page->RowCount ?>_cars2_Model"><span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_cars2_Model" class="el_cars2_Model">
<span<?= $Page->Model->viewAttributes() ?>>
<?php if (!EmptyString($Page->Model->TooltipValue) && $Page->Model->linkAttributes() != "") { ?>
<a<?= $Page->Model->linkAttributes() ?>><?= $Page->Model->getViewValue() ?></a>
<?php } else { ?>
<?= $Page->Model->getViewValue() ?>
<?php } ?>
<span id="tt_cars2_x<?= $Page->RowCount ?>_Model" class="d-none">
<?= $Page->Model->TooltipValue ?>
</span></span>
</span></template>
</td>
    <?php } ?>
    <?php if ($Page->HP->Visible) { // HP ?>
        <td data-name="HP"<?= $Page->HP->cellAttributes() ?>>
<template id="tpx<?= $Page->RowCount ?>_cars2_HP"><span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_cars2_HP" class="el_cars2_HP">
<span<?= $Page->HP->viewAttributes() ?>>
<?= $Page->HP->getViewValue() ?></span>
</span></template>
</td>
    <?php } ?>
    <?php if ($Page->Cylinders->Visible) { // Cylinders ?>
        <td data-name="Cylinders"<?= $Page->Cylinders->cellAttributes() ?>>
<template id="tpx<?= $Page->RowCount ?>_cars2_Cylinders"><span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_cars2_Cylinders" class="el_cars2_Cylinders">
<span<?= $Page->Cylinders->viewAttributes() ?>>
<?= $Page->Cylinders->getViewValue() ?></span>
</span></template>
</td>
    <?php } ?>
    <?php if ($Page->Price->Visible) { // Price ?>
        <td data-name="Price"<?= $Page->Price->cellAttributes() ?>>
<template id="tpx<?= $Page->RowCount ?>_cars2_Price"><span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_cars2_Price" class="el_cars2_Price">
<span<?= $Page->Price->viewAttributes() ?>>
<?= $Page->Price->getViewValue() ?></span>
</span></template>
</td>
    <?php } ?>
    <?php if ($Page->Picture->Visible) { // Picture ?>
        <td data-name="Picture"<?= $Page->Picture->cellAttributes() ?>>
<template id="tpx<?= $Page->RowCount ?>_cars2_Picture"><span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_cars2_Picture" class="el_cars2_Picture">
<span>
<?= GetFileViewTag($Page->Picture, $Page->Picture->getViewValue(), false) ?>
</span>
</span></template>
</td>
    <?php } ?>
    <?php if ($Page->Doors->Visible) { // Doors ?>
        <td data-name="Doors"<?= $Page->Doors->cellAttributes() ?>>
<template id="tpx<?= $Page->RowCount ?>_cars2_Doors"><span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_cars2_Doors" class="el_cars2_Doors">
<span<?= $Page->Doors->viewAttributes() ?>>
<?= $Page->Doors->getViewValue() ?></span>
</span></template>
</td>
    <?php } ?>
    <?php if ($Page->Torque->Visible) { // Torque ?>
        <td data-name="Torque"<?= $Page->Torque->cellAttributes() ?>>
<template id="tpx<?= $Page->RowCount ?>_cars2_Torque"><span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_cars2_Torque" class="el_cars2_Torque">
<span<?= $Page->Torque->viewAttributes() ?>>
<?= $Page->Torque->getViewValue() ?></span>
</span></template>
</td>
    <?php } ?>
<?php
// Render list options (body, right)
$Page->ListOptions->render("body", "right", $Page->RowCount, "block", $Page->TableVar, "cars2list");
?>
    </tr>
<?php
    }
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
</table><!-- /.ew-table -->
<?php } ?>
<div id="tpd_cars2list" class="ew-custom-template"></div>
<template id="tpm_cars2list">
<div id="ct_Cars2List"><?php if ($Page->RowCount > 0) { ?>
<table cellspacing="0" class="table table-bordered table-sm ew-table">
	<thead>
	<tr class="ew-table-header">
	<th class="ew-slot" id="tpoh_cars2" data-rowspan="2"></th>
	<td rowspan="2"><slot class="ew-slot" name="tpc_cars2_Picture"></slot></td>
	<td><slot class="ew-slot" name="tpc_cars2_ID"></slot></td>
	<td><slot class="ew-slot" name="tpc_cars2_Trademark"></slot></td>
	<td><slot class="ew-slot" name="tpc_cars2_Model"></slot></td>
	<td><slot class="ew-slot" name="tpc_cars2_HP"></slot></td>
	</tr>
	<tr class="ew-table-header">
	<td><slot class="ew-slot" name="tpc_Liter"></slot></td>
	<td><slot class="ew-slot" name="tpc_cars2_Cylinders"></slot></td>
	<td><slot class="ew-slot" name="tpc_cars2_Torque"></slot></td>
	<td><slot class="ew-slot" name="tpc_cars2_Price"></slot></td>
	</tr>
	</thead>
	<tbody>
<?php for ($i = $Page->StartRowCount; $i <= $Page->RowCount; $i++) { ?>
<tr<?= @$Page->Attrs[$i]['row_attrs'] ?>>
	<td class="ew-slot" id="tpob<?= $i ?>_cars2" data-rowspan="2"></td>
	<td rowspan="2"><slot class="ew-slot" name="tpx<?= $i ?>_cars2_Picture"></slot></td>
	<td><slot class="ew-slot" name="tpx<?= $i ?>_cars2_ID"></slot></td>
	<td><slot class="ew-slot" name="tpx<?= $i ?>_cars2_Trademark"></slot></td>
	<td><slot class="ew-slot" name="tpx<?= $i ?>_cars2_Model"></slot></td>
	<td><slot class="ew-slot" name="tpx<?= $i ?>_cars2_HP"></slot></td>
</tr>
<tr<?= @$Page->Attrs[$i]['row_attrs'] ?>>
	<td><slot class="ew-slot" name="tpx_Liter"></slot></td>
	<td><slot class="ew-slot" name="tpx<?= $i ?>_cars2_Cylinders"></slot></td>
	<td><slot class="ew-slot" name="tpx<?= $i ?>_cars2_Torque"></slot></td>
	<td><slot class="ew-slot" name="tpx<?= $i ?>_cars2_Price"></slot></td>
</tr>
<?php } ?>
	</tbody>
	<!-- <?php if ($Page->TotalRecords > 0 && !$cars2->isGridAdd() && !$cars2->isGridEdit() && !$cars2->isMultiEdit()) { ?>
<tfoot><tr class="ew-table-footer"><td class="ew-slot" id="tpof_cars2" data-rowspan="1"></td><td><slot class="ew-slot" name="tpg_MyField1"></slot></td><td>&nbsp;</td></tr></tfoot>
<?php } ?> -->
</table>
<?php } ?>
</div>
</template>
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
<script class="ew-apply-template">
loadjs.ready(ew.applyTemplateId, function() {
    ew.templateData = { rows: <?= JsonEncode($Page->Rows) ?> };
    ew.applyTemplate("tpd_cars2list", "tpm_cars2list", "cars2list", "<?= $Page->Export ?>", "cars2", ew.templateData, <?= $Page->IsModal ? "true" : "false" ?>);
    loadjs.done("customtemplate");
});
</script>
<?php } else { ?>
<div class="ew-list-other-options">
<?php $Page->OtherOptions->render("body") ?>
</div>
<?php } ?>
</div>
</main>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<?php if (!$Page->isExport()) { ?>
<script>
// Field event handlers
loadjs.ready("head", function() {
    ew.addEventHandlers("cars2");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
