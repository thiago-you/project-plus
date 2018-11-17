<?php
use yii\helpers\Url;
?>
<div class="sidenav-header">
	<img src="<?= \Yii::$app->request->baseUrl; ?>/img/logo.png"/>
    <a class="navbar-brand" href="<?= \Yii::$app->homeUrl; ?>">
    	Maklen <i>RC</i>
    </a>
</div>
<!-- ./brand -->
<div class="sidenav-content">
	<ul>
		<li>
        	<a href="<?= Url::to(['/contrato']); ?>"><i class="fa fa-file-invoice-dollar"></i>&nbsp; Contrato</a>
    	</li>
    	<li>
        	<a href="<?= Url::to(['/colaborador']); ?>"><i class="fa fa-user"></i>&nbsp; Colaborador</a>
    	</li>
	</ul>
</div>
<!-- ./conteudo do side nav -->
<footer>
	<p>
		&copy; Maklen RC - <?= date('Y') ?> - v<?= \Yii::$app->params['version']; ?>
	</p>
</footer>
<!-- ./footer do sidenav -->	
		
		
		