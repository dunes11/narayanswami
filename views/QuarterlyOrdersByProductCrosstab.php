<?php namespace PHPMaker2023\demo2023; ?>
<?php

namespace PHPMaker2023\demo2023;

// Page object
$QuarterlyOrdersByProductCrosstab = &$Page;
?>
<?php if (!$Page->isExport() && !$Page->DrillDown && !$DashboardReport) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { Quarterly_Orders_By_Product: currentTable } });
var currentPageID = ew.PAGE_ID = "crosstab";
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
<form name="fQuarterly_Orders_By_Productsrch" id="fQuarterly_Orders_By_Productsrch" class="ew-form ew-ext-search-form" action="<?= CurrentPageUrl(false) ?>" novalidate autocomplete="on">
<div id="fQuarterly_Orders_By_Productsrch_search_panel" class="mb-2 mb-sm-0 <?= $Page->SearchPanelClass ?>"><!-- .ew-search-panel -->
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { Quarterly_Orders_By_Product: currentTable } });
var currentPageID = ew.PAGE_ID = "crosstab";
var currentForm;
var fQuarterly_Orders_By_Productsrch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery,
        fields = currentTable.fields;

    // Form object for search
    let form = new ew.FormBuilder()
        .setId("fQuarterly_Orders_By_Productsrch")
        .setPageId("crosstab")
