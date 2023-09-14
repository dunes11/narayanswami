<?php

namespace PHPMaker2023\demo2023;

// Page object
$SalesByOrderSummary = &$Page;
?>
<?php if (!$Page->isExport() && !$Page->DrillDown && !$DashboardReport) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { Sales_By_Order: currentTable } });
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
<form name="fSales_By_Ordersrch" id="fSales_By_Ordersrch" class="ew-form ew-ext-search-form" action="<?= CurrentPageUrl(false) ?>" novalidate autocomplete="on">
<div id="fSales_By_Ordersrch_search_panel" class="mb-2 mb-sm-0 <?= $Page->SearchPanelClass ?>"><!-- .ew-search-panel -->
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { Sales_By_Order: currentTable } });
var currentPageID = ew.PAGE_ID = "summary";
var currentForm;
var fSales_By_Ordersrch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery,
        fields = currentTable.fields;

    // Form object for search
    let form = new ew.FormBuilder()
        .setId("fSales_By_Ordersrch")
        .setPageId("summary")
<?php if ($Page->UseAjaxActions) { ?>
        .setSubmitWithFetch(true)
<?php } ?>

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
            "CompanyName": <?= $Page->CompanyName->toClientList($Page) ?>,
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
<?php if ($Page->CompanyName->Visible) { // CompanyName ?>
<?php
if (!$Page->CompanyName->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_CompanyName" class="col-sm-auto d-sm-flex align-items-start mb-3 px-0 pe-sm-2<?= $Page->CompanyName->UseFilter ? " ew-filter-field" : "" ?>">
        <div class="d-flex my-1 my-sm-0">
            <label class="ew-search-caption ew-label"><?= $Page->CompanyName->caption() ?></label>
            <div class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_CompanyName" id="z_CompanyName" value="LIKE">
</div>
        </div>
        <div id="el_Sales_By_Order_CompanyName" class="ew-search-field">
<?php
if (IsRTL()) {
    $Page->CompanyName->EditAttrs["dir"] = "rtl";
}
?>
<span id="as_x_CompanyName" class="ew-auto-suggest">
    <input type="<?= $Page->CompanyName->getInputTextType() ?>" class="form-control" name="sv_x_CompanyName" id="sv_x_CompanyName" value="<?= RemoveHtml($Page->CompanyName->EditValue) ?>" autocomplete="off" size="30" maxlength="40" placeholder="<?= HtmlEncode($Page->CompanyName->getPlaceHolder()) ?>" data-placeholder="<?= HtmlEncode($Page->CompanyName->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->CompanyName->formatPattern()) ?>"<?= $Page->CompanyName->editAttributes() ?>>
</span>
<selection-list hidden class="form-control" data-table="Sales_By_Order" data-field="x_CompanyName" data-input="sv_x_CompanyName" data-value-separator="<?= $Page->CompanyName->displayValueSeparatorAttribute() ?>" name="x_CompanyName" id="x_CompanyName" value="<?= HtmlEncode($Page->CompanyName->AdvancedSearch->SearchValue) ?>"></selection-list>
<div class="invalid-feedback"><?= $Page->CompanyName->getErrorMessage() ?></div>
<script>
loadjs.ready("fSales_By_Ordersrch", function() {
    fSales_By_Ordersrch.createAutoSuggest(Object.assign({"id":"x_CompanyName","forceSelect":false}, { lookupAllDisplayFields: <?= $Page->CompanyName->Lookup->LookupAllDisplayFields ? "true" : "false" ?> }, ew.vars.tables.Sales_By_Order.fields.CompanyName.autoSuggestOptions));
});
</script>
<?= $Page->CompanyName->Lookup->getParamTag($Page, "p_x_CompanyName") ?>
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
<?php if ($Page->ShowDrillDownFilter) { ?>
<?php $Page->showDrillDownList() ?>
<?php } ?>
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
<?= $Page->PageBreakHtml ?>
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
<div id="gmp_Sales_By_Order" class="card-body ew-grid-middle-panel <?= $Page->TableContainerClass ?>">
<?php } ?>
<table class="<?= $Page->TableClass ?>">
<thead>
	<!-- Table header -->
    <tr class="ew-table-header">
<?php if ($Page->OrderID->Visible) { ?>
    <?php if ($Page->OrderID->ShowGroupHeaderAsRow) { ?>
    <th data-name="OrderID">&nbsp;</th>
    <?php } else { ?>
    <th data-name="OrderID" class="<?= $Page->OrderID->headerCellClass() ?>"><div class="Sales_By_Order_OrderID"><?= $Page->renderFieldHeader($Page->OrderID) ?></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->CompanyName->Visible) { ?>
    <?php if ($Page->CompanyName->ShowGroupHeaderAsRow) { ?>
    <th data-name="CompanyName">&nbsp;</th>
    <?php } else { ?>
    <th data-name="CompanyName" class="<?= $Page->CompanyName->headerCellClass() ?>"><div class="Sales_By_Order_CompanyName"><?= $Page->renderFieldHeader($Page->CompanyName) ?></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->ProductName->Visible) { ?>
    <th data-name="ProductName" class="<?= $Page->ProductName->headerCellClass() ?>"><div class="Sales_By_Order_ProductName"><?= $Page->renderFieldHeader($Page->ProductName) ?></div></th>
<?php } ?>
<?php if ($Page->UnitPrice->Visible) { ?>
    <th data-name="UnitPrice" class="<?= $Page->UnitPrice->headerCellClass() ?>"><div class="Sales_By_Order_UnitPrice"><?= $Page->renderFieldHeader($Page->UnitPrice) ?></div></th>
<?php } ?>
<?php if ($Page->Quantity->Visible) { ?>
    <th data-name="Quantity" class="<?= $Page->Quantity->headerCellClass() ?>"><div class="Sales_By_Order_Quantity"><?= $Page->renderFieldHeader($Page->Quantity) ?></div></th>
<?php } ?>
<?php if ($Page->Discount->Visible) { ?>
    <th data-name="Discount" class="<?= $Page->Discount->headerCellClass() ?>"><div class="Sales_By_Order_Discount"><?= $Page->renderFieldHeader($Page->Discount) ?></div></th>
<?php } ?>
<?php if ($Page->ExtendedPrice->Visible) { ?>
    <th data-name="ExtendedPrice" class="<?= $Page->ExtendedPrice->headerCellClass() ?>"><div class="Sales_By_Order_ExtendedPrice"><?= $Page->renderFieldHeader($Page->ExtendedPrice) ?></div></th>
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
    $where = DetailFilterSql($Page->OrderID, $Page->getSqlFirstGroupField(), $Page->OrderID->groupValue(), $Page->Dbid);
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
    $Page->OrderID->Records = &$Page->DetailRecords;
    $Page->OrderID->LevelBreak = true; // Set field level break
        $Page->GroupCounter[1] = $Page->GroupCount;
        $Page->OrderID->getCnt($Page->OrderID->Records); // Get record count
?>
<?php if ($Page->OrderID->Visible && $Page->OrderID->ShowGroupHeaderAsRow) { ?>
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
<?php if ($Page->OrderID->Visible) { ?>
        <?php $Page->OrderID->CellAttrs->appendClass("ew-rpt-grp-caret"); ?>
        <td data-field="OrderID"<?= $Page->OrderID->cellAttributes(); ?>><i class="ew-group-toggle fa-solid fa-caret-down"></i></td>
        <?php $Page->OrderID->CellAttrs->removeClass("ew-rpt-grp-caret"); ?>
<?php } ?>
        <td data-field="OrderID" colspan="<?= ($Page->GroupColumnCount + $Page->DetailColumnCount - 1) ?>"<?= $Page->OrderID->cellAttributes() ?>>
            <span class="ew-summary-caption Sales_By_Order_OrderID"><?= $Page->renderFieldHeader($Page->OrderID) ?></span><?= $Language->phrase("SummaryColon") ?><span<?= $Page->OrderID->viewAttributes() ?>><?= $Page->OrderID->GroupViewValue ?></span>
            <span class="ew-summary-count">(<span class="ew-aggregate-caption"><?= $Language->phrase("RptCnt") ?></span><span class="ew-aggregate-equal"><?= $Language->phrase("AggregateEqual") ?></span><span class="ew-aggregate-value"><?= FormatNumber($Page->OrderID->Count, Config("DEFAULT_NUMBER_FORMAT")) ?></span>)</span>
        </td>
    </tr>
<?php } ?>
<?php
    $Page->CompanyName->getDistinctValues($Page->OrderID->Records, $Page->CompanyName->getSort());
    $Page->setGroupCount(count($Page->CompanyName->DistinctValues), $Page->GroupCounter[1]);
    $Page->GroupCounter[2] = 0; // Init group count index
    foreach ($Page->CompanyName->DistinctValues as $CompanyName) { // Load records for this distinct value
        $Page->CompanyName->setGroupValue($CompanyName); // Set group value
        $Page->CompanyName->getDistinctRecords($Page->OrderID->Records, $Page->CompanyName->groupValue());
        $Page->CompanyName->LevelBreak = true; // Set field level break
        $Page->GroupCounter[2]++;
        $Page->CompanyName->getCnt($Page->CompanyName->Records); // Get record count
        $Page->setGroupCount($Page->CompanyName->Count, $Page->GroupCounter[1], $Page->GroupCounter[2]);
?>
<?php if ($Page->CompanyName->Visible && $Page->CompanyName->ShowGroupHeaderAsRow) { ?>
<?php
        // Render header row
        $Page->CompanyName->setDbValue($CompanyName); // Set current value for CompanyName
        $Page->resetAttributes();
        $Page->RowType = ROWTYPE_TOTAL;
        $Page->RowTotalType = ROWTOTAL_GROUP;
        $Page->RowTotalSubType = ROWTOTAL_HEADER;
        $Page->RowGroupLevel = 2;
        $Page->renderRow();
?>
    <tr<?= $Page->rowAttributes(); ?>>
<?php if ($Page->OrderID->Visible) { ?>
        <td data-field="OrderID"<?= $Page->OrderID->cellAttributes(); ?>></td>
<?php } ?>
<?php if ($Page->CompanyName->Visible) { ?>
        <?php $Page->CompanyName->CellAttrs->appendClass("ew-rpt-grp-caret"); ?>
        <td data-field="CompanyName"<?= $Page->CompanyName->cellAttributes(); ?>><i class="ew-group-toggle fa-solid fa-caret-down"></i></td>
        <?php $Page->CompanyName->CellAttrs->removeClass("ew-rpt-grp-caret"); ?>
<?php } ?>
        <td data-field="CompanyName" colspan="<?= ($Page->GroupColumnCount + $Page->DetailColumnCount - 2) ?>"<?= $Page->CompanyName->cellAttributes() ?>>
            <span class="ew-summary-caption Sales_By_Order_CompanyName"><?= $Page->renderFieldHeader($Page->CompanyName) ?></span><?= $Language->phrase("SummaryColon") ?><span<?= $Page->CompanyName->viewAttributes() ?>><?= $Page->CompanyName->GroupViewValue ?></span>
            <span class="ew-summary-count">(<span class="ew-aggregate-caption"><?= $Language->phrase("RptCnt") ?></span><span class="ew-aggregate-equal"><?= $Language->phrase("AggregateEqual") ?></span><span class="ew-aggregate-value"><?= FormatNumber($Page->CompanyName->Count, Config("DEFAULT_NUMBER_FORMAT")) ?></span>)</span>
        </td>
    </tr>
<?php } ?>
<?php
        $Page->RecordCount = 0; // Reset record count
        foreach ($Page->CompanyName->Records as $record) {
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
<?php if ($Page->OrderID->Visible) { ?>
    <?php if ($Page->OrderID->ShowGroupHeaderAsRow) { ?>
        <td data-field="OrderID"<?= $Page->OrderID->cellAttributes() ?>></td>
    <?php } else { ?>
        <td data-field="OrderID"<?= $Page->OrderID->cellAttributes() ?>><span<?= $Page->OrderID->viewAttributes() ?>><?= $Page->OrderID->GroupViewValue ?></span></td>
    <?php } ?>
<?php } ?>
<?php if ($Page->CompanyName->Visible) { ?>
    <?php if ($Page->CompanyName->ShowGroupHeaderAsRow) { ?>
        <td data-field="CompanyName"<?= $Page->CompanyName->cellAttributes() ?>></td>
    <?php } else { ?>
        <td data-field="CompanyName"<?= $Page->CompanyName->cellAttributes() ?>><span<?= $Page->CompanyName->viewAttributes() ?>><?= $Page->CompanyName->GroupViewValue ?></span></td>
    <?php } ?>
<?php } ?>
<?php if ($Page->ProductName->Visible) { ?>
        <td data-field="ProductName"<?= $Page->ProductName->cellAttributes() ?>>
<span<?= $Page->ProductName->viewAttributes() ?>>
<?= $Page->ProductName->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->UnitPrice->Visible) { ?>
        <td data-field="UnitPrice"<?= $Page->UnitPrice->cellAttributes() ?>>
<span<?= $Page->UnitPrice->viewAttributes() ?>>
<?= $Page->UnitPrice->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->Quantity->Visible) { ?>
        <td data-field="Quantity"<?= $Page->Quantity->cellAttributes() ?>>
<span<?= $Page->Quantity->viewAttributes() ?>>
<?= $Page->Quantity->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->Discount->Visible) { ?>
        <td data-field="Discount"<?= $Page->Discount->cellAttributes() ?>>
<span<?= $Page->Discount->viewAttributes() ?>>
<?= $Page->Discount->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->ExtendedPrice->Visible) { ?>
        <td data-field="ExtendedPrice"<?= $Page->ExtendedPrice->cellAttributes() ?>>
<span<?= $Page->ExtendedPrice->viewAttributes() ?>>
<?= $Page->ExtendedPrice->getViewValue() ?></span>
</td>
<?php } ?>
    </tr>
<?php
    }
    } // End group level 1
?>
<?php if ($Page->TotalGroups > 0) { ?>
<?php
    $Page->ExtendedPrice->getSum($Page->OrderID->Records); // Get Sum
    $Page->resetAttributes();
    $Page->RowType = ROWTYPE_TOTAL;
    $Page->RowTotalType = ROWTOTAL_GROUP;
    $Page->RowTotalSubType = ROWTOTAL_FOOTER;
    $Page->RowGroupLevel = 1;
    $Page->renderRow();
?>
<?php if ($Page->OrderID->ShowCompactSummaryFooter) { ?>
    <tr<?= $Page->rowAttributes(); ?>>
<?php if ($Page->OrderID->Visible) { ?>
    <?php if ($Page->OrderID->ShowGroupHeaderAsRow) { ?>
        <td data-field="OrderID"<?= $Page->OrderID->cellAttributes() ?>></td>
    <?php } elseif ($Page->RowGroupLevel != 1) { ?>
        <td data-field="OrderID"<?= $Page->OrderID->cellAttributes() ?>>
        </td>
    <?php } else { ?>
        <td data-field="OrderID"<?= $Page->OrderID->cellAttributes() ?>>
            <span class="ew-summary-count"><span class="ew-aggregate-caption"><?= $Language->phrase("RptCnt") ?></span><span class="ew-aggregate-equal"><?= $Language->phrase("AggregateEqual") ?></span><span class="ew-aggregate-value"><?= FormatNumber($Page->OrderID->Count, Config("DEFAULT_NUMBER_FORMAT")) ?></span></span>
        </td>
    <?php } ?>
<?php } ?>
<?php if ($Page->CompanyName->Visible) { ?>
    <?php if ($Page->CompanyName->ShowGroupHeaderAsRow) { ?>
        <td data-field="CompanyName"<?= $Page->OrderID->cellAttributes() ?>></td>
    <?php } elseif ($Page->RowGroupLevel != 2) { ?>
        <td data-field="CompanyName"<?= $Page->OrderID->cellAttributes() ?>>
        </td>
    <?php } else { ?>
        <td data-field="CompanyName"<?= $Page->OrderID->cellAttributes() ?>>
            <span class="ew-summary-count"><span class="ew-aggregate-caption"><?= $Language->phrase("RptCnt") ?></span><span class="ew-aggregate-equal"><?= $Language->phrase("AggregateEqual") ?></span><span class="ew-aggregate-value"><?= FormatNumber($Page->CompanyName->Count, Config("DEFAULT_NUMBER_FORMAT")) ?></span></span>
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
        <td data-field="Quantity"<?= $Page->OrderID->cellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->Discount->Visible) { ?>
        <td data-field="Discount"<?= $Page->OrderID->cellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->ExtendedPrice->Visible) { ?>
        <td data-field="ExtendedPrice"<?= $Page->OrderID->cellAttributes() ?>><span class="ew-aggregate-caption"><?= $Language->phrase("RptSum") ?></span><span class="ew-aggregate-equal"><?= $Language->phrase("AggregateEqual") ?></span><span class="ew-aggregate-value"><span<?= $Page->ExtendedPrice->viewAttributes() ?>><?= $Page->ExtendedPrice->SumViewValue ?></span></span></td>
<?php } ?>
    </tr>
<?php } else { ?>
    <tr<?= $Page->rowAttributes(); ?>>
<?php if ($Page->GroupColumnCount + $Page->DetailColumnCount > 0) { ?>
        <td colspan="<?= ($Page->GroupColumnCount + $Page->DetailColumnCount) ?>"<?= $Page->OrderID->cellAttributes() ?>><?= str_replace(["%v", "%c"], [$Page->OrderID->GroupViewValue, $Page->OrderID->caption()], $Language->phrase("RptSumHead")) ?> <span class="ew-dir-ltr">(<?= FormatNumber($Page->OrderID->Count, Config("DEFAULT_NUMBER_FORMAT")) ?><?= $Language->phrase("RptDtlRec") ?>)</span></td>
<?php } ?>
    </tr>
    <tr<?= $Page->rowAttributes(); ?>>
<?php if ($Page->GroupColumnCount > 0) { ?>
        <td colspan="<?= ($Page->GroupColumnCount - 0) ?>"<?= $Page->OrderID->cellAttributes() ?>><?= $Language->phrase("RptSum") ?></td>
<?php } ?>
<?php if ($Page->ProductName->Visible) { ?>
        <td data-field="ProductName"<?= $Page->OrderID->cellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->UnitPrice->Visible) { ?>
        <td data-field="UnitPrice"<?= $Page->OrderID->cellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->Quantity->Visible) { ?>
        <td data-field="Quantity"<?= $Page->OrderID->cellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->Discount->Visible) { ?>
        <td data-field="Discount"<?= $Page->OrderID->cellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->ExtendedPrice->Visible) { ?>
        <td data-field="ExtendedPrice"<?= $Page->ExtendedPrice->cellAttributes() ?>>
<span<?= $Page->ExtendedPrice->viewAttributes() ?>>
<?= $Page->ExtendedPrice->SumViewValue ?></span>
</td>
<?php } ?>
    </tr>
<?php } ?>
<?php } ?>
<?php
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
<?php
    $Page->resetAttributes();
    $Page->RowType = ROWTYPE_TOTAL;
    $Page->RowTotalType = ROWTOTAL_GRAND;
    $Page->RowTotalSubType = ROWTOTAL_FOOTER;
    $Page->RowAttrs["class"] = "ew-rpt-grand-summary";
    $Page->renderRow();
?>
<?php if ($Page->OrderID->ShowCompactSummaryFooter) { ?>
    <tr<?= $Page->rowAttributes() ?>><td colspan="<?= ($Page->GroupColumnCount + $Page->DetailColumnCount) ?>"><?= $Language->phrase("RptGrandSummary") ?> <span class="ew-summary-count">(<span class="ew-aggregate-caption"><?= $Language->phrase("RptCnt") ?></span><span class="ew-aggregate-equal"><?= $Language->phrase("AggregateEqual") ?></span><span class="ew-aggregate-value"><?= FormatNumber($Page->TotalCount, Config("DEFAULT_NUMBER_FORMAT")) ?></span>)</span></td></tr>
    <tr<?= $Page->rowAttributes() ?>>
<?php if ($Page->GroupColumnCount > 0) { ?>
        <td colspan="<?= $Page->GroupColumnCount ?>" class="ew-rpt-grp-aggregate"></td>
<?php } ?>
<?php if ($Page->ProductName->Visible) { ?>
        <td data-field="ProductName"<?= $Page->ProductName->cellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->UnitPrice->Visible) { ?>
        <td data-field="UnitPrice"<?= $Page->UnitPrice->cellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->Quantity->Visible) { ?>
        <td data-field="Quantity"<?= $Page->Quantity->cellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->Discount->Visible) { ?>
        <td data-field="Discount"<?= $Page->Discount->cellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->ExtendedPrice->Visible) { ?>
        <td data-field="ExtendedPrice"<?= $Page->ExtendedPrice->cellAttributes() ?>><span class="ew-aggregate-caption"><?= $Language->phrase("RptSum") ?></span><span class="ew-aggregate-equal"><?= $Language->phrase("AggregateEqual") ?></span><span class="ew-aggregate-value"><span<?= $Page->ExtendedPrice->viewAttributes() ?>><?= $Page->ExtendedPrice->SumViewValue ?></span></span></td>
<?php } ?>
    </tr>
<?php } else { ?>
    <tr<?= $Page->rowAttributes() ?>><td colspan="<?= ($Page->GroupColumnCount + $Page->DetailColumnCount) ?>"><?= $Language->phrase("RptGrandSummary") ?> <span class="ew-summary-count">(<?= FormatNumber($Page->TotalCount, Config("DEFAULT_NUMBER_FORMAT")) ?><?= $Language->phrase("RptDtlRec") ?>)</span></td></tr>
    <tr<?= $Page->rowAttributes() ?>>
<?php if ($Page->GroupColumnCount > 0) { ?>
        <td colspan="<?= $Page->GroupColumnCount ?>" class="ew-rpt-grp-aggregate"><?= $Language->phrase("RptSum") ?></td>
<?php } ?>
<?php if ($Page->ProductName->Visible) { ?>
        <td data-field="ProductName"<?= $Page->ProductName->cellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->UnitPrice->Visible) { ?>
        <td data-field="UnitPrice"<?= $Page->UnitPrice->cellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->Quantity->Visible) { ?>
        <td data-field="Quantity"<?= $Page->Quantity->cellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->Discount->Visible) { ?>
        <td data-field="Discount"<?= $Page->Discount->cellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->ExtendedPrice->Visible) { ?>
        <td data-field="ExtendedPrice"<?= $Page->ExtendedPrice->cellAttributes() ?>>
<span<?= $Page->ExtendedPrice->viewAttributes() ?>>
<?= $Page->ExtendedPrice->SumViewValue ?></span>
</td>
<?php } ?>
    </tr>
<?php } ?>
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
<!-- Summary report (end) -->
<?php } ?>
</div>
<!-- /.ew-report -->
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
