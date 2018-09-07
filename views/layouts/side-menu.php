<?php
use yii\helpers\Url;
?>
<div class="sidenav-content">
	<a href="<?= Url::to(['/colaborador']); ?>"><i class="fa fa-user"></i>&nbsp; Colaborador</a>
	<a href="<?= Url::to(['/credor']); ?>"><i class="fa fa-university"></i>&nbsp; Credor</a>
</div>
<!-- ./conteudo do side nav -->
<footer>
	<p>&copy; Thiago You</p>
	<hr>
   	<p><?= date('Y') ?> - v<?= \Yii::$app->params['version']; ?></p>
</footer>
<!-- ./footer do sidenav -->	
		
		
		