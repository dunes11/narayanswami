<?php

namespace PHPMaker2023\demo2023;

// Page object
$News = &$Page;
?>
<?php
$Page->showMessage();
?>
<div class="card">
	<div class="card-header">
		<h5 class="m-0">Latest News</h5>
	</div>
	<div class="card-body">
		<h6 class="card-title">2022/9/12 - PHPMaker 2023 Released</h6>
		<p class="card-text">For more information, please visit PHPMaker website.</p>
		<a href="https://phpmaker.dev" class="btn btn-primary">Go to PHPMaker website</a>
	</div>
</div>

<?= GetDebugMessage() ?>