<?php if ($Page->UseAjaxActions) { ?>
        .setSubmitWithFetch(true)
<?php } ?>

        // Add fields
        .addFields([
            ["CategoryName", [], fields.CategoryName.isInvalid],
            ["ProductName", [], fields.ProductName.isInvalid],
            ["CompanyName", [], fields.CompanyName.isInvalid],
            ["OrderDate", [], fields.OrderDate.isInvalid],
            ["Amount", [], fields.Amount.isInvalid]
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
            "OrderDate": <?= $Page->OrderDate->toClientList($Page) ?>,
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
<?php if ($Page->ProductName->Visible) { // ProductName ?>
<?php
if (!$Page->ProductName->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_ProductName" class="col-sm-auto d-sm-flex align-items-start mb-3 px-0 pe-sm-2<?= $Page->ProductName->UseFilter ? " ew-filter-field" : "" ?>">
        <div class="d-flex my-1 my-sm-0">
            <label for="x_ProductName" class="ew-search-caption ew-label"><?= $Page->ProductName->caption() ?></label>
            <div class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_ProductName" id="z_ProductName" value="LIKE">
</div>
        </div>
        <div id="el_Quarterly_Orders_By_Product_ProductName" class="ew-search-field">
<input type="<?= $Page->ProductName->getInputTextType() ?>" name="x_ProductName" id="x_ProductName" data-table="Quarterly_Orders_By_Product" data-field="x_ProductName" value="<?= $Page->ProductName->EditValue ?>" size="30" maxlength="40" placeholder="<?= HtmlEncode($Page->ProductName->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->ProductName->formatPattern()) ?>"<?= $Page->ProductName->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->ProductName->getErrorMessage() ?></div>
</div>
        <div class="d-flex my-1 my-sm-0">
        </div><!-- /.ew-search-field -->
    </div><!-- /.col-sm-auto -->
<?php } ?>
<?php if ($Page->OrderDate->Visible) { // OrderDate ?>
<?php
if (!$Page->OrderDate->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_OrderDate" class="col-sm-auto d-sm-flex align-items-start mb-3 px-0 pe-sm-2<?= $Page->OrderDate->UseFilter ? " ew-filter-field" : "" ?>">
        <div class="d-flex my-1 my-sm-0">
            <label for="x_OrderDate" class="ew-search-caption ew-label"><?= $Page->OrderDate->caption() ?></label>
        </div>
        <div id="el_Quarterly_Orders_By_Product_OrderDate" class="ew-search-field">
    <select
        id="x_OrderDate"
        name="x_OrderDate"
        class="form-select ew-select<?= $Page->OrderDate->isInvalidClass() ?>"
        <?php if (!$Page->OrderDate->IsNativeSelect) { ?>
        data-select2-id="fQuarterly_Orders_By_Productsrch_x_OrderDate"
        <?php } ?>
        data-table="Quarterly_Orders_By_Product"
        data-field="x_OrderDate"
        data-value-separator="<?= $Page->OrderDate->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->OrderDate->getPlaceHolder()) ?>"
        <?= $Page->OrderDate->editAttributes() ?>>
        <?= $Page->OrderDate->selectOptionListHtml("x_OrderDate") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->OrderDate->getErrorMessage() ?></div>
<?= $Page->OrderDate->Lookup->getParamTag($Page, "p_x_OrderDate") ?>
<?php if (!$Page->OrderDate->IsNativeSelect) { ?>
<script>
loadjs.ready("fQuarterly_Orders_By_Productsrch", function() {
    var options = { name: "x_OrderDate", selectId: "fQuarterly_Orders_By_Productsrch_x_OrderDate" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fQuarterly_Orders_By_Productsrch.lists.OrderDate?.lookupOptions.length) {
        options.data = { id: "x_OrderDate", form: "fQuarterly_Orders_By_Productsrch" };
    } else {
        options.ajax = { id: "x_OrderDate", form: "fQuarterly_Orders_By_Productsrch", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.Quarterly_Orders_By_Product.fields.OrderDate.selectOptions);
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
<?php if ((!$Page->isExport() || $Page->isExport("print")) && !$DashboardReport) { ?>
<!-- Middle Container -->
<div id="ew-middle" class="<?= $Page->MiddleContentClass ?>">
<?php } ?>
<?php if ((!$Page->isExport() || $Page->isExport("print")) && !$DashboardReport) { ?>
<!-- Content Container -->
<div id="ew-content" class="<?= $Page->ContainerClass ?>">
<?php } ?>
<?php if ($Page->ShowReport) { ?>
<!-- Crosstab report (begin) -->
<?php if (!$Page->isExport("pdf")) { ?>
<main class="report-crosstab<?= ($Page->TotalGroups == 0) ? " ew-no-record" : "" ?>">
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
<div id="gmp_Quarterly_Orders_By_Product" class="card-body ew-grid-middle-panel <?= $Page->TableContainerClass ?>">
<?php } ?>
<table class="<?= $Page->TableClass ?>">
<thead>
	<!-- Table header -->
    <tr class="ew-table-header">
<?php if ($Page->GroupColumnCount > 0) { ?>
        <td class="ew-rpt-col-summary" colspan="<?= $Page->GroupColumnCount ?>"><div><?= $Page->renderSummaryCaptions() ?></div></td>
<?php } ?>
        <td class="ew-rpt-col-header" colspan="<?= @$Page->ColumnSpan ?>">
            <div class="ew-table-header-btn">
                <span class="ew-table-header-caption"><?= $Page->OrderDate->caption() ?></span>
            </div>
        </td>
    </tr>
    <tr class="ew-table-header">
<?php if ($Page->CategoryName->Visible) { ?>
    <td data-field="CategoryName"><div><?= $Page->renderFieldHeader($Page->CategoryName) ?></div></td>
<?php } ?>
<?php if ($Page->ProductName->Visible) { ?>
    <td data-field="ProductName"><div><?= $Page->renderFieldHeader($Page->ProductName) ?></div></td>
<?php } ?>
<!-- Dynamic columns begin -->
<?php
    $cntval = count($Page->Columns);
    for ($iy = 1; $iy < $cntval; $iy++) {
        if ($Page->Columns[$iy]->Visible) {
            $Page->SummaryCurrentValues[$iy - 1] = $Page->Columns[$iy]->Caption;
            $Page->SummaryViewValues[$iy - 1] = $Page->SummaryCurrentValues[$iy - 1];
?>
        <td class="ew-table-header"<?= $Page->OrderDate->cellAttributes() ?>><div<?= $Page->OrderDate->viewAttributes() ?>><?= $Page->SummaryViewValues[$iy-1]; ?></div></td>
<?php
        }
    }
?>
<!-- Dynamic columns end -->
        <td class="ew-table-header"<?= $Page->OrderDate->cellAttributes() ?>><div<?= $Page->OrderDate->viewAttributes() ?>><?= $Page->renderSummaryCaptions() ?></div></td>
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
    $where = DetailFilterSql($Page->CategoryName, $Page->getSqlFirstGroupField(), $Page->CategoryName->groupValue(), $Page->Dbid);
    if ($Page->PageFirstGroupFilter != "") {
        $Page->PageFirstGroupFilter .= " OR ";
    }
    $Page->PageFirstGroupFilter .= $where;
    if ($Page->Filter != "") {
        $where = "($Page->Filter) AND ($where)";
    }
    $sql = $Page->buildReportSql($Page->getSqlSelect()->addSelect($Page->DistinctColumnFields), $Page->getSqlFrom(), $Page->getSqlWhere(), $Page->getSqlGroupBy(), "", $Page->getSqlOrderBy(), $where, $Page->Sort);
    $rs = $sql->execute();
    $Page->DetailRecords = $rs ? $rs->fetchAll() : [];
    $Page->DetailRecordCount = count($Page->DetailRecords);

    // Load detail records
    $Page->CategoryName->Records = &$Page->DetailRecords;
    $Page->CategoryName->LevelBreak = true; // Set field level break
    $Page->ProductName->getDistinctValues($Page->CategoryName->Records, $Page->ProductName->getSort());
    foreach ($Page->ProductName->DistinctValues as $ProductName) { // Load records for this distinct value
        $Page->ProductName->setGroupValue($ProductName); // Set group value
        $Page->ProductName->getDistinctRecords($Page->CategoryName->Records, $Page->ProductName->groupValue());
        $Page->ProductName->LevelBreak = true; // Set field level break
        foreach ($Page->ProductName->Records as $record) {
            $Page->RecordCount++;
            $Page->RecordIndex++;
            $Page->loadRowValues($record);

            // Render row
            $Page->resetAttributes();
            $Page->RowType = ROWTYPE_DETAIL;
            $Page->renderRow();
?>
	<tr<?= $Page->rowAttributes(); ?>>
<?php if ($Page->CategoryName->Visible) { ?>
        <!-- CategoryName -->
        <td data-field="CategoryName"<?= $Page->CategoryName->cellAttributes() ?>><span<?= $Page->CategoryName->viewAttributes() ?>><?= $Page->CategoryName->GroupViewValue ?></span></td>
<?php } ?>
<?php if ($Page->ProductName->Visible) { ?>
        <!-- ProductName -->
        <td data-field="ProductName"<?= $Page->ProductName->cellAttributes() ?>><span<?= $Page->ProductName->viewAttributes() ?>><?= $Page->ProductName->GroupViewValue ?></span></td>
<?php } ?>
<!-- Dynamic columns begin -->
<?php
        $cntcol = count($Page->SummaryViewValues);
        for ($iy = 1; $iy <= $cntcol; $iy++) {
            $colShow = ($iy <= $Page->ColumnCount) ? $Page->Columns[$iy]->Visible : true;
            $colDesc = ($iy <= $Page->ColumnCount) ? $Page->Columns[$iy]->Caption : $Language->phrase("Summary");
            if ($colShow) {
?>
        <!-- <?= $colDesc; ?> -->
        <td<?= $Page->summaryCellAttributes($iy-1) ?>><?= $Page->renderSummaryFields($iy-1) ?></td>
<?php
            }
        }
?>
<!-- Dynamic columns end -->
    </tr>
<?php
    }
    } // End group level 1
?>
<?php if ($Page->TotalGroups > 0) { ?>
<?php
    $Page->getSummaryValues($Page->CategoryName->Records); // Get crosstab summaries from records
    $Page->resetAttributes();
    $Page->RowType = ROWTYPE_TOTAL;
    $Page->RowTotalType = ROWTOTAL_GROUP;
    $Page->RowTotalSubType = ROWTOTAL_FOOTER;
    $Page->RowGroupLevel = 1;
    $Page->renderRow();
?>
    <!-- Summary CategoryName (level 1) -->
    <tr<?= $Page->rowAttributes(); ?>>
        <td colspan="<?= ($Page->GroupColumnCount - 0) ?>"<?= $Page->CategoryName->cellAttributes() ?>><?= str_replace(["%v", "%c"], [$Page->CategoryName->GroupViewValue, $Page->CategoryName->caption()], $Language->phrase("CrosstabSummary")) ?></td>
<!-- Dynamic columns begin -->
<?php
    $cntcol = count($Page->SummaryViewValues);
for ($iy = 1; $iy <= $cntcol; $iy++) {
    $colShow = ($iy <= $Page->ColumnCount) ? $Page->Columns[$iy]->Visible : true;
    $colDesc = ($iy <= $Page->ColumnCount) ? $Page->Columns[$iy]->Caption : $Language->phrase("Summary");
    if ($colShow) {
        ?>
        <!-- <?= $colDesc; ?> -->
        <td<?= $Page->summaryCellAttributes($iy-1) ?>><?= $Page->renderSummaryFields($iy-1) ?></td>
        <?php
    }
}
?>
<!-- Dynamic columns end -->
    </tr>
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
<?php if (($Page->StopGroup - $Page->StartGroup + 1) != $Page->TotalGroups) { ?>
<?php
    $Page->resetAttributes();
    $Page->RowType = ROWTYPE_TOTAL;
    $Page->RowTotalType = ROWTOTAL_PAGE;
    $Page->RowAttrs["class"] = "ew-rpt-page-summary";
    $Page->renderRow();
?>
    <!-- Page Summary -->
    <tr<?= $Page->rowAttributes(); ?>>
<?php if ($Page->GroupColumnCount > 0) { ?>
    <td colspan="<?= $Page->GroupColumnCount ?>"><?= $Page->renderSummaryCaptions("page") ?></td>
<?php } ?>
<!-- Dynamic columns begin -->
<?php
    $cntcol = count($Page->SummaryViewValues);
    for ($iy = 1; $iy <= $cntcol; $iy++) {
        $colShow = ($iy <= $Page->ColumnCount) ? $Page->Columns[$iy]->Visible : true;
        $colDesc = ($iy <= $Page->ColumnCount) ? $Page->Columns[$iy]->Caption : $Language->phrase("Summary");
        if ($colShow) {
?>
        <!-- <?= $colDesc; ?> -->
        <td<?= $Page->summaryCellAttributes($iy-1) ?>><?= $Page->renderSummaryFields($iy-1) ?></td>
<?php
        }
    }
?>
<!-- Dynamic columns end -->
    </tr>
<?php } ?>
<?php
    $Page->resetAttributes();
    $Page->RowType = ROWTYPE_TOTAL;
    $Page->RowTotalType = ROWTOTAL_GRAND;
    $Page->RowAttrs["class"] = "ew-rpt-grand-summary";
    $Page->renderRow();
?>
    <!-- Grand Total -->
    <tr<?= $Page->rowAttributes(); ?>>
<?php if ($Page->GroupColumnCount > 0) { ?>
    <td colspan="<?= $Page->GroupColumnCount ?>"><?= $Page->renderSummaryCaptions("grand") ?></td>
<?php } ?>
<!-- Dynamic columns begin -->
<?php
    $cntcol = count($Page->SummaryViewValues);
    for ($iy = 1; $iy <= $cntcol; $iy++) {
        $colShow = ($iy <= $Page->ColumnCount) ? $Page->Columns[$iy]->Visible : true;
        $colDesc = ($iy <= $Page->ColumnCount) ? $Page->Columns[$iy]->Caption : $Language->phrase("Summary");
        if ($colShow) {
?>
        <!-- <?= $colDesc; ?> -->
        <td<?= $Page->summaryCellAttributes($iy-1) ?>><?= $Page->renderSummaryFields($iy-1) ?></td>
<?php
        }
    }
?>
<!-- Dynamic columns end -->
    </tr>
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
<!-- /.report-crosstab -->
<?php } ?>
<!-- Crosstab report (end) -->
<?php } ?>
<?php if ((!$Page->isExport() || $Page->isExport("print")) && !$DashboardReport) { ?>
</div>
<!-- /#ew-content -->
<?php } ?>
<?php if ((!$Page->isExport() || $Page->isExport("print")) && !$DashboardReport) { ?>
</div>
<!-- /#ew-middle -->
<?php } ?>
<?php if ((!$Page->isExport() || $Page->isExport("print")) && !$DashboardReport) { ?>
<!-- Bottom Container -->
<div id="ew-bottom" class="<?= $Page->BottomContentClass ?>">
<?php } ?>
<?php
if (!$DashboardReport) {
    // Set up chart drilldown
    $Page->OrdersByCategory->DrillDownInPanel = $Page->DrillDownInPanel;

    // Update chart drill down URL from filter
    $Page->OrdersByCategory->DrillDownUrl = str_replace("=f1", "=" . Encrypt($Page->getDrillDownSql($QuarterlyOrdersByProductCrosstab->OrderDate, "OrderDate", 0, 0)), $Page->OrdersByCategory->DrillDownUrl);
    echo $Page->OrdersByCategory->render("ew-chart-bottom");
}
?>
<?php if ((!$Page->isExport() || $Page->isExport("print")) && !$DashboardReport) { ?>
</div>
<!-- /#ew-bottom -->
<?php } ?>
<?php if (!$DashboardReport && !$Page->isExport()) { ?>
<div class="mb-3"><a class="ew-top-link" data-ew-action="scroll-top"><?= $Language->phrase("Top") ?></a></div>
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
