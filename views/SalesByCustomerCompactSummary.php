<?php

namespace PHPMaker2023\demo2023;

// Page object
$SalesByCustomerCompactSummary = &$Page;
?>
<?php if (!$Page->isExport() && !$Page->DrillDown && !$DashboardReport) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { Sales_By_Customer_Compact: currentTable } });
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
<div id="gmp_Sales_By_Customer_Compact" class="card-body ew-grid-middle-panel <?= $Page->TableContainerClass ?>">
<?php } ?>
<table class="<?= $Page->TableClass ?>">
<thead>
	<!-- Table header -->
    <tr class="ew-table-header">
<?php if ($Page->CompanyName->Visible) { ?>
    <?php if ($Page->CompanyName->ShowGroupHeaderAsRow) { ?>
    <th data-name="CompanyName">&nbsp;</th>
    <?php } else { ?>
    <th data-name="CompanyName" class="<?= $Page->CompanyName->headerCellClass() ?>"><div class="Sales_By_Customer_Compact_CompanyName"><?= $Page->renderFieldHeader($Page->CompanyName) ?></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->OrderID->Visible) { ?>
    <?php if ($Page->OrderID->ShowGroupHeaderAsRow) { ?>
    <th data-name="OrderID">&nbsp;</th>
    <?php } else { ?>
    <th data-name="OrderID" class="<?= $Page->OrderID->headerCellClass() ?>"><div class="Sales_By_Customer_Compact_OrderID"><?= $Page->renderFieldHeader($Page->OrderID) ?></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->ExtendedPrice->Visible) { ?>
    <th data-name="ExtendedPrice" class="<?= $Page->ExtendedPrice->headerCellClass() ?>"><div class="ew-table-header-btn"><div class="ew-table-header-caption"><?= $Page->ExtendedPrice->caption() ?><span class="ew-compact-header-summary"> (<?= $Language->phrase("RptSum") ?>)</span></div></div></th>
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
            <span class="ew-summary-caption Sales_By_Customer_Compact_CompanyName"><?= $Page->renderFieldHeader($Page->CompanyName) ?></span><?= $Language->phrase("SummaryColon") ?><span<?= $Page->CompanyName->viewAttributes() ?>><?= $Page->CompanyName->GroupViewValue ?></span>
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
            <span class="ew-summary-caption Sales_By_Customer_Compact_OrderID"><?= $Page->renderFieldHeader($Page->OrderID) ?></span><?= $Language->phrase("SummaryColon") ?><span<?= $Page->OrderID->viewAttributes() ?>><?= $Page->OrderID->GroupViewValue ?></span>
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
    }
?>
<?php if ($Page->TotalGroups > 0) { ?>
<?php
    $Page->ExtendedPrice->getSum($Page->OrderID->Records); // Get Sum
    $Page->resetAttributes();
    $Page->RowType = ROWTYPE_TOTAL;
    $Page->RowTotalType = ROWTOTAL_GROUP;
    $Page->RowTotalSubType = ROWTOTAL_FOOTER;
    $Page->RowGroupLevel = 2;
    $Page->renderRow();
?>
    <tr<?= $Page->rowAttributes(); ?>>
<?php if ($Page->CompanyName->Visible) { ?>
        <td data-field="CompanyName"<?= $Page->CompanyName->cellAttributes() ?>>
<span<?= $Page->CompanyName->viewAttributes() ?>><?= $Page->CompanyName->GroupViewValue ?></span></td>
<?php } ?>
<?php if ($Page->OrderID->Visible) { ?>
        <td data-field="OrderID"<?= $Page->OrderID->cellAttributes() ?>><span<?= $Page->OrderID->viewAttributes() ?>><?= $Page->OrderID->GroupViewValue ?></span>&nbsp;<span class="ew-detail-count">(<?= FormatNumber($Page->OrderID->Count, Config("DEFAULT_NUMBER_FORMAT")) ?><?= $Language->phrase("RptDtlRec") ?>)</span></td>
<?php } ?>
<?php if ($Page->ExtendedPrice->Visible) { ?>
        <td data-field="ExtendedPrice"<?= $Page->ExtendedPrice->cellAttributes() ?>>
<span<?= $Page->ExtendedPrice->viewAttributes() ?>>
<?php if ($Page->ExtendedPrice->linkAttributes() != "") { ?>
<a<?= $Page->ExtendedPrice->linkAttributes() ?>><?= $Page->ExtendedPrice->SumViewValue ?></a>
<?php } else { ?>
<?= $Page->ExtendedPrice->SumViewValue ?>
<?php } ?>
</span>
</td>
<?php } ?>
    </tr>
<?php } ?>
<?php
    } // End group level 1
?>
<?php if ($Page->TotalGroups > 0) { ?>
<?php
    $Page->ExtendedPrice->getSum($Page->CompanyName->Records); // Get Sum
    $Page->resetAttributes();
    $Page->RowType = ROWTYPE_TOTAL;
    $Page->RowTotalType = ROWTOTAL_GROUP;
    $Page->RowTotalSubType = ROWTOTAL_FOOTER;
    $Page->RowGroupLevel = 1;
    $Page->renderRow();
?>
    <tr<?= $Page->rowAttributes(); ?>>
<?php if ($Page->CompanyName->Visible) { ?>
        <td data-field="CompanyName"<?= $Page->CompanyName->cellAttributes() ?>><span class="ew-summary-text"><?= $Language->phrase("Summary") ?></span>&nbsp;<span class="ew-summary-value"><span<?= $Page->CompanyName->viewAttributes() ?>><?= $Page->CompanyName->GroupViewValue ?></span></span>&nbsp;<span class="ew-detail-count">(<?= FormatNumber($Page->CompanyName->Count, Config("DEFAULT_NUMBER_FORMAT")) ?><?= $Language->phrase("RptDtlRec") ?>)</span></td>
<?php } ?>
<?php if ($Page->OrderID->Visible) { ?>
        <td data-field="OrderID"<?= $Page->CompanyName->cellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->ExtendedPrice->Visible) { ?>
        <td data-field="ExtendedPrice"<?= $Page->ExtendedPrice->cellAttributes() ?>>
<span<?= $Page->ExtendedPrice->viewAttributes() ?>>
<?php if ($Page->ExtendedPrice->linkAttributes() != "") { ?>
<a<?= $Page->ExtendedPrice->linkAttributes() ?>><?= $Page->ExtendedPrice->SumViewValue ?></a>
<?php } else { ?>
<?= $Page->ExtendedPrice->SumViewValue ?>
<?php } ?>
</span>
</td>
<?php } ?>
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
<?php
    $Page->resetAttributes();
    $Page->RowType = ROWTYPE_TOTAL;
    $Page->RowTotalType = ROWTOTAL_GRAND;
    $Page->RowTotalSubType = ROWTOTAL_FOOTER;
    $Page->RowAttrs["class"] = "ew-rpt-grand-summary";
    $Page->renderRow();
?>
    <tr<?= $Page->rowAttributes(); ?>>
        <td><?= $Language->phrase("RptGrandSummary") ?>&nbsp;<span class="ew-detail-count">(<?= FormatNumber($Page->TotalCount, Config("DEFAULT_NUMBER_FORMAT")) ?><?= $Language->phrase("RptDtlRec") ?>)</span></td>
<?php if ($Page->OrderID->Visible) { ?>
        <td data-field="OrderID"></td>
<?php } ?>
<?php if ($Page->ExtendedPrice->Visible) { ?>
        <td data-field="ExtendedPrice"<?= $Page->ExtendedPrice->cellAttributes() ?>>
<span<?= $Page->ExtendedPrice->viewAttributes() ?>>
<?php if ($Page->ExtendedPrice->linkAttributes() != "") { ?>
<a<?= $Page->ExtendedPrice->linkAttributes() ?>><?= $Page->ExtendedPrice->SumViewValue ?></a>
<?php } else { ?>
<?= $Page->ExtendedPrice->SumViewValue ?>
<?php } ?>
</span>
</td>
<?php } ?>
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
