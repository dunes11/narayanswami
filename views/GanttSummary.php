<?php

namespace PHPMaker2023\demo2023;

// Page object
$GanttSummary = &$Page;
?>
<?php if (!$Page->isExport() && !$Page->DrillDown && !$DashboardReport) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { Gantt: currentTable } });
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
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<?php if ($Page->ShowReport) { ?>
<!-- Summary report (begin) -->
<main class="report-summary<?= ($Page->TotalGroups == 0) ? " ew-no-record" : "" ?>">
</main>
<!-- /.report-summary -->
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
    // Startup script
    // Write your table-specific startup script here
    // console.log("page loaded");
    loadjs('https://www.gstatic.com/charts/loader.js', function() {
    	google.charts.load('current', {'packages':['gantt']});
    	google.charts.setOnLoadCallback(function() {
    		// Add <div> for chart
    		$(".report-summary").append('<div id="chart_div"></div>');
    		// Create data table
    		var data = new google.visualization.DataTable();
    		// Add columns, see: https://developers.google.com/chart/interactive/docs/gallery/ganttchart#data-format)
    		data.addColumn('string', 'Task ID');
    		data.addColumn('string', 'Task Name');
    		data.addColumn('string', 'Resource');
    		data.addColumn('date', 'Start Date');
    		data.addColumn('date', 'End Date');
    		data.addColumn('number', 'Duration');
    		data.addColumn('number', 'Percent Complete');
    		data.addColumn('string', 'Dependencies');
    		/**
    		 * Add rows by ExecuteJson()
    		 * NOTE: The fields must be in the same order as above columns. Change the table name and field names (if necessary) in the SQL.
    		*/
    		data.addRows(<?= ExecuteJson("SELECT TaskID, TaskName, ResourceID, Start, End, Duration, PercentComplete, Dependencies FROM tasks", ["header" => FALSE, "array" => TRUE, "convertdate" => TRUE, "datatypes" => [3 => "date", 4 => "date", 5 => "number", 6 => "number"]]); ?>);
    		// Chart options, see: https://developers.google.com/chart/interactive/docs/gallery/ganttchart#configuration-options
    		var options = {
    			height: 750 // Change height
    		};
    		// Create chart
    		var chart = new google.visualization.Gantt(document.getElementById('chart_div'));
    		// Draw chart
    		chart.draw(data, options);
    	});
    });
});
</script>
<?php } ?>
