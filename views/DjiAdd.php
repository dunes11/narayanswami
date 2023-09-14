<?php

namespace PHPMaker2023\demo2023;

// Page object
$DjiAdd = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { dji: currentTable } });
var currentPageID = ew.PAGE_ID = "add";
var currentForm;
var fdjiadd;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fdjiadd")
        .setPageId("add")

        // Add fields
        .setFields([
            ["Date", [fields.Date.visible && fields.Date.required ? ew.Validators.required(fields.Date.caption) : null, ew.Validators.datetime(fields.Date.clientFormatPattern)], fields.Date.isInvalid],
            ["Open", [fields.Open.visible && fields.Open.required ? ew.Validators.required(fields.Open.caption) : null, ew.Validators.float], fields.Open.isInvalid],
            ["High", [fields.High.visible && fields.High.required ? ew.Validators.required(fields.High.caption) : null, ew.Validators.float], fields.High.isInvalid],
            ["Low", [fields.Low.visible && fields.Low.required ? ew.Validators.required(fields.Low.caption) : null, ew.Validators.float], fields.Low.isInvalid],
            ["Close", [fields.Close.visible && fields.Close.required ? ew.Validators.required(fields.Close.caption) : null, ew.Validators.float], fields.Close.isInvalid],
            ["Volume", [fields.Volume.visible && fields.Volume.required ? ew.Validators.required(fields.Volume.caption) : null, ew.Validators.float], fields.Volume.isInvalid],
            ["AdjClose", [fields.AdjClose.visible && fields.AdjClose.required ? ew.Validators.required(fields.AdjClose.caption) : null, ew.Validators.float], fields.AdjClose.isInvalid],
            ["Name", [fields.Name.visible && fields.Name.required ? ew.Validators.required(fields.Name.caption) : null, ew.Validators.datetime(fields.Name.clientFormatPattern)], fields.Name.isInvalid],
            ["Name2", [fields.Name2.visible && fields.Name2.required ? ew.Validators.required(fields.Name2.caption) : null], fields.Name2.isInvalid]
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
<form name="fdjiadd" id="fdjiadd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="on">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="dji">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->Date->Visible) { // Date ?>
    <div id="r_Date"<?= $Page->Date->rowAttributes() ?>>
        <label id="elh_dji_Date" for="x_Date" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Date->caption() ?><?= $Page->Date->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Date->cellAttributes() ?>>
<span id="el_dji_Date">
<input type="<?= $Page->Date->getInputTextType() ?>" name="x_Date" id="x_Date" data-table="dji" data-field="x_Date" value="<?= $Page->Date->EditValue ?>" placeholder="<?= HtmlEncode($Page->Date->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Date->formatPattern()) ?>"<?= $Page->Date->editAttributes() ?> aria-describedby="x_Date_help">
<?= $Page->Date->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->Date->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->Open->Visible) { // Open ?>
    <div id="r_Open"<?= $Page->Open->rowAttributes() ?>>
        <label id="elh_dji_Open" for="x_Open" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Open->caption() ?><?= $Page->Open->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Open->cellAttributes() ?>>
<span id="el_dji_Open">
<input type="<?= $Page->Open->getInputTextType() ?>" name="x_Open" id="x_Open" data-table="dji" data-field="x_Open" value="<?= $Page->Open->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->Open->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Open->formatPattern()) ?>"<?= $Page->Open->editAttributes() ?> aria-describedby="x_Open_help">
<?= $Page->Open->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->Open->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->High->Visible) { // High ?>
    <div id="r_High"<?= $Page->High->rowAttributes() ?>>
        <label id="elh_dji_High" for="x_High" class="<?= $Page->LeftColumnClass ?>"><?= $Page->High->caption() ?><?= $Page->High->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->High->cellAttributes() ?>>
<span id="el_dji_High">
<input type="<?= $Page->High->getInputTextType() ?>" name="x_High" id="x_High" data-table="dji" data-field="x_High" value="<?= $Page->High->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->High->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->High->formatPattern()) ?>"<?= $Page->High->editAttributes() ?> aria-describedby="x_High_help">
<?= $Page->High->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->High->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->Low->Visible) { // Low ?>
    <div id="r_Low"<?= $Page->Low->rowAttributes() ?>>
        <label id="elh_dji_Low" for="x_Low" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Low->caption() ?><?= $Page->Low->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Low->cellAttributes() ?>>
<span id="el_dji_Low">
<input type="<?= $Page->Low->getInputTextType() ?>" name="x_Low" id="x_Low" data-table="dji" data-field="x_Low" value="<?= $Page->Low->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->Low->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Low->formatPattern()) ?>"<?= $Page->Low->editAttributes() ?> aria-describedby="x_Low_help">
<?= $Page->Low->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->Low->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->Close->Visible) { // Close ?>
    <div id="r_Close"<?= $Page->Close->rowAttributes() ?>>
        <label id="elh_dji_Close" for="x_Close" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Close->caption() ?><?= $Page->Close->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Close->cellAttributes() ?>>
<span id="el_dji_Close">
<input type="<?= $Page->Close->getInputTextType() ?>" name="x_Close" id="x_Close" data-table="dji" data-field="x_Close" value="<?= $Page->Close->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->Close->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Close->formatPattern()) ?>"<?= $Page->Close->editAttributes() ?> aria-describedby="x_Close_help">
<?= $Page->Close->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->Close->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->Volume->Visible) { // Volume ?>
    <div id="r_Volume"<?= $Page->Volume->rowAttributes() ?>>
        <label id="elh_dji_Volume" for="x_Volume" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Volume->caption() ?><?= $Page->Volume->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Volume->cellAttributes() ?>>
<span id="el_dji_Volume">
<input type="<?= $Page->Volume->getInputTextType() ?>" name="x_Volume" id="x_Volume" data-table="dji" data-field="x_Volume" value="<?= $Page->Volume->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->Volume->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Volume->formatPattern()) ?>"<?= $Page->Volume->editAttributes() ?> aria-describedby="x_Volume_help">
<?= $Page->Volume->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->Volume->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->AdjClose->Visible) { // Adj Close ?>
    <div id="r_AdjClose"<?= $Page->AdjClose->rowAttributes() ?>>
        <label id="elh_dji_AdjClose" for="x_AdjClose" class="<?= $Page->LeftColumnClass ?>"><?= $Page->AdjClose->caption() ?><?= $Page->AdjClose->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->AdjClose->cellAttributes() ?>>
<span id="el_dji_AdjClose">
<input type="<?= $Page->AdjClose->getInputTextType() ?>" name="x_AdjClose" id="x_AdjClose" data-table="dji" data-field="x_AdjClose" value="<?= $Page->AdjClose->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->AdjClose->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->AdjClose->formatPattern()) ?>"<?= $Page->AdjClose->editAttributes() ?> aria-describedby="x_AdjClose_help">
<?= $Page->AdjClose->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->AdjClose->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->Name->Visible) { // Name ?>
    <div id="r_Name"<?= $Page->Name->rowAttributes() ?>>
        <label id="elh_dji_Name" for="x_Name" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Name->caption() ?><?= $Page->Name->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Name->cellAttributes() ?>>
<span id="el_dji_Name">
<input type="<?= $Page->Name->getInputTextType() ?>" name="x_Name" id="x_Name" data-table="dji" data-field="x_Name" value="<?= $Page->Name->EditValue ?>" placeholder="<?= HtmlEncode($Page->Name->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Name->formatPattern()) ?>"<?= $Page->Name->editAttributes() ?> aria-describedby="x_Name_help">
<?= $Page->Name->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->Name->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->Name2->Visible) { // Name2 ?>
    <div id="r_Name2"<?= $Page->Name2->rowAttributes() ?>>
        <label id="elh_dji_Name2" for="x_Name2" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Name2->caption() ?><?= $Page->Name2->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Name2->cellAttributes() ?>>
<span id="el_dji_Name2">
<input type="<?= $Page->Name2->getInputTextType() ?>" name="x_Name2" id="x_Name2" data-table="dji" data-field="x_Name2" value="<?= $Page->Name2->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->Name2->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Name2->formatPattern()) ?>"<?= $Page->Name2->editAttributes() ?> aria-describedby="x_Name2_help">
<?= $Page->Name2->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->Name2->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="fdjiadd"><?= $Language->phrase("AddBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="fdjiadd" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
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
    ew.addEventHandlers("dji");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
