<?php
$this->title = $title ? $title : 'Erro Inesperado';
?>
<div class="site-error">
    <h1><?= $this->title; ?></h1>
	<!-- ./titulo do erro -->
	<br>
    <div class="alert alert-<?= $label ? $label : 'danger'; ?> font16">
        <br>
        <?= $message; ?>
        <br><br>
    </div>
    <!-- ./mensagem de erro -->
</div>
