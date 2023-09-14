<?php

namespace PHPMaker2023\demo2023;

// Page object
$CategoriesEdit = &$Page;
?>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<main class="edit">
<?php if (!$Page->IsModal) { ?>
<?= $Page->Pager->render() ?>
<?php } ?>
<form name="fcategoriesedit" id="fcategoriesedit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="on">
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { categories: currentTable } });
var currentPageID = ew.PAGE_ID = "edit";
var currentForm;
var fcategoriesedit;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fcategoriesedit")
        .setPageId("edit")

        // Add fields
        .setFields([
            ["CategoryID", [fields.CategoryID.visible && fields.CategoryID.required ? ew.Validators.required(fields.CategoryID.caption) : null], fields.CategoryID.isInvalid],
            ["CategoryName", [fields.CategoryName.visible && fields.CategoryName.required ? ew.Validators.required(fields.CategoryName.caption) : null], fields.CategoryName.isInvalid],
            ["Description", [fields.Description.visible && fields.Description.required ? ew.Validators.required(fields.Description.caption) : null], fields.Description.isInvalid],
            ["Picture", [fields.Picture.visible && fields.Picture.required ? ew.Validators.fileRequired(fields.Picture.caption) : null], fields.Picture.isInvalid],
            ["Icon_17", [fields.Icon_17.visible && fields.Icon_17.required ? ew.Validators.fileRequired(fields.Icon_17.caption) : null], fields.Icon_17.isInvalid],
            ["Icon_25", [fields.Icon_25.visible && fields.Icon_25.required ? ew.Validators.fileRequired(fields.Icon_25.caption) : null], fields.Icon_25.isInvalid]
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
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="categories">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->CategoryID->Visible) { // CategoryID ?>
    <div id="r_CategoryID"<?= $Page->CategoryID->rowAttributes() ?>>
        <label id="elh_categories_CategoryID" class="<?= $Page->LeftColumnClass ?>"><?= $Page->CategoryID->caption() ?><?= $Page->CategoryID->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->CategoryID->cellAttributes() ?>>
<span id="el_categories_CategoryID">
<span<?= $Page->CategoryID->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->CategoryID->getDisplayValue($Page->CategoryID->EditValue))) ?>"></span>
<input type="hidden" data-table="categories" data-field="x_CategoryID" data-hidden="1" name="x_CategoryID" id="x_CategoryID" value="<?= HtmlEncode($Page->CategoryID->CurrentValue) ?>">
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->CategoryName->Visible) { // CategoryName ?>
    <div id="r_CategoryName"<?= $Page->CategoryName->rowAttributes() ?>>
        <label id="elh_categories_CategoryName" for="x_CategoryName" class="<?= $Page->LeftColumnClass ?>"><?= $Page->CategoryName->caption() ?><?= $Page->CategoryName->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->CategoryName->cellAttributes() ?>>
<span id="el_categories_CategoryName">
<input type="<?= $Page->CategoryName->getInputTextType() ?>" name="x_CategoryName" id="x_CategoryName" data-table="categories" data-field="x_CategoryName" value="<?= $Page->CategoryName->EditValue ?>" size="30" maxlength="15" placeholder="<?= HtmlEncode($Page->CategoryName->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->CategoryName->formatPattern()) ?>"<?= $Page->CategoryName->editAttributes() ?> aria-describedby="x_CategoryName_help">
<?= $Page->CategoryName->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->CategoryName->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->Description->Visible) { // Description ?>
    <div id="r_Description"<?= $Page->Description->rowAttributes() ?>>
        <label id="elh_categories_Description" for="x_Description" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Description->caption() ?><?= $Page->Description->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Description->cellAttributes() ?>>
<span id="el_categories_Description">
<textarea data-table="categories" data-field="x_Description" name="x_Description" id="x_Description" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->Description->getPlaceHolder()) ?>"<?= $Page->Description->editAttributes() ?> aria-describedby="x_Description_help"><?= $Page->Description->EditValue ?></textarea>
<?= $Page->Description->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->Description->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->Picture->Visible) { // Picture ?>
    <div id="r_Picture"<?= $Page->Picture->rowAttributes() ?>>
        <label id="elh_categories_Picture" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Picture->caption() ?><?= $Page->Picture->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Picture->cellAttributes() ?>>
<span id="el_categories_Picture">
<div id="fd_x_Picture" class="fileinput-button ew-file-drop-zone">
    <input
        type="file"
        id="x_Picture"
        name="x_Picture"
        class="form-control ew-file-input"
        title="<?= $Page->Picture->title() ?>"
        lang="<?= CurrentLanguageID() ?>"
        data-table="categories"
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
</div></div>
    </div>
<?php } ?>
<?php if ($Page->Icon_17->Visible) { // Icon_17 ?>
    <div id="r_Icon_17"<?= $Page->Icon_17->rowAttributes() ?>>
        <label id="elh_categories_Icon_17" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Icon_17->caption() ?><?= $Page->Icon_17->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Icon_17->cellAttributes() ?>>
<span id="el_categories_Icon_17">
<div id="fd_x_Icon_17" class="fileinput-button ew-file-drop-zone">
    <input
        type="file"
        id="x_Icon_17"
        name="x_Icon_17"
        class="form-control ew-file-input"
        title="<?= $Page->Icon_17->title() ?>"
        lang="<?= CurrentLanguageID() ?>"
        data-table="categories"
        data-field="x_Icon_17"
        data-size="0"
        data-accept-file-types="<?= $Page->Icon_17->acceptFileTypes() ?>"
        data-max-file-size="<?= $Page->Icon_17->UploadMaxFileSize ?>"
        data-max-number-of-files="null"
        data-disable-image-crop="<?= $Page->Icon_17->ImageCropper ? 0 : 1 ?>"
        aria-describedby="x_Icon_17_help"
        <?= ($Page->Icon_17->ReadOnly || $Page->Icon_17->Disabled) ? " disabled" : "" ?>
        <?= $Page->Icon_17->editAttributes() ?>
    >
    <div class="text-muted ew-file-text"><?= $Language->phrase("ChooseFile") ?></div>
    <?= $Page->Icon_17->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->Icon_17->getErrorMessage() ?></div>
</div>
<input type="hidden" name="fn_x_Icon_17" id= "fn_x_Icon_17" value="<?= $Page->Icon_17->Upload->FileName ?>">
<input type="hidden" name="fa_x_Icon_17" id= "fa_x_Icon_17" value="<?= (Post("fa_x_Icon_17") == "0") ? "0" : "1" ?>">
<table id="ft_x_Icon_17" class="table table-sm float-start ew-upload-table"><tbody class="files"></tbody></table>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->Icon_25->Visible) { // Icon_25 ?>
    <div id="r_Icon_25"<?= $Page->Icon_25->rowAttributes() ?>>
        <label id="elh_categories_Icon_25" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Icon_25->caption() ?><?= $Page->Icon_25->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Icon_25->cellAttributes() ?>>
<span id="el_categories_Icon_25">
<div id="fd_x_Icon_25" class="fileinput-button ew-file-drop-zone">
    <input
        type="file"
        id="x_Icon_25"
        name="x_Icon_25"
        class="form-control ew-file-input"
        title="<?= $Page->Icon_25->title() ?>"
        lang="<?= CurrentLanguageID() ?>"
        data-table="categories"
        data-field="x_Icon_25"
        data-size="0"
        data-accept-file-types="<?= $Page->Icon_25->acceptFileTypes() ?>"
        data-max-file-size="<?= $Page->Icon_25->UploadMaxFileSize ?>"
        data-max-number-of-files="null"
        data-disable-image-crop="<?= $Page->Icon_25->ImageCropper ? 0 : 1 ?>"
        aria-describedby="x_Icon_25_help"
        <?= ($Page->Icon_25->ReadOnly || $Page->Icon_25->Disabled) ? " disabled" : "" ?>
        <?= $Page->Icon_25->editAttributes() ?>
    >
    <div class="text-muted ew-file-text"><?= $Language->phrase("ChooseFile") ?></div>
    <?= $Page->Icon_25->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->Icon_25->getErrorMessage() ?></div>
</div>
<input type="hidden" name="fn_x_Icon_25" id= "fn_x_Icon_25" value="<?= $Page->Icon_25->Upload->FileName ?>">
<input type="hidden" name="fa_x_Icon_25" id= "fa_x_Icon_25" value="<?= (Post("fa_x_Icon_25") == "0") ? "0" : "1" ?>">
<table id="ft_x_Icon_25" class="table table-sm float-start ew-upload-table"><tbody class="files"></tbody></table>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?php
    if (in_array("products", explode(",", $Page->getCurrentDetailTable())) && $products->DetailEdit) {
?>
<?php if ($Page->getCurrentDetailTable() != "") { ?>
<h4 class="ew-detail-caption"><?= $Language->tablePhrase("products", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "ProductsGrid.php" ?>
<?php } ?>
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="fcategoriesedit"><?= $Language->phrase("SaveBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="fcategoriesedit" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
<?php } ?>
    </div><!-- /buttons offset -->
<?= $Page->IsModal ? "</template>" : "</div>" ?><!-- /buttons .row -->
</form>
<?php if (!$Page->IsModal) { ?>
<?= $Page->Pager->render() ?>
<?php } ?>
</main>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<script>
// Field event handlers
loadjs.ready("head", function() {
    ew.addEventHandlers("categories");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
