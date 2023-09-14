<?php

namespace PHPMaker2023\demo2023;

// Page object
$CarsSearch = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { cars: currentTable } });
var currentPageID = ew.PAGE_ID = "search";
var currentForm;
var fcarssearch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery,
        fields = currentTable.fields;

    // Form object for search
    let form = new ew.FormBuilder()
        .setId("fcarssearch")
        .setPageId("search")
<?php if ($Page->IsModal && $Page->UseAjaxActions) { ?>
        .setSubmitWithFetch(true)
<?php } ?>

        // Add fields
        .addFields([
            ["ID", [ew.Validators.integer], fields.ID.isInvalid],
            ["Trademark", [], fields.Trademark.isInvalid],
            ["Model", [], fields.Model.isInvalid],
            ["HP", [], fields.HP.isInvalid],
            ["Cylinders", [ew.Validators.integer], fields.Cylinders.isInvalid],
            ["Description", [], fields.Description.isInvalid],
            ["Price", [ew.Validators.float], fields.Price.isInvalid],
            ["Doors", [ew.Validators.integer], fields.Doors.isInvalid],
            ["Torque", [], fields.Torque.isInvalid]
        ])
        // Validate form
        .setValidate(
            async function () {
                if (!this.validateRequired)
                    return true; // Ignore validation
                let fobj = this.getForm();

                // Validate fields
                if (!this.validateFields())
                    return false;

                // Call Form_CustomValidate event
                if (!(await this.customValidate?.(fobj) ?? true)) {
                    this.focus();
                    return false;
                }
                return true;
            }
        )

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
        })
        .build();
    window[form.id] = form;
<?php if ($Page->IsModal) { ?>
    currentAdvancedSearchForm = form;
<?php } else { ?>
    currentForm = form;
<?php } ?>
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
<form name="fcarssearch" id="fcarssearch" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="on">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="cars">
<input type="hidden" name="action" id="action" value="search">
<?php if ($Page->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div class="ew-search-div"><!-- page* -->
<?php if ($Page->ID->Visible) { // ID ?>
    <div id="r_ID" class="row"<?= $Page->ID->rowAttributes() ?>>
        <label for="x_ID" class="<?= $Page->LeftColumnClass ?>"><span id="elh_cars_ID"><?= $Page->ID->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_ID" id="z_ID" value="=">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->ID->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_cars_ID" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->ID->getInputTextType() ?>" name="x_ID" id="x_ID" data-table="cars" data-field="x_ID" value="<?= $Page->ID->EditValue ?>" placeholder="<?= HtmlEncode($Page->ID->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->ID->formatPattern()) ?>"<?= $Page->ID->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->ID->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->Trademark->Visible) { // Trademark ?>
    <div id="r_Trademark" class="row"<?= $Page->Trademark->rowAttributes() ?>>
        <label for="x_Trademark" class="<?= $Page->LeftColumnClass ?>"><span id="elh_cars_Trademark"><?= $Page->Trademark->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_Trademark" id="z_Trademark" value="LIKE">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->Trademark->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_cars_Trademark" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->Trademark->getInputTextType() ?>" name="x_Trademark" id="x_Trademark" data-table="cars" data-field="x_Trademark" value="<?= $Page->Trademark->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->Trademark->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Trademark->formatPattern()) ?>"<?= $Page->Trademark->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->Trademark->getErrorMessage(false) ?></div>
<?= $Page->Trademark->Lookup->getParamTag($Page, "p_x_Trademark") ?>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->Model->Visible) { // Model ?>
    <div id="r_Model" class="row"<?= $Page->Model->rowAttributes() ?>>
        <label for="x_Model" class="<?= $Page->LeftColumnClass ?>"><span id="elh_cars_Model"><?= $Page->Model->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_Model" id="z_Model" value="LIKE">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->Model->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_cars_Model" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->Model->getInputTextType() ?>" name="x_Model" id="x_Model" data-table="cars" data-field="x_Model" value="<?= $Page->Model->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->Model->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Model->formatPattern()) ?>"<?= $Page->Model->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->Model->getErrorMessage(false) ?></div>
