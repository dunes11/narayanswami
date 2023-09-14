<?php

namespace PHPMaker2023\demo2023;

// Page object
$CategoriesView = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<?php } ?>
<?php if (!$Page->isExport()) { ?>
<div class="btn-toolbar ew-toolbar">
<?php $Page->ExportOptions->render("body") ?>
<?php $Page->OtherOptions->render("body") ?>
</div>
<?php } ?>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<main class="view">
<?php if (!$Page->IsModal) { ?>
<?php if (!$Page->isExport()) { ?>
<?= $Page->Pager->render() ?>
<?php } ?>
<?php } ?>
<form name="fcategoriesview" id="fcategoriesview" class="ew-form ew-view-form overlay-wrapper" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="on">
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { categories: currentTable } });
var currentPageID = ew.PAGE_ID = "view";
var currentForm;
var fcategoriesview;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fcategoriesview")
        .setPageId("view")
        .build();
    window[form.id] = form;
    currentForm = form;
    loadjs.done(form.id);
});
</script>
<?php } ?>
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="categories">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="<?= $Page->TableClass ?>">
<?php if ($Page->CategoryID->Visible) { // CategoryID ?>
    <tr id="r_CategoryID"<?= $Page->CategoryID->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_categories_CategoryID"><?= $Page->CategoryID->caption() ?></span></td>
        <td data-name="CategoryID"<?= $Page->CategoryID->cellAttributes() ?>>
<span id="el_categories_CategoryID">
<span<?= $Page->CategoryID->viewAttributes() ?>>
<?= $Page->CategoryID->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->CategoryName->Visible) { // CategoryName ?>
    <tr id="r_CategoryName"<?= $Page->CategoryName->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_categories_CategoryName"><?= $Page->CategoryName->caption() ?></span></td>
        <td data-name="CategoryName"<?= $Page->CategoryName->cellAttributes() ?>>
<span id="el_categories_CategoryName">
<span<?= $Page->CategoryName->viewAttributes() ?>>
<?= $Page->CategoryName->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Description->Visible) { // Description ?>
    <tr id="r_Description"<?= $Page->Description->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_categories_Description"><?= $Page->Description->caption() ?></span></td>
        <td data-name="Description"<?= $Page->Description->cellAttributes() ?>>
<span id="el_categories_Description">
<span<?= $Page->Description->viewAttributes() ?>>
<?= $Page->Description->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Picture->Visible) { // Picture ?>
    <tr id="r_Picture"<?= $Page->Picture->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_categories_Picture"><?= $Page->Picture->caption() ?></span></td>
        <td data-name="Picture"<?= $Page->Picture->cellAttributes() ?>>
<span id="el_categories_Picture">
<span>
<?= GetFileViewTag($Page->Picture, $Page->Picture->getViewValue(), false) ?>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Icon_17->Visible) { // Icon_17 ?>
    <tr id="r_Icon_17"<?= $Page->Icon_17->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_categories_Icon_17"><?= $Page->Icon_17->caption() ?></span></td>
        <td data-name="Icon_17"<?= $Page->Icon_17->cellAttributes() ?>>
<span id="el_categories_Icon_17">
<span>
<?= GetFileViewTag($Page->Icon_17, $Page->Icon_17->getViewValue(), false) ?>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Icon_25->Visible) { // Icon_25 ?>
    <tr id="r_Icon_25"<?= $Page->Icon_25->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_categories_Icon_25"><?= $Page->Icon_25->caption() ?></span></td>
        <td data-name="Icon_25"<?= $Page->Icon_25->cellAttributes() ?>>
<span id="el_categories_Icon_25">
<span>
<?= GetFileViewTag($Page->Icon_25, $Page->Icon_25->getViewValue(), false) ?>
</span>
</span>
</td>
    </tr>
<?php } ?>
</table>
<?php
    if (in_array("products", explode(",", $Page->getCurrentDetailTable())) && $products->DetailView) {
?>
<?php if ($Page->getCurrentDetailTable() != "") { ?>
<h4 class="ew-detail-caption"><?= $Language->tablePhrase("products", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "ProductsGrid.php" ?>
<?php } ?>
</form>
<?php if (!$Page->IsModal) { ?>
<?php if (!$Page->isExport()) { ?>
<?= $Page->Pager->render() ?>
<?php } ?>
<?php } ?>
</main>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<?php if (!$Page->isExport()) { ?>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
