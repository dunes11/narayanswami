<?php

namespace PHPMaker2023\demo2023;

// Page object
$SalesByCustomerSummary = &$Page;
?>
<?php if (!$Page->isExport() && !$Page->DrillDown && !$DashboardReport) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { Sales_By_Customer: currentTable } });
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
<div id="gmp_Sales_By_Customer" class="card-body ew-grid-middle-panel <?= $Page->TableContainerClass ?>">
<?php } ?>
<table class="<?= $Page->TableClass ?>">
<thead>
	<!-- Table header -->
    <tr class="ew-table-header">
<?php if ($Page->CompanyName->Visible) { ?>
    <?php if ($Page->CompanyName->ShowGroupHeaderAsRow) { ?>
    <th data-name="CompanyName">&nbsp;</th>
    <?php } else { ?>
    <th data-name="CompanyName" class="<?= $Page->CompanyName->headerCellClass() ?>"><div class="Sales_By_Customer_CompanyName"><?= $Page->renderFieldHeader($Page->CompanyName) ?></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->OrderID->Visible) { ?>
    <?php if ($Page->OrderID->ShowGroupHeaderAsRow) { ?>
    <th data-name="OrderID">&nbsp;</th>
    <?php } else { ?>
    <th data-name="OrderID" class="<?= $Page->OrderID->headerCellClass() ?>"><div class="Sales_By_Customer_OrderID"><?= $Page->renderFieldHeader($Page->OrderID) ?></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->ProductName->Visible) { ?>
    <th data-name="ProductName" class="<?= $Page->ProductName->headerCellClass() ?>"><div class="Sales_By_Customer_ProductName"><?= $Page->renderFieldHeader($Page->ProductName) ?></div></th>
<?php } ?>
<?php if ($Page->UnitPrice->Visible) { ?>
    <th data-name="UnitPrice" class="<?= $Page->UnitPrice->headerCellClass() ?>"><div class="Sales_By_Customer_UnitPrice"><?= $Page->renderFieldHeader($Page->UnitPrice) ?></div></th>
<?php } ?>
<?php if ($Page->Quantity->Visible) { ?>
    <th data-name="Quantity" class="<?= $Page->Quantity->headerCellClass() ?>"><div class="Sales_By_Customer_Quantity"><?= $Page->renderFieldHeader($Page->Quantity) ?></div></th>
<?php } ?>
<?php if ($Page->Discount->Visible) { ?>
    <th data-name="Discount" class="<?= $Page->Discount->headerCellClass() ?>"><div class="Sales_By_Customer_Discount"><?= $Page->renderFieldHeader($Page->Discount) ?></div></th>
<?php } ?>
<?php if ($Page->ExtendedPrice->Visible) { ?>
    <th data-name="ExtendedPrice" class="<?= $Page->ExtendedPrice->headerCellClass() ?>"><div class="Sales_By_Customer_ExtendedPrice"><?= $Page->renderFieldHeader($Page->ExtendedPrice) ?></div></th>
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
            <span class="ew-summary-caption Sales_By_Customer_CompanyName"><?= $Page->renderFieldHeader($Page->CompanyName) ?></span><?= $Language->phrase("SummaryColon") ?><span<?= $Page->CompanyName->viewAttributes() ?>><?= $Page->CompanyName->GroupViewValue ?></span>
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
            <span class="ew-summary-caption Sales_By_Customer_OrderID"><?= $Page->renderFieldHeader($Page->OrderID) ?></span><?= $Language->phrase("SummaryColon") ?><span<?= $Page->OrderID->viewAttributes() ?>><?= $Page->OrderID->GroupViewValue ?></span>
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
        <td data-field="CompanyName"<?= $Page->CompanyName->cellAttributes() ?>><span<?= $Page->CompanyName->viewAttributes() ?>><?= $Page->CompanyName->GroupViewValue ?></span></td>
    <?php } ?>
<?php } ?>
<?php if ($Page->OrderID->Visible) { ?>
    <?php if ($Page->OrderID->ShowGroupHeaderAsRow) { ?>
        <td data-field="OrderID"<?= $Page->OrderID->cellAttributes() ?>></td>
    <?php } else { ?>
        <td data-field="OrderID"<?= $Page->OrderID->cellAttributes() ?>><span<?= $Page->OrderID->viewAttributes() ?>><?= $Page->OrderID->GroupViewValue ?></span></td>
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
?>
<?php if ($Page->TotalGroups > 0) { ?>
<?php
    $Page->Quantity->getAvg($Page->OrderID->Records); // Get Avg
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
        <td data-field="Quantity"<?= $Page->OrderID->cellAttributes() ?>><span class="ew-aggregate-caption"><?= $Language->phrase("RptAvg") ?></span><span class="ew-aggregate-equal"><?= $Language->phrase("AggregateEqual") ?></span><span class="ew-aggregate-value"><span<?= $Page->Quantity->viewAttributes() ?>><?= $Page->Quantity->AvgViewValue ?></span></span></td>
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
    <tr<?= $Page->rowAttributes(); ?>>
<?php if ($Page->CompanyName->Visible) { ?>
        <td data-field="CompanyName"<?= $Page->CompanyName->cellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->GroupColumnCount > 0) { ?>
        <td colspan="<?= ($Page->GroupColumnCount - 1) ?>"<?= $Page->OrderID->cellAttributes() ?>><?= $Language->phrase("RptAvg") ?></td>
<?php } ?>
<?php if ($Page->ProductName->Visible) { ?>
        <td data-field="ProductName"<?= $Page->OrderID->cellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->UnitPrice->Visible) { ?>
        <td data-field="UnitPrice"<?= $Page->OrderID->cellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->Quantity->Visible) { ?>
        <td data-field="Quantity"<?= $Page->Quantity->cellAttributes() ?>>
<span<?= $Page->Quantity->viewAttributes() ?>>
<?= $Page->Quantity->AvgViewValue ?></span>
</td>
<?php } ?>
<?php if ($Page->Discount->Visible) { ?>
        <td data-field="Discount"<?= $Page->OrderID->cellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->ExtendedPrice->Visible) { ?>
        <td data-field="ExtendedPrice"<?= $Page->OrderID->cellAttributes() ?>></td>
<?php } ?>
    </tr>
<?php } ?>
<?php } ?>
<?php
    } // End group level 1
?>
<?php if ($Page->TotalGroups > 0) { ?>
<?php
    $Page->Quantity->getAvg($Page->CompanyName->Records); // Get Avg
    $Page->ExtendedPrice->getSum($Page->CompanyName->Records); // Get Sum
    $Page->resetAttributes();
    $Page->RowType = ROWTYPE_TOTAL;
    $Page->RowTotalType = ROWTOTAL_GROUP;
    $Page->RowTotalSubType = ROWTOTAL_FOOTER;
    $Page->RowGroupLevel = 1;
    $Page->renderRow();
?>
<?php if ($Page->CompanyName->ShowCompactSummaryFooter) { ?>
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
        <td data-field="OrderID"<?= $Page->CompanyName->cellAttributes() ?>></td>
    <?php } elseif ($Page->RowGroupLevel != 2) { ?>
        <td data-field="OrderID"<?= $Page->CompanyName->cellAttributes() ?>>
        </td>
    <?php } else { ?>
        <td data-field="OrderID"<?= $Page->CompanyName->cellAttributes() ?>>
            <span class="ew-summary-count"><span class="ew-aggregate-caption"><?= $Language->phrase("RptCnt") ?></span><span class="ew-aggregate-equal"><?= $Language->phrase("AggregateEqual") ?></span><span class="ew-aggregate-value"><?= FormatNumber($Page->OrderID->Count, Config("DEFAULT_NUMBER_FORMAT")) ?></span></span>
        </td>
    <?php } ?>
<?php } ?>
<?php if ($Page->ProductName->Visible) { ?>
        <td data-field="ProductName"<?= $Page->CompanyName->cellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->UnitPrice->Visible) { ?>
        <td data-field="UnitPrice"<?= $Page->CompanyName->cellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->Quantity->Visible) { ?>
        <td data-field="Quantity"<?= $Page->CompanyName->cellAttributes() ?>><span class="ew-aggregate-caption"><?= $Language->phrase("RptAvg") ?></span><span class="ew-aggregate-equal"><?= $Language->phrase("AggregateEqual") ?></span><span class="ew-aggregate-value"><span<?= $Page->Quantity->viewAttributes() ?>><?= $Page->Quantity->AvgViewValue ?></span></span></td>
<?php } ?>
<?php if ($Page->Discount->Visible) { ?>
        <td data-field="Discount"<?= $Page->CompanyName->cellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->ExtendedPrice->Visible) { ?>
        <td data-field="ExtendedPrice"<?= $Page->CompanyName->cellAttributes() ?>><span class="ew-aggregate-caption"><?= $Language->phrase("RptSum") ?></span><span class="ew-aggregate-equal"><?= $Language->phrase("AggregateEqual") ?></span><span class="ew-aggregate-value"><span<?= $Page->ExtendedPrice->viewAttributes() ?>><?= $Page->ExtendedPrice->SumViewValue ?></span></span></td>
<?php } ?>
    </tr>
<?php } else { ?>
    <tr<?= $Page->rowAttributes(); ?>>
<?php if ($Page->GroupColumnCount + $Page->DetailColumnCount > 0) { ?>
        <td colspan="<?= ($Page->GroupColumnCount + $Page->DetailColumnCount) ?>"<?= $Page->CompanyName->cellAttributes() ?>><?= str_replace(["%v", "%c"], [$Page->CompanyName->GroupViewValue, $Page->CompanyName->caption()], $Language->phrase("RptSumHead")) ?> <span class="ew-dir-ltr">(<?= FormatNumber($Page->CompanyName->Count, Config("DEFAULT_NUMBER_FORMAT")) ?><?= $Language->phrase("RptDtlRec") ?>)</span></td>
<?php } ?>
    </tr>
    <tr<?= $Page->rowAttributes(); ?>>
<?php if ($Page->GroupColumnCount > 0) { ?>
        <td colspan="<?= ($Page->GroupColumnCount - 0) ?>"<?= $Page->CompanyName->cellAttributes() ?>><?= $Language->phrase("RptSum") ?></td>
<?php } ?>
<?php if ($Page->ProductName->Visible) { ?>
        <td data-field="ProductName"<?= $Page->CompanyName->cellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->UnitPrice->Visible) { ?>
        <td data-field="UnitPrice"<?= $Page->CompanyName->cellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->Quantity->Visible) { ?>
        <td data-field="Quantity"<?= $Page->CompanyName->cellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->Discount->Visible) { ?>
        <td data-field="Discount"<?= $Page->CompanyName->cellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->ExtendedPrice->Visible) { ?>
        <td data-field="ExtendedPrice"<?= $Page->ExtendedPrice->cellAttributes() ?>>
<span<?= $Page->ExtendedPrice->viewAttributes() ?>>
<?= $Page->ExtendedPrice->SumViewValue ?></span>
</td>
<?php } ?>
    </tr>
    <tr<?= $Page->rowAttributes(); ?>>
<?php if ($Page->GroupColumnCount > 0) { ?>
        <td colspan="<?= ($Page->GroupColumnCount - 0) ?>"<?= $Page->CompanyName->cellAttributes() ?>><?= $Language->phrase("RptAvg") ?></td>
<?php } ?>
<?php if ($Page->ProductName->Visible) { ?>
        <td data-field="ProductName"<?= $Page->CompanyName->cellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->UnitPrice->Visible) { ?>
        <td data-field="UnitPrice"<?= $Page->CompanyName->cellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->Quantity->Visible) { ?>
        <td data-field="Quantity"<?= $Page->Quantity->cellAttributes() ?>>
<span<?= $Page->Quantity->viewAttributes() ?>>
<?= $Page->Quantity->AvgViewValue ?></span>
</td>
<?php } ?>
<?php if ($Page->Discount->Visible) { ?>
        <td data-field="Discount"<?= $Page->CompanyName->cellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->ExtendedPrice->Visible) { ?>
        <td data-field="ExtendedPrice"<?= $Page->CompanyName->cellAttributes() ?>></td>
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
<?php if ($Page->CompanyName->ShowCompactSummaryFooter) { ?>
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
        <td data-field="Quantity"<?= $Page->Quantity->cellAttributes() ?>><span class="ew-aggregate-caption"><?= $Language->phrase("RptAvg") ?></span><span class="ew-aggregate-equal"><?= $Language->phrase("AggregateEqual") ?></span><span class="ew-aggregate-value"><span<?= $Page->Quantity->viewAttributes() ?>><?= $Page->Quantity->AvgViewValue ?></span></span></td>
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
    <tr<?= $Page->rowAttributes() ?>>
<?php if ($Page->GroupColumnCount > 0) { ?>
        <td colspan="<?= $Page->GroupColumnCount ?>" class="ew-rpt-grp-aggregate"><?= $Language->phrase("RptAvg") ?></td>
<?php } ?>
<?php if ($Page->ProductName->Visible) { ?>
        <td data-field="ProductName"<?= $Page->ProductName->cellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->UnitPrice->Visible) { ?>
        <td data-field="UnitPrice"<?= $Page->UnitPrice->cellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->Quantity->Visible) { ?>
        <td data-field="Quantity"<?= $Page->Quantity->cellAttributes() ?>>
<span<?= $Page->Quantity->viewAttributes() ?>>
<?= $Page->Quantity->AvgViewValue ?></span>
</td>
<?php } ?>
<?php if ($Page->Discount->Visible) { ?>
        <td data-field="Discount"<?= $Page->Discount->cellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->ExtendedPrice->Visible) { ?>
        <td data-field="ExtendedPrice"<?= $Page->ExtendedPrice->cellAttributes() ?>></td>
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
