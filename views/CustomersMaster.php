<?php

namespace PHPMaker2023\demo2023;

// Table
$customers = Container("customers");
?>
<?php if ($customers->Visible) { ?>
<div id="t_customers" class="card ew-grid ew-list-form ew-master-div <?= $Page->TableContainerClass ?>">
<table id="tbl_customersmaster" class="<?= $Page->TableClass ?>">
    <thead>
        <tr class="ew-table-header">
<?php if ($customers->CustomerID->Visible) { // CustomerID ?>
            <th class="<?= $customers->CustomerID->headerCellClass() ?>"><?= $customers->CustomerID->caption() ?></th>
<?php } ?>
<?php if ($customers->CompanyName->Visible) { // CompanyName ?>
            <th class="<?= $customers->CompanyName->headerCellClass() ?>"><?= $customers->CompanyName->caption() ?></th>
<?php } ?>
<?php if ($customers->ContactName->Visible) { // ContactName ?>
            <th class="<?= $customers->ContactName->headerCellClass() ?>"><?= $customers->ContactName->caption() ?></th>
<?php } ?>
<?php if ($customers->ContactTitle->Visible) { // ContactTitle ?>
            <th class="<?= $customers->ContactTitle->headerCellClass() ?>"><?= $customers->ContactTitle->caption() ?></th>
<?php } ?>
<?php if ($customers->Address->Visible) { // Address ?>
            <th class="<?= $customers->Address->headerCellClass() ?>"><?= $customers->Address->caption() ?></th>
<?php } ?>
<?php if ($customers->City->Visible) { // City ?>
            <th class="<?= $customers->City->headerCellClass() ?>"><?= $customers->City->caption() ?></th>
<?php } ?>
<?php if ($customers->Region->Visible) { // Region ?>
            <th class="<?= $customers->Region->headerCellClass() ?>"><?= $customers->Region->caption() ?></th>
<?php } ?>
<?php if ($customers->PostalCode->Visible) { // PostalCode ?>
            <th class="<?= $customers->PostalCode->headerCellClass() ?>"><?= $customers->PostalCode->caption() ?></th>
<?php } ?>
<?php if ($customers->Country->Visible) { // Country ?>
            <th class="<?= $customers->Country->headerCellClass() ?>"><?= $customers->Country->caption() ?></th>
<?php } ?>
<?php if ($customers->Phone->Visible) { // Phone ?>
            <th class="<?= $customers->Phone->headerCellClass() ?>"><?= $customers->Phone->caption() ?></th>
<?php } ?>
<?php if ($customers->Fax->Visible) { // Fax ?>
            <th class="<?= $customers->Fax->headerCellClass() ?>"><?= $customers->Fax->caption() ?></th>
<?php } ?>
        </tr>
    </thead>
    <tbody>
        <tr>
<?php if ($customers->CustomerID->Visible) { // CustomerID ?>
            <td<?= $customers->CustomerID->cellAttributes() ?>>
<span id="el_customers_CustomerID">
<span<?= $customers->CustomerID->viewAttributes() ?>>
<?= $customers->CustomerID->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($customers->CompanyName->Visible) { // CompanyName ?>
            <td<?= $customers->CompanyName->cellAttributes() ?>>
<span id="el_customers_CompanyName">
<span<?= $customers->CompanyName->viewAttributes() ?>>
<?= $customers->CompanyName->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($customers->ContactName->Visible) { // ContactName ?>
            <td<?= $customers->ContactName->cellAttributes() ?>>
<span id="el_customers_ContactName">
<span<?= $customers->ContactName->viewAttributes() ?>>
<?= $customers->ContactName->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($customers->ContactTitle->Visible) { // ContactTitle ?>
            <td<?= $customers->ContactTitle->cellAttributes() ?>>
<span id="el_customers_ContactTitle">
<span<?= $customers->ContactTitle->viewAttributes() ?>>
<?= $customers->ContactTitle->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($customers->Address->Visible) { // Address ?>
            <td<?= $customers->Address->cellAttributes() ?>>
<span id="el_customers_Address">
<span<?= $customers->Address->viewAttributes() ?>>
<?= $customers->Address->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($customers->City->Visible) { // City ?>
            <td<?= $customers->City->cellAttributes() ?>>
<span id="el_customers_City">
<span<?= $customers->City->viewAttributes() ?>>
<?= $customers->City->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($customers->Region->Visible) { // Region ?>
            <td<?= $customers->Region->cellAttributes() ?>>
<span id="el_customers_Region">
<span<?= $customers->Region->viewAttributes() ?>>
<?= $customers->Region->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($customers->PostalCode->Visible) { // PostalCode ?>
            <td<?= $customers->PostalCode->cellAttributes() ?>>
<span id="el_customers_PostalCode">
<span<?= $customers->PostalCode->viewAttributes() ?>>
<?= $customers->PostalCode->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($customers->Country->Visible) { // Country ?>
            <td<?= $customers->Country->cellAttributes() ?>>
<span id="el_customers_Country">
<span<?= $customers->Country->viewAttributes() ?>>
<?= $customers->Country->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($customers->Phone->Visible) { // Phone ?>
            <td<?= $customers->Phone->cellAttributes() ?>>
<span id="el_customers_Phone">
<span<?= $customers->Phone->viewAttributes() ?>>
<?= $customers->Phone->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($customers->Fax->Visible) { // Fax ?>
            <td<?= $customers->Fax->cellAttributes() ?>>
<span id="el_customers_Fax">
<span<?= $customers->Fax->viewAttributes() ?>>
<?= $customers->Fax->getViewValue() ?></span>
</span>
</td>
<?php } ?>
        </tr>
    </tbody>
</table>
</div>
<?php } ?>
