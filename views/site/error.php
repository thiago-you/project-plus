<?php
$this->title = $title;
?>
<div class="site-error">
    <h1><?= $title; ?></h1>
	<!-- ./titulo do erro -->
	<br>
    <div class="alert alert-<?= $label; ?> font16">
        <br>
        <?= $message; ?>
        <br><br>
    </div>
    <br><br>
    <!-- ./mensagem de erro -->
    <p class="text-center font16">
        <i class="fa fa-wrench"></i>&nbsp; A aplicação ainda esta em desenvolvimento e pode apresentar instabilidade e erros inesperados.
    </p>
    <!-- ./mensagem do ambiente de dev -->
</div>
