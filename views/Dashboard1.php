<?php

namespace PHPMaker2023\demo2023;

// Dashboard Page object
$Dashboard1 = $Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { Dashboard1: currentTable } });
var currentPageID = ew.PAGE_ID = "dashboard";
var currentForm;
var fDashboard1dashboard;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fDashboard1dashboard")
        .setPageId("dashboard")
        .build();
    window[form.id] = form;
    currentForm = form;
    loadjs.done(form.id);
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<!-- Content Container -->
<div id="ew-report" class="ew-report">
<div class="btn-toolbar ew-toolbar">
<?php
    $Page->ExportOptions->render("body");
?>
</div>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<!-- Dashboard Container -->
<div id="ew-dashboard" class="ew-dashboard">
<div class="row">
<div class="<?= $Dashboard1->ItemClassNames[0] ?>">
<div id="Item1" class="card">
    <div class="card-header">
        <h3 class="card-title"><?= $Language->tablePhrase("Sales_by_Category_for_2014", "TblCaption") ?></h3>
    <?php if (!$Dashboard1->isExport()) { ?>
        <div class="card-tools">
    <?php if ($Dashboard1->CanRefresh) { ?>
            <button type="button" class="btn btn-tool" data-card-widget="card-refresh" data-source="<?= GetUrl("salesbycategoryfor2014") ?>?layout=false&dashboard=true" data-load-on-init="<?= $Page->LoadOnInit ? "true" : "false" ?>"><i class="fa-solid fa-rotate"></i></button>
    <?php } ?>
    <?php if ($Dashboard1->CanMaximize) { ?>
            <button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fa-solid fa-maximize"></i></button>
    <?php } ?>
    <?php if ($Dashboard1->CanCollapse) { ?>
            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fa-solid fa-minus"></i></button>
    <?php } ?>
        </div>
    <?php } ?>
    </div><!-- /.card-header -->
    <div class="card-body">
        <?= $Dashboard1->renderItem($this, 1) ?>
    </div><!-- /.card-body -->
</div><!-- /.card -->
</div>
<div class="<?= $Dashboard1->ItemClassNames[1] ?>">
<div id="Item2" class="card">
    <div class="card-header">
        <h3 class="card-title"><?= $Language->chartPhrase("Sales_by_Category_for_2014", "SalesByCategory2014", "ChartCaption") ?></h3>
    <?php if (!$Dashboard1->isExport()) { ?>
        <div class="card-tools">
    <?php if ($Dashboard1->CanRefresh) { ?>
            <button type="button" class="btn btn-tool" data-card-widget="card-refresh" data-source="<?= GetUrl("salesbycategoryfor2014/SalesByCategory2014?dashboard=true") ?>" data-load-on-init="<?= $Page->LoadOnInit ? "true" : "false" ?>"><i class="fa-solid fa-rotate"></i></button>
    <?php } ?>
    <?php if ($Dashboard1->CanMaximize) { ?>
            <button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fa-solid fa-maximize"></i></button>
    <?php } ?>
    <?php if ($Dashboard1->CanCollapse) { ?>
            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fa-solid fa-minus"></i></button>
    <?php } ?>
        </div>
    <?php } ?>
    </div><!-- /.card-header -->
    <div class="card-body">
        <?= $Dashboard1->renderItem($this, 2) ?>
    </div><!-- /.card-body -->
</div><!-- /.card -->
</div>
<div class="<?= $Dashboard1->ItemClassNames[2] ?>">
<div id="Item3" class="card">
    <div class="card-header">
        <h3 class="card-title"><?= $Language->chartPhrase("Quarterly_Orders_By_Product", "OrdersByCategory", "ChartCaption") ?></h3>
    <?php if (!$Dashboard1->isExport()) { ?>
        <div class="card-tools">
    <?php if ($Dashboard1->CanRefresh) { ?>
            <button type="button" class="btn btn-tool" data-card-widget="card-refresh" data-source="<?= GetUrl("quarterlyordersbyproduct/OrdersByCategory?dashboard=true") ?>" data-load-on-init="<?= $Page->LoadOnInit ? "true" : "false" ?>"><i class="fa-solid fa-rotate"></i></button>
    <?php } ?>
    <?php if ($Dashboard1->CanMaximize) { ?>
            <button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fa-solid fa-maximize"></i></button>
    <?php } ?>
    <?php if ($Dashboard1->CanCollapse) { ?>
            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fa-solid fa-minus"></i></button>
    <?php } ?>
        </div>
    <?php } ?>
    </div><!-- /.card-header -->
    <div class="card-body">
        <?= $Dashboard1->renderItem($this, 3) ?>
    </div><!-- /.card-body -->
</div><!-- /.card -->
</div>
</div>
</div>
<!-- /.ew-dashboard -->
</div>
<!-- /.ew-report -->
<script>
loadjs.ready("load", () => jQuery('[data-card-widget="card-refresh"]')
    .on("loaded.fail.lte.cardrefresh", (e, jqXHR, textStatus, errorThrown) => console.error(errorThrown))
    .on("loaded.success.lte.cardrefresh", (e, result) => !ew.getError(result) || console.error(result)));
</script>
<?php if ($Dashboard1->isExport() && !$Dashboard1->isExport("print")) { ?>
<script class="ew-export-dashboard">
loadjs.ready("load", function() {
    ew.exportCustom("ew-dashboard", "<?= $Dashboard1->Export ?>", "Dashboard1");
    loadjs.done("exportdashboard");
});
</script>
<?php } ?>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
