<?php

namespace PHPMaker2023\demo2023;

// Page object
$SalesByYearSummary = &$Page;
?>
<?php if (!$Page->isExport() && !$Page->DrillDown && !$DashboardReport) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { Sales_By_Year: currentTable } });
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
<div id="gmp_Sales_By_Year" class="card-body ew-grid-middle-panel <?= $Page->TableContainerClass ?>">
<?php } ?>
<table class="<?= $Page->TableClass ?>">
<thead>
	<!-- Table header -->
    <tr class="ew-table-header">
<?php if ($Page->Year->Visible) { ?>
    <?php if ($Page->Year->ShowGroupHeaderAsRow) { ?>
    <th data-name="Year">&nbsp;</th>
    <?php } else { ?>
    <th data-name="Year" class="<?= $Page->Year->headerCellClass() ?>"><div class="Sales_By_Year_Year"><?= $Page->renderFieldHeader($Page->Year) ?></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->ShippedDate->Visible) { ?>
    <?php if ($Page->ShippedDate->ShowGroupHeaderAsRow) { ?>
    <th data-name="ShippedDate">&nbsp;</th>
    <?php } else { ?>
    <th data-name="ShippedDate" class="<?= $Page->ShippedDate->headerCellClass() ?>"><div class="Sales_By_Year_ShippedDate"><?= $Page->renderFieldHeader($Page->ShippedDate) ?></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->OrderID->Visible) { ?>
    <th data-name="OrderID" class="<?= $Page->OrderID->headerCellClass() ?>"><div class="Sales_By_Year_OrderID"><?= $Page->renderFieldHeader($Page->OrderID) ?></div></th>
<?php } ?>
<?php if ($Page->Subtotal->Visible) { ?>
    <th data-name="Subtotal" class="<?= $Page->Subtotal->headerCellClass() ?>"><div class="Sales_By_Year_Subtotal"><?= $Page->renderFieldHeader($Page->Subtotal) ?></div></th>
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
    $where = DetailFilterSql($Page->Year, $Page->getSqlFirstGroupField(), $Page->Year->groupValue(), $Page->Dbid);
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
    $Page->Year->Records = &$Page->DetailRecords;
    $Page->Year->LevelBreak = true; // Set field level break
        $Page->GroupCounter[1] = $Page->GroupCount;
        $Page->Year->getCnt($Page->Year->Records); // Get record count
?>
<?php if ($Page->Year->Visible && $Page->Year->ShowGroupHeaderAsRow) { ?>
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
<?php if ($Page->Year->Visible) { ?>
        <?php $Page->Year->CellAttrs->appendClass("ew-rpt-grp-caret"); ?>
        <td data-field="Year"<?= $Page->Year->cellAttributes(); ?>><i class="ew-group-toggle fa-solid fa-caret-down"></i></td>
        <?php $Page->Year->CellAttrs->removeClass("ew-rpt-grp-caret"); ?>
<?php } ?>
        <td data-field="Year" colspan="<?= ($Page->GroupColumnCount + $Page->DetailColumnCount - 1) ?>"<?= $Page->Year->cellAttributes() ?>>
            <span class="ew-summary-caption Sales_By_Year_Year"><?= $Page->renderFieldHeader($Page->Year) ?></span><?= $Language->phrase("SummaryColon") ?><span<?= $Page->Year->viewAttributes() ?>><?= $Page->Year->GroupViewValue ?></span>
            <span class="ew-summary-count">(<span class="ew-aggregate-caption"><?= $Language->phrase("RptCnt") ?></span><span class="ew-aggregate-equal"><?= $Language->phrase("AggregateEqual") ?></span><span class="ew-aggregate-value"><?= FormatNumber($Page->Year->Count, Config("DEFAULT_NUMBER_FORMAT")) ?></span>)</span>
        </td>
    </tr>
<?php } ?>
<?php
    $Page->ShippedDate->getDistinctValues($Page->Year->Records, $Page->ShippedDate->getSort());
    $Page->setGroupCount(count($Page->ShippedDate->DistinctValues), $Page->GroupCounter[1]);
    $Page->GroupCounter[2] = 0; // Init group count index
    foreach ($Page->ShippedDate->DistinctValues as $ShippedDate) { // Load records for this distinct value
        $Page->ShippedDate->setGroupValue($ShippedDate); // Set group value
        $Page->ShippedDate->getDistinctRecords($Page->Year->Records, $Page->ShippedDate->groupValue());
        $Page->ShippedDate->LevelBreak = true; // Set field level break
        $Page->GroupCounter[2]++;
        $Page->ShippedDate->getCnt($Page->ShippedDate->Records); // Get record count
        $Page->setGroupCount($Page->ShippedDate->Count, $Page->GroupCounter[1], $Page->GroupCounter[2]);
?>
<?php if ($Page->ShippedDate->Visible && $Page->ShippedDate->ShowGroupHeaderAsRow) { ?>
<?php
        // Render header row
        $Page->ShippedDate->setDbValue($ShippedDate); // Set current value for ShippedDate
        $Page->resetAttributes();
        $Page->RowType = ROWTYPE_TOTAL;
        $Page->RowTotalType = ROWTOTAL_GROUP;
        $Page->RowTotalSubType = ROWTOTAL_HEADER;
        $Page->RowGroupLevel = 2;
        $Page->renderRow();
?>
    <tr<?= $Page->rowAttributes(); ?>>
<?php if ($Page->Year->Visible) { ?>
        <td data-field="Year"<?= $Page->Year->cellAttributes(); ?>></td>
<?php } ?>
<?php if ($Page->ShippedDate->Visible) { ?>
        <?php $Page->ShippedDate->CellAttrs->appendClass("ew-rpt-grp-caret"); ?>
        <td data-field="ShippedDate"<?= $Page->ShippedDate->cellAttributes(); ?>><i class="ew-group-toggle fa-solid fa-caret-down"></i></td>
        <?php $Page->ShippedDate->CellAttrs->removeClass("ew-rpt-grp-caret"); ?>
<?php } ?>
        <td data-field="ShippedDate" colspan="<?= ($Page->GroupColumnCount + $Page->DetailColumnCount - 2) ?>"<?= $Page->ShippedDate->cellAttributes() ?>>
            <span class="ew-summary-caption Sales_By_Year_ShippedDate"><?= $Page->renderFieldHeader($Page->ShippedDate) ?></span><?= $Language->phrase("SummaryColon") ?><span<?= $Page->ShippedDate->viewAttributes() ?>><?= $Page->ShippedDate->GroupViewValue ?></span>
            <span class="ew-summary-count">(<span class="ew-aggregate-caption"><?= $Language->phrase("RptCnt") ?></span><span class="ew-aggregate-equal"><?= $Language->phrase("AggregateEqual") ?></span><span class="ew-aggregate-value"><?= FormatNumber($Page->ShippedDate->Count, Config("DEFAULT_NUMBER_FORMAT")) ?></span>)</span>
        </td>
    </tr>
<?php } ?>
<?php
        $Page->RecordCount = 0; // Reset record count
        foreach ($Page->ShippedDate->Records as $record) {
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
<?php if ($Page->Year->Visible) { ?>
    <?php if ($Page->Year->ShowGroupHeaderAsRow) { ?>
        <td data-field="Year"<?= $Page->Year->cellAttributes() ?>></td>
    <?php } else { ?>
        <td data-field="Year"<?= $Page->Year->cellAttributes() ?>><span<?= $Page->Year->viewAttributes() ?>><?= $Page->Year->GroupViewValue ?></span></td>
    <?php } ?>
<?php } ?>
<?php if ($Page->ShippedDate->Visible) { ?>
    <?php if ($Page->ShippedDate->ShowGroupHeaderAsRow) { ?>
        <td data-field="ShippedDate"<?= $Page->ShippedDate->cellAttributes() ?>></td>
    <?php } else { ?>
        <td data-field="ShippedDate"<?= $Page->ShippedDate->cellAttributes() ?>><span<?= $Page->ShippedDate->viewAttributes() ?>><?= $Page->ShippedDate->GroupViewValue ?></span></td>
    <?php } ?>
<?php } ?>
<?php if ($Page->OrderID->Visible) { ?>
        <td data-field="OrderID"<?= $Page->OrderID->cellAttributes() ?>>
<span<?= $Page->OrderID->viewAttributes() ?>>
<?= $Page->OrderID->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->Subtotal->Visible) { ?>
        <td data-field="Subtotal"<?= $Page->Subtotal->cellAttributes() ?>>
<span<?= $Page->Subtotal->viewAttributes() ?>>
<?= $Page->Subtotal->getViewValue() ?></span>
</td>
<?php } ?>
    </tr>
<?php
    }
?>
<?php if ($Page->TotalGroups > 0) { ?>
<?php
    $Page->Subtotal->getSum($Page->ShippedDate->Records); // Get Sum
    $Page->resetAttributes();
    $Page->RowType = ROWTYPE_TOTAL;
    $Page->RowTotalType = ROWTOTAL_GROUP;
    $Page->RowTotalSubType = ROWTOTAL_FOOTER;
    $Page->RowGroupLevel = 2;
    $Page->renderRow();
?>
<?php if ($Page->ShippedDate->ShowCompactSummaryFooter) { ?>
    <tr<?= $Page->rowAttributes(); ?>>
<?php if ($Page->Year->Visible) { ?>
    <?php if ($Page->Year->ShowGroupHeaderAsRow) { ?>
        <td data-field="Year"<?= $Page->Year->cellAttributes() ?>></td>
    <?php } elseif ($Page->RowGroupLevel != 1) { ?>
        <td data-field="Year"<?= $Page->Year->cellAttributes() ?>>
        </td>
    <?php } else { ?>
        <td data-field="Year"<?= $Page->Year->cellAttributes() ?>>
            <span class="ew-summary-count"><span class="ew-aggregate-caption"><?= $Language->phrase("RptCnt") ?></span><span class="ew-aggregate-equal"><?= $Language->phrase("AggregateEqual") ?></span><span class="ew-aggregate-value"><?= FormatNumber($Page->Year->Count, Config("DEFAULT_NUMBER_FORMAT")) ?></span></span>
        </td>
    <?php } ?>
<?php } ?>
<?php if ($Page->ShippedDate->Visible) { ?>
    <?php if ($Page->ShippedDate->ShowGroupHeaderAsRow) { ?>
        <td data-field="ShippedDate"<?= $Page->ShippedDate->cellAttributes() ?>></td>
    <?php } elseif ($Page->RowGroupLevel != 2) { ?>
        <td data-field="ShippedDate"<?= $Page->ShippedDate->cellAttributes() ?>>
        </td>
    <?php } else { ?>
        <td data-field="ShippedDate"<?= $Page->ShippedDate->cellAttributes() ?>>
            <span class="ew-summary-count"><span class="ew-aggregate-caption"><?= $Language->phrase("RptCnt") ?></span><span class="ew-aggregate-equal"><?= $Language->phrase("AggregateEqual") ?></span><span class="ew-aggregate-value"><?= FormatNumber($Page->ShippedDate->Count, Config("DEFAULT_NUMBER_FORMAT")) ?></span></span>
        </td>
    <?php } ?>
<?php } ?>
<?php if ($Page->OrderID->Visible) { ?>
        <td data-field="OrderID"<?= $Page->ShippedDate->cellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->Subtotal->Visible) { ?>
        <td data-field="Subtotal"<?= $Page->ShippedDate->cellAttributes() ?>><span class="ew-aggregate-caption"><?= $Language->phrase("RptSum") ?></span><span class="ew-aggregate-equal"><?= $Language->phrase("AggregateEqual") ?></span><span class="ew-aggregate-value"><span<?= $Page->Subtotal->viewAttributes() ?>><?= $Page->Subtotal->SumViewValue ?></span></span></td>
<?php } ?>
    </tr>
<?php } else { ?>
    <tr<?= $Page->rowAttributes(); ?>>
<?php if ($Page->Year->Visible) { ?>
        <td data-field="Year"<?= $Page->Year->cellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->SubGroupColumnCount + $Page->DetailColumnCount > 0) { ?>
        <td colspan="<?= ($Page->SubGroupColumnCount + $Page->DetailColumnCount) ?>"<?= $Page->ShippedDate->cellAttributes() ?>><?= str_replace(["%v", "%c"], [$Page->ShippedDate->GroupViewValue, $Page->ShippedDate->caption()], $Language->phrase("RptSumHead")) ?> <span class="ew-dir-ltr">(<?= FormatNumber($Page->ShippedDate->Count, Config("DEFAULT_NUMBER_FORMAT")) ?><?= $Language->phrase("RptDtlRec") ?>)</span></td>
<?php } ?>
    </tr>
    <tr<?= $Page->rowAttributes(); ?>>
<?php if ($Page->Year->Visible) { ?>
        <td data-field="Year"<?= $Page->Year->cellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->GroupColumnCount > 0) { ?>
        <td colspan="<?= ($Page->GroupColumnCount - 1) ?>"<?= $Page->ShippedDate->cellAttributes() ?>><?= $Language->phrase("RptSum") ?></td>
<?php } ?>
<?php if ($Page->OrderID->Visible) { ?>
        <td data-field="OrderID"<?= $Page->ShippedDate->cellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->Subtotal->Visible) { ?>
        <td data-field="Subtotal"<?= $Page->Subtotal->cellAttributes() ?>>
<span<?= $Page->Subtotal->viewAttributes() ?>>
<?= $Page->Subtotal->SumViewValue ?></span>
</td>
<?php } ?>
    </tr>
<?php } ?>
<?php } ?>
<?php
    } // End group level 1
?>
<?php if ($Page->TotalGroups > 0) { ?>
<?php
    $Page->Subtotal->getSum($Page->Year->Records); // Get Sum
    $Page->resetAttributes();
    $Page->RowType = ROWTYPE_TOTAL;
    $Page->RowTotalType = ROWTOTAL_GROUP;
    $Page->RowTotalSubType = ROWTOTAL_FOOTER;
    $Page->RowGroupLevel = 1;
    $Page->renderRow();
?>
<?php if ($Page->Year->ShowCompactSummaryFooter) { ?>
    <tr<?= $Page->rowAttributes(); ?>>
<?php if ($Page->Year->Visible) { ?>
    <?php if ($Page->Year->ShowGroupHeaderAsRow) { ?>
        <td data-field="Year"<?= $Page->Year->cellAttributes() ?>></td>
    <?php } elseif ($Page->RowGroupLevel != 1) { ?>
        <td data-field="Year"<?= $Page->Year->cellAttributes() ?>>
        </td>
    <?php } else { ?>
        <td data-field="Year"<?= $Page->Year->cellAttributes() ?>>
            <span class="ew-summary-count"><span class="ew-aggregate-caption"><?= $Language->phrase("RptCnt") ?></span><span class="ew-aggregate-equal"><?= $Language->phrase("AggregateEqual") ?></span><span class="ew-aggregate-value"><?= FormatNumber($Page->Year->Count, Config("DEFAULT_NUMBER_FORMAT")) ?></span></span>
        </td>
    <?php } ?>
<?php } ?>
<?php if ($Page->ShippedDate->Visible) { ?>
    <?php if ($Page->ShippedDate->ShowGroupHeaderAsRow) { ?>
        <td data-field="ShippedDate"<?= $Page->Year->cellAttributes() ?>></td>
    <?php } elseif ($Page->RowGroupLevel != 2) { ?>
        <td data-field="ShippedDate"<?= $Page->Year->cellAttributes() ?>>
        </td>
    <?php } else { ?>
        <td data-field="ShippedDate"<?= $Page->Year->cellAttributes() ?>>
            <span class="ew-summary-count"><span class="ew-aggregate-caption"><?= $Language->phrase("RptCnt") ?></span><span class="ew-aggregate-equal"><?= $Language->phrase("AggregateEqual") ?></span><span class="ew-aggregate-value"><?= FormatNumber($Page->ShippedDate->Count, Config("DEFAULT_NUMBER_FORMAT")) ?></span></span>
        </td>
    <?php } ?>
<?php } ?>
<?php if ($Page->OrderID->Visible) { ?>
        <td data-field="OrderID"<?= $Page->Year->cellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->Subtotal->Visible) { ?>
        <td data-field="Subtotal"<?= $Page->Year->cellAttributes() ?>><span class="ew-aggregate-caption"><?= $Language->phrase("RptSum") ?></span><span class="ew-aggregate-equal"><?= $Language->phrase("AggregateEqual") ?></span><span class="ew-aggregate-value"><span<?= $Page->Subtotal->viewAttributes() ?>><?= $Page->Subtotal->SumViewValue ?></span></span></td>
<?php } ?>
    </tr>
<?php } else { ?>
    <tr<?= $Page->rowAttributes(); ?>>
<?php if ($Page->GroupColumnCount + $Page->DetailColumnCount > 0) { ?>
        <td colspan="<?= ($Page->GroupColumnCount + $Page->DetailColumnCount) ?>"<?= $Page->Year->cellAttributes() ?>><?= str_replace(["%v", "%c"], [$Page->Year->GroupViewValue, $Page->Year->caption()], $Language->phrase("RptSumHead")) ?> <span class="ew-dir-ltr">(<?= FormatNumber($Page->Year->Count, Config("DEFAULT_NUMBER_FORMAT")) ?><?= $Language->phrase("RptDtlRec") ?>)</span></td>
<?php } ?>
    </tr>
    <tr<?= $Page->rowAttributes(); ?>>
<?php if ($Page->GroupColumnCount > 0) { ?>
        <td colspan="<?= ($Page->GroupColumnCount - 0) ?>"<?= $Page->Year->cellAttributes() ?>><?= $Language->phrase("RptSum") ?></td>
<?php } ?>
<?php if ($Page->OrderID->Visible) { ?>
        <td data-field="OrderID"<?= $Page->Year->cellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->Subtotal->Visible) { ?>
        <td data-field="Subtotal"<?= $Page->Subtotal->cellAttributes() ?>>
<span<?= $Page->Subtotal->viewAttributes() ?>>
<?= $Page->Subtotal->SumViewValue ?></span>
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
<?php if ($Page->Year->ShowCompactSummaryFooter) { ?>
    <tr<?= $Page->rowAttributes() ?>><td colspan="<?= ($Page->GroupColumnCount + $Page->DetailColumnCount) ?>"><?= $Language->phrase("RptGrandSummary") ?> <span class="ew-summary-count">(<span class="ew-aggregate-caption"><?= $Language->phrase("RptCnt") ?></span><span class="ew-aggregate-equal"><?= $Language->phrase("AggregateEqual") ?></span><span class="ew-aggregate-value"><?= FormatNumber($Page->TotalCount, Config("DEFAULT_NUMBER_FORMAT")) ?></span>)</span></td></tr>
    <tr<?= $Page->rowAttributes() ?>>
<?php if ($Page->GroupColumnCount > 0) { ?>
        <td colspan="<?= $Page->GroupColumnCount ?>" class="ew-rpt-grp-aggregate"></td>
<?php } ?>
<?php if ($Page->OrderID->Visible) { ?>
        <td data-field="OrderID"<?= $Page->OrderID->cellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->Subtotal->Visible) { ?>
        <td data-field="Subtotal"<?= $Page->Subtotal->cellAttributes() ?>><span class="ew-aggregate-caption"><?= $Language->phrase("RptSum") ?></span><span class="ew-aggregate-equal"><?= $Language->phrase("AggregateEqual") ?></span><span class="ew-aggregate-value"><span<?= $Page->Subtotal->viewAttributes() ?>><?= $Page->Subtotal->SumViewValue ?></span></span></td>
<?php } ?>
    </tr>
<?php } else { ?>
    <tr<?= $Page->rowAttributes() ?>><td colspan="<?= ($Page->GroupColumnCount + $Page->DetailColumnCount) ?>"><?= $Language->phrase("RptGrandSummary") ?> <span class="ew-summary-count">(<?= FormatNumber($Page->TotalCount, Config("DEFAULT_NUMBER_FORMAT")) ?><?= $Language->phrase("RptDtlRec") ?>)</span></td></tr>
    <tr<?= $Page->rowAttributes() ?>>
<?php if ($Page->GroupColumnCount > 0) { ?>
        <td colspan="<?= $Page->GroupColumnCount ?>" class="ew-rpt-grp-aggregate"><?= $Language->phrase("RptSum") ?></td>
<?php } ?>
<?php if ($Page->OrderID->Visible) { ?>
        <td data-field="OrderID"<?= $Page->OrderID->cellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->Subtotal->Visible) { ?>
        <td data-field="Subtotal"<?= $Page->Subtotal->cellAttributes() ?>>
<span<?= $Page->Subtotal->viewAttributes() ?>>
<?= $Page->Subtotal->SumViewValue ?></span>
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
