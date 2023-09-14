<?php

namespace PHPMaker2023\demo2023;

// Page object
$AlphabeticalListOfProductsSummary = &$Page;
?>
<?php if (!$Page->isExport() && !$Page->DrillDown && !$DashboardReport) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { Alphabetical_List_of_Products: currentTable } });
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
<div id="gmp_Alphabetical_List_of_Products" class="card-body ew-grid-middle-panel <?= $Page->TableContainerClass ?>">
<?php } ?>
<table class="<?= $Page->TableClass ?>">
<thead>
	<!-- Table header -->
    <tr class="ew-table-header">
<?php if ($Page->ProductName2->Visible) { ?>
    <?php if ($Page->ProductName2->ShowGroupHeaderAsRow) { ?>
    <th data-name="ProductName2">&nbsp;</th>
    <?php } else { ?>
    <th data-name="ProductName2" class="<?= $Page->ProductName2->headerCellClass() ?>"><div class="Alphabetical_List_of_Products_ProductName2"><?= $Page->renderFieldHeader($Page->ProductName2) ?></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->ProductName->Visible) { ?>
    <th data-name="ProductName" class="<?= $Page->ProductName->headerCellClass() ?>"><div class="Alphabetical_List_of_Products_ProductName"><?= $Page->renderFieldHeader($Page->ProductName) ?></div></th>
<?php } ?>
<?php if ($Page->CategoryName->Visible) { ?>
    <th data-name="CategoryName" class="<?= $Page->CategoryName->headerCellClass() ?>"><div class="Alphabetical_List_of_Products_CategoryName"><?= $Page->renderFieldHeader($Page->CategoryName) ?></div></th>
<?php } ?>
<?php if ($Page->QuantityPerUnit->Visible) { ?>
    <th data-name="QuantityPerUnit" class="<?= $Page->QuantityPerUnit->headerCellClass() ?>"><div class="Alphabetical_List_of_Products_QuantityPerUnit"><?= $Page->renderFieldHeader($Page->QuantityPerUnit) ?></div></th>
<?php } ?>
<?php if ($Page->UnitsInStock->Visible) { ?>
    <th data-name="UnitsInStock" class="<?= $Page->UnitsInStock->headerCellClass() ?>"><div class="Alphabetical_List_of_Products_UnitsInStock"><?= $Page->renderFieldHeader($Page->UnitsInStock) ?></div></th>
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
    $where = DetailFilterSql($Page->ProductName2, $Page->getSqlFirstGroupField(), $Page->ProductName2->groupValue(), $Page->Dbid);
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
    $Page->setGroupCount($Page->DetailRecordCount, $Page->GroupCount);

    // Load detail records
    $Page->ProductName2->Records = &$Page->DetailRecords;
    $Page->ProductName2->LevelBreak = true; // Set field level break
        $Page->GroupCounter[1] = $Page->GroupCount;
        $Page->ProductName2->getCnt($Page->ProductName2->Records); // Get record count
        $Page->setGroupCount($Page->ProductName2->Count, $Page->GroupCounter[1]);
?>
<?php if ($Page->ProductName2->Visible && $Page->ProductName2->ShowGroupHeaderAsRow) { ?>
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
<?php if ($Page->ProductName2->Visible) { ?>
        <?php $Page->ProductName2->CellAttrs->appendClass("ew-rpt-grp-caret"); ?>
        <td data-field="ProductName2"<?= $Page->ProductName2->cellAttributes(); ?>><i class="ew-group-toggle fa-solid fa-caret-down"></i></td>
        <?php $Page->ProductName2->CellAttrs->removeClass("ew-rpt-grp-caret"); ?>
<?php } ?>
        <td data-field="ProductName2" colspan="<?= ($Page->GroupColumnCount + $Page->DetailColumnCount - 1) ?>"<?= $Page->ProductName2->cellAttributes() ?>>
            <span class="ew-summary-caption Alphabetical_List_of_Products_ProductName2"><?= $Page->renderFieldHeader($Page->ProductName2) ?></span><?= $Language->phrase("SummaryColon") ?><span<?= $Page->ProductName2->viewAttributes() ?>><?= $Page->ProductName2->GroupViewValue ?></span>
            <span class="ew-summary-count">(<span class="ew-aggregate-caption"><?= $Language->phrase("RptCnt") ?></span><span class="ew-aggregate-equal"><?= $Language->phrase("AggregateEqual") ?></span><span class="ew-aggregate-value"><?= FormatNumber($Page->ProductName2->Count, Config("DEFAULT_NUMBER_FORMAT")) ?></span>)</span>
        </td>
    </tr>
<?php } ?>
<?php
        $Page->RecordCount = 0; // Reset record count
        foreach ($Page->ProductName2->Records as $record) {
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
<?php if ($Page->ProductName2->Visible) { ?>
    <?php if ($Page->ProductName2->ShowGroupHeaderAsRow) { ?>
        <td data-field="ProductName2"<?= $Page->ProductName2->cellAttributes() ?>></td>
    <?php } else { ?>
        <td data-field="ProductName2"<?= $Page->ProductName2->cellAttributes() ?>><span<?= $Page->ProductName2->viewAttributes() ?>><?= $Page->ProductName2->GroupViewValue ?></span></td>
    <?php } ?>
<?php } ?>
<?php if ($Page->ProductName->Visible) { ?>
        <td data-field="ProductName"<?= $Page->ProductName->cellAttributes() ?>>
<span<?= $Page->ProductName->viewAttributes() ?>>
<?= $Page->ProductName->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->CategoryName->Visible) { ?>
        <td data-field="CategoryName"<?= $Page->CategoryName->cellAttributes() ?>>
<span<?= $Page->CategoryName->viewAttributes() ?>>
<?= $Page->CategoryName->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->QuantityPerUnit->Visible) { ?>
        <td data-field="QuantityPerUnit"<?= $Page->QuantityPerUnit->cellAttributes() ?>>
<span<?= $Page->QuantityPerUnit->viewAttributes() ?>>
<?= $Page->QuantityPerUnit->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->UnitsInStock->Visible) { ?>
        <td data-field="UnitsInStock"<?= $Page->UnitsInStock->cellAttributes() ?>>
<span<?= $Page->UnitsInStock->viewAttributes() ?>>
<?= $Page->UnitsInStock->getViewValue() ?></span>
</td>
<?php } ?>
    </tr>
<?php
    }
?>
<?php if ($Page->TotalGroups > 0) { ?>
<?php
    $Page->UnitsInStock->getSum($Page->ProductName2->Records); // Get Sum
    $Page->resetAttributes();
    $Page->RowType = ROWTYPE_TOTAL;
    $Page->RowTotalType = ROWTOTAL_GROUP;
    $Page->RowTotalSubType = ROWTOTAL_FOOTER;
    $Page->RowGroupLevel = 1;
    $Page->renderRow();
?>
<?php if ($Page->ProductName2->ShowCompactSummaryFooter) { ?>
    <tr<?= $Page->rowAttributes(); ?>>
<?php if ($Page->ProductName2->Visible) { ?>
    <?php if ($Page->ProductName2->ShowGroupHeaderAsRow) { ?>
        <td data-field="ProductName2"<?= $Page->ProductName2->cellAttributes() ?>></td>
    <?php } elseif ($Page->RowGroupLevel != 1) { ?>
        <td data-field="ProductName2"<?= $Page->ProductName2->cellAttributes() ?>>
        </td>
    <?php } else { ?>
        <td data-field="ProductName2"<?= $Page->ProductName2->cellAttributes() ?>>
            <span class="ew-summary-count"><span class="ew-aggregate-caption"><?= $Language->phrase("RptCnt") ?></span><span class="ew-aggregate-equal"><?= $Language->phrase("AggregateEqual") ?></span><span class="ew-aggregate-value"><?= FormatNumber($Page->ProductName2->Count, Config("DEFAULT_NUMBER_FORMAT")) ?></span></span>
        </td>
    <?php } ?>
<?php } ?>
<?php if ($Page->ProductName->Visible) { ?>
        <td data-field="ProductName"<?= $Page->ProductName2->cellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->CategoryName->Visible) { ?>
        <td data-field="CategoryName"<?= $Page->ProductName2->cellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->QuantityPerUnit->Visible) { ?>
        <td data-field="QuantityPerUnit"<?= $Page->ProductName2->cellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->UnitsInStock->Visible) { ?>
        <td data-field="UnitsInStock"<?= $Page->ProductName2->cellAttributes() ?>><span class="ew-aggregate-caption"><?= $Language->phrase("RptSum") ?></span><span class="ew-aggregate-equal"><?= $Language->phrase("AggregateEqual") ?></span><span class="ew-aggregate-value"><span<?= $Page->UnitsInStock->viewAttributes() ?>><?= $Page->UnitsInStock->SumViewValue ?></span></span></td>
<?php } ?>
    </tr>
<?php } else { ?>
    <tr<?= $Page->rowAttributes(); ?>>
<?php if ($Page->GroupColumnCount + $Page->DetailColumnCount > 0) { ?>
        <td colspan="<?= ($Page->GroupColumnCount + $Page->DetailColumnCount) ?>"<?= $Page->ProductName2->cellAttributes() ?>><?= str_replace(["%v", "%c"], [$Page->ProductName2->GroupViewValue, $Page->ProductName2->caption()], $Language->phrase("RptSumHead")) ?> <span class="ew-dir-ltr">(<?= FormatNumber($Page->ProductName2->Count, Config("DEFAULT_NUMBER_FORMAT")) ?><?= $Language->phrase("RptDtlRec") ?>)</span></td>
<?php } ?>
    </tr>
    <tr<?= $Page->rowAttributes(); ?>>
<?php if ($Page->GroupColumnCount > 0) { ?>
        <td colspan="<?= ($Page->GroupColumnCount - 0) ?>"<?= $Page->ProductName2->cellAttributes() ?>><?= $Language->phrase("RptSum") ?></td>
<?php } ?>
<?php if ($Page->ProductName->Visible) { ?>
        <td data-field="ProductName"<?= $Page->ProductName2->cellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->CategoryName->Visible) { ?>
        <td data-field="CategoryName"<?= $Page->ProductName2->cellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->QuantityPerUnit->Visible) { ?>
        <td data-field="QuantityPerUnit"<?= $Page->ProductName2->cellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->UnitsInStock->Visible) { ?>
        <td data-field="UnitsInStock"<?= $Page->UnitsInStock->cellAttributes() ?>>
<span<?= $Page->UnitsInStock->viewAttributes() ?>>
<?= $Page->UnitsInStock->SumViewValue ?></span>
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
<?php if (($Page->StopGroup - $Page->StartGroup + 1) != $Page->TotalGroups) { ?>
<?php
    $Page->resetAttributes();
    $Page->RowType = ROWTYPE_TOTAL;
    $Page->RowTotalType = ROWTOTAL_PAGE;
    $Page->RowTotalSubType = ROWTOTAL_FOOTER;
    $Page->RowAttrs["class"] = "ew-rpt-page-summary";
    $Page->renderRow();
?>
<?php if ($Page->ProductName2->ShowCompactSummaryFooter) { ?>
    <tr<?= $Page->rowAttributes(); ?>><td colspan="<?= ($Page->GroupColumnCount + $Page->DetailColumnCount) ?>"><?= $Language->phrase("RptPageSummary") ?> <span class="ew-summary-count">(<span class="ew-aggregate-caption"><?= $Language->phrase("RptCnt") ?></span><span class="ew-aggregate-equal"><?= $Language->phrase("AggregateEqual") ?></span><span class="ew-aggregate-value"><?= FormatNumber($Page->PageTotalCount, Config("DEFAULT_NUMBER_FORMAT")) ?></span>)</span></td></tr>
    <tr<?= $Page->rowAttributes(); ?>>
<?php if ($Page->GroupColumnCount > 0) { ?>
        <td colspan="<?= $Page->GroupColumnCount ?>" class="ew-rpt-grp-aggregate"></td>
<?php } ?>
<?php if ($Page->ProductName->Visible) { ?>
        <td data-field="ProductName"<?= $Page->ProductName->cellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->CategoryName->Visible) { ?>
        <td data-field="CategoryName"<?= $Page->CategoryName->cellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->QuantityPerUnit->Visible) { ?>
        <td data-field="QuantityPerUnit"<?= $Page->QuantityPerUnit->cellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->UnitsInStock->Visible) { ?>
        <td data-field="UnitsInStock"<?= $Page->UnitsInStock->cellAttributes() ?>><span class="ew-aggregate-caption"><?= $Language->phrase("RptSum") ?></span><span class="ew-aggregate-equal"><?= $Language->phrase("AggregateEqual") ?></span><span class="ew-aggregate-value"><span<?= $Page->UnitsInStock->viewAttributes() ?>><?= $Page->UnitsInStock->SumViewValue ?></span></span></td>
<?php } ?>
    </tr>
<?php } else { ?>
    <tr<?= $Page->rowAttributes(); ?>><td colspan="<?= ($Page->GroupColumnCount + $Page->DetailColumnCount) ?>"><?= $Language->phrase("RptPageSummary") ?> <span class="ew-summary-count">(<?= FormatNumber($Page->PageTotalCount, Config("DEFAULT_NUMBER_FORMAT")) ?><?= $Language->phrase("RptDtlRec") ?>)</span></td></tr>
    <tr<?= $Page->rowAttributes(); ?>>
<?php if ($Page->GroupColumnCount > 0) { ?>
        <td colspan="<?= $Page->GroupColumnCount ?>" class="ew-rpt-grp-aggregate"><?= $Language->phrase("RptSum") ?></td>
<?php } ?>
<?php if ($Page->ProductName->Visible) { ?>
        <td data-field="ProductName"<?= $Page->ProductName->cellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->CategoryName->Visible) { ?>
        <td data-field="CategoryName"<?= $Page->CategoryName->cellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->QuantityPerUnit->Visible) { ?>
        <td data-field="QuantityPerUnit"<?= $Page->QuantityPerUnit->cellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->UnitsInStock->Visible) { ?>
        <td data-field="UnitsInStock"<?= $Page->UnitsInStock->cellAttributes() ?>>
<span<?= $Page->UnitsInStock->viewAttributes() ?>>
<?= $Page->UnitsInStock->SumViewValue ?></span>
</td>
<?php } ?>
    </tr>
<?php } ?>
<?php } ?>
<?php
    $Page->resetAttributes();
    $Page->RowType = ROWTYPE_TOTAL;
    $Page->RowTotalType = ROWTOTAL_GRAND;
    $Page->RowTotalSubType = ROWTOTAL_FOOTER;
    $Page->RowAttrs["class"] = "ew-rpt-grand-summary";
    $Page->renderRow();
?>
<?php if ($Page->ProductName2->ShowCompactSummaryFooter) { ?>
    <tr<?= $Page->rowAttributes() ?>><td colspan="<?= ($Page->GroupColumnCount + $Page->DetailColumnCount) ?>"><?= $Language->phrase("RptGrandSummary") ?> <span class="ew-summary-count">(<span class="ew-aggregate-caption"><?= $Language->phrase("RptCnt") ?></span><span class="ew-aggregate-equal"><?= $Language->phrase("AggregateEqual") ?></span><span class="ew-aggregate-value"><?= FormatNumber($Page->TotalCount, Config("DEFAULT_NUMBER_FORMAT")) ?></span>)</span></td></tr>
    <tr<?= $Page->rowAttributes() ?>>
<?php if ($Page->GroupColumnCount > 0) { ?>
        <td colspan="<?= $Page->GroupColumnCount ?>" class="ew-rpt-grp-aggregate"></td>
<?php } ?>
<?php if ($Page->ProductName->Visible) { ?>
        <td data-field="ProductName"<?= $Page->ProductName->cellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->CategoryName->Visible) { ?>
        <td data-field="CategoryName"<?= $Page->CategoryName->cellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->QuantityPerUnit->Visible) { ?>
        <td data-field="QuantityPerUnit"<?= $Page->QuantityPerUnit->cellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->UnitsInStock->Visible) { ?>
        <td data-field="UnitsInStock"<?= $Page->UnitsInStock->cellAttributes() ?>><span class="ew-aggregate-caption"><?= $Language->phrase("RptSum") ?></span><span class="ew-aggregate-equal"><?= $Language->phrase("AggregateEqual") ?></span><span class="ew-aggregate-value"><span<?= $Page->UnitsInStock->viewAttributes() ?>><?= $Page->UnitsInStock->SumViewValue ?></span></span></td>
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
<?php if ($Page->CategoryName->Visible) { ?>
        <td data-field="CategoryName"<?= $Page->CategoryName->cellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->QuantityPerUnit->Visible) { ?>
        <td data-field="QuantityPerUnit"<?= $Page->QuantityPerUnit->cellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->UnitsInStock->Visible) { ?>
        <td data-field="UnitsInStock"<?= $Page->UnitsInStock->cellAttributes() ?>>
<span<?= $Page->UnitsInStock->viewAttributes() ?>>
<?= $Page->UnitsInStock->SumViewValue ?></span>
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
