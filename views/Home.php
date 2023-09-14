<?php

namespace PHPMaker2023\demo2023;

// Page object
$Home = &$Page;
?>
<?php
$Page->showMessage();
?>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Out of stock products</h3>
    </div>
    <div class="card-body p-0 table-responsive">
<?php
    $sql = "SELECT DISTINCT " .
        "`categories`.`CategoryName` AS `CategoryName`," .
        "`products`.`ProductName` AS `ProductName`," .
        "`products`.`QuantityPerUnit` AS `QuantityPerUnit`" .
        " FROM `categories` JOIN `products` ON (`categories`.`CategoryID` = `products`.`CategoryID`)" .
        " WHERE " .
        "`products`.`UnitsInStock` <= 0";
    Write(ExecuteHtml($sql, ["fieldcaption" => true, "tablename" => ["products", "categories"]]));
?>
    </div>
</div>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Discontinued products</h3>
    </div>
    <div class="card-body p-0 table-responsive">
<?php
    $sql = "SELECT DISTINCT " .
        "`categories`.`CategoryName` AS `CategoryName`," .
        "`products`.`ProductName` AS `ProductName`," .
        "`products`.`QuantityPerUnit` AS `QuantityPerUnit`," .
        "`products`.`UnitsInStock` AS `UnitsInStock`" .
        " FROM `categories` JOIN `products` ON (`categories`.`CategoryID` = `products`.`CategoryID`)" .
        " WHERE " .
        "`products`.`Discontinued` = '1'";
    Write(ExecuteHtml($sql, ["fieldcaption" => true, "tablename" => ["products", "categories"]]));
?>
    </div>
</div>


<?= GetDebugMessage() ?>
