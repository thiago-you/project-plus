<?php
use yii\helpers\Url;
?>
<div class="sidenav-content">
	<a href="<?= Url::to(['/contrato']); ?>"><i class="fa fa-file-invoice-dollar"></i>&nbsp; Contrato</a>
	<a href="<?= Url::to(['/cliente']); ?>"><i class="fa fa-users"></i>&nbsp; Cliente</a>
	<a href="<?= Url::to(['/colaborador']); ?>"><i class="fa fa-user"></i>&nbsp; Colaborador</a>
	<a href="<?= Url::to(['/carteira']); ?>"><i class="fa fa-university"></i>&nbsp; Carteira</a>
	<a href="<?= Url::to(['/site/importacao']); ?>"><i class="fa fa-file-upload"></i>&nbsp; Importação</a>
</div>
<!-- ./conteudo do side nav -->
<footer>
	<p>&copy; Maklen RC</p>
	<hr>
   	<p><?= date('Y') ?> - v<?= \Yii::$app->params['version']; ?></p>
</footer>
<!-- ./footer do sidenav -->	
		
		
		