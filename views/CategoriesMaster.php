<?php

namespace PHPMaker2023\demo2023;

// Table
$categories = Container("categories");
?>
<?php if ($categories->Visible) { ?>
<div id="t_categories" class="card ew-grid ew-list-form ew-master-div <?= $Page->TableContainerClass ?>">
<table id="tbl_categoriesmaster" class="<?= $Page->TableClass ?>">
    <thead>
        <tr class="ew-table-header">
<?php if ($categories->CategoryID->Visible) { // CategoryID ?>
            <th class="<?= $categories->CategoryID->headerCellClass() ?>"><?= $categories->CategoryID->caption() ?></th>
<?php } ?>
<?php if ($categories->CategoryName->Visible) { // CategoryName ?>
            <th class="<?= $categories->CategoryName->headerCellClass() ?>"><?= $categories->CategoryName->caption() ?></th>
<?php } ?>
<?php if ($categories->Picture->Visible) { // Picture ?>
            <th class="<?= $categories->Picture->headerCellClass() ?>"><?= $categories->Picture->caption() ?></th>
<?php } ?>
        </tr>
    </thead>
    <tbody>
        <tr>
<?php if ($categories->CategoryID->Visible) { // CategoryID ?>
            <td<?= $categories->CategoryID->cellAttributes() ?>>
<span id="el_categories_CategoryID">
<span<?= $categories->CategoryID->viewAttributes() ?>>
<?= $categories->CategoryID->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($categories->CategoryName->Visible) { // CategoryName ?>
            <td<?= $categories->CategoryName->cellAttributes() ?>>
<span id="el_categories_CategoryName">
<span<?= $categories->CategoryName->viewAttributes() ?>>
<?= $categories->CategoryName->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($categories->Picture->Visible) { // Picture ?>
            <td<?= $categories->Picture->cellAttributes() ?>>
<span id="el_categories_Picture">
<span>
<?= GetFileViewTag($categories->Picture, $categories->Picture->getViewValue(), false) ?>
</span>
</span>
</td>
<?php } ?>
        </tr>
    </tbody>
</table>
</div>
<?php } ?>
