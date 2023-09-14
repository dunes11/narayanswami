<?php

namespace PHPMaker2023\demo2023;

// Page object
$SalesByCustomer2Summary = &$Page;
?>
<?php if (!$Page->isExport() && !$Page->DrillDown && !$DashboardReport) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { Sales_By_Customer_2: currentTable } });
var currentPageID = ew.PAGE_ID = "summary";
var currentForm;
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<?php } ?>
<a id="top"></a>
<!-- Content Container -->
<div id="ew-report" class="ew-report container-fluid">
<?php if ($Page->ShowCurrentFilter) { ?>
<?php $Page->showFilterList() ?>
<?php } ?>
<div class="btn-toolbar ew-toolbar">
<?php
if (!$Page->DrillDownInPanel) {
    $Page->ExportOptions->render("body");
    $Page->SearchOptions->render("body");
    $Page->FilterOptions->render("body");
}
?>
</div>
<?php if (!$Page->isExport() && !$Page->DrillDown && !$DashboardReport) { ?>
<form name="fSales_By_Customer_2srch" id="fSales_By_Customer_2srch" class="ew-form ew-ext-search-form" action="<?= CurrentPageUrl(false) ?>" novalidate autocomplete="on">
<div id="fSales_By_Customer_2srch_search_panel" class="mb-2 mb-sm-0 <?= $Page->SearchPanelClass ?>"><!-- .ew-search-panel -->
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { Sales_By_Customer_2: currentTable } });
var currentPageID = ew.PAGE_ID = "summary";
var currentForm;
var fSales_By_Customer_2srch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery,
        fields = currentTable.fields;

    // Form object for search
    let form = new ew.FormBuilder()
        .setId("fSales_By_Customer_2srch")
        .setPageId("summary")

        // Add fields
        .addFields([
            ["CompanyName", [], fields.CompanyName.isInvalid],
            ["OrderID", [], fields.OrderID.isInvalid],
            ["ProductName", [], fields.ProductName.isInvalid],
            ["UnitPrice", [], fields.UnitPrice.isInvalid],
            ["Quantity", [], fields.Quantity.isInvalid],
            ["Discount", [], fields.Discount.isInvalid],
            ["ExtendedPrice", [], fields.ExtendedPrice.isInvalid]
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
            "OrderID": <?= $Page->OrderID->toClientList($Page) ?>,
        })

        // Filters
        .setFilterList(<?= $Page->getFilterList() ?>)
        .build();
    window[form.id] = form;
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
<?php if ($Page->OrderID->Visible) { // OrderID ?>
<?php
if (!$Page->OrderID->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_OrderID" class="col-sm-auto d-sm-flex align-items-start mb-3 px-0 pe-sm-2<?= $Page->OrderID->UseFilter ? " ew-filter-field" : "" ?>">
        <div class="d-flex my-1 my-sm-0">
            <label for="x_OrderID" class="ew-search-caption ew-label"><?= $Page->OrderID->caption() ?></label>
        </div>
        <div id="el_Sales_By_Customer_2_OrderID" class="ew-search-field">
    <select
        id="x_OrderID"
        name="x_OrderID"
        class="form-select ew-select<?= $Page->OrderID->isInvalidClass() ?>"
        <?php if (!$Page->OrderID->IsNativeSelect) { ?>
        data-select2-id="fSales_By_Customer_2srch_x_OrderID"
        <?php } ?>
        data-table="Sales_By_Customer_2"
        data-field="x_OrderID"
        data-value-separator="<?= $Page->OrderID->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->OrderID->getPlaceHolder()) ?>"
        <?= $Page->OrderID->editAttributes() ?>>
        <?= $Page->OrderID->selectOptionListHtml("x_OrderID") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->OrderID->getErrorMessage() ?></div>
<?= $Page->OrderID->Lookup->getParamTag($Page, "p_x_OrderID") ?>
<?php if (!$Page->OrderID->IsNativeSelect) { ?>
<script>
loadjs.ready("fSales_By_Customer_2srch", function() {
    var options = { name: "x_OrderID", selectId: "fSales_By_Customer_2srch_x_OrderID" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fSales_By_Customer_2srch.lists.OrderID?.lookupOptions.length) {
        options.data = { id: "x_OrderID", form: "fSales_By_Customer_2srch" };
    } else {
        options.ajax = { id: "x_OrderID", form: "fSales_By_Customer_2srch", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.Sales_By_Customer_2.fields.OrderID.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</div>
        <div class="d-flex my-1 my-sm-0">
        </div><!-- /.ew-search-field -->
    </div><!-- /.col-sm-auto -->
<?php } ?>
<?php if ($Page->SearchColumnCount > 0) { ?>
   <div class="col-sm-auto mb-3">
       <button class="btn btn-primary" name="btn-submit" id="btn-submit" type="submit"><?= $Language->phrase("SearchBtn") ?></button>
   </div>
<?php } ?>
</div><!-- /.row -->
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
<?php if ($Page->ShowReport) { ?>
<!-- Summary report (begin) -->
<?php if (!$Page->isExport("pdf")) { ?>
<main class="report-summary<?= ($Page->TotalGroups == 0) ? " ew-no-record" : "" ?>">
<?php } ?>
<?php
while ($Page->GroupCount <= count($Page->GroupRecords) && $Page->GroupCount <= $Page->DisplayGroups) {
?>
<?php
    // Show header
    if ($Page->ShowHeader) {
?>
<?php if ($Page->GroupCount > 1) { ?>
</tbody>
</table>
<?php if (!$Page->isExport("pdf")) { ?>
</div>
<!-- /.ew-grid-middle-panel -->
<!-- Report grid (end) -->
<?php } ?>
<?php if ($Page->TotalGroups > 0) { ?>
<?php if (!$Page->isExport() && !($Page->DrillDown && $Page->TotalGroups > 0)) { ?>
<!-- Bottom pager -->
<div class="card-footer ew-grid-lower-panel">
<?= $Page->Pager->render() ?>
</div>
<?php } ?>
<?php } ?>
<?php if (!$Page->isExport("pdf")) { ?>
</div>
<!-- /.ew-grid -->
<?php } ?>
<template id="tpb<?= $Page->GroupCount - 1 ?>_Sales_By_Customer_2"><?= $Page->PageBreakHtml ?></template>
<?php } ?>
<?php if (!$Page->isExport("pdf")) { ?>
<div class="<?= $Page->ReportContainerClass ?>">
<?php } ?>
<?php if (!$Page->isExport() && !($Page->DrillDown && $Page->TotalGroups > 0)) { ?>
<!-- Top pager -->
<div class="card-header ew-grid-upper-panel">
<?= $Page->Pager->render() ?>
</div>
<?php } ?>
<?php if (!$Page->isExport("pdf")) { ?>
<!-- Report grid (begin) -->
<div id="gmp_Sales_By_Customer_2" class="card-body ew-grid-middle-panel <?= $Page->TableContainerClass ?>">
<?php } ?>
<table class="<?= $Page->TableClass ?>">
<thead>
	<!-- Table header -->
    <tr class="ew-table-header">
<?php if ($Page->CompanyName->Visible) { ?>
    <?php if ($Page->CompanyName->ShowGroupHeaderAsRow) { ?>
    <th data-name="CompanyName">&nbsp;</th>
    <?php } else { ?>
    <th data-name="CompanyName" class="<?= $Page->CompanyName->headerCellClass() ?>"><div class="Sales_By_Customer_2_CompanyName"><?= $Page->renderFieldHeader($Page->CompanyName) ?></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->OrderID->Visible) { ?>
    <?php if ($Page->OrderID->ShowGroupHeaderAsRow) { ?>
    <th data-name="OrderID">&nbsp;</th>
    <?php } else { ?>
    <th data-name="OrderID" class="<?= $Page->OrderID->headerCellClass() ?>"><div class="Sales_By_Customer_2_OrderID"><?= $Page->renderFieldHeader($Page->OrderID) ?></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->ProductName->Visible) { ?>
    <th data-name="ProductName" class="<?= $Page->ProductName->headerCellClass() ?>"><div class="Sales_By_Customer_2_ProductName"><?= $Page->renderFieldHeader($Page->ProductName) ?></div></th>
<?php } ?>
<?php if ($Page->UnitPrice->Visible) { ?>
    <th data-name="UnitPrice" class="<?= $Page->UnitPrice->headerCellClass() ?>"><div class="Sales_By_Customer_2_UnitPrice"><?= $Page->renderFieldHeader($Page->UnitPrice) ?></div></th>
<?php } ?>
<?php if ($Page->Quantity->Visible) { ?>
    <th data-name="Quantity" class="<?= $Page->Quantity->headerCellClass() ?>"><div class="Sales_By_Customer_2_Quantity"><?= $Page->renderFieldHeader($Page->Quantity) ?></div></th>
<?php } ?>
<?php if ($Page->Discount->Visible) { ?>
    <th data-name="Discount" class="<?= $Page->Discount->headerCellClass() ?>"><div class="Sales_By_Customer_2_Discount"><?= $Page->renderFieldHeader($Page->Discount) ?></div></th>
<?php } ?>
<?php if ($Page->ExtendedPrice->Visible) { ?>
    <th data-name="ExtendedPrice" class="<?= $Page->ExtendedPrice->headerCellClass() ?>"><div class="Sales_By_Customer_2_ExtendedPrice"><?= $Page->renderFieldHeader($Page->ExtendedPrice) ?></div></th>
<?php } ?>
    </tr>
</thead>
<tbody>
<?php
        if ($Page->TotalGroups == 0) {
            break; // Show header only
        }
        $Page->ShowHeader = false;
    } // End show header
?>
<?php

    // Build detail SQL
    $where = DetailFilterSql($Page->CompanyName, $Page->getSqlFirstGroupField(), $Page->CompanyName->groupValue(), $Page->Dbid);
    if ($Page->PageFirstGroupFilter != "") {
        $Page->PageFirstGroupFilter .= " OR ";
    }
    $Page->PageFirstGroupFilter .= $where;
    if ($Page->Filter != "") {
        $where = "($Page->Filter) AND ($where)";
    }
    $sql = $Page->buildReportSql($Page->getSqlSelect(), $Page->getSqlFrom(), $Page->getSqlWhere(), $Page->getSqlGroupBy(), $Page->getSqlHaving(), $Page->getSqlOrderBy(), $where, $Page->Sort);
    $rs = $sql->execute();
    $Page->DetailRecords = $rs ? $rs->fetchAll() : [];
    $Page->DetailRecordCount = count($Page->DetailRecords);

    // Load detail records
    $Page->CompanyName->Records = &$Page->DetailRecords;
    $Page->CompanyName->LevelBreak = true; // Set field level break
        $Page->GroupCounter[1] = $Page->GroupCount;
        $Page->CompanyName->getCnt($Page->CompanyName->Records); // Get record count
?>
<?php if ($Page->CompanyName->Visible && $Page->CompanyName->ShowGroupHeaderAsRow) { ?>
<?php
        // Render header row
        $Page->resetAttributes();
        $Page->RowType = ROWTYPE_TOTAL;
        $Page->RowTotalType = ROWTOTAL_GROUP;
        $Page->RowTotalSubType = ROWTOTAL_HEADER;
        $Page->RowGroupLevel = 1;
        $Page->renderRow();
?>
    <tr<?= $Page->rowAttributes(); ?>>
<?php if ($Page->CompanyName->Visible) { ?>
        <?php $Page->CompanyName->CellAttrs->appendClass("ew-rpt-grp-caret"); ?>
        <td data-field="CompanyName"<?= $Page->CompanyName->cellAttributes(); ?>><i class="ew-group-toggle fa-solid fa-caret-down"></i></td>
        <?php $Page->CompanyName->CellAttrs->removeClass("ew-rpt-grp-caret"); ?>
<?php } ?>
        <td data-field="CompanyName" colspan="<?= ($Page->GroupColumnCount + $Page->DetailColumnCount - 1) ?>"<?= $Page->CompanyName->cellAttributes() ?>>
            <span class="ew-summary-caption Sales_By_Customer_2_CompanyName"><?= $Page->renderFieldHeader($Page->CompanyName) ?></span><?= $Language->phrase("SummaryColon") ?><template id="tpx<?= $Page->GroupCount ?>_Sales_By_Customer_2_CompanyName"><span<?= $Page->CompanyName->viewAttributes() ?>><?= $Page->CompanyName->GroupViewValue ?></span></template>
            <span class="ew-summary-count">(<span class="ew-aggregate-caption"><?= $Language->phrase("RptCnt") ?></span><span class="ew-aggregate-equal"><?= $Language->phrase("AggregateEqual") ?></span><span class="ew-aggregate-value"><?= FormatNumber($Page->CompanyName->Count, Config("DEFAULT_NUMBER_FORMAT")) ?></span>)</span>
        </td>
    </tr>
<?php } ?>
<?php
    $Page->OrderID->getDistinctValues($Page->CompanyName->Records, $Page->OrderID->getSort());
    $Page->setGroupCount(count($Page->OrderID->DistinctValues), $Page->GroupCounter[1]);
    $Page->GroupCounter[2] = 0; // Init group count index
    foreach ($Page->OrderID->DistinctValues as $OrderID) { // Load records for this distinct value
        $Page->OrderID->setGroupValue($OrderID); // Set group value
        $Page->OrderID->getDistinctRecords($Page->CompanyName->Records, $Page->OrderID->groupValue());
        $Page->OrderID->LevelBreak = true; // Set field level break
        $Page->GroupCounter[2]++;
        $Page->OrderID->getCnt($Page->OrderID->Records); // Get record count
        $Page->setGroupCount($Page->OrderID->Count, $Page->GroupCounter[1], $Page->GroupCounter[2]);
?>
<?php if ($Page->OrderID->Visible && $Page->OrderID->ShowGroupHeaderAsRow) { ?>
<?php
        // Render header row
        $Page->OrderID->setDbValue($OrderID); // Set current value for OrderID
        $Page->resetAttributes();
        $Page->RowType = ROWTYPE_TOTAL;
        $Page->RowTotalType = ROWTOTAL_GROUP;
        $Page->RowTotalSubType = ROWTOTAL_HEADER;
        $Page->RowGroupLevel = 2;
        $Page->renderRow();
?>
    <tr<?= $Page->rowAttributes(); ?>>
<?php if ($Page->CompanyName->Visible) { ?>
        <td data-field="CompanyName"<?= $Page->CompanyName->cellAttributes(); ?>></td>
<?php } ?>
<?php if ($Page->OrderID->Visible) { ?>
        <?php $Page->OrderID->CellAttrs->appendClass("ew-rpt-grp-caret"); ?>
        <td data-field="OrderID"<?= $Page->OrderID->cellAttributes(); ?>><i class="ew-group-toggle fa-solid fa-caret-down"></i></td>
        <?php $Page->OrderID->CellAttrs->removeClass("ew-rpt-grp-caret"); ?>
<?php } ?>
        <td data-field="OrderID" colspan="<?= ($Page->GroupColumnCount + $Page->DetailColumnCount - 2) ?>"<?= $Page->OrderID->cellAttributes() ?>>
            <span class="ew-summary-caption Sales_By_Customer_2_OrderID"><?= $Page->renderFieldHeader($Page->OrderID) ?></span><?= $Language->phrase("SummaryColon") ?><template id="tpx<?= $Page->GroupCount ?>_<?= $Page->GroupCounter[2] ?>_Sales_By_Customer_2_OrderID"><span<?= $Page->OrderID->viewAttributes() ?>><?= $Page->OrderID->GroupViewValue ?></span></template>
            <span class="ew-summary-count">(<span class="ew-aggregate-caption"><?= $Language->phrase("RptCnt") ?></span><span class="ew-aggregate-equal"><?= $Language->phrase("AggregateEqual") ?></span><span class="ew-aggregate-value"><?= FormatNumber($Page->OrderID->Count, Config("DEFAULT_NUMBER_FORMAT")) ?></span>)</span>
        </td>
    </tr>
<?php } ?>
<?php
        $Page->RecordCount = 0; // Reset record count
        foreach ($Page->OrderID->Records as $record) {
            $Page->RecordCount++;
            $Page->RecordIndex++;
            $Page->loadRowValues($record);
?>
<?php
        // Render detail row
        $Page->resetAttributes();
        $Page->RowType = ROWTYPE_DETAIL;
        $Page->renderRow();
?>
    <tr<?= $Page->rowAttributes(); ?>>
<?php if ($Page->CompanyName->Visible) { ?>
    <?php if ($Page->CompanyName->ShowGroupHeaderAsRow) { ?>
        <td data-field="CompanyName"<?= $Page->CompanyName->cellAttributes() ?>></td>
    <?php } else { ?>
        <td data-field="CompanyName"<?= $Page->CompanyName->cellAttributes() ?>><template id="tpx<?= $Page->GroupCount ?>_Sales_By_Customer_2_CompanyName"><span<?= $Page->CompanyName->viewAttributes() ?>><?= $Page->CompanyName->GroupViewValue ?></span></template></td>
    <?php } ?>
<?php } ?>
<?php if ($Page->OrderID->Visible) { ?>
    <?php if ($Page->OrderID->ShowGroupHeaderAsRow) { ?>
        <td data-field="OrderID"<?= $Page->OrderID->cellAttributes() ?>></td>
    <?php } else { ?>
        <td data-field="OrderID"<?= $Page->OrderID->cellAttributes() ?>><template id="tpx<?= $Page->GroupCount ?>_<?= $Page->GroupCounter[2] ?>_Sales_By_Customer_2_OrderID"><span<?= $Page->OrderID->viewAttributes() ?>><?= $Page->OrderID->GroupViewValue ?></span></template></td>
    <?php } ?>
<?php } ?>
<?php if ($Page->ProductName->Visible) { ?>
        <td data-field="ProductName"<?= $Page->ProductName->cellAttributes() ?>>
<template id="tpx<?= $Page->GroupCount ?>_<?= $Page->GroupCounter[2] ?>_<?= $Page->RecordCount ?>_Sales_By_Customer_2_ProductName">
<span<?= $Page->ProductName->viewAttributes() ?>>
<?= $Page->ProductName->getViewValue() ?></span>
</template>
</td>
<?php } ?>
<?php if ($Page->UnitPrice->Visible) { ?>
        <td data-field="UnitPrice"<?= $Page->UnitPrice->cellAttributes() ?>>
<template id="tpx<?= $Page->GroupCount ?>_<?= $Page->GroupCounter[2] ?>_<?= $Page->RecordCount ?>_Sales_By_Customer_2_UnitPrice">
<span<?= $Page->UnitPrice->viewAttributes() ?>>
<?= $Page->UnitPrice->getViewValue() ?></span>
</template>
</td>
<?php } ?>
<?php if ($Page->Quantity->Visible) { ?>
        <td data-field="Quantity"<?= $Page->Quantity->cellAttributes() ?>>
<template id="tpx<?= $Page->GroupCount ?>_<?= $Page->GroupCounter[2] ?>_<?= $Page->RecordCount ?>_Sales_By_Customer_2_Quantity">
<span<?= $Page->Quantity->viewAttributes() ?>>
<?= $Page->Quantity->getViewValue() ?></span>
</template>
</td>
<?php } ?>
<?php if ($Page->Discount->Visible) { ?>
        <td data-field="Discount"<?= $Page->Discount->cellAttributes() ?>>
<template id="tpx<?= $Page->GroupCount ?>_<?= $Page->GroupCounter[2] ?>_<?= $Page->RecordCount ?>_Sales_By_Customer_2_Discount">
<span<?= $Page->Discount->viewAttributes() ?>>
<?= $Page->Discount->getViewValue() ?></span>
</template>
</td>
<?php } ?>
<?php if ($Page->ExtendedPrice->Visible) { ?>
        <td data-field="ExtendedPrice"<?= $Page->ExtendedPrice->cellAttributes() ?>>
<template id="tpx<?= $Page->GroupCount ?>_<?= $Page->GroupCounter[2] ?>_<?= $Page->RecordCount ?>_Sales_By_Customer_2_ExtendedPrice">
<span<?= $Page->ExtendedPrice->viewAttributes() ?>>
<?= $Page->ExtendedPrice->getViewValue() ?></span>
</template>
</td>
<?php } ?>
    </tr>
<?php
    }
?>
<?php if ($Page->TotalGroups > 0) { ?>
<?php
    $Page->Quantity->getSum($Page->OrderID->Records); // Get Sum
    $Page->ExtendedPrice->getSum($Page->OrderID->Records); // Get Sum
    $Page->resetAttributes();
    $Page->RowType = ROWTYPE_TOTAL;
    $Page->RowTotalType = ROWTOTAL_GROUP;
    $Page->RowTotalSubType = ROWTOTAL_FOOTER;
    $Page->RowGroupLevel = 2;
    $Page->renderRow();
?>
<?php if ($Page->OrderID->ShowCompactSummaryFooter) { ?>
    <tr<?= $Page->rowAttributes(); ?>>
<?php if ($Page->CompanyName->Visible) { ?>
    <?php if ($Page->CompanyName->ShowGroupHeaderAsRow) { ?>
        <td data-field="CompanyName"<?= $Page->CompanyName->cellAttributes() ?>></td>
    <?php } elseif ($Page->RowGroupLevel != 1) { ?>
        <td data-field="CompanyName"<?= $Page->CompanyName->cellAttributes() ?>>
        </td>
    <?php } else { ?>
        <td data-field="CompanyName"<?= $Page->CompanyName->cellAttributes() ?>>
            <span class="ew-summary-count"><span class="ew-aggregate-caption"><?= $Language->phrase("RptCnt") ?></span><span class="ew-aggregate-equal"><?= $Language->phrase("AggregateEqual") ?></span><span class="ew-aggregate-value"><?= FormatNumber($Page->CompanyName->Count, Config("DEFAULT_NUMBER_FORMAT")) ?></span></span>
        </td>
    <?php } ?>
<?php } ?>
<?php if ($Page->OrderID->Visible) { ?>
    <?php if ($Page->OrderID->ShowGroupHeaderAsRow) { ?>
        <td data-field="OrderID"<?= $Page->OrderID->cellAttributes() ?>></td>
    <?php } elseif ($Page->RowGroupLevel != 2) { ?>
        <td data-field="OrderID"<?= $Page->OrderID->cellAttributes() ?>>
        </td>
    <?php } else { ?>
        <td data-field="OrderID"<?= $Page->OrderID->cellAttributes() ?>>
            <span class="ew-summary-count"><span class="ew-aggregate-caption"><?= $Language->phrase("RptCnt") ?></span><span class="ew-aggregate-equal"><?= $Language->phrase("AggregateEqual") ?></span><span class="ew-aggregate-value"><?= FormatNumber($Page->OrderID->Count, Config("DEFAULT_NUMBER_FORMAT")) ?></span></span>
        </td>
    <?php } ?>
<?php } ?>
<?php if ($Page->ProductName->Visible) { ?>
        <td data-field="ProductName"<?= $Page->OrderID->cellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->UnitPrice->Visible) { ?>
        <td data-field="UnitPrice"<?= $Page->OrderID->cellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->Quantity->Visible) { ?>
        <td data-field="Quantity"<?= $Page->OrderID->cellAttributes() ?>><span class="ew-aggregate-caption"><?= $Language->phrase("RptSum") ?></span><span class="ew-aggregate-equal"><?= $Language->phrase("AggregateEqual") ?></span><span class="ew-aggregate-value"><template id="tpgs<?= $Page->GroupCount ?>_<?= $Page->GroupCounter[2] ?>_Sales_By_Customer_2_Quantity"><span<?= $Page->Quantity->viewAttributes() ?>><?= $Page->Quantity->SumViewValue ?></span></template></span></td>
<?php } ?>
<?php if ($Page->Discount->Visible) { ?>
        <td data-field="Discount"<?= $Page->OrderID->cellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->ExtendedPrice->Visible) { ?>
        <td data-field="ExtendedPrice"<?= $Page->OrderID->cellAttributes() ?>><span class="ew-aggregate-caption"><?= $Language->phrase("RptSum") ?></span><span class="ew-aggregate-equal"><?= $Language->phrase("AggregateEqual") ?></span><span class="ew-aggregate-value"><template id="tpgs<?= $Page->GroupCount ?>_<?= $Page->GroupCounter[2] ?>_Sales_By_Customer_2_ExtendedPrice"><span<?= $Page->ExtendedPrice->viewAttributes() ?>><?= $Page->ExtendedPrice->SumViewValue ?></span></template></span></td>
<?php } ?>
    </tr>
<?php } else { ?>
    <tr<?= $Page->rowAttributes(); ?>>
<?php if ($Page->CompanyName->Visible) { ?>
        <td data-field="CompanyName"<?= $Page->CompanyName->cellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->SubGroupColumnCount + $Page->DetailColumnCount > 0) { ?>
        <td colspan="<?= ($Page->SubGroupColumnCount + $Page->DetailColumnCount) ?>"<?= $Page->OrderID->cellAttributes() ?>><?= str_replace(["%v", "%c"], [$Page->OrderID->GroupViewValue, $Page->OrderID->caption()], $Language->phrase("RptSumHead")) ?> <span class="ew-dir-ltr">(<?= FormatNumber($Page->OrderID->Count, Config("DEFAULT_NUMBER_FORMAT")) ?><?= $Language->phrase("RptDtlRec") ?>)</span></td>
<?php } ?>
    </tr>
    <tr<?= $Page->rowAttributes(); ?>>
<?php if ($Page->CompanyName->Visible) { ?>
        <td data-field="CompanyName"<?= $Page->CompanyName->cellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->GroupColumnCount > 0) { ?>
        <td colspan="<?= ($Page->GroupColumnCount - 1) ?>"<?= $Page->OrderID->cellAttributes() ?>><?= $Language->phrase("RptSum") ?></td>
<?php } ?>
<?php if ($Page->ProductName->Visible) { ?>
        <td data-field="ProductName"<?= $Page->OrderID->cellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->UnitPrice->Visible) { ?>
        <td data-field="UnitPrice"<?= $Page->OrderID->cellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->Quantity->Visible) { ?>
        <td data-field="Quantity"<?= $Page->Quantity->cellAttributes() ?>>
<template id="tpgs<?= $Page->GroupCount ?>_<?= $Page->GroupCounter[2] ?>_Sales_By_Customer_2_Quantity">
<span<?= $Page->Quantity->viewAttributes() ?>>
<?= $Page->Quantity->SumViewValue ?></span>
</template>
</td>
<?php } ?>
<?php if ($Page->Discount->Visible) { ?>
        <td data-field="Discount"<?= $Page->OrderID->cellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->ExtendedPrice->Visible) { ?>
        <td data-field="ExtendedPrice"<?= $Page->ExtendedPrice->cellAttributes() ?>>
<template id="tpgs<?= $Page->GroupCount ?>_<?= $Page->GroupCounter[2] ?>_Sales_By_Customer_2_ExtendedPrice">
<span<?= $Page->ExtendedPrice->viewAttributes() ?>>
<?= $Page->ExtendedPrice->SumViewValue ?></span>
</template>
</td>
<?php } ?>
    </tr>
<?php } ?>
<?php } ?>
<?php
    } // End group level 1
?>
<?php

    // Next group
    $Page->loadGroupRowValues();

    // Show header if page break
    if ($Page->isExport()) {
        $Page->ShowHeader = ($Page->ExportPageBreakCount == 0) ? false : ($Page->GroupCount % $Page->ExportPageBreakCount == 0);
    }

    // Page_Breaking server event
    if ($Page->ShowHeader) {
        $Page->pageBreaking($Page->ShowHeader, $Page->PageBreakHtml);
    }
    $Page->GroupCount++;
} // End while
?>
<?php if ($Page->TotalGroups > 0) { ?>
</tbody>
<tfoot>
</tfoot>
</table>
<?php if (!$Page->isExport("pdf")) { ?>
</div>
<!-- /.ew-grid-middle-panel -->
<!-- Report grid (end) -->
<?php } ?>
<?php if ($Page->TotalGroups > 0) { ?>
<?php if (!$Page->isExport() && !($Page->DrillDown && $Page->TotalGroups > 0)) { ?>
<!-- Bottom pager -->
<div class="card-footer ew-grid-lower-panel">
<?= $Page->Pager->render() ?>
</div>
<?php } ?>
<?php } ?>
<?php if (!$Page->isExport("pdf")) { ?>
</div>
<!-- /.ew-grid -->
<?php } ?>
<?php } ?>
<?php if (!$Page->isExport("pdf")) { ?>
</main>
<!-- /.report-summary -->
<?php } ?>
<?php if ($Page->isExport() || $Page->UseCustomTemplate) { ?>
<div id="tpd_Sales_By_Customer_2summary"></div>
<template id="tpm_Sales_By_Customer_2summary">
<div id="ct_SalesByCustomer2Summary"><?php $k = 1; ?><table class="ew-table no-border"><tr><td><?= date("F j, Y"); ?></td></tr></table>
<br>
<table class="ew-table no-border">
<tr><td>{{: ~root.rows[<?= $k - 1 ?>].CompanyName }}</td></tr>
<tr><td>{{: ~root.rows[<?= $k - 1 ?>].Address }}</td></tr>
<tr><td>{{: ~root.rows[<?= $k - 1 ?>].City }}</td></tr>
<tr><td>{{: ~root.rows[<?= $k - 1 ?>].Country }}</td></tr>
<tr><td>Attn: {{: ~root.rows[<?= $k - 1 ?>].ContactName }}</td></tr>
</table>
<br>
<?php
$cnt = $Page->GroupCount - 1;
for ($i = 1; $i <= $cnt; $i++) {
?>
<?php
$cnt1 = $Page->getGroupCount($i);
for ($i1 = 1; $i1 <= $cnt1; $i1++) {
?>
<table class="ew-table no-border"><tr><td><b>Order Number</b> <slot class="ew-slot" name="tpx<?= $i ?>_<?= $i1 ?>_Sales_By_Customer_2_OrderID"></slot> </td></tr></table>
<br>
<table class="table table-sm ew-table ew-export-table" width="100%">
<tr><td><b><?= $Page->ProductName->caption() ?></b></td>
<td><b><?= $Page->UnitPrice->caption() ?></b></td>
<td><b><?= $Page->Quantity->caption() ?></b></td>
<td><b><?= $Page->Discount->caption() ?></b></td>
<td><b><?= $Page->ExtendedPrice->caption() ?></b></td>
</tr>
<?php
for ($j = 1; $j <= $Page->getGroupCount($i, $i1); $j++) {
?>
<tr>
<td><slot class="ew-slot" name="tpx<?= $i ?>_<?= $i1 ?>_<?= $j ?>_Sales_By_Customer_2_ProductName"></slot></td>
<td><slot class="ew-slot" name="tpx<?= $i ?>_<?= $i1 ?>_<?= $j ?>_Sales_By_Customer_2_UnitPrice"></slot></td>
<td><slot class="ew-slot" name="tpx<?= $i ?>_<?= $i1 ?>_<?= $j ?>_Sales_By_Customer_2_Quantity"></slot></td>
<td><slot class="ew-slot" name="tpx<?= $i ?>_<?= $i1 ?>_<?= $j ?>_Sales_By_Customer_2_Discount"></slot></td>
<td><slot class="ew-slot" name="tpx<?= $i ?>_<?= $i1 ?>_<?= $j ?>_Sales_By_Customer_2_ExtendedPrice"></slot></td>
</tr>
<?php
$k++;
}
?>
<tr>
<td colspan="4"><div style="text-align: right;"><b>Total</b></div></td>
<td><b><slot class="ew-slot" name="tpgs<?= $i ?>_<?= $i1 ?>_Sales_By_Customer_2_ExtendedPrice"></slot></b></td>
</tr>
</table>
<?php
}
?>
<?php
if ($Page->ExportPageBreakCount > 0 && $Page->isExport()) {
if ($i % $Page->ExportPageBreakCount == 0 && $i < $cnt) {
?>
<slot class="ew-slot" name="tpb<?= $i ?>_Sales_By_Customer_2"></slot>
<?php
}
}
}
?>
<br>
<table class="ew-table no-border"><tr><td>Some additional information here.</td></tr></table>
</div>
</template>
<?php } ?>
<!-- Summary report (end) -->
<?php } ?>
</div>
<!-- /.ew-report -->
<script class="ew-apply-template">
loadjs.ready(ew.applyTemplateId, function() {
    ew.templateData = { rows: <?= JsonEncode($Page->Rows) ?> };
    ew.applyTemplate("tpd_Sales_By_Customer_2summary", "tpm_Sales_By_Customer_2summary", "Sales_By_Customer_2summary", "<?= $Page->Export ?>", "Sales_By_Customer_2", ew.templateData, false);
    loadjs.done("customtemplate");
});
</script>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<?php if (!$Page->isExport() && !$Page->DrillDown && !$DashboardReport) { ?>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