<?= $Page->Model->Lookup->getParamTag($Page, "p_x_Model") ?>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->HP->Visible) { // HP ?>
    <div id="r_HP" class="row"<?= $Page->HP->rowAttributes() ?>>
        <label for="x_HP" class="<?= $Page->LeftColumnClass ?>"><span id="elh_cars_HP"><?= $Page->HP->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_HP" id="z_HP" value="LIKE">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->HP->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_cars_HP" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->HP->getInputTextType() ?>" name="x_HP" id="x_HP" data-table="cars" data-field="x_HP" value="<?= $Page->HP->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->HP->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->HP->formatPattern()) ?>"<?= $Page->HP->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->HP->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->Cylinders->Visible) { // Cylinders ?>
    <div id="r_Cylinders" class="row"<?= $Page->Cylinders->rowAttributes() ?>>
        <label for="x_Cylinders" class="<?= $Page->LeftColumnClass ?>"><span id="elh_cars_Cylinders"><?= $Page->Cylinders->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_Cylinders" id="z_Cylinders" value="=">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->Cylinders->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_cars_Cylinders" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->Cylinders->getInputTextType() ?>" name="x_Cylinders" id="x_Cylinders" data-table="cars" data-field="x_Cylinders" value="<?= $Page->Cylinders->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->Cylinders->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Cylinders->formatPattern()) ?>"<?= $Page->Cylinders->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->Cylinders->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->Description->Visible) { // Description ?>
    <div id="r_Description" class="row"<?= $Page->Description->rowAttributes() ?>>
        <label class="<?= $Page->LeftColumnClass ?>"><span id="elh_cars_Description"><?= $Page->Description->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_Description" id="z_Description" value="LIKE">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->Description->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_cars_Description" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->Description->getInputTextType() ?>" name="x_Description" id="x_Description" data-table="cars" data-field="x_Description" value="<?= $Page->Description->EditValue ?>" size="35" placeholder="<?= HtmlEncode($Page->Description->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Description->formatPattern()) ?>"<?= $Page->Description->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->Description->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->Price->Visible) { // Price ?>
    <div id="r_Price" class="row"<?= $Page->Price->rowAttributes() ?>>
        <label for="x_Price" class="<?= $Page->LeftColumnClass ?>"><span id="elh_cars_Price"><?= $Page->Price->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_Price" id="z_Price" value="=">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->Price->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_cars_Price" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->Price->getInputTextType() ?>" name="x_Price" id="x_Price" data-table="cars" data-field="x_Price" value="<?= $Page->Price->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->Price->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Price->formatPattern()) ?>"<?= $Page->Price->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->Price->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->Doors->Visible) { // Doors ?>
    <div id="r_Doors" class="row"<?= $Page->Doors->rowAttributes() ?>>
        <label for="x_Doors" class="<?= $Page->LeftColumnClass ?>"><span id="elh_cars_Doors"><?= $Page->Doors->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_Doors" id="z_Doors" value="=">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->Doors->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_cars_Doors" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->Doors->getInputTextType() ?>" name="x_Doors" id="x_Doors" data-table="cars" data-field="x_Doors" value="<?= $Page->Doors->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->Doors->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Doors->formatPattern()) ?>"<?= $Page->Doors->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->Doors->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->Torque->Visible) { // Torque ?>
    <div id="r_Torque" class="row"<?= $Page->Torque->rowAttributes() ?>>
        <label for="x_Torque" class="<?= $Page->LeftColumnClass ?>"><span id="elh_cars_Torque"><?= $Page->Torque->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_Torque" id="z_Torque" value="LIKE">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->Torque->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_cars_Torque" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->Torque->getInputTextType() ?>" name="x_Torque" id="x_Torque" data-table="cars" data-field="x_Torque" value="<?= $Page->Torque->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->Torque->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Torque->formatPattern()) ?>"<?= $Page->Torque->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->Torque->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
        <button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="fcarssearch"><?= $Language->phrase("Search") ?></button>
        <?php if ($Page->IsModal) { ?>
        <button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="fcarssearch"><?= $Language->phrase("Cancel") ?></button>
        <?php } else { ?>
        <button class="btn btn-default ew-btn" name="btn-reset" id="btn-reset" type="button" form="fcarssearch" data-ew-action="reload"><?= $Language->phrase("Reset") ?></button>
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
