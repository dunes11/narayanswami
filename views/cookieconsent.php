<?php namespace PHPMaker2023\demo2023; ?>
<?php if (!CanTrackCookie()) { ?>
<div id="cookie-consent" class="d-none">
    <div class="me-3 mb-3"><?= $Language->phrase("CookieConsentSummary") ?></div>
    <div class="text-nowrap">
        <button type="button" class="<?= Config("COOKIE_CONSENT_BUTTON_CLASS") ?>" data-action="privacy"><?= $Language->phrase("LearnMore") ?></button>
        <button type="button" class="<?= Config("COOKIE_CONSENT_BUTTON_CLASS") ?>" data-cookie-string="<?= HtmlEncode(CreateConsentCookie()) ?>"><?= $Language->phrase("Accept") ?></button>
    </div>
</div>
<script>
loadjs.ready("load", function() {
    let $ = jQuery;
    let $toast = ew.toast({
        class: "ew-toast <?= Config("COOKIE_CONSENT_CLASS") ?>",
        title: ew.language.phrase("CookieConsentTitle"),
        body: $("#cookie-consent").html(),
        close: true,
        autohide: false,
        delay: 0
    });
     // Accept button
    $toast.find("button[data-cookie-string]").on("click", function(e) {
        document.cookie = $(e.target).data("cookie-string");
        $toast.toast("hide");
    });
    // Learn more button
    $toast.find("button[data-action]").on("click", function(e) {
        window.location = ew.PATH_BASE + $(e.target).data("action");
    });
});
</script>
<?php } ?>
