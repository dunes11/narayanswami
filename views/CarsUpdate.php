<?php

namespace PHPMaker2023\demo2023;

// Page object
$CarsUpdate = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { cars: currentTable } });
var currentPageID = ew.PAGE_ID = "update";
var currentForm;
var fcarsupdate;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fcarsupdate")
        .setPageId("update")

        // Add fields
        .setFields([
            ["ID", [fields.ID.visible && fields.ID.required ? ew.Validators.required(fields.ID.caption) : null], fields.ID.isInvalid],
            ["Trademark", [fields.Trademark.visible && fields.Trademark.required ? ew.Validators.required(fields.Trademark.caption) : null], fields.Trademark.isInvalid],
            ["Model", [fields.Model.visible && fields.Model.required ? ew.Validators.required(fields.Model.caption) : null], fields.Model.isInvalid],
            ["HP", [fields.HP.visible && fields.HP.required ? ew.Validators.required(fields.HP.caption) : null], fields.HP.isInvalid],
            ["Cylinders", [fields.Cylinders.visible && fields.Cylinders.required ? ew.Validators.required(fields.Cylinders.caption) : null, ew.Validators.integer, ew.Validators.selected], fields.Cylinders.isInvalid],
            ["TransmissionSpeeds", [fields.TransmissionSpeeds.visible && fields.TransmissionSpeeds.required ? ew.Validators.required(fields.TransmissionSpeeds.caption) : null], fields.TransmissionSpeeds.isInvalid],
            ["TransmissAutomatic", [fields.TransmissAutomatic.visible && fields.TransmissAutomatic.required ? ew.Validators.required(fields.TransmissAutomatic.caption) : null], fields.TransmissAutomatic.isInvalid],
            ["MPGCity", [fields.MPGCity.visible && fields.MPGCity.required ? ew.Validators.required(fields.MPGCity.caption) : null, ew.Validators.integer, ew.Validators.selected], fields.MPGCity.isInvalid],
            ["MPGHighway", [fields.MPGHighway.visible && fields.MPGHighway.required ? ew.Validators.required(fields.MPGHighway.caption) : null, ew.Validators.integer, ew.Validators.selected], fields.MPGHighway.isInvalid],
            ["Description", [fields.Description.visible && fields.Description.required ? ew.Validators.required(fields.Description.caption) : null], fields.Description.isInvalid],
            ["Price", [fields.Price.visible && fields.Price.required ? ew.Validators.required(fields.Price.caption) : null, ew.Validators.float, ew.Validators.selected], fields.Price.isInvalid],
            ["Picture", [fields.Picture.visible && fields.Picture.required ? ew.Validators.fileRequired(fields.Picture.caption) : null], fields.Picture.isInvalid],
            ["Doors", [fields.Doors.visible && fields.Doors.required ? ew.Validators.required(fields.Doors.caption) : null, ew.Validators.integer, ew.Validators.selected], fields.Doors.isInvalid],
            ["Torque", [fields.Torque.visible && fields.Torque.required ? ew.Validators.required(fields.Torque.caption) : null], fields.Torque.isInvalid]
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
            "Trademark": <?= $Page->Trademark->toClientList($Page) ?>,
            "Model": <?= $Page->Model->toClientList($Page) ?>,
            "TransmissAutomatic": <?= $Page->TransmissAutomatic->toClientList($Page) ?>,
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
<form name="fcarsupdate" id="fcarsupdate" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="on">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="cars">
<?php if ($Page->isConfirm()) { // Confirm page ?>
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="confirm" id="confirm" value="confirm">
<?php } else { ?>
<input type="hidden" name="action" id="action" value="confirm">
<?php } ?>
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php foreach ($Page->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode(Config("COMPOSITE_KEY_SEPARATOR"), $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?= HtmlEncode($keyvalue) ?>">
<?php } ?>
<div id="tbl_carsupdate" class="ew-update-div"><!-- page -->
    <?php if (!$Page->isConfirm()) { // Confirm page ?>
    <div class="form-check">
        <input type="checkbox" class="form-check-input" name="u" id="u" data-ew-action="select-all"<?= $Page->Disabled ?>><label class="form-check-label" for="u"><?= $Language->phrase("SelectAll") ?></label>
    </div>
    <?php } ?>
<?php if ($Page->Trademark->Visible && (!$Page->isConfirm() || $Page->Trademark->multiUpdateSelected())) { // Trademark ?>
    <div id="r_Trademark"<?= $Page->Trademark->rowAttributes() ?>>
        <label for="x_Trademark" class="<?= $Page->LeftColumnClass ?>">
            <?php if (!$Page->isConfirm()) { ?>
            <div class="form-check">
                <input type="checkbox" name="u_Trademark" id="u_Trademark" class="form-check-input ew-multi-select" value="1"<?= $Page->Trademark->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="form-check-label" for="u_Trademark"><?= $Page->Trademark->caption() ?></label>
            </div>
            <?php } else { ?>
            <input type="hidden" name="u_Trademark" id="u_Trademark" value="<?= $Page->Trademark->MultiUpdate ?>">
            <?= $Page->Trademark->caption() ?>
            <?php } ?>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->Trademark->cellAttributes() ?>>
                <?php if (!$Page->isConfirm()) { ?>
                <span id="el_cars_Trademark">
                    <select
                        id="x_Trademark"
                        name="x_Trademark"
                        class="form-select ew-select<?= $Page->Trademark->isInvalidClass() ?>"
                        <?php if (!$Page->Trademark->IsNativeSelect) { ?>
                        data-select2-id="fcarsupdate_x_Trademark"
                        <?php } ?>
                        data-table="cars"
                        data-field="x_Trademark"
                        data-value-separator="<?= $Page->Trademark->displayValueSeparatorAttribute() ?>"
                        data-placeholder="<?= HtmlEncode($Page->Trademark->getPlaceHolder()) ?>"
                        data-ew-action="update-options"
                        <?= $Page->Trademark->editAttributes() ?>>
                        <?= $Page->Trademark->selectOptionListHtml("x_Trademark") ?>
                    </select>
                    <?= $Page->Trademark->getCustomMessage() ?>
                    <div class="invalid-feedback"><?= $Page->Trademark->getErrorMessage() ?></div>
                <?= $Page->Trademark->Lookup->getParamTag($Page, "p_x_Trademark") ?>
                <?php if (!$Page->Trademark->IsNativeSelect) { ?>
                <script>
                loadjs.ready("fcarsupdate", function() {
                    var options = { name: "x_Trademark", selectId: "fcarsupdate_x_Trademark" },
                        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
                    if (!el)
                        return;
                    options.closeOnSelect = !options.multiple;
                    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
                    if (fcarsupdate.lists.Trademark?.lookupOptions.length) {
                        options.data = { id: "x_Trademark", form: "fcarsupdate" };
                    } else {
                        options.ajax = { id: "x_Trademark", form: "fcarsupdate", limit: ew.LOOKUP_PAGE_SIZE };
                    }
                    options.minimumResultsForSearch = Infinity;
                    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.cars.fields.Trademark.selectOptions);
                    ew.createSelect(options);
                });
                </script>
                <?php } ?>
                </span>
                <?php } else { ?>
                <span id="el_cars_Trademark">
                <span<?= $Page->Trademark->viewAttributes() ?>>
                <span class="form-control-plaintext"><?= $Page->Trademark->getDisplayValue($Page->Trademark->ViewValue) ?></span></span>
                <input type="hidden" data-table="cars" data-field="x_Trademark" data-hidden="1" name="x_Trademark" id="x_Trademark" value="<?= HtmlEncode($Page->Trademark->FormValue) ?>">
                </span>
                <?php } ?>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->Model->Visible && (!$Page->isConfirm() || $Page->Model->multiUpdateSelected())) { // Model ?>
    <div id="r_Model"<?= $Page->Model->rowAttributes() ?>>
        <label for="x_Model" class="<?= $Page->LeftColumnClass ?>">
            <?php if (!$Page->isConfirm()) { ?>
            <div class="form-check">
                <input type="checkbox" name="u_Model" id="u_Model" class="form-check-input ew-multi-select" value="1"<?= $Page->Model->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="form-check-label" for="u_Model"><?= $Page->Model->caption() ?></label>
            </div>
            <?php } else { ?>
            <input type="hidden" name="u_Model" id="u_Model" value="<?= $Page->Model->MultiUpdate ?>">
            <?= $Page->Model->caption() ?>
            <?php } ?>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->Model->cellAttributes() ?>>
                <?php if (!$Page->isConfirm()) { ?>
                <span id="el_cars_Model">
                    <select
                        id="x_Model"
                        name="x_Model"
                        class="form-select ew-select<?= $Page->Model->isInvalidClass() ?>"
                        <?php if (!$Page->Model->IsNativeSelect) { ?>
                        data-select2-id="fcarsupdate_x_Model"
                        <?php } ?>
                        data-table="cars"
                        data-field="x_Model"
                        data-value-separator="<?= $Page->Model->displayValueSeparatorAttribute() ?>"
                        data-placeholder="<?= HtmlEncode($Page->Model->getPlaceHolder()) ?>"
                        <?= $Page->Model->editAttributes() ?>>
                        <?= $Page->Model->selectOptionListHtml("x_Model") ?>
                    </select>
                    <?= $Page->Model->getCustomMessage() ?>
                    <div class="invalid-feedback"><?= $Page->Model->getErrorMessage() ?></div>
                <?= $Page->Model->Lookup->getParamTag($Page, "p_x_Model") ?>
                <?php if (!$Page->Model->IsNativeSelect) { ?>
                <script>
                loadjs.ready("fcarsupdate", function() {
                    var options = { name: "x_Model", selectId: "fcarsupdate_x_Model" },
                        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
                    if (!el)
                        return;
                    options.closeOnSelect = !options.multiple;
                    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
                    if (fcarsupdate.lists.Model?.lookupOptions.length) {
                        options.data = { id: "x_Model", form: "fcarsupdate" };
                    } else {
                        options.ajax = { id: "x_Model", form: "fcarsupdate", limit: ew.LOOKUP_PAGE_SIZE };
                    }
                    options.minimumResultsForSearch = Infinity;
                    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.cars.fields.Model.selectOptions);
                    ew.createSelect(options);
                });
                </script>
                <?php } ?>
                </span>
                <?php } else { ?>
                <span id="el_cars_Model">
                <span<?= $Page->Model->viewAttributes() ?>>
                <?php if (!EmptyString($Page->Model->TooltipValue) && $Page->Model->linkAttributes() != "") { ?>
                <a<?= $Page->Model->linkAttributes() ?>><span class="form-control-plaintext"><?= $Page->Model->getDisplayValue($Page->Model->ViewValue) ?></span></a>
                <?php } else { ?>
                <span class="form-control-plaintext"><?= $Page->Model->getDisplayValue($Page->Model->ViewValue) ?></span>
                <?php } ?>
                </span>
                <input type="hidden" data-table="cars" data-field="x_Model" data-hidden="1" name="x_Model" id="x_Model" value="<?= HtmlEncode($Page->Model->FormValue) ?>">
                </span>
                <?php } ?>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->HP->Visible && (!$Page->isConfirm() || $Page->HP->multiUpdateSelected())) { // HP ?>
    <div id="r_HP"<?= $Page->HP->rowAttributes() ?>>
        <label for="x_HP" class="<?= $Page->LeftColumnClass ?>">
            <?php if (!$Page->isConfirm()) { ?>
            <div class="form-check">
                <input type="checkbox" name="u_HP" id="u_HP" class="form-check-input ew-multi-select" value="1"<?= $Page->HP->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="form-check-label" for="u_HP"><?= $Page->HP->caption() ?></label>
            </div>
            <?php } else { ?>
            <input type="hidden" name="u_HP" id="u_HP" value="<?= $Page->HP->MultiUpdate ?>">
            <?= $Page->HP->caption() ?>
            <?php } ?>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->HP->cellAttributes() ?>>
                <?php if (!$Page->isConfirm()) { ?>
                <span id="el_cars_HP">
                <input type="<?= $Page->HP->getInputTextType() ?>" name="x_HP" id="x_HP" data-table="cars" data-field="x_HP" value="<?= $Page->HP->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->HP->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->HP->formatPattern()) ?>"<?= $Page->HP->editAttributes() ?> aria-describedby="x_HP_help">
                <?= $Page->HP->getCustomMessage() ?>
                <div class="invalid-feedback"><?= $Page->HP->getErrorMessage() ?></div>
                </span>
                <?php } else { ?>
                <span id="el_cars_HP">
                <span<?= $Page->HP->viewAttributes() ?>>
                <input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->HP->getDisplayValue($Page->HP->ViewValue))) ?>"></span>
                <input type="hidden" data-table="cars" data-field="x_HP" data-hidden="1" name="x_HP" id="x_HP" value="<?= HtmlEncode($Page->HP->FormValue) ?>">
                </span>
                <?php } ?>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->Cylinders->Visible && (!$Page->isConfirm() || $Page->Cylinders->multiUpdateSelected())) { // Cylinders ?>
    <div id="r_Cylinders"<?= $Page->Cylinders->rowAttributes() ?>>
        <label for="x_Cylinders" class="<?= $Page->LeftColumnClass ?>">
            <?php if (!$Page->isConfirm()) { ?>
            <div class="form-check">
                <input type="checkbox" name="u_Cylinders" id="u_Cylinders" class="form-check-input ew-multi-select" value="1"<?= $Page->Cylinders->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="form-check-label" for="u_Cylinders"><?= $Page->Cylinders->caption() ?></label>
            </div>
            <?php } else { ?>
            <input type="hidden" name="u_Cylinders" id="u_Cylinders" value="<?= $Page->Cylinders->MultiUpdate ?>">
            <?= $Page->Cylinders->caption() ?>
            <?php } ?>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->Cylinders->cellAttributes() ?>>
                <?php if (!$Page->isConfirm()) { ?>
                <span id="el_cars_Cylinders">
                <input type="<?= $Page->Cylinders->getInputTextType() ?>" name="x_Cylinders" id="x_Cylinders" data-table="cars" data-field="x_Cylinders" value="<?= $Page->Cylinders->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->Cylinders->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Cylinders->formatPattern()) ?>"<?= $Page->Cylinders->editAttributes() ?> aria-describedby="x_Cylinders_help">
                <?= $Page->Cylinders->getCustomMessage() ?>
                <div class="invalid-feedback"><?= $Page->Cylinders->getErrorMessage() ?></div>
                </span>
                <?php } else { ?>
                <span id="el_cars_Cylinders">
                <span<?= $Page->Cylinders->viewAttributes() ?>>
                <input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->Cylinders->getDisplayValue($Page->Cylinders->ViewValue))) ?>"></span>
                <input type="hidden" data-table="cars" data-field="x_Cylinders" data-hidden="1" name="x_Cylinders" id="x_Cylinders" value="<?= HtmlEncode($Page->Cylinders->FormValue) ?>">
                </span>
                <?php } ?>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->TransmissionSpeeds->Visible && (!$Page->isConfirm() || $Page->TransmissionSpeeds->multiUpdateSelected())) { // Transmission Speeds ?>
    <div id="r_TransmissionSpeeds"<?= $Page->TransmissionSpeeds->rowAttributes() ?>>
        <label for="x_TransmissionSpeeds" class="<?= $Page->LeftColumnClass ?>">
            <?php if (!$Page->isConfirm()) { ?>
            <div class="form-check">
                <input type="checkbox" name="u_TransmissionSpeeds" id="u_TransmissionSpeeds" class="form-check-input ew-multi-select" value="1"<?= $Page->TransmissionSpeeds->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="form-check-label" for="u_TransmissionSpeeds"><?= $Page->TransmissionSpeeds->caption() ?></label>
            </div>
            <?php } else { ?>
            <input type="hidden" name="u_TransmissionSpeeds" id="u_TransmissionSpeeds" value="<?= $Page->TransmissionSpeeds->MultiUpdate ?>">
            <?= $Page->TransmissionSpeeds->caption() ?>
            <?php } ?>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->TransmissionSpeeds->cellAttributes() ?>>
                <?php if (!$Page->isConfirm()) { ?>
                <span id="el_cars_TransmissionSpeeds">
                <input type="<?= $Page->TransmissionSpeeds->getInputTextType() ?>" name="x_TransmissionSpeeds" id="x_TransmissionSpeeds" data-table="cars" data-field="x_TransmissionSpeeds" value="<?= $Page->TransmissionSpeeds->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->TransmissionSpeeds->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->TransmissionSpeeds->formatPattern()) ?>"<?= $Page->TransmissionSpeeds->editAttributes() ?> aria-describedby="x_TransmissionSpeeds_help">
                <?= $Page->TransmissionSpeeds->getCustomMessage() ?>
                <div class="invalid-feedback"><?= $Page->TransmissionSpeeds->getErrorMessage() ?></div>
                </span>
                <?php } else { ?>
                <span id="el_cars_TransmissionSpeeds">
                <span<?= $Page->TransmissionSpeeds->viewAttributes() ?>>
                <input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->TransmissionSpeeds->getDisplayValue($Page->TransmissionSpeeds->ViewValue))) ?>"></span>
                <input type="hidden" data-table="cars" data-field="x_TransmissionSpeeds" data-hidden="1" name="x_TransmissionSpeeds" id="x_TransmissionSpeeds" value="<?= HtmlEncode($Page->TransmissionSpeeds->FormValue) ?>">
                </span>
                <?php } ?>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->TransmissAutomatic->Visible && (!$Page->isConfirm() || $Page->TransmissAutomatic->multiUpdateSelected())) { // TransmissAutomatic ?>
    <div id="r_TransmissAutomatic"<?= $Page->TransmissAutomatic->rowAttributes() ?>>
        <label class="<?= $Page->LeftColumnClass ?>">
            <?php if (!$Page->isConfirm()) { ?>
            <div class="form-check">
                <input type="checkbox" name="u_TransmissAutomatic" id="u_TransmissAutomatic" class="form-check-input ew-multi-select" value="1"<?= $Page->TransmissAutomatic->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="form-check-label" for="u_TransmissAutomatic"><?= $Page->TransmissAutomatic->caption() ?></label>
            </div>
            <?php } else { ?>
            <input type="hidden" name="u_TransmissAutomatic" id="u_TransmissAutomatic" value="<?= $Page->TransmissAutomatic->MultiUpdate ?>">
            <?= $Page->TransmissAutomatic->caption() ?>
            <?php } ?>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->TransmissAutomatic->cellAttributes() ?>>
                <?php if (!$Page->isConfirm()) { ?>
                <span id="el_cars_TransmissAutomatic">
                <div class="form-check d-inline-block">
                    <input type="checkbox" class="form-check-input<?= $Page->TransmissAutomatic->isInvalidClass() ?>" data-table="cars" data-field="x_TransmissAutomatic" data-boolean name="x_TransmissAutomatic" id="x_TransmissAutomatic" value="1"<?= ConvertToBool($Page->TransmissAutomatic->CurrentValue) ? " checked" : "" ?><?= $Page->TransmissAutomatic->editAttributes() ?> aria-describedby="x_TransmissAutomatic_help">
                    <div class="invalid-feedback"><?= $Page->TransmissAutomatic->getErrorMessage() ?></div>
                </div>
                <?= $Page->TransmissAutomatic->getCustomMessage() ?>
                </span>
                <?php } else { ?>
                <span id="el_cars_TransmissAutomatic">
                <span<?= $Page->TransmissAutomatic->viewAttributes() ?>>
                <div class="form-check d-inline-block">
                    <input type="checkbox" id="x_TransmissAutomatic_<?= $Page->RowCount ?>" class="form-check-input" value="<?= $Page->TransmissAutomatic->ViewValue ?>" disabled<?php if (ConvertToBool($Page->TransmissAutomatic->CurrentValue)) { ?> checked<?php } ?>>
                    <label class="form-check-label" for="x_TransmissAutomatic_<?= $Page->RowCount ?>"></label>
                </div></span>
                <input type="hidden" data-table="cars" data-field="x_TransmissAutomatic" data-hidden="1" name="x_TransmissAutomatic" id="x_TransmissAutomatic" value="<?= HtmlEncode($Page->TransmissAutomatic->FormValue) ?>">
                </span>
                <?php } ?>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->MPGCity->Visible && (!$Page->isConfirm() || $Page->MPGCity->multiUpdateSelected())) { // MPG City ?>
    <div id="r_MPGCity"<?= $Page->MPGCity->rowAttributes() ?>>
        <label for="x_MPGCity" class="<?= $Page->LeftColumnClass ?>">
            <?php if (!$Page->isConfirm()) { ?>
            <div class="form-check">
                <input type="checkbox" name="u_MPGCity" id="u_MPGCity" class="form-check-input ew-multi-select" value="1"<?= $Page->MPGCity->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="form-check-label" for="u_MPGCity"><?= $Page->MPGCity->caption() ?></label>
            </div>
            <?php } else { ?>
            <input type="hidden" name="u_MPGCity" id="u_MPGCity" value="<?= $Page->MPGCity->MultiUpdate ?>">
            <?= $Page->MPGCity->caption() ?>
            <?php } ?>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->MPGCity->cellAttributes() ?>>
                <?php if (!$Page->isConfirm()) { ?>
                <span id="el_cars_MPGCity">
                <input type="<?= $Page->MPGCity->getInputTextType() ?>" name="x_MPGCity" id="x_MPGCity" data-table="cars" data-field="x_MPGCity" value="<?= $Page->MPGCity->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->MPGCity->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->MPGCity->formatPattern()) ?>"<?= $Page->MPGCity->editAttributes() ?> aria-describedby="x_MPGCity_help">
                <?= $Page->MPGCity->getCustomMessage() ?>
                <div class="invalid-feedback"><?= $Page->MPGCity->getErrorMessage() ?></div>
                </span>
                <?php } else { ?>
                <span id="el_cars_MPGCity">
                <span<?= $Page->MPGCity->viewAttributes() ?>>
                <input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->MPGCity->getDisplayValue($Page->MPGCity->ViewValue))) ?>"></span>
                <input type="hidden" data-table="cars" data-field="x_MPGCity" data-hidden="1" name="x_MPGCity" id="x_MPGCity" value="<?= HtmlEncode($Page->MPGCity->FormValue) ?>">
                </span>
                <?php } ?>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->MPGHighway->Visible && (!$Page->isConfirm() || $Page->MPGHighway->multiUpdateSelected())) { // MPG Highway ?>
    <div id="r_MPGHighway"<?= $Page->MPGHighway->rowAttributes() ?>>
        <label for="x_MPGHighway" class="<?= $Page->LeftColumnClass ?>">
            <?php if (!$Page->isConfirm()) { ?>
            <div class="form-check">
                <input type="checkbox" name="u_MPGHighway" id="u_MPGHighway" class="form-check-input ew-multi-select" value="1"<?= $Page->MPGHighway->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="form-check-label" for="u_MPGHighway"><?= $Page->MPGHighway->caption() ?></label>
            </div>
            <?php } else { ?>
            <input type="hidden" name="u_MPGHighway" id="u_MPGHighway" value="<?= $Page->MPGHighway->MultiUpdate ?>">
            <?= $Page->MPGHighway->caption() ?>
            <?php } ?>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->MPGHighway->cellAttributes() ?>>
                <?php if (!$Page->isConfirm()) { ?>
                <span id="el_cars_MPGHighway">
                <input type="<?= $Page->MPGHighway->getInputTextType() ?>" name="x_MPGHighway" id="x_MPGHighway" data-table="cars" data-field="x_MPGHighway" value="<?= $Page->MPGHighway->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->MPGHighway->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->MPGHighway->formatPattern()) ?>"<?= $Page->MPGHighway->editAttributes() ?> aria-describedby="x_MPGHighway_help">
                <?= $Page->MPGHighway->getCustomMessage() ?>
                <div class="invalid-feedback"><?= $Page->MPGHighway->getErrorMessage() ?></div>
                </span>
                <?php } else { ?>
                <span id="el_cars_MPGHighway">
                <span<?= $Page->MPGHighway->viewAttributes() ?>>
                <input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->MPGHighway->getDisplayValue($Page->MPGHighway->ViewValue))) ?>"></span>
                <input type="hidden" data-table="cars" data-field="x_MPGHighway" data-hidden="1" name="x_MPGHighway" id="x_MPGHighway" value="<?= HtmlEncode($Page->MPGHighway->FormValue) ?>">
                </span>
                <?php } ?>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->Description->Visible && (!$Page->isConfirm() || $Page->Description->multiUpdateSelected())) { // Description ?>
    <div id="r_Description"<?= $Page->Description->rowAttributes() ?>>
        <label class="<?= $Page->LeftColumnClass ?>">
            <?php if (!$Page->isConfirm()) { ?>
            <div class="form-check">
                <input type="checkbox" name="u_Description" id="u_Description" class="form-check-input ew-multi-select" value="1"<?= $Page->Description->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="form-check-label" for="u_Description"><?= $Page->Description->caption() ?></label>
            </div>
            <?php } else { ?>
            <input type="hidden" name="u_Description" id="u_Description" value="<?= $Page->Description->MultiUpdate ?>">
            <?= $Page->Description->caption() ?>
            <?php } ?>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->Description->cellAttributes() ?>>
                <?php if (!$Page->isConfirm()) { ?>
                <span id="el_cars_Description">
                <?php $Page->Description->EditAttrs->appendClass("editor"); ?>
                <textarea data-table="cars" data-field="x_Description" name="x_Description" id="x_Description" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->Description->getPlaceHolder()) ?>"<?= $Page->Description->editAttributes() ?> aria-describedby="x_Description_help"><?= $Page->Description->EditValue ?></textarea>
                <?= $Page->Description->getCustomMessage() ?>
                <div class="invalid-feedback"><?= $Page->Description->getErrorMessage() ?></div>
                <script>
                loadjs.ready(["fcarsupdate", "editor"], function() {
                    ew.createEditor("fcarsupdate", "x_Description", 35, 4, <?= $Page->Description->ReadOnly || false ? "true" : "false" ?>);
                });
                </script>
                </span>
                <?php } else { ?>
                <span id="el_cars_Description">
                <span<?= $Page->Description->viewAttributes() ?>>
                <?= $Page->Description->ViewValue ?></span>
                <input type="hidden" data-table="cars" data-field="x_Description" data-hidden="1" name="x_Description" id="x_Description" value="<?= HtmlEncode($Page->Description->FormValue) ?>">
                </span>
                <?php } ?>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->Price->Visible && (!$Page->isConfirm() || $Page->Price->multiUpdateSelected())) { // Price ?>
    <div id="r_Price"<?= $Page->Price->rowAttributes() ?>>
        <label for="x_Price" class="<?= $Page->LeftColumnClass ?>">
            <?php if (!$Page->isConfirm()) { ?>
            <div class="form-check">
                <input type="checkbox" name="u_Price" id="u_Price" class="form-check-input ew-multi-select" value="1"<?= $Page->Price->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="form-check-label" for="u_Price"><?= $Page->Price->caption() ?></label>
            </div>
            <?php } else { ?>
            <input type="hidden" name="u_Price" id="u_Price" value="<?= $Page->Price->MultiUpdate ?>">
            <?= $Page->Price->caption() ?>
            <?php } ?>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->Price->cellAttributes() ?>>
                <?php if (!$Page->isConfirm()) { ?>
                <span id="el_cars_Price">
                <input type="<?= $Page->Price->getInputTextType() ?>" name="x_Price" id="x_Price" data-table="cars" data-field="x_Price" value="<?= $Page->Price->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->Price->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Price->formatPattern()) ?>"<?= $Page->Price->editAttributes() ?> aria-describedby="x_Price_help">
                <?= $Page->Price->getCustomMessage() ?>
                <div class="invalid-feedback"><?= $Page->Price->getErrorMessage() ?></div>
                </span>
                <?php } else { ?>
                <span id="el_cars_Price">
                <span<?= $Page->Price->viewAttributes() ?>>
                <input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->Price->getDisplayValue($Page->Price->ViewValue))) ?>"></span>
                <input type="hidden" data-table="cars" data-field="x_Price" data-hidden="1" name="x_Price" id="x_Price" value="<?= HtmlEncode($Page->Price->FormValue) ?>">
                </span>
                <?php } ?>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->Picture->Visible && (!$Page->isConfirm() || $Page->Picture->multiUpdateSelected())) { // Picture ?>
    <div id="r_Picture"<?= $Page->Picture->rowAttributes() ?>>
        <label class="<?= $Page->LeftColumnClass ?>">
            <?php if (!$Page->isConfirm()) { ?>
            <div class="form-check">
                <input type="checkbox" name="u_Picture" id="u_Picture" class="form-check-input ew-multi-select" value="1"<?= $Page->Picture->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="form-check-label" for="u_Picture"><?= $Page->Picture->caption() ?></label>
            </div>
            <?php } else { ?>
            <input type="hidden" name="u_Picture" id="u_Picture" value="<?= $Page->Picture->MultiUpdate ?>">
            <?= $Page->Picture->caption() ?>
            <?php } ?>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->Picture->cellAttributes() ?>>
                <?php if (!$Page->isConfirm()) { ?>
                <span id="el_cars_Picture">
                <div id="fd_x_Picture" class="fileinput-button ew-file-drop-zone">
                    <input
                        type="file"
                        id="x_Picture"
                        name="x_Picture"
                        class="form-control ew-file-input"
                        title="<?= $Page->Picture->title() ?>"
                        lang="<?= CurrentLanguageID() ?>"
                        data-table="cars"
                        data-field="x_Picture"
                        data-size="0"
                        data-accept-file-types="<?= $Page->Picture->acceptFileTypes() ?>"
                        data-max-file-size="<?= $Page->Picture->UploadMaxFileSize ?>"
                        data-max-number-of-files="null"
                        data-disable-image-crop="<?= $Page->Picture->ImageCropper ? 0 : 1 ?>"
                        aria-describedby="x_Picture_help"
                        <?= ($Page->Picture->ReadOnly || $Page->Picture->Disabled) ? " disabled" : "" ?>
                        <?= $Page->Picture->editAttributes() ?>
                    >
                    <div class="text-muted ew-file-text"><?= $Language->phrase("ChooseFile") ?></div>
                    <?= $Page->Picture->getCustomMessage() ?>
                    <div class="invalid-feedback"><?= $Page->Picture->getErrorMessage() ?></div>
                </div>
                <input type="hidden" name="fn_x_Picture" id= "fn_x_Picture" value="<?= $Page->Picture->Upload->FileName ?>">
                <input type="hidden" name="fa_x_Picture" id= "fa_x_Picture" value="<?= (Post("fa_x_Picture") == "0") ? "0" : "1" ?>">
                <table id="ft_x_Picture" class="table table-sm float-start ew-upload-table"><tbody class="files"></tbody></table>
                </span>
                <?php } else { ?>
                <span id="el_cars_Picture">
                <div id="fd_x_Picture">
                    <input
                        type="file"
                        id="x_Picture"
                        name="x_Picture"
                        class="form-control ew-file-input d-none"
                        title="<?= $Page->Picture->title() ?>"
                        lang="<?= CurrentLanguageID() ?>"
                        data-table="cars"
                        data-field="x_Picture"
                        data-size="0"
                        data-accept-file-types="<?= $Page->Picture->acceptFileTypes() ?>"
                        data-max-file-size="<?= $Page->Picture->UploadMaxFileSize ?>"
                        data-max-number-of-files="null"
                        data-disable-image-crop="<?= $Page->Picture->ImageCropper ? 0 : 1 ?>"
                        aria-describedby="x_Picture_help"
                        <?= $Page->Picture->editAttributes() ?>
                    >
                    <?= $Page->Picture->getCustomMessage() ?>
                    <div class="invalid-feedback"><?= $Page->Picture->getErrorMessage() ?></div>
                </div>
                <input type="hidden" name="fn_x_Picture" id= "fn_x_Picture" value="<?= $Page->Picture->Upload->FileName ?>">
                <input type="hidden" name="fa_x_Picture" id= "fa_x_Picture" value="<?= (Post("fa_x_Picture") == "0") ? "0" : "1" ?>">
                <table id="ft_x_Picture" class="table table-sm float-start ew-upload-table"><tbody class="files"></tbody></table>
                </span>
                <?php } ?>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->Doors->Visible && (!$Page->isConfirm() || $Page->Doors->multiUpdateSelected())) { // Doors ?>
    <div id="r_Doors"<?= $Page->Doors->rowAttributes() ?>>
        <label for="x_Doors" class="<?= $Page->LeftColumnClass ?>">
            <?php if (!$Page->isConfirm()) { ?>
            <div class="form-check">
                <input type="checkbox" name="u_Doors" id="u_Doors" class="form-check-input ew-multi-select" value="1"<?= $Page->Doors->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="form-check-label" for="u_Doors"><?= $Page->Doors->caption() ?></label>
            </div>
            <?php } else { ?>
            <input type="hidden" name="u_Doors" id="u_Doors" value="<?= $Page->Doors->MultiUpdate ?>">
            <?= $Page->Doors->caption() ?>
            <?php } ?>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->Doors->cellAttributes() ?>>
                <?php if (!$Page->isConfirm()) { ?>
                <span id="el_cars_Doors">
                <input type="<?= $Page->Doors->getInputTextType() ?>" name="x_Doors" id="x_Doors" data-table="cars" data-field="x_Doors" value="<?= $Page->Doors->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->Doors->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Doors->formatPattern()) ?>"<?= $Page->Doors->editAttributes() ?> aria-describedby="x_Doors_help">
                <?= $Page->Doors->getCustomMessage() ?>
                <div class="invalid-feedback"><?= $Page->Doors->getErrorMessage() ?></div>
                </span>
                <?php } else { ?>
                <span id="el_cars_Doors">
                <span<?= $Page->Doors->viewAttributes() ?>>
                <input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->Doors->getDisplayValue($Page->Doors->ViewValue))) ?>"></span>
                <input type="hidden" data-table="cars" data-field="x_Doors" data-hidden="1" name="x_Doors" id="x_Doors" value="<?= HtmlEncode($Page->Doors->FormValue) ?>">
                </span>
                <?php } ?>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->Torque->Visible && (!$Page->isConfirm() || $Page->Torque->multiUpdateSelected())) { // Torque ?>
    <div id="r_Torque"<?= $Page->Torque->rowAttributes() ?>>
        <label for="x_Torque" class="<?= $Page->LeftColumnClass ?>">
            <?php if (!$Page->isConfirm()) { ?>
            <div class="form-check">
                <input type="checkbox" name="u_Torque" id="u_Torque" class="form-check-input ew-multi-select" value="1"<?= $Page->Torque->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="form-check-label" for="u_Torque"><?= $Page->Torque->caption() ?></label>
            </div>
            <?php } else { ?>
            <input type="hidden" name="u_Torque" id="u_Torque" value="<?= $Page->Torque->MultiUpdate ?>">
            <?= $Page->Torque->caption() ?>
            <?php } ?>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->Torque->cellAttributes() ?>>
                <?php if (!$Page->isConfirm()) { ?>
                <span id="el_cars_Torque">
                <input type="<?= $Page->Torque->getInputTextType() ?>" name="x_Torque" id="x_Torque" data-table="cars" data-field="x_Torque" value="<?= $Page->Torque->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->Torque->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Torque->formatPattern()) ?>"<?= $Page->Torque->editAttributes() ?> aria-describedby="x_Torque_help">
                <?= $Page->Torque->getCustomMessage() ?>
                <div class="invalid-feedback"><?= $Page->Torque->getErrorMessage() ?></div>
                </span>
                <?php } else { ?>
                <span id="el_cars_Torque">
                <span<?= $Page->Torque->viewAttributes() ?>>
                <input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->Torque->getDisplayValue($Page->Torque->ViewValue))) ?>"></span>
                <input type="hidden" data-table="cars" data-field="x_Torque" data-hidden="1" name="x_Torque" id="x_Torque" value="<?= HtmlEncode($Page->Torque->FormValue) ?>">
                </span>
                <?php } ?>
            </div>
        </div>
    </div>
<?php } ?>
</div><!-- /page -->
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<?php if (!$Page->isConfirm()) { // Confirm page ?>
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="fcarsupdate" data-ew-action="set-action" data-value="confirm"><?= $Language->phrase("UpdateBtn") ?></button>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="fcarsupdate" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="fcarsupdate"><?= $Language->phrase("ConfirmBtn") ?></button>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="submit" form="fcarsupdate" data-ew-action="set-action" data-value="cancel"><?= $Language->phrase("CancelBtn") ?></button>
<?php } ?>
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
    ew.addEventHandlers("cars");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
