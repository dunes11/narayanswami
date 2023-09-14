<?php

namespace PHPMaker2023\demo2023;

// Table
$orders = Container("orders");
?>
<?php if ($orders->Visible) { ?>
<div id="t_orders" class="card ew-grid ew-list-form ew-master-div <?= $Page->TableContainerClass ?>">
<table id="tbl_ordersmaster" class="<?= $Page->TableClass ?>">
    <thead>
        <tr class="ew-table-header">
<?php if ($orders->OrderID->Visible) { // OrderID ?>
            <th class="<?= $orders->OrderID->headerCellClass() ?>"><?= $orders->OrderID->caption() ?></th>
<?php } ?>
<?php if ($orders->CustomerID->Visible) { // CustomerID ?>
            <th class="<?= $orders->CustomerID->headerCellClass() ?>"><?= $orders->CustomerID->caption() ?></th>
<?php } ?>
<?php if ($orders->EmployeeID->Visible) { // EmployeeID ?>
            <th class="<?= $orders->EmployeeID->headerCellClass() ?>"><?= $orders->EmployeeID->caption() ?></th>
<?php } ?>
<?php if ($orders->OrderDate->Visible) { // OrderDate ?>
            <th class="<?= $orders->OrderDate->headerCellClass() ?>"><?= $orders->OrderDate->caption() ?></th>
<?php } ?>
        </tr>
    </thead>
    <tbody>
        <tr>
<?php if ($orders->OrderID->Visible) { // OrderID ?>
            <td<?= $orders->OrderID->cellAttributes() ?>>
<span id="el_orders_OrderID">
<span<?= $orders->OrderID->viewAttributes() ?>>
<?= $orders->OrderID->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($orders->CustomerID->Visible) { // CustomerID ?>
            <td<?= $orders->CustomerID->cellAttributes() ?>>
<span id="el_orders_CustomerID">
<span<?= $orders->CustomerID->viewAttributes() ?>>
<?= $orders->CustomerID->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($orders->EmployeeID->Visible) { // EmployeeID ?>
            <td<?= $orders->EmployeeID->cellAttributes() ?>>
<span id="el_orders_EmployeeID">
<span<?= $orders->EmployeeID->viewAttributes() ?>>
<?= $orders->EmployeeID->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($orders->OrderDate->Visible) { // OrderDate ?>
            <td<?= $orders->OrderDate->cellAttributes() ?>>
<span id="el_orders_OrderDate">
<span<?= $orders->OrderDate->viewAttributes() ?>>
<?= $orders->OrderDate->getViewValue() ?></span>
</span>
</td>
<?php } ?>
        </tr>
    </tbody>
</table>
</div>
<?php } ?>
