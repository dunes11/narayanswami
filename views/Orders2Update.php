<?php

namespace PHPMaker2023\demo2023;

// Page object
$Orders2Update = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { orders2: currentTable } });
var currentPageID = ew.PAGE_ID = "update";
var currentForm;
var forders2update;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("forders2update")
        .setPageId("update")

        // Add fields
        .setFields([
            ["OrderID", [fields.OrderID.visible && fields.OrderID.required ? ew.Validators.required(fields.OrderID.caption) : null], fields.OrderID.isInvalid],
            ["CustomerID", [fields.CustomerID.visible && fields.CustomerID.required ? ew.Validators.required(fields.CustomerID.caption) : null], fields.CustomerID.isInvalid],
            ["EmployeeID", [fields.EmployeeID.visible && fields.EmployeeID.required ? ew.Validators.required(fields.EmployeeID.caption) : null], fields.EmployeeID.isInvalid],
            ["OrderDate", [fields.OrderDate.visible && fields.OrderDate.required ? ew.Validators.required(fields.OrderDate.caption) : null, ew.Validators.datetime(fields.OrderDate.clientFormatPattern), ew.Validators.selected], fields.OrderDate.isInvalid],
            ["RequiredDate", [fields.RequiredDate.visible && fields.RequiredDate.required ? ew.Validators.required(fields.RequiredDate.caption) : null, ew.Validators.datetime(fields.RequiredDate.clientFormatPattern), ew.Validators.selected], fields.RequiredDate.isInvalid],
            ["ShippedDate", [fields.ShippedDate.visible && fields.ShippedDate.required ? ew.Validators.required(fields.ShippedDate.caption) : null, ew.Validators.datetime(fields.ShippedDate.clientFormatPattern), ew.Validators.selected], fields.ShippedDate.isInvalid],
            ["ShipVia", [fields.ShipVia.visible && fields.ShipVia.required ? ew.Validators.required(fields.ShipVia.caption) : null, ew.Validators.integer, ew.Validators.selected], fields.ShipVia.isInvalid],
            ["Freight", [fields.Freight.visible && fields.Freight.required ? ew.Validators.required(fields.Freight.caption) : null, ew.Validators.float, ew.Validators.selected], fields.Freight.isInvalid],
            ["ShipName", [fields.ShipName.visible && fields.ShipName.required ? ew.Validators.required(fields.ShipName.caption) : null], fields.ShipName.isInvalid],
            ["ShipAddress", [fields.ShipAddress.visible && fields.ShipAddress.required ? ew.Validators.required(fields.ShipAddress.caption) : null], fields.ShipAddress.isInvalid],
            ["ShipCity", [fields.ShipCity.visible && fields.ShipCity.required ? ew.Validators.required(fields.ShipCity.caption) : null], fields.ShipCity.isInvalid],
            ["ShipRegion", [fields.ShipRegion.visible && fields.ShipRegion.required ? ew.Validators.required(fields.ShipRegion.caption) : null], fields.ShipRegion.isInvalid],
            ["ShipPostalCode", [fields.ShipPostalCode.visible && fields.ShipPostalCode.required ? ew.Validators.required(fields.ShipPostalCode.caption) : null], fields.ShipPostalCode.isInvalid],
            ["ShipCountry", [fields.ShipCountry.visible && fields.ShipCountry.required ? ew.Validators.required(fields.ShipCountry.caption) : null], fields.ShipCountry.isInvalid]
        ])

        // Form_CustomValidate
        .setCustomValidate(
            function (fobj) { // DO NOT CHANGE THIS LINE! (except for adding "async" keyword)!
                    // Your custom validation code here, return false if invalid.
                    return true;
                }
        )

        // Use JavaScript validation or not
        .setValidateRequired(ew.CLIENT_VALIDATE)

        // Dynamic selection lists
        .setLists({
            "CustomerID": <?= $Page->CustomerID->toClientList($Page) ?>,
            "EmployeeID": <?= $Page->EmployeeID->toClientList($Page) ?>,
        })
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
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<form name="forders2update" id="forders2update" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="on">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="orders2">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php foreach ($Page->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode(Config("COMPOSITE_KEY_SEPARATOR"), $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?= HtmlEncode($keyvalue) ?>">
<?php } ?>
<div id="tbl_orders2update" class="ew-update-div"><!-- page -->
    <?php if (!$Page->isConfirm()) { // Confirm page ?>
    <div class="form-check">
        <input type="checkbox" class="form-check-input" name="u" id="u" data-ew-action="select-all"><label class="form-check-label" for="u"><?= $Language->phrase("SelectAll") ?></label>
    </div>
    <?php } ?>
<?php if ($Page->CustomerID->Visible && (!$Page->isConfirm() || $Page->CustomerID->multiUpdateSelected())) { // CustomerID ?>
    <div id="r_CustomerID"<?= $Page->CustomerID->rowAttributes() ?>>
        <label for="x_CustomerID" class="<?= $Page->LeftColumnClass ?>">
            <div class="form-check">
                <input type="checkbox" name="u_CustomerID" id="u_CustomerID" class="form-check-input ew-multi-select" value="1"<?= $Page->CustomerID->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="form-check-label" for="u_CustomerID"><?= $Page->CustomerID->caption() ?></label>
            </div>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->CustomerID->cellAttributes() ?>>
                <span id="el_orders2_CustomerID">
                    <select
                        id="x_CustomerID"
                        name="x_CustomerID"
                        class="form-select ew-select<?= $Page->CustomerID->isInvalidClass() ?>"
                        <?php if (!$Page->CustomerID->IsNativeSelect) { ?>
                        data-select2-id="forders2update_x_CustomerID"
                        <?php } ?>
                        data-table="orders2"
                        data-field="x_CustomerID"
                        data-value-separator="<?= $Page->CustomerID->displayValueSeparatorAttribute() ?>"
                        data-placeholder="<?= HtmlEncode($Page->CustomerID->getPlaceHolder()) ?>"
                        <?= $Page->CustomerID->editAttributes() ?>>
                        <?= $Page->CustomerID->selectOptionListHtml("x_CustomerID") ?>
                    </select>
                    <?= $Page->CustomerID->getCustomMessage() ?>
                    <div class="invalid-feedback"><?= $Page->CustomerID->getErrorMessage() ?></div>
                <?= $Page->CustomerID->Lookup->getParamTag($Page, "p_x_CustomerID") ?>
                <?php if (!$Page->CustomerID->IsNativeSelect) { ?>
                <script>
                loadjs.ready("forders2update", function() {
                    var options = { name: "x_CustomerID", selectId: "forders2update_x_CustomerID" },
                        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
                    if (!el)
                        return;
                    options.closeOnSelect = !options.multiple;
                    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
                    if (forders2update.lists.CustomerID?.lookupOptions.length) {
                        options.data = { id: "x_CustomerID", form: "forders2update" };
                    } else {
                        options.ajax = { id: "x_CustomerID", form: "forders2update", limit: ew.LOOKUP_PAGE_SIZE };
                    }
                    options.minimumResultsForSearch = Infinity;
                    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.orders2.fields.CustomerID.selectOptions);
                    ew.createSelect(options);
                });
                </script>
                <?php } ?>
                </span>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->EmployeeID->Visible && (!$Page->isConfirm() || $Page->EmployeeID->multiUpdateSelected())) { // EmployeeID ?>
    <div id="r_EmployeeID"<?= $Page->EmployeeID->rowAttributes() ?>>
        <label for="x_EmployeeID" class="<?= $Page->LeftColumnClass ?>">
            <div class="form-check">
                <input type="checkbox" name="u_EmployeeID" id="u_EmployeeID" class="form-check-input ew-multi-select" value="1"<?= $Page->EmployeeID->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="form-check-label" for="u_EmployeeID"><?= $Page->EmployeeID->caption() ?></label>
            </div>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->EmployeeID->cellAttributes() ?>>
                <span id="el_orders2_EmployeeID">
                    <select
                        id="x_EmployeeID"
                        name="x_EmployeeID"
                        class="form-select ew-select<?= $Page->EmployeeID->isInvalidClass() ?>"
                        <?php if (!$Page->EmployeeID->IsNativeSelect) { ?>
                        data-select2-id="forders2update_x_EmployeeID"
                        <?php } ?>
                        data-table="orders2"
                        data-field="x_EmployeeID"
                        data-value-separator="<?= $Page->EmployeeID->displayValueSeparatorAttribute() ?>"
                        data-placeholder="<?= HtmlEncode($Page->EmployeeID->getPlaceHolder()) ?>"
                        <?= $Page->EmployeeID->editAttributes() ?>>
                        <?= $Page->EmployeeID->selectOptionListHtml("x_EmployeeID") ?>
                    </select>
                    <?= $Page->EmployeeID->getCustomMessage() ?>
                    <div class="invalid-feedback"><?= $Page->EmployeeID->getErrorMessage() ?></div>
                <?= $Page->EmployeeID->Lookup->getParamTag($Page, "p_x_EmployeeID") ?>
                <?php if (!$Page->EmployeeID->IsNativeSelect) { ?>
                <script>
                loadjs.ready("forders2update", function() {
                    var options = { name: "x_EmployeeID", selectId: "forders2update_x_EmployeeID" },
                        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
                    if (!el)
                        return;
                    options.closeOnSelect = !options.multiple;
                    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
                    if (forders2update.lists.EmployeeID?.lookupOptions.length) {
                        options.data = { id: "x_EmployeeID", form: "forders2update" };
                    } else {
                        options.ajax = { id: "x_EmployeeID", form: "forders2update", limit: ew.LOOKUP_PAGE_SIZE };
                    }
                    options.minimumResultsForSearch = Infinity;
                    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.orders2.fields.EmployeeID.selectOptions);
                    ew.createSelect(options);
                });
                </script>
                <?php } ?>
                </span>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->OrderDate->Visible && (!$Page->isConfirm() || $Page->OrderDate->multiUpdateSelected())) { // OrderDate ?>
    <div id="r_OrderDate"<?= $Page->OrderDate->rowAttributes() ?>>
        <label for="x_OrderDate" class="<?= $Page->LeftColumnClass ?>">
            <div class="form-check">
                <input type="checkbox" name="u_OrderDate" id="u_OrderDate" class="form-check-input ew-multi-select" value="1"<?= $Page->OrderDate->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="form-check-label" for="u_OrderDate"><?= $Page->OrderDate->caption() ?></label>
            </div>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->OrderDate->cellAttributes() ?>>
                <span id="el_orders2_OrderDate">
                <input type="<?= $Page->OrderDate->getInputTextType() ?>" name="x_OrderDate" id="x_OrderDate" data-table="orders2" data-field="x_OrderDate" value="<?= $Page->OrderDate->EditValue ?>" placeholder="<?= HtmlEncode($Page->OrderDate->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->OrderDate->formatPattern()) ?>"<?= $Page->OrderDate->editAttributes() ?> aria-describedby="x_OrderDate_help">
                <?= $Page->OrderDate->getCustomMessage() ?>
                <div class="invalid-feedback"><?= $Page->OrderDate->getErrorMessage() ?></div>
                </span>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->RequiredDate->Visible && (!$Page->isConfirm() || $Page->RequiredDate->multiUpdateSelected())) { // RequiredDate ?>
    <div id="r_RequiredDate"<?= $Page->RequiredDate->rowAttributes() ?>>
        <label for="x_RequiredDate" class="<?= $Page->LeftColumnClass ?>">
            <div class="form-check">
                <input type="checkbox" name="u_RequiredDate" id="u_RequiredDate" class="form-check-input ew-multi-select" value="1"<?= $Page->RequiredDate->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="form-check-label" for="u_RequiredDate"><?= $Page->RequiredDate->caption() ?></label>
            </div>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->RequiredDate->cellAttributes() ?>>
                <span id="el_orders2_RequiredDate">
                <input type="<?= $Page->RequiredDate->getInputTextType() ?>" name="x_RequiredDate" id="x_RequiredDate" data-table="orders2" data-field="x_RequiredDate" value="<?= $Page->RequiredDate->EditValue ?>" placeholder="<?= HtmlEncode($Page->RequiredDate->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->RequiredDate->formatPattern()) ?>"<?= $Page->RequiredDate->editAttributes() ?> aria-describedby="x_RequiredDate_help">
                <?= $Page->RequiredDate->getCustomMessage() ?>
                <div class="invalid-feedback"><?= $Page->RequiredDate->getErrorMessage() ?></div>
                </span>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->ShippedDate->Visible && (!$Page->isConfirm() || $Page->ShippedDate->multiUpdateSelected())) { // ShippedDate ?>
    <div id="r_ShippedDate"<?= $Page->ShippedDate->rowAttributes() ?>>
        <label for="x_ShippedDate" class="<?= $Page->LeftColumnClass ?>">
            <div class="form-check">
                <input type="checkbox" name="u_ShippedDate" id="u_ShippedDate" class="form-check-input ew-multi-select" value="1"<?= $Page->ShippedDate->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="form-check-label" for="u_ShippedDate"><?= $Page->ShippedDate->caption() ?></label>
            </div>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->ShippedDate->cellAttributes() ?>>
                <span id="el_orders2_ShippedDate">
                <input type="<?= $Page->ShippedDate->getInputTextType() ?>" name="x_ShippedDate" id="x_ShippedDate" data-table="orders2" data-field="x_ShippedDate" value="<?= $Page->ShippedDate->EditValue ?>" placeholder="<?= HtmlEncode($Page->ShippedDate->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->ShippedDate->formatPattern()) ?>"<?= $Page->ShippedDate->editAttributes() ?> aria-describedby="x_ShippedDate_help">
                <?= $Page->ShippedDate->getCustomMessage() ?>
                <div class="invalid-feedback"><?= $Page->ShippedDate->getErrorMessage() ?></div>
                </span>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->ShipVia->Visible && (!$Page->isConfirm() || $Page->ShipVia->multiUpdateSelected())) { // ShipVia ?>
    <div id="r_ShipVia"<?= $Page->ShipVia->rowAttributes() ?>>
        <label for="x_ShipVia" class="<?= $Page->LeftColumnClass ?>">
            <div class="form-check">
                <input type="checkbox" name="u_ShipVia" id="u_ShipVia" class="form-check-input ew-multi-select" value="1"<?= $Page->ShipVia->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="form-check-label" for="u_ShipVia"><?= $Page->ShipVia->caption() ?></label>
            </div>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->ShipVia->cellAttributes() ?>>
                <span id="el_orders2_ShipVia">
                <input type="<?= $Page->ShipVia->getInputTextType() ?>" name="x_ShipVia" id="x_ShipVia" data-table="orders2" data-field="x_ShipVia" value="<?= $Page->ShipVia->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->ShipVia->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->ShipVia->formatPattern()) ?>"<?= $Page->ShipVia->editAttributes() ?> aria-describedby="x_ShipVia_help">
                <?= $Page->ShipVia->getCustomMessage() ?>
                <div class="invalid-feedback"><?= $Page->ShipVia->getErrorMessage() ?></div>
                </span>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->Freight->Visible && (!$Page->isConfirm() || $Page->Freight->multiUpdateSelected())) { // Freight ?>
    <div id="r_Freight"<?= $Page->Freight->rowAttributes() ?>>
        <label for="x_Freight" class="<?= $Page->LeftColumnClass ?>">
            <div class="form-check">
                <input type="checkbox" name="u_Freight" id="u_Freight" class="form-check-input ew-multi-select" value="1"<?= $Page->Freight->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="form-check-label" for="u_Freight"><?= $Page->Freight->caption() ?></label>
            </div>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->Freight->cellAttributes() ?>>
                <span id="el_orders2_Freight">
                <input type="<?= $Page->Freight->getInputTextType() ?>" name="x_Freight" id="x_Freight" data-table="orders2" data-field="x_Freight" value="<?= $Page->Freight->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->Freight->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Freight->formatPattern()) ?>"<?= $Page->Freight->editAttributes() ?> aria-describedby="x_Freight_help">
                <?= $Page->Freight->getCustomMessage() ?>
                <div class="invalid-feedback"><?= $Page->Freight->getErrorMessage() ?></div>
                </span>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->ShipName->Visible && (!$Page->isConfirm() || $Page->ShipName->multiUpdateSelected())) { // ShipName ?>
    <div id="r_ShipName"<?= $Page->ShipName->rowAttributes() ?>>
        <label for="x_ShipName" class="<?= $Page->LeftColumnClass ?>">
            <div class="form-check">
                <input type="checkbox" name="u_ShipName" id="u_ShipName" class="form-check-input ew-multi-select" value="1"<?= $Page->ShipName->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="form-check-label" for="u_ShipName"><?= $Page->ShipName->caption() ?></label>
            </div>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->ShipName->cellAttributes() ?>>
                <span id="el_orders2_ShipName">
                <input type="<?= $Page->ShipName->getInputTextType() ?>" name="x_ShipName" id="x_ShipName" data-table="orders2" data-field="x_ShipName" value="<?= $Page->ShipName->EditValue ?>" size="30" maxlength="40" placeholder="<?= HtmlEncode($Page->ShipName->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->ShipName->formatPattern()) ?>"<?= $Page->ShipName->editAttributes() ?> aria-describedby="x_ShipName_help">
                <?= $Page->ShipName->getCustomMessage() ?>
                <div class="invalid-feedback"><?= $Page->ShipName->getErrorMessage() ?></div>
                </span>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->ShipAddress->Visible && (!$Page->isConfirm() || $Page->ShipAddress->multiUpdateSelected())) { // ShipAddress ?>
    <div id="r_ShipAddress"<?= $Page->ShipAddress->rowAttributes() ?>>
        <label for="x_ShipAddress" class="<?= $Page->LeftColumnClass ?>">
            <div class="form-check">
                <input type="checkbox" name="u_ShipAddress" id="u_ShipAddress" class="form-check-input ew-multi-select" value="1"<?= $Page->ShipAddress->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="form-check-label" for="u_ShipAddress"><?= $Page->ShipAddress->caption() ?></label>
            </div>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->ShipAddress->cellAttributes() ?>>
                <span id="el_orders2_ShipAddress">
                <input type="<?= $Page->ShipAddress->getInputTextType() ?>" name="x_ShipAddress" id="x_ShipAddress" data-table="orders2" data-field="x_ShipAddress" value="<?= $Page->ShipAddress->EditValue ?>" size="30" maxlength="60" placeholder="<?= HtmlEncode($Page->ShipAddress->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->ShipAddress->formatPattern()) ?>"<?= $Page->ShipAddress->editAttributes() ?> aria-describedby="x_ShipAddress_help">
                <?= $Page->ShipAddress->getCustomMessage() ?>
                <div class="invalid-feedback"><?= $Page->ShipAddress->getErrorMessage() ?></div>
                </span>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->ShipCity->Visible && (!$Page->isConfirm() || $Page->ShipCity->multiUpdateSelected())) { // ShipCity ?>
    <div id="r_ShipCity"<?= $Page->ShipCity->rowAttributes() ?>>
        <label for="x_ShipCity" class="<?= $Page->LeftColumnClass ?>">
            <div class="form-check">
                <input type="checkbox" name="u_ShipCity" id="u_ShipCity" class="form-check-input ew-multi-select" value="1"<?= $Page->ShipCity->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="form-check-label" for="u_ShipCity"><?= $Page->ShipCity->caption() ?></label>
            </div>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->ShipCity->cellAttributes() ?>>
                <span id="el_orders2_ShipCity">
                <input type="<?= $Page->ShipCity->getInputTextType() ?>" name="x_ShipCity" id="x_ShipCity" data-table="orders2" data-field="x_ShipCity" value="<?= $Page->ShipCity->EditValue ?>" size="30" maxlength="15" placeholder="<?= HtmlEncode($Page->ShipCity->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->ShipCity->formatPattern()) ?>"<?= $Page->ShipCity->editAttributes() ?> aria-describedby="x_ShipCity_help">
                <?= $Page->ShipCity->getCustomMessage() ?>
                <div class="invalid-feedback"><?= $Page->ShipCity->getErrorMessage() ?></div>
                </span>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->ShipRegion->Visible && (!$Page->isConfirm() || $Page->ShipRegion->multiUpdateSelected())) { // ShipRegion ?>
    <div id="r_ShipRegion"<?= $Page->ShipRegion->rowAttributes() ?>>
        <label for="x_ShipRegion" class="<?= $Page->LeftColumnClass ?>">
            <div class="form-check">
                <input type="checkbox" name="u_ShipRegion" id="u_ShipRegion" class="form-check-input ew-multi-select" value="1"<?= $Page->ShipRegion->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="form-check-label" for="u_ShipRegion"><?= $Page->ShipRegion->caption() ?></label>
            </div>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->ShipRegion->cellAttributes() ?>>
                <span id="el_orders2_ShipRegion">
                <input type="<?= $Page->ShipRegion->getInputTextType() ?>" name="x_ShipRegion" id="x_ShipRegion" data-table="orders2" data-field="x_ShipRegion" value="<?= $Page->ShipRegion->EditValue ?>" size="30" maxlength="15" placeholder="<?= HtmlEncode($Page->ShipRegion->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->ShipRegion->formatPattern()) ?>"<?= $Page->ShipRegion->editAttributes() ?> aria-describedby="x_ShipRegion_help">
                <?= $Page->ShipRegion->getCustomMessage() ?>
                <div class="invalid-feedback"><?= $Page->ShipRegion->getErrorMessage() ?></div>
                </span>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->ShipPostalCode->Visible && (!$Page->isConfirm() || $Page->ShipPostalCode->multiUpdateSelected())) { // ShipPostalCode ?>
    <div id="r_ShipPostalCode"<?= $Page->ShipPostalCode->rowAttributes() ?>>
        <label for="x_ShipPostalCode" class="<?= $Page->LeftColumnClass ?>">
            <div class="form-check">
                <input type="checkbox" name="u_ShipPostalCode" id="u_ShipPostalCode" class="form-check-input ew-multi-select" value="1"<?= $Page->ShipPostalCode->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="form-check-label" for="u_ShipPostalCode"><?= $Page->ShipPostalCode->caption() ?></label>
            </div>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->ShipPostalCode->cellAttributes() ?>>
                <span id="el_orders2_ShipPostalCode">
                <input type="<?= $Page->ShipPostalCode->getInputTextType() ?>" name="x_ShipPostalCode" id="x_ShipPostalCode" data-table="orders2" data-field="x_ShipPostalCode" value="<?= $Page->ShipPostalCode->EditValue ?>" size="30" maxlength="10" placeholder="<?= HtmlEncode($Page->ShipPostalCode->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->ShipPostalCode->formatPattern()) ?>"<?= $Page->ShipPostalCode->editAttributes() ?> aria-describedby="x_ShipPostalCode_help">
                <?= $Page->ShipPostalCode->getCustomMessage() ?>
                <div class="invalid-feedback"><?= $Page->ShipPostalCode->getErrorMessage() ?></div>
                </span>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->ShipCountry->Visible && (!$Page->isConfirm() || $Page->ShipCountry->multiUpdateSelected())) { // ShipCountry ?>
    <div id="r_ShipCountry"<?= $Page->ShipCountry->rowAttributes() ?>>
        <label for="x_ShipCountry" class="<?= $Page->LeftColumnClass ?>">
            <div class="form-check">
                <input type="checkbox" name="u_ShipCountry" id="u_ShipCountry" class="form-check-input ew-multi-select" value="1"<?= $Page->ShipCountry->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="form-check-label" for="u_ShipCountry"><?= $Page->ShipCountry->caption() ?></label>
            </div>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->ShipCountry->cellAttributes() ?>>
                <span id="el_orders2_ShipCountry">
                <input type="<?= $Page->ShipCountry->getInputTextType() ?>" name="x_ShipCountry" id="x_ShipCountry" data-table="orders2" data-field="x_ShipCountry" value="<?= $Page->ShipCountry->EditValue ?>" size="30" maxlength="15" placeholder="<?= HtmlEncode($Page->ShipCountry->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->ShipCountry->formatPattern()) ?>"<?= $Page->ShipCountry->editAttributes() ?> aria-describedby="x_ShipCountry_help">
                <?= $Page->ShipCountry->getCustomMessage() ?>
                <div class="invalid-feedback"><?= $Page->ShipCountry->getErrorMessage() ?></div>
                </span>
            </div>
        </div>
    </div>
<?php } ?>
</div><!-- /page -->
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="forders2update"><?= $Language->phrase("UpdateBtn") ?></button>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="forders2update" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
    </div><!-- /buttons offset -->
<?= $Page->IsModal ? "</template>" : "</div>" ?><!-- /buttons .row -->
</form>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<script>
// Field event handlers
loadjs.ready("head", function() {
    ew.addEventHandlers("orders2");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
