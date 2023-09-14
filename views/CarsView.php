<?php

namespace PHPMaker2023\demo2023;

// Page object
$CarsView = &$Page;
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
<form name="fcarsview" id="fcarsview" class="ew-form ew-view-form overlay-wrapper" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="on">
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { cars: currentTable } });
var currentPageID = ew.PAGE_ID = "view";
var currentForm;
var fcarsview;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fcarsview")
        .setPageId("view")

        // Multi-Page
        .setMultiPage(true)
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
<input type="hidden" name="t" value="cars">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if ($Page->MultiPages->Items[0]->Visible) { ?>
<table class="<?= $Page->TableClass ?>">
<?php if ($Page->ID->Visible) { // ID ?>
    <tr id="r_ID"<?= $Page->ID->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cars_ID"><?= $Page->ID->caption() ?></span></td>
        <td data-name="ID"<?= $Page->ID->cellAttributes() ?>>
<span id="el_cars_ID" data-page="0">
<span<?= $Page->ID->viewAttributes() ?>>
<?= $Page->ID->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Trademark->Visible) { // Trademark ?>
    <tr id="r_Trademark"<?= $Page->Trademark->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cars_Trademark"><?= $Page->Trademark->caption() ?></span></td>
        <td data-name="Trademark"<?= $Page->Trademark->cellAttributes() ?>>
<span id="el_cars_Trademark" data-page="0">
<span<?= $Page->Trademark->viewAttributes() ?>>
<?= $Page->Trademark->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Model->Visible) { // Model ?>
    <tr id="r_Model"<?= $Page->Model->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cars_Model"><?= $Page->Model->caption() ?></span></td>
        <td data-name="Model"<?= $Page->Model->cellAttributes() ?>>
<span id="el_cars_Model" data-page="0">
<span<?= $Page->Model->viewAttributes() ?>>
<?php if (!EmptyString($Page->Model->TooltipValue) && $Page->Model->linkAttributes() != "") { ?>
<a<?= $Page->Model->linkAttributes() ?>><?= $Page->Model->getViewValue() ?></a>
<?php } else { ?>
<?= $Page->Model->getViewValue() ?>
<?php } ?>
<span id="tt_cars_x_Model" class="d-none">
<?= $Page->Model->TooltipValue ?>
</span></span>
</span>
</td>
    </tr>
<?php } ?>
</table>
<?php } ?>
<?php if (!$Page->isExport()) { ?>
<div class="ew-multi-page"><!-- multi-page -->
<div class="ew-nav<?= $Page->MultiPages->containerClasses() ?>" id="pages_CarsView"><!-- multi-page tabs -->
    <ul class="<?= $Page->MultiPages->navClasses() ?>" role="tablist">
        <li class="nav-item"><button class="<?= $Page->MultiPages->navLinkClasses(1) ?>" data-bs-target="#tab_cars1" data-bs-toggle="tab" type="button" role="tab" aria-controls="tab_cars1" aria-selected="<?= JsonEncode($Page->MultiPages->isActive(1)) ?>"><?= $Page->pageCaption(1) ?></button></li>
        <li class="nav-item"><button class="<?= $Page->MultiPages->navLinkClasses(2) ?>" data-bs-target="#tab_cars2" data-bs-toggle="tab" type="button" role="tab" aria-controls="tab_cars2" aria-selected="<?= JsonEncode($Page->MultiPages->isActive(2)) ?>"><?= $Page->pageCaption(2) ?></button></li>
        <li class="nav-item"><button class="<?= $Page->MultiPages->navLinkClasses(3) ?>" data-bs-target="#tab_cars3" data-bs-toggle="tab" type="button" role="tab" aria-controls="tab_cars3" aria-selected="<?= JsonEncode($Page->MultiPages->isActive(3)) ?>"><?= $Page->pageCaption(3) ?></button></li>
    </ul>
    <div class="<?= $Page->MultiPages->tabContentClasses() ?>">
<?php } ?>
<?php if (!$Page->isExport()) { ?>
        <div class="<?= $Page->MultiPages->tabPaneClasses(1) ?>" id="tab_cars1" role="tabpanel"><!-- multi-page .tab-pane -->
<?php } ?>
<table class="<?= $Page->TableClass ?>">
<?php if ($Page->HP->Visible) { // HP ?>
    <tr id="r_HP"<?= $Page->HP->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cars_HP"><?= $Page->HP->caption() ?></span></td>
        <td data-name="HP"<?= $Page->HP->cellAttributes() ?>>
<span id="el_cars_HP" data-page="1">
<span<?= $Page->HP->viewAttributes() ?>>
<?= $Page->HP->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Cylinders->Visible) { // Cylinders ?>
    <tr id="r_Cylinders"<?= $Page->Cylinders->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cars_Cylinders"><?= $Page->Cylinders->caption() ?></span></td>
        <td data-name="Cylinders"<?= $Page->Cylinders->cellAttributes() ?>>
<span id="el_cars_Cylinders" data-page="1">
<span<?= $Page->Cylinders->viewAttributes() ?>>
<?= $Page->Cylinders->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->TransmissionSpeeds->Visible) { // Transmission Speeds ?>
    <tr id="r_TransmissionSpeeds"<?= $Page->TransmissionSpeeds->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cars_TransmissionSpeeds"><?= $Page->TransmissionSpeeds->caption() ?></span></td>
        <td data-name="TransmissionSpeeds"<?= $Page->TransmissionSpeeds->cellAttributes() ?>>
<span id="el_cars_TransmissionSpeeds" data-page="1">
<span<?= $Page->TransmissionSpeeds->viewAttributes() ?>>
<?= $Page->TransmissionSpeeds->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->TransmissAutomatic->Visible) { // TransmissAutomatic ?>
    <tr id="r_TransmissAutomatic"<?= $Page->TransmissAutomatic->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cars_TransmissAutomatic"><?= $Page->TransmissAutomatic->caption() ?></span></td>
        <td data-name="TransmissAutomatic"<?= $Page->TransmissAutomatic->cellAttributes() ?>>
<span id="el_cars_TransmissAutomatic" data-page="1">
<span<?= $Page->TransmissAutomatic->viewAttributes() ?>>
<div class="form-check d-inline-block">
    <input type="checkbox" id="x_TransmissAutomatic_<?= $Page->RowCount ?>" class="form-check-input" value="<?= $Page->TransmissAutomatic->getViewValue() ?>" disabled<?php if (ConvertToBool($Page->TransmissAutomatic->CurrentValue)) { ?> checked<?php } ?>>
    <label class="form-check-label" for="x_TransmissAutomatic_<?= $Page->RowCount ?>"></label>
</div></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->MPGCity->Visible) { // MPG City ?>
    <tr id="r_MPGCity"<?= $Page->MPGCity->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cars_MPGCity"><?= $Page->MPGCity->caption() ?></span></td>
        <td data-name="MPGCity"<?= $Page->MPGCity->cellAttributes() ?>>
<span id="el_cars_MPGCity" data-page="1">
<span<?= $Page->MPGCity->viewAttributes() ?>>
<?= $Page->MPGCity->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->MPGHighway->Visible) { // MPG Highway ?>
    <tr id="r_MPGHighway"<?= $Page->MPGHighway->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cars_MPGHighway"><?= $Page->MPGHighway->caption() ?></span></td>
        <td data-name="MPGHighway"<?= $Page->MPGHighway->cellAttributes() ?>>
<span id="el_cars_MPGHighway" data-page="1">
<span<?= $Page->MPGHighway->viewAttributes() ?>>
<?= $Page->MPGHighway->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Price->Visible) { // Price ?>
    <tr id="r_Price"<?= $Page->Price->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cars_Price"><?= $Page->Price->caption() ?></span></td>
        <td data-name="Price"<?= $Page->Price->cellAttributes() ?>>
<span id="el_cars_Price" data-page="1">
<span<?= $Page->Price->viewAttributes() ?>>
<?= $Page->Price->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Doors->Visible) { // Doors ?>
    <tr id="r_Doors"<?= $Page->Doors->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cars_Doors"><?= $Page->Doors->caption() ?></span></td>
        <td data-name="Doors"<?= $Page->Doors->cellAttributes() ?>>
<span id="el_cars_Doors" data-page="1">
<span<?= $Page->Doors->viewAttributes() ?>>
<?= $Page->Doors->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Torque->Visible) { // Torque ?>
    <tr id="r_Torque"<?= $Page->Torque->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cars_Torque"><?= $Page->Torque->caption() ?></span></td>
        <td data-name="Torque"<?= $Page->Torque->cellAttributes() ?>>
<span id="el_cars_Torque" data-page="1">
<span<?= $Page->Torque->viewAttributes() ?>>
<?= $Page->Torque->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
</table>
<?php if (!$Page->isExport()) { ?>
        </div><!-- /multi-page .tab-pane -->
<?php } ?>
<?php if (!$Page->isExport()) { ?>
        <div class="<?= $Page->MultiPages->tabPaneClasses(2) ?>" id="tab_cars2" role="tabpanel"><!-- multi-page .tab-pane -->
<?php } ?>
<table class="<?= $Page->TableClass ?>">
<?php if ($Page->Description->Visible) { // Description ?>
    <tr id="r_Description"<?= $Page->Description->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cars_Description"><?= $Page->Description->caption() ?></span></td>
        <td data-name="Description"<?= $Page->Description->cellAttributes() ?>>
<span id="el_cars_Description" data-page="2">
<span<?= $Page->Description->viewAttributes() ?>>
<?= $Page->Description->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
</table>
<?php if (!$Page->isExport()) { ?>
        </div><!-- /multi-page .tab-pane -->
<?php } ?>
<?php if (!$Page->isExport()) { ?>
        <div class="<?= $Page->MultiPages->tabPaneClasses(3) ?>" id="tab_cars3" role="tabpanel"><!-- multi-page .tab-pane -->
<?php } ?>
<table class="<?= $Page->TableClass ?>">
<?php if ($Page->Picture->Visible) { // Picture ?>
    <tr id="r_Picture"<?= $Page->Picture->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cars_Picture"><?= $Page->Picture->caption() ?></span></td>
        <td data-name="Picture"<?= $Page->Picture->cellAttributes() ?>>
<span id="el_cars_Picture" data-page="3">
<span>
<?= GetFileViewTag($Page->Picture, $Page->Picture->getViewValue(), false) ?>
</span>
</span>
</td>
    </tr>
<?php } ?>
</table>
<?php if (!$Page->isExport()) { ?>
        </div><!-- /multi-page .tab-pane -->
<?php } ?>
<?php if (!$Page->isExport()) { ?>
    </div>
</div>
</div>
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
